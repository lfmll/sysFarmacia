@extends('adminlte::page')

@section('title', 'Laboratorio')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laboratorio</h3>                                                    
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tlaboratorio" class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>                
                                    <td>Nombre</td>
                                    <td>Dirección</td>
                                    <td>Teléfono</td>
                                    <td>Procedencia</td>
                                    <td>Anotaciones</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laboratorio as $lab)
                                <tr>
                                    <td>{{$lab->id}}</td>
                                    <td>{{$lab->nombre}}</td>
                                    <td>{{$lab->direccion}}</td>
                                    <td>{{$lab->telefono}}</td>
                                    <td>{{$lab->procedencia}}</td>
                                    <td>{{$lab->anotacion}}</td>
                                    <td>
                                    <a href="{{url('/laboratorio/'.$lab->id.'/edit')}}" class="btn btn-primary"><i class="fa fa-edit"></i> Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/laboratorio/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tlaboratorio').DataTable({
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

