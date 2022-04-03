<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
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
        $clase=Clase::all();
        return view('clase.index',['clase'=>$clase]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clase=new Clase();
        return view('clase.create',['clase' => $clase]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clase = new Clase($request->all());
        $clase->nombre = $request->nombre;
        $clase->clase = $request->clase;        
        
        if ($clase->save()) {
            return redirect('/clase');
        } else {
            return view('clase.create',['clase'=>$clase]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function show(Clase $clase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clase = Clase::find($id);
        return view('clase.edit',['clase'=>$clase]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $clase = Clase::find($id);
        $clase->nombre = $request->nombre;
        $clase->clase = $request->clase;

        if ($clase -> save()) {
            return redirect('/clase');
        } else {
            return view('clase.edit',['clase'=>$clase]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clase $clase)
    {
        //
    }
}
