@extends('adminlte::page')

@section('title', 'Presentaciones')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Presentaciones</h3>                
            </div> 
            <div class="box-body">
                @include('formato.form',['formato'=>$formato,'url'=>'/formato/'.$formato->id,'method'=>'PATCH'])
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


