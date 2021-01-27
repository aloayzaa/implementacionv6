<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Procedures\DocumentToPayProcedure;
use Illuminate\Support\Facades\Session;

class SunatRespuestaErrorController extends Controller
{

    private $documenttopayprocedure;

    private $docxpagar; // DOCXPAGAR REGISTRO

    private $respuesta_sunat; // ARRAY DE RESPUESTA SUNAT



    public function __construct($respuesta_sunat, $docxpagar)
    {

        $this->documenttopayprocedure = new DocumentToPayProcedure;
        $this->docxpagar = $docxpagar;

        $this->respuesta_sunat = $respuesta_sunat;

                        
    }    


    public function mostrar_error(){

        if (strtolower($this->docxpagar->ventana) != 'mov_facturaventa'){ return null; }


        session()->put('respuesta_sunat', $this->respuesta_sunat[0]);
        

        if ($this->respuesta_sunat[0]['estado'] != 'error'){ return null; }       
        
        
        $this->anular_factura();


        if ($this->respuesta_sunat[0]['codigo'] != 0 ){

            $data['estado'] = $this->respuesta_sunat[0]['estado'];
            $data['codigo'] = $this->respuesta_sunat[0]['codigo'];
            $data['descripcion'] = $this->respuesta_sunat[0]['descripcion'];

            session()->put('respuesta_sunat', $data);

            return $data;

       }    


        return null;

    }


    private function anular_factura(){

        if ( substr($this->respuesta_sunat[0]['codigo'], 0, 1) == '2' && !in_array(trim($this->respuesta_sunat[0]['codigo']), array('2080','2017','2027','2145','2325')) || trim($this->respuesta_sunat[0]['codigo']) == '3235'){

            $this->documenttopayprocedure->setanulacpe($this->docxpagar->id); // ANULAR FACTURA

        }

    }

}
