@extends('adminlte::page')

@section('title', 'Dosis')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Dosis</h3>                
            </div> 
            @include('medida.form',['medida'=>$medida,'url'=>'/medida/'.$medida->id,'method'=>'PATCH'])            
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop


