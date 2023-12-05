@extends('adminlte::page')

@section('title', 'Lote')

@section('content')
<div class="row">
    <div class="col-md-12">           
        <div class="card card-warning">            
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-fw fa-receipt"></i> Nuevo Lote</h5>                
            </div>                         
            {!! Form::open(['url' => '/lote', 'method' => 'POST']) !!}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('numero','Numero')}}
                                {{Form::text('numero', $lote->numero, ['class'=>'form-control', 'placeholder'=>'Numero de lote'])}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('fecha_vencimiento','Fecha Vencimiento')}}
                                {{Form::date('fecha_vencimiento', $lote->fecha_vencimiento,['class'=>'form-control laboratorio', 'placeholder'=>'Fecha de Vencimiento','required'])}}
                            </div>        
                        </div>                        
                    </div>
                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('precio_compra','Precio Compra')}}
                                {{ Form::number('precio_compra',$lote->precio_compra,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Compra', 'step'=>'any', 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('ganancia','Ganancia (%)')}}
                                {{ Form::number('ganancia',$lote->ganancia,['class'=>'form-control','min'=>'0', 'max'=>'100', 'placeholder'=>'Ganancia','step'=>'any', 'onchange'=>'porcentuarA(this.value)']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('precio_venta','Precio Venta')}}
                                {{ Form::number('precio_venta',$lote->precio_venta,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Venta','step'=>'any', 'onchange'=>'porcentuarA(this.value)']) }}
                            </div> 
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="col-md-6">                            
                            <div class="checkbox form-group">                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="rmedicamento" checked>
                                    <label class="form-check-label" for="rmedicamento">
                                        Medicamento
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="rinsumo" >
                                    <label class="form-check-label" for="rinsumo">
                                        Insumo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="rproducto" >
                                    <label class="form-check-label" for="rproducto">
                                        Producto
                                    </label>
                                </div>                                                                                                                                  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('medicamento', 'Medicamento') !!}
                                {{ Form::select('medicamentos',$medicamentos, $lote->medicamento_id,['class'=>'medicamentos form-control','placeholder'=>'','style'=>'width: 100%;'] ) }}
                                
                            </div>        
                        </div>                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('producto', 'Producto') !!}
                                {{ Form::select('productos',$productos, $lote->producto_id,['class'=>'productos form-control','placeholder'=>'','style'=>'width: 100%;', 'disabled']) }}            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('laboratorio', 'Laboratorio') !!}
                                {{ Form::select('laboratorios',$laboratorios, $lote->laboratorio_id, ['class'=>'laboratorios form-control','placeholder'=>'','style'=>'weight: 100%;']) }}
                            </div>
                        </div>                                                    
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-left">
                        <a type="submit" class="btn btn-default" href="{{url('/lote')}}">Cancelar</a>   
                    </div>                
                    <div class="float-right">
                        <input type="submit" value="Guardar" class="btn btn-warning"> 
                    </div>                
                </div>      
            {!! Form::close() !!}            
        </div>            
    </div>
</div>
    
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.laboratorios').select2();
        
        $('.insumos').select2();
        $('.medicamentos').select2();
        $('.productos').select2();

        $("input[id=rmedicamento]").click(function(){
            $(".insumos").prop("disabled",true);            
            $(".medicamentos").prop("disabled",false);
            $(".productos").prop("disabled",true);
            $(".laboratorios").prop("disabled",false);
            $(".insumos").val(null).trigger('change');
            $(".productos").val(null).trigger('change');            
        });
        $("input[id=rinsumo]").click(function(){ 
            $(".insumos").prop("disabled",false);            
            $(".medicamentos").prop("disabled",true);                      
            $(".productos").prop("disabled",true);
            $(".laboratorios").prop("disabled",false);
            $(".medicamentos").val(null).trigger('change');
            $(".productos").val(null).trigger('change');
        });
        $("input[id=rproducto]").click(function(){
            $(".insumos").prop("disabled",true)
            $(".medicamentos").prop("disabled",true)
            $(".productos").prop("disabled",false);
            $(".laboratorios").prop("disabled",true);         
            $(".medicamentos").val(null).trigger('change');
            $(".insumos").val(null).trigger('change');
            $(".laboratorios").val(null).trigger('change');           
        });
    });

    function porcentuarA(g)
    {
        pcompra=document.getElementById("precio_compra").value;
        porcentaje=(g/100*pcompra);
        pventa=parseFloat(pcompra)+parseFloat(porcentaje);
        document.getElementById("precio_venta").value=pventa.toFixed(2);            
    }
    function porcentuarB(pventa)
    {
        pcompra=document.getElementById("precio_compra").value;
        dividendo=100*(parseFloat(pventa)-parseFloat(pcompra));
        ganancia=parseFloat(dividendo).toFixed(2)/parseFloat(pcompra).toFixed(2);
        document.getElementById("ganancia").value=ganancia.toFixed(2);
        
        }
</script>    
@stop
