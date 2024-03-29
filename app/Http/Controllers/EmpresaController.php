<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa=new Empresa();        
        return view('empresa.create',['empresa' => $empresa]);       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hasFile=$request->hasFile('cover') && $request->cover->isValid();
        $empresa= new Empresa($request->all());
        $empresa->nombre = $request->nombre;
        $empresa->nit = $request->nit;
        $empresa->correo = $request->correo;
        $empresa->actividad = $request->actividad;
        $empresa->documento = $request->documento;
        $empresa->modalidad = $request->modalidad;
        $empresa->emision = $request->emision;
        $empresa->cuis =  $request->cuis;
        $empresa->vigencia_cuis = $request->vigencia_cuis;
        
        if ($hasFile) {
            $extension=$request->cover->extension();
            $empresa->extension=$extension;
        }        
        if ($empresa->save()) {
            if ($hasFile) {
                $request->cover->move('imagen',"$empresa->id.$extension");
            }
            return redirect('/home');
        } else {
            return view('empresa.create',['empresa'=>$empresa]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
