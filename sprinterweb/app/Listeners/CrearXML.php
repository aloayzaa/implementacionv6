<?php

namespace App\Listeners;

use App\Events\CrearXMLEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class CrearXML
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CrearXMLEvents  $event
     * @return void
     */
    public function handle(CrearXMLEvents $event)
    {
        $ruc = $event->ruc;
        $xml_nombre = $event->xml_nombre;
        $xml = $event->xml;
        
        // CUANDO SE GUARDABA EN LOCAL
        //VERIFICAR SI EXISTE LA CARPETA CON EL Nº DE RUC, EN CASO NO CREARLO
       /* if ( file_exists(public_path() . '/FIRMAXML/' . $ruc) == false ) {
            mkdir(public_path() . '/FIRMAXML/' . $ruc, 0777, true); // CREAR CARPETA CON EL Nº DE RUC
        }*/

        //File::put(public_path('/FIRMAXML/' . $ruc . '/' . $xml_nombre), $xml);

        // STORAGE DIGITAL OCEAN
        guardar_xml_firma2($ruc, $xml_nombre, $xml[0]);

    }
}
