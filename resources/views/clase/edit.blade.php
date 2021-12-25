@extends('adminlte::page')

@section('title', 'Acción Terapéutica')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Acción Terapéutica</h3>                
            </div>             
            @include('clase.form',['clase'=>$clase,'url'=>'/clase/'.$clase->id,'method'=>'PATCH'])            
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop


