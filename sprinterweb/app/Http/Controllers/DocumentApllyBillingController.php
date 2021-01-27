<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Procedures\ProvisionesPorPagarProcedure;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Currency\Entities\Currency;
use Illuminate\Support\Facades\Session;
use App\Reference\Entities\Reference;

class DocumentApllyBillingController extends Controller
{
    private $docxpagar_id; // ID DOCXPAGAR CREAR Ó EDITAR
    private $documentos_aplicar = 'movctacte_listado'; 
    private $documentos_aplicar_seleccionados = "documentos_aplicar_seleccionados";
    private $cart_referencia2 = 'cart_referencia2';

    public function __construct($docxpagar_id)
    {

        $this->docxpagar_id = $docxpagar_id;


    }

    public function listado_documentos_aplicar($request, $cart_referencia){

        Cart::instance($this->documentos_aplicar)->destroy();

        $provisionesPorPagarProcedure = new ProvisionesPorPagarProcedure;

        $documentos_aplicar = $provisionesPorPagarProcedure->getCtaCte($request->historial_aplicacion_tercero, 'C', $request->fecha_proceso, session()->get('id'), 0);

        $moneda = Currency::find($request->moneda);

        foreach($documentos_aplicar as $documento){

            $aplicar = 0;
            $saldome = $documento->saldome;
            $saldomn = $documento->saldomn;

            if($moneda->tipo == 'N'){

                $saldome = $documento->saldomn / $request->tcambio;
                $aplicar = $documento->saldomn;

            }else{

                $saldomn = $documento->saldome * $request->tcambio;
                $aplicar = $documento->saldome;

            }

            

            if($request->historial_aplicacion_tercero){

                $glosa = $documento->glosa;

            }else{

                $glosa = $documento->razonsocial;

            }

            
            $elegido = 0;

            $documento_seleccionado = collect(Cart::instance($this->cart_referencia2)->content())->firstWhere('id', $documento->id);

            if($documento_seleccionado){

                $elegido = 1;
                $aplicar = $documento_seleccionado->options->importe;
                //$this->llenar_documento_seleccionado($documento_seleccionado, $documento, $glosa);

            }



            Cart::instance($this->documentos_aplicar)->add(['id' => $documento->id, 'name' => $documento->documento, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "elegido" => $elegido,
                "aplicar" => $aplicar,
                "documento" => $documento->documento,
                "fecha" => $documento->fecha,
                "vencimiento" => $documento->vencimiento,
                "moneda" => $documento->moneda,
                "saldomn" => formatear_numero($saldomn, 2),
                "saldome" => formatear_numero($saldome, 2),
                "glosa" => $glosa
            ]]);



        }
        
        
    }


    public function documento_aplicar_seleccionado($request){
        
        $documento = $this->documento_accion($request);

        if($request->seleccionado == "true"){
            
            if($documento){

                $this->llenar_documento($documento);

            }
            

        }else{

            $this->quitar_documento($request->id);

        }

    }

    private function documento_accion($request){

        return collect(Cart::instance($this->documentos_aplicar)->content())->firstWhere('id', $request->id);

    }

    private function llenar_documento($documento){
        
        Cart::instance($this->documentos_aplicar_seleccionados)->add(['id' => $documento->id, 'name' => $documento->name, 'qty' => 1, 'price' => 0.00,
        'options' => [
            "aplicar" => $documento->options->aplicar,
            "documento" => $documento->options->documento,
            "fecha" => $documento->options->fecha,
            "vencimiento" => $documento->options->vencimiento,
            "moneda" => $documento->options->moneda,
            "saldomn" => formatear_numero($documento->options->saldomn, 2),
            "saldome" => formatear_numero($documento->options->saldome, 2),
            "glosa" => $documento->options->glosa
        ]]);
        
    }


    private function quitar_documento($id){
        
        $documento_eliminar = collect(Cart::instance($this->documentos_aplicar_seleccionados)->content())->firstWhere('id', $id);
        if($documento_eliminar){

            Cart::instance($this->documentos_aplicar_seleccionados)->remove($documento_eliminar->rowId);

        }        


    }

    private function llenar_documento_seleccionado($documento_seleccionado, $documento, $glosa){

        Cart::instance($this->documentos_aplicar_seleccionados)->add(['id' => $documento->id, 'name' => $documento->documento, 'qty' => 1, 'price' => 0.00,
        'options' => [
            "aplicar" => $documento_seleccionado->options->importe,
            "documento" => $documento->documento,
            "fecha" => $documento->fecha,
            "vencimiento" => $documento->vencimiento,
            "moneda" => $documento->moneda,
            "saldomn" => 0,
            "saldome" => 0,
            "glosa" => $glosa
        ]]);

    }

    public function obtener_total_aplicar($request){

        $aplicar = 0;
        $total_documentos = 0;

        foreach(Cart::instance($this->documentos_aplicar_seleccionados)->content() as $cart){
                      
            if(empty($request->total)){ // TOTAL DEL DOCXPAGAR ES EL LÍMITE
                
                $aplicar = $cart->options->aplicar;
                
            }else{
    
                if($total_documentos == 0){

                    if($request->total < $cart->options->aplicar){

                        $cart->options->aplicar = $request->total;

                    }
                }
                
                $aplicar = ($request->total - abs($total_documentos)) + $cart->options->aplicar;
                
                if($aplicar != 0){

                    if ($cart->options->aplicar < $aplicar){

                        $aplicar = $cart->options->aplicar;

                    }
                }
                
                $aplicar = $this->validar_limite($request, $aplicar, $total_documentos);
                
            } // EVALUAR EL LÍMITE

            $total_documentos += $aplicar;

        } // FIN FOREACH
        

        $aplicar_documento = (isset($request->id)) ? $this->obtener_aplicar_item($request->id) : 0;

        $data['total_documentos'] = $total_documentos;
        $data['aplicar_documento'] = $aplicar_documento;

        return $data;
    }

    private function validar_limite($request, $aplicar, $total_documentos){

        if($total_documentos + $aplicar > $request->total){ $this->saldo_cero($request); return 0;}
        
        return $aplicar;

    }

    public function cambiar_saldo_aplicar_documentos($request){
        
        $cart = collect(Cart::instance($this->documentos_aplicar_seleccionados)->content())->firstWhere('id', $request->id);

        $aplicar = $request->cantidad_input;

        if ( $cart->options->aplicar < $aplicar){

            $aplicar = $cart->options->aplicar;

        }

        $item = Cart::instance($this->documentos_aplicar_seleccionados)->get($cart->rowId);

        $options = $item->options->merge([
            'aplicar' => $aplicar,
        ]);

        Cart::instance($this->documentos_aplicar_seleccionados)->update($cart->rowId, [
            'options' => $options]);


    }

    private function saldo_cero($request){

        $cart = collect(Cart::instance($this->documentos_aplicar_seleccionados)->content())->firstWhere('id', $request->id);

        $item = Cart::instance($this->documentos_aplicar_seleccionados)->get($cart->rowId);

        $options = $item->options->merge([
            'aplicar' => 0,
        ]);

        Cart::instance($this->documentos_aplicar_seleccionados)->update($cart->rowId, [
            'options' => $options]);

    }


    public function eliminar_carritos(){

        Cart::instance($this->documentos_aplicar)->destroy();

        // CUANDO LA ACCIÓN VIENE DE AGREGAR NOTAS DE CRÉDITO O DÉBITO
        // NO DEBEN EXISTIR REFERENCIAS DE DOCUMENTOS A APLICAR, CUANDO EXISTE UNA NOTA DE CRÉDITO O DÉBITO 
        Cart::instance($this->documentos_aplicar_seleccionados)->destroy();
        Cart::instance($this->cart_referencia2)->destroy();
        
        
    }

    public function obtener_documentos_aplicar(){

        return Cart::instance($this->documentos_aplicar)->content();

    }

    public function obtener_documentos_aplicar_seleccionados(){

        return Cart::instance($this->documentos_aplicar_seleccionados)->content();

    }

    public function mostrar_documento_aplicar(){

        $data = array();

        $documento = collect(Cart::instance($this->documentos_aplicar_seleccionados)->content())->first();

        if ($documento){

            $data['documento'] = $documento->options->documento;
            $data['importe'] = $this->mostrar_importe_total();

        }

        return $data;

    }
    
    private function mostrar_importe_total(){

        $total = 0;

        foreach(Cart::instance($this->documentos_aplicar_seleccionados)->content() as $cart){
                      
            $total += $cart->options->aplicar;

        } // FIN FOREACH        

        return $total;

    }


    private function obtener_aplicar_item($id){

        $documento = collect(Cart::instance($this->documentos_aplicar_seleccionados)->content())->firstWhere('id', $id);
        if($documento){
            return $documento->options->aplicar;
        }else{
            return 0;
        }
        

    }


    public function agregar_documentos_aplicar(){

        $documentos_aplicar = $this->obtener_documentos_aplicar_seleccionados();

        foreach($documentos_aplicar as $documento){

            Cart::instance($this->cart_referencia2)->add(['id' => $documento->id, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => session()->get('id'), // SE ENVIA EN EL SAVE
                'referencia_id' => $documento->id,
                'docrefer' => $documento->options->documento,
                'fechadoc' => $documento->options->fecha,
                'importe' => ($documento->options->aplicar == '' || $documento->options->aplicar == null) ? 0 : $documento->options->aplicar,                
            ]]);

        }

    }

    public function grabar_carritos($docxpagar, $request){


        if($this->docxpagar_id != 0) { return null; } // NO SE REALIZA NINGUNA ACCIÓN EN EL EDITAR


        $cart_referencias = Cart::instance($this->cart_referencia2)->content();

        if ($cart_referencias){
            
            $aplicar = (empty($request->chk_historial_aplicar)) ? 0 : 1;

            foreach($cart_referencias as $cart){


                $referencia = new Reference;
                $referencia->parent_id = $docxpagar->id;
                $referencia->referencia_id = $cart->id;
                $referencia->aplicar = $aplicar;
                $referencia->importe = $cart->options->importe;
                $referencia->usuario = Session::get('usuario');
                $referencia->save();

            }


        }


    }

}
