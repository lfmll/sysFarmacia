{!! Form::open(['url'=>'/cliente/'.$cli->id, 'method'=>'DELETE', 'class'=> 'inline-block']) !!}
<button type="submit" class="btn btn-danger eliminar"><i class="fa fa-trash"></i> Eliminar</button>
{!! Form::close() !!}