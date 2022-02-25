<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ViaController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\FormatoController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\FacturaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('laboratorio',LaboratorioController::class);
Route::resource('via',ViaController::class);
Route::resource('medida',MedidaController::class);
Route::resource('formato',FormatoController::class);
Route::resource('clase',ClaseController::class);
Route::resource('insumo',InsumoController::class);
Route::resource('medicamento',MedicamentoController::class);
Route::resource('lote', LoteController::class);
Route::resource('agente',AgenteController::class);
Route::get('/lote/{id}/create_medicamento',[LoteController::class,'create_medicamento']);
Route::get('/lote/{id}/create_insumo',[LoteController::class,'create_insumo']);
Route::resource('compra', CompraController::class);
Route::resource('venta', VentaController::class);
Route::resource('factura', FacturaController::class);

Route::get('/compra/{id}/salida',[CompraController::class, 'salida']);
Route::get('/venta/{id}/entrada',[VentaController::class, 'entrada']);

// Route::get('/lote/buscarProducto',[LoteController::class,'buscarProducto'])->name('lote.buscarProducto');
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::resource('caja', CajaController::class);

Route::get('listaLaboratorios',[PDFController::class,'listaLaboratorios']);