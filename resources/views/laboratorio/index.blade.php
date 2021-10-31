@extends('adminlte::page')

@section('title', 'Laboratorios')
@section('content_header')
    <h1></h1>
@stop
@section('content')
    
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Laboratorios</h3>
      
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
                                <td>Nombre</td>
                                <td>Dirección</td>
                                <td>Teléfono</td>
                                <td>Procedencia</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laboratorio as $lab)
                            <tr>
                                <td>{{$lab->id}}</td>
                                <td>{{$lab->nombre}}</td>
                                <td>{{$lab->direccion}}</td>
                                <td>{{$lab->telefono}}</td>
                                <td>{{$lab->procedencia}}</td>
                                <td>
                                <a href="{{url('/laboratorio/'.$lab->id.'/edit')}}" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
       
    
    <div class="container">
        <a href="{{url('/laboratorio/create')}}" class="btn btn-primary">Nuevo</a>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

