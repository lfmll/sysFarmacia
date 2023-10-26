<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
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
        return view('cliente.create',['cliente' => $cliente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            return redirect('/cliente');
        } else {
            return view('cliente.create',['cliente'=>$cliente]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
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
        return view('cliente.edit',['cliente'=>$cliente]);
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
            return redirect('/cliente');
        } else {
            return view('cliente.edit',['cliente'=>$cliente]);
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
            return redirect('/cliente');
        }
    }
}
