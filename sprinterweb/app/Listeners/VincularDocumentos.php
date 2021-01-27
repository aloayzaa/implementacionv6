<?php

namespace App\Listeners;

use App\Events\VincularDocumentosEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\LinkInvoiceController;
use App\Http\Controllers\LinkLowController;

class VincularDocumentos
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    private $model_id;
    private $xml_nombre;
    private $ruc;

    private $model; // MODELO
    private $nombre_tabla;



    private $link; // VINCULO A EJECUTAR

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VincularDocumentosEvents  $event
     * @return void
     */
    public function handle(VincularDocumentosEvents $event)
    {
        $this->model_id = $event->model_id;
        $this->xml_nombre = $event->xml_nombre;
        $this->ruc = $event->ruc;

        $this->model = $event->model;
        $this->nombre_tabla = $event->nombre_tabla;

        $this->evaluar_origen();

        $this->link->store();
        
    }


    private function evaluar_origen(){

        switch(true){

            case strtolower($this->model->ventana) == 'mov_facturaventa':

                $this->link = new LinkInvoiceController($this->model_id, $this->xml_nombre, $this->ruc, $this->model, $this->nombre_tabla);

            break;

            case strtolower($this->model->ventana) == 'mov_doccomunicacion_baja':

                $this->link = new LinkLowController($this->model_id, $this->xml_nombre, $this->ruc, $this->model, $this->nombre_tabla);

            break;

            case strtolower($this->model->ventana) == 'mov_doccomunicacion_resumen':


            break;


        }

    }

}
