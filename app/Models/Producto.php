<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'id_categoria'];
    
    //relacion  1 categoria pertenece a muchos productos
    public function categoria()
    {
        return $this->belongsTo(Categoria::class,'id_categoria');
    }

    //relacion de muchos a muchos con almacenes
    public function almacenes()
    {
        return $this->belongsToMany(Almacene::class, 'producto_almacenes', 'id_producto', 'id_almacen')
                    ->withPivot('stock_actual', 'stock_minimo', 'unidad_medida')
                    ->withTimestamps();
    }

    
}

