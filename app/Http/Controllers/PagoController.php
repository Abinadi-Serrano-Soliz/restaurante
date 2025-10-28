<?php

namespace App\Http\Controllers;

use App\Models\Pago;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PagoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:pagos.listar', only: ['index']),
            new Middleware('permission:pagos.ver', only: ['show']),
            
        ];
    }

    public function index(){
        
       $pagos = Pago::with('pedidos.clientes')->get();
       return view('pagos.index', compact('pagos'));
    }

    public function show($id)
    {
        // Traer el pago con relaciones necesarias: pedido y cliente
        $pago = Pago::with(['pedidos.clientes', 'pedidos.detallePedidos', 'pedidos.menus'])->findOrFail($id);

        // Retornar vista con los datos
        return view('pagos.show', compact('pago'));
    }
}
