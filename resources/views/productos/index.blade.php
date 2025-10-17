@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Productos</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Productos</strong>
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
                        <a href="{{ route('productos.create') }}" class="btn btn-success">Nuevo Producto</a>
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
                                        <th>Descripción</th>
                                        <th>Categoría</th>
                                        <th>Stock en Almacenes</th>
                                        <th width="">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productos as $producto)
                                    <tr class="gradeX">
                                        
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                                        <td>
                                            @foreach($producto->almacenes as $almacen)
                                                <strong>{{ $almacen->nombre }}</strong>: 
                                                {{ $almacen->pivot->stock_actual }} {{ $almacen->pivot->unidad_medida }} 
                                                <small>(Mín: {{ $almacen->pivot->stock_minimo }})</small><br>
                                            
                                            @endforeach
                                        </td>
                                        <td>
                                            <form class="formulario-eliminar" action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                
                                                    @can('usuarios.actualizar')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('productos.edit', $producto->id) }}"><i
                                                            class=" fa-solid fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('usuarios.eliminar')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                    @endcan
                                            
                                            </form>
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