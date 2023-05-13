<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cesantias extends Model
{
    //
    protected $table='cesantias';

    public function Empleado() {
        return $this->hasMany('App\Models\Empleado');   
    }


}