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
use App\Models\Caja;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Venta;
use App\Models\Parametro;
use App\Models\TipoParametro;

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
        $medicamentos=DB::table('medicamentos')
                ->join('parametros','medicamentos.codigo_clasificador','=','parametros.codigo_clasificador')
                ->join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
                ->where('tipo_parametros.nombre','=','UNIDAD MEDIDA')
                ->get();
        $pdf = PDF::loadView('medicamento.reporte',['medicamentos' => $medicamentos,'fecha'=>$fecha]);
        return $pdf->setPaper('letter','portrait')
                    ->stream('lista_medicamentos.pdf',array('Attachment'=>0));
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
    
    public function listaClientes()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $clientes=Cliente::where('estado','A')->get();
        $pdf=PDF::loadView('cliente.reporte',['clientes'=>$clientes,'fecha'=>$fecha]);
        return $pdf->download('Clientes.pdf');
    }

    public function detalleMedicamento($idMedicamento)
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y h:i A');
        $medicamento=Medicamento::find($idMedicamento);
        
        $unidad_medida = Parametro::join('medicamentos','medicamentos.codigo_clasificador','=','parametros.codigo_clasificador')
                                ->join('tipo_parametros','tipo_parametros.id','=','parametros.tipo_parametro_id')
                                ->where('medicamentos.codigo_clasificador','=',$medicamento->codigo_clasificador)
                                ->where('tipo_parametros.nombre','=','UNIDAD MEDIDA')
                                ->first();
        
        $clases = Clase::join('clases_medicamentos','clases_medicamentos.clase_id','=','clases.id')
                        ->join('medicamentos','medicamentos.id','=','clases_medicamentos.medicamento_id')
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
                                                            'unidad_medida'=>$unidad_medida,
                                                            'medidamedicamento1'=>$medidamedicamento1,
                                                            'medidamedicamento2'=>$medidamedicamento2,
                                                            'medidamedicamento3'=>$medidamedicamento3,
                                                            'dosis_estandar1'=>$dosis_estandar1,
                                                            'dosis_estandar2'=>$dosis_estandar2,
                                                            'dosis_estandar3'=>$dosis_estandar3]);
        return $pdf->setPaper('letter','portrait')->stream();
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
        $lotesVencidos=Lote::whereDate('fecha_vencimiento','<=',$fechaf)->get();
        $lotesPorVencer=Lote::whereBetween('fecha_vencimiento',[$fechai, $fechaf])->get();
        $pdf = PDF::loadView('lote.reporteLotesVencimiento',['lotesVencidos' => $lotesVencidos,'lotesPorVencer'=>$lotesPorVencer,'fecha'=>$fecha]);
        return $pdf->download('Lotes_Vencimiento.pdf');
    }

    public function reporteCierreAnterior()
    {
        $fecha = Carbon::now('America/La_Paz')->toDateString();
        $fechaAnt=Carbon::yesterday('America/La_Paz')->toDateString();    
        $cajas=Caja::where('fecha',$fechaAnt);
        $pdf = PDF::loadView('caja.reporteCierreAnterior',['cajas' => $cajas,'fecha'=>$fecha]);
        return $pdf->download('Cierre_Anterior.pdf');
    }

    public function facturaCarta($id)
    {        
        $factura=Factura::find($id);
        $detalleFactura=DetalleFactura::where('factura_id',$id)->get();        
        $venta=Venta::find($factura->venta_id);        
        
        $pdf=PDF::loadView('factura.factura_carta',['factura'=>$factura, 
                                                    'detalleFactura'=>$detalleFactura,
                                                    'venta'=>$venta]);       
        return $pdf->setPaper('letter','landscape')->stream(); 
    }

    public function facturaRollo($id)
    {
        $factura=Factura::find($id);
        $detalleFactura=DetalleFactura::where('factura_id',$id)->get();
        $venta=Venta::find($factura->venta_id);
        
        $pdf=PDF::loadView('factura.factura_rollo',['factura'=>$factura,
                                                    'detalleFactura'=>$detalleFactura,
                                                    'venta'=>$venta]);
        
        return $pdf->setPaper('B7','portrait')->stream();        
    }
}