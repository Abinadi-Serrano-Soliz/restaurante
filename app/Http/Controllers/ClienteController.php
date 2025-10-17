<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClienteController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:clientes.listar', only: ['index']),
            new Middleware('permission:clientes.crear', only: ['create', 'store']),
            new Middleware('permission:clientes.editar', only: ['edit', 'update']),
            new Middleware('permission:clientes.eliminar', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();

        return view('clientes.index',compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
           
            'ci'=> 'required|numeric|unique:clientes,ci',
            'nombre'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u',
            'apellidos'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u|',
            'telefono'=> 'required|numeric|digits:8',
            'correo'=> 'required|email|unique:clientes,correo',
           
        ]);

        Cliente::create([
            'ci' =>$request->ci,
            'nombre'=>$request->nombre,
            'apellidos'=>$request->apellidos,
            'telefono'=>$request->telefono,
            'correo'=>$request->correo,
        ]);

        return redirect()->route('clientes.index')->with('success','Cliente creado correctamente');
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
    public function edit( $id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit',compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $cliente = Cliente::findOrFail($id);
        $request->validate([
           
            'ci'=> 'required|numeric|unique:clientes,ci,'. $cliente->id,//agragamso la ecepcion para el mismo registro
            'nombre'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u',
            'apellidos'=> 'required|string|max:255|regex:/^[a-zA-Z\s]+$/u|',
            'telefono'=> 'required|numeric|digits:8',
            'correo'=> 'required|email|unique:clientes,correo,'. $cliente->id,//agragamso la ecepcion para el mismo registro
           
        ]);

        
        $cliente->update([
            'ci' =>$request->ci,
            'nombre'=>$request->nombre,
            'apellidos'=>$request->apellidos,
            'telefono'=>$request->telefono,
            'correo'=>$request->correo,
        ]);

        return redirect()->route('clientes.index')->with('success','Editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success','Cliente Eliminado correctamente');

    }
}
