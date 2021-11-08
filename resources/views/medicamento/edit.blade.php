@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Medicamento</h3>                
            </div> 
            <div class="box-body">
                @include('medicamento.form',['medicamento'=>$medicamento,'url'=>'/medicamento/'.$medicamento->id,'method'=>'PATCH'])
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


