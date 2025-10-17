<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'nombre',
        'imagen',
        'descripcion',
        'precio',
        'stock_menu',
        'estado',
    ];

    //relacion de muchos a muchos con producto_almacen
    public function producto_almacenes(){

        return $this->belongsToMany(ProductoAlmacene::class,'detalle_menus','id_menu','id_ProductoAlmacen')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
