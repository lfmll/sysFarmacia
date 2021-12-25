@extends('adminlte::page')

@section('title', 'Laboratorios')

@section('content')
    <div class="col-md-6">   
        <div class="card card-success"> 
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fas fa-fw fa-flask"></i> Editar Laboratorio</h3>                
            </div> 
            
            @include('laboratorio.form',['laboratorio'=>$laboratorio,'url'=>'/laboratorio/'.$laboratorio->id,'method'=>'PATCH'])            
            
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop


