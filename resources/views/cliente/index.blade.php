@extends('adminlte::page')

@section('title', 'Clientes')

@section('content')
@include('sweetalert::alert')
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-4">                            
                        <a href="{{url('#')}}" class="btn btn-info"><i class="fa fa-user fa-lg"></i></a>
                    </div>
                    <div class="col-sm-6">
                        <h5>Clientes</h5>    
                    </div>            
                    <div class="col-sm-2">
                        <a href="{{url('listaClientes')}}" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</a>
                    </div>                    
                </div>                                                   
            </div>
            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="tclase" class="table table-bordered">
                        <thead>
                            <tr>
                                <th valign="center">ID</th>    
                                <th valign="center">Tipo de Documento</th>            
                                <th valign="center">Nro de Documento</th>
                                <th valign="center">Complemento</th>
                                <th valign="center">Nombre Razon Social</th>
                                <!-- <th valign="center">Correo</th>
                                <th valign="center">Telefono</th>
                                <th valign="center">Direccion</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cliente as $cli)
                            <tr>
                                <td>{{$cli->id}}</td>
                                <td>{{$cli->tipo_documento}}</td>
                                <td>{{$cli->numero_documento}}</td>
                                <td>{{$cli->complemento}}</td>
                                <td>{{$cli->nombre}}</td>
                                <!-- <td>{{$cli->correo}}</td>
                                <td>{{$cli->telefono}}</td>
                                <td>{{$cli->direccion}}</td> -->
                                <td>
                                    <a href="{{url('/cliente/'.$cli->id.'/edit')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                    @include('cliente.delete',['cliente'=>$cli])                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                                      
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('/cliente/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
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
    $( ".eliminar" ).on( "click", function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        
        Swal.fire({
            title: 'Esta Seguro?',
            text: "Eliminar Cliente",
            type: 'warning',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
            // if (result.isConfirmed) {
                form.submit();            
            }
            
        });
    });
</script>    
@stop