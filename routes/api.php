<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaVentaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CatalogoProveedorController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ProductoOrdenCompraController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\StockVentaController;


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
    Route::get('descargar-documento/{area}/{uuid}/{extension}', [DocumentosController::class, 'descargarDocumento']);
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
    Route::get('consultar-productos-venta', [ProductoController::class, 'consultarProductosVenta']);
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
    'prefix' => 'categorias-ventas'
], function ($router) {
    Route::get('consultar-categorias', [CategoriaVentaController::class, 'consultarCategoriasVentas']);
    Route::post('guardar-categoria', [CategoriaVentaController::class, 'crearCategoriaVentas']);
    Route::delete('eliminar-categoria/{id}', [CategoriaVentaController::class, 'eliminarCategoriaVentas']);
});

Route::group([
    'prefix' => 'empleados'
], function ($router) {
    Route::post('guardar-empleado', [EmpleadoController::class, 'crearEmpleado']);
    Route::put('actualizar-empleado', [EmpleadoController::class, 'actualizarEmpleado']);
    Route::delete('eliminar-empleado/{id}', [EmpleadoController::class, 'eliminarEmpleado']);
    Route::get('consultar-empleado/{id}', [EmpleadoController::class, 'consultarEmpleado']);
    Route::get('consultar-empleados', [EmpleadoController::class, 'consultarTodosEmpleados']);
    Route::post('guardar-documento', [EmpleadoController::class, 'guardarArchivo']);
    Route::post('traer-archivo', [EmpleadoController::class, 'traerArchivo']);
    Route::post('descargar-archivo', [EmpleadoController::class, 'descargarArchivo']);
    Route::get('traer-documentos', [EmpleadoController::class, 'traerTodosDocumentos']);
    Route::get('traer-documentos-area/{area}', [EmpleadoController::class, 'traerDocumentosArea']);
    Route::post('actualizar-documento', [EmpleadoController::class, 'actualizarDocumento']);
    Route::delete('borrar-documento', [EmpleadoController::class, 'borrarDocumento']);
    Route::get('descargar-documento/{uuid}/{extension}/{area}/{nombre_archivo}', [EmpleadoController::class, 'descargarDocumento']);
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

Route::group([
    'prefix' => 'catalogo-proveedores'
], function ($router) {
    Route::post('guardar-catalogo', [CatalogoProveedorController::class, 'crearProductoCatalogo']);
    Route::put('actualizar-catalogo', [CatalogoProveedorController::class, 'actualizarProductoCatalogo']);
    Route::delete('eliminar-catalogo/{id}', [CatalogoProveedorController::class, 'eliminarProductoCatalogo']);
    Route::get('consultar-catalogo/{id}', [CatalogoProveedorController::class, 'consultarCatalogoProveedor']);
    Route::get('consultar-catalogos', [CatalogoProveedorController::class, 'consultarCatalogos']);
});

Route::group([
    'prefix' => 'areas'
], function ($router) {
    Route::get('consultar-areas', [AreasController::class, 'consultarAreas']);
});

Route::group([
    'prefix' => 'ordenes-compra'
], function ($router) {
    Route::post('guardar-orden-compra', [OrdenCompraController::class, 'crearOrdenCompra']);
    Route::get('consultar-orden-compra', [OrdenCompraController::class, 'consultarOrdenesCompra']);
});

Route::group([
    'prefix' => 'productos-ordenes-compra'
], function ($router) {
    Route::post('guardar-producto-orden-compra', [ProductoOrdenCompraController::class, 'crearProductoOrdenCompra']);
});

Route::group([
    'prefix' => 'calendario'
], function ($router) {
    Route::get('consultar-calendario-usuario/{ano}/{idUsuario}', [CalendarioController::class, 'consultarCalendarioUsuario']);
    Route::delete('eliminar-evento-calendario/{id}', [CalendarioController::class, 'eliminarEventoCalendarioUsuario']);
    Route::post('crear-evento-calendario', [CalendarioController::class, 'crearEventoCalendario']);
});

Route::group([
    'prefix' => 'sucursales'
], function ($router) {
    Route::get('consultar-sucursales', [SucursalesController::class, 'consultarSucursales']);
    Route::delete('eliminar-sucursal/{id}', [SucursalesController::class, 'eliminarSucursal']);
    Route::post('crear-sucursal', [SucursalesController::class, 'crearSucursal']);
    Route::put('actualizar-sucursal', [SucursalesController::class, 'actualizarSucursal']);
});

Route::group([
    'prefix' => 'stock-ventas'
], function ($router) {
    Route::get('consultar-productos', [StockVentaController::class, 'consultarProductosVentas']);
    Route::post('guardar-producto', [StockVentaController::class, 'crearProductoVentas']);
    Route::put('actualizar-producto', [StockVentaController::class, 'actualizarProductoVentas']);
    Route::delete('eliminar-producto/{id}', [StockVentaController::class, 'eliminarProductoVentas']);
});


