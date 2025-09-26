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
use App\Models\Sincronizacion;
use Tests\TestCase;

class SincronizacionCatalogos extends TestCase
{
    /**
     * Ejemplo
     */
    public function testSincronizarCatalogo1()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE ACTIVIDADES
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "5E20BB5C";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 1,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            //Sincronizar Actividades
            $responseActividades = Codigo::soapActividad($clienteSincronizacion, $parametros, 6);
            
            if (!$responseActividades) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }
    public function testSincronizarCatalogo2()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE ACTIVIDADES
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "718E252F";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 0,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            //Sincronizar Actividades
            $responseActividades = Codigo::soapActividad($clienteSincronizacion, $parametros, 5);
            
            if (!$responseActividades) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }  
    
    public function testSincronizarCatalogo3()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = FECHA Y HORA ACTUAL
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "5E20BB5C";
        $empresa = Empresa::first();
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";
        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 1,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        $estaSincronizado = Sincronizacion::obtenerUltimaSincronizacion(0, 1);
        if (!$estaSincronizado) {
            //Registrar Sincronizacion
            $sincronizacion = new Sincronizacion();
            $sincronizacion->nit = $empresa->nit;
            $sincronizacion->agencia_id = 0;
            $sincronizacion->punto_venta_id = 1;
            $sincronizacion->cuis_id = 6;
            $sincronizacion->save();
        } else {
            $sincronizacion = $estaSincronizado;
        }
        for ($i=0; $i < 50; $i++) {
            //Sincronizar Fecha y Hora
            $responseFechaHora = Sincronizacion::sincronizacionFechaHora($clienteSincronizacion, $parametros, $sincronizacion->id);            
            if (!$responseFechaHora) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo4()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 0
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "718E252F";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 0,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        $estaSincronizado = Sincronizacion::obtenerUltimaSincronizacion(0, 1);
        if (!$estaSincronizado) {
            //Registrar Sincronizacion
            $sincronizacion = new Sincronizacion();
            $sincronizacion->nit = $empresa->nit;
            $sincronizacion->agencia_id = 0;
            $sincronizacion->punto_venta_id = 1;
            $sincronizacion->cuis_id = 5;
            $sincronizacion->save();
        } else {
            $sincronizacion = $estaSincronizado;
        }
        for ($i=0; $i < 50; $i++) {                       
            //Sincronizar Fecha y Hora
            $responseFechaHora = Sincronizacion::sincronizacionFechaHora($clienteSincronizacion, $parametros, $sincronizacion->id);            
            if (!$responseFechaHora) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo5()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE ACTIVIDADES DOCUMENTO SECTOR
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "5E20BB5C";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 1,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseActividadDocumento = ActividadDocumento::soapActividadDocumento($clienteSincronizacion, $parametros,6);
            
            if (!$responseActividadDocumento) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo6()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 0
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE ACTIVIDADES DOCUMENTO SECTOR
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "718E252F";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 0,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseActividadDocumento = ActividadDocumento::soapActividadDocumento($clienteSincronizacion, $parametros, 5);
            
            if (!$responseActividadDocumento) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo7()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS DE FACTURAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "5E20BB5C";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 1,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseLeyendas = Leyenda::soapLeyenda($clienteSincronizacion, $parametros,6);
            
            if (!$responseLeyendas) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo8()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 0
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS DE FACTURAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "718E252F";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 0,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseLeyendas = Leyenda::soapLeyenda($clienteSincronizacion, $parametros,5);
            
            if (!$responseLeyendas) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo9()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS DE FACTURAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "5E20BB5C";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 1,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseParametros = Parametro::sincronizarParametro($clienteSincronizacion, $parametros,6);
            
            if (!$responseParametros) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo10()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 0
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS DE FACTURAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "718E252F";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 0,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseParametros = Parametro::sincronizarParametro($clienteSincronizacion, $parametros,5);
            
            if (!$responseParametros) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }

    public function testSincronizarCatalogo11()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS DE FACTURAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "5E20BB5C";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 1,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseParametros = Catalogo::soapCatalogo($clienteSincronizacion, $parametros,6);
            
            if (!$responseParametros) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }
    public function testSincronizarCatalogo12()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 0
        // codigoSistema = su código de sistema
        // resultadoEsperado = LISTADO TOTAL DE LEYENDAS DE FACTURAS
        // nit = su NIT
        // codigoSucursal = 0 
        $cuis = "718E252F";
        $empresa = Empresa::first();        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      

        $parametros = array(
            'SolicitudSincronizacion' => array(                
                'codigoAmbiente' => $empresa->ambiente,
                'codigoPuntoVenta' => 0,
                'codigoSistema' => $empresa->codigo_sistema,
                'codigoSucursal' => 0,
                'cuis' => $cuis,
                'nit' => $empresa->nit
            )
        );
        
        $clienteSincronizacion = Ajuste::consumoSIAT($token, $wsdlSincronizacion);
        $errores = [];
        for ($i=0; $i < 50; $i++) {                       
            $responseParametros = Catalogo::soapCatalogo($clienteSincronizacion, $parametros,5);
            
            if (!$responseParametros) {
                $errores[] = "Iteración $i: Falló la sincronización de Actividades";
            }
        }
        // Validación final
        $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
    }
}
