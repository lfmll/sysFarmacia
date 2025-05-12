<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Agente;
use App\Models\DetalleCompra;
use App\Models\Medicamento;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Cuis;
use App\Models\Codigo;
use App\Models\Catalogo;
use App\Models\Parametro;
use App\Models\Clase;
use App\Models\Via;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compras=Compra::all();
        return view('compra.index',['compras'=>$compras]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $compra=new Compra();
        $agentes=Agente::orderBy('nombre','ASC')->pluck('nombre','id');
        
        $fecha_compra = Carbon::now('America/La_Paz')->toDateString();
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $cuis = Cuis::obtenerCuis();

        $codigoInicial = Codigo::where('cuis_id',$cuis->id)->orderBy('descripcion','ASC')->first();
        $catalogoInicial = Catalogo::join('codigos','catalogos.codigo_actividad','=','codigos.codigo_caeb')
                                    ->where('codigos.codigo_caeb','=',$codigoInicial->codigo_caeb)                                    
                                    ->orderBy('catalogos.descripcion_producto','ASC')                                    
                                    ->pluck('catalogos.descripcion_producto','catalogos.codigo_producto');

        $c=DB::table('compras')
            ->whereBetween('fecha_compra',[$fecha_compra.' '.$horai, $fecha_compra.' '.$horaf])
            ->count();
                    
        $comprobante = str_replace('-','',$fecha_compra).$c;
                        
        $lotesm=Lote::where('estado','A')
                    ->where('medicamento_id','<>',null)                    
                    ->get();

        $actividades = Codigo::where('cuis_id',$cuis->id)
                    ->orderBy('descripcion','ASC')
                    ->pluck('descripcion','codigo_caeb');
        $clases = Clase::orderBy('nombre','ASC')->pluck('nombre','id');
        $unidad_medida = Parametro::join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
                                ->where('tipo_parametros.nombre','=','UNIDAD MEDIDA')
                                ->orderBy('parametros.descripcion','ASC')
                                ->pluck('parametros.descripcion','parametros.codigo_clasificador');

        $vias = Via::orderBy('descripcion','ASC')->pluck('descripcion','id');

        $laboratorios = Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        return view('compra.create',['compra'=>$compra, 'comprobante'=>$comprobante])
                ->with('agentes',$agentes)  
                ->with('actividades',$actividades)
                ->with('catalogos',$catalogoInicial)
                ->with('clases',$clases)
                ->with('unidad_medida',$unidad_medida)
                ->with('vias',$vias)
                ->with('laboratorios',$laboratorios)
                ->with('lotesm',$lotesm);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {                   
            $compra = new Compra($request->all());
            
            if (is_null($compra->glosa)) {
                $compra->comprobante=$request->comprobante;
                $compra->fecha_compra=Carbon::now('America/La_Paz')->toDateTimeString();            
                $compra->agente_id =$request->agentes;                        
                $compra->pago_compra=$request->Pago;
                $compra->cambio_compra=$request->Cambio;
                $compra->forma_pago=$request->forma_pago;
                $compra->estado='A';
                $compra->save();

                $dcantidad = $request->get('dcantidad');
                $dprecio = $request->get('dprecio');
                $dlote = $request->get('dcodigo');
                
                $cont = 0;
                while ($cont < count($dcantidad)) {
                    $detalle = new DetalleCompra();
                    $detalle->compra_id = $compra->id;
                    $detalle->cantidad = $dcantidad[$cont];
                    $detalle->precio_compra = $dprecio[$cont];
                    $detalle->lote_id = $dlote[$cont];
                    $detalle->save();

                    $lote = Lote::find($dlote[$cont]);
                    $cantlote = $lote->cantidad;
                    $cantlote = $cantlote + $dcantidad[$cont];
                    $lote->cantidad = $cantlote;
                    $lote->precio_compra = $dprecio[$cont];
                    $lote->save();

                    $medicamento = Medicamento::find($lote->medicamento_id);
                    $medicamento->stock = $medicamento->stock + $dcantidad[$cont];
                    $medicamento->save();
                    
                    $cont = $cont + 1;
                }                
            }else {
                $compra->fecha_compra=Carbon::now('America/La_Paz')->toDateTimeString();
                $compra->pago_compra=$request->Pago;
                $compra->cambio_compra=0;
                $compra->glosa=$request->glosa;
                $compra->forma_pago=$request->forma_pago;
                $compra->save();                
            }
        if ($compra->save()) {
            return redirect("/compra")->with('toast_success','Pago realizado exitosamente');
        }else {
            return view("compra.create",["compra"=>$compra])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show($compra)
    {
        $detallecompras=DetalleCompra::where('compra_id',$compra)->get();        
        return view("compra.detalle",["detallecompras"=>$detallecompras]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function salida()
    {                
        return view("compra.salida");
    }
}
