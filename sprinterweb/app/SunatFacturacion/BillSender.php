<?php

namespace App\SunatFacturacion;

use App\SunatFacturacion\BillResult;
use App\Panel\Companies\Entities\Pempresa;
use SoapClient;
use App\WSSESecurityHeader;
use App\DocumentToPay\Entities\DocumentToPayCPE;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\SunatZip\ZipFileDecompress;

class BillSender extends BaseSunat
{
    /**
     * @param string $filename
     * @param string $content
     *
     * @return BillResult
     */

    private $xml_nombre;
    private $xml_nombre_sin_extension;
    private $archivo_comprimido;
    private $endpoint;
    private $sol_usuario;
    private $sol_clave;
    private $ruc;

    private $docxpagar_cpe; // DOCXPAGAR_CPE (REGISTRO)

    private $cdr; // CDR RESPUESTA
    private $respuesta; // RESPUESTA SUNAT

    public function __construct($xml_nombre, $archivo_comprimido, $endpoint, $ruc, $docxpagar_cpe_id)
    {

        $this->xml_nombre = $xml_nombre;
        $this->xml_nombre_sin_extension = basename($this->xml_nombre, ".xml");
        $this->archivo_comprimido = $archivo_comprimido;
        $this->endpoint = $endpoint;
        $this->sol_usuario = Pempresa::get_parametro('SOL_USUARIO')->valor;
        $this->sol_clave = Pempresa::get_parametro('SOL_CLAVE')->valor;
        $this->ruc = $ruc;

        $this->docxpagar_cpe = DocumentToPayCPE::findOrFail($docxpagar_cpe_id);


    }


    public function pre_envio(){

        $this->actualizar_fechaenvio_docxpagarcpe();

    }


    private function actualizar_fechaenvio_docxpagarcpe(){

        // FALTA CUANDO VIENE DE GUIAS DE REMISION

        $this->docxpagar_cpe->fecha_envio = Carbon::now();
        $this->docxpagar_cpe->save();

    }


    public function send()
    {

        $this->respuesta = new BillResult();

        try {

            $options = [ // EVALUAR TODAS ESTAS OPCIONES - SOLO EL TRACE ESTA COMPROBADO
                'cache_wsdl'     => WSDL_CACHE_NONE,
                'trace'          => 1,
                'stream_context' => stream_context_create(
                    [
                        'ssl' => [
                            'verify_peer'       => false,
                            'verify_peer_name'  => false,
                            'allow_self_signed' => true,
                        ]
                    ]
                )
            ];

            $parametros = [
                'fileName' => $this->xml_nombre_sin_extension . '.zip',
                'contentFile' => $this->archivo_comprimido,
            ];

            $cliente = new SoapClient($this->endpoint, $options);
            $cliente->__setSoapHeaders(new WSSESecurityHeader($this->ruc . $this->sol_usuario, $this->sol_clave)); // Define los encabezados SOAP
            $respuesta = $cliente->__call("sendBill", ['parameters' => $parametros]);
            $cdrZip = $respuesta->applicationResponse;

            $this->respuesta
            ->setCdrResponse($this->extractResponse($cdrZip))
            ->setCdrZip($cdrZip)
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

            $error = $this->respuesta->getError();;

            $data['estado'] = 'error';
            $data['codigo'] = $error->getCode();
            $data['descripcion'] = $error->getMessage();

            return $data;

        }

        return null;

    }


    public function post_envio(){

        $this->recepcion_repuesta();

    }


    private function recepcion_repuesta(){

        // GUARDAR RESPUESTA EN DOCXPAGAR_CPE
        $this->cdr = $this->respuesta->getCdrResponse(); // CLASE BillResult
        $this->actualizar_docxpagarcpe_recepcion();

        // GUARDAR ZIP RESPUESTA SUNAT
        $zip = $this->respuesta->getCdrZip(); // CLASE BillResult
        $this->guardar_respuesta_xml($zip);

    }

    private function actualizar_docxpagarcpe_recepcion(){

        // FALTA CUANDO VIENE DE GUIAS DE REMISION

        $this->docxpagar_cpe->fecha_recep = Carbon::now();
        $this->docxpagar_cpe->respuesta_cod = $this->cdr->getCode();
        $this->docxpagar_cpe->respuesta_dsc = $this->cdr->getDescription();

        // PORQUE SE SUBE AL STORAGE DIGIAL OCEAN
        $this->docxpagar_cpe->ftp_fecha = Carbon::now();

        $this->docxpagar_cpe->save();

    }

    private function guardar_respuesta_xml($zip){
     
        $decompress = new ZipFileDecompress;
        $content = $decompress->decompress($zip);
        $xml = obtener_contenido($content);

        // STORAGE DIGITAL OCEAN
        guardar_xml_sunat($this->ruc, $this->xml_nombre, $xml);

        // CUANDO SE GUARDABA EN LOCAL
        // File::put(public_path('/SUNATXML/' . $this->ruc . '/' . $filename.'.xml'), $content[1]['content']);

    }


    public function retornar(){

        $data['estado'] = 'ok';
        $data['codigo'] = $this->cdr->getCode();
        $data['descripcion'] = $this->cdr->getDescription();

        return $data;

    }

}
