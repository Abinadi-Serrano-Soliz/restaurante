@extends('layouts.myLayout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Menús</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <strong>Listado de Menús</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <a href="{{ route('menus.create') }}" class="btn btn-success">Nuevo Menú</a>
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
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $menu)
                                    <tr class="gradeX text-center">
                                        <td>
                                            @if($menu->imagen)
                                                <img src="{{ asset('storage/' . $menu->imagen) }}" alt="{{ $menu->nombre }}" style="max-width:150px; height:150px; object-fit: contain;" class="img-thumbnail">
                                            @else
                                                <img src="{{ asset('img/sin-imagen.png') }}" alt="Sin imagen" width="80" height="80" class="img-thumbnail">
                                            @endif
                                        </td>
                                        <td>{{ $menu->nombre }}</td>
                                        <td>{{ $menu->descripcion ?? '-' }}</td>
                                        <td>{{ number_format($menu->precio, 2) }} Bs</td>
                                        <td>{{ $menu->stock_menu }}</td>
                                        <td>
                                            @if($menu->estado)
                                                <span class="badge badge-primary">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form class="formulario-eliminar" action="{{ route('menus.destroy', $menu->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                
                                                <a class="btn btn-warning btn-sm" href="{{ route('menus.show', $menu->id) }}" title="Ver">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm" href="{{ route('menus.edit', $menu->id) }}" title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
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
