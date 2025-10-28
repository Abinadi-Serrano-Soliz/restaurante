@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Ajustes</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Ajustes</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        @can('ajustes.crear')
                            <a href="{{ route('ajustes.create') }}" class="btn btn-success">Nuevo Ajuste</a>
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                    <tr class="text-center">
                                    
                                        <th>Pedido</th>
                                        <th>Menú</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Reembolso (Bs)</th>
                                        <th>Fecha / Hora</th>
                                        <th>Registrado por</th>
                                        <th>Glosa</th>
                                        <th>Comprobante</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ajustes as $ajuste)
                                    <tr class="gradeX text-center">
                                        <td>PED-{{ $ajuste->detalle_pedidos->pedidos->id ?? 'N/A' }}</td>
                                        <td>{{ $ajuste->detalle_pedidos->menus->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $ajuste->tipo == 'INGRESO' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $ajuste->tipo }}
                                            </span>
                                        </td>
                                        <td>{{ $ajuste->cantidad}}</td>
                                        <td>{{ number_format($ajuste->reembolso, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ajuste->fecha_hora)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $ajuste->users->name ?? 'Desconocido' }} {{ $ajuste->users->lastname ?? 'Desconocido' }}</td>
                                        <td width="200">{{ $ajuste->glosa ?? '-' }}</td>
                                        <td>
                                            @if($ajuste->imagen)
                                                <img src="{{ asset('storage/' . $ajuste->imagen) }}" alt="comprobante" width="50">
                                            @else
                                                <span class="text-muted">Sin imagen</span>
                                            @endif
                                        </td>
                                
                                        <form class="formulario-eliminar" action="{{ route('ajustes.destroy', $ajuste->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                    @can('ajustes.ver')
                                                    <a href="{{ route('ajustes.show', $ajuste->id) }}" class="btn btn-warning btn-sm" title="Ver">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @endcan
                                                    
                                                    @can('ajustes.editar')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('ajustes.edit', $ajuste->id) }}"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                    @endcan

                                                    @can('ajustes.eliminar')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                    @endcan
                                                </td>
                                            </form>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
@endsection





















