@extends('layouts.myLayout')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Pedidos</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                <strong>Listado de Pedidos</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                       @can('pedidos.crear')
                        <a href="{{ route('pedidos.create') }}" class="btn btn-success">Nuevo Pedido</a>
                       @endcan
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        <a class="close-link"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead class="text-center">
                                <tr>
                                    <th>P.Nro</th>
                                    <th>Cliente</th>
                                    <th>Repartidor</th>
                                    <th>Fecha Pedido</th>
                                    <th>Monto Total (Bs)</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $pedido)
                                <tr class="text-center">
                                    <td>{{ $pedido->id }}</td>
                                    <td>{{ $pedido->clientes->nombre }} {{ $pedido->clientes->apellidos }}</td>
                                    <td>{{ $pedido->repartidors->nombre ?? 'No asignado' }} {{ $pedido->repartidors->apellidos ?? 'No asignado'}}</td>
                                    <td>{{ $pedido->fecha_hora_pedido }}</td>
                                    <td>{{ number_format($pedido->monto_total, 2) }}</td>
                                    <td>
                                        @if($pedido->estado == 0)
                                            <span class="badge badge-warning">Pendiente</span>
                            
                                        @elseif($pedido->estado == 1)
                                        <span class="badge badge-success">Enviado</span>
                                           
                                        @elseif($pedido->estado == 2)
                                            <span class="badge badge-primary">Entregado</span>
                                        @elseif($pedido->estado == 3)
                                            <span class="badge badge-alert">Cancelado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form class="formulario-eliminar" action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST">
                                            @csrf @method('delete')

                                              @can('pedidos.ver')
                                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-warning btn-sm" title="Ver">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                              @endcan  
                                            
                                              @can('pedidos.editar')
                                                <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-primary btn-sm" title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                              @endcan

                                              @can('pedidos.eliminar')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                              @endcan
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- /.table-responsive --}}
                </div> {{-- /.ibox-content --}}
            </div>
        </div>
    </div>
</div>
@endsection
