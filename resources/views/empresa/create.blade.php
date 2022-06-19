@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">        
@stop

@section('auth_body')
<div class="col-md-12">           
    <div class="row">
        <div class="col-1"></div>
        <div class="col-11">
            <h5 class="input-group mb-12"><i class="fas fa-fw fa-hospital"></i>Registro Inicial de Farmacia</h5>
        </div>
    </div>
    
    @include('empresa.form',['empresa'=>$empresa,'url'=>'/empresa','method'=>'POST'])
    
@stop