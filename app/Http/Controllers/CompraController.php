<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\ProductoAlmacene;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompraController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:compras.listar', only: ['index']),
            new Middleware('permission:compras.crear', only: ['create', 'store']),
            new Middleware('permission:compras.ver', only: ['show']),
            new Middleware('permission:compras.editar', only: ['edit', 'update']),
            new Middleware('permission:compras.eliminar', only: ['destroy']),
        ];
    }
    
    public function index()
    {
        // Trae todas las compras con su proveedor y productos relacionados
        $compras = Compra::with(['proveedor', 'producto_almacenes'])->get();

        return view('compras.index', compact('compras'));
    }

   
    public function create()
    {
        $proveedores = Proveedor::all();
        $productos_almacen = ProductoAlmacene::with(['producto', 'almacen'])->get();

        return view('compras.create', compact('proveedores', 'productos_almacen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
    // para crear la compra
    $compra = Compra::create([
        'id_proveedor' => $request->id_proveedor,
        'fecha_compra' => $request->fecha_compra,
        'monto_total' => $request->monto_total,
    ]);

    // Registrar los productos en la tabla intermedia
    foreach ($request->productos as $producto) {
        $compra->producto_almacenes()->attach($producto['id_ProductoAlmacen'], [
            'cantidad_compra' => $producto['cantidad_compra'],
            'precio_unitario' => $producto['precio_unitario'],
            'subtotal' => $producto['subtotal'],
        ]);

        // Actualizar stock del producto
        $prod = ProductoAlmacene::find($producto['id_ProductoAlmacen']);
        $prod->stock_actual += $producto['cantidad_compra'];
        $prod->save();
    }

            //Si todo fue bien, confirmamos los cambios
            DB::commit();

            return redirect()->route('compras.index')->with('success','Compra creada correctamente');

        } catch (\Exception $e) {
            //  Si algo falla, se revierte todo
            DB::rollBack();
            return with('error','Erro al crear  la compra');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $compra = Compra::with(['proveedor', 'producto_almacenes.producto', 'producto_almacenes.almacen'])->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $compra = Compra::with([
        'producto_almacenes.producto',  // carga los datos del producto
        'producto_almacenes.almacen'    // carga el almacén
        ])->findOrFail($id);

        $proveedores = Proveedor::all();
        $productos_almacen = ProductoAlmacene::with(['producto', 'almacen'])->get();

        return view('compras.edit', compact('compra', 'proveedores', 'productos_almacen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         DB::beginTransaction();

    try {
            $compra = Compra::with('producto_almacenes')->findOrFail($id);

            // Actualizar datos de la compra
            $compra->update([
                'id_proveedor' => $request->id_proveedor,
                'fecha_compra' => $request->fecha_compra,
                'monto_total' => $request->monto_total,

            ]);

            // Guardar cambios en productos
            // Primero, revertir los stocks de los productos actuales
            foreach ($compra->producto_almacenes as $pa) {
                $pa->stock_actual -= $pa->pivot->cantidad_compra;
                $pa->save();
            }

            // Eliminar productos antiguos
            $compra->producto_almacenes()->detach();

            // Agregar los productos nuevos y actualizar stock
            foreach ($request->productos as $producto) {
                $compra->producto_almacenes()->attach($producto['id_ProductoAlmacen'], [
                    'cantidad_compra' => $producto['cantidad_compra'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['subtotal'],
                ]);

                // Actualizar stock
                $prod = ProductoAlmacene::find($producto['id_ProductoAlmacen']);
                $prod->stock_actual += $producto['cantidad_compra'];
                $prod->save();
            }

            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Compra actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try {
            // Buscar la compra
            $compra = Compra::with('producto_almacenes')->findOrFail($id);

            // Revertir stock (si manejas stock en producto_almacenes)
            foreach ($compra->producto_almacenes as $producto) {
                $producto->stock_actual -= $producto->pivot->cantidad_compra;
                $producto->save();
            }

            // Eliminar relaciones pivote
            $compra->producto_almacenes()->detach();

            // Eliminar la compra
            $compra->delete();

            return redirect()->route('compras.index')->with('success', 'Compra eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('compras.index')->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}
