@extends('layouts.myLayout')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-lg-9 col-md-9">
        <div class="ibox-title">
            <h5>Crear Menú</h5>
        </div>

        <div class="ibox-content">
            <form id="menu-form" action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nombre --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Nombre del menú:</label>
                    <div class="col-lg-9">
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    @error('nombre')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Imagen --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Imagen:</label>
                    <div class="col-lg-9">
                        <input type="file" name="imagen" class="form-control" accept="image/*">
                    </div>
                    @error('imagen')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Descripción:</label>
                    <div class="col-lg-9">
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                    @error('descripcion')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Precio --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Precio:</label>
                    <div class="col-lg-9">
                        <input type="number" name="precio" step="0.01" class="form-control" required>
                    </div>
                    @error('precio')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Stock --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Stock Menú:</label>
                    <div class="col-lg-9">
                        <input type="text" name="stock_menu" class="form-control">
                    </div>
                    @error('stock_menu')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Productos --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Productos del menú:</label>
                    <div class="col-lg-9">
                        <button type="button" id="add-product" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> Agregar producto
                        </button>
                    </div>
                </div>

                <div id="productos-container"></div>

                {{-- Botones --}}
                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-success">Guardar Menú</button>
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para productos dinámicos --}}
<script>
let contador = 0;
const productos = @json($productos_almacen);
const container = document.getElementById('productos-container');
const form = document.getElementById('menu-form');

document.getElementById('add-product').addEventListener('click', () => agregarProducto());

// Función para agregar producto
function agregarProducto() {
    let div = document.createElement('div');
    div.classList.add('border', 'p-3', 'mb-2', 'rounded');
    div.innerHTML = `
        <div class="d-flex justify-content-between">
            <strong>Producto ${contador + 1}</strong>
            <button type="button" class="btn btn-danger btn-xs" onclick="this.closest('div.border').remove()">Quitar</button>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <label>Producto:</label>
                <select name="productos[${contador}][id_ProductoAlmacen]" class="form-control select-producto" required>
                    <option value="">-- Seleccione --</option>
                    ${productos.map(p => `<option value="${p.id}">${p.producto.nombre} (${p.almacen.nombre})</option>`).join('')}
                </select>
            </div>
            <div class="col-md-6">
                <label>Cantidad(Kg):</label>
                <input type="number" step="0.01" name="productos[${contador}][cantidad]" class="form-control" min="0.01" required>
            </div>
        </div>
    `;
    container.appendChild(div);
    contador++;

    // Activar búsqueda con Select2 en el nuevo select
        $(div).find('.select-producto').select2({
            width: '100%',
            placeholder: '-- Seleccione un producto --',
            allowClear: true
        });
}

// Validación para requerir al menos un producto
form.addEventListener('submit', function(e) {
    if(container.children.length === 0) {
        e.preventDefault();
       swal({
            title: "¡Atención!",
            text: "Debe agregar al menos un producto al menú.",
            type: "warning",
            confirmButtonText: "Aceptar"
        });
        return false;
    }
});
</script>

{{-- Ocultar errores automáticamente --}}
<script>
setTimeout(() => {
    document.querySelectorAll('.text-danger').forEach(el => {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
@endsection
