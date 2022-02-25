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
    <title>Reporte de Laboratorios</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Listado de Laboratorios</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <th>No</th>                
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Procedencia</th>
                    <th>Anotaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laboratorios as $laboratorio)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$laboratorio->nombre}}</td>
                    <td>{{$laboratorio->direccion}}</td>
                    <td>{{$laboratorio->telefono}}</td>
                    <td>{{$laboratorio->procedencia}}</td>
                    <td>{{$laboratorio->anotacion}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <hr>
    <footer>
        <h3 style="text-align:center">{{$fecha}}</h3>
    </footer>
</body>
</html>