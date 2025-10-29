@extends('layouts.myLayout')
@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Header con degradado --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: #334155;">
                <div class="card-body text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-shopping-cart fa-3x"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 fw-bold">Reporte de Compras</h2>
                            <p class="mb-0 opacity-75">Análisis detallado de compras a proveedores por rango de fechas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de búsqueda --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('reportescompras') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="fecha_inicio" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>Fecha Inicio
                                </label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                       class="form-control form-control-lg"
                                       value="{{ old('fecha_inicio', $fechaInicio) }}" required>
                            </div>
                            <div class="col-md-5">
                                <label for="fecha_fin" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-check text-primary me-2"></i>Fecha Fin
                                </label>
                                <input type="date" name="fecha_fin" id="fecha_fin" 
                                       class="form-control form-control-lg"
                                       value="{{ old('fecha_fin', $fechaFin) }}" required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-lg w-100 shadow" type="submit">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de compras --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-list-alt text-primary me-2"></i>
                            Detalle de Compras
                        </h5>
                        <a href="{{ route('reportescompras.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
                           class="btn btn-danger shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small">ID</th>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small">Proveedor</th>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small">Fecha Compra</th>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small text-center">Producto</th>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small">Cantidad</th>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small">Precio</th>
                                    <th class="px-4 py-3 fw-semibold text-uppercase small text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="text-secondary fw-semibold">
                                            COM-{{ $compra->id }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-medium">
                                                {{ $compra->proveedor->nombre ?? 'Sin proveedor' }} {{ $compra->proveedor->apellidos ?? 'Sin proveedor' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <div class="fw-medium">
                                                {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}
                                            
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="d-flex flex-column align-items-start">
                                            @forelse($compra->producto_almacen__compras as $detalle)
                                                <span class="fw-medium d-block mb-1">
                                                    • {{ $detalle->producto_almacenes->producto->nombre ?? 'N/A' }}
                                                </span>
                                            @empty
                                                <span class="text-muted">Sin productos</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @forelse($compra->producto_almacen__compras as $detalle)
                                            <span class="d-block mb-1" style="width: fit-content;">
                                                {{ $detalle->cantidad_compra }} 
                                                {{ $detalle->producto_almacenes->unidad_medida}}
                                            </span>
                                        @empty
                                            <span class="text-muted small">---</span>
                                        @endforelse
                                    </td>
                                    <td class="px-4 py-3">
                                        @forelse($compra->producto_almacen__compras as $detalle)
                                            <span class="d-block mb-1" style="width: fit-content;">
                                                {{ number_format($detalle->precio_unitario, 2) }} Bs
                                            </span>
                                        @empty
                                            <span class="text-muted small">---</span>
                                        @endforelse
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        @forelse($compra->producto_almacen__compras as $detalle)
                                            <span class="d-block mb-1 fw-bold text-danger fs-5" style="width: fit-content;">
                                                {{ $detalle->subtotal }} 
                    
                                            </span>
                                        @empty
                                        <small class="text-muted"> Bs</small>
                                        @endforelse
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="6" class="px-4 py-3 fw-bold">
                                        TOTAL GASTADO:
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <span class="fw-bold text-danger fs-5">
                                            {{ number_format($totalGastado, 2) }}
                                        </span>
                                        <small class="text-muted"> Bs</small>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($compras->count() == 0)
        {{-- Estado vacío --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-inbox fa-5x text-muted opacity-25"></i>
                        </div>
                        <h4 class="text-muted mb-2">No se encontraron compras</h4>
                        <p class="text-muted">
                            No hay registros entre las fechas 
                            <strong>{{ $fechaInicio }}</strong> y <strong>{{ $fechaFin }}</strong>
                        </p>
                        <button type="button" class="btn btn-primary mt-3" onclick="document.getElementById('fecha_inicio').focus()">
                            <i class="fas fa-search me-2"></i>Intentar otra búsqueda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
</style>
@endsection