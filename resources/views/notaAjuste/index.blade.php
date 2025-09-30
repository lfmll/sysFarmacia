@extends('adminlte::page')
@section('title', 'Nota Credito-Debito')
@section('content')
@include('sweetalert::alert')
    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                <h5 style="text-align: center;">Notas Credito-Debito</h5>                                                   
                </div>
                <div class="card-body">                    
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tnota" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th valign="center">Nro</th>    
                                    <th valign="center">Codigo</th>            
                                    <th valign="center">Documento</th>
                                    <th valign="center">Cliente</th>
                                    <th valign="center">Fecha Emision</th>
                                    <th valign="center">Monto Total</th>
                                    <th valign="center">Estado</th>
                                    <th valign="center">Opciones</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nota as $not)
                                <tr>
                                    <td>{{$not->numeroNota}}</td>
                                    <td><span class="badge badge-light">{{$not->factura->cuf}}</span></td>                                 
                                    <td>{{$not->factura->nitEmisor}}</td>
                                    <td>{{$not->factura->nombreRazonSocial}}</td>
                                    <td>{{ date('d-m-Y h:i A', strtotime($not->fechaEmision)) }}</td>  
                                    <td>{{$not->monto}}</td>
                                    
                                    @if ($not->estado == 'VALIDADA')
                                    <td><span class="badge badge-pill badge-success">{{$not->estado}}</span></td>
                                    <td>
                                        <a href="{{url('verSIATNota/'.$not->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-laptop"></i> Ver</a>
                                        <a href="{{url('notaCarta/'.$not->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF</a>
                                        <a href="{{url('notaRollo/'.$not->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF Rollo</a>
                                        <a href="{{url('generarXMLNota/'.$not->id)}}" class="btn btn-success btn-sm"><i class="fa fa-file-code"></i> XML</a>
                                        <!-- <a href="{{url('enviarCorreoNota/'.$not->id)}}" class="btn btn-info btn-sm" onClick="loading()"><i class="fa fa-envelope"></i> Notificacion</a>                                         -->
                                        <button type="button" class="btn btn-danger btn-sm" onclick="anularNota
                                        ('{{$not->id}}')"><i class="fa fa-trash"></i> Anular</button>                                                                             
                                    </td>
                                    @elseif ($not->estado == 'ANULADA')

                                    <td><span class="badge badge-pill badge-danger">{{$not->estado}}</span></td>
                                    <td>
                                      <a><button type="submit" class="btn btn-info btn-sm" onclick="revertirAnulacionNota('{{$not->id}}')"><i class="fa fa-undo"></i> Revertir Anulacion</button></a>   
                                    </td>
                                    @else
                                    <td><span class="badge badge-pill badge-warning">{{$not->estado}}</span></td>
                                    <td>                                  
                                      <a href="{{url('emitirNota/'.$not->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-receipt"></i> Emitir</a>     
                                    </td>
                                    @endif  
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/notaAjuste/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>
        </div>    
    </div> 
@stop
 

