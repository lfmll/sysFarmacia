@extends('adminlte::page')
@section('css')
    <style>             
        .small-box>.inner{
            padding: 10px;
        }
        ion-icon {
            font-size: 100px;
        }
    </style>
@stop

@section('title', 'Reportes')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">        
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{$cantVentas}}</h3>
                    <p>Cantidad de Ventas</p>
                </div>
                <div class="icon">
                    <ion-icon name="cart-outline"></ion-icon>
                </div>                
                <a href="{{url('reporteVentaDia')}}" class="small-box-footer">Reporte Diario <i class="fa fa-arrow-circle-down"></i></a>
                <a href="{{url('reporteVentaMensual')}}" class="small-box-footer">Reporte Mensual <i class="fa fa-arrow-circle-down"></i></a>
                <a href="{{url('reporteVentaAnual')}}" class="small-box-footer">Reporte Anual <i class="fa fa-arrow-circle-down"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{$cantCompras}}</h3>
                    <p>Cantidad de Compras</p>                    
                </div>
                <div class="icon">
                    <ion-icon name="bag-outline"></ion-icon>
                </div>
                <a href="{{url('reporteCompraDia')}}" class="small-box-footer">Reporte Diario <i class="fa fa-arrow-circle-down"></i></a>
                <a href="{{url('reporteCompraMensual')}}" class="small-box-footer">Reporte Mensual <i class="fa fa-arrow-circle-down"></i></a>
                <a href="{{url('reporteCompraAnual')}}" class="small-box-footer">Reporte Anual <i class="fa fa-arrow-circle-down"></i></a>                
            </div>            
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-warning text-white">
                <div class="inner">
                    <h3>{{$cantCierres}}</h3>
                    <p>Caja</p>
                </div>
                <div class="icon">
                    <ion-icon name="calculator-outline"></ion-icon>
                </div>
                <a href="{{url('reporteCierreAnterior')}}" class="small-box-footer">Reporte <i class="fa fa-arrow-circle-down"></i></a>
            </div>            
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{$cantLotes}}</h3>
                    <p>Lote en Vencimiento</p>
                </div>
                <div class="icon">
                    <ion-icon name="today-outline"></ion-icon>
                </div>
                <a href="{{url('reporteLotesVencimiento')}}" class="small-box-footer">Reporte <i class="fa fa-arrow-circle-down"></i></a>                
            </div>
        </div>
        
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
@stop