@extends('adminlte::page')

@section('title', 'Laboratorios')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Laboratorio</h3>                
            </div> 
            <div class="box-body">
                @include('laboratorio.form',['laboratorio'=>$laboratorio,'url'=>'/laboratorio/'.$laboratorio->id,'method'=>'PATCH'])
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


