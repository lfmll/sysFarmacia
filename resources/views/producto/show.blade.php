@extends('adminlte::page')

@section('title', 'Productos')

@section('content')
<div class="card" style="width: 18rem;">
    @if($producto->extension)
        <img src="{{url("/imagenProducto/$producto->id.$producto->extension")}}" class="product-avatar">
    @endif
    <div class="card-body">
        <h5 class="card-title"><strong>{{$producto->descripcion}}</strong></h5>
        <br>
        <hr>
        <p class="card-text"><strong>Precio:</strong> {{$producto->precio_unitario}}</p>
        <p class="card-text"><strong>Codigo:</strong> {{$producto->codigo}}</p>
        <p class="card-text"><strong>Unidad: </strong> {{$producto->unidad}}</p>
    </div>
    <ul class="list-group list-group-flush">
        <li></li>
        <li class="list-group-item"><strong>Catalogo:</strong> {{$producto->catalogo->nombre}}</li>        
    </ul>
</div>
@stop