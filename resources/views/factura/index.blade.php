@extends('adminlte::page')

@section('title', 'Factura')

@section('content')
@include('sweetalert::alert')
    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header">
                <h5 style="text-align: center;">Facturas</h5>                                                   
                </div>
                <div class="card-body">                    
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="tfactura" class="table table-bordered">
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
                                @foreach($factura as $fact)
                                <tr>
                                    <td>{{$fact->numeroFactura}}</td>
                                    <td><span class="badge badge-light">{{$fact->cuf}}</span></td>                                 
                                    <td>{{$fact->nitEmisor}}</td>
                                    <td>{{$fact->venta->cliente->nombre}}</td>
                                    <td>{{ date('d-m-Y h:i A', strtotime($fact->fechaEmision)) }}</td>  
                                    <td>{{$fact->montoTotal}}</td>
                                    
                                    @if ($fact->estado == 'VALIDADA')
                                    <td><span class="badge badge-pill badge-success">{{$fact->estado}}</span></td>
                                    <td>
                                        <a href="{{url('verSIAT/'.$fact->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-laptop"></i> Ver</a>
                                        <a href="{{url('facturaCarta/'.$fact->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF</a>
                                        <a href="{{url('facturaRollo/'.$fact->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF Rollo</a>
                                        <a href="{{url('generarXML/'.$fact->id)}}" class="btn btn-success btn-sm"><i class="fa fa-file-code"></i> XML</a>
                                        <a href="{{url('enviarCorreo/'.$fact->id)}}" class="btn btn-info btn-sm" onClick="loading()"><i class="fa fa-envelope"></i> Notificacion</a>                                        
                                        <button type="button" class="btn btn-danger btn-sm" onclick="anularFactura('{{$fact->id}}')"><i class="fa fa-trash"></i> Anular</button>                                                                             
                                        @if($fact->enPlazoNota)
                                        <a href="{{url('notaAjuste/create/'.$fact->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-file-alt"></i> Nota Ajuste</a>
                                        @endif
                                    </td>
                                    @elseif ($fact->estado == 'ANULADA')
                                    <td><span class="badge badge-pill badge-danger">{{$fact->estado}}</span></td>
                                    <td>
                                      <a><button type="submit" class="btn btn-info btn-sm" onclick="revertirAnulacionFactura('{{$fact->id}}')"><i class="fa fa-undo"></i> Revertir Anulacion</button></a>                                      
                                    </td>
                                    @else
                                    <td><span class="badge badge-pill badge-warning">{{$fact->estado}}</span></td>
                                    <td>                                      
                                      <a href="{{url('emitirFactura/'.$fact->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-receipt"></i> Emitir</a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
                <div class="card-footer">                    
                    <a href="{{url('/factura/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>    
        </div>    
    </div>
@stop

@section('css')
    <style>
        div.card-body{            
            overflow: auto;
            white-space: nowrap;
        }
    </style>     
@stop
@section('js')
<script>
  $(function(){
    $('#tfactura').DataTable({
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
  })
</script>
<script type="text/javascript">  
    $("#postForm").submit(function(e){
        e.preventDefault();  
        $.ajax({
            url: "https://jsonplaceholder.typicode.com/posts",
            type: "POST",
            data: {
                title: $("input[name='title']").val(),
                body: $("textarea[name='body']").val()
            },
            dataType: 'json',
            success: function (result) {
                console.log(result);
            }
        });
    });
      
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
  function anularFactura(id)
  {
    var csrf_token=$('meta[name="csrf_token"]').attr('content');
    swal.fire({      
      title: '¿Desea Anular esta Factura?',
      text: "Motivo:",
      icon: 'info',
      input: 'radio',
      inputOptions: {
        '1': 'Factura Mal Emitida',
        '3': 'Datos Emisión Incorrectos',
        '4': 'Factura o Nota de Credito-Debito Devuelta'
      },
      inputValidator: (value) => {
        if (!value) {
          return "Debe seleccionar el Motivo de Anulación";
        }
      },      
      showCancelButton: true,
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, Anular',
      cancelButtonColor: '#d33',  
      confirmButtonColor: '#3085d6'

    }).then(result => {
      if (result.value) {
        motivo = result.value;
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
          url:"{{url('factura')}}"+'/'+id,
          type: "POST",
          data: {'_method' : 'DELETE', '_token' :csrf_token, motivo},
          success: function(data){
            Swal.fire({
              title: 'Operación realizada exitosamente',
              text: data.message,
              icon: 'success'
            });
            setTimeout(function(){
              window.location.reload();
            }, 4000);
          },
          error: function(data){
            Swal.fire({
              title: 'Error en la Operación',
              text: data.responseJSON.message,
              icon: 'error'
            });
          }
        });
      }
      
    });
  }
</script>
<script type="text/javascript">
  function revertirAnulacionFactura(id)
  {
    swal.fire({
      title: '¿Desea Revertir la Anulación de esta Factura?',
      showCancelButton: true,
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, Revertir',
      cancelButtonColor: '#d33',  
      confirmButtonColor: '#3085d6'
    }).then(result => {
      if (result.value) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "GET",
          url: "{{url('/revertirAnulacionFactura')}}"+'/'+id,
          success: function(data){
            Swal.fire({
              title: 'Operación realizada exitosamente',
              text: data.message,
              icon: 'success'
            });
            setTimeout(function(){
              window.location.reload();
            }, 4000);
          },
          error: function(data){
            console.log(data);
            Swal.fire({
              title: 'Error en la Operación',
              text: data.responseJSON.message,
              icon: 'error'
            });
          }
        });       
      }      
    });
  }
</script>
@stop