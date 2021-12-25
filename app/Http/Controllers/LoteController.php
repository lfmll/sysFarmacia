<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Laboratorio;
use App\Models\Insumo;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lotes=Lote::all();
        return view('lote.index',['lotes' => $lotes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lote=new Lote();
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        $insumos=Insumo::orderBy('nombre','ASC')->pluck('nombre','id');        
        $medicamentos=Medicamento::orderBy('nombre_comercial','ASC')->pluck('nombre_comercial','id');
        
        return view('lote.create',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
                ->with('insumos',$insumos)
                ->with('medicamentos',$medicamentos);
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
            $lote=new Lote($request->all());
            $lote->numero=$request->numero;
            $lote->cantidad=$request->cantidad;
            $lote->fecha_vencimiento=$request->fecha_vencimiento;
            $lote->precio_compra=$request->precio_compra;
            $lote->precio_venta=$request->precio_venta;
            $lote->ganancia=0;
            $lote->estado='A';
            $lote->laboratorio_id=$request->laboratorios;
            $lote->medicamento_id=$request->medicamentos;
            $lote->insumo_id=$request->insumos;                                        
            $lote->save();            
                        
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        if ($lote->save()) {
            return redirect('/lote');
        } else {
            return view('lote.create',['lote'=>$lote]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function show(Lote $lote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lote=Lote::find($id);
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        $insumos=Insumo::orderBy('nombre','ASC')->pluck('nombre','id');        
        $medicamentos=Medicamento::orderBy('nombre_comercial','ASC')->pluck('nombre_comercial','id');
                
        return view('lote.edit',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
                ->with('insumos',$insumos)
                ->with('medicamentos',$medicamentos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $lote= Lote::find($id);
            $lote->numero=$request->numero;
            $lote->cantidad=$request->cantidad;
            $lote->fecha_vencimiento=$request->fecha_vencimiento;
            $lote->precio_compra=$request->precio_compra;
            $lote->precio_venta=$request->precio_venta;
            $lote->ganancia=0;
            $lote->estado='A';
            $lote->laboratorio_id=$request->laboratorios;
            $lote->medicamento_id=$request->medicamentos;
            $lote->insumo_id=$request->insumos;     
                               
            $lote->save();            
                        
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        if ($lote->save()) {
            return redirect('/lote');
        } else {
            return view('lote.edit',['lote'=>$lote]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lote $lote)
    {
        //
    }
}
