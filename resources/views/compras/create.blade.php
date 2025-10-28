@extends('layouts.myLayout')

@section('content')

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-lg-9 col-md-9">
        <div class="ibox-title">
            <h5>Registrar Compra</h5>
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
            
            {{-- Errores --}}
            @if ($errors->any())
                <div id="alerta" class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif


            <form action="{{ route('compras.store') }}" method="POST" id="compra-form">
                @csrf

                {{-- Proveedor --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label ">Proveedor*:</label>
                    <div class="col-lg-9">
                        <select name="id_proveedor" class="form-control select-producto" required>
                            <option value="">-- Seleccione un proveedor --</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}"
                                    {{ old('id_proveedor') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }} {{ $proveedor->apellidos }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Fecha de compra --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Fecha de compra*:</label>
                    <div class="col-lg-9">
                        <input type="date" name="fecha_compra" class="form-control"
                               value="{{ old('fecha_compra', date('Y-m-d')) }}" required>
                    </div>
                </div>

                {{-- Productos dinámicos --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Agregar productos*:</label>
                    <div class="col-lg-9">
                        <button type="button" id="add-product" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> Agregar producto
                        </button>
                    </div>
                </div>

                <div id="productos-container"></div>

                {{-- Monto total --}}
                <div class="form-group row mt-4">
                    <label class="col-lg-3 col-form-label">Monto total*:</label>
                    <div class="col-lg-9">
                        <input type="number" name="monto_total" id="monto_total"
                               class="form-control" step="0.01" min="0" readonly>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end pr-5 pt-3">
                    <div class="p-2">
                        <button class="btn btn-sm btn-success" type="submit">Guardar Compra</button>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('compras.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    let contador = 0;
    const productos = @json($productos_almacen);
    const container = document.getElementById('productos-container');
    const form = document.getElementById('compra-form');

    
    document.getElementById('add-product').addEventListener('click', function () {
        let container = document.getElementById('productos-container');
        let div = document.createElement('div');
        div.classList.add('border', 'rounded', 'p-3', 'mb-2');
        div.setAttribute('id', 'producto-' + contador);

        let options = `<option value="">-- Seleccione --</option>`;
        productos.forEach(p => {
            options += `<option value="${p.id}">${p.producto.nombre} (${p.almacen.nombre})</option>`;
        });

        div.innerHTML = `
            <div class="d-flex justify-content-between">
                <strong>Producto ${contador + 1}</strong>
                <button type="button" class="btn btn-xs btn-danger"
                        onclick="document.getElementById('producto-${contador}').remove(); calcularTotal();">Quitar</button>
            </div>

            <div class="row mt-2">
                <div class="col-md-4 mb-2">
                    <label>Producto</label>
                    <select name="productos[${contador}][id_ProductoAlmacen]" class="form-control select-producto" required>
                        ${options}
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Cantidad(Kg/Li/Unidad)</label>
                    <input type="number" name="productos[${contador}][cantidad_compra]" class="form-control cantidad" step="0.01" min="0" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Precio (K,L,U)</label>
                    <input type="text" name="productos[${contador}][precio_unitario]" class="form-control precio" step="0.01" min="0" >
                </div>
                <div class="col-md-3 mb-2">
                    <label>Subtotal</label>
                    <input type="number" name="productos[${contador}][subtotal]" class="form-control subtotal" step="0.01" min="0" readonly>
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
    });
     document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2 en todos los elementos con la clase select-producto
        $('.select-producto').each(function() {
            $(this).select2({
                width: '100%',
                placeholder: '-- Seleccione un Registro --',
                allowClear: true
            });
        });
    });

     // Validación para requerir al menos un producto
        form.addEventListener('submit', function(e) {
            if(container.children.length == 0) {
                e.preventDefault();
            swal({
                    title: "¡Atención!",
                    text: "Debe agregar al menos un producto a la Compra.",
                    type: "warning",
                    confirmButtonText: "Aceptar"
                });
                return false;
            }
        });

    // Calcular subtotal (precio_unitario * cantidad)
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            const row = e.target.closest('.row');
            const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(row.querySelector('.precio').value) || 0;

            // Calcular el subtotal
            let subtotal = 0;
            if (cantidad > 0) {
                subtotal =  cantidad * precio ;
            }

            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            calcularTotal();
        }
    });

    // Calcular monto total sumando los subtotales
    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(s => {
            total += parseFloat(s.value) || 0;
        });
        document.getElementById('monto_total').value = total.toFixed(2);
    }

   

    // Ocultar alertas después de 3s
    setTimeout(() => {
        let alerta = document.getElementById('alerta');
        if (alerta) alerta.style.display = 'none';
    }, 3000);
</script>

@endsection
