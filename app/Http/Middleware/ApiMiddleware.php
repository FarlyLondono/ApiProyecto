<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\helpers\JwtAuth;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        //COMPROVAR SI EL USUARIO ESTA IDENTIFICADO

         //recogemos token de autorizacion
         $token = $request ->header('Authorization');
        //$token = $request->bearerToken();
        //intanciamos objeto
        $jwtAuth = new JwtAuth();
        //chequeamos si el token es correcto o no
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {
            return $next($request);
        } else {

            $data = array(
                'code' => Response::HTTP_UNAUTHORIZED,
                'status' => 'error',
                'message' => 'Usuario no Autorizado'
            );

            return response()->json($data, $data['code']);
        }
    }
}
