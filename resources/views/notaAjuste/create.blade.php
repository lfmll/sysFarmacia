@extends('adminlte::page')
@section('title', 'Nota de Ajuste')
@section('content')
@include('sweetalert::alert')
    <div class="card">
        {!! Form::open(['url' => '/notaAjuste', 'method' => 'POST']) !!}
            @csrf
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-fw fa-file-alt"></i>Crear Nota de Ajuste para la Factura #{{ $factura->id }}</h3>
            </div>
        </div>
        <div class="card-body">            
            <input type="hidden" name="factura_id" value="{{ $factura->id }}">
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="tipoNota">Tipo de Nota</label>
                    <select name="tipoNota" id="tipoNota" class="form-control" required>
                        <option value="1">Débito</option>
                        <option value="2">Crédito</option>
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label for="motivo">Motivo</label>
                    <select name="motivo" id="motivo" class="form-control" required>
                        <option value="Devolución">Devolución</option>
                        <option value="Recargo">Recargo</option>
                        <option value="Corrección">Corrección</option>                        
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="Factura">Datos Factura Original</label>
                </div>
            </div>   
            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="nroFactura">No. Factura</label>
                    <input type="text" class="form-control" value="{{ $factura->numeroFactura }}" disabled>
                </div>
                <div class="form-group col-sm-2">
                        <label for="Fecha">Fecha Emisión</label>
                        <input type="text" class="form-control" value="{{ $factura->fechaEmision }}" disabled>
                    </div>
                <div class="form-group col-sm-4">
                    <label for="nroAutorizacion">No. Autorización</label>
                    <input type="text" class="form-control" value="{{ $factura->cuf }}" disabled>
                </div>
                <div class="form-group col-sm-4">
                    <label for="codigoControl">Código Control</label>
                    <input type="text" class="form-control" value="{{ $factura->codigoRecepcion }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <label for="DetalleFactura">Datos Detalle Factura Original</label>
                </div>                    
            </div>
            <div class="row">
                <table id="tdetalleFactura" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Descuento</th>
                            <th>SubTotal</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalleFactura as $detalle)
                            <tr> 
                                <td>
                                    <input type="hidden" name="dmodificado[{{$detalle->id}}]" id="dmodificadoHidden{{$detalle->id}}" value="0">
                                    <input type="checkbox" id="dmodificado{{$detalle->id}}" name="dmodificado[]" disabled>
                                </td>                                   
                                <td>{{ $detalle->descripcion }}</td>
                                <td><input type="number" id="dcantidad{{$detalle->id}}" name="dcantidad[]" class="form-control" value="{{$detalle->cantidad}}" min="1" step="1" onchange="cambiarImporte('{{ $detalle->id }}');" data-original="{{$detalle->cantidad}}"></td>                                    
                                <td><input type="number" id="dprecio{{$detalle->id}}" name="dprecio[]" class="form-control" value="{{$detalle->precioUnitario}}" min="0" step="0.01" onchange="cambiarImporte('{{ $detalle->id }}');" data-original="{{$detalle->precioUnitario}}"></td>
                                <td><input type="number" id="ddescuento{{$detalle->id}}" name="ddescuento[]" class="form-control" value="{{$detalle->descuento ?? 0}}" min="0" step="0.01" onchange="cambiarImporte('{{ $detalle->id }}');" data-original="{{$detalle->descuento}}"></td>       
                                <td><input type="number" id="dsubtotal{{$detalle->id}}" name="dsubtotal[]" class="form-control" value="{{$detalle->subTotal}}"  min="0" readonly></td>                                    
                            </tr>                                
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;"><strong>Monto Subtotal:</strong></td>
                            <td colspan="1">
                                <input type="number" id="eSubtotal" name="eSubtotal" class="form-control" value="{{ $totalSubTotal }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;"><strong>Monto Descuento Crédito Débito:</strong></td>
                            <td colspan="1">
                                <input type="number" id="eDescuento" name="eDescuento" class="form-control" value="0" min="0" step="0.01" onchange="descontarTotal(this.value);">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                            <td colspan="1">
                                <input type="number" id="eTotal" name="eTotal" class="form-control" value="{{ $factura->montoTotal }}" readonly>                                    
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>            
        </div>
        <div class="card-footer">
            <div class="form-group">
                <div class="float-left">
                    <a type="submit" class="btn btn-default btn-lg" href="{{url('/factura')}}">Cancelar</a>    
                </div>
                <div class="float-right">
                    <button type="submit" class="btn btn-success btn-lg">Guardar</button>  
                </div>            
            </div>
        </div>
        {!! Form::close() !!}
    </div>

@stop   
@section('js')
<script>
    function cambiarImporte(index) {
        const cantidadInput = document.getElementById('dcantidad' + index);
        const precioInput = document.getElementById('dprecio' + index);
        const descuentoInput = document.getElementById('ddescuento' + index);
        const subtotalInput = document.getElementById('dsubtotal' + index);
        const subtotalSumaInput = document.getElementById('eSubtotal');
        const totalInput = document.getElementById('eTotal');
        
        //Valores Actuales
        const cant = parseFloat(cantidadInput.value) || 0;
        const precio = parseFloat(precioInput.value) || 0;
        const descuento = parseFloat(descuentoInput.value) || 0;

        //Valores Originales
        const cantOriginal = parseFloat(cantidadInput.dataset.original) || 0;
        const precioOriginal = parseFloat(precioInput.dataset.original) || 0;
        const descuentoOriginal = parseFloat(descuentoInput.dataset.original) || 0;
        
        if (precio < 0 || cant <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Valor inválido',
            });
            precioInput.value = 0;
            return;
        }

        const subtotal = (cant * precio) - descuento;
        subtotalInput.value = subtotal.toFixed(2);

        let total = 0;
        document.querySelectorAll('input[name="dsubtotal[]"]').forEach((input) => {
            total += parseFloat(input.value) || 0;
        });
        total = total.toFixed(2);
        subtotalSumaInput.value = total;
        totalInput.value = total;

        const modificado = cant !== cantOriginal || precio !== precioOriginal || descuento !== descuentoOriginal;
        
        document.getElementById('dmodificado'+index).checked = modificado;
        document.getElementById('dmodificadoHidden'+index).value = modificado ? 1 : 0;
    }

    function descontarTotal(val){
        sub = document.getElementById('eSubtotal').value;
        if (val > sub){
            Swal.fire({
                icon: "error",
                title: "Alerta",
                text: "El descuento no puede exceder el Subtotal"
            });
            return;
        }
        total = parseFloat(sub) - parseFloat(val);
        total = total.toFixed(2);
        document.getElementById("eTotal").value = total;
    }
</script>
@stop 