<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Panel\Companies\Entities\Pempresa;
use DateTime;

class InvoicedCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subject; // ASUNTO
    public $documento; // INFORMACIÓN DEL DOCUMENTO A ENVIAR
    public $xml; // ARCHIVO XML
    public $ruc; // RUC DEL EMISOR
    public $nombre_xml; // NOMBRE DEL XML
    public $parrafo1; 

    public function __construct($documento)
    {
        $this->documento = $documento;
        $this->ruc = Pempresa::get_parametro('CPE_RUC')->valor;

        $this->segun_estado();

        $this->obtener_xml();
        
    }

    private function segun_estado(){

        $razon_social = Pempresa::get_parametro('CPE_ACTIVAR')->valor;

        if ($this->documento->estado != 'ANULADO' ){

            $this->subject = ucwords($this->documento->tipodoc_nombre) . ' Electrónica ' . substr($this->documento->seriedoc, -4) . '-' . trim($this->documento->numerodoc) . '-' . $razon_social;
            $this->parrafo1 = ', informamos que su comprobante electrónico ha sido emitido exitosamente. Para descargar o consultar los archivos puede ingresar a:';

        }else{

            $this->subject = 'COMUNICACION DE BAJA ' . substr($this->documento->seriedoc, -4) . '-' . trim($this->documento->numerodoc) . '-' . $razon_social;
            $this->parrafo1 = ', informamos que su comprobante electrónico ha sido dado de baja. Para descargar o consultar los archivos puede ingresar a:';

        }

    }


    private function obtener_xml(){

        $this->nombre_xml = $this->ruc . '-' . $this->documento->tipodoc . '-' . $this->documento->seriedoc . '-' . $this->documento->numerodoc . '.xml';

        $this->xml = obtener_xml_firma2($this->ruc, $this->nombre_xml);


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoicedCustomer');
    }
}
