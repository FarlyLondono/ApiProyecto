<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class eps extends Model
{
    //
    protected $table='eps';

    public function Empleado() {
        return $this->hasMany('App\Models\Empleado');   
    }


}