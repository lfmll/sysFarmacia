@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Medicamento</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tmedicamento" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Nombre Comercial</td>    
                                <td>Nombre Generico</td>            
                                <td>Stock</td>
                                <td>Stock Mínimo</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicamento as $medi)
                            <tr>
                                <td>{{$medi->id}}</td>
                                <td>{{$medi->nombre_comercial}}</td>
                                <td>{{$medi->nombre_generico}}</td>
                                <td>{{$medi->stock}}</td>
                                <td>{{$medi->stock_minimo}}</td>
                                <td>
                                    <a href="{{url('/medicamento/'.$medi->id)}}" class="btn btn-info btn-sm"><i class="fa fa-bullseye"></i> Mostrar</a>
                                    <a href="{{url('/medicamento/'.$medi->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/medicamento/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tmedicamento').DataTable({
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
