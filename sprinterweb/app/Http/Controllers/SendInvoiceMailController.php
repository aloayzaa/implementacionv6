<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Panel\Companies\Entities\Pempresa;
use App\Customers\Entities\Customer;
use App\Mail\InvoicedCustomer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendInvoiceMailController extends Controller
{
    private $documento_id;
    private $ventana;

    public function __construct($documento_id, $ventana)
    {
        $this->documento_id = $documento_id;
        $this->ventana = $ventana;
        

    }


    public function enviar(){
        
        $email = '';


        $documento = $this->obtener_reportecmd();
        // EN CASO NO EXISTA INFORMACIÓN DEL DOCUMENTO
        if(empty($documento)) { return null; }


        $tercero = Customer::find($documento->tercero_id);
        if ($tercero){

            $filtered = collect($tercero->terceroContacto)->filter(function ($value, $key) {
                return $value->cpe == 1 && !empty($value->email);
            });

            $tercero = $filtered->first();

            $email = $tercero->email; 

        } 
        

        // EN CASO NO EXISTA EMAIL DE RECEPCIÓN
        if(empty($email)){ return null; }

        $this->actualizar_docxpagar_cpe($email);

        //return Mail::to($email)->send(new InvoicedCustomer($documento));
        Mail::to('miguel.c9.delacruz@gmail.com')->send(new InvoicedCustomer($documento));

    }

    private function obtener_reportecmd(){

        $model = new DocumentToPay;
        $model->ventana = $this->ventana;
        $model->id = $this->documento_id;

        $reporteCmdController = new ReporteCmdController($model, $this->documento_id, null);
        $reportecmd = $reporteCmdController->reportecmd();
        $documento = collect($reportecmd)->first();

        return $documento;

    }

    private function actualizar_docxpagar_cpe($email){

        $sql = "UPDATE docxpagar_cpe SET email_fecha = '" . Carbon::now() . "',  email_destino = '" . $email . "' WHERE docxpagar_id = " . $this->documento_id;

        DB::update($sql);

    }

}
