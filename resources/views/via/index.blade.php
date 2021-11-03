@extends('adminlte::page')

@section('title', 'Vías de Administración')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vía de Administración</h3>
                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tvia" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>                
                                <td>Descripción</td>
                                <td>Modificar</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($via as $v)
                            <tr>
                                <td>{{$v->id}}</td>
                                <td>{{$v->descripcion}}</td>
                                <td>
                                <a href="{{url('/via/'.$v->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/via/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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

