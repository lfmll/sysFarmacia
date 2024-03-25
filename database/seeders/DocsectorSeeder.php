<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class DocsectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doc_sector = [
            ['codigo'       =>  '1',
            'nombre'        =>  'Factura de Compra y Venta',
            'descripcion'   =>  'Habilitada para transacciones por bienes o servicios en general, incluyen línea blanca, negra y cualquier actividad que involucre un intercambio de estos.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '2',
            'nombre'       =>  'Factura de Alquiler de Bienes Inmuebles',
            'descripcion'   =>  'Habilitado para alquiler de bienes inmuebles propios.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '3',
            'nombre'       =>  'Factura Comercial de Exportación',
            'descripcion'   =>  'Habilitada para transacciones de exportación de bienes, no se incluyen minerales.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '4',
            'nombre'       =>  'Factura de Comercial de Exportación en Libre Consignación',
            'descripcion'   =>  'Habilitada para transacciones de exportación de bienes en libre consignación.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '5',
            'nombre'       =>  'Factura de Venta en Zona Franca',
            'descripcion'   =>  'Habilitada para transacciones en zonas francas a concesionario o usuario.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '6',
            'nombre'       =>  'Factura de Servicio Turístico y Hospedaje',
            'descripcion'   =>  'Habilitada para la exportación de servicios turísticos y hospedaje, alcanzados por el Artículo 30 de la Ley N° 292.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '7',
            'nombre'       =>  'Factura de Seguridad Alimentaria y Abastecimiento',
            'descripcion'   =>  'Habilitada para comercialización de alimentos exentos de impuestos.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '8',
            'nombre'       =>  'Factura Tasa Cero Venta de Libros y Transporte Internacional de Carga por Carretera',
            'descripcion'   =>  'Habilitada para los que se encuentren alcanzados por el Régimen Tasa Cero en el IVA. Para la venta de libros nacionales o importados y publicaciones oficiales. Por el transporte internacional de carga por carretera.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '9',
            'nombre'       =>  'Factura de Compra y Venta de Moneda Extranjera',
            'descripcion'   =>  'Habilitada para transacciones de compra/venta de moneda extranjera.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '10',
            'nombre'       =>  'Factura Dutty Free',
            'descripcion'   =>  'Habilitada para los que realicen ventas en tiendas libres o Dutty Free.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '11',
            'nombre'       =>  'Factura Sectores Educativos',
            'descripcion'   =>  'Habilitada para la facturación de unidades educativas preescolares, primaria, secundaria, de educación superior, institutos educativos, enseñanza de adultos y otros tipos de enseñanza.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '12',
            'nombre'       =>  'Factura de Comercialización de Hidrocarburos',
            'descripcion'   =>  'Habilitada para la venta de combustible diésel oíl, venta de combustible gasolina especial y/o gasolina Premium, venta de combustible para automotores.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '13',
            'nombre'       =>  'Factura de Servicios Básicos',
            'descripcion'   =>  'Habilitada para la distribución de agua, electricidad y Cooperativas Telefónicas que dentro de sus operaciones utilicen otras tasas.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '14',
            'nombre'       =>  'Factura Productos Alcanzados por el ICE',
            'descripcion'   =>  'Habilitada a los productos que estén alcanzados por el ICE, por ejemplo: cigarrillos, bebidas alcohólicas y otros.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '15',
            'nombre'       =>  'Factura de Entidades Financieras',
            'descripcion'   =>  'Habilitada para entidades de carácter financiero, por ejemplo: bancos, cooperativas y otros. No incluyen casas de cambio.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '16',
            'nombre'       =>  'Factura de Hoteles',
            'descripcion'   =>  'Habilitada para hoteles, hostales, alojamientos y otros, cuando los huéspedes sean de origen nacional o residentes en Bolivia.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '17',
            'nombre'       =>  'Factura de Hospitales/Clínicas',
            'descripcion'   =>  'Habilitada para hospitales y clínicas, deberá incluir información de los pacientes y médicos cuando sea una intervención quirúrgica.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '18',
            'nombre'       =>  'Factura de Juegos de Azar',
            'descripcion'   =>  'Habilitada para las actividades que incluyan sorteos, concursos o juegos de azar.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '19',
            'nombre'       =>  'Factura de Hidrocarburos Alcanzada IEHD',
            'descripcion'   =>  'Habilitada para empresas dedicadas a la comercialización de hidrocarburos o sus derivados en primera fase',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '20',
            'nombre'       =>  'Factura Comercial de Exportación de Minerales',
            'descripcion'   =>  'Habilitada para transacciones de exportación de minerales.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '21',
            'nombre'       =>  'Factura de Venta de Minerales',
            'descripcion'   =>  'Habilitada para la venta de minerales en el territorio nacional.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '22',
            'nombre'       =>  'Factura de Telecomunicaciones',
            'descripcion'   =>  'Habilitada para servicios de telecomunicaciones.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '23',
            'nombre'       =>  'Factura Prevalorada',
            'descripcion'   =>  'Habilitada para actividades de cobro de tasa aeroportuaria y terrestre, y para entradas a ferias.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '24',
            'nombre'       =>  'Nota de Crédito - Débito ',
            'descripcion'   =>  'Habilitada para realizar ajustes en el crédito y débito fiscal de los Sujetos Pasivos o compradores.',
            'tipo_documento'=>  'Documento de Ajuste'],

            ['codigo'       =>  '28',
            'nombre'       =>  'Factura Comercial de Exportación de Servicios',
            'descripcion'   =>  'Habilitada para Contribuyentes que Exportan Servicios',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],
            
            ['codigo'       =>  '29',
            'nombre'       =>  'Nota de Conciliación',
            'descripcion'   =>  'Habilitada para realizar ajustes en el Crédito y en el Débito Fiscal de los Sujetos Pasivos del IVA por transacciones facturadas en periodos anteriores no mayores a doce (12) meses, por servicios de energía eléctrica, telecomunicaciones, agua potable e hidrocarburos.',
            'tipo_documento'=>  'Documento de Ajuste'],
            
            ['codigo'       =>  '30',
            'nombre'       =>  'Boleto Aéreo',
            'descripcion'   =>  'Habilitada para el registro de pasajes aéreos.',
            'tipo_documento'=>  'Documento Equivalente'],

            ['codigo'       =>  '31',
            'nombre'       =>  'Factura de Suministro de Energía',
            'descripcion'   =>  'Habilitada para la recarga de Energía Eléctrica a Vehículos Eléctricos.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '33',
            'nombre'       =>  'Factura Tasa Cero IVA Ley N° 1546',
            'descripcion'   =>  'Habilitada para la importación y comercialización de bienes de capital y plantas industriales',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '34',
            'nombre'       =>  'Factura de Seguros',
            'descripcion'   =>  'Habilitada para transacciones específicas del Sector Seguros',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '35',
            'nombre'       =>  'Factura Compra Venta Bonificaciones',
            'descripcion'   =>  'Habilitada para transacciones por bienes o servicios en general, incluyen línea blanca, negra y cualquier actividad que involucre un intercambio de estos. Permite descuento total en algunos de los productos en el detalle.',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '36',
            'nombre'       =>  'Factura Prevalorada Sin Derecho Crédito Fiscal',
            'descripcion'   =>  'Habilitada para actividades de realización de Espectáculos Públicos Eventuales, Zona Franza e Importación y Venta de Libros',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '37',
            'nombre'       =>  'Factura de Comercialización de GNV',
            'descripcion'   =>  'Habilitada para la venta de Gas Natural Vehicular',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '38',
            'nombre'       =>  'Factura Hidrocarburos No Alcanzada IEHD',
            'descripcion'   =>  'Habilitada para todas aquellas actividades exentas del pago del IEHD',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '39',
            'nombre'       =>  'Factura de Comercialización De GN y GLP',
            'descripcion'   =>  'Habilitada para la comercialización de Gas Natural y Gas Licuado de Petróleo',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '40',
            'nombre'       =>  'Factura de Servicios Básicos Zona Franca',
            'descripcion'   =>  'Habilitada para la distribución de agua, electricidad o cualquier servicio que se considere básico, de acuerdo a normativa vigente en Zona Franca',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '41',
            'nombre'       =>  'Factura de Compra Venta Tasas',
            'descripcion'   =>  'Habilitada para transacciones por bienes o servicios en general, incluyen línea blanca, negra y cualquier actividad que involucre un intercambio de estos, permite incluir tasas no sujetas a Crédito Fiscal',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '42',
            'nombre'       =>  'Factura Alquiler Zona Franca',
            'descripcion'   =>  'Habilitada para el alquiler de Bienes Inmuebles en zona Franca',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '43',
            'nombre'       =>  'Factura Comercial de Exportación Hidrocarburos',
            'descripcion'   =>  'Habilitada para transacciones de exportación del sector Hidrocarburos adecuado a legislativa vigente',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '44',
            'nombre'       =>  'Factura Importación y Comercialización de Lubricantes',
            'descripcion'   =>  'Habilitada para empresas que importen de forma directa lubricantes y que comercialicen los mismos al consumidor final o distribuidor (No utilizada para servicios, solo venta de productos)',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '45',
            'nombre'       =>  'Factura Comercial de Exportación Precio Venta',
            'descripcion'   =>  'Habilitada para transacciones de exportación de bienes, no se incluye a la exportación de minerales.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '46',
            'nombre'       =>  'Factura Sector Educativo Zona Franca',
            'descripcion'   =>  'Habilitada para la facturación de unidades educativas preescolares, primaria, secundaria, de educación superior, institutos educativos, enseñanza de adultos y otros tipos de enseñanza al interior de Zona Franca',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '47',
            'nombre'       =>  'Nota Crédito Débito Descuentos',
            'descripcion'   =>  'Habilitada para realizar ajustes en el crédito y débito fiscal de los Sujetos Pasivos o compradores a facturas afectadas con un Descuento Adicional',
            'tipo_documento'=>  'Documento de Ajuste'],

            ['codigo'       =>  '48',
            'nombre'       =>  'Nota Crédito Débito ICE',
            'descripcion'   =>  'Habilitada para realizar ajustes en el crédito y débito fiscal de los Sujetos Pasivos o compradores a facturas emitidas con ICE',
            'tipo_documento'=>  'Documento de Ajuste'],

            ['codigo'       =>  '49',
            'nombre'       =>  'Factura Telecomunicaciones Zona Franca',
            'descripcion'   =>  'Habilitada para servicios de telecomunicaciones en Zona Franca',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '50',
            'nombre'       =>  'Factura Hospitales/ Clínicas Zona Franca',
            'descripcion'   =>  'Habilitada para hospitales y clínicas en Zona Franca, deberá incluir información de los pacientes y médicos cuando sea una intervención quirúrgica.',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '51',
            'nombre'       =>  'Factura Engarrafadoras',
            'descripcion'   =>  'Habilitada para empresas dedicadas a la recarga o llenado de gas en Garrafas y Contenedores',
            'tipo_documento'=>  'Con derecho a credito fiscal'],

            ['codigo'       =>  '52',
            'nombre'       =>  'Factura Venta Minerales Banco Central',
            'descripcion'   =>  'Habilitada para la Venta de Minerales al Banco Central y solo en la modalidad electrónica',
            'tipo_documento'=>  'Sin derecho a credito fiscal'],

            ['codigo'       =>  '53',
            'nombre'       =>  'Factura Importación y Comercialización de Lubricantes IEHD',
            'descripcion'   =>  'Habilitada para empresas que importen de forma directa lubricantes y que comercialicen los mismos al consumidor final o distribuidor (No utilizada para servicios, solo venta de productos)',
            'tipo_documento'=>  'Con derecho a credito fiscal'],
        ];

        foreach ($doc_sector as $doc) {
            TipoDocumento::create($doc);
        }
    }
}
