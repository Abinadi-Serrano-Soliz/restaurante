<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\Cliente;
use App\Models\Menu;
use App\Models\ProductoAlmacene;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:panel de control.listar', only: ['index']),
          
        ];
    }

     public function index()
    {
        // Total de pedidos
        $totalPedidos = Pedido::count();

        // Ingresos totales (de la tabla pagos)
        $ingresosTotales = Pago::sum('monto_total');

        // Ingresos del mes actual
        $ingresosMensuales = Pago::whereMonth('created_at', Carbon::now()->month)->sum('monto_total');

        // Pedidos del mes actual
        $pedidosMensuales = Pedido::whereMonth('fecha_hora_pedido', Carbon::now()->month)->count();

        // Total de clientes registrados
        $clientes = Cliente::count();

        // Menú más vendido (según detalle_pedidos)
        $menuMasVendido = DB::table('detalle_pedidos')
            ->select('menus.nombre', DB::raw('SUM(detalle_pedidos.cantidad_pedido) as total'))
            ->join('menus', 'detalle_pedidos.id_menu', '=', 'menus.id')
            ->groupBy('menus.nombre')
            ->orderByDesc('total')
            ->first();

        // Ventas de los últimos 6 meses
        $ventasSemestrales = Pedido::selectRaw('MONTH(fecha_hora_pedido) as mes, SUM(monto_total) as total')
            ->where('fecha_hora_pedido', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Reembolsos registrados en ajustes
        $reembolsosTotales = Ajuste::where('tipo', 'EGRESO')->sum('reembolso');

         //  Productos con stock bajo o agotado
        $productosBajoStock = ProductoAlmacene::with('producto', 'almacen')
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalPedidos',
            'ingresosTotales',
            'ingresosMensuales',
            'pedidosMensuales',
            'clientes',
            'menuMasVendido',
            'ventasSemestrales',
            'reembolsosTotales',
            'productosBajoStock'
        ));
    }
}
