<?php

namespace App\Listeners;

use App\Events\ComprimirXMLEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\SunatZip\ZipFly;
use Illuminate\Support\Facades\Session;

class ComprimirXML
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    private $ruc;
    private $objeto_zip; // OBJETO DE LA CLASE ZIP
    private $xml_nombre;
    private $xml_archivo;
    private $xml_comprimido;

    public function __construct()
    {
        $this->objeto_zip = new ZipFly;
    }

    /**
     * Handle the event.
     *
     * @param  ComprimirXMLEvents  $event
     * @return void
     */
    public function handle(ComprimirXMLEvents $event)
    {
        $this->ruc = $event->ruc;
        $this->xml_nombre = $event->xml_nombre;

        $this->archivo();

        $this->comprimir();

        return $this->xml_comprimido;

    }


    private function archivo(){

        // CUANDO SE GUARDABA EN LOCAL 
        //$this->xml_archivo = file_get_contents(public_path() . '/FIRMAXML/' . $this->ruc . '/' . $this->xml_nombre);

        // STORAGE DIGITAL OCEAN     
        $this->xml_archivo = obtener_xml_firma2($this->ruc, $this->xml_nombre);
        
    }

    private function comprimir(){

        $this->xml_comprimido = $this->objeto_zip->compress($this->xml_nombre, $this->xml_archivo);
        

    }
}
