<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Compras</title>
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
            background-color: #334155;
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
            border-top: 2px solid #334155;
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
        
        .text-danger {
            color: #dc3545;
        }
        
        .text-secondary {
            color: #6c757d;
        }
        
        .text-muted {
            color: #999;
        }
        
        .producto-item {
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Compras</h1>
        <p>Análisis detallado de compras a proveedores por rango de fechas</p>
    </div>

    <div class="fecha-rango">
         Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} 
        al 
        {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 20%;">Proveedor</th>
                <th style="width: 12%;">Fecha</th>
                <th style="width: 25%;">Producto</th>
                <th style="width: 10%;" class="text-center">Cantidad</th>
                <th style="width: 12%;">Precio</th>
                <th style="width: 13%;" class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
            <tr>
                <td class="text-secondary fw-bold">
                    COM-{{ $compra->id }}
                </td>
                <td>
                    {{ $compra->proveedor->nombre ?? 'Sin proveedor' }} {{ $compra->proveedor->apellidos ?? 'Sin proveedor' }}
                </td>
                <td>
                    <div class="fw-bold">
                        {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}
                    </div>
                </td>
                <td>
                    @foreach($compra->producto_almacen__compras as $detalle)
                        <span class="producto-item">
                            • {{ $detalle->producto_almacenes->producto->nombre ?? 'N/A' }}
                        </span>
                    @endforeach
                </td>
                <td class="text-center">
                    @foreach($compra->producto_almacen__compras as $detalle)
                        <span class="cantidad-item fw-bold">
                            {{ $detalle->cantidad_compra }}
                            {{ $detalle->producto_almacenes->unidad_medida}}
                        </span>
                    @endforeach
                </td>
                <td>
                    @foreach($compra->producto_almacen__compras as $detalle)
                        <span class="precio-item">
                            {{ number_format($detalle->precio_unitario, 2) }} Bs
                        </span>
                    @endforeach
                </td>
                <td class="text-end ">
                     @forelse($compra->producto_almacen__compras as $detalle)
                        <span class="precio-item" style="width: fit-content;">
                             {{ $detalle->subtotal }} 
                         </span>
                    @empty
                        <small class="text-muted"> Bs</small>
                    @endforelse
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No se encontraron compras en este rango de fechas
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end fw-bold">
                    TOTAL GASTADO:
                </td>
                <td class="text-end">
                    <span class="fw-bold text-danger" style="font-size: 12px;">
                        {{ number_format($totalGastado, 2) }} Bs
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-info">
        Reporte generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} | 
        Total de compras: {{ $totalCompras }} | 
      
    </div>
</body>
</html>