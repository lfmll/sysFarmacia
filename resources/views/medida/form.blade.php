<div class="box-body">
    {!! Form::open(['url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        {{Form::text('descripcion',$medida->descripcion,['class'=>'form-control', 'placeholder'=>'Dosis','required'])}}
    </div>
</div>
<div class="box-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/medida')}}">Cancelar</a>
    </div>
    <div class="float-right">
        <input type="submit" value="Guardar" class="btn btn-success">
    </div>
</div>
{!! Form::close() !!}