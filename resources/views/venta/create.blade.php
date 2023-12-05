@extends('adminlte::page')

@section('title', 'Venta')

@section('content')
@include('sweetalert::alert')

    {!! Form::open(['url' => '/venta', 'method' => 'POST']) !!}       
    {{Form::token()}}     
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-bookmark"></i> Venta</h3>    
                </div>   
                <div class="card-body">                                                        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="float-left">                                        
                                    {!! Form::label('comprobante','Comprobante: ') !!}   
                                    {!! Form::label('comprobante',$comprobante) !!}  
                                    <input type="hidden" name="comprobante" id="eComprobante" value={{$comprobante}}>                                                                     
                                </div>                                                                                    
                            </div>
                        </div>
                    </div>                                                                                                                                             
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        {!! Form::label('cliente','Nombre o Razon Social: ') !!}   
                                        <div class="row">
                                            {!! Form::text('cliente', null, ['id'=>'cnombre','class'=>'form-control','style'=>'width:88%;']) !!} 
                                            <a href="#modalBuscarCliente" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalBuscarCliente">
                                                <i class="fa fa-lg fa-user"></i>
                                            </a>
                                        </div>   
                                        {!! Form::label('idcliente', 'idcliente', ['id'=>'ccliente', 'style'=>'display:none']) !!}                                     
                                    </div>                                    
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">   
                                        {!! Form::label('Accion','Accion',['type'=>'hidden']) !!}                                                                               
                                        <a href="#modalCrearCliente" class="btn btn-info form-control" data-toggle="modal" data-target="#modalCrearCliente">Nuevo Cliente</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 ">
                                    <div class="form-group">
                                        {!! Form::label('tipodoc','Tipo Documento: ') !!}   
                                        {!! Form::text('tipodoc', null, ['id'=>'ctipodoc','class'=>'form-control', 'readonly'=>true]) !!} 
                                    </div>
                                </div>
                                <div class="col-lg-3 ">
                                    <div class="form-group">
                                        {!! Form::label('nrodoc','Numero de Documento: ') !!}   
                                        {!! Form::text('nrodoc', null, ['id'=>'cnrodoc','class'=>'form-control', 'readonly'=>true]) !!} 
                                    </div>
                                </div>
                                <div class="col-lg-2 ">
                                    <div class="form-group">
                                        {!! Form::label('cmpl','Complemento: ') !!}   
                                        {!! Form::text('cmpl', null, ['id'=>'ccmpl','class'=>'form-control', 'readonly'=>true]) !!} 
                                    </div>
                                </div> 
                            </div>                               
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        {!! Form::label('correo','Correo: ') !!}   
                                        {!! Form::text('correo', null, ['id'=>'ccorreo','class'=>'form-control', 'readonly'=>true]) !!} 
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12">
                                    <div class="form-group">                                        
                                        {!! Form::label('Producto', 'Producto') !!}                                          
                                        <div class="row">                                                                                                                                   
                                            {!! Form::text('producto', null, ['id'=>'pproducto','class'=>'form-control','style'=>'width:80%;']) !!}                                            
                                            <a href="#modalLote" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalLote" >
                                                <i class="fa fa-lg fa-receipt"></i>
                                            </a>
                                        </div>
                                        {!! Form::label('loteid', 'loteid', ['id'=>'pcodigo','style'=>'display:none']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12">
                                    <div class="form-group">
                                        {!! Form::label('Cantidad', 'Cantidad') !!}
                                        {!! Form::number('Cantidad', null, ['id'=>'pcantidad','class'=>'form-control','placeholder'=>'0','min'=>'0', 'oninput'=>'this.value|=0']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12">
                                    <div class="form-group">
                                        {!! Form::label('Precio', 'Precio') !!}
                                        {!! Form::number('Precio', null, ['id'=>'pprecio','class'=>'form-control','placeholder'=>'0.00','min'=>'0']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-2 col-xs-12">
                                    <div class="form-group">  
                                        {!! Form::label('Accion','Accion',['type'=>'hidden']) !!}                                        
                                        <input type="button" value="Agregar" id="btn_add" class="btn btn-success form-control" onclick="agregar()">
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <div class="card-body">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <table id="detalles" class="table table-bordered table-condensed table-hover">
                                    <thead style="background-color: #2ab27b">
                                        <th></th>
                                        <th>Codigo</th>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                        <th>P. Unitario</th>
                                        <th>SubTotal</th>
                                    </thead>
                                    <tfoot>         
                                        <td colspan="4"></td>                                                                                
                                        <td>Total: </td>                                 
                                        <td><input type="number" name="eTotal" id="eTotal" class="form-control" readonly></td>
                                    </tfoot>
                                    <tbody>
                                
                                    </tbody>
                                </table>
                            </div>                                    
                        </div> 
                        <div class="card-footer">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div class="checkbox form-group">
                                            <label><i class="fa fa-credit-card"></i> Forma de Pago</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="forma_pago" value="Efectivo" id="chkefectivo" checked>
                                                <label class="form-check-label" for="chkefectivo">
                                                    Efectivo
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="forma_pago" value="Linkser" id="chktarjeta" >
                                                <label class="form-check-label" for="chktarjeta">
                                                    Tarjeta
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="forma_pago" value="QR" id="chkqr" >
                                                <label class="form-check-label" for="chkqr">
                                                    QR
                                                </label>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col">
                                        <div class="form-group">                                    
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="fcheck" onclick="onFactura()"/>
                                                <label>Facturar</label>
                                            </div>    
                                        </div>
                                        <div class="form-group">
                                            {!! Form::number('Nit', null, ['id'=>'fnit','class'=>'form-control','placeholder'=>'NIT', 'style'=>'display: none;']) !!}
                                        </div>                                                                            
                                        <div class="form-group">
                                            {!! Form::number('Razon', null, ['id'=>'frazon','class'=>'form-control','placeholder'=>'Razón Social', 'style'=>'display: none;']) !!}    
                                        </div>                                            
                                        <div class="form-group">
                                            {!! Form::number('Autorizacion', null, ['id'=>'fautorizacion','class'=>'form-control','placeholder'=>'Autorización', 'style'=>'display: none;']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('Factura', '', ['id'=>'ffactura','hidden']) !!}
                                        </div>
                                    </div>    
                                </div>                                                                                                                                                                                                     
                            </div>          
                            <hr>                  
                            <div class="form-group">
                                <div class="float-left">
                                    {!! Form::label('Pago', 'Pago') !!}
                                    {!! Form::number('Pago', null, ['id'=>'ppago','class'=>'form-control','placeholder'=>'0.00','min'=>'0', 'required', 'onchange'=>'pagar()', 'step'=>'any']) !!}
                                </div>
                                <div class="float-right">
                                    {!! Form::label('Cambio', 'Cambio') !!}
                                    {!! Form::number('Cambio', null, ['id'=>'pcambio','class'=>'form-control','placeholder'=>'0.00','min'=>'0', 'readonly', 'step'=>'any']) !!}
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
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="seleccionarCliente()">Aceptar</button>
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
                                        <option value="CI - Cedula de Identidad">CI - Cedula de Identidad</option>
                                        <option value="CEX - Cedula de Indentidad Extranjero">CEX - Cedula de Indentidad Extranjero</option>
                                        <option value="PAS - Pasaporte">PAS - Pasaporte</option>
                                        <option value="NIT - Numero de Certificacion Tributaria">NIT - Numero de Certificacion Tributaria</option>
                                        <option value="OO - Otros Documentos">OO - Otros Documentos</option>                            
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
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="crearCliente">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- *************** Buscar Producto ******************** -->
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
                    <div role="tabpanel">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#tab01" aria-controls="tab01" role="tab" data-toggle="tab">Medicamentos</a>
                            </li>                            
                            <li class="nav-item">
                                <a class="nav-link" href="#tab03" aria-controls="tab03" role="tab" data-toggle="tab">Productos</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tab01">
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
                                    <tbody>
                                        @foreach ($lotesm as $lotem)
                                            <tr id="{{$lotem->id}}">
                                                <td>
                                                    <div class="chk">
                                                        <input type="checkbox" name="chk" class="chk" value="{{$lotem->id}},{{$lotem->medicamento->nombre_comercial}},{{$lotem->cantidad}},{{$lotem->precio_venta}}" id="{{$lotem->id}}">
                                                    </div>
                                                </td>
                                                <td>{{$lotem->numero}}</td>
                                                <td>{{$lotem->medicamento->nombre_comercial}}</td>
                                                <td>{{$lotem->fecha_vencimiento}}</td>
                                                <td>{{$lotem->cantidad}}</td>
                                                <td>{{$lotem->precio_venta}}</td>
                                                <td>{{$lotem->laboratorio->nombre}}</td>
                                            </tr>                                            
                                        @endforeach                                                                                
                                    </tbody>
                                </table>
                            </div>                            
                            <div role="tabpanel" class="tab-pane" id="tab03">
                                <br>
                                <table id="tlotep" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Producto</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Stock</th>
                                            <th>Precio Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lotesp as $lotep)
                                            <tr id="{{$lotep->id}}">
                                                <td>
                                                    <div class="chk">
                                                        <input type="checkbox" name="chk" class="chk" value="{{$lotep->id}},{{$lotep->producto->nombre}},{{$lotep->cantidad}},{{$lotep->precio_venta}}" id="{{$lotep->id}}">                                                        
                                                    </div>
                                                </td>                                                                                              
                                                <td>{{$lotep->producto->nombre}}</td>
                                                <td>{{$lotep->fecha_vencimiento}}</td>
                                                <td>{{$lotep->cantidad}}</td>
                                                <td>{{$lotep->precio_venta}}</td>
                                            </tr>                                            
                                        @endforeach                                                                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="seleccionar()">Aceptar</button>
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
            "autoWidth": false
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

    $(function () {
        $('#tlotei').DataTable({
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

    $(function () {
        $('#tlotep').DataTable({
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
    
    $('input.chk').on('change', function() {
        $('input.chk').not(this).prop('checked', false);  
    });
   
    let total=0;
    let subtotal=[];

    $('#modalLote.save').click(function (e) {
        e.preventDefault();
        addImage(5);
        $('#modalLote').modal('hide');
        return false;
    })

    $('#modalBuscarCliente.save').click(function (e) {
        e.preventDefault();
        addImage(5);
        $('#modalBuscarCliente').modal('hide');
        return false;
    })

    $('#modalCrearCliente.save').click(function (e) {
        e.preventDefault();
        addImage(5);
        $('#modalCrearCliente').modal('hide');
        return false;
    })

    $('#crearCliente').click(function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
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
            data: formData,
            success: function(data){
                swal.fire("Registro Realizado","Cliente Nuevo: "+data.numero_documento,"success");
                
                $('#cnombre').val(data.nombre);
                $('#ctipodoc').val(data.tipo_documento);
                $('#cnrodoc').val(data.numero_documento);
                $('#ccmpl').val(data.complemento);
                $('#ccorreo').val(data.correo);
            },
            error: function (json) {
                if (json.status==409) {
                    swal.fire("Error", JSON.parse(json.responseText).mensaje, "error"); 
                } else {
                    swal.fire("Error","Error al registrar","error")  ;
                }
            }
        });
    });

    function agregar(){
        arreglo = document.getElementById("pcodigo").innerText;
        arr=arreglo.split(',')
        codigo=arr[0];
        cant_max=arr[2];
               
        concepto = document.getElementById("pproducto").value;               
        cantidad = document.getElementById("pcantidad").value; 
        precio = document.getElementById("pprecio").value;    
               
        if (cantidad=="" || precio=="") {
            alert("Error: Campos no pueden estar vacíos");                        
        } else if (parseInt(cantidad) > parseInt(cant_max)) {            
            alert("Error: la cantidad excede el stock del lote");            
        } else {
            [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {
                idlote=cb.id;
                $('#'+idlote).fadeOut('slow');
            });
            gasto=(cantidad*precio);                        
            subtotal.push(gasto.toFixed(2));
            cont=subtotal.length-1;
            total=parseFloat(total)+parseFloat(subtotal[cont]);            
            document.getElementById('eTotal').value = total.toFixed(2);
            var fila='<tr class="selectd" id="fila'+idlote+'"><td><button type="button" class="btn btn-danger" onclick="eliminar('+idlote+','+cont+');"><i class="fas fa-times-circle"></i></button></td><td><input type="number" class="form-control input-sm" name="dcodigo[]" readonly value="'+codigo+'"></td><td><input type="text" class="form-control input-sm" name="dconcepto[]" readonly value="'+concepto+'"></td><td><input type="number" class="form-control input-sm" name="dcantidad[]" readonly value="'+cantidad+'"></td><td><input class="form-control input-sm" type="number" name="dprecio[]" readonly value="'+precio+'"></td><td><input class="form-control input-sm" type="number" name="dsubtotal[]" readonly value="'+subtotal[cont]+'"></td></tr>';                   
            $('#detalles').append(fila);                                                              
        }
        limpiar();        
    }

    function agregarCliente(){
        limpiar();
    }

    function limpiar(){        
        document.getElementById("pcantidad").value = "";
        document.getElementById("pprecio").value = "";
        document.getElementById("pproducto").value = "";
        document.getElementById("pcodigo").innerText = "";
        $('input[name="chk"]').prop('checked', false);

    }

    function eliminar(index, cont){            
        total=parseFloat(total)-parseFloat(subtotal[cont]);
        document.getElementById('eTotal').value = total.toFixed(2);
        subtotal.splice(cont,1);        
        $('#fila'+index).remove();                
        $('#'+index).fadeIn('slow');
    }
    function seleccionarCliente(){
        [].forEach.call(document.querySelectorAll('input[name="chkCliente"]:checked'), function(cb) {
            document.getElementById('ccliente').innerText=cb.value;            
        });
        cliente=document.getElementById("ccliente").innerText;
        cl=cliente.split(',');
        nombre=cl[1];
        tipodoc=cl[2];
        nrodoc=cl[3];
        cmpl=cl[4];
        correo=cl[5];
        document.getElementById('cnombre').value =nombre;
        document.getElementById('ctipodoc').value =tipodoc;
        document.getElementById('cnrodoc').value  =nrodoc;
        document.getElementById('ccmpl').value    =cmpl;
        document.getElementById('ccorreo').value  =correo;        
    }

    function seleccionar(){  
        console.log("Hola");
        [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {
            document.getElementById('pcodigo').innerText=cb.value;            
        });
        arreglo = document.getElementById("pcodigo").innerText;        
        arr=arreglo.split(',')
        producto=arr[1];        
        precio_venta=arr[3];
        document.getElementById('pprecio').value=precio_venta;
        document.getElementById('pproducto').value=producto;        
    }

    function pagar(){
        pago=document.getElementById('ppago').value;
        document.getElementById('pcambio').value = pago-total;
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


