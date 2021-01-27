<?php

namespace App\SunatFacturacion;

use App\SunatFacturacion\Error;
use App\SunatFacturacion\StatusResult;
use SoapClient;
use App\WSSESecurityHeader;
/**
 * Class ExtService.
 */
class ExtService extends BaseSunat
{
    /**
     * @param string $ticket
     *
     * @return StatusResult
     */

    private $ticket;
    private $endpoint;
    private $ruc;
    private $sol_usuario;
    private $sol_clave;


    public function __construct($ticket, $endpoint, $ruc, $sol_usuario, $sol_clave)
    {

        $this->ticket = $ticket;
        $this->endpoint = $endpoint;
        $this->ruc = $ruc;
        $this->sol_usuario = $sol_usuario;
        $this->sol_clave = $sol_clave;


    }
     
    public function getStatus()
    {
        try {
            return $this->getStatusInternal();
        } catch (\SoapFault $e) {
            $result = new StatusResult();
            $result->setError($this->getErrorFromFault($e));

            return $result;
        }
    }

    private function getStatusInternal()
    {

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

        $params = [
            'ticket' => $this->ticket,
        ];

        $cliente = new SoapClient($this->endpoint, $options); 
        $cliente->__setSoapHeaders(new WSSESecurityHeader($this->ruc . $this->sol_usuario, $this->sol_clave)); // Define los encabezados SOAP
        $response = $cliente->__call("getStatus", ['parameters' => $params]);   

        $status = $response->status;
        $code = $status->statusCode;

        $result = new StatusResult();
        $result->setCode($code);

        if ($this->isPending($code)) {
            $result->setError($this->getCustomError($code));

            //dd($result);
            return $result;
        }

        if ($this->isProcessed($code)) {
            $cdrZip = $status->content;
            $result
                ->setSuccess(true)
                ->setCdrResponse($this->extractResponse($cdrZip))
                ->setCdrZip($cdrZip);

            //dd($result);
            $code = $result->getCdrResponse()->getCode();
        }

        if ($this->isExceptionCode($code)) {

            //dd($this->loadErrorByCode($result, $code));
            $this->loadErrorByCode($result, $code);
        }

        return $result;
    }

    /**
     * @param string $code
     *
     * @return Error
     */
    private function getCustomError($code)
    {
        $error = new Error($code, 'El procesamiento del comprobante aún no ha terminado');

        return $error;
    }

    private function isProcessed($code)
    {
        return '0' == $code || '99' == $code;
    }

    private function isPending($code)
    {
        return '98' == $code;
    }
}
