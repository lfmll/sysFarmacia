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

    public function reporte()
    {
        $fecha = Carbon::now('America/La_Paz')->toDateString();        
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $fechai=Carbon::now('America/La_Paz')->toDateString();
        $fechaf=Carbon::now('America/La_Paz')->addMonths(5)->toDateString();

        $cantVentas=DB::table('ventas')
                ->whereBetween('fecha_venta',[$fecha.' '.$horai, $fecha.' '.$horaf])
                ->count();
        
        $cantCompras=DB::table('compras')
                ->whereBetween('fecha_compra',[$fecha.' '.$horai, $fecha.' '.$horaf])
                ->count();

        $cantLotes=DB::table('lotes')
                ->whereBetween('fecha_vencimiento',[$fechai,$fechaf])
                ->count();               

        return view('reporte.reporte',['cantVentas'=>$cantVentas,'cantCompras'=>$cantCompras,'cantLotes'=>$cantLotes]);
    }
    public function reporteVentaDia()
    {
        $fecha_venta = Carbon::now('America/La_Paz')->toDateString();
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $ventas=DB::table('ventas')
                ->whereBetween('fecha_venta',[$fecha_venta.' '.$horai, $fecha_venta.' '.$horaf])
                ->get(); 

        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");                       
        $fecha = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        
        $pdf = PDF::loadView('venta.reporteVentaDia',['ventas' => $ventas,'fecha'=>$fecha]);
        return $pdf->download('Ventas_Dia.pdf');        
    }
    public function reporteVentaMensual()
    {        
        $fechai=Carbon::now('America/La_Paz')->startOfMonth()->toDateString();
        $fechaf=Carbon::now('America/La_Paz')->endOfMonth()->toDateString();
     
        $ventas=DB::table('ventas')
                ->whereBetween('fecha_venta',[$fechai, $fechaf])
                ->get();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes=$meses[date('n')-1];
        
        $pdf = PDF::loadView('venta.reporteVentaMensual',['ventas' => $ventas,'mes'=>$mes]);
        return $pdf->download('Ventas_Mes.pdf');               
    }
    public function reporteVentaAnual()
    {
        $anio=date('Y');
        $fechai=$anio.'-01-01';
        $fechaf=$anio.'-12-31';
        $ventas=DB::table('ventas')
                ->whereBetween('fecha_venta',[$fechai, $fechaf])
                ->get();

        $pdf = PDF::loadView('venta.reporteVentaAnual',['ventas' => $ventas,'anio'=>$anio]);
        return $pdf->download('Ventas_Anual.pdf');
    }
    public function reporteCompraDia()
    {
        $fecha_compra = Carbon::now('America/La_Paz')->toDateString();
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $compras=DB::table('compras')
                ->whereBetween('fecha_compra',[$fecha_compra.' '.$horai, $fecha_compra.' '.$horaf])
                ->get(); 

        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");                       
        $fecha = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
        
        $pdf = PDF::loadView('compra.reporteCompraDia',['compras' => $compras,'fecha'=>$fecha]);
        return $pdf->download('Compras_dia.pdf');
    }
    public function reporteCompraMensual()
    {
        $fechai=Carbon::now('America/La_Paz')->startOfMonth()->toDateString();
        $fechaf=Carbon::now('America/La_Paz')->endOfMonth()->toDateString();
     
        $compras=DB::table('compras')
                ->whereBetween('fecha_compra',[$fechai, $fechaf])
                ->get();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes=$meses[date('n')-1];
        
        $pdf = PDF::loadView('compra.reporteCompraMensual',['compras' => $compras,'mes'=>$mes]);
        return $pdf->download('Compras_Mes.pdf');         
    }
    public function reporteCompraAnual()
    {
        $anio=date('Y');
        $fechai=$anio.'-01-01';
        $fechaf=$anio.'-12-31';
        $compras=DB::table('compras')
                ->whereBetween('fecha_compra',[$fechai, $fechaf])
                ->get();

        $pdf = PDF::loadView('compra.reporteCompraAnual',['compras' => $compras,'anio'=>$anio]);
        return $pdf->download('Compras_Anual.pdf');
    }
    public function reporteLotesVencimiento()
    {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");                       
        $fecha = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
        $fechai=Carbon::now('America/La_Paz')->toDateString();
        $fechaf=Carbon::now('America/La_Paz')->addMonths(5)->toDateString();
        $lotes=Lote::whereBetween('fecha_vencimiento',[$fechai,$fechaf])
                ->get();
        
        $pdf = PDF::loadView('lote.reporteLotesVencimiento',['lotes' => $lotes,'fecha'=>$fecha]);
        return $pdf->download('Lotes_en_Vencimiento.pdf');
    }
}