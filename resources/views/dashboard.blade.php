@extends('layouts.myLayout')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <!-- Total Pedidos -->
        <div class="col-lg-2">
            <div class="ibox">
                <div class="ibox-title">
                    <span class="label label-success float-right">Total</span>
                    <h5>Pedidos</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $totalPedidos }}</h1>
                    <div class="stat-percent font-bold text-success">100% <i class="fa fa-bolt"></i></div>
                    <small>Total de pedidos</small>
                </div>
            </div>
        </div>

        <!-- Ingresos Mensuales -->
        <div class="col-lg-2">
            <div class="ibox">
                <div class="ibox-title">
                    <span class="label label-info float-right">Mensual</span>
                    <h5>Ingresos</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($ingresosMensuales, 2) }} Bs</h1>
                    <div class="stat-percent font-bold text-info">↑ <i class="fa fa-level-up"></i></div>
                    <small>Ingresos este mes</small>
                </div>
            </div>
        </div>

        <!-- Reembolsos -->
        <div class="col-lg-2">
            <div class="ibox">
                <div class="ibox-title">
                    <span class="label label-danger float-right">Egresos</span>
                    <h5>Reembolsos</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($reembolsosTotales, 2) }} Bs</h1>
                    <div class="stat-percent font-bold text-danger">↓ <i class="fa fa-level-down"></i></div>
                    <small>Total reembolsado</small>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-lg-2">
            <div class="ibox">
                <div class="ibox-title">
                    <span class="label label-primary float-right">Total</span>
                    <h5>Clientes</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $clientes }}</h1>
                    <div class="stat-percent font-bold text-primary">+ <i class="fa fa-user"></i></div>
                    <small>Clientes registrados</small>
                </div>
            </div>
        </div>

        <!-- Menú más vendido -->
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-title">
                    <span class="label label-warning float-right">Top</span>
                    <h5>Menú más vendido</h5>
                </div>
                <div class="ibox-content">
                    @if($menuMasVendido)
                        <h2 class="no-margins">{{ $menuMasVendido->nombre }}</h2>
                        <small>Total vendidos: {{ $menuMasVendido->total }}</small>
                    @else
                        <p class="text-muted">Sin datos de ventas aún.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!--  Gráfico de ventas semestrales -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Ventas de los últimos 6 meses</h5>
                </div>
                <div class="ibox-content">
                    <canvas id="ventasChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!--  Tabla de productos con bajo stock -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Productos con bajo stock</h5>
                    <span class="label label-danger float-right">Reponer</span>
                </div>
                <div class="ibox-content">
                    @if($productosBajoStock->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Mínimo</th>
                                    <th>Unidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productosBajoStock as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->producto->nombre ?? 'Sin nombre' }}</td>
                                    <td>{{ $item->almacen->nombre ?? 'N/A' }}</td>
                                    <td class="text-danger font-weight-bold">{{ $item->stock_actual }}</td>
                                    <td>{{ $item->stock_minimo }}</td>
                                    <td>{{ $item->unidad_medida }}</td>
                                    <td>
                                        @if($item->stock_actual == 0)
                                            <span class="badge bg-danger">Agotado</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Bajo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">✅ Todos los productos tienen stock suficiente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const meses = @json($ventasSemestrales->pluck('mes'));
    const totales = @json($ventasSemestrales->pluck('total'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses.map(m => new Date(0, m-1).toLocaleString('es', { month: 'long' })),
            datasets: [{
                label: 'Ventas (Bs)',
                data: totales,
                backgroundColor: 'rgba(26, 179, 148, 0.5)',
                borderColor: '#1ab394',
                borderWidth: 2
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection
