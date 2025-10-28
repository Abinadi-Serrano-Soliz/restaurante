<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pedidos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #334155;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #334155;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 12px;
        }
        
        .fecha-rango {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        thead {
            background-color:#10161f;
            color: white;
        }
        
        thead th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        tbody td {
            padding: 6px 5px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tfoot {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        tfoot td {
            padding: 10px 5px;
            border-top: 2px solid  #334155;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-end {
            text-align: right;
        }
        
        .fw-bold {
            font-weight: bold;
        }
        
        .text-success {
            color: #070c08;
        }
        
        .text-secondary {
            color: #6c757d;
        }
        
        .text-muted {
            color: #999;
        }
        
        .menu-item {
            display: block;
            margin-bottom: 3px;
        }
        
        .cantidad-item {
            display: block;
            margin-bottom: 3px;
        }
        
        .precio-item {
            display: block;
            margin-bottom: 3px;
        }
        
        .footer-info {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Pedidos</h1>
        <p>Análisis detallado de pedidos por rango de fechas</p>
    </div>

    <div class="fecha-rango">
        Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} 
        al 
        {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 6%;">ID</th>
                <th style="width: 14%;">Cliente</th>
                <th style="width: 14%;">Repartidor</th>
                <th style="width: 10%;">Fecha y Hora</th>
                <th style="width: 20%;">Menú</th>
                <th style="width: 8%;" class="text-center">Cant.</th>
                <th style="width: 10%;">Precio</th>
                <th style="width: 10%;" class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
            <tr>
                <td class="text-secondary fw-bold">
                    PED-{{ $pedido->id }}
                </td>
                <td>
                    {{ $pedido->clientes->nombre ?? '---' }} 
                    {{ $pedido->clientes->apellidos ?? '' }}
                </td>
                <td>
                    {{ $pedido->repartidors->nombre ?? 'Sin asignar' }} 
                    {{ $pedido->repartidors->apellidos ?? '' }}
                </td>
                <td>
                    <div class="fw-bold">
                        {{ \Carbon\Carbon::parse($pedido->fecha_hora_pedido)->format('d/m/Y') }}
                    </div>
                    <span class="text-muted">
                        {{ \Carbon\Carbon::parse($pedido->fecha_hora_pedido)->format('H:i') }}
                    </span>
                </td>
                <td>
                    @foreach($pedido->detallePedidos as $detalle)
                        <span class="menu-item">
                            • {{ $detalle->menus->nombre ?? 'N/A' }}
                        </span>
                    @endforeach
                </td>
                <td class="text-center">
                    @foreach($pedido->detallePedidos as $detalle)
                        <span class="cantidad-item fw-bold">
                            {{ $detalle->cantidad_pedido }}
                        </span>
                    @endforeach
                </td>
                <td>
                    @foreach($pedido->detallePedidos as $detalle)
                        <span class="precio-item">
                            {{ number_format($detalle->precio_unitario, 2) }} Bs
                        </span>
                    @endforeach
                </td>
                <td class="text-end">
                    <span class="fw-bold text-success">
                        {{ number_format($pedido->monto_total, 2) }} Bs
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">
                    No se encontraron pedidos en este rango de fechas
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-end fw-bold">
                    MONTO TOTAL:
                </td>
                <td class="text-end">
                    <span class="fw-bold text-success" style="font-size: 12px;">
                        {{ number_format($totalVentas, 2) }} Bs
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-info">
        Reporte generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} | 
        Total de pedidos: {{ $totalPedidos }} 
    
    </div>
</body>
</html>