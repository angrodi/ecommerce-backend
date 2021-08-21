<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $appends = [
        'proveedorId'
    ];

    protected $hidden = [
        'proveedor_id',
        'created_at', 
        'updated_at'
    ];

    // Métodos accesores - mutadores
    public function getProveedorIdAttribute() {
        return $this->attributes['proveedor_id'];
    }

    public function setProveedorIdAttribute($value) {
        $this->attributes['proveedor_id'] = $value;
    }

    public function getMontoAttribute() {
        return $this->attributes['monto'] + 0;
    }

    public function setMontoAttribute($value) {
        $this->attributes['monto'] = $value + 0;
    }

    // Relaciones
    public function proveedor() {
        return $this->belongsTo('App\Models\Proveedor');
    }

    public function detalles() {
        return $this->hasMany('App\Models\DetalleCompra');
    }
}
