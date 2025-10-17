@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Proveedores</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Proveedores</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                       @can('proveedores.crear')
                        <a href="{{ route('proveedores.create') }}" class="btn btn-success">Nuevo Proveedor</a>
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
                                        <th>Apellidos</th>
                                        <th>Telefono</th>
                                        <th>Dirección</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proveedores as $proveedor)
                                    <tr class="gradeX">
                                        <td>{{$proveedor->nombre}}</td>
                                        <td>{{$proveedor->apellidos}}</td>
                                        <td>{{$proveedor->telefono}}</td>
                                        <td>{{$proveedor->direccion}}</td>
                                
                                        <form class="formulario-eliminar"
                                                action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                  @can('proveedores.editar')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('proveedores.edit', $proveedor->id) }}"><i
                                                            class=" fa-solid fa-pen-to-square"></i></a>
                                                  @endcan
                                                  @can('proveedores.eliminar')
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





















