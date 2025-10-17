<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleMenu extends Model
{
    protected $fillable = [
        'id_menu',
        'id_ProductoAlmacen',
        'cantidad',
    ];

    //relacion con menu
    public function menus(){

        return $this->belongsTo(Menu::class,'id_menu');
    }

    //relacion con producto_almacen
    public function producto_almacenes(){
       
        return $this->belongsTo(ProductoAlmacene::class,'id_ProductoAlmacen');
    }
}
