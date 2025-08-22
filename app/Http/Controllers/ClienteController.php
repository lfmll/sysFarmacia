<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Helpers\BitacoraHelper;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
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
        $cliente = Cliente::where('estado','A')->get();    
        return view('cliente.index',['cliente'=>$cliente]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente=new Cliente();
        $tipo_documento = Parametro::join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
                                        ->where('tipo_parametros.nombre','=','TIPO DOCUMENTO IDENTIDAD')
                                        ->orderBy('parametros.codigo_clasificador','ASC')
                                        ->pluck('parametros.descripcion','parametros.codigo_clasificador');
        
        return view('cliente.create',['cliente' => $cliente])->with('tipo_documento',$tipo_documento);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $existeCliente=Cliente::where('numero_documento','=',$request->numero_documento)
                                    ->where('tipo_documento','=',$request->tipo_documento)
                                    ->get();

            if ($existeCliente->isNotEmpty()) {
                return Response::json(["mensaje"=>"El Cliente se encuentra registrado con el Documento: ".$existeCliente[0]["numero_documento"]."."],409);
            }
            
            $cliente = Cliente::create($request->all());
            return Response::json($cliente);
        }

        $cliente=Cliente::where('numero_documento','=',$request->numero_documento)
                        ->where('tipo_documento','=',$request->tipo_documento)
                        ->get();
        
        if ($cliente->isNotEmpty()) {
            return redirect('cliente')->with('toast_error','El cliente ya se encuentra registrado');
        }

        $cliente = new Cliente($request->all());
        $cliente->tipo_documento    = $request->tipo_documento;
        $cliente->numero_documento  = $request->numero_documento; 
        $cliente->complemento       = $request->complemento;
        $cliente->nombre            = $request->nombre;
        $cliente->correo            = $request->correo;
        $cliente->telefono          = $request->telefono;
        $cliente->direccion         = $request->direccion;
        $cliente->estado            = 'A';
        
        if ($cliente->save()) {
            // Registrar en Bitacora
            BitacoraHelper::registrar('Registro Cliente', 'Cliente creado por el usuario: ' . Auth::user()->name, 'Cliente');
            return redirect('/cliente')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('cliente.create',['cliente'=>$cliente])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('cliente.show',['cliente'=>$cliente]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Cliente::find($id);
        $tipo_documento = Parametro::join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
                                        ->where('tipo_parametros.nombre','=','TIPO DOCUMENTO IDENTIDAD')
                                        ->orderBy('parametros.codigo_clasificador','ASC')
                                        ->pluck('parametros.descripcion','parametros.codigo_clasificador');
        return view('cliente.edit',['cliente'=>$cliente])->with('tipo_documento',$tipo_documento);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        $cliente->tipo_documento    = $request->tipo_documento;
        $cliente->numero_documento  = $request->numero_documento; 
        $cliente->complemento       = $request->complemento;
        $cliente->nombre            = $request->nombre;
        $cliente->correo            = $request->correo;
        $cliente->telefono          = $request->telefono;
        $cliente->direccion         = $request->direccion;       
        $cliente->estado            = 'A';

        if ($cliente->save()) {
            // Registrar en Bitacora
            BitacoraHelper::registrar('Actualización Cliente', 'Cliente modificado por el usuario: ' . Auth::user()->name, 'Cliente');
            return redirect('/cliente')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('cliente.edit',['cliente'=>$cliente])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        $cliente = Cliente::find($id);
        $cliente->estado = 'E';

        if ($cliente->save()) {
            // Registrar en Bitacora
            BitacoraHelper::registrar('Eliminación Cliente', 'Cliente eliminado por el usuario: ' . Auth::user()->name, 'Cliente');
            return redirect('/cliente')->with('toast_success','Cliente eliminado exitosamente');
        } else {
            return redirect('/cliente')->with('toast_error','Error al eliminar el Cliente');
        }
    }
}
