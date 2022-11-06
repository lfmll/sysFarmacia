<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class CajaController extends Controller
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
        $cajas=Caja::all();
        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        
        return view('caja.index',['cajas'=>$cajas,'dias'=>$dias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $caja=new Caja();
        $fecha=Carbon::now('America/La_Paz')->toDateString();
        $hora_inicio = Carbon::now('America/La_Paz')->format('H:i');
        return view('caja.create',['caja'=>$caja, 'fecha'=>$fecha, 'hora_inicio'=>$hora_inicio]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $caja = new Caja($request->all());
        $caja->fecha = $request->fecha;
        $caja->hora_inicio = $request->hora_inicio;
        $caja->monto_apertura = $request->monto_apertura;
        if ($caja->save()) {
            return redirect('/caja');
        } else {
            return view('caja.create',['caja'=>$caja]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show(Caja $caja)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {                
        $caja=Caja::all()->last();
        $fecha = Carbon::now('America/La_Paz')->toDateString();       
        $horai=date('00:00:00');
        $horaf=date('23:59:59');  

        $gastos=DB::table('compras')->whereBetween('fecha_compra',[$fecha.' '.$horai, $fecha.' '.$horaf])->get();
        $total_gasto=0;
        
        if (!is_null($gastos)) {
            foreach ($gastos as $gasto) {
                $total_gasto=$total_gasto+($gasto->pago_compra - $gasto->cambio_compra);
            }
        }

        $ganancias=DB::table('ventas')->whereBetween('fecha_venta',[$fecha.' '.$horai, $fecha.' '.$horaf])->get();
        $total_ganancia=0;
        if (!is_null($ganancias)) {
            foreach ($ganancias as $ganancia) {
                $total_ganancia=$total_ganancia+($ganancia->pago_venta - $ganancia->cambio_venta);
            }
        }
        
        return view('caja.edit',['caja'=>$caja, 'total_gasto'=>$total_gasto, 'total_ganancia'=>$total_ganancia]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caja $caja)
    {
        $caja=Caja::all()->last();
        $hora_fin = Carbon::now('America/La_Paz')->format('H:i');
        
        $caja->b200=$request->b200;
        $caja->b100=$request->b100;
        $caja->b50=$request->b50;
        $caja->b20=$request->b20;
        $caja->b10=$request->b10;
        $caja->m5=$request->m5;
        $caja->m2=$request->m2;
        $caja->m1=$request->m1;
        $caja->m05=$request->m05;
        $caja->m02=$request->m02;
        $caja->m01=$request->m01;
        $caja->hora_fin=$hora_fin;
        $caja->monto_apertura=$request->monto_apertura;
        $caja->gastos=$request->gastos;
        $caja->ganancias=$request->ganancias;
        $caja->total=$request->total;

        if ($caja -> save()) {
            return redirect('/caja');
        } else {
            return view('caja.edit',['caja'=>$caja]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja)
    {
        //
    }
}
