<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Laboratorio;
use App\Models\Medicamento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
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
        $medicamentos=Medicamento::orderBy('nombre_comercial','ASC')->pluck('nombre_comercial','id');
                
        return view('lote.create',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
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
            // $lote->cantidad=0;   //Definir en cero si solo entra con compra
            $lote->cantidad=$request->cantidad;
            $lote->fecha_vencimiento=$request->fecha_vencimiento;
            $lote->precio_compra=$request->precio_compra;
            $lote->ganancia=$request->ganancia;
            $lote->precio_venta=$request->precio_venta;                      
            $lote->estado='A';
            $lote->laboratorio_id=$request->laboratorios;
            $lote->medicamento_id=$request->medicamentos;                                 
            $lote->save();            
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        if ($lote->save()) {
            return redirect('/lote')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('lote.create',['lote'=>$lote])->with('toast_error','Error al registrar');
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
        $medicamentos=Medicamento::orderBy('nombre_comercial','ASC')->pluck('nombre_comercial','id');
        
        return view('lote.edit',['lote'=>$lote])
                ->with('laboratorios',$laboratorios)
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
            $lote_cantidad_anterior=$lote->cantidad;
            $lote->cantidad=$request->cantidad;
            $lote->fecha_vencimiento=$request->fecha_vencimiento;
            $lote->precio_compra=$request->precio_compra;
            $lote->ganancia=$request->ganancia;
            $lote->precio_venta=$request->precio_venta;
            $lote->estado='A';
            $lote->laboratorio_id=$request->laboratorios;
            $lote->medicamento_id=$request->medicamentos;            
                               
            $lote->save();  

            $medicamento=Medicamento::find($lote->medicamento_id);
            $medicamento->stock = ($medicamento->stock - $lote_cantidad_anterior) + $lote->cantidad;                
            $medicamento->save();
                            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        if ($lote->save()) {
            return redirect('/lote')->with('toast_success','Modificación realizada exitosamente');
        } else {
            return view('lote.edit',['lote'=>$lote])->with('toast_error','Error al actualizar');
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
    public function catalogarActividad(Request $request)
    {
        if ($request->ajax()) {
            $data=DB::table('catalogos')
                    ->join('codigos','catalogos.codigo_actividad','=','codigos.codigo_caeb')
                    ->where('codigos.codigo_caeb','=',$request->codCaeb)
                    ->orderBy('catalogos.descripcion_producto','ASC')
                    ->get();
                    
            if ($data->isEmpty()) {
                return response()->json(["mensaje"=>"No existe Productos con esa Actividad"],409);
            }
            return response()->json($data);    
        }
        
    }

    public function catalogarProducto(Request $request)
    {   
        if ($request->ajax()) {
            $data=DB::table('lotes')
                    ->join('medicamentos','lotes.medicamento_id','=','medicamentos.id')
                    ->join('laboratorios','lotes.laboratorio_id','=','laboratorios.id')
                    ->where('medicamentos.codigo_producto_sin',$request->codProducto)
                    ->get();
            if ($data->isEmpty()) {
                return response()->json(["mensaje"=>"No existe lotes con este catalogo"],409);
            }
        }        
        
        return response()->json($data);
    }
}
