@extends('adminlte::page')

@section('title', 'Insumo')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Registro Insumo</h3>                
            </div> 
            <div class="box-body">
                @include('insumo.form',['insumo'=>$insumo,'url'=>'/insumo','method'=>'POST'])
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