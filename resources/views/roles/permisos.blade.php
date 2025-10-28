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
                        <thead class="text-center">
                            <tr class="">
                                <th class="pb-4">MÓDULO</th>
                                <th class="pb-4">LISTAR</th>
                                <th class="pb-4">VER</th>
                                <th class="pb-4">CREAR</th>
                                <th width="10">ASIGNAR PERMISOS</th>
                                <th class="pb-4">EDITAR</th>
                                <th class="pb-4">ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $module => $permsForModule)
                                <tr>
                                    <td style="text-transform: capitalize;"class="text-center">{{ $module }}</td>
                                    @foreach(['listar','ver','crear','permisos.asignar','editar','eliminar'] as $action)
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionamos todas las filas de la tabla (cada módulo)
    const filas = document.querySelectorAll('tbody tr');

    filas.forEach(fila => {
        // Obtenemos el checkbox del permiso 'listar'
        const listarCheckbox = fila.querySelector('input[value$=".listar"]');

        if (listarCheckbox) {
            listarCheckbox.addEventListener('change', function() {
                // Todos los checkboxes de esta fila
                const checkboxes = fila.querySelectorAll('input[type="checkbox"]');

                // Si desactiva 'listar', desmarcamos y bloqueamos los demás
                if (!this.checked) {
                    checkboxes.forEach(cb => {
                        if (cb !== listarCheckbox) {
                            cb.checked = false;
                            cb.disabled = true;
                        }
                    });
                } else {
                    // Si se vuelve a activar 'listar', habilitamos los demás
                    checkboxes.forEach(cb => {
                        cb.disabled = false;
                    });
                }
            });

            // Lógica inicial: si 'listar' está desactivado al cargar, bloquear otros
            if (!listarCheckbox.checked) {
                const checkboxes = fila.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => {
                    if (cb !== listarCheckbox) {
                        cb.disabled = true;
                    }
                });
            }
        }
    });
});
</script>
@endsection