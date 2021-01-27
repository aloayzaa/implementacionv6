<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountCte\Entities\MovementCte;
use Gloudemans\Shoppingcart\Facades\Cart;

class MovctacteBillingController extends Controller
{

    private $movctacte = 'movctacte'; 
    private $docxpagar_id;
    
    public function __construct($docxpagar_id)
    {

        $this->docxpagar_id = $docxpagar_id;

    }

    public function llenar_carritos($docxpagar){

        $movementCte = MovementCte::selectidcmd($docxpagar->id);
        //dd($movementCte);
        if ($movementCte){


            foreach($movementCte as $m){

                Cart::instance($this->movctacte)->add(['id' => $m->id, 'name' => $m->documento, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    "fechaproceso" => $m->fechaproceso,
                    "documento" => $m->documento,
                    "fechadoc" => $m->fechadoc,
                    "vencimiento" => $m->vencimiento,
                    "glosa" => $m->glosa,
                    "ventana" => $m->ventana,
                    "saldomn" => $m->saldomn,
                    "saldome" => $m->saldome,
                    "origen_id" => $m->origen_id,
                    "tabla" => $m->tabla,
                    "ventanaorigen" => $m->ventanaorigen
                ]]);
    
            }


        }




    }


    public function obtener_data_edit($docxpagar, $referencia){

        $this->llenar_carritos($docxpagar);

        
        $saldomn = 0;
        $saldome = 0;
        $documento_aplicacion = '';
        $importe_aplicacion = 0;

        $movctacte = Cart::instance($this->movctacte)->content();

        if ($movctacte){

            foreach($movctacte as $m){

                $saldomn += $m->saldomn;
                $saldome += $m->saldome;

            }

        }

        if (empty($docxpagar->tiponotacredito_id) && empty($docxpagar->tiponotadebito_id)){

            if($referencia){

                $importe_aplicacion = $referencia->importe;
                $documento_aplicacion = $referencia->docrefer;

            }

        }

        $data['saldomn'] = $saldomn;
        $data['saldome'] = $saldome;
        $data['documento_aplicacion'] = $documento_aplicacion;
        $data['importe_aplicacion'] = $importe_aplicacion;


        return $data;
    }


    public function obtener_movctacte(){

        return Cart::instance($this->movctacte)->content();

    }


    public function eliminar_carritos(){

        Cart::instance($this->movctacte)->destroy();

    }

}
