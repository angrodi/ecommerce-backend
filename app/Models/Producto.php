<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $appends = [
        'categoriaId'
    ];

    protected $hidden = [
        'categoria_id',
        'created_at',
        'updated_at'
    ];

    public function getCategoriaIdAttribute() {
        return $this->attributes['categoria_id'];
    }

    public function setCategoriaIdAttribute($value) {
        $this->attributes['categoria_id'] = $value;
    }

    public function getPrecioAttribute() {
        return $this->attributes['precio'] + 0;
    }

    public function setPrecioAttribute($value) {
        $this->attributes['precio'] = $value + 0;
    }

    public function categoria() {
        return $this->belongsTo('App\Models\Categoria');
    }

    public function detalles() {
        return $this->hasMany('App\Model\DetalleCompra');
    }

}
