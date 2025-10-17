@extends('layouts.myLayout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Errores --}}
            @if ($errors->any())
                <div id="alerta" class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header">
                    <h5>Mi Perfil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label>Correo</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" disabled>
                        </div>

                        <hr>
                        <h3><strong>Cambiar contraseña</strong></h3>

                        <div class="form-group mb-3">
                            <label>Contraseña actual</label>
                            <input type="password" name="old_password" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label>Nueva contraseña</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label>Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="d-flex justify-content-end">
                             <div class="p-2">
                                    <a href="{{ route('proveedores.index') }}" class="btn  btn-secondary ">Cancelar</a>
                             </div>
                             <div class="p-2">
                                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                             </div>
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        var alerta = document.getElementById('alerta');
        if(alerta){
            alerta.style.display = 'none';
        }
    }, 3000); // 3000ms = 3 segundos
</script>
@endsection