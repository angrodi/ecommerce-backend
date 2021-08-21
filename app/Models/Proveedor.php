<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $hidden = ['created_at', 'updated_at'];

    public function compras() {
        return $this->hasMany('App\Model\Compra');
    }
}
