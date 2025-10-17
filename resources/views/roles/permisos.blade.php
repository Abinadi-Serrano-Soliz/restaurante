@extends('layouts.myLayout')
@section('content')
<style>
/* Switch estilo toggle */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0;
  right: 0; bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #007bff; /* Color azul tipo primary */
}

input:checked + .slider:before {
  transform: translateX(26px);
}
</style>
<div class="container mt-4">
    <div class="card shadow-lg rounded">
        <div class="card-header  text-gris">
            <h3 class="mb-0">Permisos para el: {{ $role->name }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.asignarPermisos', $role->id) }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr class="text-center">
                                <th>Módulo</th>
                                <th>Ver</th>
                                <th>Crear</th>
                                <th>Actualizar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $module => $permsForModule)
                                <tr>
                                    <td style="text-transform: capitalize;">{{ $module }}</td>
                                    @foreach(['ver','crear','actualizar','eliminar'] as $action)
                                        @php
                                            $permName = "{$module}.{$action}";
                                            $perm = $permsForModule->firstWhere('name', $permName);
                                        @endphp
                                        <td class="text-center">
                                            @if($perm)
                                            <label class="switch">
                                                <input type="checkbox" name="permisos[]" value="{{ $perm->name }}"
                                                    {{ in_array($perm->name, $rolePermisos) ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center  mt-3">
                    <button class="btn btn-success "style="margin-right:10px;">Guardar</button>
                     <button type="button" class="btn btn-primary mr-2" id="toggleSelectBtn">Seleccionar todos</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                    
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('toggleSelectBtn');
    const permisos = document.querySelectorAll('input[name="permisos[]"]');

    toggleBtn.addEventListener('click', function() {
        // Verificamos si todos los checkboxes están seleccionados
        const allChecked = Array.from(permisos).every(cb => cb.checked);

        permisos.forEach(cb => {
            cb.checked = !allChecked; // Si todos estaban seleccionados, desmarcamos; si no, marcamos
        });

        // Cambiamos el texto del botón
        toggleBtn.textContent = allChecked ? 'Seleccionar todos' : 'Deseleccionar todos';
    });
</script>
@endsection