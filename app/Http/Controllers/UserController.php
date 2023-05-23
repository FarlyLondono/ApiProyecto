<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\helpers\JwtAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    //LISTAR USUARIOS ACTIVOS
    public function listar()
    {
        //return "Accion de pruebas controlador USER-LISTAR";
        $user = user::select('Nombres','Apellidos','email','Estado')->where("Estado", '=', 1)->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $user
        ]);
    }

    //LISTAR USUARIOS ACTIVOS
    public function listarInactivos()
    {
        //return "Accion de pruebas controlador USER-LISTAR";
        $user = user::select('Nombres','Apellidos','email','Estado')->where("Estado", '=', 2)->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $user
        ]);
    }

    //REGISTRAR USUARIO
    public function register(Request $request) //Recoger datos del usuario por post!!
    {
        $validate = Validator::make( //Validar datos!!
            $request->all(),
            [

                'Nombres'      => 'required',
                'Apellidos'   => 'required',
                'email'     => 'required|email|unique:users', //comprobrar si exite el usuario
                'password'  => 'required',
                'Estado'  => 'required'
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
            $params_array = array_map('trim', $request->except('password'));
            $pwd = hash('sha256', $request->password); //Cifrar contraseñas!!

            //crear el usuario!!
            $user = new User();
            $user->Nombres = $params_array['Nombres'];
            $user->Apellidos = $params_array['Apellidos'];
            $user->email = $params_array['email'];
            $user->password = $pwd;
            $user->Estado = $params_array['Estado'];

            //guardar el usuario!!
            $user->save();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'el usuario se ha creado correctamente!!',
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

    //LOGIN DE USUARIO
    public function login(Request $request) //Recibir los datos por post
    {

        $jwtauth = new JwtAuth(); //Traemos la clase creada en provider

        $validate = Validator::make( //Validar  datos recibidos
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validate->fails()) {

            //la validacion ah fallado!!
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Por favor ingresa los datos solicitados.',
                'errors' => $validate->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $pwd = hash('sha256', $request->password); //Cifrar la contraseña

            $signup = $jwtauth->signup($request->email, $pwd); //Devolver token o datos

            $access_token = $signup;

            if (isset($signup['status'])) {
                return response()->json($signup, Response::HTTP_UNAUTHORIZED);
            }

            return response()->json(compact('access_token'), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Ocurrió un error inesperado!',
                'errors' => $e->getMessage()
            ]);
        }
    }
}
