@extends('layouts.myLayout')

@section('content')
<div class="container mt-5">
 <div class="ibox-content">
    <h2 class="mb-5 text-center"><strong>Editar Pedido #{{ $pedido->id }}</strong></h2>
    
    {{-- Formulario de pedido --}}
    <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST" id="pedidoFormu">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="id_cliente" class="form-label">Cliente:*</label>
                <select name="id_cliente" id="id_cliente" class="form-control select-producto" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ $pedido->id_cliente == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->ci }} {{ $cliente->nombre }} {{ $cliente->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('id_cliente')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="id_repartidor" class="form-label">Repartidor:</label>
                <select name="id_repartidor" id="id_repartidor" class="form-control select-producto">
                    <option value="">Seleccione un repartidor</option>
                    @foreach($repartidores as $repartidor)
                        <option value="{{ $repartidor->id }}" {{ $pedido->id_repartidor == $repartidor->id ? 'selected' : '' }}>
                            {{ $repartidor->nombre }} {{ $repartidor->apellidos }} 
                            ({{ $repartidor->estado == 0 ? 'DISPONIBLE' : 'OCUPADO' }})
                        </option>
                    @endforeach
                </select>
                @error('id_repartidor')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="direccion_entrega" class="form-label">Dirección de entrega:*</label>
            <input type="text" name="direccion_entrega" id="direccion_entrega" class="form-control" value="{{ old('direccion_entrega', $pedido->direccion_entrega) }}" required>
            @error('direccion_entrega')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones:</label>
            <textarea name="observaciones" id="observaciones" rows="2" class="form-control">{{ old('observaciones', $pedido->observaciones) }}</textarea>
            @error('observaciones')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-6">
                <label  class="form-label">Estado:*</label>
                <select name="estado" class="form-control">
                    <option value="">Seleccione el Estado</option>
                      
                        <option value="0" {{ $pedido->estado == 0 ? 'selected' : '' }}>Pendiente</option>
                        <option value="1" {{ $pedido->estado == 1 ? 'selected' : '' }}>Enviado</option>
                        <option value="2" {{ $pedido->estado == 2 ? 'selected' : '' }}>Entregado</option>
                        <option value="3" {{ $pedido->estado == 3 ? 'selected' : '' }}>Cancelado</option>
                   
                </select>
                @error('estado')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
         </div>

        {{-- Latitud y Longitud --}}
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="latitud" class="form-label">Latitud</label>
                <input type="text" id="latitud" name="latitud" class="form-control" value="{{ old('latitud', $pedido->latitud) }}" readonly>
            </div>
            <div class="col-md-6">
                <label for="longitud" class="form-label">Longitud</label>
                <input type="text" id="longitud" name="longitud" class="form-control" value="{{ old('longitud', $pedido->longitud) }}" readonly>
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label class="form-label">Ubicación en el mapa</label>
            <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
        </div>

        <hr>
        <h4 class="mb-3">Detalles del Pedido</h4>

        <div id="menusContainer">
            @foreach($pedido->menus as $index => $menu)
                <div class="row g-2 menu-row">
                    <div class="col-md-5">
                        <label>Menú</label>
                        <select name="menus[{{ $index }}][id_menu]" class="form-control select-producto" disabled>
                            <option value="">Seleccione un menú</option>
                            @foreach($menus as $m)
                                <option value="{{ $m->id }}" data-precio="{{ $m->precio }}" {{ $menu->id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>
                                <!-- Input oculto que se envía al servidor -->
                        <input type="hidden" name="menus[{{ $index }}][id_menu]" value="{{ $menu->id }}">

                    </div>
                    <div class="col-md-2">
                        <label>Cantidad</label>
                        <input type="text" name="menus[{{ $index }}][cantidad_pedido]" class="form-control cantidad" min="1" value="{{ $menu->pivot->cantidad_pedido }}" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Precio</label>
                        <input type="text" name="menus[{{ $index }}][precio_unitario]" class="form-control precio" step="0.01" value="{{ $menu->pivot->precio_unitario }}" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Subtotal</label>
                        <input type="text" name="menus[{{ $index }}][subtotal]" class="form-control subtotal" value="{{ $menu->pivot->subtotal }}" readonly>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 text-end">
            <h5>Monto total: <span id="montoTotal">{{ number_format($pedido->monto_total, 2) }}</span> Bs</h5>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary w-20">Actualizar Pedido</button>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
 </div>
</div>

{{-- Scripts para manejar menú dinámico y mapa --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let menuIndex = {{ $pedido->menus->count() }};

    $('.select-producto').select2({
        width: '100%',
        placeholder: '-- Seleccione un Registro --',
        allowClear: true
    });

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
    }

    $(document).on('change', 'select[name^="menus"]', function() {
        const selected = this.selectedOptions[0];
        const precio = selected ? selected.getAttribute('data-precio') : 0;
        const row = $(this).closest('.menu-row')[0];
        row.querySelector('.precio').value = precio;
        updateTotals();
    });

    $(document).on('input', '.cantidad', function() {
        updateTotals();
    });

   

    // ---------- Mapa ----------
    var latInicial = {{ $pedido->latitud ?? '-16.500000' }};
    var lngInicial = {{ $pedido->longitud ?? '-68.150000' }};

    var map = L.map('map').setView([latInicial, lngInicial], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([latInicial, lngInicial], { draggable: true }).addTo(map);

    marker.on('dragend', function(e){
        const lat = e.target.getLatLng().lat.toFixed(6);
        const lng = e.target.getLatLng().lng.toFixed(6);
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
    });

    map.on('click', function(e){
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        marker.setLatLng([lat,lng]);
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
    });
});
</script>

@endsection
