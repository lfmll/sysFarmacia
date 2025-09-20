@extends('adminlte::page')

@section('title', 'Configuracion')

@section('content')
@include('sweetalert::alert')
<div class="row">
    <div class="col-8">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-codigo-tab" data-bs-toggle="tab" data-bs-target="#nav-codigo" type="button" role="tab" aria-controls="nav-codigo" aria-selected="true">Codigo</button>
                <button class="nav-link" id="nav-parametro-tab" data-bs-toggle="tab" data-bs-target="#nav-parametro" type="button" role="tab" aria-controls="nav-parametro" aria-selected="false">Parametros</button>
                <button class="nav-link" id="nav-sector-tab" data-bs-toggle="tab" data-bs-target="#nav-sector" type="button" role="tab" aria-controls="nav-sector" aria-selected="false">Sectores</button>
                <button class="nav-link" id="nav-otro-tab" data-bs-toggle="tab" data-bs-target="#nav-otro" type="button" role="tab" aria-controls="nav-otro" aria-selected="false">Correo</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-codigo" role="tabpanel" aria-labelledby="nav-codigo-tab">
                <div class="card">
                    <div class="card-header">
                        <h5>Token</h5>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['url' => '/ajuste/'.$ajuste->id, 'method' => 'PATCH', 'id'=>'FAjuste']) !!} 
                            <div class="col-sm-12">
                                <textarea name="token" id="token" class="form-control" required>{{$ajuste->token}}</textarea>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="submit" value="Nuevo Token" class="btn btn-primary form-control">
                                    </div>
                                </div>
                            </div> 
                        {!! Form::close() !!}                                               
                    </div>                                     
                </div>
                <div id="spinner" style="display: none;" class="text-center mt-3">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p>Cargando, por favor espera...</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Codigos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <span style="font-size: 10px;"><b>CUIS (Código Único de Inicio de Sistema)</b></span>
                                @if (!is_null($cuis))
                                    {{Form::text('cuis',$cuis->codigo_cuis,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <span style="font-size: 10px;"><b>CUFD (Código Único de Facturación Diario)</b></span>
                                @if (!is_null($cufd))
                                    {{Form::text('cufd',$cufd->codigo_cufd,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                @endif
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <span style="font-size: 10px;"><b>Fecha Expiración</b></span>
                                @if (!is_null($cuis))
                                    {{Form::text('fecha_cuis',$cuis->fecha_vigencia,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <span style="font-size: 10px;"><b>Fecha Expiración</b></span>
                                @if (!is_null($cufd))
                                    {{Form::text('fecha_cufd',$cufd->fecha_vigencia,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <a href="{{url('sincronizarCuis')}}"><button type="button" class="btn btn-primary form-control"> Obtener CUIS</button></a>
                                </div>
                            </div>
                            <div class="col-sm-6">                                        
                                <div class="form-group">
                                    <a href="{{url('sincronizarCufd')}}"><button type="button" class="btn btn-primary form-control"> Obtener CUFD</button></a>                                    
                                </div>
                            </div>
                        </div>
                    </div>                                                                                                       
                </div>            
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Fecha</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <span style="font-size: 10px;"><b>Servidor</b></span>
                                @if (!is_null($fechaSincronizada))
                                    {{Form::text('fechaSincronizada',$fechaSincronizada,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}  
                                @else
                                    <p>Aqui Fecha</p>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <span style="font-size: 10px;"><b>Local</b></span>
                                @if (!is_null($fechaSincronizada))
                                    {{Form::text('fecha_local',$fecha_local,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                @else
                                    <p>Aqui Fecha</p>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <span style="font-size: 10px;"><b>Diferencia Horaria (seg)</b></span>
                                @if (!is_null($fechaSincronizada))
                                    {{Form::text('diferencia_horaria',$diferencia_horaria,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                @else
                                    <p>Aqui Hora</p>
                                @endif                                
                            </div>
                            <span style="font-size: 10px;">La diferencia entre la fecha y hora local y la del servidor no debe exceder los 5 minutos</span>
                        </div>                                
                    </div>
                    <div class="card-footer">    
                        <div class="float-right">
                            <a href="{{url('/sincronizar')}}" id="aSincronizar" class="btn btn-primary btn-fab"><i class="material-icons fas fa-sync-alt"></i></a>
                        </div>                                                                
                    </div>                            
                </div>                    
            </div>
            <div class="tab-pane fade" id="nav-parametro" role="tabpanel" aria-labelledby="nav-parametro-tab">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Grupo</h5>
                            </div>
                            <div class="card-body">                        
                                <div class="col-sm-12">                                    
                                    <table style="width:100%; font-size:50%;" class="table table-bordered">
                                        <tbody>
                                            @if (!is_null($tipo_parametro))
                                                <div class="btn-group-vertical">                                                
                                                    @foreach($tipo_parametro as $tp)                                                    
                                                        <button type="button" onclick="tipoParametro('{{$tp->id}}','{{$parametros}}')" class="btn btn-success" style="border: 1px solid white; font-size:70%;">{{$tp->nombre}}</button>                                                    
                                                    @endforeach                                                                                                                                        
                                                </div>
                                            @endif                                    
                                        </tbody>                                        
                                    </table>
                                </div>                                                
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Parametricas</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-sm-12">
                                @if (!is_null($parametros))
                                    <div class="table-wrapper">
                                        <table id="tparametro" style="width:100%;" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Descripcion</th>                                                
                                                </tr>                                        
                                            </thead>
                                            <tbody id="tbodyParametro">
                                                <tr><td colspan="2">Seleccione Un parametro</td></tr>
                                            </tbody>
                                        </table>
                                    </div>                                    
                                @endif
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>                                                                   
            </div>
            <div class="tab-pane fade" id="nav-sector" role="tabpanel" aria-labelledby="nav-sector-tab">                
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actividades</h4>
                    </div>
                    <div class="card-body">
                        @if (!is_null($actividades))
                        <table class="table table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Codigo Caeb</th>
                                    <th>Descripcion</th>
                                </tr>                                        
                            </thead>
                            <tbody>
                            @foreach ($actividades as $actividad)
                                <tr>
                                    <td>{{$actividad->codigo_caeb}}</td>
                                    <td>{{$actividad->descripcion}}</td>
                                </tr>                                                                
                            @endforeach                                     
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>                                                            
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sectores</h4>
                    </div>
                    <div class="card-body">
                        @if (!is_null($actividad_documentos))                              
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Codigo Actividad</th>
                                    <th>Codigo Documento Sector</th>
                                    <th>Tipo Documento Sector</th>
                                </tr>                                        
                            </thead>
                            <tbody>
                            @foreach ($actividad_documentos as $adoc)
                                <tr>
                                    <td>{{$adoc->codigo_actividad}}</td>
                                    <td>{{$adoc->codigo_documento_sector}}</td>
                                    <td>{{$adoc->tipo_documento_sector}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif                                                        
                    </div>                        
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Catalogo</h4>
                    </div>
                    <div class="card-body">                        
                        @if (!is_null($catalogos))                              
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Codigo Actividad</th>
                                    <th>Codigo Producto/Servicio</th>
                                    <th>Descripcion</th>
                                </tr>                                        
                            </thead>
                            <tbody>
                            @foreach ($catalogos as $cat)
                                <tr>
                                    <td>{{$cat->codigo_actividad}}</td>
                                    <td>{{$cat->codigo_producto}}</td>
                                    <td>{{$cat->descripcion_producto}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif                        
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Leyendas</h4>
                    </div>
                    <div class="card-body">
                    @if (!is_null($leyendas))
                        <table class="table table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                </tr>                                        
                            </thead>
                            <tbody>
                            @foreach ($leyendas as $leyenda)
                                <tr><td>{{$leyenda->descripcion_leyenda}}</td></tr>
                            @endforeach                                 
                            </tbody>
                        </table>
                    @endif
                    </div>
                </div>                    
            </div>
            <div class="tab-pane fade" id="nav-otro" role="tabpanel" aria-labelledby="nav-otro-tab">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Notificacion</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => '/ajuste/'.$ajuste->id, 'method' => 'PATCH']) !!} 
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" onclick="esGmail()" checked>
                                    <label class="form-check-label" for="inlineRadio1">Gmail</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" onclick="esOtro()">
                                    <label class="form-check-label" for="inlineRadio2">Otros Servicios</label>
                                </div>
                                <hr>
                                <h5>Gmail</h5>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>Correo Gmail</b></span>
                                        {{Form::text('fromgmail',$ajuste->username,['id'=>'fromgmail', 'class'=>'form-control','style'=>'font-size: 10px;'])}}
                                    </div>
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>Contraseña de Aplicación</b></span>
                                        {{Form::text('passgmail',$ajuste->passgmail,['id'=>'passgmail','class'=>'form-control','style'=>'font-size: 10px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    <span style="font-size: 10px;">*IMPORTANTE. La contraseña de Aplicación, se tiene que generar desde su cuenta Gmail, ir a las opciones: Gestionar tu cuenta de Google, Seguridad.</span>
                                    <span style="font-size: 10px;"><b>1.- Activar Verificación en dos pasos</b></span>
                                    <span style="font-size: 10px;"><b>2.- Generar y copiar la contraseña de aplicaciones.</b></span>
                                </div>
                                <br>
                                <h5>Otros Servidores</h5>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>Username</b></span>
                                        {{Form::text('username',$ajuste->username,['id'=>'username', 'class'=>'form-control','style'=>'font-size: 10px;'])}}
                                    </div>
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>Contraseña</b></span>
                                        {{Form::text('password',$ajuste->password,['id'=>'pass', 'class'=>'form-control','style'=>'font-size: 10px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>SMTP Host</b></span>
                                        {{Form::text('host',$ajuste->host,['id'=>'host', 'class'=>'form-control','style'=>'font-size: 10px;'])}}
                                    </div>
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>SMTP Port</b></span>
                                        {{Form::text('port',$ajuste->port,['id'=>'port', 'class'=>'form-control','style'=>'font-size: 10px;'])}}
                                    </div>
                                </div>                                                       
                                </div>
                                <div class="card-footer">
                                    <div class="float-right">
                                        <input type="submit" value="Guardar" class="btn btn-success">
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div> 
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Crear Copia de Respaldo</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span>Crea una copia de respaldo de los usuarios, productos, clientes e historial de facturas, de modo que si pierde sus datos puede restaurarlos</span>
                                </div>                                                            
                                <div class="form-group">
                                    <a href="{{url('crearRespaldo')}}"><button type="button" class="btn btn-primary btn-lg btn-block"><i class="fas fa-download"></i> Crear Copia de respaldo</button></a>                                    
                                </div>
                            </div>
                        </div>
                    </div>                                       
                </div>
            </div>
        </div>
    </div>
</div>
    
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>        
        .table-wrapper {
            max-height: 470px;
            overflow: auto;
        }
    </style>
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>    
    <script>
        function tipoParametro(tp, par) {            
            let arr = JSON.parse(par);
            let n=arr.length;
            $("#tbodyParametro tr").remove(); 
            var tparametro = document.getElementById ("tparametro");
            var fila="";
            for (let i = 0; i < n; i++) {
                if (arr[i].tipo_parametro_id == tp) {
                    fila += "<tr><td>"+arr[i].codigo_clasificador+"</td><td>"+arr[i].descripcion+"</td></tr>";
                }                              
            }
            $('#tparametro').append(fila);            
        }
    </script>
    <script>
        function esGmail(){
            var checkBox = document.getElementById("inlineRadio1");
            if (checkBox.checked == true) {
                limpiarCorreo();
                document.getElementById("host").readOnly = true;
                document.getElementById("port").readOnly = true;
                document.getElementById("username").readOnly = true;
                document.getElementById("pass").readOnly = true;
                document.getElementById("fromgmail").readOnly = false;
                document.getElementById("passgmail").readOnly = false; 
            }
        }

        function esOtro(){
            var checkBox = document.getElementById("inlineRadio2");
            if (checkBox.checked == true) {
                limpiarCorreo();
                document.getElementById("fromgmail").readOnly = true;
                document.getElementById("passgmail").readOnly = true; 
                document.getElementById("host").readOnly = false;
                document.getElementById("port").readOnly = false;
                document.getElementById("username").readOnly = false;
                document.getElementById("pass").readOnly = false;
            }
        }

        function limpiarCorreo(){
            document.getElementById("fromgmail").value = "";
            document.getElementById("passgmail").value = "";
            document.getElementById("host").value = "";
            document.getElementById("port").value = "";
            document.getElementById("username").value = "";
            document.getElementById("pass").value = "";                        
        }
    </script>
    <script>
        document.getElementById('aSincronizar').addEventListener('click', function () {
            document.getElementById('spinner').style.display = 'block';
        });
    </script>
@stop