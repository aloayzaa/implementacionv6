<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class DocumentsSendingCpeController extends Controller
{

    private $documentos = 'documentos_cpe';
    public $documentos_seleccionados;


    public function __construct()
    {

    }

    public function llenar_documentos($listado){


        foreach($listado as $l){

            Cart::instance($this->documentos)->add(['id' => $l->id, 'name' => trim($l->tipodoc) . '-' . trim($l->seriedoc) . '-' . trim($l->numerodoc), 'qty' => 1, 'price' => 0.00,
            'options' => [
                "flag" => $l->flag,
                "fecha" => $l->fecha,
                "tipo" => $l->tipo,
                "tipodoc" => $l->tipodoc,
                "seriedoc" => $l->seriedoc,
                "numerodoc" => $l->numerodoc,
                "codigo" => $l->codigo,
                "nombre" => $l->nombre,
                "glosa" => $l->glosa,
                "moneda" => $l->moneda,
                "base" => $l->base,
                "inafecto" => $l->inafecto,
                "impuesto" => $l->impuesto,
                "total" => $l->total,
                "txt" => $l->txt,
                "fecha_genera" => $l->fecha_genera,
                "fecha_firma" => $l->fecha_firma,
                "fecha_envio" => $l->fecha_envio,
                "fecha_recep" => $l->fecha_recep,
                "respuesta_cod" => $l->respuesta_cod,
                "respuesta_dsc" => $l->respuesta_dsc,
                "respuesta_otros" => $l->respuesta_otros,
                "email" => $l->email,
                "email_fecha" => $l->email_fecha,
                "email_destino" => $l->email_destino,
                "ftp" => $l->ftp,
                "ftp_fecha" => $l->ftp_fecha,
                "cpe_servidor" => $l->cpe_servidor,
                "estado" => $l->estado,
                "id" => $l->id,
                "ventana" => $l->ventana,
                "fechaproceso" => $l->fechaproceso,
                "horaproceso" => $l->horaproceso             
            ]]);        


        }


    }

    public function eliminar_documentos(){

        Cart::instance($this->documentos)->destroy();

    }


    public function obtener_documentos(){

        return Cart::instance($this->documentos)->content();

    }


    public function llenar_seleccionados($data){

        // ID ITEMS SELECCIONADOS
        $detalle_id = array_column($data, 'ids'); 


            
        // REGISTROS SELECCIONADOS
        $this->documentos_seleccionados = collect($this->obtener_documentos())->whereIn('id', $detalle_id);

        

    }


    public function validar_s(){

        
        foreach($this->documentos_seleccionados as $documento){

            if($documento->options->respuesta_dsc != "" || in_array($documento->options->respuesta_cod, array('2017','2027','2145', "")) == false){

                $this->documentos_seleccionados = $this->documentos_seleccionados->whereNotIn('id', $documento->id);

            }
            

        }


    }

    public function valida_c(){

        foreach($this->documentos_seleccionados as $documento){

            // NO SE PUEDE RECUPERAR CDR SI NO SE HA ENVIADO
            if($documento->options->fecha_envio == null){

                $this->documentos_seleccionados = $this->documentos_seleccionados->whereNotIn('id', $documento->id);

            }
            

        }


    }

    public function valida_e(){

        foreach($this->documentos_seleccionados as $documento){

            // SOLO SE ENVIAN LOS DOCUMENTOS ACEPTADOS POR SUNAT
            if($documento->options->respuesta_cod !== "0"){

                $this->documentos_seleccionados = $this->documentos_seleccionados->whereNotIn('id', $documento->id);

            }
            

        }

    }

}
