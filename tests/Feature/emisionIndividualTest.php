<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ajuste;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\Catalogo;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Medicamento;
use App\Models\Parametro;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Cliente;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Luecano\NumeroALetras\NumeroALetras;

class emisionIndividualTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testEmisionIndividual1()
    {
        // codigoAmbiente = 2
        // codigoEmision = 1
        // archivo = su cadena de archivo
        // codigoSistema = su código de sistema
        // hashArchivo = el hash correspondiente a su cadena archivo
        // codigoSucursal = 0
        // codigoModalidad = su modalidad de Facturación
        // cuis = su CUIS
        // codigoPuntoVenta = 1
        // fechaEnvio = la fecha actual en formato UTC extendido sin zona horaria
        // tipoFacturaDocumento = 1
        // nit = su NIT
        // codigoDocumentoSector = 1
        // cufd = su CUFD válido 

        $empresa = Empresa::first();
        $agencia = Agencia::where('codigo',0)->first();
        $puntoVenta = PuntoVenta::where('codigo',1)->first();
        $cuis = Cuis::where('codigo_cuis',"5E20BB5C")->first();
        $cufd = Cufd::where('estado','A')
                    ->where('cuis_id',$cuis->id)
                    ->first();

        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlFacturacion = $ajuste->wsdl."/ServicioFacturacionCompraVenta?wsdl";
        $clienteFacturacion = Ajuste::consumoSIAT($token,$wsdlFacturacion);
        $errores = [];
        if ($clienteFacturacion->verificarComunicacion()->RespuestaComunicacion->mensajesList->codigo == "926") 
        {
            // Emision Individual
            // Construccion de entidades
            //Medicamento y Lote
            for ($i=0; $i < 50; $i++) { 
                $cantMedicamento = Medicamento::count()+1;
                $medicamento = Medicamento::factory()->create([
                    'codigo_actividad' => '477310',                
                    'codigo_producto' => 'P'.str_pad($cantMedicamento, 5, "0", STR_PAD_LEFT),
                    'codigo_producto_sin' => Catalogo::where('codigo_actividad','477310')
                                                    ->where('cuis_id',$cuis->id)
                                                    ->inRandomOrder()
                                                    ->value('codigo_producto_sin'),
                    'nombre_comercial' => $this->faker->word(),
                    'stock' => 0,
                    'stock_minimo' => 5,
                    'codigo_clasificador' => Parametro::where('tipo_parametro_id',11)
                                                    // ->where('cuis_id',$cuis->id)
                                                    ->whereIn('descripcion',['COMPRIMIDO','CAPSULA','FRASCO','SOBRES'])
                                                    ->inRandomOrder()
                                                    ->value('codigo_clasificador'),
                    'via_id' => 1
                ]);
                $precio_compra = $this->faker->randomFloat(2,0.5,10);
                $precio_venta = round($precio_compra * 1.10,2);
                $lote = Lote::factory()->create([
                    'numero' => $this->faker->bothify('Lote-####'),
                    'cantidad' => 200,
                    'fecha_vencimiento' => now()->addMonths(18),
                    'laboratorio_id' => rand(1,5),
                    'medicamento_id' => $medicamento->id,
                    'precio_compra' => $precio_compra,
                    'precio_venta' => $precio_venta,
                    'ganancia' => $precio_venta - $precio_compra,
                    'estado' => 'A'                                
                ]);
                //Cliente
                $cliente = Cliente::factory()->create([
                    'tipo_documento' => '1',
                    'numero_documento' => $this->faker->unique()->numerify('#######'),
                    'complemento' => null,
                    'nombre' => $this->faker->name(),
                    'correo' => $this->faker->unique()->safeEmail(),
                    'telefono' => $this->faker->phoneNumber(),
                    'direccion' => $this->faker->address(),
                    'estado' => 'A'
                ]);
                //Venta y DetalleVenta
                $cantidad = $this->faker->numberBetween(1,10);
                $venta = new Venta();
                $venta->fecha_venta = Carbon::now('America/La_Paz')->toDateTimeString();
                $venta->subtotal = $precio_venta * $cantidad;
                $venta->descuento = 0;
                $venta->total = $venta->subtotal - $venta->descuento;
                $venta->importe_iva = round($venta->total * 0.13,2);
                $literal = new NumeroALetras();
                $venta->literal = $literal->toMoney($venta->total, 2, 'BOLIVIANOS', 'CENTAVOS');
                $venta->estado = 'A';
                $venta->monto_pagar = $venta->total;
                $venta->cambio_venta = 0;
                $venta->monto_giftcard = 0;
                $venta->user_id = 1;
                $venta->cliente_id = $cliente->id;
                $venta->save();

                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->cantidad = $cantidad;
                $detalle->precio_venta = $precio_venta;
                $detalle->lote_id = $lote->id;
                $detalle->save();
                // Actualizar stock del medicamento
                $lote->cantidad -= $cantidad;
                $lote->save();
                $medicamento->stock -= $cantidad;
                $medicamento->save();
                //Factura y DetalleFactura
                $fecha = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
                $fecha = $fecha.'.'.str_pad(now('America/La_Paz')->mili, 3, "0", STR_PAD_LEFT);
                $fecha_emision = Factura::deFechaNumero($fecha);

                $factura = new Factura();
                $factura->nitEmisor = $empresa->nit;
                $factura->razonSocialEmisor = $empresa->nombre;
                $factura->municipio = $agencia->municipio;
                $factura->telefono = $agencia->telefono;
                $factura->numeroFactura = Factura::count()+1;
                $factura->cufd = $cufd->codigo_cufd;
                $cuf = Ajuste::generarCUF($empresa->nit,
                                                $agencia->codigo,
                                                $fecha_emision,
                                                $empresa->modalidad,
                                                1, //Emision Online=1, Offline=2
                                                1, //Tipo Factura documento=1, documento sin valor fiscal=2, documento ajuste=3
                                                1, //Tipo Documento factura compra venta=1, nota credito=2, nota debito=3
                                                Factura::count()+1, //Numero de factura
                                                $puntoVenta->codigo    //pos
                                                );
                $factura->cufd = $cufd->codigo_cufd;
                $factura->cuf = $cuf.$cufd->codigo_control;
                $factura->codigoSucursal = $agencia->codigo;
                $factura->direccion = $agencia->direccion;
                $factura->codigoPuntoVenta = $puntoVenta->codigo;
                $factura->fechaEmision = $fecha;
                $factura->nombreRazonSocial = $cliente->nombre;
                $factura->codigoTipoDocumentoIdentidad = $cliente->tipo_documento;
                $factura->numeroDocumento = $cliente->numero_documento;
                $factura->complemento = $cliente->complemento;
                $factura->codigoCliente = $cliente->id;
                $factura->codigoMetodoPago = 1;
                $factura->numeroTarjeta = null;
                $factura->montoTotal = $venta->total;
                $factura->montoTotalSujetoIva = $venta->total;
                $factura->codigoMoneda = '1';
                $factura->tipoCambio = 1;
                $factura->montoTotalMoneda = $venta->total;
                $factura->montoGiftCard = 0;
                $factura->descuentoAdicional = $venta->descuento;
                $factura->codigoExcepcion = null;
                $factura->cafc = null;
                $factura->leyenda = 'Ley N° 453: El proveedor debe entregar al consumidor la factura o el comprobante de venta';
                $factura->usuario = 'Luis Fernando';
                $factura->codigoDocumentoSector = 1;
                $factura->estado = 'RECEPCION PENDIENTE';
                $factura->venta_id = $venta->id;
                $factura->save();

                $detalleFactura = new DetalleFactura();            
                $detalleFactura->actividadEconomica = $medicamento->codigo_actividad;
                $detalleFactura->codigoProductoSin = $medicamento->codigo_producto_sin;
                $detalleFactura->codigoProducto = $medicamento->codigo_producto;
                $detalleFactura->descripcion = $medicamento->nombre_comercial;
                $detalleFactura->cantidad = $cantidad;
                $detalleFactura->unidadMedida = $medicamento->codigo_clasificador;
                $detalleFactura->precioUnitario = $precio_venta;
                $detalleFactura->montoDescuento = 0;
                $detalleFactura->subTotal = $detalle->cantidad * $detalle->precio_venta;
                $detalleFactura->montoTotal = $detalleFactura->subTotal;
                $detalleFactura->numeroSerie = null;
                $detalleFactura->numeroImei = null;
                $detalleFactura->factura_id = $factura->id;
                $detalleFactura->save();
                
                //FACTURAR
                //1- Generar XML
                $xml = Factura::generarFacturaXML($factura->id);
                //2- Validar XML
                $msjError = Factura::validarFacturaXML($xml,'facturaComputarizadaCompraVenta.xsd');
                if (empty($msjError)) {
                    //3- Comprimir XML
                    $gzipFileName = $factura->numeroFactura.'.gz';
                    $path = public_path('/siat/facturas/');
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $gzPath = $path.$gzipFileName;
                    $gzdata = gzencode($xml, 9);
                    file_put_contents($gzPath, $gzdata);
                    $hashArchivo = hash_file('sha256', $gzPath);

                    //4- Enviar a SIAT                
                    $parametrosEmision = array(
                        'SolicitudServicioFacturacion' => array(
                            'codigoAmbiente' => $empresa->ambiente, 
                            'codigoDocumentoSector' => 1,
                            'codigoEmision' => $empresa->emision,
                            'codigoModalidad' => $empresa->modalidad,
                            'codigoPuntoVenta' => $puntoVenta->codigo,
                            'codigoSistema' => $empresa->codigo_sistema,
                            'codigoSucursal' => $agencia->codigo,
                            'cufd' => $cufd->codigo_cufd,
                            'cuis' => $cuis->codigo_cuis,
                            'nit' => $empresa->nit,
                            'tipoFacturaDocumento' => 1,
                            'archivo' => $gzdata,
                            'fechaEnvio' => $fecha,
                            'hashArchivo' => $hashArchivo                        
                        )
                    );
                    $responseRecepcionFactura = Factura::soapRecepcionFactura($clienteFacturacion,$parametrosEmision,$factura->id);
                    if ($responseRecepcionFactura == "VALIDADA") {
                        $this->assertTrue(true);
                    } else {
                        $this->assertTrue(false);   
                        $errores[] = $responseRecepcionFactura;
                    }
                    
                } else {
                    $this->assertTrue(false);   
                    $errores[] = $msjError;
                }
            }            
        } else {
            $this->assertTrue(false);   
            $errores[] = "Error de comunicacion con SIAT";
        }
    }
}
    
            
