<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repartidor extends Model
{
    protected $fillable = [
        'nombre','apellidos','salario','telefono','placa','tipo_vehiculo','estado'
    ];

     //relacion inversa con pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_pedido');
    }
}
