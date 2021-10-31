{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="form-group">
    {{Form::text('descripcion',$via->descripcion,['class'=>'form-control', 'placeholder'=>'Via de Administración','required'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/laboratorio')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}