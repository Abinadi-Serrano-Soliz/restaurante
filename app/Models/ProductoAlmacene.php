<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoAlmacene extends Model
{

    protected $fillable = [
        'id_producto',
        'id_almacen',
        'stock_actual',
        'stock_minimo',
        'unidad_medida',
    ];

     //  Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    //  Relación con Almacen
    public function almacen()
    {
        return $this->belongsTo(Almacene::class, 'id_almacen');
    }
    
    //relacion de muchos a muchos con compra
    public function compra()
    {
        return $this->belongsToMany(Compra::class, 'producto_almacen__compras', 'id_compra', 'id_ProductoAlmacen')
                    ->withPivot('cantidad_compra', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    //relacion de muchos a muchos con menu
    public function menus(){

        return $this->belongsToMany(Menu::class,'detalle_menus','id_menu','id_ProductoAlmacen')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
