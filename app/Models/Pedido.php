<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $appends = [
        'empleadoId',
        'clienteId',
        'metodoPago',
        'fechaCreacion',
        'fechaActualizacion'
    ];

    protected $hidden = [
        'empleado_id',
        'cliente_id',
        'metodo_pago',
        'created_at', 
        'updated_at'
    ];

    // Métodos accesores - mutadores
    public function getEmpleadoIdAttribute() {
        return $this->attributes['empleado_id'];
    }

    public function setEmpleadoIdAttribute($value) {
        $this->attributes['empleado_id'] = $value;
    }

    public function getClienteIdAttribute() {
        return $this->attributes['cliente_id'];
    }

    public function setClienteIdAttribute($value) {
        $this->attributes['cliente_id'] = $value;
    }

    public function getMetodoPagoAttribute() {
        return $this->attributes['metodo_pago'];
    }

    public function setMetodoPagoAttribute($value) {
        $this->attributes['metodo_pago'] = $value;
    }

    public function getFechaCreacionAttribute() {
        return $this->attributes['created_at'];
    }

    public function setFechaCreacionAttribute($value) {
        $this->attributes['created_at'] = $value;
    }

    public function getFechaActualizacionAttribute() {
        return $this->attributes['updated_at'];
    }

    public function setFechaActualizacionAttribute($value) {
        $this->attributes['updated_at'] = $value;
    }
 
    public function getMontoAttribute() {
        return $this->attributes['monto'] + 0;
    }

    public function setMontoAttribute($value) {
        $this->attributes['monto'] = $value + 0;
    }

    // Relaciones
    public function cliente() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function empleado() {
        return $this->belongsTo('App\Models\Usuario');
    }

    public function detalles() {
        return $this->hasMany('App\Models\DetallePedido');
    }
}
