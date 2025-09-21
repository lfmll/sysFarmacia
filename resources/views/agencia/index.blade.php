@extends('adminlte::page')

@section('title', 'Proveedor')

@section('content')
@include('sweetalert::alert')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sucursales</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tagencia" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Código</td>    
                                <td>Nombre</td>            
                                <td>Direccion</td>
                                <td>Telefono</td>
                                <td>Departamento</td>
                                <td>Municipio</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agencias as $agencia)
                            <tr>
                                <td>{{$agencia->codigo}}</td>
                                <td>{{$agencia->nombre}}</td>
                                <td>{{$agencia->direccion}}</td>
                                <td>{{$agencia->telefono}}</td>
                                <td>{{$agencia->departamento}}</td>
                                <td>{{$agencia->municipio}}</td>
                                <td>
                                    <a href="{{url('/agencia/'.$agencia->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/agencia/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tagencia').DataTable({
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

