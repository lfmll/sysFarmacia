@extends('adminlte::page')

@section('title', 'Compra')

@section('content')
    {!! Form::open(['url' => '/compra', 'method' => 'POST']) !!}       
    {{Form::token()}}     
        <div class="col-md-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-bookmark"></i> Compra</h3>    
                </div>   
                <div class="card-body">                                                        
                                           
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                            {!! Form::label('agentes', 'Proveedores') !!}
                                            {{ Form::select('agentes',$agentes, $compra->agente_id, ['class'=>'agentes form-control','placeholder'=>'']) }}                                
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">                                        
                                        {!! Form::label('Producto', 'Producto') !!}   
                                        <div class="input-group mb-3">
                                            {!! Form::text('producto', null, ['id'=>'pproducto','class'=>'form-control','style'=>'width:80%;']) !!}
                                            <div class="input-group-append">
                                                <a href="#modalLote" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalLote" >
                                                    <i class="fa fa-lg fa-receipt"></i>
                                                </a>
                                            </div>                                            
                                        </div>                                                                                                                                                                   
                                        {!! Form::label('loteid', 'loteid', ['id'=>'pcodigo']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-3">
                                    <div class="row">
                                        <label for="accionA" style="visibility:hidden;">accionA</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <a href="#modalCrearProducto" class="btn btn-info form-control" data-toggle="modal" data-target="#modalCrearProducto">
                                                <i class="fa fa-lg fa-prescription-bottle-alt"></i> Nuevo
                                            </a>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="button" value="Limpiar" id="btn_limpiar" class="btn btn-warning form-control" onclick="limpiar()">                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        {!! Form::label('Cantidad', 'Cantidad') !!}
                                        {!! Form::number('Cantidad', null, ['id'=>'pcantidad','class'=>'form-control','placeholder'=>'0','min'=>'0', 'oninput'=>'this.value|=0']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        {!! Form::label('Precio', 'Precio') !!}
                                        {!! Form::number('Precio', null, ['id'=>'pprecio','class'=>'form-control','placeholder'=>'0.00','min'=>'0']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        {!! Form::label('Descuento', 'Descuento') !!}
                                        {!! Form::number('descuento', null, ['id'=>'pdescuento','class'=>'form-control','placeholder'=>'0.00','min'=>'0','max'=>'100']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">  
                                        {!! Form::label('accionB', 'accionB',['style'=>'visibility:hidden;']) !!}                                   
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
                            <a type="submit" class="btn btn-default btn-lg" href="{{url('/compra')}}">Cancelar</a>    
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-success btn-lg">Guardar</button>  
                        </div>            
                    </div>
                </div>      
            </div>        
        </div>                                
    {!! Form::close() !!}   
<!-- ***************Modal Buscar Producto ******************** -->     
    <div class="modal fade" id="modalLote" role="dialog" tabindex="-1" aria-hidden="true"> 
        <div class="modal-dialog modal-lg">        
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Escoger Lote</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="">
                        <span aria-hidden="true">x</span>
                    </button>                    
                </div>
                <div class="modal-body">                                                                      
                    <div>
                        <table id="tlotem" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nro Lote</th>
                                    <th>Medicamento</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Laboratorio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lotesm as $lotem)
                                    <tr id="{{$lotem->id}}">
                                        <td>
                                            <div class="chk">
                                                <input type="radio" name="chk" class="chk" value="{{$lotem->id}},{{$lotem->medicamento->nombre_comercial}},{{$lotem->cantidad}},{{$lotem->precio_compra}}" id="{{$lotem->id}}">
                                            </div>
                                        </td>
                                        <td>{{$lotem->numero}}</td>
                                        <td>{{$lotem->medicamento->nombre_comercial}}</td>
                                        <td>{{$lotem->fecha_vencimiento}}</td>
                                        <td>{{$lotem->cantidad}}</td>
                                        <td>{{$lotem->precio_compra}}</td>
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
<!-- ******** Modal Crear Producto ******** -->
<div class="modal fade" id="modalCrearProducto" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Crear Producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="">
                    <span aria-hidden="true">x</span>
                </button>  
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {{ Form::select('actividades',$actividades, null, ['class'=>'form-control','id'=>'pcodigo_caeb'])  }}  
                            </div>
                            <div class="col-md-6">
                                {{ Form::select('catalogos',$catalogos, null, ['class'=>'form-control','id'=>'pcodigo_producto'])  }}
                            </div>
                        </div>                        
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">                                
                                <label>Nombre Comercial</label>
                                <input type="text" name="pnombrec" id="pnombrec" class="form-control">                                
                            </div>
                            <div class="col-md-6">
                                <label>Nombre Generico</label>
                                <input type="text" name="pnombreg" id="pnombreg" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Acción</label>    
                                {{ Form::select('clases',$clases, null, ['class'=>'form-control', 'id'=>'pclase', 'required'])  }}
                            </div>
                            <div class="col-md-4">
                                <label>Unidad Medida</label>
                                {{ Form::select('unidad_medida',$unidad_medida, null, ['class'=>'form-control', 'id'=>'pmedida', 'required'])  }}
                            </div>
                            <div class="col-md-4">
                                <label>Vía Administración</label>
                                {!! Form::select('vias', $vias, null, ['class'=>'form-control', 'id'=>'pvia', 'required']) !!}
                            </div>
                        </div>    
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Laboratorio</label>
                                {!! Form::select('laboratorios', $laboratorios, null, ['class'=>'form-control', 'id'=>'plaboratorio', 'placeholder'=>'Seleccione..']) !!}
                            </div>
                            <div class="col-md-4">
                                <label>Numero de Lote</label>
                                <input type="text" name="pnlote" id="pnlote" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha Vencimiento</label>
                                <input type="date" name="pfechav" id="pfechav" class="form-control" required>
                            </div>                                                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="crearProducto">Aceptar</button>
            </div>
        </div>
    </div>
</div>    
@stop
@section('js')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">    
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>   
    $('.agentes').select2({theme: 'bootstrap4'});
    $(function(){
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

    let total=0;
    let subtotal=[];
    
    function agregar(){
        arreglo = document.getElementById("pcodigo").innerText;
        arr=arreglo.split(',')
        codigo=arr[0];
               
        concepto = document.getElementById("pproducto").value;               
        cantidad = document.getElementById("pcantidad").value; 
        precio = document.getElementById("pprecio").value;    
               
        if (cantidad=="" || precio=="") {
            alert("Error: Campos no pueden estar vacíos");                        
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

    function limpiar(){        
        document.getElementById("pcantidad").value = "";
        document.getElementById("pprecio").value = "";
        document.getElementById("pproducto").value = "";
        document.getElementById("pcodigo").innerText = "";
        document.getElementById("pdescuento").innerText = "";
        $('input[name="chk"]').prop('checked', false);      
    }

    function eliminar(index,cont){
        total=parseFloat(total)-parseFloat(subtotal[cont]);        
        document.getElementById('eTotal').value = total.toFixed(2);
        subtotal.splice(cont,1);
        document.getElementById("ppago").value = "";
        document.getElementById("pcambio").value = "";
        $('#fila'+index).remove();
        $('#'+index).fadeIn('slow');
    }

    function seleccionar(){        
        [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {
            document.getElementById('pcodigo').innerText=cb.value;            
        });
        arreglo = document.getElementById("pcodigo").innerText;        
        arr=arreglo.split(',')
        producto=arr[1];        
        precio_compra=arr[3];
        document.getElementById('pprecio').value=precio_compra;
        document.getElementById('pproducto').value=producto;
    }

    function pagar(){
        pago=document.getElementById('ppago').value;
        document.getElementById('pcambio').value = pago-total;
    }

    $('#crearProducto').click(function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            codigo_caeb: $('#pcodigo_caeb').val(),
            codigo_producto: $('#pcodigo_producto').val(),
            nombre_comercial: $('#pnombrec').val(),
            nombre_generico: $('#pnombreg').val(),
            unidad_medida: $('#pmedida').val(),
            via: $('#pvia').val(),
            clase: $('#pclase').val(),
            laboratorio: $('#plaboratorio').val(),
            nro_lote: $('#pnlote').val(),
            fecha_vencimiento: $('#pfechav').val(),
        };        
        var type="POST";
        $.ajax({
            type: type,
            url: '{{url("/medicamento")}}',
            data: formData,
            dataType: 'json',
            success: function(data){
                console.log(data);
                document.getElementById('pcodigo').innerText = data.value;
                producto = document.getElementById('pcodigo').innerText;
                pro = producto.split(',');
                $('#pproducto').val(data.nombre_comercial);
                swal.fire("Registro de producto Realizado","success");                
            },
            error: function (json) {
                console.log(json);
                if (json.status==409) {
                    swal.fire("Error", JSON.parse(json.responseText).mensaje, "error"); 
                } else {
                    swal.fire("Error","Error al registrar","error");
                }
            }
        });
    })
</script>

@stop

