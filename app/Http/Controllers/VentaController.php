<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Medicamento;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas=Venta::all();
        return view('venta.index',['ventas'=>$ventas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $venta=new Venta();

        $fecha_venta = Carbon::now('America/La_Paz')->toDateString();
        $hoy=date("Y-m-d");
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $c=DB::table('ventas')
                ->whereBetween('fecha_venta',[$hoy.' '.$horai, $hoy.' '.$horaf])
                ->count();
                          
        $comprobante = str_replace('-','',$hoy).$c;

        $lotes=Lote::where('estado','A')->get();

        $medicamentos=DB::table('medicamentos')
                        ->select('nombre_comercial','id')
                        ->where('stock','>','stock_minimo');

        $productos=DB::table('insumos')
                    ->select('nombre','id')
                    ->where('stock','>','stock_minimo')
                    ->union($medicamentos)
                    ->get(); 

        return view('venta.create',['venta'=>$venta, 'comprobante'=>$comprobante])
                ->with('productos',$productos)
                ->with('lotes',$lotes);
                
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
            $venta = new Venta($request->all());            
            $venta->comprobante=$request->comprobante;
            $venta->fecha_venta=Carbon::now('America/La_Paz')->toDateTimeString();
            $venta->pago_venta=$request->Pago;
            $venta->cambio_venta=$request->Cambio;
            $venta->save();

            $dcantidad = $request->get('dcantidad');
            $dprecio = $request->get('dprecio');
            $dlote = $request->get('dcodigo');
            
            $cont = 0;
            while ($cont < count($dcantidad)) {
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->cantidad = $dcantidad[$cont];
                $detalle->precio_venta = $dprecio[$cont];                
                $detalle->lote_id = $dlote[$cont];                                                       
                $detalle->save();

                $lote = Lote::find($dlote[$cont]);
                $cantlote = $lote->cantidad;
                $cantlote = $cantlote - $dcantidad[$cont];                
                $lote->cantidad = $cantlote;
                $lote->precio_venta = $dprecio[$cont];
                $lote->save();
                
                $cont = $cont + 1;
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
        }
        if ($venta->save()) {
            return redirect("/venta");
        }else {
            return view("venta.create",["venta"=>$venta]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }
}
