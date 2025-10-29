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
                                     <div class="form-group row">
                                        <label class="col-lg-1 col-form-label">Rol: </label>
                                        <div class="col-lg-10">
                                            <select id="roleSelect" class="form-control select-producto">
                                                <option value="">--selecciona un rol--</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-3">
                                        <label class="col-lg-1 col-form-label">Roles asignados: </label>
                                        <div class="col-lg-10">
                                            <div id="rolesContainer" class="border rounded p-3 bg-light" style="min-height: 80px;">
                                                <!-- Los roles se agregarán dinámicamente aquí -->
                                            </div>
                                            <!-- Campos hidden para enviar los roles al servidor -->
                                            <div id="hiddenRolesContainer">
                                                <!-- Los inputs hidden se agregarán dinámicamente aquí -->
                                            </div>
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

        <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>            


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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2
    $('.select-producto').select2({
        width: '100%',
        placeholder: '-- Selecciona un rol --',
        allowClear: true
    });

    const rolesContainer = document.getElementById('rolesContainer');
    const hiddenRolesContainer = document.getElementById('hiddenRolesContainer');

    // ✅ Evento correcto para detectar selección en Select2
    $('#roleSelect').on('select2:select', function (e) {
        const selectedRole = e.params.data.id;

        if (!selectedRole) return;

        // Evitar roles duplicados
        const existingBadge = rolesContainer.querySelector(`[data-role="${selectedRole}"]`);
        if (existingBadge) {
            Swal.fire({
                icon: 'warning',
                title: 'Rol duplicado',
                text: 'Este rol ya está asignado al usuario',
                confirmButtonText: 'Entendido'
            });
            $(this).val('').trigger('change'); // limpiar visualmente el select
            return;
        }

        // Crear badge visual
        const badge = document.createElement('span');
        badge.className = 'badge badge-primary badge-lg mr-2 mb-2';
        badge.setAttribute('data-role', selectedRole);
        badge.style.fontSize = '14px';
        badge.style.padding = '8px 12px';
        badge.innerHTML = `
            ${selectedRole.charAt(0).toUpperCase() + selectedRole.slice(1)}
            <button type="button" class="btn-close-badge ml-2" onclick="removeRole('${selectedRole}')" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">&times;</button>
        `;

        rolesContainer.appendChild(badge);

        // Crear input oculto para enviar al servidor
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'roles[]';
        hiddenInput.value = selectedRole;
        hiddenInput.setAttribute('data-role-input', selectedRole);
        hiddenRolesContainer.appendChild(hiddenInput);

        // Resetear select visualmente
        $(this).val('').trigger('change');
    });
});

// Función global para eliminar rol
function removeRole(roleName) {
    const badge = document.querySelector(`[data-role="${roleName}"]`);
    if (badge) badge.remove();

    const hiddenInput = document.querySelector(`[data-role-input="${roleName}"]`);
    if (hiddenInput) hiddenInput.remove();
}
</script>

<style>
.badge {
    display: inline-flex;
    align-items: center;
}

.btn-close-badge:hover {
    opacity: 0.8;
}

#rolesContainer:empty::before {
    content: 'No hay roles asignados';
    color: #6c757d;
    font-style: italic;
}
</style>

@endsection



