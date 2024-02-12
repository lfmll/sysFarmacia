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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Codigos</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>CUIS (Código Único de Inicio Semanal)</b></span>
                                        {{Form::text('cuis',$ajuste->cuis,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                    </div>
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>Fecha Expiración</b></span>
                                        {{Form::text('fecha_cuis',$ajuste->fecha_cuis,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>CUIFD (Código Único de Facturación Diario)</b></span>
                                        {{Form::text('cuifd',$ajuste->cuifd,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                    </div>
                                    <div class="col-sm-6">
                                        <span style="font-size: 10px;"><b>Fecha Expiración</b></span>
                                        {{Form::text('fecha_cuifd',$ajuste->fecha_cuifd,['class'=>'form-control','style'=>'font-size: 10px;','readonly'])}}
                                    </div>
                                </div>                                                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Fecha</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <span style="font-size: 10px;"><b>Servidor</b></span>
                                        <p>Aqui Fecha</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <span style="font-size: 10px;"><b>Local</b></span>
                                        <p>Aqui Fecha</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <span style="font-size: 10px;"><b>Diferencia Horaria</b></span>
                                        <p>Aqui Hora</p>
                                    </div>
                                    <span style="font-size: 10px;">La diferencia entre la fecha y hora local y la del servidor no debe exceder los 5 minutos</span>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>                           
            </div>
            <div class="tab-pane fade" id="nav-parametro" role="tabpanel" aria-labelledby="nav-parametro-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Grupo</h5>
                    </div>
                    <div class="card-body">                        
                        <div class="col-sm-12">                                    
                            <table style="width:100%;" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>...</td>
                                        
                                    </tr>
                                    <tr>                                            
                                        <td>...</td>
                                    </tr>
                                    <tr>
                                        <td>...</td>
                                    </tr>
                                </tbody>
                                
                            </table>
                        </div>                                                
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Parametricas</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12">
                            <table style="width:100%;" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripcion</th>
                                        <th>Activo</th>
                                    </tr>                                        
                                </thead>
                                <tbody>
                                    <td>...</td>
                                    <td>...</td>
                                    <td>...</td>

                                </tbody>
                            </table>
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="tab-pane fade" id="nav-sector" role="tabpanel" aria-labelledby="nav-sector-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Actividades</h4>
                            </div>
                            <div class="card-body">
                                <table style="boder-collapse: collapse; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Descripcion</th>
                                            <th>Activo</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <td>...</td>
                                        <td>...</td>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Sectores</h4>
                            </div>
                            <div class="card-body">
                                <table style="boder-collapse: collapse; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Descripcion</th>
                                            <th>Activo</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <td>...</td>
                                        <td>...</td>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Catalogo</h4>
                            </div>
                            <div class="card-body">
                                <table style="boder-collapse: collapse; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Descripcion</th>
                                            <th>Activo</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <td>...</td>
                                        <td>...</td>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                                    {{Form::text('fromgmail',$ajuste->fromgamil,['id'=>'fromgmail', 'class'=>'form-control','style'=>'font-size: 10px;'])}}
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
            </div>
        </div>
    </div>
</div>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        div.card-body{
            overflow-y: auto;
            white-space: nowrap;
        }
        
    </style>
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    
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
    
@stop