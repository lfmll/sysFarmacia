@extends('adminlte::page')

@section('title', 'Lote')

@section('content')
<div class="row">
    <div class="col-md-12">           
        <div class="card card-warning">            
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-fw fa-receipt"></i> Nuevo Lote</h5>                
            </div>        
            {!! Form::open(['url' => '/lote', 'method' => 'POST']) !!}                 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::text('numero', $lote->numero, ['class'=>'form-control', 'placeholder'=>'Numero de lote','required'])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::number('cantidad', $lote->cantidad, ['class'=>'form-control','min'=>'0', 'placeholder'=>'Cantidad'])}}    
                            </div>        
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::number('precio_compra',$lote->precio_compra,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Compra']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::number('precio_venta',$lote->precio_venta,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Venta']) }}
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::date('fecha_vencimiento', $lote->fecha_vencimiento,['class'=>'form-control laboratorio', 'placeholder'=>'Fecha de Vencimiento','required'])}}
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('laboratorio', 'Laboratorio') !!}
                                {{ Form::select('laboratorios',$laboratorios, $lote->laboratorio_id, ['class'=>'laboratorios form-control','placeholder'=>'','required','style'=>'weight: 100%;']) }}
                            </div>                    
                            <div class="form-group" style="display: none;">
                                {{ Form::select('medicamentos',$medicamentos, null, ['class'=>'form-control','placeholder'=>'']) }}                              
                                {{ Form::select('insumos',$insumos, $insumo_id,['class'=>'form-control']) }}
                            </div>                                
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-left">
                        <a type="submit" class="btn btn-default" href="{{url('/medicamento')}}">Cancelar</a>   
                    </div>                
                    <div class="float-right">
                        <input type="submit" value="Guardar" class="btn btn-warning"> 
                    </div>                
                </div>
            {!! Form::close() !!}                  
        </div>            
    </div>
</div>
    
@stop
@section('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="/css/admin_custom.css">
    <script>
        $(document).ready(function(){
            $('.laboratorios').select2();
        });
    </script>
@stop