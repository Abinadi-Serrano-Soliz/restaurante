<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'id_pedido',
        'transaccion_id',
        'referencia',
        'monto_total',
        'metodo_pago',
        'canal_pago',
        'estado_pago',
        'fecha_hora_pago',
        'respuesta_libelula'
    ];

    protected $casts = [
        'fecha_hora_pago' => 'datetime',
        'monto_total' => 'decimal:2',
        'respuesta_libelula' => 'array' // Cast automático a array
    ];

    // Un pago pertenece a un pedido
    public function pedidos()
    {
        return $this->belongsTo(Pedido::class,'id_pedido');
    }

    // Obtener respuesta de Libélula como array
    public function getResponseLibelulaAttribute()
    {
        return json_decode($this->respuesta_libelula, true);
    }
}
