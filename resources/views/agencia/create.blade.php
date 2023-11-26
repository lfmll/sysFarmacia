@extends('adminlte::page')

@section('title', 'Sucursal')

@section('content')
    <div class="col-md-6">           
        <div class="card card-primary">            
            <div class="card-header">    
                <h5 class="box-title"><i class="fas fa-fw fa-hospital"></i> Registro Sucursal</h5>                
            </div> 
                @include('agencia.form',['agencia'=>$agencia,'url'=>'/agencia','method'=>'POST'])
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop