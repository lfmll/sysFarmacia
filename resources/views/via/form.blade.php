<div class="box-body">
    {!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
    <div class="form-group">
        {{Form::text('descripcion',$via->descripcion,['class'=>'form-control', 'placeholder'=>'Via de Administración','required'])}}
    </div>
</div>
<div class="box-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/via')}}">Cancelar</a>
    </div>
    <div class="float-right">
        <input type="submit" value="Guardar" class="btn btn-success">    
    </div>
</div>
{!! Form::close() !!}