@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Clientes</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Clientes</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                       
                        <a href="{{route('clientes.create')}}" class="btn btn-success">Nuevo Cliente</a>
                    
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
                                        <th>CI</th>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientes as $cliente)
                                    <tr class="gradeX">
                                        <td>{{$cliente->ci}}</td>
                                        <td>{{$cliente->nombre}}</td>
                                        <td>{{$cliente->apellidos}}</td>
                                        <td>{{$cliente->telefono}}</td>
                                        <td>{{$cliente->correo}}</td>
                                
                                        <form class="formulario-eliminar"
                                                action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <td>
                                                   
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('clientes.edit', $cliente->id) }}"><i
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





















