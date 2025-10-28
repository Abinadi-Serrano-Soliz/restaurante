<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
     protected $fillable = [
        'id_cliente',
        'id_repartidor',
        'id_user',
        'direccion_entrega',
        'estado',
        'fecha_hora_pedido',
        'latitud',
        'longitud',
        'monto_total',
        'observaciones',
        
    ];

     public function clientes()
    {
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function repartidors()
    {
        return $this->belongsTo(Repartidor::class,'id_repartidor');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pagos()
    {
        return $this->hasOne(Pago::class,'id_pedido');
    }

     // Relación muchos a muchos con MENÚ mediante DETALLE_PEDIDOS
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'detalle_pedidos','id_pedido','id_menu')
                    ->withPivot('cantidad_pedido', 'precio_unitario', 'subtotal', 'estado')
                    ->withTimestamps();
    }

    public function detallePedidos() {
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }
    
}
