@extends('adminlte::page')
@section('css')
    <style>             
        .small-box>.inner{
            padding: 10px;            
        }
        .icon {
            margin-left: 20px;
        }
        ion-icon {
            font-size: 100px;            
        }
    </style>
@stop

@section('title', 'Importes')

@section('content')
@include('sweetalert::alert')
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h5>Importar Medicamentos</h5>
                    </div>
                    <div class="icon">
                        <ion-icon name="medkit"></ion-icon>
                    </div>
                    <a href="{{url('formatoMedicamentos')}}" class="small-box-footer">Descargar Formato <i class="fa fa-arrow-circle-down"></i></a>
                    <a href="{{url('importMedicamento')}}" class="small-box-footer">Importar Medicamentos <i class="fa fa-arrow-circle-up"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h5>Importar Clientes</h5>
                    </div>
                    <div class="icon">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                    <a href="{{url('formatoClientes')}}" class="small-box-footer">Descargar Formato <i class="fa fa-arrow-circle-down"></i></a>
                    <a href="{{url('importCliente')}}" class="small-box-footer">Importar Clientes <i class="fa fa-arrow-circle-up"></i></a>
                </div>
            </div>
        </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
@stop