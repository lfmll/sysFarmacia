{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="card-body">
    <div class="form-group">
        {!! Form::select('catalogos', $catalogos, $producto->catalogo_id, ['class'=>'form-control','placeholder'=>'Catalogos','required']) !!}
    </div>
    <div class="form-group">
        {{Form::text('codigo',$producto->codigo,['class'=>'form-control', 'placeholder'=>'Codigo Producto','required'])}}
    </div>
    <div class="form-group">
        {{Form::text('descripcion',$producto->descripcion,['class'=>'form-control', 'placeholder'=>'Descripción'])}}
    </div>
    <div class="form-group">
        {{Form::number('precio_unitario',$producto->precio_unitario,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Compra','step'=>'any'])}}    
    </div>
    <div class="form-group">        
        {!! Form::select('formatos', $formatos, $producto->unidad, ['class'=>'form-control','placeholder'=>'Unidades','required']) !!}                
    </div> 
    <div class="form-group">
        {{Form::label('imagen','Imagen')}}
    </div>
    <div class="form-group mb-3">        
        {{Form::file('cover')}}
    </div>   
</div>
<div class="card-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/producto')}}">Cancelar</a>
    </div>
    <div class="float-right">
        @yield('aceptar')
        <input type="submit" value="Guardar" class="btn btn-success">
    </div>                    
</div> 
{!! Form::close() !!}