<div class="card-body">
    {!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!} 
    <h5>Datos Generales</h5>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">        
                {{ Form::label('tipodoc', 'Tipo de Documento'); }}
                {!! Form::select('tipo_documento', $tipo_documento, $cliente->tipo_documento, ['class'=>'form-control','placeholder'=>'Tipo de Documento','required']) !!}
            </div>
        </div>                
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {{Form::label('nrodoc', 'Nro Documento'); }}
                {{Form::text('numero_documento',$cliente->numero_documento,['class'=>'form-control', 'placeholder'=>'Numero de Documento','required'])}}
            </div>    
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{Form::label('complemento', 'Complemento'); }}
                {{Form::text('complemento',$cliente->complemento,['class'=>'form-control', 'placeholder'=>'Numero de Documento'])}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {{Form::label('nombre', 'Nombre o Razon Social'); }}
                {{Form::text('nombre',$cliente->nombre,['class'=>'form-control', 'placeholder'=>'Nombre','required'])}}
            </div>
        </div>        
    </div>
    <h5>Datos de Contacto</h5>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {{Form::label('correo', 'Correo Electronico'); }}
                {{Form::text('correo',$cliente->correo,['class'=>'form-control', 'placeholder'=>'Correo','required'])}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{Form::label('telefono', 'Telefono'); }}
                {{Form::number('telefono',$cliente->telefono,['class'=>'form-control','placeholder'=>0, 'min'=>'0'])}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {{Form::label('direccion','Direccion')}}
                {{Form::text('direccion',$cliente->direccion,['class'=>'form-control', 'placeholder'=>'Direccion'])}}
            </div> 
        </div>
    </div>
                       
    
</div>
<div class="card-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/cliente')}}">Cancelar</a>    
    </div>   
    <div class="float-right">
        <input type="submit" value="Guardar" class="btn btn-info">
    </div>
</div>
{!! Form::close() !!}