@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-6">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-10">
                        <h1 class="card-title">{{$medicamento->codigo_producto}}</h1>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{url('/detalleMedicamento/'.$medicamento->id)}}" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir</a>
                    </div>                                        
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <li class="list-group-item list-group-item-primary">{{$medicamento->nombre_comercial}} - {{$medicamento->nombre_generico}}</li>
                    </div>
                </div>
                <hr style="border: none;"> 
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary">Composición:</li>
                            <li class="list-group-item disabled">{{$medicamento->composicion}}</li>
                        </ul>                        
                    </div>
                    <div class="col-md-6">
                        <li class="list-group-item list-group-item-primary">Observación:</li>
                        <li class="list-group-item disabled">{{$medicamento->observacion}}</li>
                    </div>
                </div>  
                <hr style="border: none;">                               
                <div class="row">
                    <div class="col-md-6">
                        <li class="list-group-item list-group-item-primary">Indicación:</li>
                        <li class="list-group-item disabled">{{$medicamento->indicacion}}</li>
                    </div>
                    <div class="col-md-6">
                        <li class="list-group-item list-group-item-primary">Contra-Indicación:</li>
                        <li class="list-group-item disabled">{{$medicamento->contraindicacion}}</li>                    
                    </div>                                        
                </div>
                <hr>                    
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary">Unidad Medida:</li>
                            <li class="list-group-item disabled">{{$unidad_medida->descripcion}}</li>
                        </ul> 
                    </div>
               
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary">Vía Administración:</li>
                            <li class="list-group-item disabled">{{$vias->descripcion}}</li>
                        </ul>                        
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary">Acciones Terapéuticas:</li>
                            @foreach ($clases as $cl)
                            <li class="list-group-item disabled">{{$cl->nombre}}</li>
                            @endforeach                            
                        </ul>  
                    </div> 
                </div>
                <hr >
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary">Dosis:</li>
                            @if (!is_null($medidamedicamento1))
                            <li class="list-group-item disabled">Lactantes: {{$medidamedicamento1}} <br> {{$dosis_estandar1}}</li>
                            @endif
                            @if (!is_null($medidamedicamento2))
                            <li class="list-group-item disabled">Infantes:  {{$medidamedicamento2}} <br> {{$dosis_estandar2}}</li>
                            @endif
                            @if (!is_null($medidamedicamento3))
                            <li class="list-group-item disabled">Adultos:   {{$medidamedicamento3}} <br> {{$dosis_estandar3}}</li>
                            @endif
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