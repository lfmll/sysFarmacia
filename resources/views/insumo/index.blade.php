@extends('adminlte::page')

@section('title', 'Insumos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Insumos</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tinsumo" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Codigo</td>    
                                <td>Nombre</td>            
                                <td>Descripción</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($insumo as $ins)
                            <tr>
                                <td>{{$ins->id}}</td>
                                <td>{{$ins->codigo}}</td>
                                <td>{{$ins->nombre}}</td>
                                <td>{{$ins->descripcion}}</td>
                                <td>
                                    <a href="{{url('/insumo/'.$ins->id)}}" class="btn btn-info btn-sm"><i class="fa fa-bullseye"></i> Mostrar</a>
                                    <a href="{{url('/insumo/'.$ins->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                    <a href="{{url('/lote/'.$ins->id.'/create_insumo')}}" class="btn btn-warning btn-sm"><i class="fa fa-fw fa-receipt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/insumo/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tinsumo').DataTable({
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

