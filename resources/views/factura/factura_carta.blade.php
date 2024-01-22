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
    <title>Factura</title>
</head>
<body>
    <table style="width:100%">
        <tr>
            <th style="width:15%"></th>
            <th style="width:55%"></th>
            <th style="width:15%"></th> 
            <th style="width:15%"></th>
        </tr>
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
            <td><h4>FACTURA N</h4></td>
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
        
    </table>   
    <table style="width: 100%">
        <tr>
            <th colspan="4" style="text-align: center;">FACTURA</th>            
        </tr>
        <tr><td colspan="4" style="text-align: center;">(Con Derecho a Crédito Fiscal)</td></tr>
        <tr>
            <td style="width:15%"><h4>Fecha:</h4></td>
            <td style="width:55%">{{$factura->fechaEmision}}</td>
            <td style="width:15%"><h4>NIT/CI/CEX:</h4></td>
            <td style="width:15%">{{$factura->numeroDocumento}}</td>
        </tr>
        <tr>
            <td style="width:15%"><h4>Nombre/Razón Social:</h4></td>
            <td style="width:55%">{{$factura->nombreRazonSocial}}</td>
            <td style="width:15%"><h4>Cod. Cliente:</h4></td>
            <td style="width:15%">{{$factura->codigoCliente}}</td>
        </tr>
    </table>
    <table class="detalle" style="width: 100%">
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
                <td colspan="4" style="border-collapse: collapse;"></td>
                <td colspan="2" style="border: 1px solid black; text-align: right">SUBTOTAL Bs</td>
                <td style="border: 1px solid black; text-align: right">{{$venta->subtotal}}</td>
            </tr>
            <tr>
                <td colspan="4" style="border-collapse: collapse;"></td>
                <td colspan="2" style="border: 1px solid black; text-align: right">DESCUENTO Bs</td>
                <td style="border: 1px solid black; text-align: right">{{$venta->descuento}}</td>
            </tr>
            <tr>
                <td colspan="4" style="border-collapse: collapse;">Son: {{$venta->literal}} 00/100 Bolivianos</td>
                <td colspan="2" style="border: 1px solid black; text-align: right">TOTAL Bs</td>
                <td style="border: 1px solid black; text-align: right">{{$venta->total}}</td>
            </tr>
            <tr>
                <td colspan="4" style="border-collapse: collapse;"></td>
                <td colspan="2" style="border: 1px solid black; text-align: right">MONTO GIFT CARD Bs</td>
                <td style="border: 1px solid black; text-align: right">{{$venta->monto_giftcard}}</td>
            </tr>
            <tr>
                <td colspan="4" style="border-collapse: collapse;"></td>
                <td colspan="2" style="border: 1px solid black; text-align: right">MONTO A PAGAR Bs</td>
                <td style="border: 1px solid black; text-align: right">{{$venta->total}}</td>
            </tr>
            <tr>
                <td colspan="4" style="border-collapse: collapse;"></td>
                <td colspan="2" style="border: 1px solid black; text-align: right">IMPORTE BASE CRÉDITO FISCA.</td>
                <td style="border: 1px solid black; text-align: right">{{$venta->importe_iva}}</td>
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