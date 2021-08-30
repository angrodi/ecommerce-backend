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
        'fechaEntrega'
    ];

    protected $hidden = [
        'empleado_id',
        'cliente_id',
        'metodo_pago',
        'fecha_creacion',
        'fecha_entrega',
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
        return $this->attributes['fecha_creacion'];
    }

    public function setFechaCreacionAttribute($value) {
        $this->attributes['fecha_creacion'] = $value;
    }

    public function getFechaEntregaAttribute() {
        return $this->attributes['fecha_entrega'];
    }

    public function setFechaEntregaAttribute($value) {
        $this->attributes['fecha_entrega'] = $value;
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
