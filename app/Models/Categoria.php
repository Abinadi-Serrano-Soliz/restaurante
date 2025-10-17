<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    //relacion con producto/ muchos productos 
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }
}
