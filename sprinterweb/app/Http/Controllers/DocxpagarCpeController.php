<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentToPay\Entities\DocumentToPayCPE;
use Carbon\Carbon;

class DocxpagarCpeController extends Controller
{
    private $selectparentcmd; // objeto consulta (ver existencia del cpe)
    private $docxpagar_cpe; // objeto DocumentToPayCPE (crear o actualizar - registro)
    private $docxpagar_id;

    private $ruc;
    private $xml_archivo; // ARCHIVO XML EN STRING
    private $xml_nombre;
    private $cpetiposerv;

    private $modelo;

    public function __construct($docxpagar_id, $ruc, $xml_nombre, $cpetiposerv, $modelo)
    {

        $this->ruc = $ruc;
        $this->xml_nombre = $xml_nombre;
        $this->cpetiposerv = $cpetiposerv;

        $this->modelo = $modelo;

        $this->docxpagar_id = $docxpagar_id;
        $this->selectparentcmd = DocumentToPayCPE::selectparentcmd($this->docxpagar_id);
        
    }  

    public function existencia_cpe(){

        if ($this->selectparentcmd){


            $this->docxpagar_cpe = DocumentToPayCPE::findOrFail($this->selectparentcmd->id);
            

        }else{

            // LA CONDICIONAL ES POR FECHA FIRMA - VER CLASIC
            // CUANDO VIENE DE MOV_FACTURAVENTA
            if(isset($this->modelo->origen_envio) == false){

                $this->docxpagar_cpe = new DocumentToPayCPE;            
                $this->docxpagar_cpe->docxpagar_id = $this->docxpagar_id;
                $this->docxpagar_cpe->fecha_genera = Carbon::now();            
                $this->docxpagar_cpe->fecha_firma = Carbon::now();

            }else{ // CUANDO VIENE DE ENVIO CPE

                $this->docxpagar_cpe = new DocumentToPayCPE;            
                $this->docxpagar_cpe->docxpagar_id = $this->docxpagar_id;
                $this->docxpagar_cpe->fecha_genera = Carbon::now();            


            }




        }

    }


    public function obtener_archivo(){

        // CUANDO SE GRABABA EN LOCAL
        //$this->xml_archivo = file_get_contents(public_path() . '/FIRMAXML/' . $this->ruc . '/' . $this->xml_nombre);
        
        // STORAGE DIGITAL OCEAN
        $this->xml_archivo = obtener_xml_firma2($this->ruc, $this->xml_nombre);

    }

    public function actualizar_o_crear_cpe(){
        
        // LA CONDICIONAL ES POR FECHA FIRMA - VER CLASIC
        // CUANDO VIENE DE MOV_FACTURAVENTA
        if(isset($this->modelo->origen_envio) == false){

            $this->docxpagar_cpe->respuesta_hash = $this->extraer_cpehash(); 
            $this->docxpagar_cpe->respuesta_firma = $this->extraer_cpevalorfirma();
            $this->docxpagar_cpe->cpe_servidor = $this->cpetiposerv;
            $this->docxpagar_cpe->save();


        }else{ // CUANDO VIENE DE ENVIO CPE

            $this->docxpagar_cpe->fecha_firma = Carbon::now();
            $this->docxpagar_cpe->respuesta_hash = $this->extraer_cpehash(); 
            $this->docxpagar_cpe->respuesta_firma = $this->extraer_cpevalorfirma();
            $this->docxpagar_cpe->cpe_servidor = $this->cpetiposerv;
            $this->docxpagar_cpe->save();

        }


    }

    private function extraer_cpehash(){

        return trim(string_between_two_string($this->xml_archivo, '<ds:DigestValue>', '</ds:DigestValue>'));
    }

    private function extraer_cpevalorfirma(){

        return trim(string_between_two_string($this->xml_archivo, '<ds:SignatureValue>', '</ds:SignatureValue>'));

    }

    public function obtener_cpe_id(){

        $id = $this->docxpagar_cpe->id;
        return $id;
    }

}
