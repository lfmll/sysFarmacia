@extends('adminlte::page')

@section('title', 'PuntoVenta')

@section('content')
    {!! Form::open(['url' => '/puntoventa', 'method' => 'POST']) !!}       
    {{Form::token()}}
    <div class="col-md-6">           
        <div class="card card-primary">            
            <div class="card-header">    
                <h5 class="box-title"><i class="fas fa-fw fa-hospital"></i> Registro Punto de Venta</h5>                
            </div>    
        <div class="card-body">        
            <div class="form-group">
                {{Form::text('nombre',$puntoventa->nombre,['class'=>'form-control', 'placeholder'=>'Nombre Punto Venta','required'])}}
            </div>
            <div class="form-group">
                {{Form::text('descripcion',$puntoventa->descripcion,['class'=>'form-control', 'placeholder'=>'Descripcion Punto Venta','required'])}}
            </div>
            <div class="form-group">
                {{ Form::select('agencias',$agencias, $puntoventa->agencia_id, ['class'=>'agencias form-control','placeholder'=>'Seleccionar Sucursal','required','style'=>'weight: 100%;']) }}
            </div>
            <div class="for-group">
                {{ Form::select('tipoPuntoVenta',$tipoPuntoVenta, $puntoventa->tipo_punto_venta_id, ['class'=>'tipoPuntoVenta form-control','placeholder'=>'Seleccionar Tipo Punto Venta','required','style'=>'weight: 100%;']) }}
            </div>
        </div>
        <div class="card-footer">
            <div class="float-left">
                <a type="submit" class="btn btn-default" href="{{url('/puntoventa')}}">Cancelar</a>
            </div>
            <div class="float-right">
                <input type="submit" value="Guardar" class="btn btn-success">
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