@extends('adminlte::page')

@section('title', 'Medicamento')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-4">                            
                        <a href="{{url('#')}}" class="btn btn-info"><i class="fa fa-medkit fa-lg"></i></a>
                    </div>
                    <div class="col-sm-6">
                        <h5>Medicamentos</h5>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{url('listaMedicamentos')}}" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</a>
                    </div>                    
                </div>                                                   
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
                                    <a href="{{url('/lote/'.$medi->id.'/create_medicamento')}}" class="btn btn-warning btn-sm"><i class="fa fa-fw fa-receipt"></i></a>
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

