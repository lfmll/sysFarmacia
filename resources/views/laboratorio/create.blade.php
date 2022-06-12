@extends('adminlte::page')

@section('title', 'Laboratorios')

@section('content')
    <div class="col-md-6">           
        <div class="card card-success">            
            <div class="card-header">    
                <h5 class="box-title"><i class="fas fa-fw fa-flask"></i> Registro Nuevo Laboratorio</h5>                
            </div> 
            
                @include('laboratorio.form',['laboratorio'=>$laboratorio,'url'=>'/laboratorio','method'=>'POST'])
            
                     
        </div>            
    </div>

@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop