@extends('adminlte::page')
@section('title', 'Importar')

@section('content')
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            
            <script src="{{asset('js/app.js')}}"></script>
            <link rel="stylesheet" href="{{asset('css/app.css')}}">
        </head>
        <body>
            <div class="flex-center position-ref full-height">
                
                <div class="container mt-5">
                    <h3>Importar Medicamentos</h3>
            
                    @if ( $errors->any() )
            
                        <div class="alert alert-danger">
                            @foreach( $errors->all() as $error )<li>{{ $error }}</li>@endforeach
                        </div>
                    @endif
            
                    @if(isset($numRows))
                        <div class="alert alert-sucess">
                            Se importaron {{$numRows}} registros.
                        </div>
                    @endif
            
                    {!! Form::open(['url' => '/importM', 'method' => 'POST']) !!}       
                        {{Form::token()}} 
                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">Medicamento</th>
                                            <th scope="col">Presentación</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Precio Compra</th>
                                            <th scope="col">Laboratorio</th>
                                            <th scope="col">Lote</th>
                                            <th scope="col">Fecha Vencimiento</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-file">
                                        <input type="file" name="imedicamento" class="custom-file-input" id="imedicamento">
                                        <label class="custom-file-label" for="imedicamento">Seleccionar Archivo</label>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Importar</button>
                                    </div>
                                </div>
                            </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </body>
    </html>
@stop