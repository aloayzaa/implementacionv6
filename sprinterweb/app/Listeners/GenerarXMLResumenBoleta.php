<?php

namespace App\Listeners;

use App\Events\GenerarXMLResumenBoletaEvents;
use App\Panel\Companies\Entities\Pempresa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerarXMLResumenBoleta
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
     * @param  GenerarXMLResumenBoletaEvents  $event
     * @return void
     */
   public function handle(GenerarXMLResumenBoletaEvents $event)
   {
        //
        dd($event->reportecmd);


        /*   INDEX on ALLTRIM(tipodoc) + ALLTRIM(seriedoc) + ALLTRIM(numerodoc) TAG i1
           lcSerieNum = DTOS(fechaproceso) + '-' + ALLTRIM(STR(numero))
           lcFechaEmision = PADL(YEAR(fechaproceso),4,'0') + '-' + PADL(MONTH(fechaproceso),2,'0') + '-' + PADL(DAY(fechaproceso),2,'0')
           lcFechaDocumentos = PADL(YEAR(fechadoc),4,'0') + '-' + PADL(MONTH(fechadoc),2,'0') + '-' + PADL(DAY(fechadoc),2,'0')
           COUNT TO lnCant
           GO TOP
           IF lnCant = 0
           RETURN
           ENDIF */

        $lcRucFirmaDigital = trim(Pempresa::get_parametro('CER_RUC')->valor); // NÚMERO DE RUC DEL RESPONSABLE DE LA FIRMA DIGITAL
        $lcRazonEmpresaFirma = trim(Pempresa::get_parametro('CER_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA RESPONSABLE DE LA FIRMA DIGITAL

        // DATOS DEL EMISOR
        $lcRucEmisor = trim(Pempresa::get_parametro('CPE_RUC')->valor); // NÚMERO DE RUC DEL EMISOR
        $lcRazonEmpresaEmisor = trim(Pempresa::get_parametro('CPE_NOMBRE')->valor); // RAZON SOCIAL DE LA EMPRESA EMISORA
        $lcUbigeoEmisor = trim(Pempresa::get_parametro('CPE_UBIGEO')->valor); // CÓDIGO DE UBIGEO
        $lcDirEmpresaEmisor = trim(Pempresa::get_parametro('CPE_DIRECCION')->valor); // DIRECCIÓN DE LA EMPRESA EMISORA
        $lcCiudadEmisor = trim(Pempresa::get_parametro('CPE_CIUDAD')->valor); // CIUDAD DEL EMISOR - DATO OPCIONAL
        $lcDistritoEmisor = "";


        
        $lcChrEnt = PHP_EOL;
        $lcStrXML = "";

        $lcStrXML .= '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>' .$lcChrEnt;
        $lcStrXML .= '<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1' .$lcChrEnt;
        $lcStrXML .= 'xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' .$lcChrEnt;
        $lcStrXML .= 'xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' .$lcChrEnt;
        $lcStrXML .= 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#' .$lcChrEnt;
        $lcStrXML .= 'xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2' .$lcChrEnt;
        $lcStrXML .= 'xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1' .$lcChrEnt;
        $lcStrXML .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .$lcChrEnt;
        $lcStrXML .= 'ext:UBLExtensions>' .$lcChrEnt;


        //NODO PARA LA FIRMA DIGITAL

        $lcStrXML .= '  <ext:UBLExtension>';
        $lcStrXML .= '      <ext:ExtensionContent>' .$lcChrEnt;
        $lcStrXML .= '      </ext:ExtensionContent>' .$lcChrEnt;
        $lcStrXML .= '  </ext:UBLExtension>' .$lcChrEnt;

        //FIN DE LA FIRMA DIGITAL

        $lcStrXML .= '</ext:UBLExtensions>' .$lcChrEnt;
        $lcStrXML .= '<cbc:UBLVersionID>2.0</cbc:UBLVersionID>' .$lcChrEnt;  //UBL 2.1?
        $lcStrXML .= '<cbc:CustomizationID>1.1</cbc:CustomizationID>' .$lcChrEnt;
        $lcStrXML .= '<cbc:ID>RC-' . $lcSerieNum . '</cbc:ID>' .$lcChrEnt;
        /* $lcStrXML  = cbc:DocumentTypeCode>RC</cbc:DocumentTypeCode> . $lcChrEnt*/
        $lcStrXML .= '<cbc:ReferenceDate>' . $referencedate . '</cbc:ReferenceDate>' . $lcChrEnt;
        $lcStrXML .= '<cbc:IssueDate>' . $lcFechaEmision . '</cbc:IssueDate>' . $lcChrEnt;

        //Signature

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



       //Datos del Emisor

        $lcStrXML .= '<cac:AccountingSupplierParty>' . $lcChrEnt;
        $lcStrXML .= '<cbc:CustomerAssignedAccountID>' . $lcRucEmisor  .'</cbc:CustomerAssignedAccountID>' .$lcChrEnt;
        $lcStrXML .= '	<cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>' .$lcChrEnt;
        $lcStrXML .= '	<cac:Party>' . $lcChrEnt;
        $lcStrXML .= '		<cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:RegistrationName><![CDATA[' . $lcRazonEmpresaEmisor . ']]></cbc:RegistrationName>' . $lcChrEnt;
        $lcStrXML .= '		</cac:PartyLegalEntity>' . $lcChrEnt;
        $lcStrXML .= '	</cac:Party>' . $lcChrEnt;
        $lcStrXML .= '</cac:AccountingSupplierParty>' . $lcChrEnt;


        //LOGICA

        //
        $lcStrXML .= '<sac:SummaryDocumentsLine>' .$lcChrEnt;
        $lcStrXML .= '	<cbc:LineID>' . $lnitem . '</cbc:LineID>' .$lcChrEnt; //ALLTRIM(STR($lnItem))
        $lcStrXML .= '	<cbc:DocumentTypeCode>' . $tipodoc . '</cbc:DocumentTypeCode>' .$lcChrEnt; //ALLTRIM(tipodoc)
        $lcStrXML .= '	<cbc:ID>' . trim($seriedoc) . '-' . trim($numerodoc) . '</cbc:ID>' .$lcChrEnt;
        $lcStrXML .= '	<cac:AccountingCustomerParty>' .$lcChrEnt;
        $lcStrXML .= '		<cbc:CustomerAssignedAccountID>' . $ter_nrodoc . '</cbc:CustomerAssignedAccountID>' .$lcChrEnt;
        $lcStrXML .= '		<cbc:AdditionalAccountID>' . trim($ter_tipodoc) . '</cbc:AdditionalAccountID>' .$lcChrEnt;
        $lcStrXML .= '	</cac:AccountingCustomerParty>' .$lcChrEnt;

        if(trim($tipodoc) == '07' || trim($tipodoc) == '08'){
            if(empty(trim($referencia))){
                $lcStrXML .= '	<cac:BillingReference>' . $lcChrEnt;
                $lcStrXML .= '	<cac:InvoiceDocumentReference>' .$lcChrEnt;
                $lcStrXML .= '  		<cbc:ID>' . trim($referencia) . '</cbc:ID>' . $lcChrEnt;;
                $lcStrXML .= '  		<cbc:DocumentTypeCode>' . trim($tipodocref) . '</cbc:DocumentTypeCode>' . $lcChrEnt;
                $lcStrXML .= '	</cac:InvoiceDocumentReference>' . $lcChrEnt;
                $lcStrXML .= '	</cac:BillingReference>' . $lcChrEnt;
            }
        }

        if($percepcion > 0) {
            $lcStrXML .= '	<sac:SUNATPerceptionSummaryDocumentReference>' . $lcChrEnt;
            $lcStrXML .= '  		<sac:SUNATPerceptionSystemCode>' . '01' . '</sac:SUNATPerceptionSystemCode>' . $lcChrEnt;
            $lcStrXML .= '  		<sac:SUNATPerceptionPercent>' . trim(STR(factpercep, 12, 3)) . '</sac:SUNATPerceptionPercent>' . $lcChrEnt;
            $lcStrXML .= '  		<cbc:TotalInvoiceAmount>' . trim(STR(m . percepcion, 12, 2)) . '</cbc:TotalInvoiceAmount>' . $lcChrEnt;
            $lcStrXML .= '  		<sac:SUNATTotalCashed>' . trim(STR(m . percepcion + m . total, 12, 2)) . '</sac:SUNATTotalCashed>' . $lcChrEnt;
            $lcStrXML .= '  		<cbc:TaxableAmount>' . trim(STR(m . gravado, 12, 2)) . '</cbc:TaxableAmount>' . $lcChrEnt;
            $lcStrXML .= '	</sac:SUNATPerceptionSummaryDocumentReference>' . $lcChrEnt;
        }

        $lcStrXML .= '	<cac:Status>' . $lcChrEnt;
        $lcStrXML .= '		<cbc:ConditionCode>' . trim($estado) . '</cbc:ConditionCode>' . $lcChrEnt;
        $lcStrXML .= '	</cac:Status>' . $lcChrEnt;
        $lcStrXML .= '	<sac:TotalAmount currencyID="' . $lcMoneda .'">' . trim(STR(total,15,2)) . '</sac:TotalAmount>' .$lcChrEnt;

        if($gravado>0){
            $lcStrXML .= '	<sac:BillingPayment>' . $lcChrEnt;
            $lcStrXML .=  '		<cbc:PayableAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.gravado,15,2)) . '</cbc:PayableAmount>' . $lcChrEnt;
            $lcStrXML .= '		<cbc:InstructionID>01</cbc:InstructionID>' .$lcChrEnt;
            $lcStrXML .= '	</sac:BillingPayment>' .$lcChrEnt;
        }

        if($exonera){
           $lcStrXML .= '	<sac:BillingPayment>' .$lcChrEnt;
           $lcStrXML .= '		<cbc:PaidAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.exonera,15,2)) . '</cbc:PaidAmount>' .$lcChrEnt;
           $lcStrXML .= '		<cbc:InstructionID>02</cbc:InstructionID>' .$lcChrEnt;
           $lcStrXML .= '	</sac:BillingPayment>' .$lcChrEnt;
        }

        if($inafecto){
           $lcStrXML .= '	<sac:BillingPayment>' .$lcChrEnt;
           $lcStrXML .= '	<cbc:PaidAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.inafecto,15,2)) . '</cbc:PaidAmount>' .$lcChrEnt;
           $lcStrXML .= '		<cbc:InstructionID>03</cbc:InstructionID>' .$lcChrEnt;
           $lcStrXML .= '	</sac:BillingPayment>' .$lcChrEnt;
        }

        if($gratuito){
           $lcStrXML .= '	<cac:AllowanceCharge>' .$lcChrEnt;
           $lcStrXML .= '		<cbc:ChargeIndicator>true</cbc:ChargeIndicator>' .$lcChrEnt;
           $lcStrXML .= '		<cbc:Amount currencyID="' . $lcMoneda . '">' . trim(STR(m.gratuito,15,2)) . '</cbc:Amount>' .$lcChrEnt;
           $lcStrXML .= '	</cac:AllowanceCharge>' .$lcChrEnt;
        }

        if($otros){
           $lcStrXML .= '	<cac:AllowanceCharge>' . $lcChrEnt;
           $lcStrXML .= '		<cbc:ChargeIndicator>true</cbc:ChargeIndicator>' .$lcChrEnt;
           $lcStrXML .= '		<cbc:Amount currencyID="' . $lcMoneda . '">' . trim(STR(m.otros,15,2)) . '</cbc:Amount>' .$lcChrEnt;
           $lcStrXML .= '	</cac:AllowanceCharge>' .$lcChrEnt;
        }


        //IMPUESTO SELECTIVO AL CONSUMO

        if($isc){
           $lcStrXML .= '	<cac:TaxTotal>' . $lcChrEnt;
           $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.isc,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
           $lcStrXML .= '		<cac:TaxSubtotal>' .$lcChrEnt;
           $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.isc,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
           $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
           $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
           $lcStrXML .= '					<cbc:ID>2000</cbc:ID>' . $lcChrEnt;
           $lcStrXML .= '					<cbc:Name>ISC</cbc:Name>' . $lcChrEnt;
           $lcStrXML .= '					<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>' . $lcChrEnt;
           $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
           $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
           $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
           $lcStrXML .= '	</cac:TaxTotal>' . $lcChrEnt;
        }  
        //IMPUESTO IGV

        $lcStrXML .= '	<cac:TaxTotal>' . $lcChrEnt;
        $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.igv,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
        $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
        $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.igv,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
        $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
        $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
        $lcStrXML .= '					<cbc:ID>1000</cbc:ID>' . $lcChrEnt;
        $lcStrXML .= '					<cbc:Name>IGV</cbc:Name>' . $lcChrEnt;
        $lcStrXML .= '					<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>' . $lcChrEnt;
        $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
        $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
        $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
        $lcStrXML .= '	</cac:TaxTotal>' . $lcChrEnt;


        //IMPUESTO ICBPER
        if($icbper){
           $lcStrXML  .= '	<cac:TaxTotal>' . $lcChrEnt;
           $lcStrXML  .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.icbper,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
           $lcStrXML  .= '		<cac:TaxSubtotal>' .$lcChrEnt;
           $lcStrXML  .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.icbper,15,2)) . '</cbc:TaxAmount>'. $lcChrEnt;
           $lcStrXML  .= '			<cac:TaxCategory>'. $lcChrEnt;
           $lcStrXML  .= '				<cac:TaxScheme>'. $lcChrEnt;
           $lcStrXML  .= '					<cbc:ID>7152</cbc:ID>'. $lcChrEnt;
           $lcStrXML  .= '					<cbc:Name>ICBPER</cbc:Name>'. $lcChrEnt;
           $lcStrXML  .= '					<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>'. $lcChrEnt;
           $lcStrXML  .= '				</cac:TaxScheme>'. $lcChrEnt;
           $lcStrXML  .= '			</cac:TaxCategory>' . $lcChrEnt;
           $lcStrXML  .= '		</cac:TaxSubtotal>' . $lcChrEnt;
           $lcStrXML  .= '	</cac:TaxTotal>'. $lcChrEnt;
        }

        if($otroimpto){
           $lcStrXML .= '	<cac:TaxTotal>' . $lcChrEnt;
           $lcStrXML .= '		<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.otroimpto ,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
           $lcStrXML .= '		<cac:TaxSubtotal>' . $lcChrEnt;
           $lcStrXML .= '			<cbc:TaxAmount currencyID="' . $lcMoneda . '">' . trim(STR(m.otroimpto ,15,2)) . '</cbc:TaxAmount>' . $lcChrEnt;
           $lcStrXML .= '			<cac:TaxCategory>' . $lcChrEnt;
           $lcStrXML .= '				<cac:TaxScheme>' . $lcChrEnt;
           $lcStrXML .= '					<cbc:ID>9999</cbc:ID>' . $lcChrEnt;
           $lcStrXML .= '					<cbc:Name>OTROS CONCEPTOS DE PAGO</cbc:Name>' . $lcChrEnt;
           $lcStrXML .= '					<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>' . $lcChrEnt;
           $lcStrXML .= '				</cac:TaxScheme>' . $lcChrEnt;
           $lcStrXML .= '			</cac:TaxCategory>' . $lcChrEnt;
           $lcStrXML .= '		</cac:TaxSubtotal>' . $lcChrEnt;
           $lcStrXML .= '	</cac:TaxTotal>' . $lcChrEnt;
        }

        $lcStrXML .= '</sac:SummaryDocumentsLine>' . $lcChrEnt;
            

            $lcStrXML .= '</SummaryDocuments>';

            return $lcStrXML;





   }
}
