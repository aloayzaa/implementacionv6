<?php

namespace App\Listeners;

use App\Events\Generarxmlfactura21Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Panel\Companies\Entities\Pempresa;
use App\Ubigeos\Entities\Ubigeo;
use App\Customers\Entities\CustomerAccount;
use DateTime;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Transport\Entities\TransportGuide;
use App\UndTransport\Entities\UndTransport;

class Generarxmlfactura21
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
     * @param  Generarxmlfactura21Events  $event
     * @return void
     */
    public function handle(Generarxmlfactura21Events $event) // clasic, clase: entorno, método: generaxmlfactura21
    {
        
        //dd($event);            
        //dd($event->reportecmd);
        
        $documento = collect($event->reportecmd)->first();
        $documento_detalles = $event->reportecmd;
        
        $lcTipoDoc = trim($documento->tipodoc);
        $lcSerieNum = substr(trim($documento->seriedoc), -4) . '-' . trim($documento->numerodoc);
        $lcLetras = $documento->total . ' ' . $documento->mon_nombre;
        $lcFecha = str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->fechadoc)->format("d"), 2, '0', STR_PAD_LEFT);  
        $lcHoraEmision = trim($documento->horaproceso);
        $lcFechaVence = str_pad(DateTime::createFromFormat('Y-m-d', $documento->vencimiento)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->vencimiento)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->vencimiento)->format("d"), 2, '0', STR_PAD_LEFT);  
        $lcTvSunat = trim($documento->tvsunat);
        $lcPtoVenta = "PV:" . trim($documento->ptoventa);
        $lcTipoVenta = "TV:" . trim($documento->tipoventa);
        $lcCondicion = "CP:" . trim(strtoupper($documento->condicion));
        $lcNroOp = "OP:" . trim($documento->op_numero);
        $lcGlosa = trim($documento->glosa);
        $lcTcambio = trim(strval(formatear_numero($documento->tcambio, 4)));

        $lcMoneda = trim($documento->mon_codigo); // TIPO DE MONEDA DE LA FACTURA ELECTRÓNICA
        $lcTotVentaOpeGrav = trim(strval(formatear_numero($documento->base + $documento->totaldscto, 2))); // TOTAL VALOR VENTA DE LAS OPERACIONES GRAVADAS

        if( trim($documento->tipoafeigv) == '20' ){
            $lcTotVentaOpeExo = trim(strval(formatear_numero($documento->inafecto, 2))); // TOTAL VALOR VENTA DE LAS OPERACIONES EXONERADA
            $lcTotVentaOpeInaf = "0.00"; // TOTAL VALOR VENTA DE LAS OPERACIONES INAFECTAS
        }else{
            $lcTotVentaOpeInaf = trim(strval(formatear_numero($documento->inafecto, 2))); // TOTAL VALOR VENTA DE LAS OPERACIONES INAFECTAS
            $lcTotVentaOpeExo = "0.00"; // TOTAL VALOR VENTA DE LAS OPERACIONES EXONERADAS
        }

        $lcTotVentaOpeGrat = trim(strval(formatear_numero($documento->gratuito, 2))); // TOTAL VALOR VENTA DE LAS OPERACIONES GRATUITAS
        $lnfacturaanticipos = $documento->esanticipo;
        $lnfacturareganticipos = 0;
        
        if($documento->esgratuito == 1){

            $lcTotVentaOpeGrat = trim(strval(formatear_numero($documento->gratuito, 2)));
            $lcTotVentaOpeGrav = "0.00";
            $lcTotVentaOpeInaf = "0.00";

        }

        $lcTipoIGV = trim($documento->tipoafeigv); // TIPO DE AFECTACIÓN DEL IGV VER. CATÁLOGO No 7 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf            
        $lcPorcentajeIGV = trim(strval(formatear_numero($documento->factigv, 1))); // PORCENTAJE DEL IGV A USAR EN LA FACTURA ELECTRÓNICA
        $lcImporteTotal_Letras = $lcLetras; // IMPORTE TOTAL DE LA VENTA EN LETRAS
        $lcFechaEmision = $lcFecha; // FECHA DE EMISIÓN DE LA FACTURA ELECTRÓNICA      
        $lcRucFirmaDigital = trim(Pempresa::get_parametro('CER_RUC')->valor); // NÚMERO DE RUC DEL RESPONSABLE DE LA FIRMA DIGITAL
        $lcRazonEmpresaFirma = trim(Pempresa::get_parametro('CER_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA RESPONSABLE DE LA FIRMA DIGITAL

        // DATOS DEL EMISOR
        $lcRucEmisor = trim(Pempresa::get_parametro('CPE_RUC')->valor); // NÚMERO DE RUC DEL EMISOR
        $lcRazonEmpresaEmisor = trim(Pempresa::get_parametro('CPE_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA EMISORA              
        $lcUbigeoEmisor = trim(Pempresa::get_parametro('CPE_UBIGEO')->valor); // CÓDIGO DE UBIGEO         
        $lcDirEmpresaEmisor = trim(Pempresa::get_parametro('CPE_DIRECCION')->valor); // DIRECCIÓN DE LA EMPRESA EMISORA 
        $lcCiudadEmisor = trim(Pempresa::get_parametro('CPE_CIUDAD')->valor); // CIUDAD DEL EMISOR - DATO OPCIONAL        
        $lcDistritoEmisor = "";

        $ubigeo = Ubigeo::select("descripcion")->where("codigo", $lcUbigeoEmisor)->first();
        if($ubigeo){
            $lcDistritoEmisor = $ubigeo->descripcion;              
        }

        // GUIA DE REMISIÓN
        $lcNroGuiaRemision = trim($documento->gremision);
        $lcFecGuiaRemision = "";

        if( !empty(trim($lcNroGuiaRemision))){
            $lcFecGuiaRemision = str_pad(DateTime::createFromFormat('Y-m-d', $documento->gremisionfecha)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->gremisionfecha)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $documento->gremisionfecha)->format("d"), 2, '0', STR_PAD_LEFT);               
        }

        // ORDEN COMPRA CLIENTE
        $lcNroOcCliente = trim($documento->occliente);

        // DATOS DEL CLIENTE
        $lcRucCliente = trim($documento->ruc);
        $lcTipoDocCliente = trim($documento->ter_docide); // TIPO DE DOCUMENTO DEL CLIENTE VER. CATÁLOGO No 6 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf
        $lcRazonEmpresaCliente = trim($documento->razonsocial); // RAZON SOCIAL DEL CLIENTE
        $lcDirCliente = trim($documento->direccion); // DIRECCIÓN DEL CLIENTE
        $lcCodigoPaisCliente = trim($documento->pais); // CÓDIGO DE PAÍS DEL CLIENTE VER. CATÁLOGO No 4 SUNAT http://www.sunat.gob.pe/legislacion/superin/2014/anexo8-300-2014.pdf
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

        $lcReferencial = '';
        $temp_guiat_id = 0;
        $temp_peso = 0;
        if( 'mov_facturatransporte' == strtolower(trim($documento->ventana)) ){
            $temp_guiat_id = $documento->guiat_id;
            $temp_peso = $documento->guiat_peso;
            $lcReferencial = trim(strval(formatear_numero($documento->referencial, 2))); 
        }

        // DETRACCION
        $lcCtaBancoDetraccion = ""; // VER ORIGEN
        $lcMontoDetraccion = "";
        if($documento->detraccion > 0){
            $lcTvSunat = '1001';
            $lcTipoDetraccion = trim($documento->tipodetr);
            $lcMontoDetraccion = trim(strval(formatear_numero($documento->detraccion, 2)));
            $lcPorcentajeDetraccion = trim(strval(formatear_numero($documento->factordetr, 2)));
            $tercero_cuenta = CustomerAccount::selectparentcmd_codigo($lcRucEmisor);
            if($tercero_cuenta){
                if($tercero_cuenta->tipocuenta == "D"){
                    $lcCtaBancoDetraccion = trim($tercero_cuenta->cuenta);
                }

            }
        }

        $lnConIGV = Pempresa::get_parametro('CON_IMPTOS')->valor;
        $lcTotalISC = "";	
        $lcTotalICBPER = ""; // MONTO TOTAL DEL IMPUESTO ISC		
        $lcMontoPercepcion 	 = ""; // MONTO TOTAL DE LA PERCEPCIÓN
        $lcTotalDocPercepcion = "";
        $lcTotalDescuento = "";
        $lcTotalConsumo = "";        

        if($documento->totalisc > 0){ 
            $lcTotalISC = trim(strval(formatear_numero($documento->totalisc, 2)));  
        }
        if($documento->totalicbper > 0) {
            $lcTotalICBPER = trim(strval(formatear_numero($documento->totalicbper, 2)));
        }

        if($documento->impuesto2 > 0){
            $lcTvSunat = '2001';
            $lcMontoPercepcion = trim(strval(formatear_numero($documento->impuesto2, 2)));
            $lcTotalDocPercepcion = trim(strval(formatear_numero($documento->total + $documento->impuesto2, 2)));
        }   

        if($documento->impuesto4 > 0){
            $lcTotalConsumo = trim(strval(formatear_numero($documento->impuesto4, 2)));            
        }

        $lcTotalIGV = trim(strval(formatear_numero($documento->impuesto, 2))); // MONTO TOTAL DEL IMPUESTO IGV

        if($documento->esgratuito == 1){
            $lcTotalIGV = "0.00";            
        }
        
        if($documento->totaldscto != 0){
            $lcTotalDescuento = trim(strval(formatear_numero($documento->totaldscto, 2))); // OPCIONAL - DESCUENTOS TOTALES DEL DOCUMENTO          
        }

        $lcSubTotalDoc = trim(strval(formatear_numero($documento->base + $documento->inafecto + $documento->totaldscto, 2))); // SUB TOTAL DEL DOCUMENTO (AQUI TÁMBIEN ICLUIR LA SUMA DE OPERACIONES NO GRAVADAS)
        $lcTotalDoc = trim(strval(formatear_numero($documento->total, 2))); // TOTAL DEL DOCUMENTO

        // $d_  <- DETALLES

        $lnItem = 0;
        $lnItemDif = 0;
        $d_totigv = 0;
        $d_totant = 0;
        
        $detalles = collect($documento_detalles)->filter(function ($detalle) { // DETALLES VÁLIDOS // EXPLICACIÓN DEL CLASIC: la variable lnItemsDocumento  es para crear el array: DIMENSION laItems(lnItemsDocumento,14)
            return (($detalle->cantidad > 0 && $detalle->precio > 0) || $detalle->referencia_id > 0);
        });

        $lnCountItem = collect($documento_detalles)->filter(function ($detalle) { // EXPLICACIÓN CLASIC : lnCountItem es para especificar en el XML cúantas líneas de detalle tiene el documento
            return ($detalle->cantidad > 0 && $detalle->precio > 0 && $detalle->referencia_id == 0);
        });

        $lnCountItem = $lnCountItem->count(); // LOS DOCUMENTOS DE REFERENCIA NO VAN EN EL DETALLE DEL DOCUMENTO POR ESO LA CONDICIÓN Y EL BUCLE ANTERIOR

        //$laItems[$detalles->count()][14] = array();
        $laItems = array();

        foreach($documento_detalles as $detalle){

            $d_referencia_id = (empty($detalle->referencia_id)) ? 0 : $detalle->referencia_id;
            $d_codigo = trim($detalle->codigo);
            $d_codsunat = trim($detalle->codsunat);
            $d_descripcion = trim($detalle->descripcion);
            $temp_d_peso = (empty($detalle->peso)) ? 0 : $detalle->peso;
            $temp_d_cantidad = (empty($detalle->cantidad)) ? 0 : $detalle->cantidad;
            $d_cantidad = ($detalle->tipoprecio == 2 || $detalle->tipoprecio == 3) ? $temp_d_peso : $temp_d_cantidad;
            $d_umedida = ($detalle->tipoprecio == 2 || $detalle->tipoprecio == 3) ? 'KGM' : trim($detalle->umsunat);
            $d_precio = (empty($detalle->precio)) ? 0 : $detalle->precio;
            $d_subtotal = (empty($detalle->subtotal)) ? 0 : $detalle->subtotal;
            $d_importe = (empty($detalle->importe)) ? 0 : $detalle->importe;
            $d_serie = trim($detalle->serie);
            $d_lote = trim($detalle->lote);
            $d_nrovin = trim($detalle->nrovin);
            $d_isc = $detalle->isc;
            $d_icbper = $detalle->icbper * $d_cantidad;
            $d_tipoisc = trim($detalle->tipoisc);
            $d_tipoigv = trim($detalle->tipoafeigv);
            $d_factigv = $detalle->factigv;

            if( $documento->esgratuito == 1 && $d_importe == 0 ){

                $d_factigv = 18;
                $d_importe = $d_precio * $d_cantidad; // ver el clasic(esta en la condición pero no es necesario) 

                if ( $lnConIGV == 1 ){                            

                    $d_subtotal = $d_importe / ( 1 + ($d_factigv/100) );

                }else{

                    $d_subtotal = $d_importe * ( 1 + ($d_factigv/100) );

                }                    

            }

            if($lnConIGV == 1){

                $d_igv = $d_importe - $d_subtotal;

            }else{

                $d_igv = $d_subtotal - $d_importe;
                $d_importe = $d_subtotal;           
                $d_subtotal = $d_importe - $d_igv;
                
                if( $d_importe != 0 ){

                    if( $d_cantidad != 0 ){

                        $d_precio = formatear_numero($d_importe / $d_cantidad, 4);

                    }else{

                        $d_precio = $d_importe;

                    }

                }

            }

            $d_totigv = $d_totigv + $d_igv;

            if( ($d_cantidad > 0 && $d_precio > 0) || $d_referencia_id > 0 ){ // ITEMS ACEPTADOS

                // FALTA LA SELECCIÓN DEL CURSOR: crsundtransporte Y SUS CALCULOS (PREGUNTAR ORIGEN)
                //IF USED('crsundtransporte')
                //  IF !EMPTY(m.serie)
                //      SELECT crsundtransporte
                //      LOCATE FOR ALLTRIM(serie) == m.serie
                //      IF FOUND()
                //        m.descripcion = m.descripcion + " VIN:" + ALLTRIM(nrovin) + " NRO.MOTOR:" + ALLTRIM(nromotor)
                //      ENDIF 
                //  ENDIF 
                //ELSE 
                    if ( !empty($d_lote) ) { 
                        $d_descripcion = $d_descripcion . " LOTE:" . trim($d_lote);
                    } 
                    if ( !empty($d_serie) ){
                        $d_descripcion = $d_descripcion . " SERIE:" . trim($d_serie);
                    } 
                //ENDIF 


                $lnItem += 1; // EN CASO SE CAMBIE A CERO EXISTE UNA CONDICIÓN QUE UTILIZA EL CONTADOR, CAMBIAR TAMBIEN ESA CONDICIÓN
                $laItems[$lnItem][1] = trim(formatear_numero($d_cantidad, 3)); // CANTIDAD DEL PRODUCTO O ITEM
                $laItems[$lnItem][2] = trim(formatear_numero($d_subtotal, 2)); // SUB TOTAL DEL PRODUCTO O ITEM
                $laItems[$lnItem][3] = trim(formatear_numero($d_importe, 2)); // IMPORTE TOTAL DEL PRODUCTO O ITEM (COLOCAR AQUI LA SUMA DE SUB TOTAL + TOTAL IGV)
                $laItems[$lnItem][4] = trim(formatear_numero($d_igv, 2)); // IMPORTE DEL IMPUESTO IGV DEL PRODUCTO O ITEM 
                $laItems[$lnItem][5] = $d_descripcion; // DESCRIPCIÓN DEL PRODUCTO O ITEM 
                $laItems[$lnItem][6] = $d_codigo; // CÓDIGO DEL PRODUCTO O ITEM 
                $laItems[$lnItem][7] = trim(formatear_numero($d_precio, 2)); // PRECIO UNITARIO DEL PRODUCTO O ITEM 

                if ($d_isc > 0){

                    $laItems[$lnItem][2] = trim(formatear_numero($d_subtotal - $d_isc, 2));
                    $laItems[$lnItem][8] = trim(formatear_numero($d_isc, 2));
                    $laItems[$lnItem][9] = trim($d_tipoisc); 

                }

                if ($d_icbper > 0) {

                    $laItems[$lnItem][8] = trim(formatear_numero($d_icbper, 2)); // MONTO TRIBUTO POR LINEA
                    $laItems[$lnItem][9] = "0.10"; // TASA ICBPER

                }

                $laItems[$lnItem][10] = $d_umedida; // CODIGO DE UNIDAD DE MEDIDA UNIT CODE
                $laItems[$lnItem][11] = $d_tipoigv;
                $laItems[$lnItem][12] = $d_codsunat; // CÓDIGO SUNAT DEL PRODUCTO O ITEM 

                $laItems[$lnItem][13] = trim(formatear_numero($d_factigv ,2)); // PORCENTAJE IGV O IVAP  
                $laItems[$lnItem][14] = "";

                if (!empty($d_nrovin)){

                    $laItems[$lnItem][14] = $d_nrovin;

                }
                    
                if ($d_igv != 0 && $lnItemDif == 0){

                    $lnItemDif = $lnItem;

                }
                    
                if ( $d_referencia_id > 0 && $d_importe < 0 ) {

                    $d_importe = abs($d_importe);
                    $docxpagar_referencia = DocumentToPay::selectidcmd_billing($d_referencia_id);

                    if($docxpagar_referencia){

                        $laItems[$lnItem][6] = "";
                        $laItems[$lnItem][3] = trim(formatear_numero($d_importe,2));	// MONTO PREPAGAGO O ANTICIPO
                        $laItems[$lnItem][5] = substr(trim($docxpagar_referencia->seriedoc), -4) . '-' . trim($docxpagar_referencia->numerodoc);
                        $d_totant = $d_totant + $d_importe;

                    }

                } 
            } // FIN ITEMS ACEPTADOS


        } // fin foreach

        $lcmontototalanticipo = "";
        if ($d_totant > 0){
            $lcmontototalanticipo = trim(formatear_numero($d_totant, 2));
        }
        
        $d_igvconsumo = 0;

        if ( !empty($lcTotalConsumo) ){

            $d_igvconsumo = $lcTotalConsumo / ( 1 + $lcPorcentajeIGV / 100 ) * ( $lcPorcentajeIGV / 100 );
        } 

        $lnDifIgv = ($lcTotalIGV + $d_igvconsumo - $d_totigv);

        if( $lnDifIgv != 0 && $documento->esgratuito == 0){

            if ( abs($lnDifIgv * 100) < $lnItem ){

                $lnDif = $lnDifIgv / abs($lnDifIgv * 100);

                for ( $lnY = 1; $lnY <= abs((int) $lnDifIgv * 100); $lnY++ ){

                    if ( $laItems[$lnY][4] != '0.00'){

                        $laItems[$lnY][4] = trim(formatear_numero($laItems[$lnY][4] + $lnDif, 2));
                        $laItems[$lnY][2] = trim(abs(formatear_numero($laItems[$lnY][3] - $laItems[$lnY][4], 2)));

                    }

                }

            }else{

                $laItems[$lnItemDif][4] = trim(formatear_numero($laItems[$lnItemDif][4] + $lnDifIgv, 2));
                $laItems[$lnItemDif][2] = trim(abs(formatear_numero($laItems[$lnItemDif][3] - $laItems[$lnItemDif][4], 2)));                

            }

        }    

        //dd($laItems);
        $llFacReg = false;
        $lcChrEnt = PHP_EOL;
        $lcStrXML = "";

        $lcStrXML .=  '<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . $lcChrEnt;
        $lcStrXML .=  '<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:ccts="urn:un:unece:uncefact:documentation:2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:ds="http://www.w3.org/2000/09/xmldsig#"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"' . $lcChrEnt;
        $lcStrXML .=  ' xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:urn="urn:un:unece:uncefact:data:specification:CoreComponentTypeSchemaModule:2"' . $lcChrEnt;
        $lcStrXML .=  '	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $lcChrEnt;
        $lcStrXML .=  '<ext:UBLExtensions>' . $lcChrEnt;
        $lcStrXML .=  '	<ext:UBLExtension>' . $lcChrEnt; 
        $lcStrXML .=  '		<ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .=  '			<sac:AdditionalInformation>' . $lcChrEnt; 
        $lcStrXML .=  '				<sac:AdditionalMonetaryTotal>' . $lcChrEnt; 
        $lcStrXML .=  '					<cbc:ID>1001</cbc:ID>' . $lcChrEnt;
        $lcStrXML .=  '					<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeGrav . '</cbc:PayableAmount>' . $lcChrEnt; 
        $lcStrXML .=  '				</sac:AdditionalMonetaryTotal>' . $lcChrEnt;
        $lcStrXML .=  '			</sac:AdditionalInformation>' . $lcChrEnt;
        $lcStrXML .=  '		</ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .=  '	</ext:UBLExtension>' . $lcChrEnt;

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
        $lcStrXML .= '<cbc:DueDate>' . $lcFechaVence . '</cbc:DueDate>' . $lcChrEnt; 
        $lcStrXML .= '<cbc:InvoiceTypeCode listID="' . $lcTvSunat . '" listAgencyName="PE:SUNAT" listName="Tipo de Documento" name="Tipo de Operacion" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">' . $lcTipoDoc . '</cbc:InvoiceTypeCode>' . $lcChrEnt;        

        // LEYENDAS
        $lcStrXML .= '<cbc:Note languageLocaleID="1000">' . $lcImporteTotal_Letras . '</cbc:Note>' . $lcChrEnt;
        if ( $documento->esgratuito == 1 ){
            $lcStrXML .= '<cbc:Note languageLocaleID="1002">TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE</cbc:Note>' . $lcChrEnt;
        }
        if ( $documento->detraccion > 0 ){
            $lcStrXML .= '<cbc:Note languageLocaleID="2006">OPERACION SUJETA A DETRACCION</cbc:Note>' . $lcChrEnt;
        }
        $lcStrXML .= '<cbc:Note languageLocaleID="2005">OPERACION VENTA ITINERANTE</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcPtoVenta . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcTipoVenta . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcCondicion . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcNroOp . '</cbc:Note>' . $lcChrEnt;
        $lcStrXML .= '<cbc:Note languageID="es">' . $lcGlosa . '</cbc:Note>' . $lcChrEnt;

        $lcStrXML  .= '<cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $lcMoneda . '</cbc:DocumentCurrencyCode>' . $lcChrEnt;  
        $lcStrXML  .= '<cbc:LineCountNumeric>' . trim($lnCountItem) . '</cbc:LineCountNumeric>' . $lcChrEnt;

        // ORDEN COMPRA CLIENTE
        if ( is_string($lcNroOcCliente) ){

            if ( !empty($lcNroOcCliente) ){

                $lcStrXML .= '<cac:OrderReference>' . $lcChrEnt;
                $lcStrXML .= ' <cbc:ID>' . $lcNroOcCliente . '</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '</cac:OrderReference>' . $lcChrEnt;

            }

        }

        // GUIA DE REMISIÓN
        if ( is_string($lcNroGuiaRemision) ) {

            if ( !empty($lcNroGuiaRemision) ){

                $lcStrXML .= '<cac:DespatchDocumentReference>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:ID>' . $lcNroGuiaRemision . '</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">09</cbc:DocumentTypeCode>' . $lcChrEnt;  
                $lcStrXML .= '</cac:DespatchDocumentReference>' . $lcChrEnt;

            }
        }

        // FACTURA CON REGULARIZACION DE ANTICIPOS 
        if ( $lcmontototalanticipo != 0 ) {
            $d_itemant = 0;
            for ($lnItem = 1; $lnItem <= count($laItems); $lnItem++) { // contar filas = count($laItems), contar columnas = count($laItems[fila])
                if ( empty($laItems[$lnItem][6]) ) {

                    $d_itemant +=  1;

                    if (substr($laItems[$lnItem][5], 0 ,1) == 'F'){
                        $d_tipodoc = '02';
                    }else{
                        $d_tipodoc = '03';
                    }
                        
                    $lcStrXML .= '<cac:AdditionalDocumentReference>' . $lcChrEnt;
                    $lcStrXML .= '	<cbc:ID>' . $laItems[$lnItem][5] . '</cbc:ID>' . $lcChrEnt;
                    $lcStrXML .= '	<cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento Relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">' . $d_tipodoc . '</cbc:DocumentTypeCode>' . $lcChrEnt;
                    $lcStrXML .= '	<cbc:DocumentStatusCode listAgencyName="PE:SUNAT" listName="Anticipo">' . $laItems[$lnItem][5] . '</cbc:DocumentStatusCode>' . $lcChrEnt;
                    $lcStrXML .= '	<cac:IssuerParty>' . $lcChrEnt;
                    $lcStrXML .= '		<cac:PartyIdentification>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:ID schemeID="' . $lcTipoDocCliente . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $lcRucCliente . '</cbc:ID>' . $lcChrEnt;
                    $lcStrXML .= '		</cac:PartyIdentification>' . $lcChrEnt;
                    $lcStrXML .= '	</cac:IssuerParty>' . $lcChrEnt;
                    $lcStrXML .= '</cac:AdditionalDocumentReference>' . $lcChrEnt;
                } 
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
        $lcStrXML .= '			<cbc:CityName><![CDATA[' . $lcProvCliente . ']]></cbc:CityName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:CountrySubentity><![CDATA[' . $lcDepCliente . ']]></cbc:CountrySubentity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:District><![CDATA[' . $lcDistritoCliente . ']]></cbc:District>' . $lcChrEnt;
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
        
        // DIRECCION ENTREGA
        $lcStrXML .= '<cac:Delivery>' . $lcChrEnt;
        $lcStrXML .= '	<cac:DeliveryLocation>' . $lcChrEnt;
        $lcStrXML .= '		<cac:Address>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:CityName><![CDATA[' . $lcProvCliente . ']]></cbc:CityName>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:CountrySubentity><![CDATA[' . $lcDepCliente . ']]></cbc:CountrySubentity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:District><![CDATA[' . $lcDistritoCliente . ']]></cbc:District>' . $lcChrEnt;
        $lcStrXML .= '			<cac:AddressLine>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:Line><![CDATA[' . $lcDirCliente . ']]></cbc:Line>' . $lcChrEnt;
        $lcStrXML .= '			</cac:AddressLine>' . $lcChrEnt;
        $lcStrXML .= '			<cac:Country>' . $lcChrEnt;
        $lcStrXML .= '				<cbc:IdentificationCode>' . $lcCodigoPaisCliente . '</cbc:IdentificationCode>' . $lcChrEnt;
        $lcStrXML .= '			</cac:Country>' . $lcChrEnt;
        $lcStrXML .= '		</cac:Address>' . $lcChrEnt;
        $lcStrXML .= '	</cac:DeliveryLocation>' . $lcChrEnt;
        $lcStrXML .= '</cac:Delivery>' . $lcChrEnt;


        // DETRACCION
        if ( is_string($lcCtaBancoDetraccion) && is_string($lcMontoDetraccion) ){
            if ( !empty($lcCtaBancoDetraccion) && !empty($lcMontoDetraccion)) {
                $lcStrXML  .=  '<cac:PaymentMeans>' . $lcChrEnt;
                $lcStrXML  .=  '    <cbc:ID>Detraccion</cbc:ID>' . $lcChrEnt;
                $lcStrXML  .=  '	<cbc:PaymentMeansCode listAgencyName="PE:SUNAT" listName="Medio de pago" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo59">003</cbc:PaymentMeansCode>' . $lcChrEnt;
                $lcStrXML  .=  '	<cac:PayeeFinancialAccount>' . $lcChrEnt;
                $lcStrXML  .=  '		<cbc:ID>' . $lcCtaBancoDetraccion . '</cbc:ID>' . $lcChrEnt;
                $lcStrXML  .=  '	</cac:PayeeFinancialAccount>' . $lcChrEnt;
                $lcStrXML  .=  '</cac:PaymentMeans>' . $lcChrEnt;
            
                $lcStrXML  .=  '<cac:PaymentTerms>' . $lcChrEnt;
                $lcStrXML  .=  '	<cbc:ID>Detraccion</cbc:ID>' . $lcChrEnt;
                $lcStrXML  .=  '	<cbc:PaymentMeansID schemeName="SUNAT:Codigo de detraccion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54">' . $lcTipoDetraccion . '</cbc:PaymentMeansID>' . $lcChrEnt;
                $lcStrXML  .=  '	<cbc:PaymentPercent>' . $lcPorcentajeDetraccion . '</cbc:PaymentPercent>' . $lcChrEnt;
                $lcStrXML  .=  '	<cbc:Amount currencyID="PEN">' . $lcMontoDetraccion . '</cbc:Amount>' . $lcChrEnt;
                $lcStrXML  .=  '</cac:PaymentTerms>' . $lcChrEnt;
            }
        }        

        // FACTURA CON REGULARIZACION DE ANTICIPOS 
        if ( $lcmontototalanticipo != 0 ) {
            $d_itemant = 0;
            for ($lnItem = 1; $lnItem <= count($laItems); $lnItem++){
                if (empty($laItems[$lnItem][6])) {
                    $d_itemant = $d_itemant + 1;  
                    if (substr($laItems[lnItem][5], 0, 1) == 'F') {
                        $d_tipodoc = '02';
                    }else{
                        $d_tipodoc = '03';
                    }
                    $lcStrXML .= '<cac:PrepaidPayment>' . $lcChrEnt;
                    $lcStrXML .= '	<cbc:ID schemeID="' . $d_tipodoc . '" schemeName="Anticipo" schemeAgencyName="PE:SUNAT">' . $laItems[$lnItem][5] . '</cbc:ID>' . $lcChrEnt;
                    $lcStrXML .= '	<cbc:PaidAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][3] . '</cbc:PaidAmount>' . $lcChrEnt;
                    $lcStrXML .= '</cac:PrepaidPayment>' . $lcChrEnt;
                }
            }
        }

        // TIPO CAMBIO
        $lcStrXML .= '<cac:PaymentExchangeRate>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:SourceCurrencyCode>PEN</cbc:SourceCurrencyCode>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:TargetCurrencyCode>USD</cbc:TargetCurrencyCode>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:CalculationRate>' . $lcTcambio . '</cbc:CalculationRate>' . $lcChrEnt;
        $lcStrXML .= '</cac:PaymentExchangeRate>' . $lcChrEnt;        

        // TOTALES Y SUBTOTALES

        $lcStrXML .= '<cac:TaxTotal>' . $lcChrEnt; 
        $lcStrXML .= '	<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalIGV . '</cbc:TaxAmount>' . $lcChrEnt;
        if ( $lcTipoIGV == '40' ){
            $lcStrXML .=  '	<cac:TaxSubtotal>' . $lcChrEnt; 
            $lcStrXML .=  '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeInaf . '</cbc:TaxableAmount>' . $lcChrEnt;
            $lcStrXML .=  '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">0.00</cbc:TaxAmount>' . $lcChrEnt;
            $lcStrXML .=  '			<cac:TaxCategory>' . $lcChrEnt;
            $lcStrXML .=  '				<cac:TaxScheme>' . $lcChrEnt;
            $lcStrXML .=  '					<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9995</cbc:ID>' . $lcChrEnt;
            $lcStrXML .=  '					<cbc:Name>EXP</cbc:Name>' . $lcChrEnt;
            $lcStrXML .=  '					<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>' . $lcChrEnt;
            $lcStrXML .=  '				</cac:TaxScheme>' . $lcChrEnt;
            $lcStrXML .=  '			</cac:TaxCategory>' . $lcChrEnt;
            $lcStrXML .=  '	</cac:TaxSubtotal>' . $lcChrEnt;
        }else{ 
            if ( $lcTotVentaOpeGrav != 0 ) {
                $lcStrXML .=  '	<cac:TaxSubtotal>' . $lcChrEnt; 
                $lcStrXML .=  '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeGrav . '</cbc:TaxableAmount>' . $lcChrEnt;
                $lcStrXML .=  '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalIGV . '</cbc:TaxAmount>' . $lcChrEnt;
                $lcStrXML .=  '		<cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .=  '			<cbc:ID schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe" schemeID="UN/ECE 5305">S</cbc:ID>' . $lcChrEnt;
                $lcStrXML .=  '			<cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .=  '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>' . $lcChrEnt;
                $lcStrXML .=  '				<cbc:Name>IGV</cbc:Name>' . $lcChrEnt;
                $lcStrXML .=  '				<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
                $lcStrXML .=  '			</cac:TaxScheme>' . $lcChrEnt;
                $lcStrXML .=  '		</cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .=  '	</cac:TaxSubtotal>' . $lcChrEnt;
            } 
            if ( $lcTotVentaOpeInaf != 0 ) {
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
            if ( $lcTotVentaOpeExo != 0 ) {
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
                $lcStrXML .= '	</cac:TaxSubtotal>' . lcChrEnt;
            }
            if ( $lcTotVentaOpeGrat != 0 ) {
                $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
                $lcStrXML .= '		<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $lcTotVentaOpeGrat . '</cbc:TaxableAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalIGV . '</cbc:TaxAmount>' . $lcChrEnt;
                $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">Z</cbc:ID>' . $lcChrEnt;
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
        if ( is_string($lcTotalISC) ) {
            if ( !empty($lcTotalISC) ) {
              $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
              $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalISC . '</cbc:TaxAmount>' . $lcChrEnt;
              $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;
              $lcStrXML .= '			<cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">2000</cbc:ID>' . $lcChrEnt; 
              $lcStrXML .= '				<cbc:Name>ISC</cbc:Name>' . $lcChrEnt;
              $lcStrXML .= '				<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>' . $lcChrEnt;
              $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            }
        }


        // IMPUESTO ICBPER BOLSAS PLASTICAS
        if ( is_string($lcTotalICBPER) ) {
            if ( !empty($lcTotalICBPER) ) {
              $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt; 
              $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalICBPER . '</cbc:TaxAmount>' . $lcChrEnt;
              $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;
              $lcStrXML .= '			<cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">7152</cbc:ID>' . $lcChrEnt; 
              $lcStrXML .= '				<cbc:Name>ICBPER</cbc:Name>' . $lcChrEnt;
              $lcStrXML .= '				<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>' . $lcChrEnt;
              $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt; 
              $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt; 
              $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt;
            }
        }         

        // OTROS TRIBUTOS IVAP
        if ($lcTipoIGV == "17") {
           if ( is_string($lcTotalIVAP) ){
                if ( !empty($lcTotalIVAP) ) {
                    $lcStrXML .= '	<cac:TaxSubtotal>' . $lcChrEnt;
                    $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $lcTotalIVAP . '</cbc:TaxAmount>' . $lcChrEnt; 
                    $lcStrXML .= '		<cac:TaxCategory>' . $lcChrEnt; 
                    $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;
                    $lcStrXML .= '			<cac:TaxScheme>' . $lcChrEnt;
                    $lcStrXML .= '				<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9999</cbc:ID>' . $lcChrEnt; 
                    $lcStrXML .= '				<cbc:Name>OTROS</cbc:Name>' . $lcChrEnt; 
                    $lcStrXML .= '				<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>' . $lcChrEnt; 
                    $lcStrXML .= '			</cac:TaxScheme>' . $lcChrEnt; 
                    $lcStrXML .= '		</cac:TaxCategory>' . $lcChrEnt;
                    $lcStrXML .= '	</cac:TaxSubtotal>' . $lcChrEnt; 
                }
            }
        }

        $lcStrXML .= '</cac:TaxTotal>' . $lcChrEnt; 
        $lcStrXML .= '<cac:LegalMonetaryTotal>' . $lcChrEnt;

        // DESCUENTOS
        if ( !empty($lcTotalDescuento) ) {
            switch($lcTipoIGV){
                case "10":
                case "40":
                    $lcStrXML .= '	<cbc:AllowanceTotalAmount currencyID="' . $lcMoneda . '">' . $lcTotalDescuento . '</cbc:AllowanceTotalAmount>' . $lcChrEnt;
                break;
                case "14":
                case "11":
                case "13":
                    $lcStrXML .= '	<cbc:AllowanceTotalAmount currencyID="' . $lcMoneda . '">' . "0" . '</cbc:AllowanceTotalAmount>' . $lcChrEnt;                    
                break;
            }
        }else{ 

            $lcStrXML .= '	<cbc:LineExtensionAmount currencyID="' . $lcMoneda . '">' . $lcSubTotalDoc . '</cbc:LineExtensionAmount>' . $lcChrEnt;
            $lcStrXML .= '	<cbc:TaxInclusiveAmount currencyID="' . $lcMoneda . '">' . $lcTotalDoc . '</cbc:TaxInclusiveAmount>' . $lcChrEnt; 
            if ( !empty($lcTotalConsumo) ){
                $lcStrXML .= '	<cbc:ChargeTotalAmount currencyID="' . $lcMoneda . '">' . $lcTotalConsumo . '</cbc:ChargeTotalAmount>' . $lcChrEnt; 
            } 
        } 
        
        // ANTICIPOS
        if ($lcmontototalanticipo != 0){
            $lcStrXML .= '	<cbc:PrepaidAmount currencyID="' . $lcMoneda . '">' . $lcMontoTotalAnticipo . '</cbc:PrepaidAmount>' . $lcChrEnt;
        }  
        if ( in_array($lcTipoIGV, array('11', '12', '13', '14', '16', '16')) ){
           $lcStrXML .= '	<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . "0.00" . '</cbc:PayableAmount>' . $lcChrEnt; 
        }else{
           $lcStrXML .= '	<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . $lcTotalDoc . '</cbc:PayableAmount>' . $lcChrEnt; 
        }

        $lcStrXML .= '</cac:LegalMonetaryTotal>' . $lcChrEnt;

        // ITEMS DE LA FACTURA   
        if (is_string($laItems[1][1])){

            for ($lnItem = 1; $lnItem <= count($laItems); $lnItem++) { // contar filas = count($laItems), contar columnas = count($laItems[fila])
                
                if ( empty($laItems[$lnItem][6]) ){ // SI NO EXISTE CÓDIGO DEL PRODUCTO
                    continue;
                }
                     
                $lcTipoIgv = trim($laItems[$lnItem][11]);
                $lcStrXML .= '<cac:InvoiceLine>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:ID>' . trim($lnItem) . '</cbc:ID>' . $lcChrEnt;  
                $lcStrXML .= '	<cbc:InvoicedQuantity unitCode="' . $laItems[$lnItem][10] . '" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">' . $laItems[$lnItem][1] . '</cbc:InvoicedQuantity>' . $lcChrEnt; 
                $resultado_condicional = (in_array($lcTipoIGV, array("11","12","13","14","15","16","21","30","31","32","33","34","35","36"))) ? "0.00" : $laItems[$lnItem][2];
                $lcStrXML .= '	<cbc:LineExtensionAmount currencyID="' . $lcMoneda . '">' . $resultado_condicional . '</cbc:LineExtensionAmount>' . $lcChrEnt; 
            
                $lcStrXML .= '	<cac:PricingReference>' . $lcChrEnt;
                

                // PRECIO PARA OPERACIONES ONEROSAS
                if ( in_array($lcTipoIGV, array("10", "17", "20", "30", "40")) ){
                    $lcStrXML .= '		<cac:AlternativeConditionPrice>' . $lcChrEnt; 
                    $lcStrXML .= '			<cbc:PriceAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][7] . '</cbc:PriceAmount>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>' . $lcChrEnt;
                    $lcStrXML .= '		</cac:AlternativeConditionPrice>' . $lcChrEnt;  
                }else{
                    $lcStrXML .= '		<cac:AlternativeConditionPrice>' . $lcChrEnt; 
                    $lcStrXML .= '			<cbc:PriceAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][7] . '</cbc:PriceAmount>' . $lcChrEnt; 
                    $lcStrXML .= '			<cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">02</cbc:PriceTypeCode>' . $lcChrEnt;
                    $lcStrXML .= '		</cac:AlternativeConditionPrice>' . $lcChrEnt;  
                }
                $lcStrXML .= '	</cac:PricingReference>' . $lcChrEnt;
                
                if ($temp_guiat_id > 0){
                    $guia_transporte = TransportGuide::selectidcmd($temp_guiat_id);
                    if ($guia_transporte){
                        $d_ubigeop = trim($guia_transporte->ubigeop);
                        $d_ubigeod = trim($guia_transporte->ubigeod);
                        $d_direccionp = trim($guia_transporte->puntopartida);
                        $d_direcciond = trim($guia_transporte->puntollegada);
                        if ( $guia_transporte->referencial > 0){
                            $d_referencial = trim(formatear_numero($guia_transporte->referencial,2));
                            $d_referencial_u = trim(formatear_numero($guia_transporte->referencial * $guia_transporte->cargautil, 2));
                        }else{
                            $d_referencial = $lcReferencial;
                            $d_referencial_u = $lcReferencial;
                        } 
                        $lcStrXML .= '	<cac:Delivery>' . $lcChrEnt;
                        $lcStrXML .= '		<cac:DeliveryLocation>' . $lcChrEnt;
                        $lcStrXML .= '			<cac:Address>' . $lcChrEnt;
                        $lcStrXML .= '				<cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $d_ubigeod . '</cbc:ID>' . $lcChrEnt; 
                        $lcStrXML .= '				<cac:AddressLine>' . $lcChrEnt;
                        $lcStrXML .= '					<cbc:Line><![CDATA[' . $d_direcciond . ']]></cbc:Line>' . $lcChrEnt;
                        $lcStrXML .= '				</cac:AddressLine>' . $lcChrEnt;
                        $lcStrXML .= '			</cac:Address>' . $lcChrEnt;
                        $lcStrXML .= '		</cac:DeliveryLocation>' . $lcChrEnt;
                        $lcStrXML .= '		<cac:Despatch>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Instructions><![CDATA[' . $lcglosa . ']]></cbc:Instructions>' . $lcChrEnt;
                        $lcStrXML .= '			<cac:DespatchAddress>' . $lcChrEnt;
                        $lcStrXML .= '				<cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $d_ubigeop . '</cbc:ID>' . $lcChrEnt; 
                        $lcStrXML .= '				<cac:AddressLine>' . $lcChrEnt;
                        $lcStrXML .= '					<cbc:Line><![CDATA[' . $d_direccionp . ']]></cbc:Line>' . $lcChrEnt;
                        $lcStrXML .= '				</cac:AddressLine>' . $lcChrEnt;
                        $lcStrXML .= '			</cac:DespatchAddress>' . $lcChrEnt;
                        $lcStrXML .= '		</cac:Despatch>' . $lcChrEnt;
                        
                        if ( trim($lcTipoDetraccion) == '027' ){
                            if ($lcreferencial > 0){
                                $lcStrXML .= '		<cac:DeliveryTerms>' . $lcChrEnt;
                                $lcStrXML .= '			<cbc:ID>01</cbc:ID>' . $lcChrEnt;
                                $lcStrXML .= '			<cbc:Amount currencyID="PEN">' . $lcreferencial . '</cbc:Amount>' . $lcChrEnt;
                                $lcStrXML .= '		</cac:DeliveryTerms>' . $lcChrEnt;
                            } 
                            if ($d_referencial > 0){
                                $lcStrXML .= '		<cac:DeliveryTerms>' . $lcChrEnt;
                                $lcStrXML .= '			<cbc:ID>02</cbc:ID>' . $lcChrEnt;
                                $lcStrXML .= '			<cbc:Amount currencyID="PEN">' . $d_referencial . '</cbc:Amount>' . $lcChrEnt;
                                $lcStrXML .= '		</cac:DeliveryTerms>' . $lcChrEnt;
                            } 
                            if ($d_referencial_u > 0){
                                $lcStrXML .= '		<cac:DeliveryTerms>' . $lcChrEnt;
                                $lcStrXML .= '			<cbc:ID>03</cbc:ID>' . $lcChrEnt;
                                $lcStrXML .= '			<cbc:Amount currencyID="PEN">' . $d_referencial_u . '</cbc:Amount>' . $lcChrEnt;
                                $lcStrXML .= '		</cac:DeliveryTerms>' . $lcChrEnt;
                            } 
                        } 
                        $lcStrXML .= '	</cac:Delivery>' . $lcChrEnt;

                    } // FIN GUIA TRANSPORTE 

                } // FIN GUIAT          

                // IMPUESTO IGV
                $lcStrXML .= '	<cac:TaxTotal>' . $lcChrEnt;
                if ( $documento->esgratuito == 1 ) {
                    $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][4] . '</cbc:TaxAmount>' . $lcChrEnt;
                    $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                    $lcStrXML .= '			<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][2] . '</cbc:TaxableAmount>' . $lcChrEnt;
                    if ( in_array($laItems[lnItem][11], array("21","30","31","32","33","34","35","36")) ) {
                        $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">0.00</cbc:TaxAmount>' . $lcChrEnt;
                    }else{
                        $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][4] . '</cbc:TaxAmount>' . $lcChrEnt;
                    }
                    $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                    $resultado_condicional = ($laItems[$lnItem][4] == 0) ?  "0.00" : $laItems[$lnItem][13];
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
                    $resultado_condicional = ( $laItems[$lnItem][4] == 0) ?  "0.00" : $laItems[$lnItem][13];
                    $lcStrXML .= '				<cbc:Percent>' . $resultado_condicional . '</cbc:Percent>' . $lcChrEnt;
                    $lcStrXML .= '				<cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">' . $laItems[$lnItem][11] . '</cbc:TaxExemptionReasonCode>' . $lcChrEnt;
                    $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
                    switch($lcTipoIGV){ 
                        case "20":
                            $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9997</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:Name>EXO</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
                        break;
                        case "21":
                        case "37":
                            $lcStrXML .= '					<cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9996</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '					<cbc:Name>GRA</cbc:Name>' . $lcChrEnt;
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
                    if (!empty($lcTotalISC)){
                        if ( is_string($laItems[$lnItem][8]) ){
                            $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][2] . '</cbc:TaxAmount>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:TaxableAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][2] . '</cbc:TaxableAmount>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][8] . '</cbc:TaxAmount>' . $lcChrEnt;
                            $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>' . $lcChrEnt;
                            $lcStrXML .= '				<cbc:Percent>' . $laItems[$lnItem][13] . '</cbc:Percent>' . $lcChrEnt;
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
                } // FIN ISC  
                
                // IMPUESTO ICBPER
                if ( is_string($lcTotalICBPER) ){
                    if ( !empty($lcTotalICBPER) ){
                        if ( is_string($laItems[$lnItem][8]) ) {
                            $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . $laItems[$lnItem][8] . '</cbc:TaxAmount>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:BaseUnitMeasure unitCode="' . $laItems[$lnItem][10] . '">' . trim($laItems[$lnItem][1]) . '</cbc:BaseUnitMeasure>' . $lcChrEnt;		
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
                } // FIN ICBPER                
                
                $lcStrXML .= '	</cac:TaxTotal>' . $lcChrEnt;

                $lcStrXML .= '	<cac:Item>' . $lcChrEnt;
                $lcStrXML .= '		<cbc:Description><![CDATA[' . $laItems[$lnItem][5] . ']]></cbc:Description>' . $lcChrEnt;
                $lcStrXML .= '		<cac:SellersItemIdentification>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ID>' . $laItems[$lnItem][6] . '</cbc:ID>' . $lcChrEnt;
                $lcStrXML .= '		</cac:SellersItemIdentification>' . $lcChrEnt;
                $lcStrXML .= '		<cac:CommodityClassification>' . $lcChrEnt;
                $lcStrXML .= '			<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">' . $laItems[$lnItem][12] . '</cbc:ItemClassificationCode>' . $lcChrEnt;
                $lcStrXML .= '		</cac:CommodityClassification>' . $lcChrEnt;
        
                $d_nrovin = trim($laItems[$lnItem][14]); 

                if ( !empty($d_nrovin) ) {
                    $unidad_transporte = UndTransport::selectvincmd($d_nrovin);
                    if ($unidad_transporte){
                        $d_placa = trim($unidad_transporte->placa);
                        $d_categoria = (empty($unidad_transporte->cat_dsc)) ? '' : trim($unidad_transporte->cat_dsc);
                        $d_marca = (empty($unidad_transporte->mca_dsc)) ? '' : trim($unidad_transporte->mca_dsc);
                        $d_modelo = (empty($unidad_transporte->mod_dsc)) ? '' : trim($unidad_transporte->mod_dsc);
                        $d_color = (empty($unidad_transporte->col_dsc)) ? '' : trim($unidad_transporte->col_dsc);
                        $d_motor = trim($unidad_transporte->nromotor);
                        $d_combustible = (empty($unidad_transporte->tipocombustible)) ? '' : trim($unidad_transporte->tipocombustible);
                        $d_rodante = trim($unidad_transporte->tipomotor);
                        $d_chasis = trim($unidad_transporte->nrochasis);
                        $d_aniofab = trim($unidad_transporte->anio);
                        $d_aniomod = trim($unidad_transporte->aniomodelo);
                        $d_version = trim($unidad_transporte->version);
                        $d_ejes = trim($unidad_transporte->ejes);
                        $d_asientos = trim($unidad_transporte->asientos);
                        $d_pasajeros = trim($unidad_transporte->pasajeros);
                        $d_ruedas = trim($unidad_transporte->ruedas);
                        $d_carroceria = (empty($unidad_transporte->tca_dsc)) ? '' : trim($unidad_transporte->tca_dsc);
                        $d_potencia = trim($unidad_transporte->potencia);
                        $d_cilindros = trim($unidad_transporte->cilindros);
                        $d_cilindrada = trim($unidad_transporte->captanque);
                        $d_pesobruto = trim($unidad_transporte->pesobruto);
                        $d_pesoneto = trim($unidad_transporte->pesoneto);
                        $d_cargautil = trim($unidad_transporte->cargautil);
                        $d_largo = trim(formatear_numero($unidad_transporte->largo, 3));
                        $d_alto = trim(formatear_numero($unidad_transporte->alto, 3));
                        $d_ancho = trim(formatear_numero($unidad_transporte->ancho, 3));
                        $d_descripcion = trim($unidad_transporte->descripcion);
                        $d_observaciones = trim($unidad_transporte->observaciones);
                        
                        if ( empty($d_motor) ){	// GCR Sim Cards
                            $lcStrXML .= '		<cac:AdditionalItemProperty>]' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Numero Telefono</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_placa . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;  
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>SIM Card</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_nrovin . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;  				      			
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Descripcion</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_descripcion . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;  				      			
                        }else{ 
                            // Automotrices
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Name>Numero de Placa</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5010</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_placa . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Categoria</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5011</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_categoria . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Marca</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5012</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_marca . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Modelo</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5013</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_modelo . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Name>Color</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5014</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_color . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Name>Motor</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5015</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_motor . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Combustible</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5016</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_combustible . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Name>Form.Rodante</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5017</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_rodante . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>VIN</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5018</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_nrovin . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Serie/Chasis</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5019</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_chasis . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Año Fabricacion</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5020</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_aniofab . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Año Modelo</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5021</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_aniomod . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Version</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5022</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_version . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Name>Ejes</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5023</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_ejes . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Asientos</cbc:Name>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5024</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_asientos . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Pasajeros</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5025</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_pasajeros . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Ruedas</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5026</cbc:NameCode>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Value>' . $d_ruedas . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Carroceria</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5027</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_carroceria . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Potencia</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5028</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_potencia . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '			<cbc:Name>Cilindros</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5029</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_cilindros . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Cilindrada</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5030</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_cilindrada . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Peso Bruto</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5031</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_pesobruto . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Peso Neto</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5032</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_pesoneto . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Carga Util</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5033</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_cargautil . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Longitud</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5034</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_largo . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Altura</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5035</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_alto . '</cbc:Value>' . $lcChrEnt;
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Ancho</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">5036</cbc:NameCode>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_ancho . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Name>Observaciones:(Fecha Fabricacion)</cbc:Name>' . $lcChrEnt; 
                            $lcStrXML .= '			<cbc:Value>' . $d_observaciones . '</cbc:Value>' . $lcChrEnt; 
                            $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 					
                        } 
                    } 
                } // FIN IF d_nrovin               

                if ( $temp_guiat_id > 0 && $documento->detraccion > 0 ){                    
                    $guia_transporte = TransportGuide::selectidcmd($temp_guiat_id);
                    if ($guia_transporte){
                        $d_ubigeop = trim($guia_transporte->ubigeop);
                        $d_ubigeod = trim($guia_transporte->ubigeod);
                        $d_mtc = trim($guia_transporte->constanciamtc);
                        $d_configveh = trim($guia_transporte->configveh);
                        $d_pesobruto = trim(formatear_numero($guia_transporte->pesobruto, 2));
                        $d_cargautil = trim(formatear_numero($guia_transporte->cargautil, 2));
                        $lcReferTM = '';
                        if ($temp_peso > 0){
                            $lcReferTM = trim(formatear_numero($lcReferencial / $temp_peso, 2));
                        } 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Numero Registro MTC</cbc:Name>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3006</cbc:NameCode>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Value>' . $d_mtc . '</cbc:Value>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:ValueQuantity>' . trim(formatear_numero($temp_peso, 2)) . '</cbc:ValueQuantity>' . $lcChrEnt; 				
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Configuración Vehicular</cbc:Name>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3007</cbc:NameCode>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Value>' . $d_configveh . '</cbc:Value>' . $lcChrEnt;
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Punto de Origen</cbc:Name>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3008</cbc:NameCode>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Value>' . $d_ubigeop . '</cbc:Value>' . $lcChrEnt; 
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Punto Destino</cbc:Name>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3009</cbc:NameCode>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Value>' . $d_ubigeod . '</cbc:Value>' . $lcChrEnt; 
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Valor Referencial Preliminar por Viaje</cbc:Name>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3010</cbc:NameCode>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Value>' . $lcReferencial . '</cbc:Value>' . $lcChrEnt; 
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Valor Referencial Preliminar por Vehiculo</cbc:Name>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3011</cbc:NameCode>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Value>' . $lcReferencial  . '</cbc:Value>' . $lcChrEnt; 
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt;
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Valor Referencial Preliminar por TM en viaje</cbc:Name>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3012</cbc:NameCode>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Value>' . $lcReferTM . '</cbc:Value>' . $lcChrEnt; 
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Carga Efectiva en TM por Vehículo</cbc:Name>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3013</cbc:NameCode>' . $lcChrEnt; 
                        $lcStrXML .= '			<cbc:Value>' . trim(formatear_numero($temp_peso, 2)) . '</cbc:Value>' . $lcChrEnt;
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 
                        $lcStrXML .= '		<cac:AdditionalItemProperty>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Name>Detracciones: Transporte Bienes vía terrestre - Carga Útil en TM del Vehículo en Viaje</cbc:Name>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem" listAgencyName="PE:SUNAT">3014</cbc:NameCode>' . $lcChrEnt;
                        $lcStrXML .= '			<cbc:Value>' . $d_cargautil . '</cbc:Value>' . $lcChrEnt; 
                        $lcStrXML .= '		</cac:AdditionalItemProperty>' . $lcChrEnt; 						

                    } 
                }  // FIN IF $temp_guiat_id > 0 && $documento->detraccion > 0    
                
                $lcStrXML .= '	</cac:Item>' . $lcChrEnt;

                $lcStrXML .= '	<cac:Price>' . $lcChrEnt;
                $resultado_condicional = (in_array($lcTipoIGV, array("11","12","13","14","15","16","21","30","31","32","33","34","35","36"))) ? "0.00" : $laItems[$lnItem][7];
                $lcStrXML .= '		<cbc:PriceAmount currencyID="' . $lcMoneda . '">' . $resultado_condicional  . '</cbc:PriceAmount>' . $lcChrEnt;
                $lcStrXML .= '	</cac:Price>' . $lcChrEnt;
                $lcStrXML .= '</cac:InvoiceLine>' . $lcChrEnt;                

            } // FIN FOR ITEMS

        } // FIN CONDICIONAL ITEMS        

        $lcStrXML .= '</Invoice>';       

        return $lcStrXML;

    }
}
