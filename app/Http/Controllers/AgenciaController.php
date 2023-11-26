<?php

namespace App\Http\Controllers;

use App\Models\Agencia;
use Illuminate\Http\Request;

class AgenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agencias = Agencia::where('estado','A')->get();    
        return view('agencia.index',['agencias'=>$agencias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agencia=new Agencia();
        return view('agencia.create',['agencia' => $agencia]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agencia = new Agencia($request->all());
        $agencia->nombre    = $request->nombre;
        $agencia->direccion = $request->direccion; 
        $agencia->telefono  = $request->telefono;
        $agencia->ciudad    = $request->ciudad;
        $agencia->municipio = $request->municipio;
        $agencia->estado    = 'A';
        $agencia->empresa_id= $request->empresa_id;
        
        
        if ($agencia->save()) {
            return redirect('/agencia');
        } else {
            return view('agencia.create',['agencia'=>$agencia]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agencia  $agencia
     * @return \Illuminate\Http\Response
     */
    public function show(Agencia $agencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agencia  $agencia
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agencia = Agencia::find($id);
        return view('agencia.edit',['agencia'=>$agencia]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agencia  $agencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $agencia = Agencia::find($id);
        $agencia->nombre    = $request->nombre;
        $agencia->direccion = $request->direccion; 
        $agencia->telefono  = $request->telefono;
        $agencia->ciudad    = $request->ciudad;
        $agencia->municipio = $request->municipio;
        $agencia->estado    = 'A';
        $agencia->empresa_id= $request->empresa_id;
        
        
        if ($agencia->save()) {
            return redirect('/agencia');
        } else {
            return view('agencia.create',['agencia'=>$agencia]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agencia  $agencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agencia $agencia)
    {
        //
    }
}
