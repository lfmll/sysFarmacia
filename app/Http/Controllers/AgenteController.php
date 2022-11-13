<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Models\Laboratorio;
use Illuminate\Http\Request;

class AgenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agentes=Agente::all();
        return view('agente.index',['agentes'=>$agentes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agente=new Agente();
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        return view('agente.create',['agente'=>$agente])
                ->with('laboratorios',$laboratorios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agente= new Agente($request->all());
        $agente->nombre = $request->nombre;
        $agente->telefonos = $request->telefonos;
        $agente->anotacion = $request->anotacion;
        $agente->laboratorio_id=$request->laboratorios;
        if ($agente->save()) {
            return redirect('/agente')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('agente.create',['agente'=>$agente])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agente  $agente
     * @return \Illuminate\Http\Response
     */
    public function show(Agente $agente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agente  $agente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agente = Agente::find($id);
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        return view('agente.edit',['agente'=>$agente])
                ->with('laboratorios',$laboratorios);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agente  $agente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $agente=Agente::find($id);
        $agente->nombre = $request->nombre;
        $agente->telefonos = $request->telefonos;
        $agente->anotacion = $request->anotacion;
        $agente->laboratorio_id=$request->laboratorios;
        if ($agente->save()) {
            return redirect('/agente')->with('toast_success','Proveedor modificado exitosamente');
        } else {
            return view('agente.create',['agente'=>$agente])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agente  $agente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agente $agente)
    {
        //
    }
}
