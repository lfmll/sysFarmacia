@extends('adminlte::page')

@section('title', 'Empresa')

@section('content')
@include('sweetalert::alert')
{!! Form::open(['url' => '/empresa/1', 'method' => 'PATCH']) !!}
    <div class="col-md-8">
        <div class="card">
            <img src="#" alt="">
            <div class="card-body">
                <h5 class="card-title"><b>Sistema Farmacia</b></h5>
            </div>
            <ul class="list-group list-group-flush">            
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{Form::label('nombre', 'Nombre/Razon Social'); }}
                                {{Form::text('nombre',$empresa->nombre,['class'=>'form-control','required'])}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{Form::label('nit', 'Nit'); }}
                                {{Form::text('nit',$empresa->nit,['class'=>'form-control','required'])}}
                            </div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{Form::label('correo', 'Correo'); }}
                            {{Form::text('correo',$empresa->correo,['class'=>'form-control','required'])}}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {{Form::label('sistema', 'Sistema'); }}
                                {{Form::text('sistema',$empresa->sistema,['class'=>'form-control','required'])}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {{Form::label('codigo_sistema', 'Codigo Sistema'); }}
                                {{Form::text('codigo_sistema',$empresa->codigo_sistema,['class'=>'form-control','required'])}}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                {{Form::label('version', 'version'); }}
                                {{Form::text('version',$empresa->version,['class'=>'form-control','required'])}}
                            </div>
                        </div>
                    </div>                
                    
                </li>
            </ul>        
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <input type="submit" value="Guardar" class="btn btn-info">
                    </div>
                </div>            
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop