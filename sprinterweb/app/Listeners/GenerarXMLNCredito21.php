<?php

namespace App\Listeners;

use App\Events\GenerarXMLNCredito21Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Panel\Companies\Entities\Pempresa;
use App\Ubigeos\Entities\Ubigeo;
use App\DocumentToPay\Entities\DocumentToPay;
use DateTime;

class GenerarXMLNCredito21
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GenerarXMLNCredito21Events  $event
     * @return void
     */
    public function handle(GenerarXMLNCredito21Events $event)
    {
        $documento = collect($event->reportecmd)->first();
        $documento_detalles = $event->reportecmd;

        $lcSerieNum = substr(trim($documento->seriedoc), -4) . '-' . trim($documento->numerodoc);
        $lcLetras = $documento->total . ' ' . trim($documento->mon_nombre);
        $lcFecha = str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("d"), 2, '0', STR_PAD_LEFT);
        $lcHoraEmision = trim($documento->horaproceso);
        $lcFechaVence = str_pad(DateTime::createFromFormat('Y-m-d', $documento->vencimiento)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->vencimiento)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->vencimiento)->format("d"), 2, '0', STR_PAD_LEFT);
        $lcCondicion = trim(strtoupper($documento->condicion));
        $lcTipoVenta = trim($documento->tipoventa);
        $lcTvSunat = trim($documento->tvsunat);
        $lcNroOp = "OP:" . trim($documento->op_numero);
        $lcPtoVenta = "Pto:" . trim($documento->ptoventa);
        $lcGlosa = trim($documento->glosa);
        $lcTcambio = trim(formatear_numero($documento->tcambio, 4));

        $lcMoneda              = trim($documento->mon_codigo); // TIPO DE MONEDA DE LA FACTURA ELECTRÓNICA        
        $lcTotVentaOpeGrav     = trim(formatear_numero($documento->base + $documento->totaldscto, 2)); // TOTAL VALOR VENTA DE LAS OPERACIONES GRAVADAS
        if ( trim($documento->tipoafeigv) == '20' ) {
            $lcTotVentaOpeExo  = trim(formatear_numero($documento->inafecto, 2)); // TOTAL VALOR VENTA DE LAS OPERACIONES EXONERADAS
            $lcTotVentaOpeInaf = "0.00"; // TOTAL VALOR VENTA DE LAS OPERACIONES INAFECTAS
        } else { 
            $lcTotVentaOpeInaf = trim(formatear_numero($documento->inafecto ,2)); // TOTAL VALOR VENTA DE LAS OPERACIONES INAFECTAS
            $lcTotVentaOpeExo  = "0.00"; // TOTAL VALOR VENTA DE LAS OPERACIONES EXONERADAS
        } 
        $lcTotVentaOpeGrat 	= trim(formatear_numero($documento->gratuito, 2)); // TOTAL VALOR VENTA DE LAS OPERACIONES GRATUITAS
        $lnfacturaanticipos 	= $documento->esanticipo;
        $lnfacturareganticipos = 0;
        if ($documento->esgratuito == 1) {
            $lcTotVentaOpeGrat = trim(formatear_numero($documento->gratuito ,2));
            $lcTotVentaOpeGrav = "0.00";
            $lcTotVentaOpeInaf = "0.00";
        } 
        $lcTipoIGV           = trim($documento->tipoafeigv); // TIPO DE AFECTACIÓN DEL IGV VER. CATÁLOGO No 7 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf 
        $lcPorcentajeIGV     = trim(formatear_numero($documento->factigv ,1)); // &PORCENTAJE DEL IGV A USAR EN LA FACTURA ELECTRÓNICA
        $lcImporteTotal_Letras = $lcLetras; // IMPORTE TOTAL DE LA VENTA EN LETRAS
        $lcFechaEmision      = $lcFecha; // FECHA DE EMISIÓN DE LA FACTURA ELECTRÓNICA
        $lcRucFirmaDigital 	= trim(Pempresa::get_parametro('CER_RUC')->valor); 	// NÚMERO DE RUC DEL RESPONSABLE DE LA FIRMA DIGITAL
        $lcRazonEmpresaFirma = trim(Pempresa::get_parametro('CER_NOMBRE')->valor);	// RAZON SOCIAL DE LA EMPRESA RESPONSABLE DE LA FIRMA DIGITAL        
                
        // DATOS DEL EMISOR
        $lcRucEmisor           = trim(Pempresa::get_parametro('CPE_RUC')->valor); // NÚMERO DE RUC DEL EMISOR
        $lcRazonEmpresaEmisor  = trim(Pempresa::get_parametro('CPE_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA EMISORA
        $lcUbigeoEmisor        = trim(Pempresa::get_parametro('CPE_UBIGEO')->valor); // CÓDIGO DE UBIGEO
        $lcDirEmpresaEmisor    = trim(Pempresa::get_parametro('CPE_DIRECCION')->valor); // DIRECCIÓN DE LA EMPRESA EMISORA
        $lcCiudadEmisor        = trim(Pempresa::get_parametro('CPE_CIUDAD')->valor); // CIUDAD DEL EMISOR - DATO OPCIONAL
        $lcDistritoEmisor      = ""; // DISTRITO DEL EMISOR - DATO OPCIONAL
        
        $ubigeo = Ubigeo::select("descripcion")->where("codigo", $lcUbigeoEmisor)->first();

        if ($ubigeo){

            $lcDistritoEmisor = trim($ubigeo->descripcion);

        }

        // INFORMACIÓN DEL DOCUMENTO QUE AFECTA LA NOTA DE CRÉDITO
        $lcDocumentoAfecta     = trim($documento->referencia); // NÚMERO DE DOCUMENTO QUE AFECTA LA NOTA DE CRÉDIDO O DÉBITO
        $lcTipoNotaCreDe       = trim($documento->tiponotacr); // TIPO DE NOTA DE CRÉDITO QUE AFECTA EL DOCUMENTO ELECTRÓNICO VER. CATÁLOGO No 9 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf 
        $lcDescriSustento      = trim($documento->tiponotacr_dsc); // SUSTENTO DEL TIPO DE ANULACIÓN DE NOTA DE CRÉDIDO O DÉBITO VER. CATÁLOGO No 9 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf
        $lcTipoDocAfecta       = trim($documento->tipodocref); // TIPO DE DOCUMENTO QUE AFECTA LA NOTA DE CRÉDITO O DÉBITO VER. CATÁLOGO No 1 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf

        // GUIA DE REMISIÓN
        $lcNroGuiaRemision = trim($documento->gremision);
        $lcFecGuiaRemision = '';
        if ( !empty(trim($lcNroGuiaRemision)) ) {
            $lcFecGuiaRemision = str_pad(DateTime::createFromFormat('Y-m-d', $documento->gremisionfecha)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->gremisionfecha)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->gremisionfecha)->format("d"), 2, '0', STR_PAD_LEFT);
        } 
        // ORDEN COMPRA CLIENTE
        $lcNroOcCliente = trim($documento->occliente);        

        // DATOS DEL CLIENTE
        $lcRucCliente = trim($documento->ruc);
        $lcTipoDocCliente  = trim($documento->ter_docide); // TIPO DE DOCUMENTO DEL CLIENTE VER. CATÁLOGO No 6 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf
        $lcRazonEmpresaCliente = trim($documento->razonsocial); // RAZON SOCIAL DEL CLIENTE
        $lcDirCliente          = trim($documento->direccion); // DIRECCIÓN DEL CLIENTE
        $lcCodigoPaisCliente   = trim($documento->pais); // CÓDIGO DE PAÍS DEL CLIENTE VER. CATÁLOGO No 4 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf          
        $lcUbigeoCliente = trim($documento->ubigeo_cod);
        $lcDistritoCliente = trim($documento->ubigeo_dis);
        $lcProvCliente = '';
        $lcDepCliente = '';

        $ubigeo = Ubigeo::select("descripcion")->where("codigo", substr($lcUbigeoCliente, 0, 4))->first();
        if($ubigeo){    
            $lcProvCliente = $ubigeo->descripcion;
        }

        $ubigeo = Ubigeo::select("descripcion")->where("codigo", substr($lcUbigeoCliente, 0, 2))->first();
        if($ubigeo){
            $lcDepCliente = $ubigeo->descripcion;
        }

        // DETRACCION
        $lcMontoDetraccion = "";
        if ( $documento->detraccion > 0 ) {
            $lcTipoDetraccion = trim($documento->tipodetr);
            $lcMontoDetraccion	= trim(formatear_numero($documento->detraccion, 2));
            $lcPorcentajeDetraccion	= trim(formatear_numero($documento->factordetr, 2));

            $tercero_cuenta = CustomerAccount::selectparentcmd_codigo($lcRucEmisor);

            if ($tercero_cuenta) { 
                if ($tercero_cuenta->tipocuenta == 'D') { 
                    $lcCtaBancoDetraccion = trim($tercero_cuenta->cuenta);                  
                }
            } 

        }         


        $lnConIGV = Pempresa::get_parametro('CON_IMPTOS')->valor;
        $lcTotalISC = "";
        $lcTotalICBPER = ""; // MONTO TOTAL DEL IMPUESTO ISC		
        $lcMontoPercepcion = ""; // &&MONTO TOTAL DE LA PERCEPCIÓN
        $lcTotalDocPercepcion = "";
        $lcTotalDescuento = "";
        $lcTotalConsumo = "";

        
        if ( $documento->totalisc > 0 ) {
            $lcTotalISC 	= trim(formatear_numero($documento->totalisc, 2));		
        } 
        if ( $documento->totalicbper > 0 ) { 
            $lcTotalICBPER = trim(formatear_numero($documento->totalicbper ,2));		
        }
        if ( $documento->impuesto2 > 0 ) { 
            $lcTvSunat = '2001';
            $lcMontoPercepcion = trim(formatear_numero($documento->impuesto2, 2));
            $lcTotalDocPercepcion= trim(formatear_numero($documento->total + $documento->impuesto2, 2)); // TOTAL DE DOCUMENTO INCLUIDO EL MONTO DE PERCEPCIÓN
        }
        if ( $documento->impuesto4 != 0 ) {
            $lcTotalConsumo = trim(formatear_numero($documento->impuesto4, 2));
        } 
        $lcTotalIGV = trim(formatear_numero($documento->impuesto, 2)); // MONTO TOTAL DEL IMPUESTO IGV
        if ( $documento->esgratuito == 1 ) {
            $lcTotalIGV = "0.00";
        } 
        if ( $documento->totaldscto != 0 ){
            $lcTotalDescuento  = trim(formatear_numero($documento->totaldscto, 2)); // OPCIONAL - DESCUENTOS TOTALES DEL DOCUMENTO
        } 
        $lcSubTotalDoc = trim(formatear_numero($documento->base + $documento->inafecto + $documento->totaldscto, 2)); // SUB TOTAL DEL DOCUMENTO (AQUI TÁMBIEN ICLUIR LA SUMA DE OPERACIONES NO GRAVADAS)
        $lcTotalDoc    = trim(formatear_numero($documento->total, 2)); // TOTAL DEL DOCUMENTO
        
        // $d_  <- DETALLES

        $lnItem = 0;
        $d_totigv = 0;
        $d_totant = 0;
        
        if ( $lcTipoNotaCreDe != '03' ) {

            $lnItemsDocumento = collect($documento_detalles)->filter(function ($detalle) { // DETALLES VÁLIDOS // EXPLICACIÓN DEL CLASIC: la variable lnItemsDocumento  es para crear el array: DIMENSION laItems(lnItemsDocumento,14)
                return (($detalle->cantidad > 0 && $detalle->precio > 0) || $detalle->referencia_id > 0);
            });    

            $lnCountItem = collect($documento_detalles)->filter(function ($detalle) { 
                return (($detalle->cantidad > 0 && $detalle->precio > 0 && $detalle->referencia_id == 0));
            });                

        }else{

            $lnItemsDocumento = collect($documento_detalles)->filter(function ($detalle) { // DETALLES VÁLIDOS // EXPLICACIÓN DEL CLASIC: la variable lnItemsDocumento  es para crear el array: DIMENSION laItems(lnItemsDocumento,14)
                return ($detalle->cantidad > 0 || $detalle->referencia_id > 0);
            });    

            $lnCountItem = collect($documento_detalles)->filter(function ($detalle) { 
                return ($detalle->cantidad > 0 && $detalle->referencia_id == 0);
            });                          

        } 

        /*
        if ( $lnItemsDocumento <= 0 ) { 
            return $lcSerieNum . ': El Detalle del documento está vacío';
        } */

        $lnCountItem = $lnCountItem->count();

        $laItems = array();

        foreach($documento_detalles as $detalle){

            $d_referencia_id = ( empty($detalle->referencia_id) ) ? 0 : $detalle->referencia_id;
            $d_codigo = trim($detalle->codigo);
            $d_codsunat = trim($detalle->codsunat);
            $d_descripcion = trim($detalle->descripcion);
            $d_cantidad = ( empty($detalle->cantidad) ) ? 0 : $detalle->cantidad;
            $d_precio = ( empty($detalle->precio) ) ? 0 : $detalle->precio;
            $d_subtotal = ( empty($detalle->subtotal) ) ? 0 : $detalle->subtotal;
            $d_importe = ( empty($detalle->importe) ) ? 0 : $detalle->importe;
            $d_serie = trim($detalle->serie);
            $d_lote = trim($detalle->lote);
            $d_isc = $detalle->isc;
            $d_icbper = $detalle->icbper * $d_cantidad;
            $d_tipoisc = trim($detalle->tipoisc);
            $d_tipoigv = trim($detalle->tipoafeigv);
            $d_factigv = $detalle->factigv;

            if ( $lnConIGV == 1){

                $d_igv = $d_importe - $d_subtotal;
                
            }else{

                $d_igv = $d_subtotal - $d_importe;
                $d_importe = $d_subtotal;
                $d_subtotal = $d_importe - $d_igv;	

                if ($d_cantidad != 0) {

                    $d_precio = formatear_numero($d_importe / $d_cantidad, 4);

                }else{ 

                    $d_precio = $d_importe;

                } 	
            }

            $d_totigv = $d_totigv + $d_igv;
            if ( ($d_cantidad > 0 || $d_referencia_id > 0) && (($lcTipoNotaCreDe != '03' && $d_precio > 0) || $lcTipoNotaCreDe == '03') ){
                
                // FALTA LA SELECCIÓN DEL CURSOR: crsundtransporte Y SUS CALCULOS (PREGUNTAR ORIGEN)
                //IF USED('crsundtransporte')
                //    IF !EMPTY(m.serie)
                //        SELECT crsundtransporte
                //        IF SEEK(m.serie)
                //            m.descripcion = m.descripcion + " VIN:" + ALLTRIM(nrovin) + " NRO.MOTOR:" + ALLTRIM(nromotor)
                //        ENDIF 
                //    ENDIF 
                //ELSE 
                    if ( !empty($d_lote) ){
                        $d_descripion = $d_descripcion . " LOTE:" . trim($d_lote);
                    } 
                    if ( !empty($d_serie) ) {
                        $d_descripcion = $d_descripcion . " SERIE:" . trim($d_serie);
                    } 
                //ENDIF 
        
                $lnItem += 1; // EN CASO SE CAMBIE A CERO EXISTE UNA CONDICIÓN QUE UTILIZA EL CONTADOR, CAMBIAR TAMBIEN ESA CONDICIÓN
                $laItems[$lnItem][1] = trim(formatear_numero($d_cantidad, 3)); // CANTIDAD DEL PRODUCTO O ITEM
                $laItems[$lnItem][2] = trim(formatear_numero($d_subtotal, 2)); // SUB TOTAL DEL PRODUCTO O ITEM
                $laItems[$lnItem][3] = trim(formatear_numero($d_importe, 2)); // IMPORTE TOTAL DEL PRODUCTO O ITEM (COLOCAR AQUI LA SUMA DE SUB TOTAL + TOTAL IGV)
                $laItems[$lnItem][4] = trim(formatear_numero($d_igv, 2)); // IMPORTE DEL IMPUESTO IGV DEL PRODUCTO O ITEM 
                $laItems[$lnItem][5] = trim($d_descripcion); // DESCRIPCIÓN DEL PRODUCTO O ITEM 
                $laItems[$lnItem][6] = trim($d_codigo); // CÓDIGO DEL PRODUCTO O ITEM 
                $laItems[$lnItem][7] = trim(formatear_numero($d_precio, 2)); // PRECIO UNITARIO DEL PRODUCTO O ITEM 
                if ( $d_isc > 0) { 
                    $laItems[$lnItem][2] = trim(formatear_numero($d_subtotal - $d_isc, 2));
                    $laItems[$lnItem][8] = trim(formatear_numero($d_isc, 2));
                    $laItems[$lnItem][9] = trim($d_tipoisc);
                } 
                if ( $d_icbper > 0 ){
                    $laItems[$lnItem][2] = trim(formatear_numero($d_icbper, 2)); // MONTO TRIBUTO POR LINEA
                    $laItems[$lnItem][8] = "0.10"; // TASA ICBPER
                } 			
                $laItems[$lnItem][10] = "NIU"; // CODIGO DE UNIDAD DE MEDIDA UNIT CODE
                $laItems[$lnItem][11] = $d_tipoigv; 
                $laItems[$lnItem][12] = trim($d_codsunat); // CÓDIGO SUNAT DEL PRODUCTO O ITEM 
                $laItems[$lnItem][13] = trim(formatear_numero($d_factigv, 2)); // PORCENTAJE IGV O IVAP  
                
                if ($d_referencia_id > 0 && $d_importe < 0){
                    $d_importe = abs($d_importe);
                    $docxpagar_referencia = DocumentToPay::selectidcmd_billing($d_referencia_id);
                    if ($docxpagar_referencia) {
                        $laItems[$lnItem][6] = "";
                        $laItems[$lnItem][3] = trim(formatear_numero($d_importe, 2));	// MONTO PREPAGAGO O ANTICIPO
                        $laItems[$lnItem][5] = substr(trim($docxpagar_referencia->seriedoc), -4) . '-' . trim($docxpagar_referencia->numerodoc);
                        $d_totant = $d_totant + $d_importe;
                    } 
                } 
            } // FIN ITEMS ACEPTADOS 
        } // FIN FOREACH

        $lcmontototalanticipo = "";
        if ( $d_totant > 0 ) { 
            $lcTvSunat = '0103';
            $lcmontototalanticipo = trim(formatear_numero($d_totant, 2));
        } 

        $d_igvconsumo = 0;

        if ( !empty($lcTotalConsumo) ) {
            $d_igvconsumo = $lcTotalConsumo / (1 + $lcPorcentajeIGV / 100) * ($lcPorcentajeIGV / 100);
        } 
        
        $lnDifIgv = ($lcTotalIGV + $d_igvconsumo - $d_totigv);

        if ($lnDifIgv != 0 && $documento->esgratuito == 0){

            $lnMax = abs($lnDifIgv) / 0.01;

            if ( $lnItem > $lnMax ){

                if ($lnDifIgv > 0){
                    $lnDifIgv = 0.01;
                }else{
                    $lnDifIgv = -0.01;
                } 
                for( $i = 1; $i <= $lnMax; $i++){
                    $laItems[$lnItem - ($i - 1)][4] = trim(formatear_numero($laItems[$lnItem - ($i - 1)][4] + $lnDifIgv, 2));
                    $laItems[$lnItem - ($i - 1)][2] = trim(formatear_numero(abs($laItems[$lnItem - ($i - 1)][3] - $laItems[$lnItem - ($i - 1)][4]), 2));
                }
            }else{
                $laItems[$lnItem][4] = trim(formatear_numero($laItems[$lnItem][4] + $lnDifIgv, 2));
                $laItems[$lnItem][2] = trim(formatear_numero(abs($laItems[$lnItem][3] - $laItems[$lnItem][4]), 2));	
            } 
        } 

        $llFacReg = false;
        $lcChrEnt = PHP_EOL;
        $lcStrXML = "";

        $lcStrXML .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . $lcChrEnt;
        $lcStrXML .= '<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:ccts="urn:un:unece:uncefact:documentation:2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:ds="http://www.w3.org/2000/09/xmldsig#"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"' . $lcChrEnt;
        $lcStrXML .= '  xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:urn="urn:un:unece:uncefact:data:specification:CoreComponentTypeSchemaModule:2"' . $lcChrEnt;
        $lcStrXML .= '	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $lcChrEnt;
        $lcStrXML .= '<ext:UBLExtensions>' . $lcChrEnt;
        $lcStrXML .= '	<ext:UBLExtension>' . $lcChrEnt; 
        $lcStrXML .= '		<ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .= '			<sac:AdditionalInformation>' . $lcChrEnt;
        $lcStrXML .= '				<sac:AdditionalMonetaryTotal>' . $lcChrEnt; 
        $lcStrXML .= '					<cbc:ID>1001</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '					<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeGrav . '</cbc:PayableAmount>' . $lcChrEnt;
        $lcStrXML .= '				</sac:AdditionalMonetaryTotal>' . $lcChrEnt;
        $lcStrXML .= '			</sac:AdditionalInformation>' . $lcChrEnt;
        $lcStrXML .= '		</ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .= '	</ext:UBLExtension>' . $lcChrEnt;
        // NODO PARA LA FIRMA DIGITAL
        $lcStrXML .= '	<ext:UBLExtension>' . $lcChrEnt;  
        $lcStrXML .= '		<ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .= '		</ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .= '	</ext:UBLExtension>' . $lcChrEnt;
        $lcStrXML .= '</ext:UBLExtensions>' . $lcChrEnt;
        
        $lcStrXML .= '<cbc:UBLVersionID>2.1</cbc:UBLVersionID>' . $lcChrEnt;
        $lcStrXML .= '<cbc:CustomizationID>2.0</cbc:CustomizationID>' . $lcChrEnt;  
        // DATOS DEL DOCUMENTO 
        $lcStrXML .= '<cbc:ID>' . $lcSerieNum . '</cbc:ID>' . $lcChrEnt; 
        $lcStrXML .= '<cbc:IssueDate>' . $lcFechaEmision . '</cbc:IssueDate>' . $lcChrEnt; 
        $lcStrXML .= '<cbc:IssueTime>' . $lcHoraEmision . '</cbc:IssueTime>' . $lcChrEnt;

        //	LEYENDAS / NOTAS
        $lcStrXML .= '<cbc:Note languageLocaleID="1000">' . $lcImporteTotal_Letras . '</cbc:Note>' . $lcChrEnt;
        if ($documento->esgratuito == 1){
            $lcStrXML .= '<cbc:Note languageLocaleID="1002">TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE</cbc:Note>' . $lcChrEnt;
        } 
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcPtoVenta . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcTipoVenta . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcNroOp . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcGlosa . '</cbc:Note>' . $lcChrEnt;
        
        $lcStrXML .= '<cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $lcMoneda . '</cbc:DocumentCurrencyCode>' . $lcChrEnt;  
        $lcStrXML .= '<cbc:LineCountNumeric>' . trim($lnCountItem) . '</cbc:LineCountNumeric>' . $lcChrEnt; 
        
        // TIPO NOTA DE CREDITO
        $lcStrXML .= '<cac:DiscrepancyResponse>' . $lcChrEnt;
        $lcStrXML .= 	'<cbc:ReferenceID>' . $lcDocumentoAfecta . '</cbc:ReferenceID>' . $lcChrEnt;
        $lcStrXML .= 	'<cbc:ResponseCode>' . $lcTipoNotaCreDe . '</cbc:ResponseCode>' . $lcChrEnt;  
        $lcStrXML .= 	'<cbc:Description>' . trim($lcDescriSustento) . '</cbc:Description>' . $lcChrEnt; 
        $lcStrXML .= '</cac:DiscrepancyResponse>' . $lcChrEnt;

        
        // DOCUMENTO QUE MODIFICA
        $lcStrXML .= '<cac:BillingReference>' . $lcChrEnt;
        $lcStrXML .= 	'<cac:InvoiceDocumentReference>' . $lcChrEnt;
        $lcStrXML .= 		'<cbc:ID>' . $lcDocumentoAfecta . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= 		'<cbc:DocumentTypeCode>' . $lcTipoDocAfecta  . '</cbc:DocumentTypeCode>' . $lcChrEnt;       
        $lcStrXML .= 	'</cac:InvoiceDocumentReference>' . $lcChrEnt;
        $lcStrXML .= '</cac:BillingReference>' . $lcChrEnt; 
        
        // GUIA DE REMISIÓN RELACIONADA
        if (is_string($lcNroGuiaRemision)){

           if ( !empty($lcNroGuiaRemision) ) { 
              $lcStrXML .= '<cac:DespatchDocumentReference>]' . $lcChrEnt;
              $lcStrXML .=   '<cbc:ID>' . $lcNroGuiaRemision . '</cbc:ID>' . $lcChrEnt;
              $lcStrXML .=   '<cbc:DocumentTypeCode>09</cbc:DocumentTypeCode>' . $lcChrEnt;  
              $lcStrXML .= '</cac:DespatchDocumentReference>' . $lcChrEnt;
            }

        }

        $lcStrXML .= '<cac:Signature>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:ID>S' . $lcSerieNum . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '	<cac:SignatoryParty>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyIdentification>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:ID>' . $lcRucFirmaDigital . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyIdentification>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:Name><![CDATA[' . $lcRazonEmpresaEmisor . ']]></cbc:Name>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyName>' . $lcChrEnt;
        $lcStrXML .= '	</cac:SignatoryParty>' . $lcChrEnt;
        $lcStrXML .= '	<cac:DigitalSignatureAttachment>' . $lcChrEnt;
        $lcStrXML .= '		<cac:ExternalReference>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:URI>#' . $lcSerieNum . '</cbc:URI>' . $lcChrEnt;
        $lcStrXML .= '		</cac:ExternalReference>' . $lcChrEnt;
        $lcStrXML .= '	</cac:DigitalSignatureAttachment>' . $lcChrEnt;
        $lcStrXML .= '</cac:Signature>' . $lcChrEnt;
        
        // DATOS DEL EMISOR
        $lcStrXML .= '<cac:AccountingSupplierParty>' . $lcChrEnt;
        $lcStrXML .= '	<cac:Party>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyIdentification>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $lcRucEmisor . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyIdentification>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:Name><![CDATA[' . $lcRazonEmpresaEmisor . ']]></cbc:Name>' . $lcChrEnt; 
        $lcStrXML .= '		</cac:PartyName>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:RegistrationName><![CDATA[' . $lcRazonEmpresaEmisor . ']]></cbc:RegistrationName>' . $lcChrEnt; 
        $lcStrXML .= '			<cac:RegistrationAddress>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $lcUbigeoEmisor . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0001</cbc:AddressTypeCode>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:CityName><![CDATA[' . $lcCiudadEmisor . ']]></cbc:CityName>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:CountrySubentity><![CDATA[' . $lcCiudadEmisor . ']]></cbc:CountrySubentity>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:District><![CDATA[' . $lcDistritoEmisor . ']]></cbc:District>' . $lcChrEnt;
        $lcStrXML .= '				<cac:AddressLine>' . $lcChrEnt;
        $lcStrXML .= '					<cbc:Line><![CDATA[' . $lcDirEmpresaEmisor . ']]></cbc:Line>' . $lcChrEnt; 
        $lcStrXML .= '				</cac:AddressLine>' . $lcChrEnt;
        $lcStrXML .= '				<cac:Country>' . $lcChrEnt;
        $lcStrXML .= '					<cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>' . $lcChrEnt;
        $lcStrXML .= '				</cac:Country>' . $lcChrEnt;
        $lcStrXML .= '			</cac:RegistrationAddress>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '	</cac:Party>' . $lcChrEnt;
        $lcStrXML .= '</cac:AccountingSupplierParty>' . $lcChrEnt;
        
        // DATOS DEL CLIENTE
        $lcStrXML .= '<cac:AccountingCustomerParty>' . $lcChrEnt;
        $lcStrXML .= '	<cac:Party>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyIdentification>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:ID schemeID="' . $lcTipoDocCliente . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $lcRucCliente . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyIdentification>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PostalAddress>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:ID>' . $lcUbigeoCliente . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:StreetName><![CDATA[' . $lcDirCliente . ']]></cbc:StreetName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:CityName>' . $lcProvCliente . '</cbc:CityName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:CountrySubentity>' . $lcDepCliente . '</cbc:CountrySubentity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:District>' . $lcDistritoCliente . '</cbc:District>' . $lcChrEnt;
        $lcStrXML .= '			<cac:Country>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:IdentificationCode>' . $lcCodigoPaisCliente . '</cbc:IdentificationCode>' . $lcChrEnt;
        $lcStrXML .= '			</cac:Country>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PostalAddress>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:RegistrationName><![CDATA[' . $lcRazonEmpresaCliente . ']]></cbc:RegistrationName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:CompanyID>' . $lcRucCliente . '</cbc:CompanyID>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '	</cac:Party>' . $lcChrEnt;
        $lcStrXML .= '</cac:AccountingCustomerParty>' . $lcChrEnt;

        // TIPO CAMBIO
        $lcStrXML .= '<cac:PaymentExchangeRate>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:SourceCurrencyCode>PEN</cbc:SourceCurrencyCode>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:TargetCurrencyCode>USD</cbc:TargetCurrencyCode>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:CalculationRate>' . $lcTcambio . '</cbc:CalculationRate>' . $lcChrEnt;
        $lcStrXML .= '</cac:PaymentExchangeRate>' . $lcChrEnt;
        
        // TOTALES Y SUBTOTALES
        $lcStrXML .= '<cac:TaxTotal>' . $lcChrEnt; 
        $lcStrXML .= '	<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalIGV . '</cbc:TaxAmount>' . $lcChrEnt;
        if ($lcTipoIGV == '40'){
            $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
            $lcStrXML .= '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeInaf . '</cbc:TaxableAmount>' . $lcChrEnt;
            $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">0.00</cbc:TaxAmount>' . $lcChrEnt;
            $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
            $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
            $lcStrXML .= '					<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9995</cbc:ID>' . $lcChrEnt;
            $lcStrXML .= '					<cbc:Name>EXP</cbc:Name>' . $lcChrEnt;
            $lcStrXML .= '					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
            $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
            $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
            $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
        }else{ 
            if ( $lcTotVentaOpeGrav != 0 ){
                $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
                $lcStrXML .= '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeGrav . '</cbc:TaxableAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalIGV . '</cbc:TaxAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ID schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe" schemeID="UN/ECE 5305">S</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '			<cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:Name>IGV</cbc:Name>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
                $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            } 
            if ( $lcTotVentaOpeInaf != 0 ){
                $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
                $lcStrXML .= '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeInaf . '</cbc:TaxableAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">0.00</cbc:TaxAmount>' . $lcChrEnt;
                $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:ID schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe" schemeID="UN/ECE 5305">O</cbc:ID>' . $lcChrEnt;	
                $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '					<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '					<cbc:Name>INA</cbc:Name>' . $lcChrEnt;
                $lcStrXML .= '					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
                $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            } 
            if ( $lcTotVentaOpeExo != 0 ){
                $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
                $lcStrXML .= '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeExo . '</cbc:TaxableAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">0.00</cbc:TaxAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '			<cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:Name>EXO</cbc:Name>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
                $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            } 
            if ( $lcTotVentaOpeGrat != 0 ) {
                $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
                $lcStrXML .= '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeGrat . '</cbc:TaxableAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">0.00</cbc:TaxAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ID schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe" schemeID="UN/ECE 5305">Z</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '			<cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:Name>GRA</cbc:Name>' . $lcChrEnt;
                $lcStrXML .= '				<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
                $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            }
        }         

        // IMPUESTO SELECTIVO AL CONSUMO
        if (is_string($lcTotalISC)) {
            if ( !empty($lcTotalISC) ) {
              $lcStrXML .= '<cac:TaxSubtotal>' . $lcChrEnt; 
              $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalISC . '</cbc:TaxAmount>' . $lcChrEnt;
              $lcStrXML .= '	<cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;
              $lcStrXML .= '		<cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">2000</cbc:ID>' . $lcChrEnt; 
              $lcStrXML .= '				<cbc:Name>ISC</cbc:Name>' . $lcChrEnt;
              $lcStrXML .= '				<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>' . $lcChrEnt;
              $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            }
        } 
        // IMPUESTO ICBPER BOLSAS PLASTICAS
        if (is_string($lcTotalICBPER)) {
            if ( !empty($lcTotalICBPER) ) {
              $lcStrXML .= 	'<cac:TaxSubtotal>' . $lcChrEnt; 
              $lcStrXML .= 	'	<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalICBPER . '</cbc:TaxAmount>' . $lcChrEnt;
              $lcStrXML .= 	'	<cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= 	'		<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;
              $lcStrXML .= 	'		<cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= 	'			<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">7152</cbc:ID>' . $lcChrEnt; 
              $lcStrXML .= 	'			<cbc:Name>ICBPER</cbc:Name>' . $lcChrEnt;
              $lcStrXML .= 	'			<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>' . $lcChrEnt;
              $lcStrXML .= 	'		</cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= 	'	</cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= 	'</cac:TaxSubtotal>' . $lcChrEnt;
            }
        } 
        $lcStrXML .= '</cac:TaxTotal>' . $lcChrEnt; 
        
        $lcStrXML .= '<cac:LegalMonetaryTotal>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:LineExtensionAmount currencyID="' . $lcMoneda . '">' . $lcSubTotalDoc . '</cbc:LineExtensionAmount>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:TaxInclusiveAmount currencyID="' . $lcMoneda . '">' . $lcTotalDoc . '</cbc:TaxInclusiveAmount>' . $lcChrEnt; 
        if ( !empty($lcTotalConsumo) ){
            $lcStrXML .= '	<cbc:ChargeTotalAmount currencyID="' . $lcMoneda . '">' . $lcTotalConsumo . '</cbc:ChargeTotalAmount>' . lcChrEnt; 
        } 
        if ( in_array($lcTipoIGV, array('11', '12', '13', '14','15','16')) ){
           $lcStrXML .= '	<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . "0.00" . '</cbc:PayableAmount>' . $lcChrEnt; 
        }else{
           $lcStrXML .= '	<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . $lcTotalDoc . '</cbc:PayableAmount>' . $lcChrEnt; 
        }
        $lcStrXML .= '</cac:LegalMonetaryTotal>' . $lcChrEnt;
                
        // ITEMS DE LA FACTURA   
        if ( is_string($laItems[1][1]) ) {

            for ($lnItem = 1; $lnItem <= count($laItems); $lnItem++){

                if ( empty($laItems[$lnItem][6]) ) { // SI NO EXISTE CÓDIGO DEL PRODUCTO
                    continue; 
                } 

                $lcTipoIGV = trim($laItems[$lnItem][11]);
                $lcStrXML .= '<cac:CreditNoteLine>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:ID>' . trim($lnItem) . '</cbc:ID>' . $lcChrEnt;
                $resultado_condicional = ( is_string($laItems[$lnItem][10]) ) ? $laItems[$lnItem][10] : 'NIU';
                $lcStrXML .= '	<cbc:CreditedQuantity unitCode="' . $resultado_condicional . '" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">' . $laItems[$lnItem][1] . '</cbc:CreditedQuantity>' . $lcChrEnt; 
                $resultado_condicional = (in_array($lcTipoIGV, array("11","12","13","14","15","16")) ) ?  "0.00" : $laItems[$lnItem][2];
                $lcStrXML .= '	<cbc:LineExtensionAmount currencyID="' . $lcMoneda . '">' . $resultado_condicional . '</cbc:LineExtensionAmount>' . $lcChrEnt; 
                $lcStrXML .= '	<cac:PricingReference>' . $lcChrEnt;
                // PRECIO PARA OPERACIONES ONEROSAS
                if ( in_array($lcTipoIGV, array("10", "17", "20", "30", "40")) ) {
                    $lcStrXML .= '	<cac:AlternativeConditionPrice>' . $lcChrEnt; 
                    $lcStrXML .= '		<cbc:PriceAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][3] . '</cbc:PriceAmount>' . $lcChrEnt;
                    $lcStrXML .= '		<cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>' . $lcChrEnt;
                    $lcStrXML .= '	</cac:AlternativeConditionPrice>' . $lcChrEnt;  
                }else{ 
                    $lcStrXML .= '	<cac:AlternativeConditionPrice>' . $lcChrEnt; 
        		    //lcStrXML .= '		'<cbc:PriceAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][7] . '</cbc:PriceAmount>' . $lcChrEnt; 
                    $lcStrXML .= '		<cbc:PriceAmount currencyID="' . $lcMoneda . '">0.00</cbc:PriceAmount>' . $lcChrEnt; 
                    $lcStrXML .= '		<cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">02</cbc:PriceTypeCode>' . $lcChrEnt;
                    $lcStrXML .= '	</cac:AlternativeConditionPrice>' . $lcChrEnt;  
                }
                $lcStrXML .= '	</cac:PricingReference>' . $lcChrEnt;
            
                $lcStrXML .= '	<cac:TaxTotal>' . $lcChrEnt;
                // IMPUESTO IGV
                if ( $documento->esgratuito == 1 ) {
                    $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][4] . '</cbc:TaxAmount>' . $lcChrEnt;
                    $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][2] . '</cbc:TaxableAmount>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][4] . '</cbc:TaxAmount>' . $lcChrEnt;
                    $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                    $resultado_condicional = ( $laItems[$lnItem][4] == 0 ) ?  "0.00" :  $laItems[$lnItem][13];
                    $lcStrXML .= '				<cbc:Percent>' . $resultado_condicional . '</cbc:Percent>' . $lcChrEnt;
                    $lcStrXML .= '				<cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">' . $laItems[$lnItem][11] . '</cbc:TaxExemptionReasonCode>' . $lcChrEnt;
                    $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;	
                    $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9996</cbc:ID>' . $lcChrEnt;
                    $lcStrXML .= '					<cbc:Name>GRA</cbc:Name>' . $lcChrEnt;
                    $lcStrXML .= '					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
                    $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
                    $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
                    $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
                }else{ 
                    $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][4] . '</cbc:TaxAmount>' . $lcChrEnt;
                    $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][2] . '</cbc:TaxableAmount>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][4] . '</cbc:TaxAmount>' . $lcChrEnt;
                    $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                    $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;	
                    $resultado_condicional = ( $laItems[$lnItem][4] == 0 ) ? "0.00" : $laItems[$lnItem][13];
                    $lcStrXML .= '				<cbc:Percent>' . $resultado_condicional . '</cbc:Percent>' . $lcChrEnt;
                    $lcStrXML .= '				<cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">' . $laItems[$lnItem][11] . '</cbc:TaxExemptionReasonCode>' . $lcChrEnt;
                    $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
                    
                    switch($lcTipoIGV){
                        case "20":
                        case "21":
                            $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9997</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:Name>EXO</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
                        break;
                        case "40":
                            $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9995</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:Name>EXP</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
                        break;
                        default: 
                            if ( $laItems[$lnItem][4] > 0 ){
                                $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">1000</cbc:ID>' . $lcChrEnt;
                                $lcStrXML .= '					<cbc:Name>IGV</cbc:Name>' . $lcChrEnt;
                                $lcStrXML .= '					<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
                            }else{ 
                                $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9998</cbc:ID>' . $lcChrEnt;
                                $lcStrXML .= '					<cbc:Name>INA</cbc:Name>' . $lcChrEnt;
                                $lcStrXML .= '					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
                            } 
                        break;
                    } 
                    $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
                    $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
                    $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
                } 
                
                // IMPUESTO ISC
                if ( is_string($lcTotalISC) ){
                    if ( !empty($lcTotalISC) ) {
                        if ( is_string(laItems[lnItem][8]) ){
                            $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][8] . '</cbc:TaxAmount>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][2] . '</cbc:TaxableAmount>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][8] . '</cbc:TaxAmount>' . $lcChrEnt;
                            $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                            $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;	
                            $lcStrXML .= '				<cbc:Percent>' . $laItems[$lnItem][13] . '</cbc:Percent' . $lcChrEnt;
                            $lcStrXML .= '				<cbc:TierRange>' . $laItems[$lnItem][9] . '</cbc:TierRange>' . $lcChrEnt; 
                            $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">2000</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:Name>ISC</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>' . $lcChrEnt;
                            $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
                            $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
                        }
                    }
                }
                // FIN ISC
        
                // IMPUESTO ICBPER
                if ( is_string($lcTotalICBPER) ){
                    if ( !empty($lcTotalICBPER) ){
                        if ( is_string($laItems[$lnItem][8]) ) {
                            $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][8] . '</cbc:TaxAmount>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:BaseUnitMeasure unitCode="' . $laItems[$lnItem][10] . '">' . intval(trim($laItems[$lnItem][1])) . '</cbc:BaseUnitMeasure>' . $lcChrEnt;		
                            $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                            $lcStrXML .= '				<cbc:PerUnitAmount currencyID="PEN">' . $laItems[$lnItem][9] . '</cbc:PerUnitAmount>' . $lcChrEnt;
                            $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:Name>ICBPER</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>' . $lcChrEnt;
                            $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
                            $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
                        }
                    }
                }
                // FIN ICBPER
                $lcStrXML .= '	</cac:TaxTotal>' . $lcChrEnt;
                
                $lcStrXML .= '	<cac:Item>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:Description><![CDATA[' . $laItems[$lnItem][5] . ']]></cbc:Description>' . $lcChrEnt;
                $lcStrXML .= '		<cac:SellersItemIdentification>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ID>' . $laItems[$lnItem][6] . '</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '		</cac:SellersItemIdentification>' . $lcChrEnt;
                $lcStrXML .= '		<cac:CommodityClassification>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">' . $laItems[$lnItem][12] . '</cbc:ItemClassificationCode>' . $lcChrEnt;
                $lcStrXML .= '		</cac:CommodityClassification>' . $lcChrEnt;
                $lcStrXML .= '	</cac:Item>' . $lcChrEnt;
                $lcStrXML .= '	<cac:Price>' . $lcChrEnt;
                $resultado_condicional = (in_array($lcTipoIGV, array("11", "12", "13", "14", "15", "16", "21")) ) ?  "0.00" : $laItems[$lnItem][7];
                $lcStrXML .= '		<cbc:PriceAmount currencyID="' . $lcMoneda . '">' . $resultado_condicional . '</cbc:PriceAmount>' . $lcChrEnt;
                $lcStrXML .= '	</cac:Price>' . $lcChrEnt;
                $lcStrXML .= '</cac:CreditNoteLine>' . $lcChrEnt;
            } // FOR ITEM
        } // ES STRING EL CÓDIGO         

        $lcStrXML .= '</CreditNote>';

        return $lcStrXML;

    } // FIN HANDLE
}
