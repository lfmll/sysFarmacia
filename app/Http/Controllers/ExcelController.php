<?php

namespace App\Http\Controllers;

use App\Imports\MedicamentoImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Medicamento;
use App\Models\Cliente;
use App\Exports\MedicamentoExport;
use App\Exports\ClienteExport;
use App\Models\Lote;
use App\Models\Laboratorio;
use App\Models\Formato;
use App\Models\Via;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Session;
use Illuminate\Support\Facades\DB;

class ExcelController extends Controller
{        
    public function importMedicamento()
    {
        return view('medicamento.importMedicamento');
    }
    
    public function importM(Request $request)
    {                
        $hasFile=$request->hasFile('cover');
        $mensaje="Importación realizada con exito";        
        if ($hasFile==true) {            
            $extension=$request->file('cover')->extension();                            
            if ($extension=="xlsx" || $extension =="xls" || $extension=="csv" || $extension=="txt") { 
                
                $path=public_path('importeMedicamentos/');
                
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path);
                }

                $archivos=Storage::disk('public_dir')->allFiles('importeMedicamentos');                
                $cantArchivos=count($archivos);
                $cantArchivos=$cantArchivos+1;                
                
                $excelFile = $_FILES['cover']['tmp_name'];
                
                $url=public_path('importeMedicamentos');                
                                             
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                 
                $spreadsheet = $reader->load($excelFile);
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setSheetIndex(0);
                $writer->save($url."/"."$cantArchivos.csv");
                           
                $fila=1;                      
                $cabecera=array();
                $cabecera_modelo=array("medicamento","presentacion","via","cantidad","precio_compra","laboratorio","lote","fecha_vencimiento");
                
                if (($gestor=fopen(public_path('importeMedicamentos/'.$cantArchivos.'.csv'),"r"))!==FALSE) {                    
                    while (($datos=fgetcsv($gestor,1000,","))==TRUE) {
                        $columnas=count($datos);                        
                        if ($fila==1) {                            
                            for ($i=0; $i < $columnas; $i++) {
                                $col=explode(',',$datos[$i]);
                                $cabecera[$i]=strtolower($col[0]);
                            }                   
                                   
                            if ($cabecera != $cabecera_modelo) {
                                $mensaje="El archivo no coincide con el modelo requerido";
                                goto mensaje;
                            }
                        } else {
                            $medicamento_data   = $datos[0];                            
                            $presentacion_data  = $datos[1];
                            $via_data           = $datos[2];
                            $cantidad_data      = $datos[3];
                            $precio_compra_data = $datos[4];
                            $laboratorio_data   = $datos[5];
                            $lote_data          = $datos[6];
                            $fecha_vencimiento  = $datos[7];

                            $fecha_vencimiento=str_replace('/','-',$fecha_vencimiento);                            
                            $fecha_vencimiento_data = date("Y-m-d", strtotime($fecha_vencimiento));                            

                            DB::beginTransaction();
                            try {
                                
                                $medicamento=Medicamento::where('nombre_comercial',$medicamento_data)->first();                            
                                $laboratorio=Laboratorio::where('nombre',$laboratorio_data)->first();
                                $presentacion=Formato::where('descripcion',$presentacion_data)->first();
                                $via=Via::where('descripcion',$via_data)->first();

                                if(is_null($laboratorio)) {
                                    $laboratorio=new Laboratorio();
                                    $laboratorio->nombre=$laboratorio_data;
                                    $laboratorio->save();
                                }
                                if (is_null($medicamento)) {
                                    $medi=new Medicamento();
                                    $medi->nombre_comercial = $medicamento_data;
                                    $medi->nombre_generico = $medicamento_data;
                                    $medi->stock = $cantidad_data;
                                    $medi->stock_minimo = $cantidad_data;
                                    $medi->formato_id = $presentacion->id;
                                    $medi->via_id = $via->id;
                                    $medi->save();

                                    $lote=new Lote();
                                    $lote->numero = $lote_data;
                                    $lote->cantidad = $cantidad_data;
                                    $lote->fecha_vencimiento = $fecha_vencimiento_data;
                                    $lote->laboratorio_id = $laboratorio->id;
                                    $lote->medicamento_id = $medi->id;
                                    $lote->precio_compra = $precio_compra_data;
                                    $lote->ganancia = 0;
                                    $lote->precio_venta = 0;
                                    $lote->estado = 'A';
                                    $lote->save();
                                } else {
                                    $medicamento->stock = $medicamento->stock + $cantidad_data;
                                    $medicamento->save();                                
                                    $lote=Lote::where('medicamento_id',$medicamento->id)->first();                                
                                    $lote->numero = $lote_data;
                                    $lote->cantidad = $cantidad_data;
                                    $lote->fecha_vencimiento = $fecha_vencimiento_data;
                                    $lote->laboratorio_id = $laboratorio->id;
                                    $lote->medicamento_id = $medicamento->id;
                                    $lote->precio_compra = $precio_compra_data;
                                    $lote->ganancia = 0;
                                    $lote->precio_venta = 0;
                                    $lote->estado = 'A';
                                    $lote->save();
                                }
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                $mensaje="Hubo un error de escritura, fila=".$fila;
                                goto mensaje;
                            }
                                                                                                            
                            
                        }                        
                        $fila++;                          
                    }                    
                    fclose($gestor);
                }
            } else {
                $mensaje="El archivo no es de tipo xls, xlsx, csv o txt";
            }           
        } else {
            $mensaje="El archivo no fue subido correctamente";            
        }
        mensaje:
       
        if ($mensaje=="Importación realizada con exito") {
            return redirect('/importMedicamento')->with('success_message',$mensaje);
        } else {
            return redirect('/importMedicamento')->with('error_message',$mensaje);
        } 

    }

    public function formatoMedicamentos()
    {
        $cabecera = [
                        [
                            'medicamento' => "",
                            'presentacion' => "",
                            'via' => "",
                            'cantidad' => "",
                            'precio_compra' => "",
                            'laboratorio' => "",
                            'lote' => "",
                            'fecha_vencimiento' => ""
                        ]
                    ];
        return Excel::download(new MedicamentoExport($cabecera),'medicamentos.xlsx');
        
    }
    public function formatoClientes()
    {      
        $cabecera = [
                        [
                        'tipo_documento' => "",
                        'numero_documento' => "",
                        'complemento' => "",
                        'nombre' => "",
                        'correo' => "",
                        'telefono' => "",
                        'direccion' => ""
                        ]
                    ];  
        return Excel::download(new ClienteExport($cabecera),'clientes.xlsx');
    }

    public function importCliente()
    {
        return view('cliente.importCliente');
    }

    public function importC(Request $request)
    {
        $hasFile=$request->hasFile('cover');               
        date_default_timezone_set('America/La_Paz');
        $fecha = date('Y-m-d H:i:s');
        $mensaje="Importación realizada con exito";        
        if ($hasFile) {
            $extension=$request->file('cover')->extension();            
                
            if ($extension=="xlsx" || $extension =="xls" || $extension=="csv" || $extension=="txt") { 
                
                $path=public_path('importeClientes/');
                
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path);
                }

                $archivos=Storage::disk('public_dir')->allFiles('importeClientes');
                $cantArchivos=count($archivos);
                $cantArchivos=$cantArchivos+1;                
                                
                $excelFile = $_FILES['cover']['tmp_name'];

                $url=public_path('importeClientes');
                                
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                $spreadsheet = $reader->load($excelFile);
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setSheetIndex(0);
                $writer->save($url."/"."$cantArchivos.csv");

                $fila=1;                      
                $cabecera=array();
                $cabecera_modelo=array("tipo_documento","numero_documento","complemento","nombre","correo","telefono","direccion");
                
                if (($gestor=fopen(public_path('importeClientes/'.$cantArchivos.'.csv'),"r"))!==FALSE) {                    
                    while (($datos=fgetcsv($gestor,1000,","))!==FALSE) {
                        $columnas=count($datos);                        
                        if ($fila==1) {                            
                            for ($i=0; $i < $columnas; $i++) {
                                $col=explode(',',$datos[$i]);
                                $cabecera[$i]=strtolower($col[0]);
                            }                            
                            if ($cabecera!==$cabecera_modelo) {
                                $mensaje="el archivo no coincide con el modelo requerido.";
                                goto mensaje;
                            }
                        } else {
                            $tipo_documento_data     = $datos[0];
                            $numero_documento_data   = $datos[1];
                            $complemento_data        = $datos[2];
                            $nombre_data             = $datos[3];
                            $correo_data             = $datos[4];
                            $telefono_data           = $datos[5];
                            $direccion_data          = $datos[6];

                            DB::beginTransaction();
                            try {
                                $numero_documento=Cliente::where('numero_documento',$numero_documento_data)->first(); 
                                if (is_null($numero_documento)) {
                                    $cli=new Cliente();
                                    $cli->tipo_documento    = $tipo_documento_data;
                                    $cli->numero_documento  = $numero_documento_data;
                                    $cli->complemento       = $complemento_data;
                                    $cli->nombre            = $nombre_data;
                                    $cli->correo            = $correo_data;
                                    $cli->telefono          = $telefono_data;
                                    $cli->direccion         = $direccion_data;
                                    $cli->save();
                                }
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                $mensaje="Hubo un error de escritura, fila=".$fila;
                                goto mensaje;
                            }
                            
                        }                       
                        $fila++;                          
                    }                    
                    fclose($gestor);
                }
            } else {
                $mensaje="El archivo no es de tipo xls, xlsx, csv o txt";
            }          
        } else {
            $mensaje="El archivo no fue subido correctamente";            
        }
        mensaje:            
            if ($mensaje=="Importación realizada con exito") {
                return redirect('/importCliente')->with('success_message',$mensaje);
            } else {
                return redirect('/importCliente')->with('error_message',$mensaje);
            }
    }
}
