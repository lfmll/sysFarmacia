@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
    <div class="conatiner-fluid">
        <h3 class="box-title">Registro Nuevo Medicamento</h3>
        @include('medicamento.form',['medicamento'=>$medicamento,'url'=>'/medicamento','method'=>'POST'])
    </div>  
@stop
@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('scripts')    
    <script> console.log('Hi!'); </script>
@stop