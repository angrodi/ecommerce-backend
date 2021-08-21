<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compra';

    protected $appends = [
        'productoId',
        'compraId'
    ];

    protected $hidden = [
        'compra_id',
        'producto_id',
        'created_at', 
        'updated_at'
    ];

    // Métodos accesores - mutadores
    public function getCompraIdAttribute() {
        return $this->attributes['compra_id'];
    }

    public function setCompraIdAttribute($value) {
        $this->attributes['compra_id'] = $value;
    }

    public function getProductoIdAttribute() {
        return $this->attributes['producto_id'];
    }

    public function setProductoIdAttribute($value) {
        $this->attributes['producto_id'] = $value;
    }

    public function getPrecioAttribute() {
        return $this->attributes['precio'] + 0;
    }

    public function setPrecioAttribute($value) {
        $this->attributes['precio'] = $value + 0;
    }

    // Relaciones
    public function compra() {
        return $this->belongsTo('App\Models\Compra');
    }

    public function producto() {
        return $this->belongsTo('App\Models\Producto');
    }
}
