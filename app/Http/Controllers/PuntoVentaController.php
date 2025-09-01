<?php

namespace App\Http\Controllers;

use App\Helpers\BitacoraHelper;
use App\Models\PuntoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agencia;
use App\Models\Ajuste;
use App\Models\Cuis;
use App\Models\Parametro;
use PhpParser\Builder\Param;
use Illuminate\Support\Facades\DB;

class PuntoVentaController extends Controller
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
        $userid = Auth::id();
        $puntoventas = PuntoVenta::join('user_punto_venta','punto_ventas.id','=','user_punto_venta.punto_venta_id')
                                ->join('users','user_punto_venta.user_id','=','users.id')
                                ->where('user_punto_venta.user_id','=',$userid)
                                ->where('punto_ventas.estado','=','A')
                                ->where('user_punto_venta.estado','=','A')
                                ->get();
        return view('puntoventa.index',['puntoventas' => $puntoventas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipoPuntoVenta = [
            '1' => 'Comisionista',
            '2' => 'Ventanilla de Cobranza',
            '3' => 'Venta Móviles',
            '4' => 'Venta YPFB',
            '5' => 'Cajero',
            '6' => 'Conjunta'
        ];
        $puntoventa=new PuntoVenta();
        $agencias=Agencia::orderBy('nombre','ASC')->pluck('nombre','id');

        return view('puntoventa.create',['puntoventa' => $puntoventa])
                ->with('agencias',$agencias)
                ->with('tipoPuntoVenta',$tipoPuntoVenta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlOperaciones = $ajuste->wsdl."/FacturacionOperaciones?wsdl";
        $clienteSoap = Ajuste::consumoSIAT($token, $wsdlOperaciones);
        
        $msjError = PuntoVenta::registrarPuntoVenta($clienteSoap, $request);
        if ($msjError == "") {
            BitacoraHelper::registrar('Registro Punto de Venta', 'Punto de Venta creado por el usuario: ' . Auth::user()->name, 'PuntoVenta');
            return redirect('/puntoventa')->with('toast_success','Registro realizado exitosamente');
        } else { 
            return view('puntoventa.create',['puntoventa'=>$request])->with('toast_error', $msjError);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PuntoVenta  $puntoVenta
     * @return \Illuminate\Http\Response
     */
    public function show(PuntoVenta $puntoVenta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PuntoVenta  $puntoVenta
     * @return \Illuminate\Http\Response
     */
    public function edit(PuntoVenta $puntoVenta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PuntoVenta  $puntoVenta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PuntoVenta $puntoVenta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PuntoVenta  $puntoVenta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlOperaciones = $ajuste->wsdl."/FacturacionOperaciones?wsdl";
        $clienteSoap = Ajuste::consumoSIAT($token, $wsdlOperaciones);
        $msjError = PuntoVenta::cerrarPuntoVenta($clienteSoap, $id);
        if ($msjError == "") {
            BitacoraHelper::registrar('Cierre Punto de Venta', 'Punto de Venta cerrado por el usuario: ' . Auth::user()->name, 'PuntoVenta');
            return redirect('/puntoventa')->with('toast_success','Punto de Venta cerrado exitosamente');
        } else {
            return redirect('/puntoventa')->with('toast_error', $msjError);
        }
        
    }  
    
    public function cargarPuntosVentaU(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('punto_ventas as pv')
                    ->join('agencias as a', 'pv.agencia_id', '=', 'a.id')                
                    ->join('user_punto_venta as upv', 'pv.id', '=', 'upv.punto_venta_id')
                    ->join('users as u', 'upv.user_id', '=', 'u.id')
                    ->where('u.id','=', Auth::user()->id)
                    ->where('a.id','=', $request->agencia)
                    ->where('pv.estado','=', 'A')
                    ->where('upv.estado','=', 'A')
                    ->select('pv.id', 'pv.nombre')
                    ->get();
            if ($data->isEmpty()) {
                return response()->json(["mensaje"=>"No existe puntos de ventas en esta Sucursal"],409);
            }
        }
        
        return response()->json($data);
    }

    
}
