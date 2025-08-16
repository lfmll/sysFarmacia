@extends('adminlte::page')
@section('title', 'Crear Evento')
@section('content')
    {!! Form::open(['url'=>'/evento','method'=>'POST']) !!}
    {{Form::token()}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Crear Evento</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('tipo', 'Tipo del Evento') !!}
                        {!! Form::select('tipos', $eventos, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un motivo']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('cafc', 'CAFC') !!}
                        {!! Form::text('cafc', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el cafc del evento']) !!}    
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('fecha', 'Fecha') !!}
                        {!! Form::date('fecha', $fecha, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('hora', 'Hora') !!}
                        {!! Form::time('hora', $hora, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('nombre', 'Nombre del Evento') !!}
                {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del evento', 'required', 'readonly']) !!}
            </div>           
        </div>
        <div class="card-footer">
            <div class="form-group">
                <div class="float-left">
                    <a href="{{ url('/evento') }}" class="btn btn-secondary">Cancelar</a>
                </div>
                <div class="float-right">
                    {!! Form::submit('Guardar Evento', ['class' => 'btn btn-success']) !!}
                </div>
            </div>                        
        </div>
    </div>
@stop