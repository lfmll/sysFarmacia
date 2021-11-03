@extends('adminlte::page')

@section('title', 'Dosis')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dosis</h3>                                                    
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>                
                                    <td>Descripción</td>
                                    <td>Modificar</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medida as $med)
                                <tr>
                                    <td>{{$med->id}}</td>
                                    <td>{{$med->descripcion}}</td>
                                    <td>
                                    <a href="{{url('/medida/'.$med->id.'/edit')}}" class="btn btn-primary"><i class="fa fa-edit"></i>Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                                      
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('/medida/create')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo</a>
                </div>
            </div>    
        </div>    
    </div>        
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

