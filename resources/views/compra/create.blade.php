@extends('adminlte::page')

@section('title', 'Compra')

@section('content')
    {!! Form::open(['url' => '/compra', 'method' => 'POST']) !!}       
    {{Form::token()}}     
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-bookmark"></i> Compra</h3>    
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
                                <div class="float-right">
                                    {!! Form::label('agentes', 'Proveedores') !!}
                                    {{ Form::select('agentes',$agentes, $compra->agente_id, ['class'=>'agentes form-control','placeholder'=>'','style'=>'weight: 100%;']) }}                                            
                                </div>
                            </div>
                        </div>
                    </div>                                                                                                    
                    <hr class="dotted">                       
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12">
                                    <div class="form-group">                                        
                                        {!! Form::label('Producto', 'Producto') !!}                                          
                                        <div class="row">
                                            {!! Form::select('lotes', $lotes->pluck('numero','id'), null, ['id'=>'plote','class'=>'lotes form-control', 'placeholder'=>'', 'style'=>'width:80%']) !!}                                                                      
                                            <a href="#myModal" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-fw fa-receipt"></i>
                                            </a>
                                        </div>                                        
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
                            <div class="form-group">
                                <div class="float-left">
                                    {!! Form::label('Pago', 'Pago') !!}
                                    {!! Form::number('Pago', null, ['id'=>'ppago','class'=>'form-control','placeholder'=>'0.00','min'=>'0']) !!}
                                </div>
                                <div class="float-right">
                                    {!! Form::label('Cambio', 'Cambio') !!}
                                    {!! Form::number('Cambio', null, ['id'=>'pcambio','class'=>'form-control','placeholder'=>'0.00','min'=>'0', 'disabled']) !!}
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
    
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar Lote</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="">
                        <span aria-hidden="true">x</span>
                    </button>                    
                </div>
                <div class="modal-body">
                    <p>This is a large modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>      
    </div>
    
@stop

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>    
    $(document).ready(function() {
        $('.lotes').select2();
    });

    var cont=0;
    let total=0;
    let subtotal=[];
    
    function agregar(){
        slote=document.getElementById("plote");
        if (slote.options[slote.selectedIndex].value=="") {
            alert("vacio");
        }else{
            codigo=slote.options[slote.selectedIndex].value;
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
            $.ajax({
                type:'get',
                url:'{{URL::to('buscarProducto')}}',
                data:{'buscarProducto': codigo},
                dataType:'json',
                success:function(data, textStatus, jqXHR){
                    console.log(data.error);
                }
            });
        }
        
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
        total=parseFloat(total)-parseFloat(subtotal[index]);        
        document.getElementById('eTotal').value = total;
        subtotal.splice(index,1);
        $('#fila'+index).remove();
        cont=cont-1;
    }
    
</script>
@stop
