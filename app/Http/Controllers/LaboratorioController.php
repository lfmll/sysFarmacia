<?php

namespace App\Http\Controllers;

use App\Helpers\BitacoraHelper;
use App\Models\Bitacora;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class LaboratorioController extends Controller
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
            BitacoraHelper::registrar('Registro Laboratorio', 'Laboratorio creado por el usuario: ' . Auth::user()->name, 'Laboratorio');
            return redirect('/laboratorio')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('laboratorio.create',['laboratorio'=>$laboratorio])->with('toast_error','Error al registrar');
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
            BitacoraHelper::registrar('Actualización Laboratorio', 'Laboratorio modificado por el usuario: ' . Auth::user()->name, 'Laboratorio');
            return redirect('/laboratorio')->with('toast_success','Laboratorio modificado realizado exitosamente');
        } else {
            return view('laboratorio.edit',['laboratorio'=>$laboratorio])->with('toast_error','Error al registrar');
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
