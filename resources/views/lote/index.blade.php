@extends('adminlte::page')

@section('title', 'Lote')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lote</h3>                                                    
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tlote" class="table table-bordered">
                            <thead>
                                <tr>                                    
                                    <td>Nro</td>
                                    <td>Cantidad</td>
                                    <td>Fecha Vencimiento</td>
                                    <td>Laboratorio</td>
                                    <td>Medicamento/Insumo</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lotes as $lote)
                                <tr>                                    
                                    <td>{{$lote->numero}}</td>
                                    <td>{{$lote->cantidad}}</td>
                                    <td>{{$lote->fecha_vencimiento}}</td>
                                    <td>{{$lote->laboratorio->nombre}}</td>
                                    @if (!is_null($lote->medicamento))
                                        <td>
                                            {{$lote->medicamento->nombre_comercial}}
                                        </td>
                                    @endif
                                    @if (!is_null($lote->insumo))
                                        <td>
                                            {{$lote->insumo->nombre}}
                                        </td>
                                    @endif                                    
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
