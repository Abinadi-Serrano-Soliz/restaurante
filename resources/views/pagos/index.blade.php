@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Pagos</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Pagos</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
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
                                        <th>Nro. Transaccion</th>
                                        <th>Cliente</th>
                                        <th>Monto Total</th>
                                        <th>Método de Pago</th>
                                        <th>Estado de Pago</th>
                                        <th>Fecha de Pago</th>
                                        <th width="">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pagos as $pago)
                                    <tr class="gradeX">
                                        
                                        <td>{{ $pago->transaccion_id }}</td>
                                        <td>{{ $pago->pedidos->clientes->nombre }} {{ $pago->pedidos->clientes->apellidos }}</td>
                                        <td>{{ $pago->monto_total}}</td>
                                        <td>{{ $pago->metodo_pago}}</td>
                                        <td>{{ $pago->estado_pago}}</td>
                                        <td>{{ $pago->fecha_hora_pago}}</td>

                                        <td>
                                            @can('pagos.ver')
                                               <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-warning btn-sm" title="Ver">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                             @endcan 
                                        </td>
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