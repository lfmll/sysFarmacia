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
    <title>Reporte de Medicamento</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Medicamento</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>N. Comercial</th>
                    <th>N. Genérico</th>
                    <th>Stock</th>
                    <th>Stock Min</th>
                    <th>Presentación</th>
                    <th>Vía Adm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicamentos as $medicamento)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$medicamento->nombre_comercial}}</td>
                    <td>{{$medicamento->nombre_generico}}</td>
                    <td>{{$medicamento->stock}}</td>
                    <td>{{$medicamento->stock_minimo}}</td>
                    <td>{{$medicamento->formato->descripcion}}</td>
                    <td>{{$medicamento->via->descripcion}}</td>
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