@extends('adminlte::page')

@section('title', 'Venta')

@section('content')
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
                                <div class="col-lg-3 col-md-3 col-xs-12">
                                    <div class="form-group">                                        
                                        {!! Form::label('Producto', 'Producto') !!}                                          
                                        <div class="row">                                                                                                                                   
                                            {{-- {!! Form::select('productos', $productos->pluck('nombre','id'), null, ['id'=>'pproducto', 'class'=>'productos form-control','style'=>'width:80%;']) !!}                                             --}}
                                            {!! Form::text('producto', null, ['id'=>'pproducto','class'=>'form-control','style'=>'width:80%;']) !!}                                            
                                            <a href="#myModal" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal" >
                                                <i class="fa fa-lg fa-receipt"></i>
                                            </a>
                                        </div>
                                        {!! Form::label('loteid', 'loteid', ['id'=>'pcodigo']) !!}
                                        {!! Form::label('productoid', 'productoid', ['id'=>'pcodigoprod']) !!}
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
                                        <input type="button" value="Agregar" id="btn_add" class="btn btn-info form-control" onclick="agregar()">
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
    
    <div class="modal fade" id="myModal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Buscar Medicamento-Insumo-Producto</h4>
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
                                <a class="nav-link" href="#tab02" aria-controls="tab02" role="tab" data-toggle="tab">Insumos</a>
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
                                            <th>Precio</th>
                                            <th>Laboratorio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lotesm as $lotem)
                                            <tr>
                                                <td>
                                                    <div class="chk">
                                                        <input type="checkbox" name="chk" class="chk" value="{{$lotem->id}}">
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
                            <div role="tabpanel" class="tab-pane" id="tab02">
                                <br>
                                <table id="tlotei" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nro Lote</th>
                                            <th>Insumo</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Laboratorio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lotesi as $lotei)
                                            <tr>
                                                <td>
                                                    <div class="chk">
                                                        <input type="checkbox" name="chk" class="chk" value="{{$lotei->id}}">
                                                    </div>
                                                </td>
                                                <td>{{$lotei->numero}}</td>
                                                <td>{{$lotei->insumo->nombre}}</td>
                                                <td>{{$lotei->fecha_vencimiento}}</td>
                                                <td>{{$lotei->cantidad}}</td>
                                                <td>{{$lotei->precio_venta}}</td>
                                                <td>{{$lotei->laboratorio->nombre}}</td>
                                            </tr>                                            
                                        @endforeach                                                                                
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab03">
                                <br>
                                <table id="tproducto" class="table">
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
                                        @foreach ($productos as $producto)
                                            <tr>
                                                <td>
                                                    <div class="chk">
                                                        <input type="checkbox" name="chk" class="chk" value="{{$producto->id}}">
                                                    </div>
                                                </td>
                                                <td>{{$producto->nombre}}</td>                                                
                                                <td>{{$producto->fecha_vencimiento}}</td>
                                                <td>{{$producto->stock}}</td>
                                                <td>{{$producto->precio_venta}}</td>
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
<script>
    $(document).ready(function() {
        $('.productos').select2();
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
        $('#tproducto').DataTable({
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
   
    var cont=0;
    let total=0;
    let subtotal=[];

    $('#myModal .save').click(function (e) {
        e.preventDefault();
        addImage(5);
        $('#myModal').modal('hide');
        //$(this).tab('show')
        return false;
    })

    function agregar(){
        codigo = document.getElementById("pcodigo").innerText;
        producto=document.getElementById("pproducto");
        concepto=producto.options[producto.selectedIndex].text;    
        cantidad = document.getElementById("pcantidad").value; 
        precio = document.getElementById("pprecio").value;    
        
        if (cantidad!="" && precio!="") {
            gasto=(cantidad*precio);
            subtotal.push(gasto.toFixed(2));
            total=total+parseFloat(subtotal[cont]);            
            document.getElementById('eTotal').value = total;
            var fila='<tr class="selectd" id="fila'+cont+'"><td><button type="button" class="btn btn-danger" onclick="eliminar('+cont+');"><i class="fas fa-times-circle"></i></button></td><td><input type="number" class="form-control input-sm" name="dcodigo[]" readonly value="'+codigo+'"></td><td><input type="text" class="form-control input-sm" name="dconcepto[]" readonly value="'+concepto+'"></td><td><input type="number" class="form-control input-sm" name="dcantidad[]" readonly value="'+cantidad+'"></td><td><input class="form-control input-sm" type="number" name="dprecio[]" readonly value="'+precio+'"></td><td><input class="form-control input-sm" type="number" name="dsubtotal[]" readonly value="'+subtotal[cont]+'"></td></tr>';                                  
            $('#detalles').append(fila);            
            cont=cont+1;
        }else{
            alert("Error al ingresar al detalle de ingreso");            
        }
        limpiar();        
    }
    function limpiar(){        
        document.getElementById("pcantidad").value = "";
        document.getElementById("pprecio").value = "";        
    }
    function eliminar(index){
        console.log("sub antes: "+subtotal);
        console.log(index);
        total=parseFloat(total)-parseFloat(subtotal[index]);
        
        document.getElementById('eTotal').value = total;
        subtotal.splice(index,1);
        console.log("sub despues: "+subtotal);
        $('#fila'+index).remove();
        cont=cont-1;
    }

    function seleccionar(){        
        [].forEach.call(document.querySelectorAll('input[name="chk"]:checked'), function(cb) {
            document.getElementById('pcodigo').innerText=cb.value;            
        });
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


