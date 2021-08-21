<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $hidden = [
        'created_at', 
        'updated_at',
        'pivot'
    ];

    // Relaciones
    public function usuarios() {
        return $this->belongsToMany('App\Models\Usuario');
    }
}
