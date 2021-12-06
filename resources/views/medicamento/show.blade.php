@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-6">
        <div class="card card-primary">
            <div class="card-header">
                <div class="row">
                    <h1 class="card-title">{{$medicamento->nombre_comercial}}</h1>
                    <div class="text-right">   
                        {{$medicamento->stock}}
                    </div> 
                </div>                  
                <hr>
                <div class="row">
                    <h6 class="card-subtitle">{{$medicamento->nombre_generico}}</h6>                                                                 
                </div>              
                
            </div>
            <div class="card-body">
                <h5>Composición:</h5>
                <span>{{$medicamento->composicion}}</span>
                <hr>
                <h5>Indicación:</h5>
                <span>{{$medicamento->indicacion}}</span>
                <hr>
                <h5>Contra-Indicación:</h5>
                <span>{{$medicamento->contraindicacion}}</span>                    
            </div>
            <div class="card-footer">
    
            </div>
        </div>
    </div>
</div>
@stop