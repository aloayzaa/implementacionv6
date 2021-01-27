<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VincularDocumentosEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $model_id;
    public $xml_nombre;
    public $ruc;

    public $model; // MODELO
    public $nombre_tabla;

    public function __construct($model_id, $xml_nombre, $ruc, $model, $nombre_tabla)
    {
        $this->model_id = $model_id;

        $this->xml_nombre = $xml_nombre;
        $this->ruc = $ruc;

        $this->model = $model;
        $this->nombre_tabla = $nombre_tabla;


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
