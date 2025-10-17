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

}
