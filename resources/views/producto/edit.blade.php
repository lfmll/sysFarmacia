@extends('adminlte::page')

@section('title', 'Productos')

@section('content')
    <div class="col-md-6">   
        <div class="card card-primary"> 
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fas fa-fw fa-inbox"></i> Editar Producto</h3>                
            </div> 
            
            @include('producto.form',['producto'=>$producto,'url'=>'/producto/'.$producto->id,'method'=>'PATCH'])            
            
        </div>            
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop