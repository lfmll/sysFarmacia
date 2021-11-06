{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="form-group">
    {{Form::text('codigo',$insumo->codigo,['class'=>'form-control','placeholder'=>'Código'])}}
</div>
<div class="form-group">
    {{Form::text('nombre',$insumo->nombre,['class'=>'form-control', 'placeholder'=>'Nombre','required'])}}
</div>
<div class="form-group">
    {{Form::text('descripcion',$insumo->descripcion,['class'=>'form-control', 'placeholder'=>'Descripción'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/insumo')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}