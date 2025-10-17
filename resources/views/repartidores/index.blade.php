@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Repartidores</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Repartidores</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        
                        <a href="{{ route('repartidores.create') }}" class="btn btn-success">Nuevo Repartidor</a>
                        
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
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Salario</th>
                                        <th>Teléfono</th>
                                        <th>Placa</th>
                                        <th>Tipo Vehículo</th>
                                        <th>Estado</th>
                
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($repartidores as $repartidor)
                                    <tr class="gradeX">
                                        <td>{{$repartidor->nombre}}</td>
                                        <td>{{$repartidor->apellidos}}</td>
                                        <td>{{$repartidor->salario}}</td>
                                        <td>{{$repartidor->telefono}}</td>
                                        <td>{{$repartidor->placa}}</td>
                                        <td>{{$repartidor->tipo_vehiculo}}</td>
                                        <td class="text-center">
                                            @if ($repartidor->estado==0)
                                                <span class="badge rounded bg-primary p-1">
                                                    Disponible
                                                </span>
                                            @else
                                                <span class="badge rounded bg-danger p-1">
                                                    Ocupado
                                                </span>
                                            @endif
                                        </td>
                                      
                                
                                        <form class="formulario-eliminar"
                                                action="{{ route('repartidores.destroy', $repartidor->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                 
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('repartidores.edit', $repartidor->id) }}"><i
                                                            class=" fa-solid fa-pen-to-square"></i></a>
                                                    
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                    
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





















