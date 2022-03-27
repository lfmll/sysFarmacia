@extends('adminlte::page')
@section('title', 'Importar')

@section('content')
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            
        </head>
        <body>
            <div class="flex-center position-ref full-height">
                
                <div class="container mt-5">
                    <h3>Importar Medicamentos</h3>
                    {!! Form::open(['url' => '/importM', 'method' => 'POST','enctype'=>'multipart/form-data']) !!}
                        {{Form::token()}} 
                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">Medicamento</th>
                                            <th scope="col">Presentación</th>
                                            <th scope="col">Vía</th>
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
                                        {{Form::file('cover')}}
                                        {{-- <label class="custom-file-label" for="imedicamento">Seleccionaar Archivo</label> --}}
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