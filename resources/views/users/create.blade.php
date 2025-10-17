@extends('layouts.myLayout')

@section('content')
   <div class="d-flex justify-content-center align-items-center vh-100">
                        <div class="col-lg-8 col-md-9">
                            <div class="ibox-title">
                                <h5>Crear Usuario</h5>
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
                                <form action="{{ route('users.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">CI:* </label>

                                        <div class="col-lg-10">
                                            <input type="text"value="{{ old('ci') }}" placeholder="CI" name="ci"
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('ci')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Nombre:* </label>

                                        <div class="col-lg-10">
                                            <input type="text"value="{{ old('name') }}" placeholder="Nombre" name="name"
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('name')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Apellidos:* </label>

                                        <div class="col-lg-10">
                                            <input type="text"value="{{ old('lastname') }}" placeholder="Apellidos" name="lastname"
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('lastname')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Cargo:* </label>

                                        <div class="col-lg-10">
                                            <input type="text"value="{{ old('cargo') }}" placeholder="Cargo" name="cargo"
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('cargo')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-2 col-form-label">Fecha de Contratación:* </label>

                                        <div class="col-lg-9">
                                            <input type="date"value="{{ old('fecha_contratacion') }}" name="fecha_contratacion"
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('fecha_contratacion')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Salario:* </label>

                                        <div class="col-lg-10">
                                            <input type="text"value="{{ old('salario') }}" placeholder="Salario" name="salario"
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('salario')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Telefono: </label>

                                        <div class="col-lg-10">
                                            <input type="number" value="{{ old('telefono') }}" placeholder="Telefono" name="telefono" 
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('telefono')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Email: </label>

                                        <div class="col-lg-10">
                                            <input type="email" value="{{ old('email') }}" placeholder="solo si tendra Acceso al Sistema" name="email" 
                                                class="form-control"> 
                                        </div>
                                    </div>
                                    @error('email')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class=" col-form-label">Contraseña: </label>

                                        <div class="col-lg-10">
                                            <input type="password" placeholder="solo Si va a acceder al sistema" name="password" 
                                                class="form-control"></div>
                                    </div>
                                    @error('password')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Rol: </label>
                                        <div class="col-lg-10">
                                            <select name="role" class="form-control" >
                                                <option value="">--selecciona un rol--</option>
                                               @foreach ($roles as $role)
                                                    <option value="">
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class=" d-flex justify-content-end pr-5 pt-3">
                                            <div class="p-2">
                                                <button class="btn btn-sm btn-success" type="submit">Guardar</button>
                                            </div>
                                            
                                            <div class="p-2">
                                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary ">Cancelar</a>
                                            </div>
                                            
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

    <script>

    // Espera 5 segundos y luego oculta los mensajes de error
    setTimeout(() => {
        document.querySelectorAll('.text-danger').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500); // elimina del DOM
        });
    }, 5000);
</script>
@endsection



