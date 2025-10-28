@extends('layouts.myLayout')

@section('content')

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-lg-9 col-md-9">
        <div class="ibox-title">
            <h5>Editar Compra</h5>
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
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

            <form action="{{ route('compras.update', $compra->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Proveedor --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Proveedor*:</label>
                    <div class="col-lg-9">
                        <select name="id_proveedor" class="form-control select-producto" required>
                            <option value="">-- Seleccione un proveedor --</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}"
                                    {{ $compra->id_proveedor == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }}
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
                               value="{{ old('fecha_compra', $compra->fecha_compra) }}" required>
                    </div>
                </div>

                {{-- Productos dinámicos --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Agregar productos*:</label>
                    <div class="col-lg-9">
                        <button type="button" id="add-product" class="btn btn-sm btn-primary d-none">
                            <i class="fa fa-plus"></i> Agregar producto
                        </button>
                    </div>
                </div>

                <div id="productos-container"></div>

                {{-- Monto total --}}
                <div class="form-group row mt-4">
                    <label class="col-lg-3 col-form-label">Monto total*:</label>
                    <div class="col-lg-9">
                        <input type="number" class="form-control"id="monto_total_visible" value="{{ $compra->monto_total }}" readonly>
                        <input type="hidden" name="monto_total" id="monto_total" value="{{ $compra->monto_total }}">
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end pr-5 pt-3">
                    <div class="p-2">
                        <button class="btn btn-sm btn-success" type="submit">Actualizar Compra</button>
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
    const productosCompra = @json($compra->producto_almacenes);

    const container = document.getElementById('productos-container');

    // Función para agregar producto (nuevo o precargado)
    function agregarProducto(p = null) {
        let div = document.createElement('div');
        div.classList.add('border', 'rounded', 'p-3', 'mb-2');
        div.setAttribute('id', 'producto-' + contador);

        // Opciones del select
        let options = `<option value="">-- Seleccione --</option>`;
        productos.forEach(prod => {
            let selected = p && prod.id === p.id ? 'selected' : '';
            // Si no selecciona con el ID correcto del pivot
            if (p && prod.id === p.id) selected = 'selected';
            options += `<option value="${prod.id}" ${selected}>
                            ${prod.producto.nombre} (${prod.almacen.nombre})
                        </option>`;
        });
         
        let cantidad = p ? p.pivot.cantidad_compra : '';
        let precio = p ? parseFloat(p.pivot.precio_unitario).toFixed(2) : '';
        let subtotal = p ? parseFloat(p.pivot.subtotal).toFixed(2) : '';

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
                    <label>Cantidad</label>
                    <input type="number" name="productos[${contador}][cantidad_compra]" class="form-control cantidad" value="${cantidad}" step="0.01" min="0" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Precio Kg/L/Unidad</label>
                    <input type="number" name="productos[${contador}][precio_unitario]" class="form-control precio" step="0.01" min="0" value="${precio}"required >
                </div>
                <div class="col-md-3 mb-2">
                    <label>Subtotal</label>
                    <input type="number" name="productos[${contador}][subtotal]" class="form-control subtotal" step="0.01" min="0" value="${subtotal}" readonly>
                </div>
            </div>
        `;

        container.appendChild(div);
        contador++;

         
    }

   
 // Precargar productos existentes
        productosCompra.forEach(p => agregarProducto(p));

        // Ejecutar calcularTotal después de un pequeño retraso para asegurar que el DOM esté listo
       
        setTimeout(() => {
            //select2
             $('.select-producto').select2({
                width: '100%',
                placeholder: '-- Seleccione un producto --',
                allowClear: true
            });
            calcularTotal();
        }, 100);

        

    // Botón agregar nuevo producto
    document.getElementById('add-product').addEventListener('click', () => agregarProducto());

    // Calcular precio unitario y total
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            const row = e.target.closest('.row');
            const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(row.querySelector('.precio').value) || 0;

            let subtotal = 0;
            if (cantidad > 0){
                subtotal = cantidad * precio;
            }

            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            calcularTotal();
        }
    });

    function calcularTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(s => {
        let val = parseFloat(s.value);
        if (isNaN(val)) val = 0;
        total += val;
    });
    document.getElementById('monto_total').value = total.toFixed(2);
    document.getElementById('monto_total_visible').value = total.toFixed(2);
}

    // Calcular total inicial
    calcularTotal();

    // Ocultar alertas después de 3s
    setTimeout(() => {
        let alerta = document.getElementById('alerta');
        if (alerta) alerta.style.display = 'none';
    }, 3000);
</script>

@endsection
