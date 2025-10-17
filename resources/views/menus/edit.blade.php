@extends('layouts.myLayout')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-lg-9 col-md-9">
        <div class="ibox-title">
            <h5>Editar Menú</h5>
        </div>

        <div class="ibox-content">
            <form id="menu-form" action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Nombre del menú:</label>
                    <div class="col-lg-9">
                        <input type="text" name="nombre" value="{{ old('nombre', $menu->nombre) }}" class="form-control" required>
                    </div>
                    @error('nombre')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Imagen --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Imagen actual:</label>
                    <div class="col-lg-9">
                        @if($menu->imagen)
                            <img src="{{ asset('storage/' . $menu->imagen) }}" alt="{{ $menu->nombre }}" width="150" height="150" class="img-thumbnail mb-2" style="object-fit: cover;">
                        @else
                            <p>No hay imagen registrada</p>
                        @endif
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
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $menu->descripcion) }}</textarea>
                    </div>
                    @error('descripcion')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Precio --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Precio:</label>
                    <div class="col-lg-9">
                        <input type="number" name="precio" step="0.01" class="form-control" value="{{ old('precio', $menu->precio) }}" required>
                    </div>
                    @error('precio')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Stock --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Stock Menú:</label>
                    <div class="col-lg-9">
                        <input type="number" name="stock_menu" class="form-control" value="{{ old('stock_menu', $menu->stock_menu) }}">
                    </div>
                    @error('stock_menu')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
                {{-- Estado --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Estado:</label>
                    <div class="col-lg-9">
                        <select name="estado" class="form-control" required>
                            <option value="1" {{ old('estado', $menu->estado) ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estado', $menu->estado) ? '' : 'selected' }}>Inactivo</option>
                        </select>
                    </div>
                    @error('estado')
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

                <div id="productos-container">
                    @php
                        $menuProductos = $menu->producto_almacenes ?? collect();
                    @endphp

                   @foreach($menuProductos as $index => $p)
                        <div class="border p-3 mb-2 rounded">
                            <div class="d-flex justify-content-between">
                                <strong>Producto {{ $index + 1 }}</strong>
                                <button type="button" class="btn btn-danger btn-xs" onclick="this.closest('div.border').remove()">Quitar</button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Producto:</label>
                                    {{-- Agregamos la clase select-producto aquí --}}
                                    <select name="productos[{{ $index }}][id_ProductoAlmacen]" class="form-control select-producto" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($productos_almacen as $producto)
                                            <option value="{{ $producto->id }}" {{ $producto->id == $p->id ? 'selected' : '' }}>
                                                {{ $producto->producto->nombre }} ({{ $producto->almacen->nombre }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Cantidad(Kg):</label>
                                    <input type="number" step="0.01" name="productos[{{ $index }}][cantidad]" class="form-control" value="{{ $p->pivot->cantidad }}" min="0" required>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Botones --}}
                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-success">Actualizar Menú</button>
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    
    let contador = {{ $menuProductos->count() }};
    const productos = @json($productos_almacen);
    const container = document.getElementById('productos-container');


    document.getElementById('add-product').addEventListener('click', function() {

  
        let div = document.createElement('div');
        div.classList.add('border', 'p-3', 'mb-2', 'rounded');
        div.innerHTML = `
            <div class="d-flex justify-content-between">
                <strong>Ingrediente ${contador + 1}</strong>
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
                    <label>Cantidad(Kg/Li):</label>
                    <input type="number" step="0.01" name="productos[${contador}][cantidad]" class="form-control" min="0" required>
                </div>
            </div>
        `;
        container.appendChild(div);
        contador++;

    });

    setTimeout(() => {
             $('.select-producto').select2({
                width: '100%',
                placeholder: '-- Seleccione un producto --',
                allowClear: true
            });
        }, 100);

    // Validación con SweetAlert v1
    const form = document.getElementById('menu-form');
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

    // Ocultar mensajes de error después de unos segundos
    setTimeout(() => {
        document.querySelectorAll('.text-danger').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>

@endsection
