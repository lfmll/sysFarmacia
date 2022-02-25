<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;
use PDF;

class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $laboratorio=Laboratorio::all();
        return view('laboratorio.index',['laboratorio' => $laboratorio]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $laboratorio=new Laboratorio();
        return view('laboratorio.create',['laboratorio' => $laboratorio]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $laboratorio= new Laboratorio($request->all());
        $laboratorio->nombre = $request->nombre;
        $laboratorio->direccion = $request->direccion;
        $laboratorio->telefono = $request->telefono;
        $laboratorio->procedencia = $request->procedencia;
        $laboratorio->anotacion = $request->anotacion;
        if ($laboratorio->save()) {
            return redirect('/laboratorio');
        } else {
            return view('laboratorio.create',['laboratorio'=>$laboratorio]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function show(Laboratorio $laboratorio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $laboratorio = Laboratorio::find($id);
        return view('laboratorio.edit',['laboratorio'=>$laboratorio]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $laboratorio = Laboratorio::find($id);
        $laboratorio->nombre = $request->nombre;
        $laboratorio->direccion = $request->direccion;
        $laboratorio->telefono = $request->telefono;
        $laboratorio->procedencia = $request->procedencia;
        $laboratorio->anotacion = $request->anotacion;
        if ($laboratorio->save()) {
            return redirect('/laboratorio');
        } else {
            return view('laboratorio.edit',['laboratorio'=>$laboratorio]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laboratorio  $laboratorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laboratorio $laboratorio)
    {
        //
    }
}
