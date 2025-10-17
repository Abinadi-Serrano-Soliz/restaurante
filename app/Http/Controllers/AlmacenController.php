<?php

namespace App\Http\Controllers;

use App\Models\Almacene;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AlmacenController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:almacenes.listar', only: ['index']),
            new Middleware('permission:almacenes.crear', only: ['create', 'store']),
            new Middleware('permission:almacenes.editar', only: ['edit', 'update']),
            new Middleware('permission:almacenes.eliminar', only: ['destroy']),
        ];
    }

    public function index()
    {
        $almacenes = Almacene::all();
        return view('almacenes.index',compact('almacenes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('almacenes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:almacenes,nombre',
            'ubicacion' => 'nullable|string|max:250',
            'capacidad' => 'nullable|string|max:250',
        ], [
            'nombre.required' => 'El nombre del almacen es obligatorio.',
            'nombre.unique' => 'Ya existe un almacén con este nombre.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'ubicacion.max' => 'La ubicación no puede tener más de 250 caracteres.',
            'capacidad.max' => 'La capacidad no puede tener más de 250 caracteres.',
        ]);

        try {
            $almacen = Almacene::create([
                'nombre' => $validated['nombre'],
                'ubicacion' => $validated['ubicacion'] ?? null,
                'capacidad' => $validated['capacidad'] ?? null,
            ]);

            return redirect()->route('almacenes.index')->with('success', "¡El almacén {$almacen->nombre} se ha creado correctamente!");;

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear Almacén, por favor intenta nuevamente.')->withInput();
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
    public function edit(Almacene $almacen)
    {
        return view('almacenes.edit', compact('almacen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Almacene $almacen)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:almacenes,nombre,' . $almacen->id,//agragamso la ecepcion para el mismo registro
            'ubicacion' => 'nullable|string|max:250',
            'capacidad' => 'nullable|string|max:250',
        ], [
            'nombre.required' => 'El nombre del almacén es obligatorio.',
            'nombre.unique' => 'Ya existe un Almacén  con este nombre.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'ubicacion.max' => 'La ubicación no puede tener más de 250 caracteres.',
            'capacidad.max' => 'La capacidad no puede tener más de 250 caracteres.',
        ]);
        
        try {
            $almacen->update([
                'nombre' => $validated['nombre'],
                'ubicacion' => $validated['ubicacion'] ?? null,
                'capacidad' => $validated['capacidad'] ?? null,
            ]);

            return redirect()->route('almacenes.index')->with('success', "¡El almacén {$almacen->nombre} se ha Actualizado correctamente!");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al Actualizar Almacén, por favor intenta nuevamente.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Almacene $almacen)
    {
        $almacen->delete();
        return redirect()->route('almacenes.index')->with('success', 'Almacén eliminada');
    }
}
