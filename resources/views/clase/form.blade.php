{!! Form::open(['url' => $url, 'method' => $method]) !!}
<div class="form-group">
    {{Form::text('nombre',$clase->nombre,['class'=>'form-control', 'placeholder'=>'Nombre','required'])}}
</div>
<div class="form-group">
    {{Form::text('descripcion',$clase->descripcion,['class'=>'form-control', 'placeholder'=>'Descripción'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/clase')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}