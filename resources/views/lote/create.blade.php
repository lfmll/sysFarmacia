@extends('adminlte::page')

@section('title', 'Lote')

@section('content')
<div class="row">
    <div class="col-md-12">           
        <div class="card card-warning">            
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-fw fa-receipt"></i> Nuevo Lote</h5>                
            </div>                         
            @include('lote.form',['lote'=>$lote,'url'=>'/lote','method'=>'POST'])                  
        </div>            
    </div>
</div>
    
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

