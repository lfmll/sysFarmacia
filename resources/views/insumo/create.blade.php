@extends('adminlte::page')

@section('title', 'Insumo')

@section('content')
    <div class="col-md-6">   
        <div class="card card-info">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-fw fa-archive"></i> Registrar Insumo</h5>                
            </div> 
            @include('insumo.form',['insumo'=>$insumo,'url'=>'/insumo','method'=>'POST'])     
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop