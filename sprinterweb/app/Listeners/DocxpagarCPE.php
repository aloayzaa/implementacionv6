<?php

namespace App\Listeners;

use App\Events\DocxpagarCPEEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\DocxpagarCpeController;
use App\Http\Controllers\WarehouseIncomeCpeController;

class DocxpagarCPE
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $docxpagar_id; // ID DOCXPGAR 
    private $cpetiposerv; // TIPO SERVIDOR

    private $xml_nombre;
    private $ruc;

    private $modelo; // MODEL ENVIO
    private $tablaCpe; // TABLA QUE SE ACTUALIZA EN EL ENVIO

    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  DocxpagarCPEEvents  $event
     * @return void
     */
    
    public function handle(DocxpagarCPEEvents $event)
    {
        // SOLO SE EJECUTA CUANDO LA VENTANA ES : mov_facturaventa, mov_guiaremision
        if ( strtolower($event->modelo->ventana) != 'mov_facturaventa' && strtolower($event->modelo->ventana) != 'mov_guiaremision') { return null; }  

        $this->modelo = $event->modelo;


        $this->docxpagar_id = $event->docxpagar_id;
        $this->cpetiposerv = $event->cpetiposerv;

        $this->xml_nombre = $event->xml_nombre;
        $this->ruc = $event->ruc;


        $this->segun_ventana();


        $this->tablaCpe->existencia_cpe();
        $this->tablaCpe->obtener_archivo();
        $this->tablaCpe->actualizar_o_crear_cpe();


        return $this->tablaCpe->obtener_cpe_id();


    }



    private function segun_ventana(){

        switch(strtolower($this->modelo->ventana)){

            case 'mov_facturaventa' :

                $this->tablaCpe = new DocxpagarCpeController($this->docxpagar_id, $this->ruc, $this->xml_nombre, $this->cpetiposerv, $this->modelo);

            break;

            default:

                $this->tablaCpe = new WarehouseIncomeCpeController($this->docxpagar_id, $this->ruc, $this->xml_nombre, $this->cpetiposerv);

            break;

        }

    }

}
