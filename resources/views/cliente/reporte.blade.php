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
    <title>Reporte de Clientes</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Clientes</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th> 
                    <th>Tipo Doc</th>               
                    <th>Nro Doc</th>
                    <th>Complemento</th>
                    <th>Nombre o Razon Social</th>
                    <th>Correo</th>
                    <th>Telefono</th>                    
                    <th>Direccion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$cliente->tipo_documento}}</td>
                    <td>{{$cliente->numero_documento}}</td>
                    <td>{{$cliente->complemento}}</td>
                    <td>{{$cliente->nombre}}</td>
                    <td>{{$cliente->correo}}</td>
                    <td>{{$cliente->telefono}}</td>
                    <td>{{$cliente->direccion}}</td>
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