{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="card-body">
    <div class="input-group mb-3">
        {{Form::text('nombre',$empresa->nombre,['class'=>'form-control', 'placeholder'=>'Nombre de Farmacia','required'])}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-first-aid"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        {{Form::text('actividad',$empresa->actividad,['class'=>'form-control', 'placeholder'=>'Razon Social','required'])}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-first-aid"></span>
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
        {{Form::text('correo',$empresa->correo,['class'=>'form-control', 'placeholder'=>'Correo'])}}    
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        {{Form::select('documento', ['FACTURA COMPRA-VENTA'=>'FACTURA COMPRA-VENTA',
                                    'FACTURA COMERCIAL DE EXPORTACION'=>'FACTURA COMERCIAL DE EXPORTACION',
                                    'FACTURA DE SEGUROS'=>'FACTURA DE SEGUROS',
                                    'FACTURA COMPRA VENTA BONIFICACIONES'=>'FACTURA COMPRA VENTA BONIFICACIONES'],$empresa->documento,['class'=>'form-control', 'placeholder'=>'Documento de Emision', 'min'=>'0'])}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-file-alt"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
    {{Form::text('modalidad', $empresa->modalidad,['class'=>'form-control', 'placeholder'=>'COMPUTARIZADA EN LINEA', 'readonly'=>'true'])}}    
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-inbox"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
    {{Form::number('cuis', $empresa->cuis,['class'=>'form-control', 'placeholder'=>'CUIS'])}}    
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-money-check"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
    {{Form::date('vigencia_cuis', $empresa->vigencia_cuis,['class'=>'form-control', 'placeholder'=>'Vigencia CUIS'])}}    
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