@extends('adminlte::page')

@section('title', 'Ventas')

@section('content')
@include('sweetalert::alert')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ventas</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tventa" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Fecha Venta</td>            
                                <!-- <td>Forma Pago</td>  -->
                                <td>Total</td>                                                 
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                            <tr>
                                <td>{{$venta->fecha_venta}}</td>
                                
                                <td>{{$venta->total}}</td>
                                <td>                                
                                    <a href="{{url('/venta/'.$venta->id)}}" class="btn btn-info btn-sm"><i class="fa fa-bars"></i> Detalle</a>
                                </td>                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/venta/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nueva venta</a>
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
            $('#tventa').DataTable({
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

