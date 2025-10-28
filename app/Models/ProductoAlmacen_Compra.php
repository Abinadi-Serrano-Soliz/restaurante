<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoAlmacen_Compra extends Model
{
    protected $fillable = [
        'id_compra',
        'id_ProductoAlmacen',
        'cantidad_compra',
        'precio_unitario',
        'subtotal',
    ];


    // Relación con ProductoAlmacene
    public function producto_almacenes()
    {
        return $this->belongsTo(ProductoAlmacene::class, 'id_ProductoAlmacen');
    }

    public function compras()
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }
}
