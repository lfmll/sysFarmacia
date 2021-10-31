{!! Form::open(['url' => $url, 'method' => $method]) !!}
<div class="form-group">
    {{Form::text('descripcion',$medida->descripcion,['class'=>'form-control', 'placeholder'=>'Dosis','required'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/medida')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}