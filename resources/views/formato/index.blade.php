@extends('adminlte::page')

@section('title', 'Presentaciones')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-4">                            
                        <a href="{{url('#')}}" class="btn btn-info"><i class="fa fa-pills fa-lg"></i></a>
                    </div>
                    <div class="col-sm-8">
                        <h5>Presentaciones</h5>
                    </div>
                </div>
            </div>            
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tformato" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>            
                                <td>Descripción</td>
                                <td>Modificar</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($formato as $f)
                            <tr>
                                <td>{{$f->id}}</td>
                                <td>{{$f->descripcion}}</td>
                                <td>
                                <a href="{{url('/formato/'.$f->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/formato/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tformato').DataTable({
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

