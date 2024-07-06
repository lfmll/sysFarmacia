<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        h4{margin:0;}
        table, th, td {
            table-layout: auto;
            word-wrap:break-word;      
            font-size: 8px;			
        }

        hr{
            width: 90%;
            margin-left: auto; 
            margin-right: auto;
        }

        table.detalle, .leyenda{
            border-collapse: collapse;
            table-layout: auto;
            margin-left: auto; 
            margin-right: auto; 
            width: 90%;
        }

        table.detalle td {
            border-bottom: 0.5px solid;
        } 

		body {
			margin: 0;
            padding: 0;
		}

		@page {
            height: auto;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }
    </style>
    <title>Factura</title>
</head>
<body>
    <table width="100%">
        <tr>
            <td colspan="4" style="text-align: center;"><h2>FACTURA</h2></td>            
        </tr>
		<tr>
			<td colspan="4" style="text-align: center;">
				@if ($factura->codigoSucursal==0)
					<h4>Casa Matriz</h4>
				@else
					<h4>Sucursal {{$factura->codigoSucursal}}</h4>
				@endif
			</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center;">{{$factura->direccion}}</td>
		</tr>
        <tr>
            <td colspan="4" style="text-align: center;">{{$factura->municipio}} - Bolivia</td>
        </tr>
    </table>
    <hr>
    <table width="100%">
        <tr>
            <td colspan="4" style="text-align: center;">NIT: {{$factura->nitEmisor}}</td>                                    
        </tr>			
		<tr>
			<td colspan="4" style="text-align: center;">FACTURA N: {{$factura->numeroFactura}}</td>
		</tr>
        <tr>             
            <td colspan="4" style="text-align: center;"><h4>COD. AUTORIZACION</h4></td>
        </tr>
		<tr>
			<td colspan="4" style="text-align: center;"><h4>{{$factura->cuf}}</h4></td>
		</tr>
        <tr>
            <td colspan="4" style="text-align: center;"><h4>Nombre/Razón Social: {{$factura->nombreRazonSocialEmisor}}</h4></td> 
        </tr>        
    </table>   
    <hr>
    <table style="width: 100%">
        <tr>
            <td colspan="4" style="text-align: center;"><h4>NIT/CI/CEX: {{$factura->numeroDocumento}}</h4></td>            
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;"><h4>{{$factura->nombreRazonSocial}}</h4></td>
        </tr>
        <tr><td colspan="4" style="text-align: center;">Fecha: {{$factura->fechaEmision}}</td></tr>       
    </table>
    <hr>
    <table class="detalle">
        <thead>
            <tr style="border-bottom: 1px solid;">
                <th style="text-align: left">PRODUCTO</th>
                <th style="text-align: right">CANTIDAD</th>
                <th style="text-align: right">P/U</th>
                <th style="text-align: right">SUBTOTAL</th>
            </tr>            
        </thead>
        <tbody style="border-bottom: 1px solid;">
        @foreach($detalleFactura as $item)
            <td>{{$item->descripcion}}</td>
            <td style="text-align: right">{{$item->cantidad}}</td>
            <td style="text-align: right">{{$item->precioUnitario}}</td>
            <td style="text-align: right">{{$item->subTotal}}</td>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right">TOTAL</td>
                <td style="border: 0.5px; text-align: right">{{$venta->total}}</td>
            </tr>                     
            @if($venta->descuento > 0)
            <tr>
                <td colspan="3" style="text-align: right">DESCUENTO</td>
                <td style="border: 0.5px; text-align: right">{{$venta->descuento}}</td>
            </tr>            
            @endif
            <tr>
                <td colspan="3" style="text-align: right">MONTO</td>
                <td style="border: 0.5px; text-align: right">{{$venta->monto_pagar}}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">CAMBIO</td>
                <td style="border: 0.5px; text-align: right">{{$venta->cambio_venta}}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">IMPORTE BASE CREDITO FISCAL</td>
                <td style="border: 0.5px; text-align: right">{{$venta->importe_iva}}</td>
            </tr>            
        </tfoot>
    </table>
    <br>
    <table class="leyenda">
        <tr>
            <td colspan="4" style="text-align: center;">
                "ESTA FACTURA CONTRIBUYE AL DESARROLLO DE NUESTRO PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY"
            </td>            
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">{{$factura->leyenda}}</td>
        </tr>
    </table>
</body>
</html>