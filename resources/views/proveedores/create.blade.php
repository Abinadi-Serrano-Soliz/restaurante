@extends('layouts.myLayout')

@section('content')
   <div class="d-flex justify-content-center align-items-center vh-100">
                        <div class="col-lg-9 col-md-9">
                            <div class="ibox-title">
                                <h5>Crear Proveedor</h5>
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

                                <form action="{{ route('proveedores.store') }}" method="POST">
                                    @csrf
                      
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Nombre:* </label>

                                        <div class="col-lg-10">
                                             <input type="text" placeholder="Nombre" name="nombre"
                                                class="form-control"value="{{ old('nombre') }}"> 
                                        </div>
                                    </div>
                                    @error('nombre')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror

                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Apellidos: </label>

                                        <div class="col-lg-10">
                                             <input type="text" placeholder="Apellidos" name="apellidos"
                                                class="form-control"value="{{ old('apellidos') }}"> 
                                        </div>
                                    </div>
                                    @error('apellidos')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror

                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Teléfono:* </label>

                                        <div class="col-lg-10">
                                             <input type="number" placeholder="Telefono" name="telefono"
                                                class="form-control"value="{{ old('telefono') }}"> 
                                        </div>
                                    </div>
                                    @error('telefono')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror

                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Dirección: </label>

                                        <div class="col-lg-10">
                                             <input type="text" placeholder="Direccion" name="direccion"
                                                class="form-control"value="{{ old('direccion') }}"> 
                                        </div>
                                    </div>
                                    @error('correo')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror

                                    <div class=" d-flex justify-content-end pr-5 pt-3">
                                            <div class="p-2">
                                                <button class="btn btn-sm btn-success" type="submit">Guardar</button>
                                            </div>
                                            
                                            <div class="p-2">
                                            <a href="{{ route('proveedores.index') }}" class="btn btn-sm btn-secondary ">Cancelar</a>
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







