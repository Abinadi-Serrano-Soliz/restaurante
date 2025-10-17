<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repartidor extends Model
{
    protected $fillable = [
        'nombre','apellidos','salario','telefono','placa','tipo_vehiculo','estado'
    ];
}
