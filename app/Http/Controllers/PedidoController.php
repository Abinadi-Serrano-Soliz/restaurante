<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleMenu;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Menu;
use App\Models\Pago;
use App\Models\ProductoAlmacene;
use App\Models\Repartidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Jobs\DeletePendingPedido;         //para eliminar si es que no poagan
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PedidoController extends Controller implements HasMiddleware
{
    private $libelulaAppKey;
    private $libelulaApiUrl;
    private $libelulaCallbackUrl;
   
    public function __construct()
    {
        $this->libelulaAppKey = env('LIBELULA_APPKEY');
        $this->libelulaApiUrl = env('LIBELULA_API_URL');
        $this->libelulaCallbackUrl = env('LIBELULA_CALLBACK_URL');
    }

     public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['callbackLibelula']), // ← Excluir callback
            new Middleware('permission:pedidos.listar', only: ['index']),
            new Middleware('permission:pedidos.crear', only: ['create', 'store']),
            new Middleware('permission:pedidos.ver', only: ['show']),
            new Middleware('permission:pedidos.editar', only: ['edit', 'update']),
            new Middleware('permission:pedidos.eliminar', only: ['destroy']),
        ];
    }

   

    /**
     * Muestra todos los pedidos
     */
    public function index()
    {
        $pedidos = Pedido::with(['clientes', 'repartidors', 'users', 'menus', 'pagos'])->get();
        return view('pedidos.index', compact('pedidos'));
       
    }

    public function create()
    {
        $clientes = Cliente::all();
        $repartidores = Repartidor::where('estado','0')->get();
        $menus = Menu::where('estado','1')->get();

        return view('pedidos.create', compact('clientes', 'repartidores', 'menus'));
    }

    /**
     * Guarda un nuevo pedido con detalles
     */
    public function store(Request $request)
    {
        try {
        DB::beginTransaction();

        // === 1. Crear Pedido ===
        $pedido = Pedido::create([
            'id_cliente' => $request->id_cliente,
            'id_repartidor' => $request->id_repartidor,
            'id_user' => Auth::id(),
            'direccion_entrega' => $request->direccion_entrega,
            'estado' => 0,
            'fecha_hora_pedido' => now(),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'monto_total' => $request->monto_total,
            'observaciones' => $request->observaciones,
        ]);

        
        // === 2. Insertar detalles y actualizar stock ===
        foreach ($request->menus as $item) {
            
            $subtotal = $item['cantidad_pedido'] * $item['precio_unitario'];
            $menu = Menu::findOrFail($item['id_menu']);

            if ($menu->stock_menu < $item['cantidad_pedido']) {
                throw new \Exception("No hay suficiente stock para el menú: {$menu->nombre}");
            }

            $subtotal = $item['cantidad_pedido'] * $item['precio_unitario'];

            DetallePedido::create([
                'id_pedido' => $pedido->id,
                'id_menu' => $item['id_menu'],
                'cantidad_pedido' => $item['cantidad_pedido'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal' => $subtotal,
                'estado' => 1,
            ]);
        }

        

        $cliente = Cliente::findOrFail($request->id_cliente);

        $lineas_detalle_deuda = [];
        foreach ($request->menus as $item) {
            $menu = Menu::findOrFail($item['id_menu']);
            $lineas_detalle_deuda[] = [
                'concepto' => $menu->nombre,
                'cantidad' => (int)$item['cantidad_pedido'],
                'costo_unitario' => (float)$item['precio_unitario'],
                'descuento_unitario' => 0,
                'codigo_producto' => $menu->id
            ];
        }

        $payload = [
            'appkey' => env('LIBELULA_APPKEY'),
            'email_cliente' => $cliente->correo ?? 'cliente@ejemplo.com',
            'identificador' => 'PED-' . $pedido->id,
            'callback_url' => env('LIBELULA_CALLBACK_URL'),
            'url_retorno' => route('pedidos.index'),
            'descripcion' => 'Pedido de menús - ' . $cliente->nombre,
            'nombre_cliente' => $cliente->nombre,
            'apellido_cliente' => $cliente->apellidos,
            'numero_documento' => $cliente->ci,
            'codigo_tipo_documento' => 'CI',
            'moneda' => 'BOB',
            'emite_factura' => true,
            'tipo_factura' => 'Servicios',
            'lineas_detalle_deuda' => $lineas_detalle_deuda,
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post(env('LIBELULA_API_URL') . '/rest/deuda/registrar', [
            'json' => $payload,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        $resultado = json_decode($response->getBody()->getContents(), true);

        $qr_url = $resultado['qr_simple_url'] ?? null;
        $id_transaccion = 'PED-' . $pedido->id;
        $identificador_deuda = $resultado['identificador_deuda'] ?? null;
                // === 4. Crear registro de pago pendiente ===
                Pago::create([
                    'id_pedido' => $pedido->id,
                    'transaccion_id' => $id_transaccion,
                    'referencia' => $identificador_deuda,
                    'monto_total' => $pedido->monto_total,
                    'metodo_pago' => 'Libélula',
                    'canal_pago' => 'QR/Pasarela',
                    'estado_pago' => 'PENDIENTE',
                    'fecha_hora_pago' => now(),
                    'respuesta_libelula' => json_encode($resultado),
                ]);

                                // Programar eliminación si no se paga en 3 minutos
                DeletePendingPedido::dispatch($pedido->id)->delay(now()->addMinutes(3));

                DB::commit();

                return response()->json([
                    'success' => true,
                    'mensaje' => 'Pedido creado exitosamente.',
                    'qr_url' => $qr_url,
                    'monto_total' => $pedido->monto_total,
                    'id_pedido' => $pedido->id,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al crear pedido: ' . $e->getMessage());
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
    }

    //PARA GENERAR EL QR DE PAGO CON LIBELULA
    public function generarQR(Request $request)
    {
        try {
            // Calcular monto total
            $monto_total = 0;
            $lineas_detalle_deuda = [];

            foreach ($request->menus as $item) {
                $menu = Menu::findOrFail($item['id_menu']);
                $subtotal = $item['cantidad_pedido'] * $item['precio_unitario'];
                $monto_total += $subtotal;

                // Preparar líneas de detalle según formato Libélula
                $lineas_detalle_deuda[] = [
                    'concepto' => $menu->nombre,
                    'cantidad' => (int)$item['cantidad_pedido'],
                    'costo_unitario' => (float)$item['precio_unitario'],
                    'descuento_unitario' => 0,
                    'codigo_producto' => $menu->id
                ];
            }

            // Obtener datos del cliente
            $cliente = Cliente::findOrFail($request->id_cliente);

            // Generar identificador único para la deuda
            $identificador_deuda = 'PEDIDO-' . time() . '-' . uniqid();

            // Preparar payload para Libélula
            $payload = [
                'appkey' => $this->libelulaAppKey,
                'email_cliente' => $cliente->correo ?? 'cliente@ejemplo.com', // Asegúrate que exista el campo email
                'identificador' => $identificador_deuda,
                'callback_url' => $this->libelulaCallbackUrl,
                'url_retorno' => route('pedidos.index'),
                'descripcion' => 'Pedido de menús - ' . $cliente->nombre,
                'nombre_cliente' => $cliente->nombre,
                'apellido_cliente' => $cliente->apellidos,
                'numero_documento' => $cliente->ci,
                'codigo_tipo_documento' => 'CI',
                'moneda' => 'BOB',
                'emite_factura' => true,
                'tipo_factura' => 'Servicios',
                'lineas_detalle_deuda' => $lineas_detalle_deuda
            ];

            // Llamar al API de Libélula
            $client = new Client();
            $response = $client->post($this->libelulaApiUrl . '/rest/deuda/registrar', [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $resultado = json_decode($response->getBody()->getContents(), true);

            if (!$resultado['error']) {
                return response()->json([
                    'success' => true,
                    'id_transaccion' => $resultado['id_transaccion'],
                    'url_pasarela' => $resultado['url_pasarela_pagos'],
                    'qr_url' => $resultado['qr_simple_url'] ?? null,
                    'monto_total' => $monto_total,
                    'identificador_deuda' => $identificador_deuda
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $resultado['mensaje']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error generando QR Libélula: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al generar QR: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmarPago(Request $request)
    {
         $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'id_repartidor' => 'nullable|exists:repartidors,id',
            'direccion_entrega' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'menus' => 'required|array|min:1',
            'menus.*.id_menu' => 'required|exists:menus,id',
            'menus.*.cantidad_pedido' => 'required|integer|min:1',
            'menus.*.precio_unitario' => 'required|numeric|min:0',
        ],[
            'menus.required' => 'Debe seleccionar al menos un menú.',
            'menus.*.id_menu.required' => 'Debe seleccionar un menú válido.',
            'menus.*.id_menu.exists' => 'El menú seleccionado no existe.',
            'menus.*.cantidad_pedido.required' => 'Debe ingresar una cantidad.',
            'menus.*.cantidad_pedido.integer' => 'La cantidad debe ser un número entero.',
            'menus.*.cantidad_pedido.min' => 'La cantidad debe ser al menos 1.',
            'menus.*.precio_unitario.required' => 'Debe existir un precio unitario.',
            'menus.*.precio_unitario.numeric' => 'El precio unitario debe ser numérico.',
            'menus.*.precio_unitario.min' => 'El precio unitario no puede ser negativo.'
        ]);

        try {
            DB::beginTransaction();

        
            // Crear el pedido
            $pedido = Pedido::create([
                'id_cliente' => $request->id_cliente,
                'id_repartidor' => $request->id_repartidor,
                'id_user' => Auth::id(),
                'direccion_entrega' => $request->direccion_entrega,
                'estado' => 0,
                'fecha_hora_pedido' => Carbon::now(),
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'monto_total' => $request->monto_total,
                'observaciones' => $request->observaciones,
            ]);

            // Insertar detalles y descontar stock
            foreach ($request->menus as $item) {
                $menu = Menu::findOrFail($item['id_menu']);
                
                if ($menu->stock_menu < $item['cantidad_pedido']) {
                    throw new \Exception("No hay suficiente stock para el menú: {$menu->nombre}");
                }

                $menu->stock_menu -= $item['cantidad_pedido'];
                if ($menu->stock_menu <= 0) {
                    $menu->estado = 0;
                }
                $menu->save();

                DetallePedido::create([
                    'id_pedido' => $pedido->id,
                    'id_menu' => $item['id_menu'],
                    'cantidad_pedido' => $item['cantidad_pedido'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $item['cantidad_pedido'] * $item['precio_unitario'],
                    'estado' => 1,
                ]);

                // Descontar ingredientes
                $detallesMenu = DetalleMenu::where('id_menu', $menu->id)->get();
                foreach ($detallesMenu as $detalle) {
                    $producto = ProductoAlmacene::findOrFail($detalle->id_ProductoAlmacen);
                    $cantidadDescontar = $detalle->cantidad * $item['cantidad_pedido'];

                    if ($producto->stock_actual < $cantidadDescontar) {
                        throw new \Exception("No hay suficiente stock del producto: {$producto->nombre}");
                    }

                    $producto->stock_actual -= $cantidadDescontar;
                    if ($producto->stock_actual <= 0) {
                        $producto->stock_actual = 0;
                        $producto->estado = 0;
                    }
                    $producto->save();
                }
            }

            // Crear registro de pago pendiente
            Pago::create([
                'id_pedido' => $pedido->id,
                'transaccion_id' => $request->id_transaccion,
                'referencia' => $request->identificador_deuda,
                'monto_total' => $request->monto_total,
                'metodo_pago' => 'Libélula',
                'canal_pago' => 'QR/Pasarela',
                'estado_pago' => 'PENDIENTE',
                'fecha_hora_pago' => Carbon::now(),
                'respuesta_libelula' => json_encode(['id_transaccion' => $request->id_transaccion])
            ]);

            DB::commit();

           return back()->with('success', 'Pedido registrado ahora puedes Generar el QR.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirmando pago: ' . $e->getMessage());
             return back()->with('error', 'Error al registrar Pedido.');
        }
    }
    
     public function callbackLibelula(Request $request)
    {
 
    try {
        Log::info('📥 ===== CALLBACK LIBÉLULA RECIBIDO =====', [
            'method' => $request->method(),
            'all_params' => $request->all(),
        ]);

        $transaction_id = $request->input('transaction_id');
        $invoice_id = $request->input('invoice_id');
        $invoice_url = $request->input('invoice_url');
        $payment_date = $request->input('payment_date');
        $payment_method = $request->input('payment_method');

        if (!$transaction_id) {
            Log::warning('⚠️ Transaction ID no proporcionado');
            return response()->json(['error' => 'Transaction ID requerido'], 400);
        }

        Log::info('🔍 Buscando pago con transaction_id: ' . $transaction_id);

        // BUSCAR POR MÚLTIPLES CAMPOS
        // Libélula puede enviar el identificador_deuda que nosotros generamos
        $pago = Pago::where(function($query) use ($transaction_id) {
                $query->where('transaccion_id', $transaction_id)
                      ->orWhere('referencia', $transaction_id);
            })
            ->where('estado_pago', 'PENDIENTE')
            ->first();

        // Si no lo encuentra, buscar en el campo respuesta_libelula (JSON)
        if (!$pago) {
            Log::info('🔍 Buscando en respuesta_libelula JSON...');
            
            $pago = Pago::where('estado_pago', 'PENDIENTE')
                ->where(function($query) use ($transaction_id) {
                    $query->where('respuesta_libelula', 'like', '%' . $transaction_id . '%')
                          ->orWhere('referencia', 'like', '%' . $transaction_id . '%');
                })
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if (!$pago) {
            Log::warning('⚠️ Pago no encontrado', [
                'transaction_id_buscado' => $transaction_id,
                'pagos_pendientes' => Pago::where('estado_pago', 'PENDIENTE')->get(['id', 'transaccion_id', 'referencia', 'created_at'])
            ]);
            
            return response()->json([
                'error' => 'Pago no encontrado',
                'transaction_id' => $transaction_id
            ], 404);
        }

        Log::info('🟢 Pago encontrado', [
            'pago_id' => $pago->id,
            'pedido_id' => $pago->id_pedido,
            'transaccion_id_guardado' => $pago->transaccion_id,
            'referencia_guardada' => $pago->referencia
        ]);

        // Actualizar el pago
        $respuestaLibelula = [
            'callback_recibido' => true,
            'transaction_id' => $transaction_id,
            'invoice_id' => $invoice_id,
            'invoice_url' => $invoice_url,
            'payment_date' => $payment_date,
            'payment_method' => $payment_method,
            'fecha_callback' => Carbon::now()->toDateTimeString(),
            'all_data' => $request->all()
        ];

        $pago->update([
            'estado_pago' => 'COMPLETADO',
            'metodo_pago' => $payment_method ?? 'Libélula',
            'fecha_hora_pago' => Carbon::parse($payment_date ?? now()),
            'respuesta_libelula' => json_encode($respuestaLibelula)
        ]);

        Log::info('✅ Pago actualizado a COMPLETADO');

        // Obtener el pedido relacionado con sus menús y productos
        $pedido = Pedido::with(['menus.producto_almacenes'])->findOrFail($pago->id_pedido);

            // 🔹 Recuperar y actualizar el estado del repartidor
    if ($pedido->id_repartidor !== null) {
        $repartidor = Repartidor::find($pedido->id_repartidor);

        if ($repartidor) {
            $repartidor->estado = 1; // ocupado
            $repartidor->save();

            Log::info('🚴 Repartidor actualizado a ocupado', [
                'repartidor_id' => $repartidor->id,
                'nombre' => $repartidor->nombre ?? 'N/A'
            ]);
        } 
    } 


        DB::beginTransaction();

        // Agrupar todas las cantidades por menú
$menuCantidades = [];

foreach ($pedido->menus as $menu) {
    $idMenu = $menu->id;
    $menuCantidades[$idMenu] = ($menuCantidades[$idMenu] ?? 0) + $menu->pivot->cantidad_pedido;
}

// Descontar el stock acumulado por cada menú
foreach ($menuCantidades as $idMenu => $cantidadTotal) {
    $menu = Menu::with('producto_almacenes')->findOrFail($idMenu);

    // Descontar stock del menú
    if ($menu->stock_menu < $cantidadTotal) {
        throw new \Exception("No hay suficiente stock del menú {$menu->nombre}");
    }

    $menu->stock_menu -= $cantidadTotal;
    if ($menu->stock_menu <= 0) {
        $menu->estado = 0;
    }
    $menu->save();

    // Descontar stock de los productos asociados al menú
    foreach ($menu->producto_almacenes as $producto) {
        $detalleMenu = DetalleMenu::where('id_menu', $menu->id)
                                  ->where('id_ProductoAlmacen', $producto->id)
                                  ->first();

        if (!$detalleMenu) continue;

        $cantidadDescontar = $detalleMenu->cantidad * $cantidadTotal;

        if ($producto->stock_actual < $cantidadDescontar) {
            throw new \Exception("No hay suficiente stock del producto {$producto->nombre}");
        }

        $producto->stock_actual -= $cantidadDescontar;
        if ($producto->stock_actual <= 0) {
            $producto->stock_actual = 0;
            $producto->estado = 0;
        }
        $producto->save();
    }
}

        DB::commit();

        Log::info('🎉 ===== CALLBACK PROCESADO EXITOSAMENTE =====');

        return response()->json([
            'success' => true,
            'mensaje' => 'Pago confirmado correctamente',
            'pedido_id' => $pago->id_pedido
        ], 200);

    } catch (\Exception $e) {
        Log::error('🔴 ERROR EN CALLBACK', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Error procesando callback',
            'mensaje' => $e->getMessage()
        ], 500);
    }
    }
    /**
     * Muestra un pedido específico
     */
    public function show($id)
    {
        $pedido = Pedido::with(['clientes', 'repartidors', 'users', 'menus', 'pagos'])
            ->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Actualiza el estado del pedido (por ejemplo: enviado o entregado)
     */
    public function edit($id)
    {
        $pedido = Pedido::with(['menus'])->findOrFail($id); // cargamos el pedido con sus menús
        $clientes = Cliente::all();
        $repartidores = Repartidor::all();
        $menus = Menu::all();

        return view('pedidos.edit', compact('pedido', 'clientes', 'repartidores', 'menus'));
    }

    public function update(Request $request,$id)
    {
         $request->validate([
        'id_cliente' => 'required|exists:clientes,id',
        'id_repartidor' => 'nullable|exists:repartidors,id',
        'direccion_entrega' => 'required|string|max:255',
        'observaciones' => 'nullable|string',
        'estado' => 'required',
        'menus' => 'required|array|min:1',
        'menus.*.id_menu' => 'required|exists:menus,id',
        'menus.*.cantidad_pedido' => 'required|integer|min:1',
        'menus.*.precio_unitario' => 'required|numeric|min:0',
        ]);

         try {
            DB::beginTransaction();

        $pedido = Pedido::findOrFail($id);

        // Actualizamos los datos principales
        $pedido->update([
            'id_cliente' => $request->id_cliente,
            'id_repartidor' => $request->id_repartidor,
            'direccion_entrega' => $request->direccion_entrega,
            'observaciones' => $request->observaciones,
            'estado' => $request->estado,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);
        if ($pedido->estado == 2 && $pedido->id_repartidor) {
            $repartidor = \App\Models\Repartidor::find($pedido->id_repartidor);
            if ($repartidor) {
                $repartidor->estado = 0;
                $repartidor->save();
            }
        }
        if ($pedido->estado == 0) {
            $repartidor = \App\Models\Repartidor::find($pedido->id_repartidor);
            if ($repartidor) {
                $repartidor->estado = 1;
                $repartidor->save();
            }
        }

        // Primero, restauramos stock de los menús antiguos
        foreach ($pedido->menus as $detalle) {
            $menu = Menu::find($detalle->id);
            $menu->stock_menu += $detalle->pivot->cantidad_pedido;
            $menu->save();
        }

        // Eliminamos los detalles antiguos
        $pedido->menus()->detach();

        // Insertamos los nuevos detalles y descontamos stock
        $monto_total = 0;
        foreach ($request->menus as $item) {
            $menu = Menu::findOrFail($item['id_menu']);
            if ($menu->stock_menu < $item['cantidad_pedido']) {
                throw new \Exception("No hay suficiente stock para el menú: {$menu->nombre}");
            }

            $menu->stock_menu -= $item['cantidad_pedido'];
            if ($menu->stock_menu <= 0) {
                $menu->stock_menu = 0;
                $menu->estado = 0;
            }
            $menu->save();

            $pedido->menus()->attach($menu->id, [
                'cantidad_pedido' => $item['cantidad_pedido'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal' => $item['cantidad_pedido'] * $item['precio_unitario'],
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $monto_total += $item['cantidad_pedido'] * $item['precio_unitario'];
        }

        // Actualizamos el monto total
        $pedido->monto_total = $monto_total;
        $pedido->save();

            DB::commit();
            return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un pedido
     */
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

      return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado correctamente.');  
    }
}
