<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Cuis;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\Ajuste;
use App\Models\Parametro;
use App\Models\Codigo;
use App\Models\Leyenda;
use App\Models\Catalogo;
use App\Models\ActividadDocumento;
use App\Models\PuntoVenta;
use App\Models\Sincronizacion;
use Tests\TestCase;

class SincronizacionCatalogos extends TestCase
{
    /**
     * Ejemplo
     */
    private const ITERACIONES = 50;
    private const SUCURSAL = 0;

    private function cuisVigente(int $codigoPuntoVenta): Cuis
    {
        $agencia = Agencia::where('codigo', self::SUCURSAL)->first();
        $puntoVenta = PuntoVenta::where('codigo', $codigoPuntoVenta)->first();

        session([
            'agencia_id'     => $agencia->id,
            'punto_venta_id' => $puntoVenta->id,
        ]);

        $cuis = Cuis::obtenerCuis();
        $this->assertNotNull($cuis, "No hay CUIS vigente para punto de venta $codigoPuntoVenta");

        return $cuis;
    }

    private function probarParametrica(int $codigoPuntoVenta, string $metodoSoap): void
    {
        $cuis = $this->cuisVigente($codigoPuntoVenta);
        $empresa = Empresa::first();
        $ajuste = Ajuste::first();
        $wsdlSincronizacion = $ajuste->wsdl . "/FacturacionSincronizacion?wsdl";

        $parametros = array(
            'SolicitudSincronizacion' => array(
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => self::SUCURSAL,
                'cuis' => $cuis->codigo_cuis,
                'nit' => $empresa->nit
            )
        );

        $clienteSincronizacion = Ajuste::consumoSIAT($ajuste->token, $wsdlSincronizacion);
        $errores = [];
        for ($i = 0; $i < self::ITERACIONES; $i++) {
            try {
                $respuesta = $clienteSincronizacion->$metodoSoap($parametros)->RespuestaListaParametricas;
                if (!$respuesta->transaccion) {
                    $errores[] = "Iteración $i: " . json_encode($respuesta->mensajesList ?? null);
                }
            } catch (\Throwable $e) {
                $errores[] = "Iteración $i: " . $e->getMessage();
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados en $metodoSoap (punto de venta $codigoPuntoVenta):\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo9()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE MENSAJES DE SERVICIOS
        $this->probarParametrica(1, 'sincronizarListaMensajesServicios');
    }

    public function testSincronizarCatalogo10()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE MENSAJES DE SERVICIOS
        $this->probarParametrica(0, 'sincronizarListaMensajesServicios');
    }

    public function testSincronizarCatalogo13()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE EVENTOS SIGNIFICATIVOS
        $this->probarParametrica(1, 'sincronizarParametricaEventosSignificativos');
    }

    public function testSincronizarCatalogo14()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE EVENTOS SIGNIFICATIVOS
        $this->probarParametrica(0, 'sincronizarParametricaEventosSignificativos');
    }

    public function testSincronizarCatalogo15()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE MOTIVO DE ANULACIÓN
        $this->probarParametrica(1, 'sincronizarParametricaMotivoAnulacion');
    }

    public function testSincronizarCatalogo16()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE MOTIVO DE ANULACIÓN
        $this->probarParametrica(0, 'sincronizarParametricaMotivoAnulacion');
    }

    public function testSincronizarCatalogo17()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE PAÍSES
        $this->probarParametrica(1, 'sincronizarParametricaPaisOrigen');
    }

    public function testSincronizarCatalogo18()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE PAÍSES
        $this->probarParametrica(0, 'sincronizarParametricaPaisOrigen');
    }

    public function testSincronizarCatalogo19()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE DOCUMENTO DE IDENTIDAD
        $this->probarParametrica(1, 'sincronizarParametricaTipoDocumentoIdentidad');
    }

    public function testSincronizarCatalogo20()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE DOCUMENTO DE IDENTIDAD
        $this->probarParametrica(0, 'sincronizarParametricaTipoDocumentoIdentidad');
    }

    public function testSincronizarCatalogo21()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE DOCUMENTO SECTOR
        $this->probarParametrica(1, 'sincronizarParametricaTipoDocumentoSector');
    }

    public function testSincronizarCatalogo22()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE DOCUMENTO SECTOR
        $this->probarParametrica(0, 'sincronizarParametricaTipoDocumentoSector');
    }

    public function testSincronizarCatalogo23()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPO EMISIÓN
        $this->probarParametrica(1, 'sincronizarParametricaTipoEmision');
    }

    public function testSincronizarCatalogo24()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPO EMISIÓN
        $this->probarParametrica(0, 'sincronizarParametricaTipoEmision');
    }

    public function testSincronizarCatalogo25()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPO HABITACIÓN
        $this->probarParametrica(1, 'sincronizarParametricaTipoHabitacion');
    }

    public function testSincronizarCatalogo26()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPO HABITACIÓN
        $this->probarParametrica(0, 'sincronizarParametricaTipoHabitacion');
    }

    public function testSincronizarCatalogo27()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE MÉTODO DE PAGO
        $this->probarParametrica(1, 'sincronizarParametricaTipoMetodoPago');
    }

    public function testSincronizarCatalogo28()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE MÉTODO DE PAGO
        $this->probarParametrica(0, 'sincronizarParametricaTipoMetodoPago');
    }

    public function testSincronizarCatalogo29()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE MONEDA
        $this->probarParametrica(1, 'sincronizarParametricaTipoMoneda');
    }

    public function testSincronizarCatalogo30()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE MONEDA
        $this->probarParametrica(0, 'sincronizarParametricaTipoMoneda');
    }

    public function testSincronizarCatalogo31()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE PUNTO DE VENTA
        $this->probarParametrica(1, 'sincronizarParametricaTipoPuntoVenta');
    }

    public function testSincronizarCatalogo32()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE PUNTO DE VENTA
        $this->probarParametrica(0, 'sincronizarParametricaTipoPuntoVenta');
    }

    public function testSincronizarCatalogo33()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE FACTURA
        $this->probarParametrica(1, 'sincronizarParametricaTiposFactura');
    }

    public function testSincronizarCatalogo34()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE TIPOS DE FACTURA
        $this->probarParametrica(0, 'sincronizarParametricaTiposFactura');
    }

    public function testSincronizarCatalogo35()
    {
        // codigoPuntoVenta = 1
        // resultadoEsperado = LISTADO TOTAL DE UNIDAD DE MEDIDA
        $this->probarParametrica(1, 'sincronizarParametricaUnidadMedida');
    }

    public function testSincronizarCatalogo36()
    {
        // codigoPuntoVenta = 0
        // resultadoEsperado = LISTADO TOTAL DE UNIDAD DE MEDIDA
        $this->probarParametrica(0, 'sincronizarParametricaUnidadMedida');
    }
}
