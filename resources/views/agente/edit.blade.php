@extends('adminlte::page')

@section('title', 'Proveedor')

@section('content')
    <div class="col-md-6">   
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Registro Proveedor/Visitador</h3>                
            </div> 
                @include('agente.form',['agente'=>$agente,'url'=>'/agente/'.$agente->id,'method'=>'PATCH'])
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop