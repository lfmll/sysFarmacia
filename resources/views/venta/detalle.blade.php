@extends('adminlte::page')

@section('title', 'Ventas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalle de Ventas</h3>                                                    
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
                                @foreach($detalleventas as $detalleventa)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    @if (!is_null($detalleventa->lote->medicamento))
                                        <td>{{$detalleventa->lote->medicamento->nombre_comercial}}</td>
                                    @endif
                                    @if (!is_null($detalleventa->lote->insumo))
                                        <td>{{$detalleventa->lote->insumo->nombre}}</td>
                                    @endif
                                    @if (!is_null($detalleventa->lote->producto))
                                        <td>{{$detalleventa->lote->producto->nombre}}</td> 
                                    @endif
                                    <td>{{$detalleventa->cantidad}}</td>
                                    <td>{{$detalleventa->precio_venta}}</td>
                                    <td>{{$detalleventa->cantidad * $detalleventa->precio_venta}}</td>                            
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