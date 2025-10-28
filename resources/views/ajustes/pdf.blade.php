<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ajuste #{{ $ajuste->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #555; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .badge { padding: 4px 8px; border-radius: 4px; color: #fff; }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        img { margin-top: 5px; border-radius: 6px; }
    </style>
</head>
<body>
    <h2>Comprobante de Ajuste</h2>
    <h3>Ajuste N° {{ $ajuste->id }}</h3>

    <table>
        <tr>
            <th>Tipo</th>
            <td><span >{{ $ajuste->tipo }}</span></td>
        </tr>
        <tr><th>Cantidad</th><td>{{ $ajuste->cantidad }}</td></tr>
        <tr><th>Reembolso</th><td>{{ $ajuste->reembolso ?? 'N/A' }} Bs</td></tr>
        <tr><th>Glosa</th><td>{{ $ajuste->glosa ?? 'Sin glosa' }}</td></tr>
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($ajuste->fecha_hora)->format('d/m/Y H:i') }}</td></tr>
        <tr><th>Usuario</th><td>{{ $ajuste->users->name ?? 'N/A' }}</td></tr>
    </table>

    <h3>Detalle del Pedido</h3>
    <table>
        <tr><th>ID Pedido</th><td>{{ $ajuste->detalle_pedidos->pedidos->id ?? 'N/A' }}</td></tr>
        <tr><th>Cliente</th><td>{{ $ajuste->detalle_pedidos->pedidos->clientes->nombre ?? 'N/A' }}</td></tr>
        <tr><th>Menú</th><td>{{ $ajuste->detalle_pedidos->menus->nombre ?? 'N/A' }}</td></tr>
        <tr><th>Cantidad Pedido</th><td>{{ $ajuste->detalle_pedidos->cantidad_pedido }}</td></tr>
        <tr><th>Precio Unitario</th><td>{{ $ajuste->detalle_pedidos->precio_unitario }} Bs</td></tr>
    </table>

    <h3>Ingredientes del Menú</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Unidad</th>
                <th>Cantidad usada</th>
                <th>Stock actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ajuste->detalle_pedidos->menus->detalle_menus as $detalle)
            <tr>
                <td>{{ $detalle->producto_almacenes->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->producto_almacenes->unidad_medida ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->producto_almacenes->stock_actual }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($ajuste->imagen)
        <div class="text-center">
            <p><strong>Comprobante:</strong></p>
            <img src="{{ public_path('storage/' . $ajuste->imagen) }}" width="200">
        </div>
    @endif
</body>
</html>
