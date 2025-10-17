<?php

namespace App\Http\Controllers;

use App\Models\Almacene;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with('categoria', 'almacenes')->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $almacenes = Almacene::all();
        return view('productos.create', compact('categorias', 'almacenes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'id_categoria' => 'required|exists:categorias,id',
            'almacenes' => 'required|array|min:1',
            'almacenes.*.stock_actual' => 'nullable|numeric',
            'almacenes.*.stock_minimo' => 'nullable|numeric',
            'almacenes.*.unidad_medida' => 'nullable|string',
        ]);

         // Crear producto
        $producto = Producto::create($request->only('nombre','descripcion','id_categoria'));

        // Guardar relaciones con almacenes
        if($request->has('almacenes')){
            foreach($request->almacenes as $almacen_id => $data){
                $producto->almacenes()->attach($almacen_id, [
                    'stock_actual' => $data['stock_actual'] ?? 0,
                    'stock_minimo' => $data['stock_minimo'] ?? 0,
                    'unidad_medida' => $data['unidad_medida'] ?? '',
                ]);
            }
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $almacenes = Almacene::all();
        $producto->load('almacenes'); // carga relación pivote
        return view('productos.edit', compact('producto','categorias','almacenes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'id_categoria' => 'required|exists:categorias,id',
            'almacenes' => 'required|array|min:1',
            'almacenes.*.stock_actual' => 'nullable|numeric',
            'almacenes.*.stock_minimo' => 'nullable|numeric',
            'almacenes.*.unidad_medida' => 'nullable|string',
        ]);

        // Actualizar datos del producto
        $producto->update($request->only('nombre','descripcion','precio_compra','id_categoria'));

        // Actualizar relaciones con almacenes
        if($request->has('almacenes')){
            foreach($request->almacenes as $almacen_id => $data){
                $producto->almacenes()->syncWithoutDetaching([
                    $almacen_id => [
                        'stock_actual' => $data['stock_actual'] ?? 0,
                        'stock_minimo' => $data['stock_minimo'] ?? 0,
                        'unidad_medida' => $data['unidad_medida'] ?? '',
                    ]
                ]);
            }
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete(); // eliminará automáticamente relaciones si ON DELETE CASCADE
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
}
