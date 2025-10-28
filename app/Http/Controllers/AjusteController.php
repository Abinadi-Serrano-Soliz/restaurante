<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\DetallePedido;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Carbon\Carbon;


class AjusteController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:ajustes.listar', only: ['index']),
            new Middleware('permission:ajustes.crear', only: ['create', 'store']),
            new Middleware('permission:ajustes.ver', only: ['show','descargarPdf']),
            new Middleware('permission:ajustes.editar', only: ['edit', 'update']),
            new Middleware('permission:ajustes.eliminar', only: ['destroy']),
        ];
    }

    public function index()
    {
        $ajustes = Ajuste::with(['detalle_pedidos.pedidos', 'users'])->get();
        return view('ajustes.index', compact('ajustes'));
    }

    public function create()
    {
        // Cargar todos los detalles con su pedido y menú
        $detallePedidos = DetallePedido::with(['pedidos', 'menus'])->get();

        return view('ajustes.create', compact('detallePedidos'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'glosa' => 'nullable|string',
            'reembolso' => 'nullable|numeric|min:0',
            'tipo' => 'required|in:INGRESO,EGRESO',
            'id_detallePedido' => 'required|exists:detalle_pedidos,id',
            'cantidad' => 'required|numeric|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|',
        ]);
        
        // Obtener detalle del pedido con relaciones
        $detalle = DetallePedido::with('menus.detalle_menus.producto_almacenes')->find($request->id_detallePedido);

          //para controlar que sea menor o igual a la cantidad de detallespedidos
        $cantidadPedido = $detalle->cantidad_pedido;
        if ($request->cantidad > $detalle->cantidad_pedido) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La cantidad del ajuste no puede ser mayor a la cantidad del pedido.');
        }
       
        // Ajuste de stock y reembolso según tipo
        if ($request->tipo === 'INGRESO') {
        // Aumentar el stock_menu del menú según la cantidad del ajuste
        $detalle->menus->increment('stock_menu', $request->cantidad);

            // Recorrer cada ingrediente del menú y aumentar su stock_actual
            foreach ($detalle->menus->detalle_menus as $ingrediente) {
                $producto = $ingrediente->producto_almacenes;
            
                if ($producto) {
                    // Calcular la cantidad que se devuelve al almacén
                    $cantidadDevuelta = $ingrediente->cantidad * $request->cantidad;
                    
                    // Actualizar el stock_actual
                    $producto->increment('stock_actual', $cantidadDevuelta);
                }
            }
        }
         // 👉 Subir imagen si existe
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('comprobantes', 'public');
            }
       
        // Crear registro de ajuste
        Ajuste::create([
            'fecha_hora' => Carbon::now(),
            'glosa' => $request->glosa,
            'reembolso' => $request->reembolso,
            'tipo' => $request->tipo,
            'imagen' => $imagenPath,
            'cantidad' => $request->cantidad,
            'id_detallePedido' => $detalle->id,
            'id_user' => Auth::id(),
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->route('ajustes.index')->with('success', 'Ajuste registrado correctamente.');
    }

    public function edit($id)
    {
        // Buscar el ajuste con sus relaciones
        $ajuste = Ajuste::with(['detalle_pedidos.menus', 'detalle_pedidos.pedidos', 'users'])
            ->findOrFail($id);

        // Cargar todos los detalles de pedidos para el select
        $detallePedidos = DetallePedido::with(['pedidos', 'menus'])->get();

        return view('ajustes.edit', compact('ajuste', 'detallePedidos'));
    }

    public function update(Request $request, $id)
    {
       
        $request->validate([
        'glosa' => 'nullable|string',
        'reembolso' => 'nullable|numeric|min:0',
        'tipo' => 'required|in:INGRESO,EGRESO',
        'id_detallePedido' => 'required|exists:detalle_pedidos,id',
        'cantidad' => 'required|numeric|min:1',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $ajuste = Ajuste::findOrFail($id);

    // Valores anteriores
    $detalleAnterior = DetallePedido::with('menus.detalle_menus.producto_almacenes')
        ->find($ajuste->id_detallePedido);
    $tipoAnterior = $ajuste->tipo;
    $cantidadAnterior = $ajuste->cantidad;

    // Valores nuevos
    $detalleNuevo = DetallePedido::with('menus.detalle_menus.producto_almacenes')
        ->find($request->id_detallePedido);
    $tipoNuevo = $request->tipo;
    $cantidadNueva = $request->cantidad;

    // Validar cantidad
    if ($cantidadNueva > $detalleNuevo->cantidad_pedido) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'La cantidad del ajuste no puede ser mayor a la cantidad del pedido.');
    }
    // 🔹 Si cambió el detalle y el anterior era INGRESO, revertimos solo el anterior
    if ($detalleAnterior->id != $detalleNuevo->id && $tipoAnterior === 'INGRESO') {
        $detalleAnterior->menus->decrement('stock_menu', $cantidadAnterior);
        foreach ($detalleAnterior->menus->detalle_menus as $ingrediente) {
            $producto = $ingrediente->producto_almacenes;
            if ($producto) {
                $producto->decrement('stock_actual', $ingrediente->cantidad * $cantidadAnterior);
            }
        }
    }
    /**
     * Calcular el factor de ajuste
     * Si el tipo no cambió:
     *   - INGRESO: factor = nueva - anterior
     *   - EGRESO: factor = anterior - nueva (porque restamos stock)
     * Si cambió tipo:
     *   - Factor = +cantidadNueva si INGRESO
     *   - Factor = -cantidadNueva si EGRESO
     */
    // 🔹 Aplicar ajuste al nuevo detalle según tipo
    if ($tipoNuevo === 'INGRESO') {
        $detalleNuevo->menus->increment('stock_menu', $cantidadNueva);
        foreach ($detalleNuevo->menus->detalle_menus as $ingrediente) {
            $producto = $ingrediente->producto_almacenes;
            if ($producto) {
                $producto->increment('stock_actual', $ingrediente->cantidad * $cantidadNueva);
            }
        }
    } elseif ($tipoNuevo === 'EGRESO') {
        $detalleNuevo->menus->decrement('stock_menu', $cantidadNueva);
        foreach ($detalleNuevo->menus->detalle_menus as $ingrediente) {
            $producto = $ingrediente->producto_almacenes;
            if ($producto) {
                $producto->decrement('stock_actual', $ingrediente->cantidad * $cantidadNueva);
            }
        }
    }

    // Manejar imagen
    $imagenPath = $ajuste->imagen;
    if ($request->hasFile('imagen')) {
        if ($ajuste->imagen && Storage::disk('public')->exists($ajuste->imagen)) {
            Storage::disk('public')->delete($ajuste->imagen);
        }
        $imagenPath = $request->file('imagen')->store('comprobantes', 'public');
    }

    // Actualizar ajuste
    $ajuste->update([
        'fecha_hora' => Carbon::now(),
        'glosa' => $request->glosa,
        'reembolso' => $request->reembolso,
        'tipo' => $tipoNuevo,
        'imagen' => $imagenPath,
        'cantidad' => $cantidadNueva,
        'id_detallePedido' => $detalleNuevo->id,
        'id_user' => Auth::id(),
    ]);

    return redirect()->route('ajustes.index')->with('success', 'Ajuste actualizado correctamente.');
    }


    public function show($id)
    {
        $ajuste = Ajuste::with([
            'detalle_pedidos.pedidos.clientes',
            'detalle_pedidos.menus.detalle_menus.producto_almacenes',
            'users'
        ])->findOrFail($id);

        return view('ajustes.show', compact('ajuste'));
    }

    public function descargarPDF($id)
    {
        $ajuste = Ajuste::with([
            'detalle_pedidos.pedidos.clientes',
            'detalle_pedidos.menus.detalle_menus.producto_almacenes.producto',
            'users'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('ajustes.pdf', compact('ajuste'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('ajuste_'.$ajuste->id.'.pdf');
    }



    public function destroy( $id)
    {
        // Buscar el ajuste y el detalle relacionados
        $ajuste = Ajuste::findOrFail($id);
        $detalle = DetallePedido::with('menus.detalle_menus.producto_almacenes')
            ->findOrFail($ajuste->id_detallePedido);

        // Revertir efecto del ajuste
        if ($ajuste->tipo === 'INGRESO') {
            // Disminuir lo que se había aumentado
            $detalle->menus->decrement('stock_menu', $ajuste->cantidad);
            foreach ($detalle->menus->detalle_menus as $ingrediente) {
                $producto = $ingrediente->producto_almacenes;
                if ($producto) {
                    $producto->decrement('stock_actual', $ingrediente->cantidad * $ajuste->cantidad);
                }
            }
        }

        // Eliminar la imagen si existe
        if ($ajuste->imagen && Storage::disk('public')->exists($ajuste->imagen)) {
            Storage::disk('public')->delete($ajuste->imagen);
        }

        // Finalmente eliminar el ajuste
        $ajuste->delete();

        return redirect()->route('ajustes.index')->with('success', 'Ajuste eliminado correctamente.');

    }
}
