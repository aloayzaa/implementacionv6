<?php

namespace App\SunatFacturacion;

use App\SunatFacturacion\BaseResult;
use App\SunatFacturacion\SummaryResult;
use App\Panel\Companies\Entities\Pempresa;
use SoapClient;
use App\WSSESecurityHeader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\SunatFacturacion\ExtService;
use App\Http\Controllers\LowCommunicationProcessesController;
use App\SunatZip\ZipFileDecompress;
/**
 * Class SummarySender.
 */
class SummarySender extends BaseSunat
{
    /**
     * @param string $filename
     * @param string $content
     *
     * @return BaseResult
     */

    private $xml_nombre;
    private $xml_nombre_sin_extension;
    private $archivo_comprimido;
    private $endpoint;
    private $sol_usuario;
    private $sol_clave;
    private $ruc;

    private $cdr; // CDR RESPUESTA
    private $respuesta; // RESPUESTA SUNAT
    private $ticket; // TICKET RESPUESTA

    private $id; //ID DEL DOCUMENTO PRINCIPAL

    private $tipodoc; //PARA DIFERENCIAR RESUMEN DE BOLETAS DE COMUNICACION DE BAJA

    private $respuesta_estado;

    public function __construct($xml_nombre, $archivo_comprimido, $endpoint, $ruc, $id, $tipodoc)
    {

        $this->xml_nombre = $xml_nombre;
        $this->xml_nombre_sin_extension = basename($this->xml_nombre, ".xml");
        $this->archivo_comprimido = $archivo_comprimido;
        $this->endpoint = $endpoint;
        $this->sol_usuario = Pempresa::get_parametro('SOL_USUARIO')->valor;
        $this->sol_clave = Pempresa::get_parametro('SOL_CLAVE')->valor;
        $this->ruc = $ruc;
        $this->id = $id;
        $this->tipodoc = $tipodoc;

    }

    public function pre_envio(){



    }

    public function send()
    {

        $this->respuesta = new SummaryResult();

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
                'fileName' => $this->xml_nombre_sin_extension . '.zip',
                'contentFile' => $this->archivo_comprimido,
            ];

            // "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService"
            $cliente = new SoapClient($this->endpoint, $options);
            $cliente->__setSoapHeaders(new WSSESecurityHeader($this->ruc . $this->sol_usuario, $this->sol_clave)); // Define los encabezados SOAP
            $respuesta = $cliente->__call('sendSummary', ['parameters' => $parametros]);

            $this->respuesta
            ->setTicket($respuesta->ticket)
            ->setSuccess(true);
            //dd($this->respuesta);

        } catch (\SoapFault $e) {

            //dd($this->respuesta->setError($this->getErrorFromFault($e)));
            $this->respuesta->setError($this->getErrorFromFault($e));

        }


       return $this->evaluar_respuesta();

    }


    private function evaluar_respuesta(){

        if (!$this->respuesta->isSuccess()) {

            $data['estado'] = 'error';
            $data['descripcion'] = $this->respuesta->getError();

            return $data;

        }

        $this->obtener_ticket();

        $this->enviar_ticket();

        if (!$this->respuesta->isSuccess()) {

            $error = $this->respuesta->getError();;

            $data['estado'] = 'error';
            $data['codigo'] = $error->getCode();
            $data['descripcion'] = $error->getMessage();

            return $data;
        }

        return null;


    }

    private function obtener_ticket(){

        $this->ticket = $this->respuesta->getTicket();

    }


    private function enviar_ticket(){

        $extService = new ExtService($this->ticket, $this->endpoint, $this->ruc, $this->sol_usuario, $this->sol_clave);
        $this->respuesta = $extService->getStatus();

    }


    public function post_envio(){

        $this->recepcion_repuesta();

        $proceso = new LowCommunicationProcessesController($this->id, $this->respuesta->isSuccess(), $this->ticket, $this->cdr->getCode(), $this->cdr->getDescription());

        if($this->tipodoc == 'RC'){
            //ejeecutar envio de boletas

        }
        else{
            $this->respuesta_estado = $proceso->processes();
        }


    }



    private function recepcion_repuesta(){

        //OBTENEMOS EL CDR
        $this->cdr = $this->respuesta->getCdrResponse();

        // GUARDAMOS EL ZIP DE RESPUESTA
        $zip = $this->respuesta->getCdrZip();
        $this->guardar_respuesta_xml($zip);

    }


    private function guardar_respuesta_xml($zip){

        $decompress = new ZipFileDecompress;
        $content = $decompress->decompress($zip);
        $xml = obtener_contenido($content);

        // STORAGE DIGITAL OCEAN
        guardar_xml_sunat($this->ruc, $this->xml_nombre, $xml);

        // CUANDO SE GUARDABA EN LOCAL
        // File::put(public_path('/SUNATXML/' . $this->ruc . '/' . str_replace('.zip', '', $filename) .'.xml'), $content[1]['content']);

    }


    public function retornar(){

        $data['estado'] = 'Ok';
        $data['codigo'] = $this->cdr->getCode();
        $data['descripcion'] = $this->cdr->getDescription();

        return $data;

    }

}
