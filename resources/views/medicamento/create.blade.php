@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
@include('sweetalert::alert')
    <div class="conatiner-fluid">
        <h3 class="box-title">Registro Nuevo Medicamento</h3>
        {!! Form::open(['url' => '/medicamento', 'method' => 'POST']) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-medkit"></i> Medicamento</h3>    
                        </div>   
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!!Form::text('codigo',$codigo,['class'=>'form-control','required']) !!}
                                    </div>                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">                                    
                                        {{ Form::select('catalogos', $catalogos, $medicamento->catalogo_id,['class'=>'form-control','required']) }}
                                    </div>                                    
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::text('nombre_comercial',$medicamento->nombre_comercial,['class'=>'form-control', 'placeholder'=>'Nombre Comercial','required'])}}
                                    </div>
                                </div>                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::text('nombre_generico',$medicamento->nombre_generico,['class'=>'form-control', 'placeholder'=>'Nombre Genérico','required'])}}
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::textarea('composicion',$medicamento->composicion,['class'=>'form-control', 'rows'=>5, 'placeholder'=>'Composición...'])}}    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::textarea('observacion',$medicamento->observacion,['class'=>'form-control', 'rows'=>5, 'placeholder'=>'Observacion...'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::textarea('indicacion',$medicamento->indicacion,['class'=>'form-control', 'rows'=>5, 'placeholder'=>'Indicación...'])}}    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::textarea('contraindicacion',$medicamento->contraindicacion,['class'=>'form-control', 'rows'=>5, 'placeholder'=>'Contraindicación...'])}}    
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group">                    
                                        {{Form::number('stock_minimo',$medicamento->stock_minimo,['class'=>'form-control', 'placeholder'=>'Stock Mínimo', 'min'=>'0', 'required'])}}
                                    </div>
                                </div>                                                               
                            </div>                                                                                                                                                   
                        </div>         
                    </div>        
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">                    
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-pills"></i> Presentación</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                {!! Form::select('formatos', $formatos, $medicamento->formato_id, ['class'=>'form-control','placeholder'=>'','required']) !!}    
                            </div>    
                        </div>            
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-syringe"></i> Vía de Administración</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                {!! Form::select('vias', $vias, $medicamento->via_id, ['class'=>'form-control','placeholder'=>'','required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"> 
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-vial"></i> Dosís</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Lactantes</th>
                                        <th scope="col">Infantes</th>
                                        <th scope="col">Adultos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::select('dosis1', ['1 dosis x 24 hrs'=>'1 dosis x 24 hrs','2 dosis x 12 hrs'=>'2 dosis x 12 hrs','3 dosis x 8 hrs'=>'3 dosis x 8 hrs','4 dosis x 6hrs'=>'4 dosis x 6hrs','6 dosis x 4hrs'=>'6 dosis x 4hrs'], null, ['class'=>'form-control','placeholder'=>'']) !!}
                                            <hr style="border: none;">
                                            {!! Form::text('dosis_estandar1', $dosis_estandar1, ['class'=>'form-control','placeholder'=>'Dosis Estandar']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('dosis2', ['1 dosis x 24 hrs'=>'1 dosis x 24 hrs','2 dosis x 12 hrs'=>'2 dosis x 12 hrs','3 dosis x 8 hrs'=>'3 dosis x 8 hrs','4 dosis x 6hrs'=>'4 dosis x 6hrs','6 dosis x 4hrs'=>'6 dosis x 4hrs'], null, ['class'=>'form-control','placeholder'=>'']) !!}
                                            <hr style="border: none;">
                                            {!! Form::text('dosis_estandar2', $dosis_estandar2, ['class'=>'form-control','placeholder'=>'Dosis Estandar']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('dosis3', ['1 dosis x 24 hrs'=>'1 dosis x 24 hrs','2 dosis x 12 hrs'=>'2 dosis x 12 hrs','3 dosis x 8 hrs'=>'3 dosis x 8 hrs','4 dosis x 6hrs'=>'4 dosis x 6hrs','6 dosis x 4hrs'=>'6 dosis x 4hrs'], null, ['class'=>'form-control','placeholder'=>'']) !!}
                                            <hr style="border: none;">
                                            {!! Form::text('dosis_estandar3', $dosis_estandar3, ['class'=>'form-control','placeholder'=>'Dosis Estandar']) !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-12"> 
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-notes-medical"></i> Acciones Terapéuticas</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Seleccionar uno o Varios</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>
                                        {!! Form::select('clases[]', $clases, null, ['class'=>'sclases form-control','data-width'=>'100%','multiple'=>'multiple', 'required']) !!}
                                        </th>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>        
            </div>
        <div class="form-group">
            <div class="float-left">
                <a type="submit" class="btn btn-default btn-lg" href="{{url('/medicamento')}}">Cancelar</a>    
            </div>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-lg">Guardar</button>  
            </div>            
        </div>
    </div>  
@stop

@section('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sclases').select2();
        });
    </script>
@stop
{!! Form::close() !!}