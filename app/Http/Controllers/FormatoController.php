<?php

namespace App\Http\Controllers;

use App\Models\Formato;
use Illuminate\Http\Request;

class FormatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formato=Formato::all();
        return view('formato.index',['formato'=>$formato]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formato=new Formato();
        return view('formato.create',['formato' => $formato]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formato= new Formato($request->all());
        $formato->descripcion = $request->descripcion;
        
        if ($formato->save()) {
            return redirect('/formato');
        } else {
            return view('formato.create',['formato'=>$formato]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Formato  $formato
     * @return \Illuminate\Http\Response
     */
    public function show(Formato $formato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Formato  $formato
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formato = Formato::find($id);
        return view('formato.edit',['formato'=>$formato]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Formato  $formato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formato = Formato::find($id);
        $formato->descripcion = $request->descripcion;
        
        if ($formato->save()) {
            return redirect('/formato');
        } else {
            return view('formato.edit',['formato'=>$formato]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Formato  $formato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formato $formato)
    {
        //
    }
}
