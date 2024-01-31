@extends('adminlte::page')

@section('title', 'Factura')

@section('content')
@include('sweetalert::alert')
    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                <h5 style="text-align: center;">Facturas</h5>                                                   
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tfactura" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th valign="center">Nro</th>    
                                    <th valign="center">Codigo</th>            
                                    <th valign="center">Documento</th>
                                    <th valign="center">Nombre Razon Social</th>
                                    <th valign="center">Fecha Emision</th>
                                    <th valign="center">Monto Total</th>
                                    <th valign="center">Estado</th>
                                    <th valign="center">Opciones</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factura as $fact)
                                <tr>
                                    <td>{{$fact->numeroFactura}}</td>
                                    <td>{{$fact->cuf}}</td> <!-- nroAutorizacion -->                                    
                                    <td>{{$fact->nitEmisor}}</td>
                                    <td>{{$fact->razonSocialEmisor}}</td>
                                    <td>{{$fact->fechaEmision}}</td>  
                                    <td>{{$fact->montoTotal}}</td>
                                    <td></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm"><i class="fa fa-laptop"></i> Ver SIAT</a>
                                        <a href="{{url('facturaCarta/'.$fact->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF</a>
                                        <a href="{{url('generarXML/'.$fact->id)}}" class="btn btn-success btn-sm"><i class="fa fa-file-code"></i> XML</a>
                                        <a href="#" class="btn btn-info btn-sm"><i class="fa fa-envelope"></i> Notificacion</a>
                                        <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Anular</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/factura/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>    
        </div>    
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        div.card-body{            
            overflow: auto;
            white-space: nowrap;
        }
    </style>
    
@stop