<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use App\WSSESecurityHeader;
use App\Panel\Companies\Entities\Pempresa;
use App\SunatZip\ZipFileDecompress;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CPEGetStatusCDRController extends Controller
{
    private $lnTipoServ;
    private $lcRuc;
    private $lcuser;
    private $lcpwd;
    private $lcurl;

    private $ptipodoc;
    private $pseriedoc;
    private $pnumerodoc;

    private $respuesta;
    private $respuesta_codigo;
    private $respuesta_mensaje = null; // VER SENDINGCPECONTROLLER - MÉTODO error_sunat() (EJEMPLO DEL CLASIC)
    private $respuesta_contenido = null;

    private $id_cdr = "";
    private $code_cdr = "";
    private $description_cdr = "";
    private $hash_cdr = "";  
    private $fecha_cdr = null; // TABLA: DOCXPAGAR_CPE, CAMPO: FECHA RECEPCIÓN 

    public function __construct($ptipodoc, $pseriedoc, $pnumerodoc)
    {
        $this->ptipodoc = $ptipodoc;
        $this->pseriedoc = $pseriedoc;
        $this->pnumerodoc = $pnumerodoc;
        $this->lnTipoServ = intval(Pempresa::get_parametro('CPE_SERVIDOR')->valor);
        $this->lcRuc = trim(Pempresa::get_parametro('CPE_RUC')->valor); // FACTURA ELECTRÓNICA. RUC EMISOR
        $this->lcuser = trim(Pempresa::get_parametro('SOL_USUARIO')->valor);
        $this->lcpwd = trim(Pempresa::get_parametro('SOL_CLAVE')->valor);

    }

    public function status(){

        $this->lcurl = "https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl";


        if($this->lnTipoServ == 1){ //OSE

            $this->lcurl = "https://ose.bizlinks.com.pe/ol-ti-itcpe/billService?wsdl";
        }

    }

    public function consult(){

        try {
            $options = [ // EVALUAR TODAS ESTAS OPCIONES - SOLO EL TRACE ESTA COMPROBADO
                'cache_wsdl'     => WSDL_CACHE_NONE,
                'trace'          => 1,
                'stream_context' => stream_context_create(
                    [
                        'ssl' => [
                            'verify_peer'       => false,
                            'verify_peer_name'  => false,
                            'allow_self_signed' => true
                        ]
                    ]
                )
            ];    

            $parametros = [ 
                'rucComprobante' => $this->lcRuc,
                'tipoComprobante' => $this->ptipodoc,
                'serieComprobante' => $this->pseriedoc,
                'numeroComprobante' => $this->pnumerodoc,
            ];
            

            $cliente = new SoapClient($this->lcurl, $options); 
            //$cliente->__setSoapHeaders(new WSSESecurityHeader($this->lcRuc . $this->lcuser, $this->lcpwd)); // Define los encabezados SOAP
            $cliente->__setSoapHeaders(new WSSESecurityHeader($this->lcuser, $this->lcpwd));
            $respuesta = $cliente->__call('getStatusCdr', ['parameters' => $parametros]);
            $this->respuesta = $respuesta->statusCdr;


        } catch (\SoapFault $e) {

            // VER EL TIPO DE ERROR QUE REGRESA

            //dd($this->respuesta->setError($this->getErrorFromFault($e)));
            //$this->respuesta->setError($this->getErrorFromFault($e));

        }


        //return $this->evaluar_respuesta();

    }



    private function evaluar_respuesta(){

        /*if (!$this->respuesta->isSuccess()) {

            $error = $this->respuesta->getError();;

            $data['estado'] = 'error';
            $data['codigo'] = $error->getCode();
            $data['descripcion'] = $error->getMessage();

            return $data;

        }

        return null;*/

    }


    public function procesar_respuesta(){

        if(empty($this->respuesta)){

            $this->sin_respuesta();

        }else{

            $this->con_respuesta();

        }
       
        $this->procesar_content();

    }


    private function sin_respuesta(){

        $this->respuesta_codigo = null;
        $this->respuesta_mensaje = null;
        $this->respuesta_contenido = null;
    }

    private function con_respuesta(){
        
        if($this->lnTipoServ == 1){ //OSE

            $this->respuesta_codigo = null;
            $this->respuesta_mensaje = null;
            $this->respuesta_contenido = null;


        }else{ 
            
            $this->respuesta_codigo = $this->respuesta->statusCode;
            $this->respuesta_mensaje = $this->respuesta->statusMessage; 

            // EN ALGUNOS CASOS NO REGRESA CONTENIDO POR EJEMPLO:
            // CUANDO EL TICKET NO EXISTE
            $this->respuesta_contenido = isset($this->respuesta->content) ? $this->respuesta->content : '';
            

        }

    }

    private function procesar_content(){
        
        if( !empty(trim($this->respuesta_contenido)) ){
            
            $this->guardar_xml_respuestacdr();


            
        }


    }


    private function guardar_xml_respuestacdr(){

        $decompressor  = new ZipFileDecompress();
        $archivo = $decompressor->decompress($this->respuesta_contenido);

        $xml = "";

        if (count($archivo) > 0){ // CUANDO HAY RESPUESTA
            

            $xml = obtener_contenido($archivo);


        }    
        

        if ($xml != ''){

            $nombre = $this->lcRuc . '-' . $this->ptipodoc . '-' . $this->pseriedoc . '-' . $this->pnumerodoc . '.xml';

            guardar_xml_sunat($this->lcRuc, $nombre, $xml);


            $this->id_cdr = trim(string_between_two_string($xml, '<cbc:ReferenceID>', '</cbc:ReferenceID>'));
            $this->code_cdr = trim(string_between_two_string($xml, '<cbc:ResponseCode>', '</cbc:ResponseCode>'));
            $this->description_cdr = trim(string_between_two_string($xml, '<cbc:Description>', '</cbc:Description>'));
            $this->fecha_cdr = trim(string_between_two_string($xml, '<cbc:ResponseDate>', '</cbc:ResponseDate>')) . ' ' . trim(string_between_two_string($xml, '<cbc:ResponseTime>', '</cbc:ResponseTime>'));

            // VER SENDINGCPECONTROLLER - MÉTODO error_sunat() (EJEMPLO DEL CLASIC)
            $this->respuesta_mensaje = $this->description_cdr; 
    
            if($this->lnTipoServ == 1){ //OSE
                
    
                if ( ($this->code_cdr == '500' && strpos(strtolower($this->description_cdr), '(Error en Base de Datos)') !== false) || in_array($this->code_cdr, array('0127', '0402')) ){
    
                    $this->id_cdr = "";
                    $this->code_cdr = "";
                    $this->description_cdr = "";
                    $this->hash_cdr = "";
                    $this->fecha_cdr = null;
                    
                }else{
    
                    $this->hash_cdr = trim(string_between_two_string($xml, '<cbc:DocumentHash>', '</cbc:DocumentHash>'));       
    
                }
         
    
            }else{ 
                
                $this->hash_cdr = trim(string_between_two_string($xml, '<DigestValue>', '</DigestValue>'));            
    
            }


            $this->actualizar_docxpagar_cpe();

        } //  FIN SI EXISTE XML



    }

    private function actualizar_docxpagar_cpe(){

        $sql = "update docxpagar_cpe e join docxpagar c on e.docxpagar_id = c.id join documentocom dc on c.documento_id = dc.id set ";

        if(!empty($this->fecha_cdr)){ $sql .= "e.fecha_recep = '" . trim($this->fecha_cdr) . "', ";}
        
        $sql .= "e.respuesta_cod = '" . trim($this->code_cdr) . "', ";

        $sql .= "e.respuesta_dsc = '" . trim($this->description_cdr) . "' ";

        if(!empty($this->hash_cdr)){ $sql .= ", e.respuesta_hash = '" . trim($this->hash_cdr) . "' ";}

        $sql .= "where dc.codsunat='" . $this->ptipodoc . "' and c.seriedoc='" . $this->pseriedoc . "' and c.numerodoc='" . str_pad($this->pnumerodoc, 8, '0', STR_PAD_LEFT) . "' and c.origen = 'C'";

        DB::update($sql);

        // FALTA UNA CONSULTA, CLASIC: CLASE: _BASE, MÉTODO: SETQUERYCMD - ESTE EJECUTA TRIGGERS
        // VER CLASE: ENTORNO, MÉTODO: CPEGETSTATUSCDR (ÚLTIMAS LÍNEAS)



    }

    
    public function obtener_code_cdr(){

        return $this->code_cdr;

    }


    public function obtener_respuesta_mensaje(){

        // VER SENDINGCPECONTROLLER - MÉTODO error_sunat() (EJEMPLO DEL CLASIC)
        return $this->respuesta_mensaje;

    }

}
