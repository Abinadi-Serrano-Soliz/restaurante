@extends('layouts.myLayout')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Detalles de la Compra</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
            <li class="breadcrumb-item active"><strong>Detalle de compra</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Información general</h5>
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
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label><strong>Proveedor:</strong></label>
                            <p>{{ $compra->proveedor->nombre }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Fecha de compra:</strong></label>
                            <p>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Monto total (Bs):</strong></label>
                            <p>{{ number_format($compra->monto_total, 2) }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5>Productos Comprados</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th>Cantidad Kg/Li/Unidad </th>
                                    <th>Precio Kg/Li/Unidad</th>
                                    <th>Subtotal (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($compra->producto_almacenes as $detalle)
                                    <tr>
                                        <td>{{ $detalle->producto->nombre }}</td>
                                        <td>{{ $detalle->almacen->nombre }}</td>
                                        <td class="text-center">{{ $detalle->pivot->cantidad_compra }} {{ $detalle->unidad_medida }}</td>
                                        <td class="text-center">{{ number_format($detalle->pivot->precio_unitario, 2)}} Bs.</td>
                                        <td class="text-center">{{ number_format($detalle->pivot->subtotal, 2)}} Bs.</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Monto Total:</th>
                                    <th class="text-center">{{ number_format($compra->monto_total, 2) }} Bs.</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection
