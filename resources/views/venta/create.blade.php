@extends('adminlte::page')

@section('title', 'Venta')

@section('content')
@include('sweetalert::alert')
    {!! Form::open(['url' => '/venta', 'method' => 'POST']) !!}       
    {{Form::token()}}     
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Factura Compra-Venta</h3>                        
                </div>   
                <div class="card-body">                                                                                                                                                                                                     
                    <div class="card">
                        <div class="card-header">                            
                            <div class="form-group">
                                <div class="row">
                                    @if (is_null($evento))
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-success btn-sm"><i class="fa fa-sm fa-circle"></i> En Linea</a>                                            
                                        </div>
                                    @else
                                        <div class="col-sm-6">                                            
                                            <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-sm fa-minus-circle"></i> Fuera de Linea</a>
                                        </div>
                                    @endif                                                                            
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        {{ Form::select('actividades',$actividades, null, ['class'=>'form-control','id'=>'codigo_caeb', 'onchange'=>'catalogarActividad()'])  }}
                                    </div>
                                    <div class="col-sm-6">
                                        {{ Form::select('catalogos',$catalogos, null, ['class'=>'form-control','id'=>'codigo_producto', 'onchange'=>'catalogarProducto()'])  }}
                                    </div>                                        
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        {!! Form::label('cliente','Nombre o Razon Social: ') !!}
                                        {!! Form::hidden('cid', null, ['id'=>'cid','class'=>'form-control']) !!}
                                        <div class="input-group mb-3">
                                            {!! Form::text('cliente', null, ['id'=>'cnombre','class'=>'form-control', 'required']) !!}
                                            <div class="input-group-append">
                                                <a href="#modalBuscarCliente" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalBuscarCliente">
                                                    <i class="fa fa-lg fa-search"></i>
                                                </a>
                                            </div>
                                        </div>                                        
                                        <label hidden for="idcliente" id="ccliente">idcliente</label>
                                    </div>                                    
                                </div>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-3">
                                    <div class="row">                                                                                        
                                        <label> Accion:</label>   
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="#modalCrearCliente" class="btn btn-info form-control" data-toggle="modal" data-target="#modalCrearCliente"><i class="fa fa-lg fa-user"></i> Nuevo</a>
                                        </div>  
                                        <div class="col-md-6">
                                            <input type="button" value="Limpiar" id="btn_limpiarcl" class="btn btn-warning form-control" onclick="limpiarCliente()">
                                        </div>
                                    </div>                                                                        
                                </div>
                            </div>
                            <div class="row">
                            {!! Form::hidden('tipodoc', null, ['id'=>'ctipodoc','class'=>'form-control']) !!} 
                                <div class="col-lg-2">
                                    <div class="form-group"> 
                                        {!! Form::label('nrodoc','Nro de Documento: ') !!}   
                                        {!! Form::text('nrodoc', null, ['id'=>'cnrodoc','class'=>'form-control', 'readonly'=>true]) !!}                                         
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group">
                                        {!! Form::label('cmpl','Cmpl: ') !!}   
                                        {!! Form::text('cmpl', null, ['id'=>'ccmpl','class'=>'form-control', 'readonly'=>true]) !!}    
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                    {!! Form::label('correo','Correo: ') !!}   
                                    {!! Form::text('correo', null, ['id'=>'ccorreo','class'=>'form-control', 'readonly'=>true]) !!}     
                                    </div>
                                </div> 
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">                                        
                                        {!! Form::label('Producto', 'Producto') !!}                                          
                                        <div class="input-group mb-3">                                                                                                                                   
                                            {!! Form::text('producto', null, ['id'=>'pproducto','class'=>'form-control']) !!}
                                            <div class="input-group-append">
                                                <a href="#modalLote" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalLote">
                                                    <i class="fa fa-lg fa-receipt"></i>
                                                </a>
                                            </div>                                            
                                        </div>                                        
                                        <label style="display:none" for="loteid" id="pcodigo">idcliente</label>
                                        {!! Form::hidden('maximo', null, ['id'=>'pmaximo','class'=>'form-control','placeholder'=>'0','min'=>'0', 'oninput'=>'this.value|=0']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        {!! Form::label('Cantidad', 'Cantidad') !!}
                                        {!! Form::number('cantidad', null, ['id'=>'pcantidad','class'=>'form-control','placeholder'=>'0','min'=>'1']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        {!! Form::label('Precio', 'Precio') !!}
                                        {!! Form::number('precio', null, ['id'=>'pprecio','class'=>'form-control','placeholder'=>'0.00','min'=>'0']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        {!! Form::label('Descuento', 'Descuento') !!}
                                        {!! Form::number('descuento', null, ['id'=>'pdescuento','class'=>'form-control','placeholder'=>'0.00','min'=>'0','max'=>'100']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">  
                                        {!! Form::label('accion', 'accion',['style'=>'visibility:hidden;']) !!}
                                        <input type="button" value="Agregar" id="btn_add" class="btn btn-success form-control" onclick="agregar()">
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <div class="card-body">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <table id="detalles" class="table table-bordered table-condensed table-hover">
                                    <thead style="background-color: #2ab27b">
                                        <th class="col-sm-1"></th>
                                        <th class="col-sm-2">Codigo</th>
                                        <th class="col-sm-3">Concepto</th>
                                        <th class="col-sm-1">Cantidad</th>
                                        <th class="col-sm-2">Precio</th>
                                        <th class="col-sm-1">Descueto</th>
                                        <th class="col-sm-2">SubTotal</th>
                                    </thead>
                                    <tfoot>     
                                        <tr>
                                            <td colspan="5"></td>                                                                                
                                            <td>SubTotal: </td>                                 
                                            <td><input type="number" name="eSubTotal" id="eSubTotal" class="form-control" step="any" readonly required></td>
                                        </tr>    
                                        <tr>
                                            <td colspan="5"></td>
                                            <td>Descuento: </td>
                                            <td><input type="number" name="eDescuento" id="eDescuento" class="form-control" value="0" min="0" max="100" onchange="descontarTotal(this.value)"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td>Total: </td>
                                            <td><input type="number" name="eTotal" id="eTotal" class="form-control" value="0" min="0" step="any" readonly required></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td>GiftCard: </td>
                                            <td><input type="number" name="eGiftCard" id="eGiftCard" class="form-control" value="0" min="0" step="any" onchange="descontarGiftCard(this.value)"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td>Monto a Pagar: </td>
                                            <td><input type="number" name="eMontoPagar" id="eMontoPagar" class="form-control" step="any" readonly></td>
                                        </tr> 
                                        <tr>
                                            <td colspan="5"></td>
                                            <td>Monto Total Sujeto IVA: </td>
                                            <td><input type="number" name="eTotalIVA" id="eTotalIVA" class="form-control" step="any" readonly></td>
                                        </tr>                                        
                                    </tfoot>
                                    <tbody>
                                
                                    </tbody>
                                </table>
                            </div>                                    
                        </div> 
                        <div class="card-footer">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="checkbox form-group">                                            
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th><i class="fa fa-credit-card"></i> Forma de Pago</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                        {!! Form::number('ppago_efectivo', null, ['id'=>'ppago_efectivo','class'=>'form-control','value'=>'0', 'min'=>'0', 'onchange'=>'pagar()', 'step'=>'any']) !!}
                                                        <hr>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="forma_pago" value="1" id="chkefectivo" onchange="onchkefectivo()" checked>
                                                                <label class="form-check-label" for="chkefectivo">
                                                                    Efectivo
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                        {!! Form::number('ppago_digital', null, ['id'=>'ppago_digital','class'=>'form-control','min'=>'0', 'step'=>'any', 'readonly']) !!}
                                                        <hr>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="forma_pago" value="33" id="chkqr" onchange="onchkdigital()">
                                                                <label class="form-check-label" for="chkqr">
                                                                    QR
                                                                </label>
                                                            </div> 
                                                        </td>
                                                        <td>
                                                        {!! Form::number('ppago_tarjeta', null, ['id'=>'ppago_tarjeta','class'=>'form-control','min'=>'0', 'step'=>'any', 'readonly']) !!}
                                                        <hr>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="forma_pago" value="2" id="chktarjeta" onchange="onchktarjeta()">
                                                                <label class="form-check-label" for="chktarjeta">
                                                                    Tarjeta
                                                                </label><span><small> (Nro Tarjeta)</small></span>                                                                    
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>                                        
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="float-right">
                                            {!! Form::label('Cambio', 'Cambio') !!}
                                            {!! Form::number('cambio', 0, ['id'=>'pcambio','class'=>'form-control','placeholder'=>'0.00','min'=>'0', 'step'=>'any']) !!}
                                        </div>                                        
                                    </div>                                        
                                </div>                                                                                                                                                                                                     
                            </div>                          
                        </div>                                                                                                                                                                                                                                                                                                                                                                            
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            <div class="float-left">
                                <a type="submit" class="btn btn-default btn-lg" href="{{url('/venta')}}">Cancelar</a>    
                            </div>
                            <div class="float-right">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>  
                            </div>            
                        </div>
                    </div>      
                </div>        
            </div>     
        </div>                           
    {!! Form::close() !!}

    <!-- ********Modal Buscar Cliente*********** -->
    <div class="modal fade" id="modalBuscarCliente" role="dialog">
        <div class="modal-dialog modal-dialog-centered" style="max-width: fit-content;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Buscar Clientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> 
                </div>
                <div class="modal-body">
                    <div class="container">
                        <table id="tcliente" class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre Razon Social</th>
                                    <th>Tipo Documento</th>
                                    <th>Nro Documento</th>
                                    <th>Complemento</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td>                                        
                                        <div class="chkCliente">
                                            <input type="checkbox" name="chkCliente" class="chkCliente" value="{{$cliente->id}},{{$cliente->nombre}},{{$cliente->tipo_documento}},{{$cliente->numero_documento}},{{$cliente->complemento}},{{$cliente->correo}}">                                             
                                        </div>                                        
                                    </td>
                                    <td>{{$cliente->nombre}}</td>
                                    <td>{{$cliente->tipo_documento}}</td>
                                    <td>{{$cliente->numero_documento}}</td>
                                    <td>{{$cliente->complemento}}</td>
                                    <td>{{$cliente->correo}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="seleccionarCliente()">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ******** Modal Crear Cliente ******** -->
    <div class="modal fade" id="modalCrearCliente" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Crear Cliente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> 
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nombre o Razon Social (*)</label>
                                        <input type="text" name="ccnombre" id="ccnombre" class="form-control" required>
                                    </div>                                    
                                </div>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Tipo Documento (*)</label>                        
                                    <select name="cctipodoc" id="cctipodoc" class="form-control" required>
                                        @foreach ($tipo_documento_identidad as $tdi)
                                            <option value="{{$tdi->codigo_clasificador}}">{{$tdi->descripcion}}</option>
                                        @endforeach                                        
                                    </select>                                
                                </div>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <div class="row">                                
                                <div class="col-md-9">
                                    <label>Nro Documento (*)</label>
                                    <input type="number" min="0" name="ccnrodoc" id="ccnrodoc" class="form-control" required>
                                </div>                            
                                <div class="col-md-3">
                                    <label>Complemento</label>
                                    <input type="text" name="cccmpl" id="cccmpl" class="form-control">
                                </div>                           
                            </div> 
                        </div>                                               
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-9">
                                    <label>Correo (*)</label>
                                    <input type="email" name="cccorreo" id="cccorreo" class="form-control">
                                </div>                                                            
                                <div class="col-md-3">
                                    <label>Telefono</label>
                                    <input type="number" min="0" name="cctelf" id="cctelf" class="form-control">
                                </div>
                            </div>                                                                               
                        </div>                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Direccion</label>
                                    <input type="text" name="ccdireccion" id="ccdireccion" class="form-control">
                                </div>
                            </div>                            
                        </div>                            
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="crearCliente">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ***************Modal Buscar Producto ******************** -->
    <div class="modal fade" id="modalLote" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Buscar Produtos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>                                        
                </div>
                <div class="modal-body">                                                                      
                    <div>
                        <br>
                        <table id="tlotem" class="table table-hover">
                            <thead>
                                <tr>
                                    <th></th> <!-- Para el botón de expansión -->
                                    <th>Seleccionar Medicamento</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="seleccionar()">Aceptar</button>
                </div>
            </div>
        </div>      
    </div>
    
@stop

@section('js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        $('#tcliente').DataTable({
            "responsive" : false,
            "paging": true,
            "lengthMenu": [4, 8, "All"],
            "searching": true,
            "ordering": false,
            "info": false,
            "language" : "{{ asset('js/es-ES.json') }}",
            "autoWidth": true
        });
    });

    $(function () {
        $('#tlotem').DataTable({            
            "responsive" : false,
            "paging": true,
            "lengthMenu": [4, 8, "All"],
            "searching": true,
            "ordering": false,
            "info": false,
            "language" : "{{ asset('js/es-ES.json') }}",
            "autoWidth": true
        });
    });   
  
    $("input:checkbox").on('click', function() {
        var $box = $(this);
        if ($box.is(":checked")) {
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            $(group).prop("checked", false);
            $box.prop("checked", true);
        } else {
            $box.prop("checked", false);
        }
    });

    let detalle=[];

    $('#modalLote.save').click(function (e) {        
        e.preventDefault();
        addImage(5);
        $('#modalLote').modal('hide');
        return false;
    });

    $('#modalBuscarCliente.save').click(function (e) {
        e.preventDefault();
        addImage(5);
        $('#modalBuscarCliente').modal('hide');
        return false;
    });

    $('#modalCrearCliente.save').click(function (e) {
        e.preventDefault();
        addImage(5);
        $('#modalCrearCliente').modal('hide');
        return false;
    })

    $('#crearCliente').click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            id: $('#cid').val(),
            estado: encodeURIComponent('A'),
            tipo_documento: $('#cctipodoc').val(),
            numero_documento: $('#ccnrodoc').val(),
            complemento: $('#cccmpl').val(),
            nombre: $('#ccnombre').val(),
            correo: $('#cccorreo').val(),
            telefono: $('#cctelf').val(),
            direccion: $('#ccdireccion').val(),
        };        
        var type="POST";
        $.ajax({
            type: type,
            url: '{{url("/cliente")}}',
            data: formData,
            dataType: 'json',
            success: function(data){
                $('#cid').val(data.id)
                $('#cnombre').val(data.nombre);
                $('#ctipodoc').val(data.tipo_documento);
                $('#cnrodoc').val(data.numero_documento);
                $('#ccmpl').val(data.complemento);
                $('#ccorreo').val(data.correo);
                swal.fire("Registro Realizado","Cliente Nuevo: "+data.numero_documento,"success");
            },
            error: function (json) {
                if (json.status==409) {
                    swal.fire("Error", JSON.parse(json.responseText).mensaje, "error"); 
                } else {
                    swal.fire("Error","Error al registrar","error")  ;
                }
            }
        });
    })
    
    function catalogarActividad()
    {
        var formData = { codCaeb: $('#codigo_caeb').val(),};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: '{{url("/catalogarA")}}',
            data: formData,
            dataType: 'json',
            success: function(data) {
                let n = data.length;
                $('#codigo_producto').empty();
                var fila = "";
                for (let i = 0; i < n; i++) {
                    fila += "<option value="+data[i].codigo_producto+">"+data[i].descripcion_producto+"</option>"                                     
                }
                $("#codigo_producto").append(fila);
                catalogarProducto();
            },
            error: function (data) {
                if (data.status==409) {
                    $('#codigo_producto').empty();
                    swal.fire("Error",JSON.parse(data.responseText).mensaje,"error");    
                } else {
                    swal.fire("Error","Error en la consulta","error");
                }                
            }
        });
    }
    
    function catalogarProducto()
    {      
        var formData = {
            codCaeb: $('#codigo_caeb').val(),
            codProducto: $('#codigo_producto').val(),
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: '{{url("/catalogarP")}}',
            data: formData,
            dataType: 'json',
            success: function(data) {  
                // Agrupar los lotes por nombre comercial  
                const grupos = {};
                data.forEach(lote => {
                    if (!grupos[lote.nombre_comercial]) {
                        grupos[lote.nombre_comercial] = [];
                    }
                    grupos[lote.nombre_comercial].push(lote);
                })            
                // Construir las filas principales para cada grupo
                if ($.fn.DataTable.isDataTable('#tlotem')) {
                    $('#tlotem').DataTable().clear().destroy();
                }
                $("#tlotem tbody").empty();

                let filas = "";
                Object.keys(grupos).forEach(nombreComercial => {                                        
                    filas += `
                        <tr class="grupo table-secondary border border-2 border-dark rounded" data-nombre="${nombreComercial}">
                            <td class="dt-control">
                                <button class="btn-toggle">
                                    <i class="fas fa-angle-right" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td colspan="2"><strong>${nombreComercial}</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        `;                    
                });
                $("#tlotem tbody").html(filas);
                // Inicializar DataTable sobre los grupos
                const tablal = $('#tlotem').DataTable({
                    paging: true,
                    ordering: false,
                    searching: true,
                    language: { url: "{{ asset('js/es-ES.json') }}" }
                });

                // Evento para abrir/cerrar child row
                $('#tlotem tbody').on('click', 'td.dt-control', function () {
                    const tr = $(this).closest('tr');
                    const nombre = tr.data('nombre');
                    const row = tablal.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        const lotes = grupos[nombre];
                        let contenido = '<table class="table table-sm" class="display stripe hover row-border"><thead><tr><th></th><th>Lote</th><th>Medicamento</th><th>Vencimiento</th><th>Cantidad</th><th>Precio</th><th>Laboratorio</th></tr></thead><tbody>';
                        lotes.forEach(lote => {
                            contenido += `
                                <tr id="${lote.id}">
                                    <td>
                                        <div class="chk">
                                            <input type="radio" name="chk" class="chk" id="${lote.id}" value="${lote.id},${lote.nombre_comercial},${lote.cantidad},${lote.precio_venta}">
                                        </div>
                                    </td>
                                    <td>${lote.numero}</td>
                                    <td>${lote.nombre_comercial}</td>
                                    <td>${lote.fecha_vencimiento}</td>
                                    <td>${lote.cantidad}</td>
                                    <td>${lote.precio_venta}</td>
                                    <td>${lote.nombre}</td>
                                </tr>
                            `;
                        });
                        contenido += '</tbody></table>';
                        row.child(contenido).show();
                        tr.addClass('shown');
                    }
                });
                // Cliente (si aplica)
                if ($.fn.DataTable.isDataTable('#tcliente')) {
                    $('#tcliente').DataTable().clear().destroy();
                }
                $('#tcliente').DataTable({
                    language: { url: "{{ asset('js/es-ES.json') }}" }
                });
            },
            error: function (data) {
                if (data.status==409) {
                    $("#tbodyLotem tr").empty();
                    swal.fire("Alerta",JSON.parse(data.responseText).mensaje,"warning");    
                } else {
                    swal.fire("Error","Error en la consulta","error");
                }                
            }
        });
    }
    function limpiar(){        
        document.getElementById("pcantidad").value = "";
        document.getElementById("pprecio").value = "";
        document.getElementById("pproducto").value = "";
        document.getElementById("pdescuento").value = "";
        document.getElementById("pcodigo").innerText = "";        
        $('input[name="chk"]').prop('checked', false);
    }

    function limpiarCliente(){
        document.getElementById("cid").value = "";
        document.getElementById("cnombre").value = "";
        document.getElementById("ctipodoc").value = "";
        document.getElementById("cnrodoc").value = "";
        document.getElementById("ccmpl").value = "";
        document.getElementById("ccorreo").value = "";
    }

    function camposValidos(concepto, cantidad, precio, descuento){
        if (concepto=="" || cantidad=="" || precio=="" || cantidad < 0 || precio < 0 || descuento < 0) 
            return false;
        else return true;        
    }

    function excedeLimite(cantidad, cant_max){
        if (parseInt(cantidad) > parseInt(cant_max)) 
            return true;
        else return false;
    }

    function descuentoEsMayor(calculo, descuento){
        if (Number(descuento) > Number(calculo))
            return true;
        else return false;
    }

    function obtenerSubtotal(fila){
        const [, , cantidad, precio, descuento] = fila;
        const c = parseFloat(cantidad) || 0;
        const p = parseFloat(precio) || 0;
        const d = parseFloat(descuento) || 0;
        console.log("Calculando subtotal:", { cantidad, precio, descuento });

        return parseFloat((c * p - d).toFixed(2));
    }

    function actualizarTotalesVisuales() {
        let subTotales = 0;
        for (let i = 0; i < detalle.length; i++) {
            subTotales += obtenerSubtotal(detalle[i]);
        }

        const descuentoGlobal = Number(document.getElementById('eDescuento').value) || 0;
        const descuentoGiftCard = Number(document.getElementById('eGiftCard').value) || 0;

        if (descuentoEsMayor(subTotales, descuentoGlobal)) {
            Swal.fire({
                icon: "error",
                title: "Datos No Válidos",
                text: "Error: el Descuento global no puede exceder el Total Fiscal"
            });
            return;
        }
        subTotales = parseFloat(subTotales.toFixed(2));
        totales = parseFloat((subTotales - descuentoGlobal).toFixed(2));
        
        if (descuentoEsMayor(totales, descuentoGiftCard)) {
            Swal.fire({
                icon: "error",
                title: "Datos No Válidos",
                text: "Error: el Descuento por GiftCard no puede exceder el Subtotal"
            });
            return;
        }

        const montoPagar = parseFloat((totales - descontarGiftCard).toFixed(2));
        
        document.getElementById('eSubTotal').value = subTotales.toFixed(2);
        document.getElementById('eTotal').value = totales.toFixed(2);
        document.getElementById('eMontoPagar').value = montoPagar;
        document.getElementById('eTotalIVA').value = totales.toFixed(2);
    }

    function agregar() {
        const arreglo = document.getElementById("pcodigo").innerText;
        const arr = arreglo.split(',');
        const codigo = arr[0];
        const cant_max = arr[2];

        const concepto = document.getElementById("pproducto").value;
        const cantidad = parseInt(document.getElementById("pcantidad").value);
        const precio = parseFloat(document.getElementById("pprecio").value);
        const descuento = parseFloat(document.getElementById("pdescuento").value) || 0;

        if (!camposValidos(concepto, cantidad, precio, descuento)) {
            Swal.fire({ icon: "error", title: "Alerta", text: "Campos inválidos" });
            return;
        }

        if (excedeLimite(cantidad, cant_max)) {
            Swal.fire({ icon: "error", title: "Alerta", text: "Cantidad excede el stock" });
            return;
        }

        let calculo = cantidad * precio;
        if (descuentoEsMayor(calculo, descuento)) {
            Swal.fire({ icon: "error", title: "Alerta", text: "Descuento excede el importe" });
            return;
        }

        calculo = parseFloat((calculo - descuento).toFixed(2));

        [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {
            idlote = cb.id;
            $('#' + idlote).fadeOut('slow');
        });
        console.log("Valores agregados:", { cantidad, precio, descuento });
        detalle.push([idlote, codigo, cantidad, precio, descuento]);
        const index = detalle.length - 1;
        const subtotal = obtenerSubtotal(detalle[index]);

        const fila = '<tr class="detalle" id="fila' + index + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminar(' + index + ');"><i class="fas fa-times-circle"></i></button></td>' +
            '<td hidden><input type="number" id="dcantMax' + index + '" name="dcantMax[]" class="form-control input-sm" value="' + cant_max + '"></td>' +
            '<td><input type="number" id="dcodigo' + index + '" name="dcodigo[]" class="form-control input-sm" value="' + codigo + '" readonly required></td>' +
            '<td><input type="text" id="dconcepto' + index + '" name="dconcepto[]" class="form-control input-sm" value="' + concepto + '" readonly required></td>' +
            '<td><input type="number" id="dcantidad' + index + '" name="dcantidad[]" class="form-control input-sm" value="' + cantidad + '" min="1" max="' + cant_max + '" step="1" onchange="recalcularImporte(' + index + ');" required></td>' +
            '<td><input type="number" id="dprecio' + index + '" name="dprecio[]" class="form-control input-sm" value="' + precio + '" min="0" step="0.01" onchange="recalcularImporte(' + index + ');" required></td>' +
            '<td><input type="number" id="ddescuento' + index + '" name="ddescuento[]" class="form-control input-sm" value="' + descuento + '" min="0" step="0.01" onchange="recalcularImporte(' + index + ');"></td>' +
            '<td><input type="number" id="dsubtotal' + index + '" name="dsubtotal[]" class="form-control input-sm" value="' + subtotal + '" min="0" step="0.01" readonly></td>' +
            '</tr>';

        $('#detalles').append(fila);
        actualizarTotalesVisuales();
        limpiar();
    }

    function recalcularImporte(index) {
        console.log(detalle);
        const concepto = document.getElementById('dconcepto' + index).value;
        const cantidad = parseInt(document.getElementById('dcantidad' + index).value);
        const precio = Number(document.getElementById('dprecio' + index).value);
        const descuento = Number(document.getElementById('ddescuento' + index).value);
        const cant_max = parseInt(document.getElementById('dcantMax' + index).value);

        if (!camposValidos(concepto, cantidad, precio, descuento)) {
            Swal.fire({
                icon: "error",
                title: "Datos No Válidos",
                text: "Error: Los Campos no pueden estar vacíos o con valores negativos"
            });
            return;
        }

        if (excedeLimite(cantidad, cant_max)) {
            Swal.fire({
                icon: "error",
                title: "Datos No Válidos",
                text: "Error: la cantidad excede el stock del lote"
            });
            return;
        }

        const bruto = cantidad * precio;
        if (descuentoEsMayor(bruto, descuento)) {
            Swal.fire({
                icon: "error",
                title: "Datos No Válidos",
                text: "Error: el Descuento no puede exceder el importe"
            });
            return;
        }

        // Actualizar la fila en la matriz fiscal
        const codigo = document.getElementById('dcodigo' + index).value;
        detalle[index] = [idlote, codigo, cantidad, precio, descuento];

        // Calcular subtotal dinámico
        const calculo = obtenerSubtotal(detalle[index]);
        document.getElementById('dsubtotal' + index).value = calculo.toFixed(2);

        actualizarTotalesVisuales();
    }
      
    function eliminar(index) {
        const fila = detalle[index];
        const idlote = fila[0]; // ✅ primer campo es el id visual

        detalle.splice(index, 1);
        $('#fila' + index).remove();
        actualizarTotalesVisuales();

        const total = parseFloat(document.getElementById('eTotal').value) || 0;
        if (total === 0) limpiarMetodosPago();

        // ✅ Reactivar el ítem en el modal
        $('#' + idlote).fadeIn('slow');
    }
    
    function seleccionarCliente(){
        [].forEach.call(document.querySelectorAll('input[name="chkCliente"]:checked'), function(cb) {
            document.getElementById('ccliente').innerText=cb.value;            
        });
        cliente=document.getElementById("ccliente").innerText;
        cl=cliente.split(',');
        id=cl[0];
        nombre=cl[1];
        tipodoc=cl[2];
        nrodoc=cl[3];
        cmpl=cl[4];
        correo=cl[5];
        document.getElementById('cid').value        = id;
        document.getElementById('cnombre').value    = nombre;
        document.getElementById('ctipodoc').value   = tipodoc;
        document.getElementById('cnrodoc').value    = nrodoc;
        document.getElementById('ccmpl').value      = cmpl;
        document.getElementById('ccorreo').value    = correo;        
    }

    function seleccionar(){          
        [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {            
            document.getElementById('pcodigo').innerText=cb.value;                        
        });
        
        arreglo = document.getElementById("pcodigo").innerText;        
        arr = arreglo.split(',')
        producto = arr[1]; 
        maximo = arr[2];       
        precio_venta = arr[3];
        document.getElementById('pprecio').value = precio_venta;
        document.getElementById('pproducto').value = producto;   
        document.getElementById('pmaximo').value = maximo;
    }
    //Metodos de Pago
    function onchkefectivo(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = false;
        document.getElementById("pcambio").readOnly        = false;
        document.getElementById("ppago_digital").readOnly = true;
        document.getElementById("ppago_tarjeta").readOnly  = true;        
    }
    function onchkdigital(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = true;
        document.getElementById("pcambio").readOnly        = true;
        document.getElementById("ppago_digital").readOnly = false;
        document.getElementById("ppago_tarjeta").readOnly  = true;        
    }
    function onchktarjeta(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = true;
        document.getElementById("pcambio").readOnly        = true;
        document.getElementById("ppago_digital").readOnly = true;
        document.getElementById("ppago_tarjeta").readOnly  = false;        
    }
    function onchkotros(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = true;
        document.getElementById("pcambio").readOnly        = true;
        document.getElementById("ppago_digital").readOnly = true;
        document.getElementById("ppago_tarjeta").readOnly  = true;
    }

    function limpiarMetodosPago(){
        document.getElementById("ppago_efectivo").value = "";
        document.getElementById("ppago_digital").value = "";
        document.getElementById("ppago_tarjeta").value  = "";
        document.getElementById("pcambio").value        = "";
    }

    function pagar(){
        pago=document.getElementById('ppago_efectivo').value;
        cambio = pago - total;
        document.getElementById('pcambio').value = cambio.toFixed(2);
    }

    function descontarTotal(val){
        sub=document.getElementById('eSubTotal').value;
        if (descuentoEsMayor(sub, val)){
            Swal.fire({
                icon: "error",
                title: "Alerta",
                text: "El descuento no puede exceder el subtotal"
            });
            return;
        }      
        sub = parseFloat(sub) - parseFloat(val); 
        sub.toFixed(2);
        document.getElementById("eTotal").value = sub;
        document.getElementById("eMontoPagar").value = sub;
        document.getElementById("eTotalIVA").value = sub;
    }

    function descontarGiftCard(val){
        sub=document.getElementById('eSubTotal').value;
        if (descuentoEsMayor(sub, val)) {
            Swal.fire({
                icon: "error",
                title: "Alerta",
                text: "El descuento no puede exceder el subtotal"
            });
            return;
        }
        sub = parseFloat(sub) - parseFloat(val);
        sub.toFixed(2);
        document.getElementById("eMontoPagar").value = sub;
    }
</script>

@stop


