<div class="card-body">
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
        {{Form::number('stock_minimo',$insumo->stock_minimo,['class'=>'form-control','placeholder'=>0, 'min'=>'0'])}}
    </div>
</div>
<div class="card-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/insumo')}}">Cancelar</a>    
    </div>   
    <div class="float-right">
        <input type="submit" value="Guardar" class="btn btn-info">
    </div>
</div>
{!! Form::close() !!}