@extends('layouts.myLayout')

@section('content')
   <div class="d-flex justify-content-center align-items-center vh-100">
                        <div class="col-lg-8 col-md-9">
                            <div class="ibox-title">
                                <h5>Crear Rol</h5>
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
                                <form action="{{ route('roles.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group row"><label class="col-lg-1 col-form-label">Nombre: </label>

                                        <div class="col-lg-10">
                                            <input type="text" placeholder="Nombre" name="name"required
                                                class="form-control"> 
                                        </div>
                                    </div>
                                     @error('name')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                    
                                    <div class=" d-flex justify-content-end pr-5 pt-3">
                                            <div class="p-2">
                                                <button class="btn btn-sm btn-success" type="submit">Guardar</button>
                                            </div>
                                            
                                            <div class="p-2">
                                            <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary ">Cancelar</a>
                                            </div>
                                            
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
@endsection







