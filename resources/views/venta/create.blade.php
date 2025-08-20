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
                                        <label  for="loteid" id="pcodigo">idcliente</label>
                                        {!! Form::hidden('maximo', null, ['id'=>'pmaximo','class'=>'form-control','placeholder'=>'0','min'=>'0', 'oninput'=>'this.value|=0']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        {!! Form::label('Cantidad', 'Cantidad') !!}
                                        {!! Form::number('cantidad', null, ['id'=>'pcantidad','class'=>'form-control','placeholder'=>'0','min'=>'0', 'oninput'=>'this.value|=0']) !!}
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
                                        <th class="col-sm-1">Codigo</th>
                                        <th class="col-sm-5">Concepto</th>
                                        <th class="col-sm-1">Cantidad</th>
                                        <th class="col-sm-2">P. Unitario</th>
                                        <th class="col-sm-2">SubTotal</th>
                                    </thead>
                                    <tfoot>     
                                        <tr>
                                            <td colspan="4"></td>                                                                                
                                            <td>SubTotal: </td>                                 
                                            <td><input type="number" name="eSubTotal" id="eSubTotal" class="form-control" step="any" readonly required></td>
                                        </tr>    
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>Monto Descuento: </td>
                                            <td><input type="number" name="eDescuento" id="eDescuento" class="form-control" value="0" min="0" max="100" onchange="descuentoTotal(this.value)"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>Total: </td>
                                            <td><input type="number" name="eTotal" id="eTotal" class="form-control" value="0" min="0" step="any" required></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>Monto Total Subjeto IVA: </td>
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
                                                        {!! Form::number('ppago_giftcard', null, ['id'=>'ppago_giftcard','class'=>'form-control','min'=>'0', 'step'=>'any', 'readonly']) !!}
                                                        <hr>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="forma_pago" value="27" id="chkgiftcard" onchange="onchkgiftcard()">
                                                                <label class="form-check-label" for="chkgiftcard">
                                                                    Giftcard
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
        <div class="modal-dialog modal-lg">
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
                        <table id="tlotem" class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nro Lote</th>
                                    <th>Medicamento</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Laboratorio</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyLotem">
                                @foreach ($lotesm as $lotem)
                                    <tr id="{{$lotem->id}}">
                                        <td>
                                            <div class="chk">
                                                <input type="radio" name="chk" class="chk" value="{{$lotem->id}},{{$lotem->nombre_comercial}},{{$lotem->cantidad}},{{$lotem->precio_venta}}" id="{{$lotem->id}}">
                                            </div>
                                        </td>
                                        <td>{{$lotem->numero}}</td>
                                        <td>{{$lotem->nombre_comercial}}</td>
                                        <td>{{$lotem->fecha_vencimiento}}</td>
                                        <td>{{$lotem->cantidad}}</td>
                                        <td>{{$lotem->precio_venta}}</td>
                                        <td>{{$lotem->nombre}}</td>
                                    </tr>                                            
                                @endforeach                                                                                
                            </tbody>
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
            "language" : {"url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"},
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
            "language" : {"url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"},
            "autoWidth": false
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

    let total=0;
    let subtotal=[];

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
                let n = data.length;
                $("#tbodyLotem tr").empty();
                var fila="";
                for (let i = 0; i < n; i++) {
                    fila += "<tr id="+data[i].id+"><td><div class='chk'><input type='radio' name='chk' class='chk' id="+data[i].id+" value="+data[i].id+","+data[i].nombre_comercial+","+data[i].cantidad+","+data[i].precio_venta+"></div></td><td>"+data[i].numero+"</td><td>"+data[i].nombre_comercial+"</td><td>"+data[i].fecha_vencimiento+"</td><td>"+data[i].cantidad+"</td><td>"+data[i].precio_venta+"</td><td>"+data[i].nombre+"</td></tr>";                    
                    console.log(data);
                }
                // $('#tlotem').append(fila);
                $('#tlotem').DataTable().destroy();
                $('#tlotem').find('tbody').append(fila);
                $('#tlotem').DataTable().search().draw();                
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

    function agregar(){
        arreglo = document.getElementById("pcodigo").innerText;
        arr=arreglo.split(',')
        codigo=arr[0];
        cant_max=arr[2];
        
        concepto = document.getElementById("pproducto").value;               
        cantidad = document.getElementById("pcantidad").value; 
        precio = document.getElementById("pprecio").value;
        descuento = document.getElementById("pdescuento").value;

        if (parseInt(descuento)<0 || parseInt(descuento)>100) {
            alert("Error: Valores no validos");
            return;
        }
               
        if (concepto=="" || cantidad=="" || precio=="") {
            alert("Error: Campos no pueden estar vacíos");     
            return;                   
        } else if (parseInt(cantidad) > parseInt(cant_max)) {            
            alert("Error: la cantidad excede el stock del lote");    
            return;        
        } else {
            [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {
                idlote=cb.id;
                $('#'+idlote).fadeOut('slow');
            });
            precio=precio - (Math.floor(precio*descuento)/100);
            
            calculo=(cantidad*precio);    
                                
            subtotal.push(calculo.toFixed(2));
            cont=subtotal.length-1;
            total=parseFloat(total)+parseFloat(subtotal[cont]);            
            document.getElementById('eSubTotal').value = total.toFixed(2);
            document.getElementById('eTotal').value = total.toFixed(2);
            document.getElementById('eTotalIVA').value = total.toFixed(2); //Cambiar al saber el monto que cobrara IVA
            var fila='<tr class="detalle" id="fila'+idlote+'"><td><button type="button" class="btn btn-danger" onclick="eliminar('+idlote+','+cont+');"><i class="fas fa-times-circle"></i></button></td><td><input type="number" class="form-control input-sm" name="dcodigo[]" readonly value="'+codigo+'"></td><td><input type="text" class="form-control input-sm" name="dconcepto[]" readonly value="'+concepto+'"></td><td><input id="dpcantidad'+idlote+'" type="number" min=1 max="'+cant_max+'" onchange="onCambiarCantidad('+idlote+');" class="form-control input-sm" name="dcantidad[]" value="'+cantidad+'"></td><td><input id="dpprecio'+idlote+'"  onchange="onCambiarPrecio('+idlote+');" class="form-control input-sm" type="number" step="any" min="0" name="dprecio[]" value="'+precio+'"></td><td><input id="dsubtotal'+idlote+'" class="form-control input-sm" type="number" step="any" name="dsubtotal" readonly value="'+subtotal[cont]+'"></td></tr>';                   
            $('#detalles').append(fila);                                                              
        }
        limpiar();        
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

    function eliminar(index, cont){            
        total=parseFloat(total)-parseFloat(subtotal[cont]);
        document.getElementById('eSubTotal').value = total.toFixed(2);
        document.getElementById('eTotal').value = total.toFixed(2);
        document.getElementById('eTotalIVA').value = total.toFixed(2);
        subtotal.splice(cont,1);        
        limpiarMetodosPago();
        $('#fila'+index).remove();                
        $('#'+index).fadeIn('slow');
    }
    
    function onCambiarCantidad(index){
        cant = document.getElementById('dpcantidad'+index).value;
        precio = document.getElementById('dpprecio'+index).value;
        elementos = document.getElementsByName('dpsubtotal');        
        calculo=(cant*precio);
        document.getElementById('dpsubtotal'+index).value = calculo.toFixed(2);
        total = 0;
        for (let i = 0; i < elementos.length; i++) {
            total = total + parseFloat(elementos[i].value);         
        }                                        
        total=total.toFixed(2);                  
        document.getElementById('eSubTotal').value = total;
        document.getElementById('eTotal').value = total;
        document.getElementById('eTotalIVA').value = total; //Cambiar al saber el monto que cobrara IVA
    }

    function onCambiarPrecio(index){
        cant = document.getElementById('dpcantidad'+index).value;
        precio = document.getElementById('dpprecio'+index).value;
        elementos = document.getElementsByName('dpsubtotal');        
        calculo=(cant*precio);
        document.getElementById('dpsubtotal'+index).value = calculo.toFixed(2);
        total = 0;
        for (let i = 0; i < elementos.length; i++) {
            total = total + parseFloat(elementos[i].value);         
        }                                        
        total=total.toFixed(2);                  
        document.getElementById('eSubTotal').value = total;
        document.getElementById('eTotal').value = total;
        document.getElementById('eTotalIVA').value = total; //Cambiar al saber el monto que cobrara IVA
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
            console.log(cb.value);
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
        document.getElementById("ppago_giftcard").readOnly = true;
        document.getElementById("ppago_tarjeta").readOnly  = true;        
    }
    function onchkgiftcard(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = true;
        document.getElementById("pcambio").readOnly        = true;
        document.getElementById("ppago_giftcard").readOnly = false;
        document.getElementById("ppago_tarjeta").readOnly  = true;        
    }
    function onchktarjeta(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = true;
        document.getElementById("pcambio").readOnly        = true;
        document.getElementById("ppago_giftcard").readOnly = true;
        document.getElementById("ppago_tarjeta").readOnly  = false;        
    }
    function onchkotros(){
        limpiarMetodosPago();
        document.getElementById("ppago_efectivo").readOnly = true;
        document.getElementById("pcambio").readOnly        = true;
        document.getElementById("ppago_giftcard").readOnly = true;
        document.getElementById("ppago_tarjeta").readOnly  = true;
    }

    function limpiarMetodosPago(){
        document.getElementById("ppago_efectivo").value = "";
        document.getElementById("ppago_giftcard").value = "";
        document.getElementById("ppago_tarjeta").value  = "";
        document.getElementById("pcambio").value        = "";
    }

    function pagar(){
        pago=document.getElementById('ppago_efectivo').value;
        cambio = pago - total;
        document.getElementById('pcambio').value = cambio.toFixed(2);
    }

    function descuentoTotal(val){
        total=document.getElementById('eSubTotal').value;
        total= total - (Math.floor(total*val)/100);
        document.getElementById("eTotal").value = total;
    }

    function onFactura(){
        var chkFactura=document.getElementById("fcheck");        
        var txtNit = document.getElementById("fnit");
        var txtRazon = document.getElementById("frazon");
        var txtAutorizacion = document.getElementById("fautorizacion");

            document.getElementById("ffactura").value="factura" ;
        if (chkFactura.checked == true) {
            txtNit.style.display = "block";
            txtRazon.style.display = "block";
            txtAutorizacion.style.display = "block";

            txtNit.required=true;
            txtRazon.required=true;
            txtAutorizacion.required=true;
        } else {
            document.getElementById("ffactura").value="";
            txtNit.style.display = "none";
            txtRazon.style.display = "none";
            txtAutorizacion.style.display = "none";     

            txtNit.value='';
            txtRazon.value='';
            txtAutorizacion.value='';

            txtNit.required=false;
            txtRazon.required=false;
            txtAutorizacion.required=false;
        }
    }
</script>

@stop


