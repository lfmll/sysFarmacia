<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;
use App\Models\Laboratorio;
use App\Models\Via;
use App\Models\Formato;
use App\Models\Clase;
use App\Models\Medida;
use App\Models\ClaseMedicamento;
use App\Models\MedidaMedicamento;
use Illuminate\Support\Facades\DB;

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
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        $vias=Via::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $formatos=Formato::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $clases=Clase::orderBy('nombre','ASC')->pluck('nombre','id');
        $dosis1=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis2=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis3=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $medicamento=new Medicamento();        
        return view('medicamento.create',['medicamento'=>$medicamento])        
            ->with('laboratorios',$laboratorios)
            ->with('vias',$vias)
            ->with('formatos',$formatos)
            ->with('clases',$clases)
            ->with('dosis1',$dosis1)
            ->with('dosis2',$dosis2)
            ->with('dosis3',$dosis3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try {
            DB::beginTransaction();
            $medicamento=new Medicamento($request->all());
            $medicamento->nombre_comercial=$request->nombre_comercial;
            $medicamento->nombre_generico=$request->nombre_generico;
            $medicamento->composicion=$request->composicion;
            $medicamento->indicacion=$request->indicacion;
            $medicamento->contraindicacion=$request->contraindicacion;
            $medicamento->stock=0;
            $medicamento->stock_minimo=$request->stock_minimo;

            $medicamento->formato_id=$request->formatos;
            $medicamento->laboratorio_id=$request->laboratorios;
            $medicamento->via_id=$request->vias;
            $medicamento->save();
            
            $clases=$request->clases;

            $dosis1=$request->dosis1;
            $dosis2=$request->dosis2;
            $dosis3=$request->dosis3;
            
            for ($i=1; $i <=3 ; $i++) {    
                $daux=${"dosis".$i};   
                if (!is_null($daux)) {
                    $medidamedicamento = new MedidaMedicamento();
                    $medidamedicamento->medicamento_id = $medicamento->id;
                    $medidamedicamento->medida_id = $i;
                    $medidamedicamento->descripcion = $daux;
                    $medidamedicamento->save();
                }
            }
            
            for ($i=0; $i < count($clases); $i++) { 
                $clasemedicamento = new ClaseMedicamento();
                $clasemedicamento->medicamento_id = $medicamento->id;
                $clasemedicamento->clase_id = $clases[$i];
                $clasemedicamento->save();
            }
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
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
