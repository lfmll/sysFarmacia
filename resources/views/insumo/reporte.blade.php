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
    <title>Reporte de Insumos</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Insumos</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Stock</th>
                    <th>Stock Min</th>
                </tr>
            </thead>
            <tbody>
                @foreach($insumos as $insumo)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$insumo->nombre}}</td>
                    <td>{{$insumo->descripcion}}</td>
                    <td>{{$insumo->stock}}</td>
                    <td>{{$insumo->stock_minimo}}</td>
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