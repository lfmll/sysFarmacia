<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Laboratorio;
use App\Models\Insumo;
use App\Models\Medicamento;
use App\Models\Producto;
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
        $lotes=Lote::where('estado','A')->get();
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
        $productos=Producto::orderBy('nombre','ASC')->pluck('nombre','id');
        
        return view('lote.create',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
                ->with('insumos',$insumos)
                ->with('medicamentos',$medicamentos)
                ->with('productos',$productos);
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
            $lote->cantidad=0;
            $lote->fecha_vencimiento=$request->fecha_vencimiento;
            $lote->precio_compra=$request->precio_compra;
            $lote->ganancia=$request->ganancia;
            $lote->precio_venta=$request->precio_venta;                      
            $lote->estado='A';
            $lote->laboratorio_id=$request->laboratorios;
            $lote->medicamento_id=$request->medicamentos;
            $lote->insumo_id=$request->insumos;
            $lote->producto_id=$request->productos;                                   
            $lote->save();            
            
            /* if (!is_null($request->medicamentos)) {
                $medicamento=Medicamento::find($lote->medicamento_id);
                $medicamento->stock = $medicamento->stock + $lote->cantidad;
                $medicamento->save();    
            }
            if (!is_null($request->insumos)) {
                $insumo=Insumo::find($lote->insumo_id);
                $insumo->stock = $insumo->stock + $lote->cantidad;
                $insumo->save();
            }
            if (!is_null($request->productos)) {
                $producto=Producto::find($lote->producto_id);
                $producto->stock = $producto->stock + $lote->cantidad;
                $producto->save();
            } */
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
     * @param Medicamento $medicamento_id
     */
    public function create_medicamento($medicamento_id)
    {
        $lote=new Lote();
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        $medicamentos=Medicamento::orderBy('nombre_comercial','ASC')->pluck('nombre_comercial','id');

        return view('lote.create_medicamento',['lote'=>$lote])
                ->with('medicamento_id',$medicamento_id)
                ->with('laboratorios',$laboratorios)
                ->with('medicamentos',$medicamentos);                
    }
    /**
     * @param Insumo $insumo_id
     */
    public function create_insumo($insumo_id)
    {
        $lote=new Lote();
        $laboratorios=Laboratorio::orderBy('nombre','ASC')->pluck('nombre','id');
        $insumos=Insumo::orderBy('nombre','ASC')->pluck('nombre','id');
        return view('lote.create_insumo',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
                ->with('insumo_id',$insumo_id)
                ->with('insumos',$insumos);
    }
    /**
     * @param Producto $producto_id
     */
    public function create_producto($producto_id)
    {
        $lote=new Lote();
        $productos=Producto::orderBy('nombre','ASC')->pluck('nombre','id');
        return view('lote.create_producto',['lote'=>$lote])
                ->with('producto_id',$producto_id)
                ->with('productos',$productos);
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
        $productos=Producto::orderBy('nombre','ASC')->pluck('nombre','id');

        return view('lote.edit',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
                ->with('insumos',$insumos)
                ->with('medicamentos',$medicamentos)
                ->with('productos',$productos);
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
            $lote_cantidad_anterior=$lote->cantidad;
            $lote->cantidad=$request->cantidad;
            $lote->fecha_vencimiento=$request->fecha_vencimiento;
            $lote->precio_compra=$request->precio_compra;
            $lote->ganancia=$request->ganancia;
            $lote->precio_venta=$request->precio_venta;
                        
            $lote->estado='A';
            $lote->laboratorio_id=$request->laboratorios;
            $lote->medicamento_id=$request->medicamentos;            
            $lote->insumo_id=$request->insumos;
            $lote->producto_id=$request->productos;
                               
            $lote->save();  
                 
            if (!is_null($request->medicamentos)) {
                $medicamento=Medicamento::find($lote->medicamento_id);
                $medicamento->stock = ($medicamento->stock - $lote_cantidad_anterior) + $lote->cantidad;                
                $medicamento->save();    
            }
            if (!is_null($request->insumos)) {
                $insumo=Insumo::find($lote->insumo_id);
                $insumo->stock = ($insumo->stock - $lote_cantidad_anterior) + $lote->cantidad;
                $insumo->save();
            }
            if (!is_null($request->productos)) {
                $producto=Producto::find($lote->producto_id);
                $producto->stock = $producto->stock + $lote->cantidad;
                $producto->save();
            }                
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
    /**
     * @param  \Illuminate\Http\Request  $request 
     */
    public function buscarProducto(Request $request)
    {
        if ($request->ajax()) {
            $data=DB::table('lote')->where('id',$request->search)->get();
        }
        return response()->json($data);
    }
}
