<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProveedorController;

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

Route::group([
    'prefix' => 'categorias'
], function ($router) {
    Route::get('consultar-categorias', [CategoriaController::class, 'consultarCategorias']);
    Route::post('guardar-categoria', [CategoriaController::class, 'crearCategoria']);
    Route::delete('eliminar-categoria/{id}', [CategoriaController::class, 'eliminarCategoria']);
});

Route::group([
    'prefix' => 'empleados'
], function ($router) {
    Route::post('guardar-empleado', [EmpleadoController::class, 'crearEmpleado']);
    Route::put('actualizar-empleado', [EmpleadoController::class, 'actualizarEmpleado']);
    Route::delete('eliminar-empleado/{id}', [EmpleadoController::class, 'eliminarEmpleado']);
    Route::get('consultar-empleado/{id}', [EmpleadoController::class, 'consultarEmpleado']);
    Route::get('consultar-empleados', [EmpleadoController::class, 'consultarTodosEmpleados']);
});

Route::group([
    'prefix' => 'proveedores'
], function ($router) {
    Route::post('guardar-proveedor', [ProveedorController::class, 'crearProveedor']);
    Route::put('actualizar-proveedor', [ProveedorController::class, 'actualizarProveedor']);
    Route::delete('eliminar-proveedor/{id}', [ProveedorController::class, 'eliminarProveedor']);
    Route::get('consultar-proveedor/{id}', [ProveedorController::class, 'consultarProveedor']);
    Route::get('consultar-proveedores', [ProveedorController::class, 'consultarTodosProveedores']);
});
