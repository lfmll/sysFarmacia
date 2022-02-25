@extends('adminlte::page')

@section('title', 'Factura')

@section('content')
<header>
    <div>
        <div style="text-align: center; font-size: 13px; text-transform: uppercase;">
            {{ $sucursal }}<br>
            {{ $direccion }}<br>
            TELEFONO - {{ $telefono }}<br>
            Santa Cruz - Bolivia
        </div>
        <div>
            <h4 id="factura">FACTURA</h4>
        </div>
        <div id="datosfactura">
            <div>
                <span><strong>NIT:</strong></span>{{ $facturanit }}
            </div>
            <div>
                <span><strong>Nº FACTURA:</strong></span>{{ $nroFactura }}
            </div>
            <div>
                <span><strong>Nº AUTORIZACION:</strong></span>{{ $nroAutorizacion }}
            </div>
        </div>
        <div id="descripcion">
            <p>{{ $actividad }}</p>
        </div>
    </div>
    <div id="project">
        <div>
            <span>
                <strong>Fecha:</strong>{{ $fecha }}
                {{-- <strong> Hora:</strong>{{ $hora }} --}}
            </span>
        </div> 
        <div>
            <span><strong>NIT/CI:</strong></span> {{ $facturanit }}
        </div>
        <div>
            <span><strong>Señor(es):</strong></span> {{ $razonSocial }}
        </div>
    </div>
</header>
<main>
    <div id="detalleVenta">
        <table>
            <thead class="encabezado">
                <tr>
                    <th> CANTIDAD</th>
                    <th class="desc"> DETALLE </th>
                    <th> P.UNITARIO </th>
                    <th> SUBTOTAL </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <div id="total">
            <div>
                <p>
                    <strong>TOTAL Bs. </strong>
                </p>
            </div>
            
            <div>
                <p>
                    <strong>TOTAL NETO Bs. </strong>
                </p>
            </div>
        </div>
    </div>
    <div id="totLiteral">
        <span>SON: </span>
    </div>
    <div id="notices">
        <div class="notice">
            <div>
                <strong>CODIGO DE CONTROL: </strong>
            </div>
            <div>
                <strong>FECHA LIMITE DE EMISION: </strong>
            </div>
        </div>
    </div>
</main>

    {{-- <img src="/{{$codigoQR}}" alt="codQR" height="100" width="100" /> --}}
<div class="mb-4">
    {!! DNS2D::getBarcodeHTML($datosQR,'QRCODE',3,3) !!}        
</div>

<footer>
    <p>
        Ley Nro 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad
    </p>
    <p>"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS, EL USO ILICITO DE ESTA SERA SANCIONADO DE ACUERDO A LA LEY"</p>
</footer>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop