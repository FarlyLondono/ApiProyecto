<?php

namespace App\helpers;

use Firebase\JWT\JWT;

use App\Models\user;


class JwtAuth
{

    //Esta es la clave Key que se crea para genrera el token
    public $key;

    public function __construct()
    {
        $this->key = 'esto_es_una_clave_secreta_99887766';
    }

    //FUNCION PARA VALIDAR EL INGRESO DEL USUARIO LOGIN
    public function signup($email, $password, $getToken = null)
    {

        //Buscar si exite usuario con credenciales
        $user = user::where([
            'email' => $email,
            'password' => $password
        ])->first();

        //Comprobar si son correctos(objeto)
        $signup = false;
        if (is_object($user)) {
            $signup = true;
        }

        //Generar token con datos del usuario identificado
        if ($signup) {

            //Array con datos del usuario
            $Token = array(
                'sub' => $user->IdEmpleado,
                'email' => $user->email,
                'Nombres' => $user->Nombres,
                'Apellidos' => $user->Apellidos,
                'iat' => time(),
                'exp' => time() + (9 * 60 * 60) // este token caduca en 1 semana
            );

            //Pasamos a la clase la clave secreta
            $JWT = JWT::encode($Token, $this->key, 'HS256');

            //Se crea variable para la decodificacion de token
            $decode = JWT::decode($JWT, $this->key, ['HS256']);



            //Devolver los datos de codificados o el token, en funcion de un parametro
            if (is_null($getToken)) {
                $data = $JWT;
            } else {
                $data = $decode;
            }
        } else {

            $data = array(
                'status' => 'error',
                'message' => 'Revisa los datos ingresados.'
            );
        }

        return $data;
    }

    //FUNCION PARA CHEQUEAR TOKEN
    public function checkToken($jwt, $getIdentity = false)
    {

        //autenticacion a false por defecto
        $auth = false;


        // hacer decodificacion del token
        try {
            $jwt = str_replace('"', '', $jwt);

            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }


        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}
