@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-6">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <h1 class="card-title">{{$medicamento->nombre_comercial}}</h1>                    
                </div>                  
                <hr style="border: none;">
                <div class="row">
                    <h6 class="card-subtitle">{{$medicamento->nombre_generico}}</h6>                                                                 
                </div>              
                
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h5>Composición:</h5>
                        <span>{{$medicamento->composicion}}</span>
                    </div>
                </div>      
                <hr>              
                <div class="row">
                    <div class="col-md-6">
                        <h5>Indicación:</h5>
                        <span>{{$medicamento->indicacion}}</span>
                    </div>
                    <div class="col-md-6">
                        <h5>Contra-Indicación:</h5>
                        <span>{{$medicamento->contraindicacion}}</span>                    
                    </div>                                        
                </div>
                <hr>                    
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item">Presentación:</li>
                            <li class="list-group-item disabled">{{$formatos->descripcion}}</li>
                        </ul> 
                    </div>
               
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item">Vía Administración:</li>
                            <li class="list-group-item disabled">{{$vias->descripcion}}</li>
                        </ul>                        
                    </div>
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item">Dosís:</li>
                            <li class="list-group-item disabled">Lactantes: {{$medidamedicamento1}}</li>
                            <li class="list-group-item disabled">Infantes: {{$medidamedicamento2}}</li>
                            <li class="list-group-item disabled">Adultos: {{$medidamedicamento3}}</li>
                        </ul>  
                    </div>
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item">Acciones Terapéuticas:</li>
                            @foreach ($clases as $cl)
                            <li class="list-group-item disabled">{{$cl->nombre}}</li>
                            @endforeach                            
                        </ul>  
                    </div>                    
                </div>                   
            </div>                         
            <div class="card-footer">
                <h5>Stock:</h5>
                <span>   
                    {{$medicamento->stock}}
                </span> 
            </div>
        </div>                
    </div>
</div>
@stop