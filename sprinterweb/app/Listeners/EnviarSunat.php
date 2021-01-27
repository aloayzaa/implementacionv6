<?php

namespace App\Listeners;

use App\Events\EnviarSunatEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\TipoServidorController;
use App\SunatFacturacion\SummarySender;
use App\SunatFacturacion\BillSender;

class EnviarSunat
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    private $archivo_comprimido;

    private $cpetiposerv; // TIPO SERVIDOR
    private $tipodoc; // TIPO DOCUMENTO

    private $tipoServidorController; // OBJETO DEL CONTROLADOR
    private $xml_nombre;
    private $endpoint; // URL ENVIO
    private $ruc;


    private $tipo_envio; // CLASE DE ENVIO BILL / SUMMARY

    private $docxpagar_cpe_id;

    private $id; //ID DEL DOCUMENTO PRINCIPAL

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EnviarSunatEvents  $event
     * @return void
     */
    public function handle(EnviarSunatEvents $event)
    {
        $this->ruc = $event->ruc;
        $this->archivo_comprimido = $event->archivo_comprimido[0];
        $this->cpetiposerv = $event->cpetiposerv;
        $this->tipodoc = $event->tipodoc;
        $this->xml_nombre = $event->xml_nombre;
        $this->docxpagar_cpe_id = $event->docxpagar_cpe_id;
        $this->id = $event->id;


        $this->obtener_tipo_servidor();

        $this->tipodoc_envio();

        $this->tipo_envio->pre_envio();

        if ($data = $this->tipo_envio->send()){return $data;}

        $this->tipo_envio->post_envio();

        return $this->tipo_envio->retornar();


    }


    private function obtener_tipo_servidor(){

        $this->tipoServidorController = new TipoServidorController($this->cpetiposerv, $this->tipodoc);
        $this->tipoServidorController->tipo_servidor();
        $this->endpoint = $this->tipoServidorController->obtener_tipo_servidor();


    }


    private function tipodoc_envio(){

        switch(true){

            case in_array($this->tipodoc, array('RA', 'RC', 'RR', 'RP')) :

                $this->tipo_envio = new SummarySender($this->xml_nombre, $this->archivo_comprimido, $this->endpoint, $this->ruc, $this->id, $this->tipodoc);

            break;

            default:

                $this->tipo_envio = new BillSender($this->xml_nombre, $this->archivo_comprimido, $this->endpoint, $this->ruc, $this->docxpagar_cpe_id);

            break;

        }

    }


}
