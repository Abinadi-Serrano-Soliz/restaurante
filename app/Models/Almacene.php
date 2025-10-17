<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacene extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',
        'capacidad'
    ];

    // Relación inversa muchos a muchos con productos
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'producto_almacenes',
            'id_almacen',
            'id_producto'
        )
        ->withPivot('stock_actual', 'stock_minimo', 'unidad_medida')
        ->withTimestamps();
    }

}
