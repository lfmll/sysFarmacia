{!! Form::open(['url' => $url, 'method' => $method]) !!}
<div class="form-group">
    {{Form::text('nombre',$clase->nombre,['class'=>'form-control', 'placeholder'=>'Nombre','required'])}}
</div>
<div class="form-group">
    {!! Form::select('clase', [ 'A: Tracto alimentario y metabolismo',
                                'B: Sangre y aparato hematopoyético',
                                'C: Sistama cardiovascular',
                                'D: Dermatológicos',
                                'G: Sistema genitourinario y hormonas sexuales',
                                'H: Hormonales sistémicos',
                                'J: Antiinfecciosos',
                                'K: Soluciones hospitalarias',
                                'L: Antineoplásticos e inmunomoduladores',
                                'M: Sistema músculo esquelético',
                                'N: Sistema nervioso central',
                                'P: Paristología',
                                'R: Sistema respiratorio',
                                'S: Órganos de los sentidos',
                                'V: Varios'], null, 
                                ['class'=>'form-control','placeholder'=>'Clase ATQ', 'required']) !!}
</div>
<div class="form-group">
    <a type="submit" class="btn btn-default" href="{{url('/clase')}}">Cancelar</a>
    <input type="submit" value="Guardar" class="btn btn-success">  
</div>
{!! Form::close() !!}