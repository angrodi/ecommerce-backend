<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

    protected $hidden = ['created_at', 'updated_at'];

    public function productos() {
        return $this->hasMany('App\Model\Producto');
    }
}
