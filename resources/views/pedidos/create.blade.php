@extends('layouts.myLayout')

@section('content')
<div class="container mt-5">
 <div class="ibox-content">
    <h2 class="mb-5 text-center"><strong>Registrar Pedido</strong></h2>
    
    <form id="pedidoForm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="id_cliente" class="form-label">Cliente:*</label>
                <select name="id_cliente" id="id_cliente" class="form-control select-producto" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->ci }} {{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="id_repartidor" class="form-label">Repartidor:</label>
                <select name="id_repartidor" id="id_repartidor" class="form-control select-producto">
                    <option value="">Seleccione un repartidor</option>
                    @foreach($repartidores as $repartidor)
                        <option value="{{ $repartidor->id }}">{{ $repartidor->nombre }} {{ $repartidor->apellidos }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="direccion_entrega" class="form-label">Dirección de entrega:*</label>
            <input type="text" name="direccion_entrega" id="direccion_entrega" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones:</label>
            <textarea name="observaciones" id="observaciones" rows="2" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ubicación en el mapa</label>
            <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="latitud" class="form-label">Latitud</label>
                <input type="text" id="latitud" name="latitud" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label for="longitud" class="form-label">Longitud</label>
                <input type="text" id="longitud" name="longitud" class="form-control" readonly>
            </div>
        </div>

        <hr>
        <h4 class="mb-3">Detalles del Pedido</h4>

        <div id="menusContainer">
            <div class="row g-2 menu-row">
                <div class="col-md-5">
                    <label>Menú</label>
                    <span class="stock-info text-primary small mb-1 ml-4" style="font-weight: bold;"></span>
                    <select name="menus[0][id_menu]" class="form-control select-producto" required>
                        <option value="">Seleccione un menú</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" data-precio="{{ $menu->precio }}" data-stock="{{ $menu->stock_menu }}">{{ $menu->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Cantidad</label>
                    <input type="number" name="menus[0][cantidad_pedido]" class="form-control cantidad" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <label>Precio</label>
                    <input type="number" name="menus[0][precio_unitario]" class="form-control precio" step="0.01" readonly>
                </div>
                <div class="col-md-2">
                    <label>Subtotal</label>
                    <input type="number" name="menus[0][subtotal]" class="form-control subtotal" readonly>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-menu">X</button>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="button" class="btn btn-success" id="btnAddMenu">+ Agregar Menú</button>
        </div>

        <div class="mt-4 text-end">
            <h5>Monto total: <span id="montoTotal">0.00</span> Bs</h5>
            <input type="hidden" name="monto_total" id="monto_total_input">
        </div>

        <div class="mt-4 text-center">
            <button type="button" id="btnPagarLibelula" class="btn btn-primary w-20">
                <i class="fa fa-qrcode"></i> Confirmar Pedido
            </button>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let menuIndex = 1;

    $('.select-producto').select2({
        width: '100%',
        placeholder: '-- Seleccione un Registro --',
        allowClear: true
    });

    // --- Calcular totales ---
    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.menu-row').forEach(row => {
            const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(row.querySelector('.precio').value) || 0;
            const subtotal = cantidad * precio;
            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            total += subtotal;
        });
        document.getElementById('montoTotal').innerText = total.toFixed(2);
          // Actualizar el input hidden
    document.getElementById('monto_total_input').value = total.toFixed(2);
    }

    $(document).on('change', 'select[name^="menus"]', function() {
        const selected = this.selectedOptions[0];
        const precio = selected ? selected.getAttribute('data-precio') : 0;
        const stock = selected ? selected.getAttribute('data-stock') : null; // 🔹 ahora sí obtenemos el stock
        const row = $(this).closest('.menu-row')[0];
        
        row.querySelector('.precio').value = precio;
        
            // 🔹 Mostrar stock arriba del select
        const stockInfo = row.querySelector('.stock-info');
        if (stock !== null) {
            stockInfo.textContent = `🧾 Stock disponible: ${stock} unidades`;
            stockInfo.style.color = stock > 5 ? 'green' : 'red';
        } else {
            stockInfo.textContent = '';
        }
        updateTotals();

        
    });

    $(document).on('input', '.cantidad', function() {
        updateTotals();
    });

    document.getElementById('btnAddMenu').addEventListener('click', function() {
        const container = document.getElementById('menusContainer');
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'g-2', 'menu-row');

        newRow.innerHTML = `
            <div class="col-md-5">
                <label>Menú</label>
                <span class="stock-info text-primary small mb-1 ml-4" style="font-weight: bold;"></span> <!-- 🔹 agregado -->
                <select name="menus[${menuIndex}][id_menu]" class="form-control select-producto" required>
                    <option value="">Seleccione un menú</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" data-precio="{{ $menu->precio }}"data-stock="{{ $menu->stock_menu }}">{{ $menu->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Cantidad</label>
                <input type="number" name="menus[${menuIndex}][cantidad_pedido]" class="form-control cantidad" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <label>Precio</label>
                <input type="number" name="menus[${menuIndex}][precio_unitario]" class="form-control precio" step="0.01" readonly>
            </div>
            <div class="col-md-2">
                <label>Subtotal</label>
                <input type="number" name="menus[${menuIndex}][subtotal]" class="form-control subtotal" readonly>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove-menu">X</button>
            </div>
        `;
        container.appendChild(newRow);
        $(newRow).find('.select-producto').select2({
            width: '100%',
            placeholder: '-- Seleccione un producto --',
            allowClear: true
        });
        menuIndex++;
    });

    $(document).on('click', '.btn-remove-menu', function() {
        if (document.querySelectorAll('.menu-row').length > 1) {
            $(this).closest('.menu-row').remove();
            updateTotals();
        }
    });

    // --- MAPA ---
    const defaultPosition = [-16.2902, -63.5887];
    const map = L.map('map').setView(defaultPosition, 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);
    const marker = L.marker(defaultPosition, {draggable: true}).addTo(map);
    document.getElementById('latitud').value = defaultPosition[0];
    document.getElementById('longitud').value = defaultPosition[1];

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitud').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitud').value = e.latlng.lng.toFixed(6);
    });
        // === 🔹 BOTÓN "MI UBICACIÓN" ===
    L.control.locate({
        position: 'topleft',        // posición del botón
        flyTo: true,                // anima el mapa hasta tu ubicación
        icon: 'fa fa-location-arrow', // usa icono de FontAwesome
        strings: {
            title: "Ir a mi ubicación actual" // texto al pasar el mouse
        },
        locateOptions: {
            enableHighAccuracy: true // usa GPS más preciso
        }
    }).addTo(map);

        map.on('locationfound', function(e) {
        marker.setLatLng(e.latlng);
        map.setView(e.latlng, 16);
        document.getElementById('latitud').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitud').value = e.latlng.lng.toFixed(6);
    });

    map.on('locationerror', function() {
        Swal.fire('Error', 'No se pudo obtener tu ubicación actual.', 'error');
    });

    // --- BOTÓN PRINCIPAL ---
    document.getElementById('btnPagarLibelula').addEventListener('click', function() {
        const form = document.getElementById('pedidoForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        Swal.fire({
            title: 'Procesando pedido...',
            text: 'Por favor espere mientras se confirma el pago.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('{{ route('pedidos.store') }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
        })
        .then(r => r.json())
        .then(data => {
    Swal.close();
    if (data.success) {
        Swal.fire({
            icon: 'success',
            title: '¡Pedido registrado correctamente!',
            html: `
                <p><b>Monto total:</b> ${data.monto_total} Bs</p>
                <p><b>Escanea este QR:</b></p>
                <img src="${data.qr_url}" alt="QR de pago" width="250" height="250" style="border:1px solid #ddd; border-radius:10px; padding:5px;">
                <p style="margin-top:10px;">Pedido Nro: <b>${data.id_pedido}</b></p>
            `,
            confirmButtonText: 'Ir a mis pedidos'
        }).then(() => {
            window.location.href = "{{ route('pedidos.index') }}";
        });
    } else {
        Swal.fire('Error', data.error || data.message || 'Error al registrar el pedido', 'error');
    }
        })
        .catch(() => {
            Swal.close();
            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
        });
    });
});
</script>
@endsection
