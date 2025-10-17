<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = [
        'nombre','apellidos','telefono','direccion'
    ];


    public function compra()                   //relacion inversa con compra
    {
        return $this->hasMany(Compra::class,'id_proveedor');
    }
}
