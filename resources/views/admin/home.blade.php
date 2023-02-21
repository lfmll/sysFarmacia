@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Sistema Farmacia</h1>
@stop

@section('content')
    <p>Bienvenido.</p>
    
      <canvas id="myChart" style="width: 100%; max-width:700px"></canvas>  
      
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script> console.log('Hi!'); </script>
    <script>
        
        var labels ={{ Illuminate\Support\Js::from($labels) }};
        
        var datas={{ Illuminate\Support\Js::from($datas) }};

        const ctx = document.getElementById('myChart');
      
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            backgroundColor: 'rgb(255, 99, 132)',
            datasets: [{
              label: '# de Ventas por Mes',
              fill: true,
              lineTension: 0,
              data: datas,
              borderWidth: 1              
            }]
          },
          responsive: true, 
          options:{
            scales:{
              y:{
                min:0,
                ticks:{
                  stepSize:1
                }
              }
            }
          }
        });
      </script>
      
@stop
