<?php

namespace App\Listeners;

use App\Events\GenerarXMLComunicaBajaEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DateTime;
use App\Panel\Companies\Entities\Pempresa;

class GenerarXMLComunicaBaja
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
     * @param  GenerarXMLComunicaBajaEvents  $event
     * @return void
     */
    public function handle(GenerarXMLComunicaBajaEvents $event)
    {
        //dd($event);            
        //dd($event->reportecmd);

        $doccomunicacion = collect($event->reportecmd)->first();
        $doccomunicacion_detalles = $event->reportecmd;

        $lcTipoDoc = trim($doccomunicacion->tipo);
        $lcSerieNum = str_replace('-', '',$doccomunicacion->fechaproceso) . '-' . trim($doccomunicacion->numero); // falta especificar aun no esta realizado
        $lcFechaEmision = str_pad(DateTime::createFromFormat('Y-m-d', $doccomunicacion->fechaproceso)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $doccomunicacion->fechaproceso)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $doccomunicacion->fechaproceso)->format("d"), 2, '0', STR_PAD_LEFT); 
        $lcFechaDocumentos = str_pad(DateTime::createFromFormat('Y-m-d', $doccomunicacion->fechadoc)->format("Y"), 4, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $doccomunicacion->fechadoc)->format("m"), 2, '0', STR_PAD_LEFT) . '-' . str_pad(DateTime::createFromFormat('Y-m-d', $doccomunicacion->fechadoc)->format("d"), 2, '0', STR_PAD_LEFT);  
        
        //valida antes de llegar al xlm
        /*if($lnTotDocuemntos == 0){
            return;
        }*/

        $lcRucFirmaDigital = trim(Pempresa::get_parametro('CER_RUC')->valor); // NÚMERO DE RUC DEL RESPONSABLE DE LA FIRMA DIGITAL
        $lcRazonEmpresaFirma = trim(Pempresa::get_parametro('CER_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA RESPONSABLE DE LA FIRMA DIGITAL
    
        // DATOS DEL EMISOR
        $lcRucEmisor = trim(Pempresa::get_parametro('CPE_RUC')->valor); // NÚMERO DE RUC DEL EMISOR
        $lcRazonEmpresaEmisor = trim(Pempresa::get_parametro('CPE_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA EMISORA              
        $lcUbigeoEmisor = trim(Pempresa::get_parametro('CPE_UBIGEO')->valor); // CÓDIGO DE UBIGEO         
        $lcDirEmpresaEmisor = trim(Pempresa::get_parametro('CPE_DIRECCION')->valor); // DIRECCIÓN DE LA EMPRESA EMISORA 
        $lcCiudadEmisor = trim(Pempresa::get_parametro('CPE_CIUDAD')->valor); // CIUDAD DEL EMISOR - DATO OPCIONAL        
        $lcDistritoEmisor = ""; // DISTRITO DEL EMISOR - DATO OPCIONAL

        $lnItem = 0;
        $laItemsDocumento = array();

        foreach($doccomunicacion_detalles as $detalle){
            $lnItem += 1;
            $laItemsDocumento[$lnItem][1] = trim($detalle->tipodoc);
            $laItemsDocumento[$lnItem][2] = trim($detalle->seriedoc);
            $laItemsDocumento[$lnItem][3] = trim($detalle->numerodoc);
            $laItemsDocumento[$lnItem][4] = trim($detalle->glosa);
        }

        $lcChrEnt = PHP_EOL;
        $lcStrXML = "";

        $lcStrXML .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
        $lcStrXML .= '<VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1"' . $lcChrEnt;
        $lcStrXML .= ' xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"' . $lcChrEnt;
        $lcStrXML .= ' xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"' . $lcChrEnt;
        $lcStrXML .= ' xmlns:ds="http://www.w3.org/2000/09/xmldsig#"' . $lcChrEnt;
        $lcStrXML .= ' xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"' . $lcChrEnt;
        $lcStrXML .= ' xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"' . $lcChrEnt;
        $lcStrXML .= ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $lcChrEnt;
        $lcStrXML .= ' <ext:UBLExtensions>' . $lcChrEnt;
        // NODO PARA LA FIRMA DIGITAL
        $lcStrXML .= '<ext:UBLExtension>' . $lcChrEnt;
        $lcStrXML .= '	<ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .= '	</ext:ExtensionContent>' . $lcChrEnt;
        $lcStrXML .= '</ext:UBLExtension>' . $lcChrEnt;
        // FIN DE LA FIRMA DIGITAL
        $lcStrXML .= '</ext:UBLExtensions>' . $lcChrEnt;
        $lcStrXML .= '<cbc:UBLVersionID>2.0</cbc:UBLVersionID>' . $lcChrEnt;
        $lcStrXML .= '<cbc:CustomizationID>1.0</cbc:CustomizationID>' . $lcChrEnt;
        $lcStrXML .= '<cbc:ID>' . $lcTipoDoc . '-' . $lcSerieNum . '</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '<cbc:ReferenceDate>' . $lcFechaDocumentos . '</cbc:ReferenceDate>' . $lcChrEnt;
        $lcStrXML .= '<cbc:IssueDate>' . $lcFechaEmision . '</cbc:IssueDate>' . $lcChrEnt;
        $lcStrXML .= '<cac:Signature>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:ID>sign' . $lcRucEmisor . '</cbc:ID>' . $lcChrEnt;
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
        $lcStrXML .= '			<cbc:URI>#sign' . $lcRucEmisor . '</cbc:URI>' . $lcChrEnt;
        $lcStrXML .= '		</cac:ExternalReference>' . $lcChrEnt;
        $lcStrXML .= '	</cac:DigitalSignatureAttachment>' . $lcChrEnt;
        $lcStrXML .= '</cac:Signature>' . $lcChrEnt;
        // DATOS DEL EMISOR
        $lcStrXML .= '<cac:AccountingSupplierParty>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:CustomerAssignedAccountID>' . $lcRucEmisor . '</cbc:CustomerAssignedAccountID>' . $lcChrEnt;
        $lcStrXML .= '	<cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>' . $lcChrEnt;
        $lcStrXML .= '	<cac:Party>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:RegistrationName><![CDATA[' . $lcRazonEmpresaEmisor . ']]></cbc:RegistrationName>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '	</cac:Party>' . $lcChrEnt;
        $lcStrXML .= '</cac:AccountingSupplierParty>' . $lcChrEnt;
        // ITEMS DE DOCUMENTOS
        if(is_string($laItemsDocumento[1][1])){

            for($lnItem = 1; $lnItem <= count($doccomunicacion_detalles); $lnItem++){
                $lcStrXML .= '<sac:VoidedDocumentsLine>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:LineID>' . trim(intval($lnItem)) . '</cbc:LineID>' . $lcChrEnt;
                $lcStrXML .= '	<cbc:DocumentTypeCode>' . $laItemsDocumento[$lnItem][1] . '</cbc:DocumentTypeCode>' . $lcChrEnt;
                $lcStrXML .= '	<sac:DocumentSerialID>' . $laItemsDocumento[$lnItem][2] . '</sac:DocumentSerialID>' . $lcChrEnt;
                $lcStrXML .= '	<sac:DocumentNumberID>' . $laItemsDocumento[$lnItem][3] . '</sac:DocumentNumberID>' . $lcChrEnt;
                $lcStrXML .= '	<sac:VoidReasonDescription>' . $laItemsDocumento[$lnItem][4] . '</sac:VoidReasonDescription>' . $lcChrEnt;
                $lcStrXML .= '</sac:VoidedDocumentsLine>' . $lcChrEnt;
            }
        }
        $lcStrXML .= '</VoidedDocuments>' . $lcChrEnt;

        return $lcStrXML;
    }
}
