<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use Illuminate\Http\Request;

class MedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medida=Medida::all();
        return view('medida.index',['medida'=>$medida]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $medida=new Medida();
        return view('medida.create',['medida' => $medida]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $medida= new Medida($request->all());
        $medida->descripcion = $request->descripcion;
        
        if ($medida->save()) {
            return redirect('/medida');
        } else {
            return view('medida.create',['medida'=>$medida]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medida  $medida
     * @return \Illuminate\Http\Response
     */
    public function show(Medida $medida)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medida  $medida
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medida = Medida::find($id);
        return view('medida.edit',['medida'=>$medida]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medida  $medida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $medida = Medida::find($id);
        $medida->descripcion = $request->descripcion;
        
        if ($medida->save()) {
            return redirect('/medida');
        } else {
            return view('medida.edit',['medida'=>$medida]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medida  $medida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medida $medida)
    {
        //
    }
}
