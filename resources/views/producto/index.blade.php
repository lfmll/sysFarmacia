@extends('adminlte::page')

@section('title', 'Producto')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-10">                            
                            <a href="{{url('#')}}" class="btn btn-primary"><i class="fa fa-inbox fa-lg"></i></a>
                        </div>
                        <div class="col-sm-2">
                            <a href="{{url('listaProductos')}}" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir</a>
                        </div>                    
                    </div>
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tlote" class="table table-bordered">
                            <thead>
                                <tr>                                    
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Stock</th>
                                    <th>Stock Mínimo</th>
                                    <th>Acciones</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                <tr>   
                                    <td>{{$producto->id}}</td>                                 
                                    <td>{{$producto->nombre}}</td>
                                    <td>{{$producto->descripcion}}</td>
                                    <td>{{$producto->stock}}</td>
                                    <td>{{$producto->stock_minimo}}</td>                                    
                                    <td>
                                        <a href="{{url('/producto/'.$producto->id.'/edit')}}" class="btn btn-primary"><i class="fa fa-edit"></i> Editar</a>
                                        <a href="{{url('/lote/'.$producto->id.'/create_producto')}}" class="btn btn-warning"><i class="fa fa-receipt"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/producto/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
            $('#tlote').DataTable({
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
