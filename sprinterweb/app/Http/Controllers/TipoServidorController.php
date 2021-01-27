<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoServidorController extends Controller
{
    // OSE      
    const FE_OSE = "https://ose.bizlinks.com.pe/ol-ti-itcpe/billService?wsdl";

    // FACTURACION 
    const FE_PRODUCCION = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl";
    const FE_BETA = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl";

    // GUIA DE REMISION 
    const GUIA_PRODUCCION = " https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService?wsdl";
    const GUIA_BETA = "https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl";

    // RETENCION Y PERCEPCION
    const RETENCION_PRODUCCION = "https://e-factura.sunat.gob.pe/ol-ti-itemision-otroscpe-gem/billService?wsdl";
    const RETENCION_BETA = "https://e-beta.sunat.gob.pe/ol-ti-itemision-otroscpe-gem-beta/billService?wsdl";

    // TIPO SERVIDOR
    private $cpetiposerv;

    // TIPO DOCUMENTO
    private $tipodoc;

    private $endpoint;

    public function __construct($cpetiposerv, $tipodoc)
    {
        $this->cpetiposerv = $cpetiposerv;
        $this->tipodoc = $tipodoc;

    }    

    public function tipo_servidor(){

        switch ($this->cpetiposerv){

            case 1: //OSE  

                $this->endpoint = self::FE_OSE;

            break;

            case 2: // PRODUCCIÓN

                $this->endpoint = $this->segun_tipo_doc_produccion();
                
            break;

            case 3: // PRUEBAS  

                $this->endpoint = $this->segun_tipo_doc_pruebas();

            break;

            default: 

                return "No se ha configurado tipo de servidor para envío";

            break;
        
        }


    }

    private function segun_tipo_doc_produccion(){ // PRODUCCIÓN 

        switch($this->tipodoc){

            case '09': 

                return self::GUIA_PRODUCCION; // GUIA

            break;

            case '20':
            case '40':
            case '41':
            case 'RR':
            case 'RP':

                return self::RETENCION_PRODUCCION; // RETENCION Y PERCEPCION

            break;

            default:

                return self::FE_PRODUCCION; // FACTURACION 

            break;

        }

    }

    private function segun_tipo_doc_pruebas(){ // PRUEBAS
        
        switch($this->tipodoc){

            case '09': 

                return self::GUIA_BETA; // GUIA

            break;

            case '20':
            case '40':
            case '41':
            case 'RR':
            case 'RP':

                return self::RETENCION_BETA; // RETENCION Y PERCEPCION

            break;

            default:

                return self::FE_BETA; // FACTURACION 

            break;
        
        }

    }

    public function obtener_tipo_servidor(){

        return $this->endpoint;

    }

}
