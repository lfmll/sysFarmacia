<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        h4{margin:0;}
        table, th, td {
            table-layout: fixed;
            word-wrap:break-word;        
        }

        table.detalle{
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
        }        
    </style>
    <title>Nota de Crédito - Débito</title>
</head>
<body>
    <table style="width:100%; font-size: 14px;">        
        <tr>
            <td style="text-align: center;"><h4>{{$factura->razonSocialEmisor}}</h4></td>
            <td></td>          
            <td><h4>NIT</h4></td>
            <td>{{$factura->nitEmisor}}</td>            
        </tr>
        <tr>
            <td style="text-align: center;">
                @if ($factura->codigoSucursal==0)
                    Casa Matriz
                @endif
            </td>  
            <td></td>          
            <td><h4>FACTURA N°</h4></td>
            <td>{{$factura->numeroFactura}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">
                @if ($factura->codigoPuntoVenta==null)
                    Punto Venta 0
                @else
                    Punto Venta {{$factura->codigoPuntoVenta}}
                @endif
                <br>
                {{$factura->direccion}}
                <br>
                {{$factura->municipio}}
            </td>
            <td></td>
            <td><h4>COD. AUTORIZACION</h4></td>            
            <td>{{$factura->cuf}}</td>
        </tr> 
        <tr>
            <td></td>
            <td style="text-align: center;"><h3><b>NOTA CRÉDITO - DÉBITO</b></h3></td>
            <td></td>
        </tr>       
    </table>  
    <table style="width: 100%; font-size: 14px; margin-top: 10px; margin-bottom: 10px;">
        <tr>
            <td><h4>Fecha:</h4></td>
            <td>{{ date('d-m-Y h:i A', strtotime($notaAjuste->fechaEmision)) }}</td>
            <td></td>
            <td><h4>NIT/CI/CEX:</h4></td>
            <td>{{$factura->numeroDocumento}}</td>
        </tr>
        <tr>
            <td><h4>Nombre/Razón Social:</h4></td>
            <td>{{$factura->nombreRazonSocial}}</td>
            <td></td>
            <td><h4>Cod. Cliente:</h4></td>
            <td>{{$factura->codigoCliente}}</td>            
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><h4>Fecha Factura:</h4></td>
            <td>{{ date('d-m-Y h:i A', strtotime($factura->fechaEmision))}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><h4>N° Autoriación/CUF:</h4></td>
            <td>{{$notaAjuste->codigoRecepcion}}</td>
        </tr>
    </table>
    <p><b>DATOS FACTURA ORIGINAL</b></p>
    <table class="detalle1" style="width: 100%; border-collapse: collapse;">                        
        <thead>
            <th style="border: 1px solid black;">CODIGO PRODUCTO/SERVICIO</th>
            <th style="border: 1px solid black;">CANTIDAD</th>
            <th style="border: 1px solid black;">UNIDAD DE MEDIDA</th>
            <th style="border: 1px solid black;">DESCRIPCIÓN</th>
            <th style="border: 1px solid black;">PRECIO UNITARIO</th>
            <th style="border: 1px solid black;">DESCUENTO</th>
            <th style="border: 1px solid black;">SUBTOTAL</th>
        </thead>
        <tbody>
            @foreach($detalleFactura as $item)
                <td style="border: 1px solid black;">{{$item->codigoProducto}}</td>
                <td style="border: 1px solid black;">{{$item->cantidad}}</td>
                <td style="border: 1px solid black;">{{$item->unidadMedida}}</td>
                <td style="border: 1px solid black;">{{$item->descripcion}}</td>
                <td style="border: 1px solid black; text-align: right">{{$item->precioUnitario}}</td>
                <td style="border: 1px solid black; text-align: right">{{$item->montoDescuento}}</td>
                <td style="border: 1px solid black; text-align: right">{{$item->subTotal}}</td>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="border-collapse: collapse;"></td>
                <td colspan="3" style="border: 1px solid black; text-align: right">MONTO A PAGAR Bs</td>
                <td style="border: 1px solid black; text-align: right">{{$factura->montoTotal}}</td>
            </tr>
        </tfoot>
    </table>
    <p><b>DATOS DE LA DEVOLUCIÓN O RESCISIÓN</b></p>
    <table class="detalle2" style="width: 100%; border-collapse: collapse;">   
        <thead>
            <th style="border: 1px solid black;">CODIGO PRODUCTO/SERVICIO</th>
            <th style="border: 1px solid black;">CANTIDAD</th>
            <th style="border: 1px solid black;">UNIDAD DE MEDIDA</th>
            <th style="border: 1px solid black;">DESCRIPCIÓN</th>
            <th style="border: 1px solid black;">PRECIO UNITARIO</th>
            <th style="border: 1px solid black;">DESCUENTO</th>
            <th style="border: 1px solid black;">SUBTOTAL</th>
        </thead>     
        <tbody>
            @foreach($detalleNotaAjuste as $item)
                <td style="border: 1px solid black;">{{$item->codigoProducto}}</td>
                <td style="border: 1px solid black;">{{$item->cantidad}}</td>
                <td style="border: 1px solid black;">{{$item->unidadMedida}}</td>
                <td style="border: 1px solid black;">{{$item->descripcion}}</td>
                <td style="border: 1px solid black; text-align: right">{{$item->precioUnitario}}</td>
                <td style="border: 1px solid black; text-align: right">{{$item->montoDescuento}}</td>
                <td style="border: 1px solid black; text-align: right">{{$item->subTotal}}</td>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="border-collapse: collapse;"></td>
                <td colspan="3" style="border: 1px solid black; text-align: right">MONTO TOTAL DEVUELTO</td>
                <td style="border: 1px solid black; text-align: right">{{$notaAjuste->montoTotalDevuelto}}</td>
            </tr>            
        </tfoot>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:80%">
                "ESTA FACTURA CONTRIBUYE AL DESARROLLO DE NUESTRO PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY"
            </td>
            <td></td>
        </tr>
        <tr>
            <td>{{$factura->leyenda}}</td>
        </tr>
    </table>
</body>
</html>