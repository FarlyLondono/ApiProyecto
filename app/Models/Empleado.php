<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    protected  $table='empleados';

    protected $fillable = [
        'IdUsuario',
        'IdCesantia',
        'IdEps',
        'Sede',
        'Cesantias',
        'Proceso',
        'Gerencia',
        'Benner',
        'FechaIngreso',
        'Clave',
        'Estado',
        'Disponibilidad',
    ];


    //relacion de 1 a muchos e inversa con usuarios
    public function cesantias(){
        return $this->belongsTo('App\Models\cesantias', 'IdCesantias');
    }

    //relacion de 1 a muchos e inversa con usuarios
    public function eps(){
        return $this->belongsTo('App\Models\eps', 'IdEps');
    }
}

