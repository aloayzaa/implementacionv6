<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentToPay\Entities\DocumentToPay;
use App\PaymentMethod\Entities\PaymentMethod;
use App\AccountCte\Entities\MovementCte;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Bank\Entities\Bank;
use App\DocumentToPay\Entities\DocumentToPayPaymentWay;
use App\Currency\Entities\Currency;
use Illuminate\Support\Facades\Session;

class PaymentMethodBillingController extends Controller
{
    private $docxpagar_id; // ID DOCXPAGAR
    private $docxpagar; // REGISTRO DOCXPAGAR

    private $docxpagar_formapago = 'docxpagar_formapago'; // CARRITO
    private $formapago; // REGISTRO FORMAPAGO

    public function __construct($docxpagar_id)
    {

        $this->docxpagar_id = $docxpagar_id;
        
    }

    public function validar_formapago($request){ // objeto: cntformapago - txtcodigo, método: Valid

        $this->docxpagar = DocumentToPay::find($this->docxpagar_id);
        $this->formapago = PaymentMethod::selectidcmd_formapago($request->formapago);

        $this->llenar_docxpagar_formapago();

        return $this->obtener_importe($request);

    }
    
    private function llenar_docxpagar_formapago(){
        
        Cart::instance($this->docxpagar_formapago)->destroy();

        $item = ($this->docxpagar_id != 0) ? $this->obtener_item_docxpagar_formapago_editar() : $this->obtener_item_docxpagar_formapago();

        if($item) { // PREVENIR EL ERROR CUANDO SE HACE DOBLE CLIC Y NO ENCUENTRA EL ITEM (EL MOUSE FALLA)

            Cart::instance($this->docxpagar_formapago)->add(['id' => $item, 'name' => $this->formapago->id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "moneda_id" => $this->formapago->moneda_id,
                "banco_id" => $this->formapago->banco_id,
                "ctactebanco_id" => $this->formapago->ctactebanco_id,
                "efectivo" => $this->formapago->efectivo,
                "importe" => 0
            ]]);
            //dd(Cart::instance($this->docxpagar_formapago)->content());


        }


    }

    private function obtener_importe($request){

        $cart = collect(Cart::instance($this->docxpagar_formapago)->content())->first();
        
        $data['txtpago'] = null;
        $data['txtpago_estado'] = 'desbloquear';

        // PREGUNTAR EL ORIGEN DE DETRACCION_ID
        $detraccion_id = (isset($this->docxpagar->detraccion_id) == true) ? $this->docxpagar->detraccion_id : 0;

        $total_aplica = 0;

        $movctacte = MovementCte::selectidcmd($this->docxpagar_id);

        if ($movctacte){

            if ($this->formapago->moneda_id == 1){

                foreach($movctacte as $m){

                    if ($m->id != $m->origen_id && $m->origen_id != $detraccion_id){

                        $total_aplica += $m->saldomn;

                    }
                    

                }

            }else{
    
                foreach($movctacte as $m){

                    if ($m->id != $m->origen_id && $m->origen_id != $detraccion_id){

                        $total_aplica += $m->saldome;

                    }                    

                }
    
            }

        }


        if($request->moneda == 1){

            if ($this->formapago->moneda_id == 1){

                $total_aplica = $request->total - $request->importe_detracion - abs($total_aplica);

            }else{

                $total_aplica = formatear_numero(($request->total - $request->importe_detracion) / $request->tcambio, 2) - abs($total_aplica);

            }

        }else{

            if ($this->formapago->moneda_id == 1){

                $total_aplica = formatear_numero($request->total * $request->tcambio, 2) - $request->importe_detracion - abs($total_aplica);

            }else{

                $total_aplica = $request->total - formatear_numero($request->importe_detracion / $request->tcambio, 2) - abs($total_aplica); 

            }

        }

        $total_aplica = $total_aplica; // - total importe tag(pestaña) 3

        $total_aplica = ($total_aplica < 0) ? 0 : $total_aplica;

        if($total_aplica == 0){

            $data['txtpago_estado'] = 'bloquear';

        }else{
            

            $item = Cart::instance($this->docxpagar_formapago)->get($cart->rowId);

            $options = $item->options->merge([
                'importe' => $total_aplica,
            ]);

            Cart::instance($this->docxpagar_formapago)->update($cart->rowId, [
                'options' => $options]);


        }

        $data["banco"] = Bank::find($cart->options->banco_id);
        $data["moneda"] = Currency::find($cart->options->moneda_id);
        $data['txtpago'] = $total_aplica;
        

        return $data;

    }


    private function obtener_item_docxpagar_formapago(){

        $ultimoCart = Cart::instance($this->docxpagar_formapago)->content()->sortByDesc("id")->first();

        if ($ultimoCart == null) {
    
            $ultimoBd = DocumentToPayPaymentWay::where('parent_id', $this->docxpagar_id)->get()->last(); 
            $item = !$ultimoBd ? 0 : $ultimoBd->item;
    
        } else {
    
            $item = $ultimoCart->id; // id = item
    
        }

        $item++;

        return $item;        

    }

    public function eliminar_carritos(){

        Cart::instance($this->docxpagar_formapago)->destroy();

    }

    public function obtener_docxpagar_formapago(){
        // SOLO DEBE TENER UN REGISTRO
        return collect(Cart::instance($this->docxpagar_formapago)->content())->first();

    }

    public function validar_importefp($request){

        $importefp = $request->importefp;

        $docxpagar_formapago = $this->obtener_docxpagar_formapago();
        
        if($docxpagar_formapago){

            if ($request->importefp > $docxpagar_formapago->options->importe || $request->importefp < 0){

                $importefp = $docxpagar_formapago->options->importe;
    
            }

        }

        return $importefp;
    }

    public function validar_nrochequefp($request){

        $nrochequefp = $request->nrochequefp;

        $docxpagar_formapago = $this->obtener_docxpagar_formapago();

        if($docxpagar_formapago){

            if($docxpagar_formapago->options->efectivo == 1){

                $nrochequefp = null;

            }

        }

        return $nrochequefp;

    }

    public function grabar_carritos($docxpagar, $request){

        // GRABAR DOCXPAGAR_FORMAPAGO

        $cart_docxpagar_formapago = Cart::instance($this->docxpagar_formapago)->content();
     
        foreach ($cart_docxpagar_formapago as $cart) {
          
            $existe = DocumentToPayPaymentWay::where("parent_id", $docxpagar->id)->where("item", $cart->id)->exists();
            
            if ($existe) { //Editar                

                $item = $cart->id;

                $data['formapago_id'] = $cart->name;
                $data['ctactebanco_id'] = $cart->options->ctactebanco_id;
                $data['importe'] = (empty($request->importefp)) ? 0 : $request->importefp;
                $data['nrocheque'] = "";//(empty($request->nrochequefp)) ? "" : $request->nrochequefp;
                $data['mediopago_id'] = (empty($request->mediopagofp)) ? null : $request->mediopagofp;
                $data['transaccion'] = (empty($request->transaccionfp)) ? "" : $request->transaccionfp;
                //$data['docbanco_id'] = // VER ORIGEN
                $data['usuario'] = Session::get('usuario'); 
                
                DocumentToPayPaymentWay::editarRefactorizado($docxpagar->id, $item, $data);

            } else { //Crear
                
                $docxpagar_formapago = new DocumentToPayPaymentWay();
                $docxpagar_formapago->parent_id = $docxpagar->id;
                $docxpagar_formapago->item = $cart->id;
                $docxpagar_formapago->formapago_id = $cart->name;
                $docxpagar_formapago->ctactebanco_id = $cart->options->ctactebanco_id;
                $docxpagar_formapago->importe = (empty($request->importefp)) ? 0 : $request->importefp;
                $docxpagar_formapago->nrocheque = "";//(empty($request->nrochequefp)) ? "" : $request->nrochequefp;
                $docxpagar_formapago->mediopago_id = (empty($request->mediopagofp)) ? null : $request->mediopagofp;
                $docxpagar_formapago->transaccion = (empty($request->transaccionfp)) ? "" : $request->transaccionfp;
                //$docxpagar_formapago->docbanco_id =  // VER ORIGEN
                $docxpagar_formapago->usuario = Session::get('usuario');

                $docxpagar_formapago->save();

            }
        }                


    }

    public function llenar_carritos($docxpagar){

        $docxpagar_formapago = DocumentToPayPaymentWay::selectidcmd($this->docxpagar_id);
        
        if($docxpagar_formapago){
            
            Cart::instance($this->docxpagar_formapago)->add(['id' => $docxpagar_formapago->item, 'name' => $docxpagar_formapago->formapago_id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "moneda_id" => $docxpagar_formapago->moneda_id,
                "banco_id" => $docxpagar_formapago->banco_id,
                "ctactebanco_id" => $docxpagar_formapago->ctactebanco_id,
                "efectivo" => $docxpagar_formapago->efectivo,
                "importe" => $docxpagar_formapago->importe
            ]]);

        }


    }

    private function obtener_item_docxpagar_formapago_editar(){

        $ultimoCart = Cart::instance($this->docxpagar_formapago)->content()->sortByDesc("id")->first();

        if ($ultimoCart == null) {
    
            $ultimoBd = DocumentToPayPaymentWay::where('parent_id', $this->docxpagar_id)->get()->last(); 
            $item = !$ultimoBd ? 0 : $ultimoBd->item;
    
        } else {
    
            $item = $ultimoCart->id; // id = item
    
        }

        $item = ($item == 0) ? 1 : $item; // ENVIAR 1 SOLO CUANDO VA AGREGARCE POR PRIMERA VEZ 

        // EN CASO SE AYA AGREGADO DEVOLVER EL MISMO ITEM PARA ACTUALIZAR Y NO AGREGAR

        return $item;        

    }

}
