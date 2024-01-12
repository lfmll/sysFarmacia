@extends('adminlte::page')

@section('title', 'Lote')

@section('content')
@include('sweetalert::alert')
    <div class="row">
        <div class="col-12">
            <div class="card card-warning">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-10">                            
                            <a href="{{url('#')}}" class="btn btn-warning"><i class="fa fa-receipt fa-lg"></i></a>
                        </div>
                        <div class="col-sm-2">
                            <a href="{{url('listaLotes')}}" class="btn btn-warning"><i class="fa fa-print"></i> Imprimir</a>
                        </div>                    
                    </div>
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tlote" class="table table-bordered">
                            <thead>
                                <tr>                                    
                                    <th>Nro</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Laboratorio</th>
                                    <th>Concepto</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lotes as $lote)
                                <tr>                                    
                                    <td>{{$lote->numero}}</td>
                                    <td>{{$lote->cantidad}}</td>
                                    <td>{{$lote->fecha_vencimiento}}</td>
                                    @if (!is_null($lote->laboratorio))
                                        <td>
                                            {{$lote->laboratorio->nombre}}
                                        </td>
                                    @else
                                        <td></td>
                                    @endif                                    
                                    @if (!is_null($lote->medicamento))
                                        <td>
                                            {{$lote->medicamento->nombre_comercial}}
                                        </td>
                                    @endif  
                                    <td>{{$lote->precio_compra}}</td>
                                    <td>{{$lote->precio_venta}}</td>
                                    <td>
                                    <a href="{{url('/lote/'.$lote->id.'/edit')}}" class="btn btn-primary"><i class="fa fa-edit"></i> Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/lote/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>    
        </div>    
    </div>        
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script>
        $(function () {
            $('#tlote').DataTable({
                "responsive" : false,
                "paging": true,
                "lengthMenu": [4, 8, "All"],
                "searching": true,
                "ordering": false,
                "info": false,
                "language" : {"url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"},
                "autoWidth": false
            });
        });
    </script>
@stop
