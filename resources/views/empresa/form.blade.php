{!! Form::open(['url' => $url, 'method' => $method, 'files' => true, 'id' => 'regForm']) !!}
    <div class="tab">        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-11">
                <h5 class="input-group mb-12"><i class="fas fa-fw fa-hospital"></i>Registro Inicial de Farmacia</h5>
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::text('razon_social',null,['class'=>'form-control', 'placeholder'=>'Nombre/Razon Social','required'])}}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-first-aid"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::text('nit',null,['class'=>'form-control','placeholder'=>'NIT','required'])}}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card"></span>
                </div>            
            </div>
        </div>       
        <div class="input-group mb-3">
            {{Form::text('correo',null,['class'=>'form-control', 'placeholder'=>'Correo','required'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::text('telefono',null,['class'=>'form-control', 'placeholder'=>'Teléfono','required'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-4">
            {{ Form::select('departamento', [
                                                'BENI' => 'BENI',
                                                'COCHABAMBA' => 'COCHABAMBA',
                                                'CHUQUISACA' => 'CHUQUISACA',
                                                'LA PAZ' => 'LA PAZ',
                                                'ORURO' => 'ORURO',
                                                'PANDO' => 'PANDO',
                                                'POTOSÍ' => 'POTOSÍ',
                                                'SANTA CRUZ' => 'SANTA CRUZ',
                                                'TARIJA' => 'TARIJA'
                                            ], null, ['class' => 'form-control', 'placeholder' => 'DEPARTAMENTO', 'required']) }}
                                        <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-align-justify"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::text('municipio',null,['class'=>'form-control', 'placeholder'=>'Municipio','required'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-home"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::text('direccion',null,['class'=>'form-control', 'placeholder'=>'Dirección','required'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-map-marker-alt"></span>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="logo">Subir Imagen Logo</label>
            <input type="file" class="form-control" id="logo">
        </div>                               
    </div>
    <div class="tab">
        <div class="row">            
            <div class="col-12">
                <h5 class="input-group mb-12"><i class="fas fa-fw fa-laptop"></i> Sistema</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    {{Form::text('sistema', $empresa->sistema,['class'=>'form-control', 'placeholder'=>'Nombre de Sist.'])}}    
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-desktop"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{Form::text('version', $empresa->version,['class'=>'form-control', 'placeholder'=>'Version'])}}            
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::text('codigo_sistema', $empresa->codigo_sistema,['class'=>'form-control', 'placeholder'=>'Codigo Sistema'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-code"></span>
                </div>
            </div>
        </div> 
        <div class="input-group mb-3">
            {{Form::select('modalidad', ['1'=>'Electronica en Linea','2'=>'Computarizada en Linea'], '2', ['class'=>'form-control', 'readonly'=>'true'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-inbox"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            {{Form::select('ambiente', ['1'=>'Producción','2'=>'Pruebas'], '2', ['class'=>'form-control', 'required'])}}    
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-warehouse"></span>
                </div>
            </div>
        </div>
        <h5 class="input-group mb-12"><i class="fas fa-fw fa-cogs"></i> Token Delegado</h5>
        <div class="input-group mb-3">
            <textarea name="token" id="token" class="form-control" rows="6" cols="30"></textarea>            
        </div> 
    </div>
    <div class="col-md-12" style="overflow:auto;">
        <div class="btn-group" style="float:right;">
            <button type="button" class="form-control btn btn-default" id="prevBtn" onclick="nextPrev(-1)">Atras</button>
            <button type="button" class="form-control btn btn-success" id="nextBtn" onclick="nextPrev(1)">Siguiente</button>
        </div>
    </div>
        
    <!-- Circles which indicates the steps of the form: -->
    <div class="col-md-12" style="text-align:center;margin-top:40px;">
        <span class="step"></span>
        <span class="step"></span>
    </div>
    
{!! Form::close() !!}
