<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReporteController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:reportes.listar', only: ['reportePorFechas','reportePedidosPDF','reporteComprasPorFechas','reporteComprasPDF']),
        
        ];
    }
     // Mostrar formulario y resultados
    public function reportePorFechas(Request $request)
    {
        // Inicializamos fechas por defecto (hoy) si no vienen por GET
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::today()->format('Y-m-d'));

        if($fechaInicio > $fechaFin){
            return back()->with('warning', 'La fecha Inicio tiene que ser menor a la fecha Final');
        }

        $inicioCarbon = Carbon::parse($fechaInicio)->startOfDay();
        $finCarbon = Carbon::parse($fechaFin)->endOfDay();

        // Traer pedidos en el rango
        $pedidos = Pedido::with(['clientes', 'pagos', 'repartidors','detallePedidos']) // <-- esto carga los menús a través de detalle_pedidos
            ->whereBetween('fecha_hora_pedido', [$inicioCarbon, $finCarbon])
            ->orderBy('fecha_hora_pedido', 'desc')
            ->get();

        $totalPedidos = $pedidos->count();
        $totalVentas = $pedidos->sum('monto_total');
        $promedio = $totalPedidos > 0 ? $totalVentas / $totalPedidos : 0;

        return view('reportes.reportePedidos', compact(
            'pedidos', 'fechaInicio', 'fechaFin', 'totalPedidos', 'totalVentas', 'promedio'
        ));
    }

    // Descargar PDF
    public function reportePedidosPDF(Request $request)
    {
       $fechaInicio = $request->input('fecha_inicio', Carbon::today()->format('Y-m-d'));
    $fechaFin = $request->input('fecha_fin', Carbon::today()->format('Y-m-d'));

    $inicioCarbon = Carbon::parse($fechaInicio)->startOfDay();
    $finCarbon = Carbon::parse($fechaFin)->endOfDay();

    $pedidos = Pedido::with([
        'clientes', 
        'pagos', 
        'repartidors',
        'menus',
        'detallePedidos.menus'  // AGREGAR ESTO
    ])
    ->whereBetween('fecha_hora_pedido', [$inicioCarbon, $finCarbon])
    ->orderBy('fecha_hora_pedido', 'desc')
    ->get();

    $totalPedidos = $pedidos->count();
    $totalVentas = $pedidos->sum('monto_total');
    $promedio = $totalPedidos > 0 ? $totalVentas / $totalPedidos : 0;

    $pdf = Pdf::loadView('reportes.pedidos_pdf', compact(
        'pedidos', 'fechaInicio', 'fechaFin', 'totalPedidos', 'totalVentas', 'promedio'
    ))
    ->setPaper('a4'); //  Horizontal para más espacio

    return $pdf->download('Reporte_Pedidos_'.$fechaInicio.'_a_'.$fechaFin.'.pdf');
    }

    // Mostrar formulario y resultados de compras
    public function reporteComprasPorFechas(Request $request)
    {
        // Inicializamos fechas por defecto (hoy) si no vienen por GET
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::today()->format('Y-m-d'));

        if($fechaInicio > $fechaFin){
            return back()->with('warning', 'La fecha Inicio tiene que ser menor a la fecha Final');
        }

        $inicioCarbon = Carbon::parse($fechaInicio)->startOfDay();
        $finCarbon = Carbon::parse($fechaFin)->endOfDay();

        // Traer compras en el rango con sus relaciones
        $compras = Compra::with([
            'proveedor',
            'producto_almacen__compras.producto_almacenes.producto'
        ])
        ->whereBetween('fecha_compra', [$inicioCarbon, $finCarbon])
        ->orderBy('fecha_compra', 'desc')
        ->get();

        $totalCompras = $compras->count();
        $totalGastado = $compras->sum('monto_total');
        $promedio = $totalCompras > 0 ? $totalGastado / $totalCompras : 0;

        return view('reportes.reporteCompras', compact(
            'compras', 'fechaInicio', 'fechaFin', 'totalCompras', 'totalGastado', 'promedio'
        ));
    }

    // Descargar PDF de compras
    public function reporteComprasPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::today()->format('Y-m-d'));

        $inicioCarbon = Carbon::parse($fechaInicio)->startOfDay();
        $finCarbon = Carbon::parse($fechaFin)->endOfDay();

        $compras = Compra::with([
            'proveedor',
            'producto_almacen__compras.producto_almacenes.producto'
        ])
        ->whereBetween('fecha_compra', [$inicioCarbon, $finCarbon])
        ->orderBy('fecha_compra', 'desc')
        ->get();

        $totalCompras = $compras->count();
        $totalGastado = $compras->sum('monto_total');
        $promedio = $totalCompras > 0 ? $totalGastado / $totalCompras : 0;

        $pdf = Pdf::loadView('reportes.compras_pdf', compact(
            'compras', 'fechaInicio', 'fechaFin', 'totalCompras', 'totalGastado', 'promedio'
        ))
        ->setPaper('a4');

        return $pdf->download('Reporte_Compras_'.$fechaInicio.'_a_'.$fechaFin.'.pdf');
    }
}
