@extends('adminlte::page')

@section('title', 'Lista de Arqueo')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Conteo Semanal</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tvia" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Día</td>                
                                <td>Fecha</td>
                                <td>Hora de Inicio</td>
                                <td>Hora de Cierre</td>
                                <td>Egresos</td>
                                <td>Ingresos</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cajas as $caja)
                            <tr>
                                <td>{{$dias[(date('N', strtotime($caja->fecha)))-1]}}</td>
                                <td>{{$caja->fecha}}</td>
                                <td>{{$caja->hora_inicio}}</td>
                                @if (is_null($caja->hora_fin))
                                    <td></td>
                                @else
                                    <td>{{$caja->hora_fin}}</td>
                                @endif
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
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
            $('#tvia').DataTable({
                "responsive" : false,
                "paging": true,
                "lengthMenu": [7, 7, "All"],
                "searching": true,
                "ordering": false,
                "info": false,
                "language" : {"url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"},
                "autoWidth": false
            });
        });
    </script>
@stop

