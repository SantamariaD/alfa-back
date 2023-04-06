<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
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
    Route::post('guardar-archivo', [DocumentosController::class, 'guardarArchivo']);
    Route::post('traer-archivo', [DocumentosController::class, 'traerArchivo']);
    Route::post('descargar-archivo', [DocumentosController::class, 'descargarArchivo']);
    Route::get('traer-documentos', [DocumentosController::class, 'traerTodosDocumentos']);
    Route::get('traer-documentos-area/{area}', [DocumentosController::class, 'traerDocumentosArea']);
    Route::post('actualizar-documento', [DocumentosController::class, 'actualizarDocumento']);
    Route::delete('borrar-documento', [DocumentosController::class, 'borrarDocumento']);
});
