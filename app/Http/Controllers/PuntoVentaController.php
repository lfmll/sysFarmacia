<?php

namespace App\Http\Controllers;

use App\Models\PuntoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agencia;

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
        $puntoventas = PuntoVenta::where('estado','A')
                                ->where('user_id',$userid)
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
        $puntoventa=new PuntoVenta();
        $agencias=Agencia::orderBy('nombre','ASC')->pluck('nombre','id');

        return view('puntoventa.create',['puntoventa' => $puntoventa])
                ->with('agencias',$agencias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = Auth::id();
        $puntoventa = new PuntoVenta($request->all());
        $puntoventa->descripcion = $request->descripcion;
        $puntoventa->agencia_id  = $request->agencia_id; 
        $puntoventa->user_id     = $userid;
        $puntoventa->estado      = 'A';

        if ($puntoventa->save()) {
            return redirect('/puntoventa')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('puntoventa.create',['puntoventa'=>$puntoventa])->with('toast_error','Error al registrar');
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
    public function destroy(PuntoVenta $puntoVenta)
    {
        //
    }    
}
