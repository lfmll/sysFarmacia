@extends('adminlte::page')
@section('title','Eventos_Significativos')
@section('content')
@include('sweetalert::alert')
<h4>Eventos Significativos</h4>
@foreach($eventos as $evento)
    <div class="card">
        <div class="card-header">
            <h6><b>Evento:</b> {{ $evento->id }}</h6>
            <h6><b>Fecha Inicio:</b> {{ date('d-m-Y', strtotime($evento->fechaInicioEvento))}} |<b>Fecha Fin:</b>@if(is_null($evento->fechaFinEvento)) Sin Asignar @else date('d-m-Y', strtotime($evento->fechaFinEvento)) @endif</h6>
        </div>
        <div class="card-body">            
            <ul class="list-group list-group-horizontal">                            
                <li class="list-group-item"><b>CAFC:</b> @if(is_null($evento->cafc)) <br>No Aplica @else <br>{{$evento->cafc}} @endif</li>
                <li class="list-group-item"><b>Cod Recepcion:</b> {{ $evento->codigoRecepcion }}</li>
                <li class="list-group-item"><b>Evento:</b> <br>{{ $evento->descripcion }}</li>
                <li class="list-group-item"><b>Cant Facturas:</b> <br>{{ $evento->cantidadFacturas}}</li>
                <li class="list-group-item"><b>Estado:</b><br><span class="badge bg-secondary">{{ $evento->estado }}</span></li>
                <li class="list-group-item"><b>Acciones:</b><br><a href="#" class="btn btn-primary">Paquete</a><a href="#" class="btn btn-success">Validar</a></li>
            </ul>                        
        </div>
    </div>
@endforeach
<div class="card-footer">
    <a href="{{url('/evento/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Iniciar Evento</a>
</div>
@stop