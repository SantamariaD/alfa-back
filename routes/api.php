<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group([
    'prefix' => 'documentos'
], function ($router) {
    Route::post('guardar-documento', [DocumentosController::class, 'guardarArchivo']);
    Route::post('traer-archivo', [DocumentosController::class, 'traerArchivo']);
    Route::post('descargar-archivo', [DocumentosController::class, 'descargarArchivo']);
    Route::get('traer-documentos', [DocumentosController::class, 'traerTodosDocumentos']);
    Route::get('traer-documentos-area/{area}', [DocumentosController::class, 'traerDocumentosArea']);
    Route::post('actualizar-documento', [DocumentosController::class, 'actualizarDocumento']);
    Route::delete('borrar-documento', [DocumentosController::class, 'borrarDocumento']);
    Route::get('descargar-documento/{uuid}/{extension}', [DocumentosController::class, 'descargarDocumento']);
});

Route::group([
    'prefix' => 'perfil'
], function ($router) {
    Route::post('confirmar-contrasena', [PerfilController::class, 'verificarContrasena']);
    Route::post('guardar-informacion', [PerfilController::class, 'guardarInformacionUsuario']);
    Route::post('actualizar-contrasena', [PerfilController::class, 'actualizarContrasena']);
    Route::post('guardar-imagen', [PerfilController::class, 'guardarImagen']);
});

Route::group([
    'prefix' => 'producto'
], function ($router) {
    Route::post('guardar-producto', [ProductoController::class, 'crearProducto']);
    Route::put('actualizar-producto', [ProductoController::class, 'actualizarProducto']);
    Route::get('consultar-producto/{id}', [ProductoController::class, 'consultarProducto']);
    Route::get('consultar-productos', [ProductoController::class, 'consultarProductos']);
    Route::delete('eliminar-producto/{id}', [ProductoController::class, 'eliminarProducto']);
});
