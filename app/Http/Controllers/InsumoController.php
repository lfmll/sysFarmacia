<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insumo=Insumo::all();
        return view('insumo.index',['insumo'=>$insumo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $insumo=new Insumo();
        return view('insumo.create',['insumo'=>$insumo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $insumo=new Insumo($request->all());
        $insumo->codigo=$request->codigo;
        $insumo->nombre=$request->nombre;
        $insumo->descripcion=$request->descripcion;
        $insumo->stock=0;
        $insumo->stock_minimo=$request->stock_minimo;
        if ($insumo->save()) {
            return redirect('/insumo');
        } else {
            return view('insumo.create',['insumo'=>$insumo]);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $insumo=Insumo::findOrFail($id);
        return view('insumo.show',['insumo'=>$insumo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $insumo = Insumo::find($id);
        return view('insumo.edit',['insumo'=>$insumo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $insumo=Insumo::find($id);
        $insumo->codigo=$request->codigo;
        $insumo->nombre=$request->nombre;
        $insumo->descripcion=$request->descripcion;
        $insumo->stock_minimo=$request->stock_minimo;
        if ($insumo->save()) {
            return redirect('/insumo');
        } else {
            return view('insumo.edit',['insumo'=>$insumo]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Insumo $insumo)
    {
        //
    }
}
