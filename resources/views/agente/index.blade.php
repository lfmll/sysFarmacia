@extends('adminlte::page')

@section('title', 'Proveedor')

@section('content')
@include('sweetalert::alert')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Proveedores/Visitadores</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tagente" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>    
                                <td>Nombre</td>            
                                <td>Telefonos</td>
                                <td>Anotaciones</td>
                                <td>Laboratorio</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agentes as $agente)
                            <tr>
                                <td>{{$agente->id}}</td>
                                <td>{{$agente->nombre}}</td>
                                <td>{{$agente->telefonos}}</td>
                                <td>{{$agente->anotacion}}</td>
                                <td>{{$agente->laboratorio->nombre}}</td>
                                <td>
                                <a href="{{url('/agente/'.$agente->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/agente/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tagente').DataTable({
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

