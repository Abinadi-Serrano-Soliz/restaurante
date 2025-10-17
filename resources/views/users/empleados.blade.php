@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Empleados</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Usuarios</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        @can('usuarios.crear')
                        <a href="{{ route('users.create') }}" class="btn btn-success">Nuevo Usuario</a>
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
                                    <tr>
                                        <th>CI</th>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Cargo</th>
                                        <th>Salario</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($empleados as $empleado)
                                    <tr class="gradeX">
                                        <td>{{$empleado->ci}}</td>
                                        <td>{{$empleado->name}}</td>
                                        <td>{{$empleado->lastname}}</td>
                                        <td>{{$empleado->cargo}}</td>
                                        <td>{{$empleado->salario}}</td>
                                
                                        <form class="formulario-eliminar"
                                                action="{{ route('users.destroy', $empleado->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                    @can('usuarios.actualizar')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('users.edit', $empleado->id) }}"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('usuarios.eliminar')
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





















