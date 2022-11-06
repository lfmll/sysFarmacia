@extends('adminlte::page')

@section('title', 'Apertura de Caja')

@section('content')
@include('sweetalert::alert')
    {!! Form::open(['url' => '/caja', 'method' => 'POST']) !!}       
        {{Form::token()}}  
        <div class="col-md-6">   
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title">Apertura de Caja</h3>                
                </div> 
                <div class="card-body">                
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    {!! Form::label('Fecha', 'Fecha') !!}
                                    <div class="col-sm-4">
                                        {!! Form::label('fecha', $fecha) !!}
                                        {!! Form::label('hora_inicio', $hora_inicio) !!}
                                        <input type="hidden" name="fecha" id="efecha" value={{$fecha}}>
                                        <input type="hidden" name="hora_inicio" id="ehora_inicio" value={{$hora_inicio}}>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('Monto de Apertura', 'Monto de Apertura') !!}
                                    <div class="col-sm-6">
                                        {!! Form::number('monto_apertura', null, ['class'=>'form-control', 'placeholder'=>'0.00', 'step'=>'any', 'required']) !!}
                                    </div>
                                    
                                </div>
                            </div>                         
                        </div>                    
                    </div>                
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <div class="float-left">
                            <a type="submit" class="btn btn-default btn-lg" href="{{url('/caja')}}">Cancelar</a>    
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-success btn-lg">Guardar</button>  
                        </div>            
                    </div>                
                </div>
            </div>            
        </div>
    {!! Form::close() !!}
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script src="js/sweetalert.min.js"></script>
@stop