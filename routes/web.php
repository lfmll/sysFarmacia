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
Route::get('/lote/{id}/create_medicamento',[LoteController::class,'create_medicamento']);
Route::get('/lote/{id}/create_insumo',[LoteController::class,'create_insumo']);