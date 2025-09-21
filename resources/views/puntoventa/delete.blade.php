{!! Form::open(['url' => url('/puntoventa/' . $puntoventa->id), 'method' => 'DELETE', 'class' => 'd-inline-block']) !!}
<button type="button" class="btn btn-danger btn-sm eliminar" data-nombre="{{ $puntoventa->nombre }}">
    <i class="fa fa-trash"></i> Eliminar
</button>
{!! Form::close() !!}