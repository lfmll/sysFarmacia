<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicamento=Medicamento::all();
        return view('medicamento.index',['medicamento'=>$medicamento]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $medicamento=new Medicamento();
        return view('medicamento.create',['medicamento'=>$medicamento]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $medicamento=new Medicamento($request->all());
        $medicamento->nombre_comercial=$request->nombre_comercial;
        $medicamento->nombre_generico=$request->nombre_generico;
        $medicamento->composicion=$request->composicion;
        $medicamento->indicacion=$request->indicacion;
        $medicamento->contraindicacion=$request->contraindicacion;
        $medicamento->stock=$request->stock;
        $medicamento->stock_minimo=$request->stock_minimo;

        $medicamento->id_formato=$request->id_formato;
        $medicamento->id_laboratorio=$request->id_laboratorio;
        $medicamento->id_via=$request->id_via;
        if ($medicamento->save()) {
            return redirect('/medicamento');
        } else {
            return view('medicamento.create',['medicamento'=>$medicamento]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medicamento=Medicamento::findOrFail($id);
        return view('medicamento.show',['medicamento'=>$medicamento]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Medicamento $medicamento)
    {
        $medicamento = Medicamento::find($id);
        return view('medicamento.edit',['medicamento'=>$medicamento]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $medicamento=Medicamento::find($id);
        $medicamento->nombre_comercial=$request->nombre_comercial;
        $medicamento->nombre_generico=$request->nombre_generico;
        $medicamento->composicion=$request->composicion;
        $medicamento->indicacion=$request->indicacion;
        $medicamento->contraindicacion=$request->contraindicacion;
        $medicamento->stock=$request->stock;
        $medicamento->stock_minimo=$request->stock_minimo;

        $medicamento->id_formato=$request->id_formato;
        $medicamento->id_laboratorio=$request->id_laboratorio;
        $medicamento->id_via=$request->id_via;
        if ($medicamento->save()) {
            return redirect('/medicamento');
        } else {
            return view('medicamento.edit',['medicamento'=>$medicamento]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicamento $medicamento)
    {
        //
    }
}
