{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="card-body">
    <div class="input-group mb-3">
        {{Form::text('nombre_empresa',$empresa->nombre_empresa,['class'=>'form-control', 'placeholder'=>'Nombre de Farmacia','required'])}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-first-aid"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        {{Form::text('direccion',$empresa->direccion,['class'=>'form-control', 'placeholder'=>'Dirección'])}}    
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-map-marker-alt"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        {{Form::number('telefono',$empresa->telefono,['class'=>'form-control', 'placeholder'=>'Teléfono', 'min'=>'0'])}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-phone-alt"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        {{Form::text('nit',$empresa->nit,['class'=>'form-control','placeholder'=>'NIT'])}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-id-card"></span>
            </div>            
        </div>
    </div>
    <div class="input-group mb-3">
        {{Form::file('cover')}}
    </div>
</div>
<div class="row">
    <div class="col-8">
        
    </div>
    <div class="col-4">
        <button type=submit class="btn-flat btn-primary">Aceptar
            <span class="fas fa-sign-in-alt"></span>
        </button>
    </div>
</div>

{!! Form::close() !!}