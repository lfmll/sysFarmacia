@extends('adminlte::page')
@section('title', 'Nota Credito-Debito')
@section('content')
@include('sweetalert::alert')
    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                <h5 style="text-align: center;">Notas Credito-Debito</h5>                                                   
                </div>
                <div class="card-body">                    
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tnota" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th valign="center">Nro</th>    
                                    <th valign="center">Codigo</th>            
                                    <th valign="center">Documento</th>
                                    <th valign="center">Cliente</th>
                                    <th valign="center">Fecha Emision</th>
                                    <th valign="center">Monto Total</th>
                                    <th valign="center">Estado</th>
                                    <th valign="center">Opciones</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notas as $nota)
                                <tr>
                                    <td>{{$nota->numeroNotaCreditoDebito}}</td>
                                    <td><span class="badge badge-light">{{$nota->factura->cuf}}</span></td>                                 
                                    <td>{{$nota->factura->nitEmisor}}</td>
                                    <td>{{$nota->factura->nombreRazonSocial}}</td>
                                    <td>{{ date('d-m-Y h:i A', strtotime($nota->fechaEmision)) }}</td>  
                                    <td>{{$nota->montoTotal}}</td>
                                    
                                    @if ($nota->estado == 'VALIDADA')
                                    <td><span class="badge badge-pill badge-success">{{$nota->estado}}</span></td>
                                    <td>
                                        <a href="{{url('verSIATNota/'.$nota->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-laptop"></i> Ver</a>
                                        <a href="{{url('notaAjustePDF/'.$nota->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF</a>
                                        
                                        <a href="{{url('generarXMLNota/'.$nota->id)}}" class="btn btn-success btn-sm"><i class="fa fa-file-code"></i> XML</a>
                                        <!-- <a href="{{url('enviarCorreoNota/'.$nota->id)}}" class="btn btn-info btn-sm" onClick="loading()"><i class="fa fa-envelope"></i> Notificacion</a>                                         -->
                                        <button type="button" class="btn btn-danger btn-sm" onclick="anularNota('{{$nota->id}}')"><i class="fa fa-trash"></i> Anular</button>                                                                             
                                    </td>
                                    @elseif ($nota->estado == 'ANULADA')

                                    <td><span class="badge badge-pill badge-danger">{{$nota->estado}}</span></td>
                                    <td>
                                      <a><button type="submit" class="btn btn-info btn-sm" onclick="revertirAnulacionNota('{{$nota->id}}')"><i class="fa fa-undo"></i> Revertir Anulacion</button></a>   
                                    </td>
                                    @else
                                    <td><span class="badge badge-pill badge-warning">{{$nota->estado}}</span></td>
                                    <td>                                  
                                      <a href="{{url('emitirNota/'.$nota->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-receipt"></i> Emitir</a>     
                                    </td>
                                    @endif  
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/notaAjuste/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>
        </div>    
    </div> 
@stop
@section('js') 
<script>
    $(function(){
        $('#tnota').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    'next': 'Siguiente',
                    'previous': 'Anterior'
                }
            },
            "responsive" : false,
            "paging": true,
            "lengthMenu": [4, 8, "All"],
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false
        });
    });
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function anularNota(id){
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: '¿Está seguro de anular la Nota Crédito-Débito?',
            text: "Motivo",
            icon: 'info',
            input: 'radio',
            inputOptions: {
                '2': 'Nota de Crédito-Débito mal Emitida',
                '3': 'Datos Emisión Incorrectos',
                '4': 'Factura o Nota de Crédito-Débito devuelta'
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'Debe seleccionar un motivo de anulación';
                }  
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                motivo = result.value;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('anularNotaAjuste') }}" + '/' + id,
                    type: "POST",
                    data: {'_method': 'DELETE','_token': csrf_token, motivo},
                    success: function(data) {
                        Swal.fire({                            
                            title: 'Operación Exitosa',
                            text: data.message,
                            icon: 'success'
                        });
                        setTimeout(function(){
                            window.location.reload();
                        }, 4000);
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la Operación',
                            text: data.responseJSON.message
                        });
                    }
                });
            }
        });
    }

    function revertirAnulacionNota(id){
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: '¿Está seguro de revertir la anulación de la Nota Crédito-Débito?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, revertir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/revertirAnulacionNotaAjuste') }}" + '/' + id,
                    type: "GET",
                    success: function(data) {
                        Swal.fire({                            
                            title: 'Operación Exitosa',
                            text: data.message,
                            icon: 'success'
                        });
                        setTimeout(function(){
                            window.location.reload();
                        }, 4000);
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la Operación',
                            text: data.responseJSON.message
                        });
                    }
                });
            }
        });
    }
</script>
@endsection

