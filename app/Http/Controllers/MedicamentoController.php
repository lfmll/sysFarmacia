<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;
use App\Models\Via;
use App\Models\Formato;
use App\Models\Clase;
use App\Models\Medida;
use App\Models\ClaseMedicamento;
use App\Models\MedidaMedicamento;
use Illuminate\Support\Facades\DB;

class MedicamentoController extends Controller
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
        $vias=Via::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $formatos=Formato::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $clases=Clase::orderBy('nombre','ASC')->pluck('nombre','id');
        $dosis1=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis2=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis3=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis_estandar1=null;
        $dosis_estandar2=null;
        $dosis_estandar3=null;
        $medicamento=new Medicamento();        
        $clasemedicamento=new ClaseMedicamento();
        return view('medicamento.create',['medicamento'=>$medicamento])                    
            ->with('vias',$vias)
            ->with('formatos',$formatos)
            ->with('clases',$clases)
            ->with('dosis1',$dosis1)
            ->with('dosis2',$dosis2)
            ->with('dosis3',$dosis3)
            ->with('dosis_estandar1',$dosis_estandar1)
            ->with('dosis_estandar2',$dosis_estandar2)
            ->with('dosis_estandar3',$dosis_estandar3)       
            ->with('clasemedicamento',$clasemedicamento);
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
            $medicamento->observacion=$request->observacion;
            $medicamento->stock=0;
            $medicamento->stock_minimo=$request->stock_minimo;

            $medicamento->formato_id=$request->formatos;            
            $medicamento->via_id=$request->vias;
            $medicamento->save();
            
            $clases=$request->clases;

            $dosis1=$request->dosis1;
            $dosis2=$request->dosis2;
            $dosis3=$request->dosis3;

            $dosis_estandar1=$request->dosis_estandar1;
            $dosis_estandar2=$request->dosis_estandar2;
            $dosis_estandar3=$request->dosis_estandar3;
            
            for ($i=1; $i <=3 ; $i++) {    
                $daux=${"dosis".$i};   
                if (!is_null($daux)) {
                    $medidamedicamento = new MedidaMedicamento();
                    $medidamedicamento->medicamento_id = $medicamento->id;
                    $medidamedicamento->medida_id = $i;
                    $medidamedicamento->descripcion = $daux;
                    if (!is_null(${"dosis_estandar".$i})) {
                        $medidamedicamento->dosis_estandar=${"dosis_estandar".$i};
                    }
                    $medidamedicamento->estado = 'A';
                    $medidamedicamento->save();
                }
            }
            
            for ($i=0; $i < count($clases); $i++) { 
                $clasemedicamento = new ClaseMedicamento();
                $clasemedicamento->medicamento_id = $medicamento->id;
                $clasemedicamento->clase_id = $clases[$i];
                $clasemedicamento->estado = 'A';
                $clasemedicamento->save();
            }
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        if ($medicamento->save()) {
            return redirect('/medicamento')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('medicamento.create',['medicamento'=>$medicamento])->with('toast_error','Error al registrar');
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
        $formatos=Formato::where('id',$medicamento->formato_id)->first();        
        $vias=Via::where('id',$medicamento->via_id)->first();
        
        $clases = DB::table('clases')
                    ->join('clases_medicamentos','clases.id','=','clases_medicamentos.clase_id')
                    ->where('clases_medicamentos.medicamento_id','=',$medicamento->id) 
                    ->where('clases_medicamentos.estado','=','A') 
                    ->select('clases.nombre')
                    ->get();                                               
        
        $medidamedicamento1=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',1)
                            ->where('estado','A')                            
                            ->first();

        $medidamedicamento2=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',2)
                            ->where('estado','A')
                            ->first();                            
        
        $medidamedicamento3=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',3)
                            ->where('estado','A')
                            ->first();
                                    
        $dosis_estandar1=null;
        $dosis_estandar2=null;
        $dosis_estandar3=null;
        
        if (!is_null($medidamedicamento1)) {
            $medidamedicamento1=$medidamedicamento1->descripcion;
            $dosis_estandar1=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',1)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar1)) {
                $dosis_estandar1=$dosis_estandar1->dosis_estandar;
            }                                                                    
        }
        if (!is_null($medidamedicamento2)) {
            $medidamedicamento2=$medidamedicamento2->descripcion;
            $dosis_estandar2=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',2)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar2)) {
                $dosis_estandar2=$dosis_estandar2->dosis_estandar;
            } 
        }
        if (!is_null($medidamedicamento3)) {
            $medidamedicamento3=$medidamedicamento3->descripcion;
            $dosis_estandar3=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',3)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar3)) {
                $dosis_estandar3=$dosis_estandar3->dosis_estandar;
            } 
        }
                            
        return view('medicamento.show',['medicamento'=>$medicamento])
            ->with('vias',$vias)
            ->with('formatos',$formatos)
            ->with('clases',$clases)            
            ->with('medidamedicamento1',$medidamedicamento1)
            ->with('medidamedicamento2',$medidamedicamento2)
            ->with('medidamedicamento3',$medidamedicamento3)
            ->with('dosis_estandar1',$dosis_estandar1)
            ->with('dosis_estandar2',$dosis_estandar2)
            ->with('dosis_estandar3',$dosis_estandar3);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $medicamento = Medicamento::find($id);                 
                
        $vias=Via::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $formatos=Formato::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $clases=Clase::orderBy('nombre','ASC')->pluck('nombre','id');
        $dosis1=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis2=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        $dosis3=Medida::orderBy('descripcion','ASC')->pluck('descripcion','id');
        
        $clasemedicamento = ClaseMedicamento::where('medicamento_id',$medicamento->id)
                                            ->where('estado','A')
                                            ->get('clase_id');  

        $medidamedicamento1=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',1)
                            ->where('estado','A')                            
                            ->first();
                                            
        $medidamedicamento2=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',2)
                            ->where('estado','A')
                            ->first();                                                    
        
        $medidamedicamento3=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                            ->where('medida_id',3)
                            ->where('estado','A')
                            ->first();                                                                   
        
        $dosis_estandar1=null;
        $dosis_estandar2=null;
        $dosis_estandar3=null;                   

        if (!is_null($medidamedicamento1)) {
            $medidamedicamento1=$medidamedicamento1->descripcion;
            $dosis_estandar1=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',1)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar1)) {
                $dosis_estandar1=$dosis_estandar1->dosis_estandar;
            }                        
        }
        if (!is_null($medidamedicamento2)) {
            $medidamedicamento2=$medidamedicamento2->descripcion;
            $dosis_estandar2=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',2)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar2)) {
                $dosis_estandar2=$dosis_estandar2->dosis_estandar;
            }                        
        }
        if (!is_null($medidamedicamento3)) {
            $medidamedicamento3=$medidamedicamento3->descripcion;
            $dosis_estandar3=MedidaMedicamento::where('medicamento_id',$medicamento->id)
                                                ->where('medida_id',3)
                                                ->where('estado','A')
                                                ->where('dosis_estandar','<>',null)
                                                ->first();
            if (!is_null($dosis_estandar3)) {
                $dosis_estandar3=$dosis_estandar3->dosis_estandar;
            }                        
        } 
        return view("medicamento.edit",["medicamento"=>$medicamento])                    
            ->with('vias',$vias)
            ->with('formatos',$formatos)
            ->with('clases',$clases)
            ->with('dosis1',$dosis1)
            ->with('dosis2',$dosis2)
            ->with('dosis3',$dosis3)
            ->with('dosis_estandar1',$dosis_estandar1)
            ->with('dosis_estandar2',$dosis_estandar2)
            ->with('dosis_estandar3',$dosis_estandar3)
            ->with('clasemedicamento',$clasemedicamento)
            ->with('medidamedicamento1',$medidamedicamento1)
            ->with('medidamedicamento2',$medidamedicamento2)
            ->with('medidamedicamento3',$medidamedicamento3);
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
        try {
            DB::beginTransaction();
            $medicamento=Medicamento::find($id);
            $medicamento->nombre_comercial=$request->nombre_comercial;
            $medicamento->nombre_generico=$request->nombre_generico;
            $medicamento->composicion=$request->composicion;
            $medicamento->indicacion=$request->indicacion;
            $medicamento->contraindicacion=$request->contraindicacion;
            $medicamento->observacion=$request->observacion;
            $medicamento->stock_minimo=$request->stock_minimo;

            $medicamento->formato_id=$request->formatos;
            $medicamento->via_id=$request->vias;

            $medicamento->save();
            
            $clases=$request->clases;                                    

            $dosis1=$request->dosis1;
            $dosis2=$request->dosis2;
            $dosis3=$request->dosis3;

            $dosis_estandar1=$request->dosis_estandar1;
            $dosis_estandar2=$request->dosis_estandar2;
            $dosis_estandar3=$request->dosis_estandar3;
            
            $medidamedicamento=MedidaMedicamento::where('medicamento_id',$medicamento->id)->where('estado','A')->get();
            foreach ($medidamedicamento as $mmed) {
                $mmed->estado='N';
                $mmed->save();
            }
            for ($i=1; $i <=3 ; $i++) {    
                $daux=${"dosis".$i};   
                if (!is_null($daux)) {
                    $medidamedicamento = new MedidaMedicamento();
                    $medidamedicamento->medicamento_id = $medicamento->id;
                    $medidamedicamento->medida_id = $i;
                    $medidamedicamento->descripcion = $daux;
                    if (!is_null(${"dosis_estandar".$i})) {
                        $medidamedicamento->dosis_estandar=${"dosis_estandar".$i};
                    }
                    $medidamedicamento->estado = 'A';
                    $medidamedicamento->save();
                }
            }
            
            $clasemedicamento=ClaseMedicamento::where('medicamento_id',$medicamento->id)->where('estado','A')->get();
            foreach ($clasemedicamento as $clm) {
                $clm->estado='N';
                $clm->save();
            }
            for ($i=0; $i < count($clases); $i++) { 
                $clasemedicamento = new ClaseMedicamento();
                $clasemedicamento->medicamento_id = $medicamento->id;
                $clasemedicamento->clase_id = $clases[$i];
                $clasemedicamento->estado = 'A';
                $clasemedicamento->save();
            }                       
            
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        if ($medicamento->save()) {
            return redirect('/medicamento')->with('toast_success','Medicamento modificado exitosamente');
        } else {
            return view('medicamento.create',['medicamento'=>$medicamento])->with('toast_error','Error al registrar');
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
