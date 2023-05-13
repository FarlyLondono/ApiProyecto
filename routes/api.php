<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Middleware\ApiMiddleware;  
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Rutas Usuario
//Route::post('login', 'UserController@login');
Route::post('login', [UserController::class, 'login']);
Route::get('user', [UserController::class, 'listar'])->middleware(ApiMiddleware::class);
Route::post('register', [UserController::class, 'register'])->middleware(ApiMiddleware::class);


Route::get('empleado', [EmpleadoController::class, 'listar'])->middleware(ApiMiddleware::class);
Route::post('registerEmpleado', [EmpleadoController::class, 'registerEmpleado'])->middleware(ApiMiddleware::class);