@extends('adminlte::page')

@section('title', 'Cliente')

@section('content')
    <div class="col-md-12">   
        <div class="card card-info">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-fw fa-user"></i>Registro de Clientes</h2>                
            </div>                         
                @include('cliente.form',['cliente'=>$cliente,'url'=>'/cliente','method'=>'POST'])
        </div>            
    </div>

    
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop