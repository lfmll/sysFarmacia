@extends('adminlte::page')

@section('title', 'Vías de Administración')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Registro Vías de Administración</h3>                
            </div> 
            <div class="box-body">
                @include('via.form',['via'=>$via,'url'=>'/via','method'=>'POST'])
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