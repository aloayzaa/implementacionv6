<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Events\Generarxmlfactura21Events;
use App\Events\GenerarXMLResumenBoletaEvents;
use App\Events\GenerarXMLComunicaBajaEvents;
use App\Events\CrearXMLEvents;
use App\Events\FirmarXMLEvents;
use App\Events\EnviarSunatEvents;
use App\Events\ComprimirXMLEvents;
use App\Events\GenerarXMLNCredito21Events;
use App\Events\GenerarXMLNDebito21Events;
use App\Events\DocxpagarCPEEvents;
use App\Events\VincularDocumentosEvents;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Panel\Companies\Entities\Pempresa;
use App\Http\Traits\ValidateGeneraFE;
use App\Http\Controllers\SunatRespuestaErrorController;
use App\Http\Controllers\ReporteCmdController;

class GeneraFEController extends Controller
{

    use ValidateGeneraFE;

    private $id; 
    private $docxpagar; // REGISTRO DOCXPAGAR
    private $docxpagar_cpe_id; // DOCXPAGAR_CPE - ID (ID DEL REGISTRO ACTUALIZAR O CREAR)

    private $nombre_tabla; // TABLA ORIGEN

    private $cpedoc; // SERIE + NÚMERO DOCUMENTO
    private $tipodoc; // TIPO DOCUMENTO COMERCIAL

    private $xml_nombre; // NOMBRE DEL ARCHIVO XML
    private $xml_estructura; // ESTRUCTURA DEL ARCHIVO A ENVIAR DEPENDIENDO DEL TIPO
    private $archivo_comprimido; // XML COMPRIMIDO 

    private $documento; // DOCUMENTO 
    private $reportecmd; // DOCUMENTO CON DETALLES

    private $lcpe; // FACTURACIÓN ELECTRÓNICA ACTIVO?
    private $cpetiposerv; // FACTURA ELECTRÓNICA. TIPO SERVIDOR
    private $ruc; // FACTURACIÓN ELECTRÓNICA. RUC FIRMA DIGITAL
    private $lnLinea; // FACTURA ELECTRÓNICA. PROCESAMIENTO EN LÍNEA

    private $respuesta_sunat; // EL RETORNO DE ENVIAR SUNAT

    private $reporteCmdController; // OBJETO REPORTECMD

    private $nro; // NÚMERO REGISTRO

    public function __construct($id, $lcpe, Model $docxpagar = null, $nro = null)
    {

        $this->id = $id;
        $this->lcpe = $lcpe;
        $this->docxpagar = $docxpagar;
        $this->nombre_tabla = $docxpagar->getTable();
        $this->nro = $nro;

        $this->cpetiposerv = Pempresa::get_parametro('CPE_SERVIDOR')->valor;
        $this->ruc = Pempresa::get_parametro('CPE_RUC')->valor;
        $this->lnLinea = Pempresa::get_parametro('CPE_PLINEA')->valor;

        $this->reporteCmdController = new ReporteCmdController($this->docxpagar, $this->id, $this->nro);
        
    }    

    public function reportecmd(){ 

        $this->reportecmd = $this->reporteCmdController->reportecmd();  // DOCUMENTO CON DETALLES
        $this->documento = collect($this->reportecmd)->first(); // DOCUMENTO

        // VALIDAR REPORTECMD
        if ( $data = $this->validar_reportecmd() ){return $data;}
        
    }

    public function generaFE(){ // clasic, formulario: frmmovimiento, método: generafe - cpegenerar

        // VALIDAR GENERAFE
        if ( $data = $this->validar_generafe() ){return $data;}

        // OBTENER LA DATA DEL DOCUMENTO
        if ( $data = $this->reportecmd() ) {return $data;} 
        
             
        $this->obtener_tipodoc_cpedoc(); 

        // NOMBRE XML
        if ( $data = $this->crear_nombre_xml() ) {return $data;}   
           
        
        // ESTRUCTURA XML POR TIPO
        $this->estructura_xml();

        
        // CREAR ARCHIVO XML Y GUARDAR
        $this->crear_archivo_xml();
        

        // FIRMAR ARCHIVO XML Y GUARDAR
        $this->firmar_archivo_xml();


        // COMPRIMIR ARCHIVO XML
       if ( $data = $this->comprimir_archivo_xml() ) {return $data;}

       
        // CREAR O ACTUALIZAR DOCXPGAR_CPE - OBTENER HASH
        $this->actualizar_docxpagar_cpe();       

        // ENVIAR SUNAT, ACTUALIZAR DOXCPAGAR_CPE Y RECIBIR RESPUESTA
        if ( $data = $this->enviar_sunat() ) {return $data;}

        // ENVIAR CORREO
        $this->enviar_correo();

        // VINCULAR DOCUMENTOS
        $this->vincular_documentos();

        $data['estado'] = $this->respuesta_sunat[0]['estado'];
        $data['codigo'] = $this->respuesta_sunat[0]['codigo'];
        $data['descripcion'] = $this->respuesta_sunat[0]['descripcion'];

        return $data;
        
    }


