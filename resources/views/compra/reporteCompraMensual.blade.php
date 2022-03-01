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
    <title>Reporte de Compras</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Lista de Compras Mensuales</h2>
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
                @foreach($compras as $compra)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$compra->comprobante}}</td>
                    <td>{{$compra->forma_pago}}</td>
                    <td>{{$compra->pago_compra - $compra->cambio_compra}}</td>
                    <td>{{$compra->glosa}}</td>
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