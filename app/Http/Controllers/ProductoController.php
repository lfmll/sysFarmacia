<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Catalogo;
use App\Models\Formato;
use Illuminate\Support\Facades\DB;

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
        $catalogos=Catalogo::orderBy('nombre','ASC')->pluck('nombre','id');
        $formatos=Formato::orderBy('descripcion','ASC')->pluck('descripcion','descripcion');

        return view('producto.create',['producto'=>$producto])
            ->with('catalogos',$catalogos)
            ->with('formatos',$formatos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hasFile=$request->hasFile('cover') && $request->cover->isValid();
        $producto=new producto($request->all());
        $producto->codigo=$request->codigo;
        $producto->descripcion=$request->descripcion;
        $producto->precio_unitario=$request->precio_unitario;        
        $producto->estado='A';
        $producto->unidad=$request->formatos;
        $producto->catalogo_id=$request->catalogos;
        if ($hasFile) {
            $extension=$request->cover->extension();
            $producto->extension=$extension;
        }                            
        if ($producto->save()) {
            if ($hasFile) {
                $request->cover->move('imagenProducto',"$producto->id.$extension");
            }
            return redirect('/producto')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('producto.create',['producto'=>$producto])->with('toast_error','Error al registrar');
        }
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto =  Producto::findOrFail($id);
        return view('producto.show',['producto'=>$producto]);
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
        $catalogos=Catalogo::orderBy('nombre','ASC')->pluck('nombre','id');
        $formatos=Formato::orderBy('descripcion','ASC')->pluck('descripcion','descripcion');

        return view('producto.edit',['producto'=>$producto])
                ->with('catalogos',$catalogos)
                ->with('formatos',$formatos);
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
        
        $hasFile=$request->hasFile('cover') && $request->cover->isValid();
        $producto= Producto::find($id);
        $producto->codigo=$request->codigo;
        $producto->descripcion=$request->descripcion;
        $producto->precio_unitario=$request->precio_unitario;        
        $producto->estado='A';
        $producto->unidad=$request->formatos;
        $producto->catalogo_id=$request->catalogos;
        if ($hasFile) {
            $extension=$request->cover->extension();
            $producto->extension=$extension;
        }                            
        if ($producto->save()) {
            if ($hasFile) {
                $request->cover->move('imagenProducto',"$producto->id.$extension");
            }
            return redirect('/producto')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('producto.edit',['producto'=>$producto])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        $producto->estado = 'E';
        if ($producto->save()) {
            return redirect('/producto');
        }
    }
}
