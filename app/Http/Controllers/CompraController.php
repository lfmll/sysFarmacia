<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Agente;
use App\Models\DetalleCompra;
use App\Models\Medicamento;
use App\Models\Lote;
use App\Models\Producto;
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

        $c=DB::table('compras')
                ->whereBetween('fecha_compra',[$fecha_compra.' '.$horai, $fecha_compra.' '.$horaf])
                ->count();
                          
        $comprobante = str_replace('-','',$fecha_compra).$c;
                        
        $lotesm=Lote::where('estado','A')
                    ->where('medicamento_id','<>',null)                    
                    ->get();
        
        $lotesi=Lote::where('estado','A')
                    ->where('insumo_id','<>',null)                                        
                    ->get();

        $lotesp=Lote::where('estado','A')
                    ->where('producto_id','<>',null)
                    ->get();                     

        return view('compra.create',['compra'=>$compra, 'comprobante'=>$comprobante])
                ->with('agentes',$agentes)                
                ->with('lotesm',$lotesm)
                ->with('lotesi',$lotesi)
                ->with('lotesp',$lotesp);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        try {
            DB::beginTransaction();
            $compra = new Compra($request->all());
            
            if (is_null($compra->glosa)) {
                $compra->comprobante=$request->comprobante;
                $compra->fecha_compra=Carbon::now('America/La_Paz')->toDateTimeString();            
                $compra->agente_id =$request->agentes;                        
                $compra->pago_compra=$request->Pago;
                $compra->cambio_compra=$request->Cambio;
                $compra->forma_pago=$request->forma_pago;
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

                    if (!is_null($lote->medicamento_id)) {
                        $medicamento = Medicamento::find($lote->medicamento_id);
                        $medicamento->stock = $medicamento->stock + $dcantidad[$cont];
                        $medicamento->save();
                    }

                    if (!is_null($lote->insumo_id)) {
                        $insumo = Insumo::find($lote->insumo_id);
                        $insumo->stock = $insumo->stock + $dcantidad[$cont];
                        $insumo->save();
                    }

                    if (!is_null($lote->producto_id)) {
                        $producto = Producto::find($lote->producto_id);
                        $producto->stock = $producto->stock + $dcantidad[$cont];
                        $producto->save();
                    }
                    
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
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
