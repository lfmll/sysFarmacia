@extends('adminlte::page')

@section('title', 'Compras')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Compras</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tcompras" class="table table-bordered">
                        <thead>
                            <tr>                                
                                <td>Comprobante</td>
                                <td>Fecha Compra</td>            
                                <td>Proveedor</td> 
                                <td>Forma Pago</td> 
                                <td>Total</td>    
                                <td>Glosa</td>                          
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compras as $compra)
                            <tr>                                
                                <td>{{$compra->comprobante}}</td>
                                <td>{{$compra->fecha_compra}}</td>
                                @if (is_null($compra->agente))
                                    <td></td>
                                @else
                                    <td>{{$compra->agente->nombre}}<br>{{$compra->agente->telefonos}}</td>
                                @endif   
                                <td>{{$compra->forma_pago}}</td>
                                <td>{{$compra->pago_compra - $compra->cambio_compra}}</td>
                                @if (is_null($compra->glosa)) 
                                    <td></td>
                                    <td>                                
                                        <a href="{{url('/compra/'.$compra->id)}}" class="btn btn-info btn-sm"><i class="fa fa-bars"></i> Detalle</a>
                                    </td>
                                @else
                                    <td>{{$compra->glosa}}</td>
                                    <td></td>
                                @endif
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/compra/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nueva Compra</a>
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
            $('#tcompras').DataTable({
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

