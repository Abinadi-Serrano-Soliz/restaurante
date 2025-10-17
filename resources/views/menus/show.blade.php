@extends('layouts.myLayout')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Detalles del Menú</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menú</a></li>
            <li class="breadcrumb-item active"><strong>Detalle del Menú</strong></li>
        </ol>
    </div>
</div>
<div class="container my-5">
    {{-- Encabezado --}}
    <div class="ibox-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Menú: {{ $menu->nombre }}</h2>
            @if($menu->descripcion)
            <p><strong>Descripción:</strong> {{ $menu->descripcion }}</p>
            @endif
        </div>
        @if($menu->imagen)
            <img src="{{ asset('storage/' . $menu->imagen) }}" alt="{{ $menu->nombre }}" width="120" style="object-fit: cover;">
        @endif
    </div>

    {{-- Información del menú --}}
    <div class="mb-4">
        <p><strong>Precio:</strong> ${{ number_format($menu->precio, 2) }}</p>
        <p><strong>Stock:</strong> {{ $menu->stock_menu }}</p>
        <p><strong>Estado:</strong> {{ $menu->estado ? 'Activo' : 'Inactivo' }}</p>
    </div>

    {{-- Tabla de productos --}}
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Almacén</th>
                <th>Cantidad (Kg/Li/Unidad)</th>
    
            </tr>
        </thead>
        <tbody>
            
            @foreach($menu->producto_almacenes as $index => $producto)
              
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $producto->producto->nombre }}</td>
                    <td>{{ $producto->almacen->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }} {{ $producto->unidad_medida }}</td>
                    
                </tr>
            @endforeach
        </tbody>
      </div>
    </table>

    

    {{-- Botón de volver --}}
    <a href="{{ route('menus.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
