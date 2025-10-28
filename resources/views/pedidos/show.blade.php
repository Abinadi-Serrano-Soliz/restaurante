@extends('layouts.myLayout')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Detalle del Pedido Nro: {{ $pedido->id }}</h3>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $pedido->clientes->nombre ?? 'Sin cliente' }} {{ $pedido->clientes->apellidos }}</p>
            <p><strong>CI:</strong> {{ $pedido->clientes->ci }} </p>
            <p><strong>Usuario:</strong> {{ $pedido->users->name ?? 'Sin usuario' }} {{ $pedido->users->lastname ?? 'Sin usuario' }}</p>
            <p><strong>Repartidor:</strong> {{ $pedido->repartidors->nombre ?? 'No asignado' }} {{ $pedido->repartidors->apellidos ?? 'No asignado'}}</p>
            <p><strong>Fecha Pedido:</strong> {{ $pedido->fecha_hora_pedido }}</p>
            <p><strong>Monto Total:</strong> Bs {{ number_format($pedido->monto_total, 2) }}</p>
            <p><strong>Estado:</strong>
                @if($pedido->estado == 0)
                    <span >Pendiente</span>
                @elseif($pedido->estado == 1)
                    <span >enviado</span>
                @elseif($pedido->estado == 2)
                    <span >Entregado</span>
                
                @elseif($pedido->estado == 3)
                    <span >Cancelado</span>
                @endif
            </p>
        </div>
    </div>

    <h5>🧾 Menús del Pedido</h5>
    <div class="card mb-4">
      <div class="card-body">  
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Menú</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->menus as $menu)
                    <tr>
                        <td>{{ $menu->nombre }}</td>
                        <td>{{ $menu->pivot->cantidad_pedido }}</td>
                        <td>Bs {{ number_format($menu->pivot->precio_unitario, 2) }}</td>
                        <td>Bs {{ number_format($menu->pivot->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>

    <h5 class="mt-4">💳 Pagos Realizados</h5>
    @if($pedido->pagos->count() > 0)
    <div class="card mb-4">
      <div class="card-body">  
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Método Pago</th>
                    <th>Monto Total</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
               
                    <tr>
                        <td>{{ ucfirst($pedido->pagos->metodo_pago) }}</td>
                        <td>Bs {{ number_format($pedido->pagos->monto_total, 2) }}</td>
                        <td>{{ $pedido->pagos->fecha_hora_pago }}</td>
                       
                        <td>
                             {{ $pedido->pagos->estado_pago }}
                             
                        </td>
                    </tr>
            
            </tbody>
        </table>
      </div>
    </div>
    @else
        <p class="text-muted">No se registraron pagos para este pedido.</p>
    @endif

    <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
