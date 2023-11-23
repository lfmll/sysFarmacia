@extends('adminlte::page')

@section('title', 'Producto')

@section('content')
@include('sweetalert::alert')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">                            
                            <a href="{{url('#')}}" class="btn btn-primary"><i class="fa fa-archive fa-lg"></i></a>
                        </div>
                        <div class="col-sm-6">
                            <h5>Productos</h5>                            
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
                                    <th>Catalogo</th>
                                    <th>Codigo</th>
                                    <th>Descripción</th>
                                    <th>Precio Unitario</th>
                                    <th>Acciones</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                <tr>   
                                    <td>{{$producto->id}}</td>                                 
                                    @if (!is_null($producto->catalogo))
                                        <td>
                                            {{$producto->catalogo->nombre}}
                                        </td>
                                    @else
                                        <td></td>
                                    @endif 
                                    <td>{{$producto->codigo}}</td>
                                    <td>{{$producto->descripcion}}</td>
                                    <td>{{$producto->precio_unitario}}</td>                                                                        
                                    <td>
                                        <a href="{{url('/producto/'.$producto->id)}}" class="btn btn-warning"><i class="fa fa-eye"></i> Ver</a>
                                        <a href="{{url('/producto/'.$producto->id.'/edit')}}" class="btn btn-primary"><i class="fa fa-edit"></i> Editar</a>
                                        <a href="{{url('/lote/'.$producto->id.'/create_producto')}}" class="btn btn-warning"><i class="fa fa-receipt"></i></a>
                                        @include('producto.delete',['producto'=>$producto])
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

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $( ".eliminar" ).on( "click", function(e) {
            e.preventDefault();
            var form = $(this).parents('form');
            
            Swal.fire({
                title: 'Esta Seguro?',
                text: "Eliminar Producto",
                type: 'warning',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    form.submit();            
                }
                
            });
        });
    </script>
@stop
