<?php

namespace App\Http\Controllers;

use App\Models\Repartidor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RepartidorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:repartidores.listar', only: ['index']),
            new Middleware('permission:repartidores.crear', only: ['create', 'store']),
            new Middleware('permission:repartidores.editar', only: ['edit', 'update']),
            new Middleware('permission:repartidores.eliminar', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repartidores = Repartidor::all();

        return view('repartidores.index',compact('repartidores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('repartidores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
           
            'nombre'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u',
            'apellidos'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u|',
            'salario'=> 'required|numeric|regex:/^[0-9]+$/u',
            'telefono'=> 'required|numeric|digits:8',
            'placa'=> 'required|unique:repartidors,placa',
            'tipo_vehiculo'=> 'required|string|',
        ]);

        Repartidor::create([
            
            'nombre'=>$request->nombre,
            'apellidos'=>$request->apellidos,
            'salario' =>$request->salario,
            'telefono'=>$request->telefono,
            'placa'=>$request->placa,
            'tipo_vehiculo' =>$request->tipo_vehiculo,
        ]);

        return redirect()->route('repartidores.index')->with('success','Repartidor creado correctamente');
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
        $repartidor = Repartidor::findOrFail($id);

        return view('repartidores.edit',compact('repartidor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $repartidor = Repartidor::findOrFail($id);

        $request->validate([
           
            'nombre'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u',
            'apellidos'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u|',
            'salario'=> 'required|numeric|min:0',
            'telefono'=> 'required|numeric|digits:8',
            'placa'=> 'required|unique:repartidors,placa,'. $repartidor->id,
            'tipo_vehiculo'=> 'required|string|',
        ]);

        
        $repartidor->update([
            
            'nombre'=>$request->nombre,
            'apellidos'=>$request->apellidos,
            'salario' =>$request->salario,
            'telefono'=>$request->telefono,
            'placa'=>$request->placa,
            'tipo_vehiculo' =>$request->tipo_vehiculo,
        ]);

        return redirect()->route('repartidores.index')->with('success','Editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $repartidor = Repartidor::findOrFail($id);
        $repartidor->delete();

        return redirect()->route('repartidores.index')->with('success','Repartidor Eliminado Correctamente');
    }
}
