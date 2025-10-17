@extends('layouts.login')

@section('content')

  <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <img src="tropical-logo.png" 
                alt=""
                 style="width: 200px "
            
                >
                 
            </div>
            <h3>Inicio de Sesión</h3>
           
            <form class="m-t" role="form" method="post" action="{{route('auth.login')}}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="email" required>
                </div>
                @error('email')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
                {{-- Password con ojito --}}
                <div class="form-group position-relative">
                    <input id="password" type="password" name="password" class="form-control" placeholder="Password" >
                    <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
                 @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <a href="{{ route('password.request') }}"><small>Olvidaste tu Contraseña?</small></a>
            </form>
            <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
        </div>
    </div> 
    {{--Script para ocultar errores automáticamente --}}
<script>
    // Espera 5 segundos y luego oculta los mensajes de error
    setTimeout(() => {
        document.querySelectorAll('.text-danger').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500); // elimina del DOM
        });
    }, 5000);

    // Función para mostrar/ocultar contraseña
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('togglePassword');
        const pwd = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        btn.addEventListener('click', function () {
            const isHidden = pwd.type === 'password';
            pwd.type = isHidden ? 'text' : 'password';

            // Cambia icono
            if (isHidden) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                btn.setAttribute('aria-label', 'Ocultar contraseña');
                btn.setAttribute('aria-pressed', 'true');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                btn.setAttribute('aria-label', 'Mostrar contraseña');
                btn.setAttribute('aria-pressed', 'false');
            }
        });
    });
</script>
@endsection
