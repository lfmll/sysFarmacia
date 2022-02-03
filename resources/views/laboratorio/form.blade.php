{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="card-body">
    <div class="form-group">
        {{Form::text('nombre',$laboratorio->nombre,['class'=>'form-control', 'placeholder'=>'Nombre Laboratorio','required'])}}
    </div>
    <div class="form-group">
        {{Form::text('direccion',$laboratorio->direccion,['class'=>'form-control', 'placeholder'=>'Dirección'])}}    
    </div>
    <div class="form-group">
        {{Form::number('telefono',$laboratorio->telefono,['class'=>'form-control', 'placeholder'=>'Teléfono', 'min'=>'0'])}}
    </div>
    <div class="form-group">
        {{Form::select('procedencia',['Bolivia'=>'Bolivia','Argentina'=>'Argentina','Chile'=>'Chile','Brasil'=>'Brasil','Perú'=>'Perú','Paraguay'=>'Paraguay','Colombia'=>'Colombia','China'=>'China','India'=>'India','Estados Unidos'=>'Estados Unidos','Otros'=>'Otros'], null,['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        {{Form::text('anotacion', $laboratorio->anotacion, ['class'=>'form-control','placeholder'=>'Anotación'])}}
    </div>
</div>
<div class="card-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/laboratorio')}}">Cancelar</a>
    </div>
    <div class="float-right">
        @yield('aceptar')
        <input type="submit" value="Guardar" class="btn btn-success">
    </div>                    
</div> 
{!! Form::close() !!}