<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
    </style>
    <title>Reporte de Medicamento</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Medicamento</h2>
    </header>
    <main>   
        <div class="row">
            <div class="col-xs-6">
                <h4>Nombre Comercial</h4>    
                <p>{{$medicamento->nombre_comercial}}</p>
            </div>
            <div class="col-xs-6">
                <h4>Nombre Genérico</h4>
                <p>{{$medicamento->nombre_generico}}</p>
            </div>
        </div>     
        <div class="row">
            <div class="col-xs-6">
                <h5>Composición:</h5>
                <span>{{$medicamento->composicion}}</span>
            </div>
            <div class="col-xs-6">
                <h5>Observación:</h5>
                <span>{{$medicamento->observacion}}</span>
            </div>
        </div>      
        <hr>              
        <div class="row">
            <div class="col-xs-6">
                <h5>Indicación:</h5>
                <span>{{$medicamento->indicacion}}</span>
            </div>
            <div class="col-xs-6">
                <h5>Contra-Indicación:</h5>
                <span>{{$medicamento->contraindicacion}}</span>                 
            </div>                                        
        </div>
        <hr>                    
        <div class="row">
            <div class="col-xs-6">
                <h5>Presentación:</h5>
                <span>{{$medicamento->formato->descripcion}}</span> 
            </div>        
            <div class="col-xs-6">
                <h5>Vía Administración:</h5>
                <span>{{$medicamento->via->descripcion}}</span>                        
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h5>Acciones Terapéuticas:</h5>
                <ul>
                    @foreach ($clases as $clase)
                    <li>{{$clase->nombre}}</li>
                    @endforeach  
                </ul>                
            </div> 
        </div>
        <hr style="border: none;">
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
    </main>
    <hr>
    <footer>
        <h3 style="text-align:right">{{$fecha}}</h3>
    </footer>
</body>
</html>