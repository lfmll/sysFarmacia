{!! Form::open(['url' => $url, 'method' => $method]) !!}
<div class="form-group">
    {{Form::text('descripcion',$formato->descripcion,['class'=>'form-control', 'placeholder'=>'Presentación','required'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/formato')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}