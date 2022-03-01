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
    <title>Reporte de Ventas</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Lista de Ventas del Mes</h2>
        <h3 style="text-align:center">{{$mes}}</h3>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Comprobante</th>
                    <th>Forma de Pago</th>
                    <th>Total</th>
                    <th>Glosa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$venta->comprobante}}</td>
                    <td>{{$venta->forma_pago}}</td>
                    <td>{{$venta->pago_venta - $venta->cambio_venta}}</td>
                    <td>{{$venta->glosa}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <hr>
    <footer>
        
    </footer>
</body>
</html>