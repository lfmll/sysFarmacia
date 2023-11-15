@extends('adminlte::page')

@section('title', 'Clientes')

@section('content')
<div class="col-md-9">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-address-card"></i> Datos Generales</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">Tipo de Documento</li>
                        <li class="list-group-item disabled">{{$cliente->tipo_documento}}</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-group">
                        <li class="list-group-item">Nro Documento</li>
                        <li class="list-group-item disabled">{{$cliente->numero_documento}}</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-group">
                        <li class="list-group-item">Complemento</li>
                        <li class="list-group-item disabled">{{$cliente->complemento}}</li>
                    </ul>
                </div>                
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">Nombre o Razon Social</li>
                        <li class="list-group-item disabled">{{$cliente->nombre}}</li>
                    </ul>
                </div> 
            </div>
        </div>
    </div>
</div>

<div class="col-md-9">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-address-book"></i> Datos Adicionales</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <ul class="list-group">
                        <li class="list-group-item">Correo</li>
                        <li class="list-group-item disabled">{{$cliente->correo}}</li>
                    </ul>
                </div> 
                <div class="col-md-3">
                    <ul class="list-group">
                        <li class="list-group-item">Telefono</li>
                        <li class="list-group-item disabled">{{$cliente->telefono}}</li>
                    </ul>
                </div>                  
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">Direccion</li>
                        <li class="list-group-item disabled">{{$cliente->direccion}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



@stop