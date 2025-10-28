<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
     protected $fillable = [
        'id_pedido',
        'id_menu',
        'cantidad_pedido',
        'precio_unitario',
        'subtotal',
        'estado',
    ];
  //relacion con pedidos
    public function pedidos()
    {
        return $this->belongsTo(Pedido::class,'id_pedido');
    }
   //relacion con menu
    public function menus()
    {
        return $this->belongsTo(Menu::class,'id_menu');
    }

    //relacion con ajustes
    public function ajustes()
    {
        return $this->hasMany(Ajuste::class,'id_detallePedido');
    }
}
