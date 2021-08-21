<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';

    protected $appends = [
        'productoId',
        'pedidoId'
    ];

    protected $hidden = [
        'producto_id',
        'pedido_id',
        'created_at', 
        'updated_at'
    ];

    // Métodos accesores - mutadores
    public function getPedidoIdAttribute() {
        return $this->attributes['pedido_id'];
    }

    public function setPedidoIdAttribute($value) {
        $this->attributes['pedido_id'] = $value;
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
    public function pedido() {
        return $this->belongsTo('App\Models\Pedido');
    }

    public function producto() {
        return $this->belongsTo('App\Models\Producto');
    }
}
