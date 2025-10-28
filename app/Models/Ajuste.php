<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    protected $fillable = [
        'fecha_hora',
        'glosa',
        'reembolso',
        'tipo',
        'imagen',
        'cantidad',
        'id_detallePedido',
        'id_user',
        
        
    ];

    //relacion con users
    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    //relacion con detalle_pedidos
    public function detalle_pedidos()
    {
        return $this->belongsTo(DetallePedido::class, 'id_detallePedido');
    }
}
