<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos=Producto::where('estado','A')->get();
        return view('producto.index',['productos' => $productos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $producto=new Producto();
        return view('producto.create',['producto'=>$producto]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producto=new producto($request->all());
        $producto->nombre=$request->nombre;
        $producto->stock=0;
        $producto->stock_minimo=$request->stock_minimo;
        $producto->fecha_vencimiento=$request->fecha_vencimiento;
        $producto->precio_compra=$request->precio_compra;
        $producto->precio_venta=$request->precio_venta;
        $producto->ganancia=0;
        $producto->estado='A';                                          
        if ($producto->save()) {
            return redirect('/producto');
        } else {
            return view('producto.create',['producto'=>$producto]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        return view('producto.edit',['producto'=>$producto]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $producto= Producto::find($id);
        $producto->nombre=$request->nombre;
        $producto->stock=0;
        $producto->stock_minimo=$request->stock_minimo;
        $producto->fecha_vencimiento=$request->fecha_vencimiento;
        $producto->precio_compra=$request->precio_compra;
        $producto->precio_venta=$request->precio_venta;
        $producto->ganancia=0;
        $producto->estado='A';                                          
        if ($producto->save()) {
            return redirect('/producto');
        } else {
            return view('producto.edit',['producto'=>$producto]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
