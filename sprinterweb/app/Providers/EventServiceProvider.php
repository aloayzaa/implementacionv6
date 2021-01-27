<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\Generarxmlfactura21Events' => [
            'App\Listeners\Generarxmlfactura21'
        ],
        'App\Events\GenerarXMLResumenBoletaEvents' => [
            'App\Listeners\GenerarXMLResumenBoleta'
        ],
        'App\Events\GenerarXMLNCredito21Events' => [
            'App\Listeners\GenerarXMLNCredito21'
        ],      
        'App\Events\GenerarXMLNDebito21Events' => [
            'App\Listeners\GenerarXMLNDebito21'
        ],         
        'App\Events\GenerarXMLComunicaBajaEvents' => [
            'App\Listeners\GenerarXMLComunicaBaja'
        ],              
        'App\Events\CrearXMLEvents' => [
            'App\Listeners\CrearXML'
        ],
        'App\Events\FirmarXMLEvents' => [
            'App\Listeners\FirmarXML'
        ],      
        'App\Events\ComprimirXMLEvents' => [
            'App\Listeners\ComprimirXML'
        ],
        'App\Events\DocxpagarCPEEvents' => [
            'App\Listeners\DocxpagarCPE'
        ],          
        'App\Events\EnviarSunatEvents' => [
            'App\Listeners\EnviarSunat'
        ],
        'App\Events\VincularDocumentosEvents' => [
            'App\Listeners\VincularDocumentos'
        ] 
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
