<?php

namespace App\Http\Controllers;

use App\Imports\MedicamentoImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Storage;
use App\Models\Medicamento;
use App\Models\Lote;
use App\Models\Laboratorio;
use App\Models\Formato;
use App\Models\Via;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ImportController extends Controller
{
    public function importMedicamento()
    {
        return view('medicamento.importMedicamento');
    }
    public function importM(Request $request)
    {                
        $hasFile=$request->hasFile('cover');               
        date_default_timezone_set('America/La_Paz');
        $fecha = date('Y-m-d H:i:s');
        $mensaje="Importación realizada con exito";        
        if ($hasFile) {
            $extension=$request->file('cover')->extension();            
                
            if ($extension=="xlsx" || $extension =="xls" || $extension=="csv" || $extension=="txt") { 
                
                $path='/excelMedicamento';
                $archivos=Storage::disk('public_dir')->allFiles($path);
                $cantArchivos=count($archivos);
                $cantArchivos=$cantArchivos+1;                
                
                // $request->cover->move('excelMedicamento',"$cantArchivos.$extension");
                $excelFile = $_FILES['cover']['tmp_name'];

                $url=public_path('excelMedicamento');
                                
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                $spreadsheet = $reader->load($excelFile);
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setSheetIndex(0);
                $writer->save($url."/"."$cantArchivos.$extension");

                // $request->cover->move('excelMedicamento',"$cantArchivos.$extension");
                
                           
                $fila=1;                      
                $cabecera=array();
                $cabecera_modelo=array("medicamento","presentacion","via","cantidad","precio compra","laboratorio","lote","fecha vencimiento");
                
                if (($gestor=fopen(public_path('excelMedicamento/'.$cantArchivos.'.'.$extension),"r"))!==FALSE) {
                    
                    while (($datos=fgetcsv($gestor,1000,","))!==FALSE) {
                        $columnas=count($datos);
                        
                        if ($fila==1) {                            
                            for ($i=0; $i < $columnas; $i++) {
                                $col=explode(',',$datos[$i]);
                                $cabecera[$i]=strtolower($col[0]);
                            }                            
                            if ($cabecera!==$cabecera_modelo) {
                                $mensaje=nl2br("el archivo no coincide con el modelo requerido.\n".implode(",",$cabecera));
                                goto mensaje;
                            }
                        } else {
                            $medicamento_data = $datos[0];                            
                            $presentacion_data = $datos[1];
                            $via_data = $datos[2];
                            $cantidad_data = $datos[3];
                            $precio_compra_data = $datos[4];
                            $laboratorio_data = $datos[5];
                            $lote_data = $datos[6];
                            $fecha_vencimiento = $datos[7];
                            $fecha_vencimiento=str_replace('/','-',$fecha_vencimiento);                            
                            $fecha_vencimiento_data = date("Y-m-d", strtotime($fecha_vencimiento));                            

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
                                $medi->stock = $medi->stock + $cantidad_data;
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
                            
                        }                        
                        $fila++;                          
                    }                    
                    fclose($gestor);
                }
            }            
        } else {
            $mensaje="El archivo no fue subido correctamente";            
        }
        mensaje:            
            echo $mensaje;
        
    }
}
