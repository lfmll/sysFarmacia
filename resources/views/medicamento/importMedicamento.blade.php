@extends('adminlte::page')
@section('title', 'Importar')

@section('content')
@include('sweetalert::alert')
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
                                'Importacion de Medicamentos',
                                "{{ session('success_message') }}",
                                'success')
                        </script>
                    @stop
                @endif
                @if (session('error_message'))
                    @section('js')
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Swal.fire(
                                'Importacion de Medicamentos',
                                "{{ session('error_message') }}",
                                'error')
                        </script>
                    @stop
                @endif
                <div class="container mt-5">
                    <h3>Importar Medicamentos</h3>
                    {!! Form::open(['url' => '/importM', 'method' => 'POST','enctype'=>'multipart/form-data']) !!}
                        {{Form::token()}} 
                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">MEDICAMENTO</th>
                                            <th scope="col">PRESENTACION</th>
                                            <th scope="col">CANTIDAD</th>
                                            <th scope="col">LABORATORIO</th>
                                            <th scope="col">LOTE</th>
                                            <th scope="col">FECHA_VENCIMIENTO</th>
                                            <th scope="col">PRECIO_COMPRA</th>
                                            <th scope="col">PRECIO_VENTA</th>
                                            <th scope="col">VALOR_TOTAL</th>                                            
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

