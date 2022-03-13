{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="card-body">
    <div class="form-group">
        {{Form::text('nombre',$producto->nombre,['class'=>'form-control', 'placeholder'=>'Nombre Producto','required'])}}
    </div>
    <div class="form-group">
        {{Form::text('descripcion',$producto->descripcion,['class'=>'form-control', 'placeholder'=>'Descripción'])}}
    </div>
    <div class="form-group">
        {{Form::number('stock_minimo',$producto->stock_minimo,['class'=>'form-control', 'placeholder'=>'Stock Mínimo','min'=>'0'])}}    
    </div>
    
</div>
<div class="card-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/producto')}}">Cancelar</a>
    </div>
    <div class="float-right">
        @yield('aceptar')
        <input type="submit" value="Guardar" class="btn btn-success">
    </div>                    
</div> 
{!! Form::close() !!}