<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Empleado;
use Exception;

class EmpleadoController extends Controller
{
    //
     //Listar empleado
     public function listar()
     {
         //return "Accion de pruebas controlador USER-LISTAR";
         $empleado = Empleado::all();
 
         return response()->json([
             'code' => 200,
             'status' => 'success',
             'data' => $empleado
         ]);
     }

     //REGISTRAR EMPLEADO
    public function registerEmpleado(Request $request) //Recoger datos del empleado por post!!
    {
        $validate = Validator::make( //Validar datos!!
            $request->all(),
            [
                'Sede'      => 'required',
                'Cesantias'   => 'required',
                'Proceso'     => 'required',
                'Gerencia'  => 'required',
                'Banner'  => 'required',
                'FechaIngreso'  => 'required',
                'Clave'  => 'required',
                'Estado'  => 'required',
                'Disponibilidad'  => 'required'
            ]
        );

        if ($validate->fails()) {
            $validations = json_decode($validate->errors(), true);

            if (isset($validations['email'])) {
                $data = array( //La validacion ah fallado!!
                    'status' => 'errorEmail',
                    'code' => 400,
                    'message' => 'el usuario no se ha creado',
                );
            } else {
                $data = array( //La validacion ah fallado!!
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'el usuario no se a creado',
                    'errors' => $validate->errors()
                );
            }
            return response()->json($data, $data['code']);
        }

        try {
            $params_array = array_map('trim', $request->except('Clave'));
            $pwd = hash('sha256', $request->password); //Cifrar contraseñas!!

            //crear el usuario!!
            $empleado = new Empleado();
            $empleado->Sede = $params_array['Sede'];
            $empleado->Cesantias = $params_array['Cesantias'];
            $empleado->Proceso = $params_array['Proceso'];
            $empleado->Gerencia = $params_array['Gerencia'];
            $empleado->Banner = $params_array['Banner'];
            $empleado->FechaIngreso = $params_array['FechaIngreso'];
            $empleado->Clave = $pwd;
            $empleado->Estado = $params_array['Estado'];
            $empleado->Disponibilidad = $params_array['Disponibilidad'];
            

            //guardar el empleado!!
            $empleado->save();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'el empleado se ha creado correctamente!!',
                'data' => $params_array
            );
        } catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'error inesperado!!',
                'errors' => $e
            );
        }

        return response()->json($data, $data['code']);
    }



}
