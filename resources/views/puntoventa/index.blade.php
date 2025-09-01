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
                                <th>ID</th>    
                                <th>Nombre</th>            
                                <th>Sucursal</th>
                                <th>Empleado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($puntoventas as $puntoventa)
                            <tr>
                                <td>{{$puntoventa->id}}</td>
                                <td>{{$puntoventa->nombre}}</td>
                                <td>{{$puntoventa->agencia->nombre}}</td>
                                <td>{{$puntoventa->name}}</td>
                                <td>
                                    @include('puntoventa.delete',['puntoventa'=>$puntoventa])
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
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(".eliminar").on('click', function(e){
            e.preventDefault();
            var form = $(this).parents('form');
            Swal.fire({
                title: '¿Está seguro de cerrar el Punto de Venta?',
                text: "Punto de Venta: "+$(this).data('nombre'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    form.submit();            
                }
            })
        });
    </script>
@stop