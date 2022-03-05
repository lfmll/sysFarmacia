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
    <title>Reporte de Caja</title>
</head>
<body>
    <header>
        <h2 style="text-align:center">Cierre de Ayer</h2>
    </header>
    <main>
        <table width="100%">
            <thead>
                <tr>                                
                    <th>Monto de Apertura (Bs)</th>
                    <th>Hora Apertura</th>
                    <th>Hora Cierre</th>
                    <th>Gastos</th>
                    <th>Gananacias</th>                    
                </tr>
            </thead>
            <tbody>
                @foreach($cajas as $caja)
                <tr>                    
                    <td>{{$caja->monto_apertura}}</td>
                    <td>{{$caja->fecha}}</td>
                    <td>{{$caja->hora_inicio}}</td>
                    <td>{{$caja->hora_fin}}</td>
                    <td>{{$caja->gastos}}</td>
                    <td>{{$caja->ganancias}}</td>
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