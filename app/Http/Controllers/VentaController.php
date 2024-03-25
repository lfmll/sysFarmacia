<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Medicamento;
use App\Models\Insumo;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Luecano\NumeroALetras\NumeroALetras;

class VentaController extends Controller
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
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $lotesm=Lote::where('estado','A')
                    ->where('medicamento_id','<>',null)                    
                    ->get(); 
        
        $clientes=Cliente::where('estado','A')
                    ->get();

        return view('venta.create',['venta'=>$venta])
                ->with('lotesm',$lotesm)
                ->with('clientes',$clientes);                
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // dd($request);
        try {            
            DB::beginTransaction();            
            $venta = new Venta($request->all());            
            $venta->fecha_venta = Carbon::now('America/La_Paz')->toDateTimeString();
            $venta->subtotal = $request->eSubTotal;
            $venta->descuento = $request->eDescuento;
            $venta->total = $request->eTotal;
            $venta->monto_giftcard = $request->eMontoGiftCard;
            
            $venta->importe_iva = $request->eTotalIVA;
            
            $literal = new NumeroALetras();
            $venta->literal = $literal->toMoney($venta->eTotalIVA,2);
            
            $venta->estado='A';
            $venta->metodo_pago_id = $request->forma_pago;
            $venta->monto_pagar = $request->ppago_efectivo;
            $venta->cambio_venta = $request->cambio;
            $venta->monto_giftcard = $request->ppago_giftcard;
            
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
                
                if (!is_null($lote->medicamento_id)) {
                    $medicamento = Medicamento::find($lote->medicamento_id);
                    $medicamento->stock = $medicamento->stock - $dcantidad[$cont];
                    $medicamento->save();
                }

                if (!is_null($lote->insumo_id)) {
                    $insumo = Insumo::find($lote->insumo_id);
                    $insumo->stock = $insumo->stock - $dcantidad[$cont];
                    $insumo->save();
                }

                if (!is_null($lote->producto_id)) {
                    $producto = Producto::find($lote->producto_id);
                    $producto->stock = $producto->stock - $dcantidad[$cont];
                    $producto->save();
                }
                
                $cont = $cont + 1;
            }

            //Factura
            $fecha_emision=Carbon::now('America/La_Paz')->format('YmdHisv');
            $empresa = Empresa::first();
            $agencia = Agencia::first();
            $factura = new Factura($request->all());
            $cantFactura = Factura::count();
            $factura->nitEmisor = $empresa->nit;
            $factura->razonSocialEmisor = $empresa->actividad;
            $factura->municipio = $agencia->municipio;
            $factura->telefono = $agencia->telefono;
            $factura->numeroFactura = $cantFactura++;            
            $factura->cuf =  $factura->generarCUF($empresa->nit,    //$nit
                                                $agencia->sucursal, //$sucursal
                                                $fecha_emision,     //$fecha
                                                $empresa->modalidad,//$modalidad
                                                $empresa->emision,  //$tipo_emision
                                                $empresa->documento,//$tipo_factura
                                                                    //$tipo_documento_sector
                                                                    //$nro_factura
                                                                    //$pos
                                            );// llenar

            if ($request->Factura=="factura") {
                $nit=$request->Nit;
                $razon=$request->Razon;
                $autorizacion=$request->Autorizacion;
            }
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
        }
        if ($venta->save()) {
            return redirect("/venta")->with('toast_success','Cobro realizado exitosamente');
        }else {
            return view("venta.create",["venta"=>$venta])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show($venta)
    {
        $detalleventas=DetalleVenta::where('venta_id',$venta)->get();        
        return view("venta.detalle",["detalleventas"=>$detalleventas]); 
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

    public function entrada(){
        return view("venta.entrada");
    }

}
