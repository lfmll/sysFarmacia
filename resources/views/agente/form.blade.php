{!! Form::open(['url' => $url, 'method' => $method]) !!}
    <div class="box-body">        
        <div class="form-group">
            {{Form::text('nombre',$agente->nombre,['class'=>'form-control', 'placeholder'=>'Nombre Completo','required'])}}
        </div>
        <div class="form-group">
            {{Form::text('telefonos',$agente->telefonos,['class'=>'form-control', 'placeholder'=>'Telefonos','required'])}}
        </div>
        <div class="form-group">
            {{Form::textarea('anotacion',$agente->anotacion,['class'=>'form-control', 'placeholder'=>'Anotacion','rows'=>5])}}
        </div>
        <div class="form-group">
            {{ Form::select('laboratorios',$laboratorios, $agente->laboratorio_id, ['class'=>'laboratorios form-control','placeholder'=>'','required','style'=>'weight: 100%;']) }}
        </div>
    </div>
    <div class="box-footer">
        <div class="float-left">
            <a type="submit" class="btn btn-default" href="{{url('/agente')}}">Cancelar</a>
        </div>
        <div class="float-right">
            <input type="submit" value="Guardar" class="btn btn-success">
        </div>
    </div>
{!! Form::close() !!}


@section('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.laboratorios').select2();        
    });
</script>    
@stop