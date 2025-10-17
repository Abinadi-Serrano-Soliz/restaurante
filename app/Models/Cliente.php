<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'ci','nombre','apellidos','telefono','correo'
    ];
}
