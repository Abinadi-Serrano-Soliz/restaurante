@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Usuarios</h2>
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
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr class="gradeX">
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->roles->pluck('name')->join(', ') }}</td>
                                
                                        <form class="formulario-eliminar"
                                                action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                    @can('usuarios.ver')
                                                    <a class="btn btn-warning btn-sm" 
                                                    href="{{ route('users.show', $user->id) }}">
                                                    <i class="fa fa-eye"></i>
                                                    </a>
                                                    @endcan
                                                    @can('usuarios.editar')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('users.edit', $user->id) }}"><i
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





