    public function cpegenerar(){// clasic, clase: entorno, método: cpegenerar
        
        switch($this->tipodoc){
            case '01' :
                return new Generarxmlfactura21Events($this->reportecmd);                
            break;
		    case '03' :
                return new Generarxmlfactura21Events($this->reportecmd);
            break;
            case  '07' :
                return new GenerarXMLNCredito21Events($this->reportecmd);
            break;
		    case '08' :
                return new GenerarXMLNDebito21Events($this->reportecmd, $this->cpetiposerv);
            break;
		    case '09' :
                return $this->generarxmlguiaremision();		
            break;
		    case '20' :
                return $this->generarxmlretencion();
            break;
		    case '40' :
                return $this->generarxmlpercepcion();
            break;
		    case 'RC' :
                return new GenerarXMLResumenBoletaEvents($this->reportecmd);
            break;
            case 'RA':        
            case 'RR' :
                return new GenerarXMLComunicaBajaEvents($this->reportecmd);
            break;

        }
    
    }

    public function obtener_tipodoc_cpedoc(){
        
        if ( strpos(strtolower($this->documento->ventana), 'mov_doccomunicacion') !== false ){

            $this->tipodoc = $this->documento->tipo;
            $this->cpedoc = str_replace("-", "", $this->documento->fechaproceso) . '-' . trim($this->documento->numero);

        }else {

            $this->tipodoc = trim($this->documento->tipodoc);
            $this->cpedoc = trim($this->documento->seriedoc) . '-' . trim($this->documento->numerodoc);

        }

    }


    public function crear_nombre_xml(){

        $this->xml_nombre =  $this->ruc . "-" . $this->tipodoc . "-" . trim($this->cpedoc) . ".xml";
        
        // VALIDAR EXISTENCIA XML
        if ( $data = $this->existe_xml() ){return $data;}   

    }


    public function estructura_xml(){
        
        $this->xml_estructura = event($this->cpegenerar());
        
    }

    public function crear_archivo_xml(){

        event(new CrearXMLEvents($this->xml_nombre, $this->xml_estructura, $this->ruc));

    }

    public function firmar_archivo_xml(){

        event(new FirmarXMLEvents($this->xml_nombre, $this->ruc));
        
    }

    private function comprimir_archivo_xml(){

        $this->archivo_comprimido = event(new ComprimirXMLEvents($this->xml_nombre, $this->ruc)); 

        // VALIDAR ARCHIVO COMPRIMIDO        
        if ( $data = $this->validar_archivo_comprimido() ){return $data;}
        

    }

    private function actualizar_docxpagar_cpe(){

        $docxpagar_cpe_id =  event(new DocxpagarCPEEvents($this->id, $this->cpetiposerv, $this->xml_nombre, $this->ruc, $this->docxpagar));
        $this->docxpagar_cpe_id = $docxpagar_cpe_id[0];
 
    }

    private function enviar_sunat(){

        //if ( $this->lnLinea != 1 ) {return null;}

        if ($this->valida_enviar_sunat()) { return null; }
            
        
        $this->respuesta_sunat =  event(new EnviarSunatEvents($this->archivo_comprimido, $this->cpetiposerv, $this->tipodoc, $this->xml_nombre, $this->ruc, $this->docxpagar_cpe_id, $this->id));
        $sunatRespuestaErrorController = new SunatRespuestaErrorController($this->respuesta_sunat, $this->docxpagar);

        // VALIDAR ENVIAR SUNAT        
        if ( $data = $sunatRespuestaErrorController->mostrar_error() ){return $data;}

            
      
    }

    private function enviar_correo(){

        if ( $this->lnLinea != 1 ) {return null;}

        if ( $this->cpetiposerv >= 3 ) {return null;}

        // ENVIAR CORREO

        // SUBIR A LA PÁGINA DE CUSTODIA

    }

    private function vincular_documentos(){

        // PROBABLEMENTE NO SEA NECESARIO GUARDAR LOS VINCULOS

        //event(new VincularDocumentosEvents($this->id, $this->xml_nombre, $this->ruc, $this->docxpagar, $this->nombre_tabla));

    }


}