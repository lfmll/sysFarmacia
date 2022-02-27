<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
    </style>
    <title>Reporte de Lotes</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Lotes</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Nro Lote</th>
                    <th>Cantidad</th>                    
                    <th>Fecha Vencimiento</th>
                    <th>Laboratorio</th>
                    <th>Medicamento/Insumo</th>
                    <th>Precio Compra</th>            
                    <th>Precio Venta</th>                    
                </tr>
            </thead>
            <tbody>
                @foreach($lotes as $lote)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$lote->numero}}</td>
                    <td>{{$lote->cantidad}}</td>
                    <td>{{$lote->fecha_vencimiento}}</td>
                    <td>{{$lote->laboratorio_id}}</td>
                    @if (!is_null($lote->medicamento))
                        <td>
                            {{$lote->medicamento->nombre_comercial}}
                        </td>
                    @endif
                    @if (!is_null($lote->insumo))
                        <td>
                            {{$lote->insumo->nombre}}
                        </td>
                    @endif 
                    <td>{{$lote->precio_compra}}</td>
                    <td>{{$lote->precio_venta}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <hr>
    <footer>
        <h3 style="text-align:right">{{$fecha}}</h3>
    </footer>
</body>
</html>