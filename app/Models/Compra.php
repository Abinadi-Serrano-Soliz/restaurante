<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'id_proveedor',
        'fecha_compra',
        'monto_total',
    ];

    public function proveedor()          //relacion con proveedor 
    {
        return $this->belongsTo(Proveedor::class,'id_proveedor');
    }

    //relacion de muchos a muchos con producto_almacenes
    public function producto_almacenes()
    {
        return $this->belongsToMany(ProductoAlmacene::class, 'producto_almacen__compras', 'id_compra', 'id_ProductoAlmacen')
                    ->withPivot('cantidad_compra', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    //  AGREGA ESTA RELACIÓN 
    public function producto_almacen__compras()
    {
        return $this->hasMany(ProductoAlmacen_Compra::class, 'id_compra');
    }
   
}
