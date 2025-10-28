@extends('layouts.myLayout')

@section('content')
<div class="container">
    <div class="card mb-4">
      <div class="card-body">  
        <h2>Detalle del Pago {{ $pago->transaccion_id }}</h2>

        <p><b>Cliente:</b> {{ $pago->pedidos->clientes->nombre }} {{ $pago->pedidos->clientes->apellidos }}</p>
        <p><b>Monto Total:</b> {{ $pago->monto_total }} Bs</p>
        <p><b>Estado de Pago:</b> {{ $pago->estado_pago }}</p>
        <p><b>Fecha de Pago:</b> {{ $pago->fecha_hora_pago }}</p>

        <h4>Detalles del Pedido</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Menú</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pago->pedidos->detallePedidos as $detalle)
                <tr>
                    <td>{{ $detalle->menus->nombre ?? 'N/A' }}</td>
                    <td>{{ $detalle->cantidad_pedido }}</td>
                    <td>{{ $detalle->precio_unitario }}</td>
                    <td>{{ $detalle->subtotal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>

    <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
