@extends('adminlte::page')

@section('title', 'Compra')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalle de Compra</h3>                                                    
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tventa" class="table table-bordered">
                            <thead style="background-color: #2ab27b">
                                <tr>
                                    <th>No</th>
                                    <th>Concepto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>SubTotal</th>                                                                                                     
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detallecompras as $detallecompra)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    @if (!is_null($detallecompra->lote->medicamento))
                                        <td>{{$detallecompra->lote->medicamento->nombre_comercial}}</td>
                                    @endif
                                    @if (!is_null($detallecompra->lote->insumo))
                                        <td>{{$detallecompra->lote->insumo->nombre}}</td>
                                    @endif
                                    @if (!is_null($detallecompra->lote->producto))
                                        <td>{{$detallecompra->lote->producto->nombre}}</td> 
                                    @endif                                    
                                    <td>{{$detallecompra->cantidad}}</td>
                                    <td>{{$detallecompra->precio_compra}}</td>
                                    <td>{{$detallecompra->cantidad * $detallecompra->precio_compra}}</td>                            
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
            </div>    
        </div>    
    </div>
@endsection