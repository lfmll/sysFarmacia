@extends('adminlte::page')

@section('title', 'Acción Terapéutica')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Acción Terapéutica</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tclase" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>    
                                <td>Nombre</td>            
                                <td>Descripción</td>
                                <td>Modificar</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clase as $cla)
                            <tr>
                                <td>{{$cla->id}}</td>
                                <td>{{$cla->nombre}}</td>
                                <td>{{$cla->descripcion}}</td>
                                <td>
                                <a href="{{url('/clase/'.$cla->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/clase/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tclase').DataTable({
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

