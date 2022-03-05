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
    <title>Reporte de Productos</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Productos</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Stock Mínimo</th>
                    <th>Fecha Vencimiento</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Ganancia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $productos)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$productos->nombre}}</td>
                    <td>{{$productos->stock}}</td>
                    <td>{{$productos->stock_minimo}}</td>
                    <td>{{$productos->fecha_vencimiento}}</td>
                    <td>{{$productos->precio_compra}}</td>
                    <td>{{$productos->precio_venta}}</td>
                    <td>{{$productos->ganancia}}</td>
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