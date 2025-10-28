@extends('layouts.myLayout')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h3 class="text-center mb-4"><strong>Detalles del Ajuste</strong></h3>
            <div class="d-flex justify-content-end mt-3 mb-2">
                <a href="{{ route('ajustes.pdf', $ajuste->id) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>
            </div>

            {{-- Información general del ajuste --}}
            <table class="table table-bordered">
                
                <tr>
                    <th>Tipo</th>
                    <td>
                        <span class="badge {{ $ajuste->tipo === 'INGRESO' ? 'bg-success' : 'bg-danger' }}">
                            {{ $ajuste->tipo }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <td>{{ $ajuste->cantidad }}</td>
                </tr>
                <tr>
                    <th>Reembolso</th>
                    <td>{{ $ajuste->reembolso ?? 'N/A' }} Bs</td>
                </tr>
                <tr>
                    <th>Glosa</th>
                    <td>{{ $ajuste->glosa ?? 'Sin glosa' }}</td>
                </tr>
                <tr>
                    <th>Fecha y hora</th>
                    <td>{{ \Carbon\Carbon::parse($ajuste->fecha_hora)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Usuario responsable</th>
                    <td>{{ $ajuste->users->name ?? 'N/A' }} {{ $ajuste->users->lastname ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Comprobante</th>
                    <td>
                        @if($ajuste->imagen)
                            <img src="{{ asset('storage/' . $ajuste->imagen) }}" alt="Comprobante" width="180" class="rounded border">
                        @else
                            <em>No hay imagen</em>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Información del detalle del pedido --}}
            <h2 class="mt-4">Detalle del Pedido</h2>
            <table class="table table-bordered">
                <tr>
                    <th>ID Pedido</th>
                    <td>{{ $ajuste->detalle_pedidos->pedidos->id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <td>{{ $ajuste->detalle_pedidos->pedidos->clientes->nombre }} {{ $ajuste->detalle_pedidos->pedidos->clientes->apellidos }}</td>
                </tr>
                <tr>
                    <th>Menú</th>
                    <td>{{ $ajuste->detalle_pedidos->menus->nombre ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Cantidad Pedido</th>
                    <td>{{ $ajuste->detalle_pedidos->cantidad_pedido ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Precio Unitario</th>
                    <td>{{ $ajuste->detalle_pedidos->precio_unitario ?? 'N/A' }} Bs</td>
                </tr>
            </table>

            {{-- Ingredientes del menú --}}
            <h3 class="mt-4">Ingredientes del Menú</h3>
            <table class="table table-striped">
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

            <div class="text-center mt-4">
                <a href="{{ route('ajustes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection
