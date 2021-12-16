@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
    <div class="conatiner-fluid">
        <h3 class="box-title">Editar Medicamento</h3>        
        {!! Form::open(['medicamento'=>$medicamento, 'url' => '/medicamento/'.$medicamento->id, 'method' => 'PATCH']) !!}
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{Form::textarea('composicion',$medicamento->composicion,['class'=>'form-control', 'rows'=>9, 'placeholder'=>'Composición...'])}}    
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
                                        {{Form::textarea('contraindicacion',$medicamento->contraindicacion,['class'=>'form-control', 'rows'=>5, 'placeholder'=>'Contra Indicación...'])}}    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">                    
                                        {{Form::number('stock_minimo',$medicamento->stock_minimo,['class'=>'form-control', 'placeholder'=>'Stock Mínimo','min'=>'0','required'])}}
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
                                            {!! Form::select('dosis1', ['1 dosis x 24 hrs'=>'1 dosis x 24 hrs','2 dosis x 12 hrs'=>'2 dosis x 12 hrs','3 dosis x 8 hrs'=>'3 dosis x 8 hrs'], $medidamedicamento1, ['class'=>'form-control','placeholder'=>'']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('dosis2', ['1 dosis x 24 hrs'=>'1 dosis x 24 hrs','2 dosis x 12 hrs'=>'2 dosis x 12 hrs','3 dosis x 8 hrs'=>'3 dosis x 8 hrs'], $medidamedicamento2, ['class'=>'form-control','placeholder'=>'']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('dosis3', ['1 dosis x 24 hrs'=>'1 dosis x 24 hrs','2 dosis x 12 hrs'=>'2 dosis x 12 hrs','3 dosis x 8 hrs'=>'3 dosis x 8 hrs'], $medidamedicamento3, ['class'=>'form-control','placeholder'=>'']) !!}
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
                            <div class="form-group">  
                                {!! Form::select('clases[]', $clases, $clasemedicamento->pluck('clase_id'), ['class'=>'sclases form-control','data-width'=>'100%','multiple'=>'multiple']) !!}
                            </div> 
                        </div>
                    </div>
                </div>                                                
            </div>
        <div class="form-group">
            <a type="submit" class="btn btn-default" href="{{url('/medicamento')}}">Cancelar</a>    
            <button type="submit" class="btn btn-success pull-right">Guardar</button>  
        </div>
    </div>  
@stop

@section('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.sclases').select2();
        // $('.slaboratorios').select2();
    });
</script>
@stop
{!! Form::close() !!}


