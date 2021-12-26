{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::text('numero', $lote->numero, ['class'=>'form-control', 'placeholder'=>'Numero de lote','required'])}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::number('cantidad', $lote->cantidad, ['class'=>'form-control','min'=>'0', 'placeholder'=>'Cantidad'])}}    
            </div>        
        </div>
    </div>
    <div class="row">    
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::number('precio_compra',$lote->precio_compra,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Compra']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::number('precio_venta',$lote->precio_venta,['class'=>'form-control','min'=>'0', 'placeholder'=>'Precio Venta']) }}
            </div> 
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::date('fecha_vencimiento', $lote->fecha_vencimiento,['class'=>'form-control laboratorio', 'placeholder'=>'Fecha de Vencimiento','required'])}}
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('laboratorio', 'Laboratorio') !!}
                {{ Form::select('laboratorios',$laboratorios, $lote->laboratorio_id, ['class'=>'laboratorios form-control','placeholder'=>'','required','style'=>'weight: 100%;']) }}
            </div>
            <div class="form-group">
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
            </div>    
            <div class="form-group">
                {!! Form::label('medicamento', 'Medicamento') !!}
                {{ Form::select('medicamentos',$medicamentos, $lote->medicamento_id,['class'=>'medicamentos form-control','placeholder'=>'','style'=>'width: 100%;'] ) }}
                
            </div>        
            <div class="form-group">
                {!! Form::label('insumo', 'Insumo') !!}
                {{ Form::select('insumos',$insumos, $lote->insumo_id,['class'=>'insumos form-control','placeholder'=>'','style'=>'width: 100%;', 'disabled']) }}            
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

@section('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.laboratorios').select2();
        
        $('.insumos').select2();
        $('.medicamentos').select2();

        $("input[id=rmedicamento]").click(function(){
            $(".insumos").prop("disabled",true);
            $(".medicamentos").select2({placeholder:'k'});
            $(".medicamentos").prop("disabled",false);
        });
        $("input[id=rinsumo]").click(function(){ 
            $(".insumos").prop("disabled",false);            
            $(".medicamentos").prop("disabled",true);           
            $(".insumos").select2({placeholder:''});
            
        });
    });
</script>    
@stop
{!! Form::close() !!}