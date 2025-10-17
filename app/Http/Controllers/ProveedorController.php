<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProveedorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:proveedores.listar', only: ['index']),
            new Middleware('permission:proveedores.crear', only: ['create', 'store']),
            new Middleware('permission:proveedores.editar', only: ['edit', 'update']),
            new Middleware('permission:proveedores.eliminar', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        
        return view('proveedores.index',compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
           
            'nombre'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u',
            'apellidos'=> 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/u|',
            'telefono'=> 'required|numeric|digits:8',
            'direccion'=> 'nullable|string',
        ]);

        Proveedor::create([
            
            'nombre'=>$request->nombre,
            'apellidos'=>$request->apellidos,
            'telefono'=>$request->telefono,
            'direccion'=>$request->direccion,
        ]);

        return redirect()->route('proveedores.index')->with('success','Repartidor creado correctamente');
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
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit',compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
         $request->validate([
           
            'nombre'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u',
            'apellidos'=> 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/u|',
            'telefono'=> 'required|numeric|digits:8',
            'direccion'=> 'nullable|string',
        ]);

        $proveedor = Proveedor::findOrFail($id);

        $proveedor->update([
            
            'nombre'=>$request->nombre,
            'apellidos'=>$request->apellidos,
            'telefono'=>$request->telefono,
            'direccion'=>$request->direccion,
        ]);

        return redirect()->route('proveedores.index')->with('success',' Editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

         return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente');
    }
}
