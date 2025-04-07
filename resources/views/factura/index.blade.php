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
                                    <th valign="center">Nombre Razon Social</th>
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
                                    <td>{{$fact->cuf}}</td>                                 
                                    <td>{{$fact->nitEmisor}}</td>
                                    <td>{{$fact->razonSocialEmisor}}</td>
                                    <td>{{$fact->fechaEmision}}</td>  
                                    <td>{{$fact->montoTotal}}</td>
                                    <td>{{$fact->estado}}</td>
                                    @if ($fact->estado == 'VALIDADA')
                                    <td>
                                        <a href="{{url('verSIAT/'.$fact->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-laptop"></i> Ver</a>
                                        <a href="{{url('facturaCarta/'.$fact->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF</a>
                                        <a href="{{url('facturaRollo/'.$fact->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF Rollo</a>
                                        <a href="{{url('generarXML/'.$fact->id)}}" class="btn btn-success btn-sm"><i class="fa fa-file-code"></i> XML</a>
                                        <a href="{{url('enviarCorreo/'.$fact->id)}}" class="btn btn-info btn-sm" onClick="loading()"><i class="fa fa-envelope"></i> Notificacion</a>                                        
                                        <a><button type="submit" class="btn btn-danger btn-sm" onclick="anularFactura({{$fact->id}})"><i class="fa fa-trash"></i> Anular</button></a>
                                        <div id="loading" class="loading" onClick="hideSpinner()">                                          
                                          Loading&#8230;     
                                        </div>                                      
                                    </td>
                                    @elseif ($fact->estado == 'ANULADA')
                                    <td>
                                      <a><button type="submit" class="btn btn-info btn-sm" onclick="revertirAnulacionFactura({{$fact->id}})"><i class="fa fa-undo"></i> Revertir Anulacion</button></a>                                      
                                    </td>
                                    @else
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
                <section id="loading">
                    <div id="loading-content"></div>
                </section>
                <div class="card-footer">                    
                    <a href="{{url('/factura/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>    
        </div>    
    </div>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
    <style>
        div.card-body{            
            overflow: auto;
            white-space: nowrap;
        }
    </style>
    <style type="text/css">
      .swal2-radio {
        display: grid !important;
        text-align: left !important;
      }

      .loading {
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        visibility: hidden;
      }

      /* Transparent Overlay */
      .loading:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.3);
      }

      /* :not(:required) hides these rules from IE9 and below */
      .loading:not(:required) {
        /* hide "loading..." text */
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
      }

      .loading:not(:required):after {
        content: '';
        display: block;
        font-size: 10px;
        width: 1em;
        height: 1em;
        margin-top: -0.5em;
        -webkit-animation: spinner 1500ms infinite linear;
        -moz-animation: spinner 1500ms infinite linear;
        -ms-animation: spinner 1500ms infinite linear;
        -o-animation: spinner 1500ms infinite linear;
        animation: spinner 1500ms infinite linear;
        border-radius: 0.5em;
        -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
      }

      /* Animation */

      @-webkit-keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
      @-moz-keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
      @-o-keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
      @keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
    </style>  
@stop
@section('js')
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
<script type="text/javascript">
    var loadingDiv = document.getElementById('loading');

    function loading(){
      myVar = setTimeout(showSpinner, 3000);
      myVar = setTimeout(hideSpinner, 7000);
    }

    function showSpinner() {
      loadingDiv.style.visibility = 'visible';    
    }

    function hideSpinner() {
    loadingDiv.style.visibility = 'hidden';
    }     
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