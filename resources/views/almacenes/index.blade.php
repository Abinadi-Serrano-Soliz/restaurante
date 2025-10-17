@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Almacenes</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Almacenes</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        @can('almacenes.crear')
                        <a href="{{ route('almacenes.create') }}" class="btn btn-success">Nuevo almacén</a>
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
                                        <th>Nombre</th>
                                        <th>Ubicación</th>
                                        <th>Capacidad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($almacenes as $almacen)
                                    <tr class="gradeX">
                                        <td>{{$almacen->nombre}}</td>
                                        <td>{{$almacen->ubicacion}}</td>
                                        <td>{{$almacen->capacidad}}
                                             @if($almacen->capacidad !== null)
                                                    kilos
                                             @endif
                                        </td>
                                
                                        <form class="formulario-eliminar"
                                                action="{{ route('almacenes.destroy', $almacen->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                    @can('almacenes.editar')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('almacenes.edit', $almacen->id) }}"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('almacenes.eliminar')
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





















