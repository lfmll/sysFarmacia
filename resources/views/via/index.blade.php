@extends('adminlte::page')

@section('title', 'Vías de Administración')
@section('content_header')
    <h1></h1>
@stop
@section('content')
    
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Vías de Administración</h3>
      
                    <div class="box-tools">
                      <div class="input-group input-group-sm hidden-xs" style="width: 150px;">                              
                        <div class="input-group">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Buscar">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>                
                                <td>Descripción</td>
                                <td>Modificar</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($via as $v)
                            <tr>
                                <td>{{$v->id}}</td>
                                <td>{{$v->descripcion}}</td>
                                <td>
                                <a href="{{url('/via/'.$v->id.'/edit')}}" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
       
    
    <div class="container">
        <a href="{{url('/via/create')}}" class="btn btn-primary">Nuevo</a>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

