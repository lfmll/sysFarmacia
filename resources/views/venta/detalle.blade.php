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
                                    <td>ID</td>    
                                    <td>Cantidad</td>
                                    <td>Precio Unitario</td>
                                    <td>SubTotal</td>                                                                                                     
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detalleventas as $detalleventa)
                                <tr>
                                    <td>{{$detalleventa->id}}</td>
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