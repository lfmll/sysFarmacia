{!! Form::open(['url'=>'/puntoventa/'.$puntoventa->id, 'method'=>'DELETE', 'class'=> 'd-inline-block']) !!}
<a class="btn btn-danger btn-sm eliminar" data-nombre="{{$puntoventa->nombre}}"><i class="fa fa-trash"></i> Eliminar</a>
{!! Form::close() !!}