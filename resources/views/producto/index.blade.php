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
                                    <td>ID</td>
                                    <td>Nombre</td>
                                    <td>Stock</td>
                                    <td>Fecha Vencimiento</td>
                                    <td>Precio Compra</td>
                                    <td>Precio Venta</td>
                                    <td>Acciones</td>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                <tr>   
                                    <td>{{$producto->id}}</td>                                 
                                    <td>{{$producto->nombre}}</td>
                                    <td>{{$producto->stock}}</td>
                                    <td>{{$producto->fecha_vencimiento}}</td>
                                    <td>{{$producto->precio_compra}}</td>
                                    <td>{{$producto->precio_venta}}</td>
                                    <td>
                                        <a href="{{url('/producto/'.$producto->id.'/edit')}}" class="btn btn-primary"><i class="fa fa-edit"></i> Editar</a>
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
