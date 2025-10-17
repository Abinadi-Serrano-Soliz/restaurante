@extends('layouts.myLayout')

@section('content')

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-lg-9 col-md-9">
        <div class="ibox-title">
            <h5>Editar Producto</h5>
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

            <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Nombre*:</label>
                    <div class="col-lg-10">
                        <input type="text" name="nombre" class="form-control"
                               value="{{ old('nombre', $producto->nombre) }}">
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Descripción:</label>
                    <div class="col-lg-10">
                        <textarea name="descripcion" class="form-control">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>
                </div>

                {{-- Categoría --}}
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Categoría:*</label>
                    <div class="col-lg-10">
                        <select name="id_categoria" class="form-control">
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('id_categoria', $producto->id_categoria) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Seleccionar nuevo almacén --}}
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Añadir almacén:*</label>
                    <div class="col-lg-10">
                        <select id="select-almacen" class="form-control">
                            <option value="">-- Selecciona un almacén --</option>
                            @foreach($almacenes as $almacen)
                                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Contenedor dinámico de almacenes --}}
                <div id="almacenes-container">
                    @foreach($producto->almacenes as $almacen)
                        <div class="border rounded p-3 mb-2" id="almacen-{{ $almacen->id }}">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $almacen->nombre }}</strong>
                                <button type="button" class="btn btn-xs btn-danger" onclick="document.getElementById('almacen-{{ $almacen->id }}').remove()">Quitar</button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4 mb-2"><label>Stock Actual</label>
                                    <input type="number" name="almacenes[{{ $almacen->id }}][stock_actual]"
                                           class="form-control" placeholder="Stock actual"
                                           value="{{ $almacen->pivot->stock_actual }}">
                                </div>
                                <div class="col-md-4 mb-2"><label>Stock Minimo</label>
                                    <input type="number" name="almacenes[{{ $almacen->id }}][stock_minimo]"
                                           class="form-control" placeholder="Stock mínimo"
                                           value="{{ $almacen->pivot->stock_minimo }}">
                                </div>
                                <div class="col-md-4 mb-2"><label>Unidad Medida</label>
                                    <select name="almacenes[{{ $almacen->id }}][unidad_medida]" class="form-control">
                                        <option value="">Seleccione unidad</option>
                                        <option value="unidad" {{ $almacen->pivot->unidad_medida == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                        <option value="kg" {{ $almacen->pivot->unidad_medida == 'kg' ? 'selected' : '' }}>Kilogramos</option>
                                        <option value="lt" {{ $almacen->pivot->unidad_medida == 'lt' ? 'selected' : '' }}>Litros</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end pr-5 pt-3">
                    <div class="p-2">
                        <button class="btn btn-sm btn-success" type="submit">Actualizar</button>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script dinámico --}}
<script>
    document.getElementById('select-almacen').addEventListener('change', function () {
        let almacenId = this.value;
        let almacenText = this.options[this.selectedIndex].text;
        let container = document.getElementById('almacenes-container');

        if (almacenId && !document.getElementById('almacen-' + almacenId)) {
            let div = document.createElement('div');
            div.classList.add('border', 'rounded', 'p-3', 'mb-2');
            div.setAttribute('id', 'almacen-' + almacenId);

            div.innerHTML = `
                <div class="d-flex justify-content-between">
                    <strong>${almacenText}</strong>
                    <button type="button" class="btn btn-xs btn-danger" onclick="document.getElementById('almacen-${almacenId}').remove()">Quitar</button>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4 mb-2"><label>Stock Actual</label>
                        <input type="number" name="almacenes[${almacenId}][stock_actual]" class="form-control" placeholder="Stock actual">
                    </div>
                    <div class="col-md-4 mb-2"><label>Stock Minimo</label>
                        <input type="number" name="almacenes[${almacenId}][stock_minimo]" class="form-control" placeholder="Stock mínimo">
                    </div>
                    <div class="col-md-4 mb-2"><label>Unidad Medida</label>
                        <select name="almacenes[${almacenId}][unidad_medida]" class="form-control">
                            <option value="">Seleccione unidad</option>
                            <option value="unidad">Unidad</option>
                            <option value="kg">Kilogramos</option>
                            <option value="lt">Litros</option>
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(div);
        }

        this.value = '';
    });

    // ocultar alertas después de 3s
    setTimeout(() => {
        let alerta = document.getElementById('alerta');
        if (alerta) alerta.style.display = 'none';
    }, 3000);
</script>

@endsection