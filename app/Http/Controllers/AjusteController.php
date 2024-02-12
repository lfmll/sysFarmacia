<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use Illuminate\Http\Request;

class AjusteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ajustes = Ajuste::first();
        return view('ajuste.index',['ajuste'=>$ajustes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function show(Ajuste $ajuste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function edit(Ajuste $ajuste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ajuste $ajuste)
    {
        $ajuste = Ajuste::first();
        
        if ($request->inlineRadioOptions == "option1") {    //gmail
            $ajuste->driver = "smtp";
            $ajuste->host = "smtp.gmail.com";
            $ajuste->port = "587";
            $ajuste->encryption = "tls";
            $ajuste->username = $request->fromgmail;
            $ajuste->password = $request->passgmail;
            $ajuste->from = $request->fromgmail;
        } else {
            $ajuste->driver = "smtp";
            $ajuste->host = $request->host;
            $ajuste->port = $request->port;
            $ajuste->encryption = "tls";
            $ajuste->username = $request->username;
            $ajuste->password = $request->password;
            $ajuste->from = $request->username;
        }
        
        if ($ajuste->save()) {
            return redirect('/ajuste')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('ajuste.edit',['ajuste'=>$ajuste])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ajuste $ajuste)
    {
        //
    }
}
