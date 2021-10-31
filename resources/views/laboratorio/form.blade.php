{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="form-group">
    {{Form::text('nombre',$laboratorio->nombre,['class'=>'form-control', 'placeholder'=>'Nombre Laboratorio','required'])}}
</div>
<div class="form-group">
    {{Form::text('direccion',$laboratorio->direccion,['class'=>'form-control', 'placeholder'=>'Dirección'])}}    
</div>
<div class="form-group">
    {{Form::number('telefono',$laboratorio->telefono,['class'=>'form-control', 'placeholder'=>'Teléfono'])}}
</div>
<div class="form-group">
    {{Form::select('procedencia',['Bolivia'=>'Bolivia','Argentina'=>'Argentina','Chile'=>'Chile','Brasil'=>'Brasil','Perú'=>'Perú','India'=>'India','Estados Unidos'=>'Estados Unidos','Otros'=>'Otros'], null,['class'=>'form-control'])}}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/laboratorio')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}