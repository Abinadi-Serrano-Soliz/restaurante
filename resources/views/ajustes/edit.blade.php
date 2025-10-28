@extends('layouts.myLayout')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg border-0 w-100" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="text-center mb-4"><strong>Editar Ajuste</strong></h3>

            <form action="{{ route('ajustes.update', $ajuste->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Detalle de pedido --}}
                <div class="mb-3">
                    <label for="id_detallePedido" class="form-label">Seleccione Pedido / Menú:*</label>
                    <select name="id_detallePedido" id="id_detallePedido" class="form-control select-producto" required>
                        <option value="">-- Seleccione un detalle de pedido --</option>
                        @foreach($detallePedidos as $detalle)
                            <option 
                                value="{{ $detalle->id }}"
                                data-cantidad="{{ $detalle->cantidad_pedido }}"
                                data-precio="{{ $detalle->precio_unitario }}"
                                {{ $ajuste->id_detallePedido == $detalle->id ? 'selected' : '' }}
                            >
                                PED-{{ $detalle->pedidos->id ?? 'N/A' }} - {{ $detalle->menus->nombre ?? 'Sin menú' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_detallePedido')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                     @enderror

                    <div id="infoDetalle" style="margin-top: 10px;">
                        <p><strong>Cantidad:</strong> <span id="cantidadPedido">{{ $ajuste->detalle_pedidos->cantidad_pedido ?? '' }}</span></p>
                        <p><strong>Precio Unitario:</strong> <span id="precioUnitario">{{ $ajuste->detalle_pedidos->precio_unitario ?? '' }}</span> Bs</p>
                    </div>
                </div>

                {{-- Tipo --}}
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Ajuste:*</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="">-- Seleccione tipo --</option>
                        <option value="INGRESO" {{ $ajuste->tipo == 'INGRESO' ? 'selected' : '' }}>INGRESO</option>
                        <option value="EGRESO" {{ $ajuste->tipo == 'EGRESO' ? 'selected' : '' }}>EGRESO</option>
                    </select>
                </div>
                @error('tipo')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                {{-- Reembolso --}}
                <div class="mb-3">
                    <label for="reembolso" class="form-label">Reembolso:</label>
                    <input type="number" name="reembolso" class="form-control" step="0.01" min="0" value="{{ old('reembolso', $ajuste->reembolso) }}">
                </div>
                @error('reembolso')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                {{-- Cantidad --}}
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad:*</label>
                    <input type="number" name="cantidad" class="form-control" min="1" value="{{ old('cantidad', $ajuste->cantidad) }}" required>
                </div>
                @error('cantidad')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                {{-- Glosa --}}
                <div class="mb-3">
                    <label for="glosa" class="form-label">Glosa:</label>
                    <textarea name="glosa" id="glosa" rows="3" class="form-control">{{ old('glosa', $ajuste->glosa) }}</textarea>
                </div>
                @error('glosa')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                {{-- Imagen --}}
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen Comprobante:</label>
                    <input type="file" name="imagen" class="form-control">
                    @error('imagen')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                    @if($ajuste->imagen)
                        <div class="mt-2">
                            <p>Imagen actual:</p>
                            <img src="{{ asset('storage/' . $ajuste->imagen) }}" alt="Comprobante" width="200">
                        </div>
                    @endif
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Actualizar Ajuste</button>
                    <a href="{{ route('ajustes.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select-producto').select2({
        width: '100%',
        placeholder: '-- Seleccione un Registro --',
        allowClear: true
    });

    const infoDiv = document.getElementById('infoDetalle');
    const cantidadPedido = document.getElementById('cantidadPedido');
    const precioUnitario = document.getElementById('precioUnitario');

    $('.select-producto').on('change', function () {
        const selected = $(this).find(':selected');
        if (selected.val()) {
            cantidadPedido.textContent = selected.data('cantidad');
            precioUnitario.textContent = parseFloat(selected.data('precio')).toFixed(2);
            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
    });
});
</script>
@endsection
