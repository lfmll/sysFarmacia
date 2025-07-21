<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
Use Alert;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        try {
            DB::beginTransaction();
            $hasFile=$request->hasFile('cover') && $request->cover->isValid();
            $empresa= new Empresa($request->all());
            $empresa->nombre = $request->nombre;
            $empresa->nit = $request->nit;
            $empresa->correo = $request->correo;
            if ($hasFile) {
                $extension=$request->cover->extension();
                $empresa->extension=$extension;
            }
            $empresa->sistema = $request->sistema;
            $empresa->codigo_sistema = $request->codigo_sistema;
            $empresa->version = $request->version;
            $empresa->modalidad = $request->modalidad;
            $empresa->estado = 'A';
            $empresa->save();
            $path = public_path('/imagen');
            if ($hasFile) {
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $request->cover->move('imagen',"$empresa->id.$extension");
            }                
            $sucursal = new Agencia;
            $sucursal->codigo = 0;
            $sucursal->nombre = "Casa Matriz";
            $sucursal->departamento = $request->departamento;
            $sucursal->municipio = $request->municipio;
            $sucursal->direccion = $request->direccion;
            $sucursal->telefono = $request->telefono;
            $sucursal->estado = 'A';
            $sucursal->empresa_id = $empresa->id;
            $sucursal->save();
            
            $idUsuario = Auth::id();
            
            $puntoventa = new PuntoVenta;
            $puntoventa->codigo = 0;
            $puntoventa->nombre = "Por defecto";            
            $puntoventa->agencia_id = $sucursal->id;
            $puntoventa->user_id = $idUsuario;
            $puntoventa->estado = "A";
            $puntoventa->save();                

            $ajuste = new Ajuste;
            $ajuste->username = $request->correo;
            $ajuste->punto_venta_id = $puntoventa->id;
            $ajuste->token = $request->token;
            $ajuste->wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2";
            $ajuste->save();

            DB::commit();
                            
        } catch (\Exception $e) {            
            DB::rollBack();
        }   

        if ($ajuste->save() && $puntoventa->save() && $sucursal->save() && $empresa->save()) {
            return redirect('/')->with('toast_success','Datos de Empresa registrados');
        } else {
            return view('empresa.create')->with('toast_error',"Error al registrar");
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
        $empresa = Empresa::first();
        return view('empresa.edit',['empresa'=>$empresa]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $empresa = Empresa::first();
        $hasFile=$request->hasFile('cover') && $request->cover->isValid();
        $empresa->nombre = $request->nombre;
        $empresa->nit = $request->nit;
        $empresa->correo = $request->correo;
        if ($hasFile) {
            $extension=$request->cover->extension();
            $empresa->extension=$extension;
        }
        $empresa->sistema = $request->sistema;
        $empresa->codigo_sistema = $request->codigo_sistema;
        $empresa->version = $request->version;
        if ($hasFile) {
            $request->cover->move('imagen',"$empresa->id.$extension");
        }        
        Alert::warning('Actualizar Datos Empresa', 'Desea guardar los cambios?');

        if ($empresa->save()) {
            return view('empresa.edit',['empresa'=>$empresa])->with('toast_success','Datos de Empresa actualizado');
        } else {
            return view('empresa.edit',['empresa'=>$empresa])->with('toast_error',"Error al registrar");        
        }
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
