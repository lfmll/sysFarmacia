<?php

namespace App\Http\Controllers;

use App\Models\NotaAjuste;
use Illuminate\Http\Request;

class NotaAjusteController extends Controller
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
        $nota = NotaAjuste::all();
        return view('notaAjuste.index', compact('nota'));
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
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function show(NotaAjuste $notaAjuste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function edit(NotaAjuste $notaAjuste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotaAjuste $notaAjuste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotaAjuste $notaAjuste)
    {
        //
    }
}
