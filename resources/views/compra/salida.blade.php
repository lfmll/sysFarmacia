@extends('adminlte::page')

@section('title', 'Compra')

@section('content')

    {!! Form::open(['url' => '/compra', 'method' => 'POST']) !!}       
    {{Form::token()}}     
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-bookmark"></i> Pago</h3>    
                </div>   
                <div class="card-body">                                                        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('Glosa', 'Glosa') !!}
                                {!! Form::text('glosa', null, ['class'=>'form-control','required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Pago', 'Pago') !!}
                                {!! Form::number('Pago', null, ['id'=>'ppago','class'=>'form-control','placeholder'=>'0.00', 'min'=>'0', 'required', 'step'=>'any']) !!}                                                                
                            </div>
                        </div>
                    </div>
                    <hr>                                                                                                                                                                        
                    <div class="row">
                        <div class="container">
                            <div class="checkbox form-group">
                                <label><i class="fa fa-credit-card"></i> Forma de Pago</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="forma_pago" value="Efectivo" id="chkefectivo" checked>
                                    <label class="form-check-label" for="chkefectivo">
                                        Efectivo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="forma_pago" value="Linkser" id="chktarjeta" >
                                    <label class="form-check-label" for="chktarjeta">
                                        Tarjeta
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="forma_pago" value="QR" id="chkqr" >
                                    <label class="form-check-label" for="chkqr">
                                        QR
                                    </label>
                                </div>
                            </div> 
                        </div>                        
                    </div>
                </div>                  
                <div class="card-footer">
                    <div class="form-group">
                        <div class="float-left">
                            <a type="submit" class="btn btn-default btn-lg" href="{{url('/compra')}}">Cancelar</a>    
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
