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
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\AgenciaController;
use App\Http\Controllers\PuntoVentaController;

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
// Route::get('charts',[App\Http\Controllers\HomeController::class,'index'])->name('home');
Route::resource('empresa',EmpresaController::class);
Route::resource('laboratorio',LaboratorioController::class);
Route::resource('via',ViaController::class);
Route::resource('medida',MedidaController::class);
Route::resource('formato',FormatoController::class);
Route::resource('clase',ClaseController::class);
Route::resource('medicamento',MedicamentoController::class);
Route::resource('lote', LoteController::class);
Route::resource('agente',AgenteController::class);
Route::get('/lote/{id}/create_medicamento',[LoteController::class,'create_medicamento']);
Route::get('/lote/{id}/create_producto',[LoteController::class,'create_producto']);
Route::resource('compra', CompraController::class)->middleware('aperturar');
Route::resource('venta', VentaController::class)->middleware('aperturar');
Route::resource('factura', FacturaController::class);
Route::resource('producto', ProductoController::class);
Route::resource('cliente', ClienteController::class);
Route::resource('catalogo', CatalogoController::class);
Route::resource('agencia', AgenciaController::class);
Route::resource('puntoventa', PuntoVentaController::class);

Route::get('/compra/{id}/salida',[CompraController::class, 'salida']);
Route::get('/venta/{id}/entrada',[VentaController::class, 'entrada']);

Route::get('generate-pdf', [PDFController::class, 'generatePDF']);

Route::get('/caja',[CajaController::class,'index']);
Route::get('/caja/create',[CajaController::class,'create']);
Route::post('/caja',[CajaController::class,'store']);
Route::get('/caja/{caja}',[CajaController::class,'show']);
Route::get('/caja/{caja}/edit',[CajaController::class,'edit'])->middleware('aperturar');
Route::put('/caja/{caja}',[CajaController::class,'update']);

Route::get('listaLaboratorios',[PDFController::class,'listaLaboratorios']);
Route::get('listaMedicamentos',[PDFController::class,'listaMedicamentos']);
Route::get('listaInsumos',[PDFController::class,'listaInsumos']);
Route::get('listaAcciones',[PDFController::class,'listaAcciones']);
Route::get('listaLotes',[PDFController::class,'listaLotes']);
Route::get('listaProductos',[PDFController::class,'listaProductos']);
Route::get('listaClientes',[PDFController::class,'listaClientes']);
Route::get('detalleMedicamento/{id}',[PDFController::class,'detalleMedicamento']);
Route::get('reporte',[DocumentoController::class,'reporte']);
Route::get('importe',[DocumentoController::class,'importe']);
Route::get('reporteVentaDia',[PDFController::class,'reporteVentaDia']);
Route::get('reporteVentaMensual',[PDFController::class,'reporteVentaMensual']);
Route::get('reporteVentaAnual',[PDFController::class,'reporteVentaAnual']);
Route::get('reporteCompraDia',[PDFController::class,'reporteCompraDia']);
Route::get('reporteCompraMensual',[PDFController::class,'reporteCompraMensual']);
Route::get('reporteCompraAnual',[PDFController::class,'reporteCompraAnual']);
Route::get('reporteCierreAnterior',[PDFController::class,'reporteCierreAnterior']);
Route::get('reporteLotesVencimiento',[PDFController::class,'reporteLotesVencimiento']);
Route::get('importMedicamento',[ExcelController::class,'importMedicamento']);
Route::post('importM',[ExcelController::class,'importM']);
Route::get('formatoMedicamentos',[ExcelController::class,'formatoMedicamentos']);
Route::get('formatoClientes',[ExcelController::class,'formatoClientes']);
Route::get('importCliente',[ExcelController::class,'importCliente']);
Route::post('importC',[ExcelController::class,'importC']);

