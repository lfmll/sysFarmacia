{!! Form::open(['url' => $url, 'method' => $method]) !!}
<div class="form-group">
    {{Form::text('nombre',$clase->nombre,['class'=>'form-control', 'placeholder'=>'Nombre','required'])}}
</div>
<div class="form-group">
    {!! Form::select('clase', [ '(A) Tracto alimentario y metabolismo'=>'(A) Tracto alimentario y metabolismo',
                                '(B) Sangre y aparato hematopoyético'=>'(B) Sangre y aparato hematopoyético',
                                '(C) Sistema cardiovascular'=>'(C) Sistema cardiovascular',
                                '(D) Dermatológicos'=>'(D) Dermatológicos',
                                '(G) Sistema genitourinario y hormonas sexuales'=>'(G) Sistema genitourinario y hormonas sexuales',
                                '(H) Hormonales sistémicos'=>'(H) Hormonales sistémicos',
                                '(J) Antiinfecciosos'=>'(J) Antiinfecciosos',
                                '(K) Soluciones hospitalarias'=>'(K) Soluciones hospitalarias',
                                '(L) Antineoplásicos e inmunomoduladores'=>'(L) Antineoplásicos e inmunomoduladores',
                                '(M) Sistema músculo esquelético'=>'(M) Sistema músculo esquelético',
                                '(N) Sistema nervioso central'=>'(N) Sistema nervioso central',
                                '(P) Parasitología'=>'(P) Parasitología',
                                '(R) Sistema respiratorio'=>'(R) Sistema respiratorio',
                                '(S) Órganos de los sentidos'=>'(S) Órganos de los sentidos',
                                '(V) Varios'=>'(V) Varios'], null, 
                                ['class'=>'form-control','placeholder'=>'Clase ATQ', 'required']) !!}
</div>
<div class="box-footer">
    <div class="float-left">
        <a type="submit" class="btn btn-default" href="{{url('/clase')}}">Cancelar</a>
    </div>
    <div class="float-right">
        <input type="submit" value="Guardar" class="btn btn-success">   
    </div>
</div>
{!! Form::close() !!}