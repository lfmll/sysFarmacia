<?php
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Laboratorio;
use App\Models\Medicamento;
use App\Models\Via;
use App\Models\Formato;
use App\Models\Insumo;
use App\Models\Clase;
use App\Models\Lote;
use App\Models\Medida;
use App\Models\ClaseMedicamento;
use App\Models\MedidaMedicamento;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];
          
        $pdf = PDF::loadView('reporte.reporte', $data);
    
        return $pdf->download('itsolutionstuff.pdf');
    }

    public function listaLaboratorios()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');    
        $laboratorios=Laboratorio::orderBy('nombre')->get();
        $pdf = PDF::loadView('laboratorio.reporte',['laboratorios' => $laboratorios,'fecha'=>$fecha]);
        return $pdf->download('Laboratorios.pdf');
    }

    public function listaMedicamentos()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $medicamentos=Medicamento::orderBy('nombre_comercial')->get();
        $pdf = PDF::loadView('medicamento.reporte',['medicamentos' => $medicamentos,'fecha'=>$fecha]);
        return $pdf->download('medicamentos.pdf');
    }
    public function listaInsumos()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $insumos=Insumo::orderBy('nombre')->get();
        $pdf=PDF::loadView('insumo.reporte',['insumos'=>$insumos,'fecha'=>$fecha]);
        return $pdf->download('insumos.pdf');
    }
    public function listaAcciones()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $clases=Clase::orderBy('clase')->get();
        $pdf=PDF::loadView('clase.reporte',['clases'=>$clases,'fecha'=>$fecha]);
        return $pdf->download('clase.pdf');
    }
    public function listaLotes()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $lotes=Lote::where('estado','A')->get();
        $pdf=PDF::loadView('lote.reporte',['lotes'=>$lotes,'fecha'=>$fecha]);
        return $pdf->download('lote.pdf');
    }
    public function detalleMedicamento($idMedicamento)
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $medicamento=Medicamento::find($idMedicamento);

        $clases = DB::table('clases')
                    ->join('clases_medicamentos','clases.id','=','clases_medicamentos.clase_id')
                    ->where('clases_medicamentos.medicamento_id','=',$medicamento->id) 
                    ->where('clases_medicamentos.estado','=','A') 
                    ->select('clases.nombre')
                    ->get();

        $medidamedicamento1=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',1)
                            ->where('estado','A')                            
                            ->first();

        $medidamedicamento2=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',2)
                            ->where('estado','A')
                            ->first();                            

        $medidamedicamento3=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',3)
                            ->where('estado','A')
                            ->first();
                                    
        $dosis_estandar1=null;
        $dosis_estandar2=null;
        $dosis_estandar3=null;

        if (!is_null($medidamedicamento1)) {
            $medidamedicamento1=$medidamedicamento1->descripcion;
            $dosis_estandar1=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',1)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar1)) {
                $dosis_estandar1=$dosis_estandar1->dosis_estandar;
            }                                                                    
        }
        if (!is_null($medidamedicamento2)) {
            $medidamedicamento2=$medidamedicamento2->descripcion;
            $dosis_estandar2=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',2)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar2)) {
                $dosis_estandar2=$dosis_estandar2->dosis_estandar;
            } 
        }
        if (!is_null($medidamedicamento3)) {
            $medidamedicamento3=$medidamedicamento3->descripcion;
            $dosis_estandar3=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',3)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar3)) {
                $dosis_estandar3=$dosis_estandar3->dosis_estandar;
            } 
        }                    

        $pdf=PDF::loadView('medicamento.reporteMedicamento',['medicamento'=>$medicamento,
                                                            'clases'=>$clases,
                                                            'fecha'=>$fecha,
                                                            'medidamedicamento1'=>$medidamedicamento1,
                                                            'medidamedicamento2'=>$medidamedicamento2,
                                                            'medidamedicamento3'=>$medidamedicamento3,
                                                            'dosis_estandar1'=>$dosis_estandar1,
                                                            'dosis_estandar2'=>$dosis_estandar2,
                                                            'dosis_estandar3'=>$dosis_estandar3]);
        return $pdf->download('medicamento.pdf');
    }
}