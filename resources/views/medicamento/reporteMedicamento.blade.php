<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>        
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }
        .main {
            font-size: 12pt;
        }
    </style>
    <title>Reporte de Medicamento</title>
</head>
<body>
    <div class="container">
        <table width="100%">
            <thead>
                <tr>
                    <th align="left">Nombre Comercial</th>
                    <th align="left">Nombre Genérico</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$medicamento->nombre_comercial}}</td>
                    <td>{{$medicamento->nombre_generico}}</td>
                </tr>
            </tbody>
        </table> 
            
        <div class="row">
            @if (!is_null($medicamento->composicion))
            <div class="col-sm-6">
                <h5>Composición:</h5><span>{{$medicamento->composicion}}</span>
            </div>
            @endif
            @if (!is_null($medicamento->observacion))
            <div class="col-sm-6">
                <h5>Observación:</h5><span>{{$medicamento->observacion}}</span>
            </div>
            @endif
        </div>                            
        <div class="row">
            @if (!is_null($medicamento->indicacion))
            <div class="col-sm-6">
                <h5>Indicación:</h5><span>{{$medicamento->indicacion}}</span>
            </div>
            @endif
            @if (!is_null($medicamento->contraindicacion))
            <div class="col-sm-6">
                <h5>Contra-Indicación:</h5><span>{{$medicamento->contraindicacion}}</span>                 
            </div>                                       
            @endif 
        </div>                           
        <div class="row">
            <div class="col-sm-6">                
                <h5>Unidad Medida:</h5><span>{{$unidad_medida->descripcion}}</span>                
            </div>        
            <div class="col-sm-6">
                <h5>Vía Administración:</h5><span>{{$medicamento->via->descripcion}}</span>                        
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h5>Acciones Terapéuticas:</h5>
                <ul>
                    @foreach ($clases as $clase)
                    <li>{{$clase->nombre}}</li>
                    @endforeach  
                </ul>                
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h5>Dosís:</h5>
                <ul class="list-group">                    
                    <li class="list-group-item disabled">Lactantes: {{$medidamedicamento1}} <br> {{$dosis_estandar1}}</li>
                    <li class="list-group-item disabled">Infantes:  {{$medidamedicamento2}} <br> {{$dosis_estandar2}}</li>
                    <li class="list-group-item disabled">Adultos:   {{$medidamedicamento3}} <br> {{$dosis_estandar3}}</li>
                </ul>  
            </div>                                   
        </div>                                          
    </div>
    
    <div class="footer">
        <hr>
        <h3 style="text-align:right">{{$fecha}}</h3>
    </div>
</body>
</html>