<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EnviarSunatEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $ruc;
    public $cpetiposerv; // TIPO SERVIDOR

    public $tipodoc; // TIPO DOCUMENTO
    public $xml_nombre;
    public $archivo_comprimido;

    public $docxpagar_cpe_id; // DOCXPAGAR_CPE - ID (ID DEL REGISTRO ACTUALIZAR O CREAR)

    public $id; //ID DEL DOCUMENTO PRINCIPAL


    public function __construct($archivo_comprimido, $cpetiposerv, $tipodoc, $xml_nombre, $ruc, $docxpagar_cpe_id, $id)
    {
        $this->ruc = $ruc;
        $this->archivo_comprimido = $archivo_comprimido;
        $this->cpetiposerv = $cpetiposerv;
        $this->tipodoc = $tipodoc;
        $this->xml_nombre = $xml_nombre;
        $this->docxpagar_cpe_id = $docxpagar_cpe_id;
        $this->id = $id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
