<?php

return [
    /*
    | URL base de los servicios SIAT por ambiente (valor de empresas.ambiente).
    | 1 = Producción, 2 = Pruebas/Piloto.
    | La URL de producción NO se asume: defínela en .env (SIAT_WSDL_PRODUCCION)
    | tomándola de la documentación técnica vigente del SIN.
    */
    'wsdl' => [
        '1' => env('SIAT_WSDL_PRODUCCION'),
        '2' => env('SIAT_WSDL_PILOTO', 'https://pilotosiatservicios.impuestos.gob.bo/v2'),
    ],
];