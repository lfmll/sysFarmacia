@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@include('sweetalert::alert')
@section('auth_body')
<div class="container">
    <div class="col-md-12">
        <div class="tab">
            <div class="row">
                <h6 class="input-group mb-12">Bienvenido, {{ Auth::user()->name }}</h6>
                <hr>
                <p><span class="badge badge-light">Por favor seleccione Sucursal y Punto Venta</span></p>
            </div>
            <form method="POST" action="{{url('/guardarUbicacion')}}" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-4">
                    <select id="sucursales" name="agencia_id" class="form-control" onchange="cargarPuntosVentas()" required>
                        <option value="">Seleccione una Sucursal</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-4">
                    <select id="puntosVentas" name="punto_venta_id" class="form-control" required>
                        <option value="">Seleccione un Punto de Venta</option>
                    </select>
                </div>
                <div class="input-group mb-4">                   
                    <button type="submit" class="btn btn-success">Ingresar</button>                    
                </div>
            </form>            
        </div>            
    </div>
</div>
@stop
@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    function cargarPuntosVentas()
    {
        var formData = { agencia: $('#sucursales').val() };        
        $.ajax({
            url: "{{ url('/cargarPuntosVentaU') }}",
            type: "GET",
            data: formData,
            dataType: 'json',
            success: function(data) {
                $('#puntosVentas').empty();
                $('#puntosVentas').append('<option value="">Seleccione un Punto de Venta</option>');
                $.each(data, function(key, value) {
                    $('#puntosVentas').append('<option value="'+ value.id +'">'+ value.nombre +'</option>');
                });
            },
            error: function(data) {
                if (data.status == 409) {
                    swal.fire('Error', JSON.parse(data.responseText).mensaje, 'error');                                        
                } else {
                    swal.fire('Error', 'Ocurrió un error al cargar los puntos de venta.', 'error');
                }                
            }
        });
    }
</script>
@stop