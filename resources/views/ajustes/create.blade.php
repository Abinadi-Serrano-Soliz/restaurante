@extends('layouts.myLayout')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg border-0 w-100" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="text-center mb-4"><strong>Registrar Ajuste</strong></h3>

            <form action="{{ route('ajustes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="detalle_pedido" class="form-label">Seleccione Pedido / Menú:*</label>
                    <select name="id_detallePedido" id="id_detallePedido" class="form-control select-producto" required >
                        <option value="">-- Seleccione un detalle de pedido --</option>
                        @foreach($detallePedidos as $detalle)
                            <option 
                                value="{{ $detalle->id }}"
                                data-cantidad="{{ $detalle->cantidad_pedido ?? 0 }}"
                                data-precio="{{ $detalle->precio_unitario ?? 0 }}"
                            >
                                PED-{{ $detalle->pedidos->id ?? 'N/A' }} - {{ $detalle->menus->nombre ?? 'Sin menú' }}
                            </option>
                        @endforeach
                    </select>

                    <div id="infoDetalle" style="display: none; margin-top: 10px;">
                        <p><strong>Cantidad:</strong> <span id="cantidadPedido"></span></p>
                        <p><strong>Precio Unitario:</strong> <span id="precioUnitario"></span> Bs</p>
                    </div>
                </div>
                @error('id_detallePedido')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Ajuste:*</label>
                    <select name="tipo" id="tipo" class="form-control" required value="{{ old('tipo') }}">
                        <option value="">-- Seleccione tipo --</option>
                        <option value="INGRESO">INGRESO</option>
                        <option value="EGRESO">EGRESO</option>
                    </select>
                </div>
                @error('tipo')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="mb-3">
                    <label for="reembolso" class="form-label">Reembolso:</label>
                    <input type="number" name="reembolso" class="form-control" step="0.01" min="0" value="{{ old('reembolso') }}">
                </div>
                @error('reembolso')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad:*</label>
                    <input type="number" name="cantidad" class="form-control" required value="{{ old('cantidad') }}">
                </div>
                @error('cantidad')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="mb-3">
                    <label for="glosa" class="form-label">Glosa:</label>
                    <textarea name="glosa" id="glosa" rows="3" class="form-control">{{ old('glosa') }}</textarea>
                </div>
                @error('glosa')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen Comprobante:</label>
                    <input type="file" name="imagen" class="form-control"value="{{ old('imagen') }}">
                </div>
                @error('imagen')
                     <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror

                <div class="text-center">
                    <button type="submit" class="btn btn-success px-4">Registrar Ajuste</button>
                   
                        <a href="{{ route('ajustes.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                    
                </div>
                
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Select2
    $('.select-producto').select2({
        width: '100%',
        placeholder: '-- Seleccione un Registro --',
        allowClear: true
    });

    // Variables
    const infoDiv = document.getElementById('infoDetalle');
    const cantidadPedido = document.getElementById('cantidadPedido');
    const precioUnitario = document.getElementById('precioUnitario');

    // Evento al cambiar de opción
    $('.select-producto').on('change', function () {
        const selected = $(this).find(':selected');

        if (selected.val()) {
            const cantidad = selected.data('cantidad');
            const precio = selected.data('precio');

            cantidadPedido.textContent = cantidad;
            precioUnitario.textContent = parseFloat(precio).toFixed(2);

            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
    });
});

 // Espera 5 segundos y luego oculta los mensajes de error
    setTimeout(() => {
        document.querySelectorAll('.text-danger').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500); // elimina del DOM
        });
    }, 5000);
</script>
@endsection
