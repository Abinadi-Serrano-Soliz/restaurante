<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoriaController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:categorias.ver', only: ['index', 'show']),
            new Middleware('permission:categorias.crear', only: ['create', 'store']),
            new Middleware('permission:categorias.actualizar', only: ['edit', 'update']),
            new Middleware('permission:categorias.eliminar', only: ['destroy']),
        ];
    }

    public function index()
    {
        $categorias = Categoria::all(); 
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
        ]);

        try {
            $categoria = Categoria::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            return redirect()->route('categorias.index')->with('success', "¡La categoría {$categoria->nombre} se ha creado correctamente!");;

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear categoría, por favor intenta nuevamente.')->withInput();
        }
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
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,//agragamso la ecepcion para el mismo registro
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
        ]);
        
        try {
            $categoria->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            return redirect()->route('categorias.index')->with('success', "¡La categoría {$categoria->nombre} se ha Actualizado correctamente!");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al Actualizar categoría, por favor intenta nuevamente.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria eliminada');
    }
}
