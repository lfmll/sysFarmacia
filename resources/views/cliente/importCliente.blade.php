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
                @if (session('success_message'))
                    @section('js')
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Swal.fire(
                                'Importacion de Clientes',
                                '{{session('success_message')}}',
                                'success')
                        </script>
                    @stop
                @endif
                @if (session('error_message'))
                    @section('js')
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Swal.fire(
                                'Importacion de Clientes',
                                '{{session('error_message')}}',
                                'error')
                        </script>
                    @stop
                @endif
                <div class="container mt-5">
                    <h3>Importar Clientes</h3>
                    {!! Form::open(['url' => '/importC', 'method' => 'POST','enctype'=>'multipart/form-data']) !!}
                        {{Form::token()}} 
                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">Tipo Doc</th>
                                            <th scope="col">Nro Doc</th>
                                            <th scope="col">Cmpl</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Telf</th>
                                            <th scope="col">Direccion</th>                                            
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
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-file-excel"></i> Importar</button>
                                    </div>
                                </div>
                            </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </body>
    </html>
@stop