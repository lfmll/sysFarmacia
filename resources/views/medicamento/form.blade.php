{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="form-group">
    {{Form::text('nombre_comercial',$medicamento->nombre_comercial,['class'=>'form-control', 'placeholder'=>'Nombre Comercial','required'])}}
</div>
<div class="form-group">
    {{Form::text('nombre_generico',$medicamento->nombre_generico,['class'=>'form-control', 'placeholder'=>'Nombre Genérico','required'])}}
</div>
<div class="form-group">
    {{Form::textarea('composición',$medicamento->composicion,['class'=>'form-control', 'placeholder'=>'Composición...'])}}    
</div>
<div class="form-group">
    {{Form::textarea('indicación',$medicamento->indicacion,['class'=>'form-control', 'placeholder'=>'Indicación...'])}}    
</div>
<div class="form-group">
    {{Form::label('stock_mínimo','Stock Mínimo')}}
    {{Form::number('stock_minimo',$medicamento->stock_minimo,['class'=>'form-control', 'placeholder'=>'0'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/medicamento')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}