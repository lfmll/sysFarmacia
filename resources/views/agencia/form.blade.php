{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!} 
<div class="card-body">    
    <div class="form-group">
        {{Form::label('nro', 'Nombre Sucursal'); }}
        {{Form::text('nombre',$agencia->nombre,['class'=>'form-control', 'placeholder'=>'Nombre de Sucursal','required'])}}
    </div> 
    <div class="form-group">
            {{Form::label('direccion','Direccion')}}
            {{Form::text('direccion',$agencia->direccion,['class'=>'form-control', 'placeholder'=>'Direccion','required'])}}
    </div> 
    <div class="form-group">
            {{Form::label('telefono', 'Telefono'); }}
            {{Form::number('telefono',$agencia->telefono,['class'=>'form-control','placeholder'=>0, 'min'=>'0'])}}
    </div>
    <div class="form-group">        
            {{ Form::label('ciudad', 'Ciudad'); }}
            {!! Form::select('ciudad', ['Santa Cruz'=>'Santa Cruz',
                                        'La Paz'=>'La Paz',
                                        'Cochabamba'=>'Cochabamba',
                                        'El Alto'=>'El Alto',
                                        'Sucre'=>'Sucre',
                                        'Tarija'=>'Tarija',
                                        'Oruro'=>'Oruro',
                                        'Potosi'=>'Potosi',
                                        'Trinidad'=>'Trinidad',
                                        'Cobija'=>'Cobija'], 
                                        $agencia->ciudad,                                            
            ['class'=>'form-control','placeholder'=>'Ciudad','required']) !!}
    </div>
    <div class="form-group">
        {{Form::label('municipio', 'Municipio'); }}
        {{Form::text('municipio',$agencia->municipio,['class'=>'form-control', 'placeholder'=>'Municipio','required'])}}
    </div>       
</div>    
<div class="card-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/cliente')}}">Cancelar</a>    
    </div>   
    <div class="float-right">
    @yield('aceptar')
        <input type="submit" value="Guardar" class="btn btn-info">
    </div>
</div>
{!! Form::close() !!}