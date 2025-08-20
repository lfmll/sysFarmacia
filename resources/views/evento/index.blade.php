@extends('adminlte::page')
@section('title','Eventos_Significativos')
@section('content')
@include('sweetalert::alert')
<h4>Eventos Significativos</h4>
@foreach($eventos as $evento)
    <div class="card">
        <div class="card-header">
            <h6><b>Evento:</b> {{ $evento->id }}</h6>
            <h6><b>Fecha Inicio:</b> {{ date('d-m-Y', strtotime($evento->fechaInicioEvento))}} |<b>Fecha Fin:</b>@if(is_null($evento->fechaFinEvento)) Sin Asignar @else {{ date('d-m-Y', strtotime($evento->fechaFinEvento)) }} @endif</h6>
        </div>
        <div class="card-body">            
            <ul class="list-group list-group-horizontal">                            
                <li class="list-group-item"><b>CAFC:</b> @if(is_null($evento->cafc)) <br>No Aplica @else <br>{{$evento->cafc}} @endif</li>
                <li class="list-group-item"><b>Cod Recepcion:</b> {{ $evento->codigoRecepcion }}</li>
                <li class="list-group-item"><b>Evento:</b> <br>{{ $evento->descripcion }}</li>
                <li class="list-group-item"><b>Cant Facturas:</b> <br>{{ $evento->cantidadFacturas}}</li>
                <li class="list-group-item"><b>Estado:</b><br><span class="badge bg-secondary">{{ $evento->estado }}</span></li>
                <li class="list-group-item"><b>Acciones:</b><br>
                    <div class="btn-group d-flex" role="group">
                        <a class="btn btn-warning flex-fill" data-toggle="modal" data-target="#paqueteModal{{ $evento->id }}">Paquete</a>
                        {!! Form::open(['url'=>'/evento/'.$evento->id, 'method'=>'PATCH','class'=>'d-inline-block']) !!}
                        <a class="btn btn-success flex-fill cerrar">Validar</a>
                        {!! Form::close() !!}
                    </div>                                        
                </li>
            </ul>                        
        </div>
    </div>
    <!-- Modal Paquete Facturas -->
    <div class="modal fade" id="paqueteModal{{ $evento->id }}" tabindex="-1" role="dialog" aria-labelledby="paqueteModalLabel{{ $evento->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paqueteModalLabel{{ $evento->id }}">Paquete de Facturas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nro Factura</th>
                                <th>NIT/CI</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evento->facturas as $factura)
                                <tr>
                                    <td>{{ $factura->numeroFactura }}</td>
                                    <td>{{ $factura->numeroDocumento }}</td>
                                    <td>{{ $factura->venta->cliente->nombre }}</td>
                                    <td>{{ date('d-m-Y h:i A', strtotime($factura->fechaEmision)) }}</td>
                                    <td>{{ $factura->montoTotal }}</td>
                                    <td><span class="badge badge-pill badge-primary">{{ $factura->estado }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>    
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Paquete Facturas -->
@endforeach
<div class="card-footer">
    <a href="{{url('/evento/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Iniciar Evento</a>
</div>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

@stop
@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.cerrar').on('click', function(e) {
            e.preventDefault();
            var form = $(this).parents('form');
            
            Swal.fire({
                title: '¿Está Seguro?',
                text: "Cerrar Evento Significativo",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    form.submit();            
                }
                
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.abrir-modal').click(function(e) {
            e.preventDefault(); // Evita que el enlace recargue la página
            var id = $(this).data('id');
            $('#paqueteModal' + id).modal('show');
        });
    });
</script>
@stop

