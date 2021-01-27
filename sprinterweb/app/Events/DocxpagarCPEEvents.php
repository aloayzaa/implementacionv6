<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;

class DocxpagarCPEEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $docxpagar_id;
    public $cpetiposerv;
    public $xml_nombre;
    public $ruc;

    public $modelo; // MODEL ENVIO

    public function __construct($docxpagar_id, $cpetiposerv, $xml_nombre, $ruc, Model $modelo)
    {
        $this->docxpagar_id = $docxpagar_id;
        $this->cpetiposerv = $cpetiposerv;
        $this->xml_nombre = $xml_nombre;
        $this->ruc = $ruc;

        $this->modelo = $modelo;
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
