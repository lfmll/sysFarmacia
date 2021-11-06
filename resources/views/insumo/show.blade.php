@extends('adminlte::page')

@section('title', 'Insumos')

@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3>{{$insumo->nombre}}<h3> 
            </div>
            <div class="card-body">                                
                {{$insumo->descripcion}}
                <h6>Código: </h6>{{$insumo->codigo}}
                <h6>Cantidad Total: </h6>
            </div>
            <div class="card-footer">
                
                <h6>Precio de Compra: </h6>
                <input type="text" name="precio" id="precio" placeholder="00.00" disabled>
                <p>Ultimo Precio de Compra</p>
            </div>
        </div>    
    </div>    
</div>
        

@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>    
@stop

