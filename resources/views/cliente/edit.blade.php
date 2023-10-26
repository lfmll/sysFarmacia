@extends('adminlte::page')

@section('title', 'Cliente')

@section('content')
    <div class="col-md-12">   
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Editar Cliente</h3>                
            </div> 
            @include('cliente.form',['cliente'=>$cliente,'url'=>'/cliente/'.$cliente->id,'method'=>'PATCH'])            
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop