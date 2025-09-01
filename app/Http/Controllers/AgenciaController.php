<?php

namespace App\Http\Controllers;

use App\Helpers\BitacoraHelper;
use App\Models\Agencia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgenciaController extends Controller
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
        $ultimoCodigo = Agencia::max('codigo');
        $agencia->codigo    = is_null($ultimoCodigo) ? '1' : $ultimoCodigo + 1;
        $agencia->nombre    = $request->nombre;
        $agencia->departamento    = $request->departamento;
        $agencia->municipio = $request->municipio;
        $agencia->direccion = $request->direccion; 
        $agencia->telefono  = $request->telefono;        
        $agencia->estado    = 'A';
        $agencia->empresa_id= Empresa::first()->id;        
        
        if ($agencia->save()) {
            BitacoraHelper::registrar('Registro Sucursal', 'Sucursal creada por el usuario: ' . Auth::user()->name, 'Agencia');
            return redirect('/agencia')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('agencia.create',['agencia'=>$agencia])->with('toast_error','Error al registrar');
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
        $ultimoCodigo = Agencia::max('codigo');
        $agencia->codigo    = is_null($ultimoCodigo) ? '1' : $ultimoCodigo + 1;
        $agencia->nombre    = $request->nombre;
        $agencia->departamento    = $request->departamento;
        $agencia->municipio = $request->municipio;
        $agencia->direccion = $request->direccion; 
        $agencia->telefono  = $request->telefono;        
        $agencia->estado    = 'A';
        $agencia->empresa_id= Empresa::first()->id;
        
        if ($agencia->save()) {
            //Registrar en Bitocora
            BitacoraHelper::registrar('Actualizar Sucursal', 'Sucursal actualizada por el usuario: ' . Auth::user()->name, 'Agencia');
            return redirect('/agencia')->with('toast_success','Registro Actualizado correctamente');
        } else {
            return view('agencia.create',['agencia'=>$agencia])->with('toast_error','Error al actualizar');
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
