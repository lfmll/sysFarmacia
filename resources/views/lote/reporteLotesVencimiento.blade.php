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
        <h2 style="text-align:center">Lista de Lotes en Vencimiento</h2>
        <h3 style="text-align:center">{{$fecha}}</h3>
    </header>
    <main>
        <p><strong>Lotes por Vencer</strong></p>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Cod. Lote</th>
                    <th>Producto</th>
                    <th>Laboratorio</th>
                    <th>Fecha Vencimiento</th>
                    <th>Cantidad</th>                                        
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotesPorVencer as $lotep)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$lotep->numero}}</td>
                    @if (!is_null($lotep->medicamento))
                        <td>
                            {{$lotep->medicamento->nombre_comercial}}
                        </td>
                    @endif
                    @if (!is_null($lotep->insumo))
                        <td>
                            {{$lotep->insumo->nombre}}
                        </td>
                    @endif
                    <td>{{$lotep->laboratorio->nombre}}</td>
                    <td>{{$lotep->fecha_vencimiento}}</td>
                    <td>{{$lotep->cantidad}}</td>
                    <td>{{$lotep->precio_compra}}</td>
                    <td>{{$lotep->precio_venta}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <p><strong>Lotes Vencidos</strong></p>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Cod. Lote</th>
                    <th>Producto</th>
                    <th>Laboratorio</th>
                    <th>Fecha Vencimiento</th>
                    <th>Cantidad</th>                                        
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotesVencidos as $lotev)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$lotev->numero}}</td>
                    @if (!is_null($lotev->medicamento))
                        <td>{{$lotev->medicamento->nombre_comercial}}</td>
                    @endif
                    @if (!is_null($lotev->insumo))
                        <td>{{$lotev->insumo->nombre}}</td>
                    @endif
                    <td>{{$lotev->laboratorio->nombre}}</td>
                    <td>{{$lotev->fecha_vencimiento}}</td>
                    <td>{{$lotev->cantidad}}</td>
                    <td>{{$lotev->precio_compra}}</td>
                    <td>{{$lotev->precio_venta}}</td>
                </tr>
                @endforeach
            </tbody>
    </main>
    <hr>
    <footer>        
    </footer>
</body>
</html>