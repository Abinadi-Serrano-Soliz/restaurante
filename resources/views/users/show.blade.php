@extends('layouts.myLayout')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Detalles del Usuario</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active"><strong>Detalle del usuario</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Información general del usuario</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        <a class="close-link"><i class="fa fa-times"></i></a>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label><strong>CI:</strong></label>
                            <p>{{ $user->ci }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Nombre:</strong></label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Apellidos:</strong></label>
                            <p>{{ $user->lastname }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label><strong>Cargo:</strong></label>
                            <p>{{ $user->cargo }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Fecha de contratación:</strong></label>
                            <p>{{ \Carbon\Carbon::parse($user->fecha_contratacion)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Salario (Bs):</strong></label>
                            <p>{{ number_format($user->salario, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label><strong>Teléfono:</strong></label>
                            <p>{{ $user->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Correo Electrónico:</strong></label>
                            <p>{{ $user->email ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Rol Asignado:</strong></label>
                            <p>
                                @if ($user->roles->isNotEmpty())
                                    <span class="badge badge-primary">{{ $user->roles->pluck('name')->join(', ') }}</span>
                                @else
                                    <span class="badge badge-secondary">Sin rol asignado</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection
