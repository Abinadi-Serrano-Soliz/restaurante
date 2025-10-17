@extends('layouts.myLayout')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Compras</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                <strong>Listado de Compras</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    @can('compras.crear')
                    <a href="{{ route('compras.create') }}" class="btn btn-success">Nueva Compra</a>
                    @endcan

                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    {{-- Mensaje de éxito --}}
                    @if (session('success'))
                        <div id="alerta" class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr class="text-center">
                                    <th>Proveedor</th>
                                    <th>Fecha de compra</th>
                                    <th>Monto total (Bs)</th>
                                    <th>Productos comprados</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                <tr class="gradeX">
                                    <td>{{ $compra->proveedor->nombre }}</td>
                                    <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($compra->monto_total, 2) }}</td>

                                    <td>
                                        <ul class="mb-0">
                                            @foreach($compra->producto_almacenes as $detalle)
                                                <li>
                                                    {{ $detalle->producto->nombre }} 
                                                      |{{ $detalle->almacen->nombre }} 
                                                    
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <form class="formulario-eliminar" 
                                          action="{{ route('compras.destroy', $compra->id) }}" 
                                          method="POST">
                                        @csrf
                                        @method('delete')
                                        <td class="text-center">
                                            @can('compras.eliminar')
                                                <a class="btn btn-warning btn-sm" 
                                                href="{{ route('compras.show', $compra->id) }}">
                                                <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('compras.editar')
                                                <a class="btn btn-info btn-sm" 
                                                href="{{ route('compras.edit', $compra->id) }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            @endcan

                                            @can('compras.eliminar')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> {{-- ibox-content --}}
            </div>
        </div>
    </div>
</div>

{{-- Script para ocultar alertas --}}
<script>
    setTimeout(() => {
        let alerta = document.getElementById('alerta');
        if (alerta) alerta.style.display = 'none';
    }, 3000);
</script>
@endsection
