<?php

namespace App\Http\Controllers;

use App\Models\Via;
use Illuminate\Http\Request;

class ViaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $via=Via::all();
        return view('via.index',['via'=>$via]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $via=new Via();
        return view('via.create',['via' => $via]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $via= new Via($request->all());
        $via->descripcion = $request->descripcion;
        
        if ($via->save()) {
            return redirect('/via');
        } else {
            return view('via.create',['via'=>$via]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Via  $via
     * @return \Illuminate\Http\Response
     */
    public function show(Via $via)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Via  $via
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $via = Via::find($id);
        return view('via.edit',['via'=>$via]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Via  $via
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $via = Via::find($id);
        $via->descripcion = $request->descripcion;
        
        if ($via->save()) {
            return redirect('/via');
        } else {
            return view('via.edit',['via'=>$via]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Via  $via
     * @return \Illuminate\Http\Response
     */
    public function destroy(Via $via)
    {
        //
    }
}
