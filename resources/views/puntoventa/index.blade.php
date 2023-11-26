@extends('adminlte::page')

@section('title', 'PuntoVenta')

@section('content')
@include('sweetalert::alert')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Punto de Venta</h3>                                                    
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tagente" class="table table-bordered">
                        <thead>
                            <tr>                            
                                <td>ID</td>    
                                <td>Descripcion</td>            
                                <td>Sucursal</td>
                                <td>Empleado</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($puntoventas as $puntoventa)
                            <tr>
                                <td>{{$puntoventa->id}}</td>
                                <td>{{$puntoventa->descripcion}}</td>
                                <td>{{$puntoventa->agencia_id->nombre}}</td>
                                <td>{{$puntoventa->user_id->name}}</td>
                                <td>
                                    <!-- <a href="{{url('/agente/'.$agente->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a> -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/puntoventa/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>    
    </div>    
</div>
@stop