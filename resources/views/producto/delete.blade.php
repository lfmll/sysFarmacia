{!! Form::open(['url'=>'/producto/'.$producto->id, 'method'=>'DELETE', 'class'=> 'd-inline-block']) !!}
<a class="btn btn-danger eliminar"><i class="fa fa-trash"></i> Eliminar</a>
{!! Form::close() !!}