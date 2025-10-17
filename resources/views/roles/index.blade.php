@extends('layouts.myLayout')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Roles</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Rol</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">                 
                        <a href="{{ route('roles.create') }}" class="btn btn-success">Nuevo Rol</a>
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
                                        <th class="text-center">Rol</th>
                                        <th  class="text-center"> Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach($roles as $role)
                                    <tr class="gradeX">
                                        <td>{{$role->name}}</td>
                                        <form class="formulario-eliminar" action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('roles.permisos', $role->id) }}"><i
                                                            class="fa-solid fa-key"></i></a>

                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('roles.edit', $role->id) }}"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>

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

