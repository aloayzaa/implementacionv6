<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Panel\Companies\Entities\Pempresa;
use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayWork;
use App\Quotation\Entities\Quotation;
use App\Quotation\Entities\QuotationDetail;
use App\WorkOrder\Entities\WorkOrder;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Customers\Entities\Customer;
use App\Products\Entities\Productos;
use App\SalesTypes\Entities\SaleType;
use Illuminate\Support\Facades\Session;

class ReferenceBillingController extends Controller
{

    private $docxpagar_id; // ID DOCXPAGAR CREAR-EDITAR
    private $lfactop;
    private $referencia_detalle = 'detalle_referencia_cotizacion_otrabajo';
    private $docxpagar_otrabajo = 'docxpagar_otrabajo_documentos_referencia';
    private $detailBillingController; // OBJETO DOCXPAGAR DETALLE

    public function __construct($id)
    {
        $this->docxpagar_id = $id;
    }


    public function mostrar_referencias_cabecera($request){

        Cart::instance($this->referencia_detalle)->destroy(); // LIMPIAR DATA DETALLE

        $this->lfactop = Pempresa::get_parametro('PRO_FACTOP')->valor; // Se puede facturar Ordenes sin cerrar

        return $this->evaluar_origen($request);


    }


    private function evaluar_origen($request){
   
        $docxpagar = DocumentToPay::find($this->docxpagar_id);
        
        $referencia = ($request->referencia_tipo == 2) ? $this->cotizacion($request, $docxpagar) : $this->orden_trabajo($request, $docxpagar);

        return $referencia;

    }

    private function cotizacion($request, $docxpagar){ // Ver aux_aplicaot - btnrefresh - método click 

        $cotizacion_id = (Session::get('cotizacion_id')) ? Session::get('cotizacion_id') : 0;
        
        if (!empty($request->referencia_desde)){

            $referencia_cotizacion = Quotation::referencia_facturacion_desde($request->referencia_desde, $request->referencia_hasta, $cotizacion_id, $request->referencia_serie, $request->referencia_numero, $request->referencia_nombre);

        }else{

            $referencia_cotizacion = Quotation::referencia_facturacion_hasta($request->referencia_hasta, $cotizacion_id, $request->referencia_serie, $request->referencia_numero, $request->referencia_nombre);

        }


        return $this->formatear_listado_cabecera($referencia_cotizacion);


    }


    
    private function orden_trabajo($request, $docxpagar){ // Ver aux_aplicaot - btnrefresh - método click ok

        if($this->lfactop == 1){ return []; } // OBJETO: AUX_APLICAOT - GRDCAB, MÉTODO: AFTERROWCOLCHANGE

        $tipo_venta = SaleType::select("esanticipo")->where('id', $request->tipo_venta)->first();
        $anticipo = ($tipo_venta) ? $tipo_venta->esanticipo : 0;

        $cotizacion_id = 0;
        $estado = "('ACTIVO','APROBADO','CERRADO','FT.PARCIAL')";


        if ($this->lfactop != 0 && $anticipo == 0){
            $estado = "('CERRADO','FT.PARCIAL')";
        }
        

        if (!empty($request->referencia_desde)){

            $referencia_orden_trabajo = WorkOrder::referencia_facturacion_desde($request->referencia_desde, $request->referencia_hasta, $estado, $cotizacion_id, $request->referencia_numero, $request->referencia_nombre);

        }else{

            $referencia_orden_trabajo = WorkOrder::referencia_facturacion_hasta($request->referencia_hasta, $estado, $cotizacion_id, $request->referencia_numero, $request->referencia_nombre);

        }
        
        return $this->formatear_listado_cabecera($referencia_orden_trabajo);

    }

    private function formatear_listado_cabecera($referencias){
        
        $array_enviar = array();

        foreach($referencias as $referencia){

            $array = array(
                "id" => $referencia->id,
                "documento" => $referencia->documento,
                "fecha" => $referencia->fecha,
                "nombre" => (empty($referencia->nombre)) ? '' : trim($referencia->nombre),
                "moneda_id" => $referencia->moneda_id,
                "tcambio" => $referencia->tcambio,
                "ventana" => (empty($referencia->ventana)) ? '' : $referencia->ventana,
                "glosa" => substr($referencia->glosa, 0, 200),
                "elegido" => 0
            );
            $array_enviar[] = $array;

        } // FIN FOREACH


        return $array_enviar;

    }

    public function agregar_quitar_detalle($request){

        if($request->estado == "true"){


            $this->agregar_detalle($request);
                                

        }else{

            $this->quitar_detalle($request);

        }

    }


    private function agregar_detalle($request){

        if($request->referencia_tipo == 1){ // ORDEN TRABAJO

            $detalles = WorkOrder::getdetotfactura($request->id);
            
            foreach($detalles as $detalle){

                $descripcion = (!empty($detalle->glosa)) ? trim($detalle->glosa) : trim($detalle->descripcion);

                Cart::instance($this->referencia_detalle)->add(['id' => $detalle->id . $detalle->item, 'name' => $detalle->id, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    "producto_id" => (empty($detalle->producto_id)) ? 0 : $detalle->producto_id,
                    "umedida_id" => (empty($detalle->umedida_id)) ? 0 : $detalle->umedida_id,
                    "prd_cod" => (empty($detalle->codigo)) ? '' : $detalle->codigo,
                    "prd_dsc" => (empty($descripcion)) ? '' : $descripcion,
                    "ume_cod" => (empty($detalle->ume_cod)) ? '' : $detalle->ume_cod,
                    "esafecto" => (empty($detalle->esafecto)) ? 0 : $detalle->esafecto,
                    "cantidad" => $detalle->cantidad,
                    "precio" => $detalle->precio,
                    "preciolista" => $detalle->preciolista,
                    "pdescuento" => $detalle->pdescuento,
                    "atendido" => 0,
                    "aplicar" => $detalle->cantidad,
                    "stock" => 0,
                    "prd_tipo" => $detalle->tipoproducto,
                    //"referencia_id" => $detalle->parent_id,
                    "docrefer" => '',
                    "elegido" => 1,
                    "tabla" => 'ordentrabajo'
                ]]);
    
            }            
            
        }else{ // COTIZACION

            $detalles = QuotationDetail::selectidcmd($request->id);

            foreach($detalles as $detalle){

                $descripcion = (!empty($detalle->glosa)) ? trim($detalle->glosa) : trim($detalle->descripcion);
                
                Cart::instance($this->referencia_detalle)->add(['id' => $detalle->parent_id . $detalle->item, 'name' => $detalle->parent_id, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    "producto_id" => (empty($detalle->producto_id)) ? 0 : $detalle->producto_id,
                    "umedida_id" => (empty($detalle->umedida_id)) ? 0 : $detalle->umedida_id,
                    "prd_cod" => (empty($detalle->codigo)) ? '' : $detalle->codigo,
                    "prd_dsc" => (empty($descripcion)) ? '' : $descripcion,
                    "ume_cod" => (empty($detalle->ume_cod)) ? '' : $detalle->ume_cod,
                    "esafecto" => (empty($detalle->esafecto)) ? 0 : $detalle->esafecto,
                    "cantidad" => $detalle->cantidad,
                    "precio" => $detalle->precio,
                    "preciolista" => $detalle->preciolista,
                    "pdescuento" => $detalle->pdescuento,
                    "atendido" => 0,
                    "aplicar" => $detalle->cantidad,
                    "stock" => 0,
                    "prd_tipo" => $detalle->tipoproducto,
                    //"referencia_id" => $detalle->parent_id,
                    "docrefer" => '',
                    "elegido" => 1,
                    "tabla" => 'cotizacion'
                ]]);
    
            }



        }


    }

    private function quitar_detalle($request){

        $detalles_eliminar = collect(Cart::instance($this->referencia_detalle)->content())->where('name', $request->id);

        foreach($detalles_eliminar as $detalle){

            Cart::instance($this->referencia_detalle)->remove($detalle->rowId);

        }


    }

    public function obtener_referencia_detalle(){

        return Cart::instance($this->referencia_detalle)->content();

    }

    public function agregar_detalles_referencia($request, $detailBillingController, $data){

        if (collect(Cart::instance($this->referencia_detalle)->content())->isEmpty()) : return null; endif;

        // OBJETO DOCXPAGAR_DETALLE
        $this->detailBillingController = $detailBillingController;

        

        // ELIMINAR LOS CARRITOS NECESARIOS
        $this->eliminar_carritos_agregar_detalles();


        // ID ITEMS SELECCIONADOS
        $detalle_id = array_column(array_column($data, 'ids'),'value'); 
        

        // REGISTROS SELECCIONADOS
        $detalles = collect(Cart::instance($this->referencia_detalle)->content())->whereIn('id', $detalle_id); // items seleccionados

        // REGISTROS ÚNICOS
        $detalles_unicos = collect($detalles)->unique('name');        


        return $this->obtener_data_docxpagar($detalles_unicos, $detalles, $request);
              
    

    }


    private function eliminar_carritos_agregar_detalles(){

        // ELIMINAR CARRITOS NECESARIOS VER CLASIC
        $this->detailBillingController->eliminar_carritos();
        Cart::instance($this->docxpagar_otrabajo)->destroy();

    } 


    private function obtener_data_docxpagar($detalles_unicos, $detalles, $request){

        $tmprefer = array();

        $i = 0;

        foreach($detalles_unicos as $detalle){

            if($detalle->options->tabla == 'cotizacion'){

                $origen = Quotation::selectidcmd($detalle->name);
                $tercero_id = empty($origen->cliente_id) ? null : $origen->cliente_id;
                $cotizacion_id = $detalle->name;
                $ordentrabajo_id = null; 
                $vendedor_id = $origen->vendedor_id;
                $condicionpago_id = $origen->condicionpago_id;
                $moneda_id =  $origen->moneda_id;
                $deducible = 0;
                $ventana = $origen->ventana;
                $nromanual = "CO" . str_pad($origen->serie, 3, "0", STR_PAD_LEFT) . '-' . str_pad($origen->numero, 5, "0", STR_PAD_LEFT);
                $glosa = $origen->glosa;   
    
            }else{ // ordentrabajo
    
                $origen = WorkOrder::selectidcmd($detalle->name);
                $tercero_id = empty($origen->tercero_id) ? null : $origen->tercero_id;
                $cotizacion_id = null;
                $ordentrabajo_id = $detalle->name;
                $vendedor_id = null;
                $condicionpago_id = null;
                $moneda_id =  $origen->moneda_id;
                $deducible = $origen->precio2;
                $ventana = $origen->ventana;
                $nromanual = $origen->nromanual;
                $glosa = (empty($origen->prd_dsc)) ? '' : trim($origen->prd_dsc) ;
            }
    
            $centrocosto_id = $origen->centrocosto_id;
                
            if($i == 0){

                if(empty($tercero_id) == false){
        
                    $tercero = Customer::select('codigo', 'descripcion')->where('id', $tercero_id)->first();
                    
                }

                $data['tercero_id'] = $tercero_id;
                $data['tercero_codigo'] = (isset($tercero->codigo)) ? $tercero->codigo : '';
                $data['tercero_descripcion'] = (isset($tercero->descripcion)) ? $tercero->descripcion : '';

                $tercero = Customer::select2_id($tercero_id);
                $data['doc_identidad'] = $tercero->codigo;
                $data['ubigeo_id'] = $tercero->ubigeo_id;
                $data['direccion'] = $tercero->direccion;
                

                session()->put('cotizacion_id', $cotizacion_id); 
                session()->put('ordentrabajo_id', $ordentrabajo_id);
        
                $data['vendedor_id'] = $vendedor_id;
                $data['condicionpago_id'] = $condicionpago_id;
                $data['moneda_id'] = $moneda_id;
                $data['centrocosto_id'] = $centrocosto_id;
                $data['glosa'] = $glosa;           

            }

            $this->llenar_docxpagar_otrabajo($ordentrabajo_id, $cotizacion_id, $nromanual, $ventana);            

            $array = array(
                "id" => $detalle->name,
                "numero" => $nromanual,
                "ventana" => $ventana
            );
            $tmprefer[] = $array;

            $i++;

        }

        $data['tmprefer'] = $tmprefer;


        foreach($detalles as $detalle){

            $getctaventapdto = $this->getctaventapdto($detalle, $request);

            $this->detailBillingController->agregar_documentos_referencia($detalle, $getctaventapdto, $request);


        }


        return $data;

    }

    private function llenar_docxpagar_otrabajo($ordentrabajo_id, $cotizacion_id, $nromanual, $ventana){

        $item = $this->obtener_item_docxpagar_otrabajo();
        
        Cart::instance($this->docxpagar_otrabajo)->add(['id' => $item, 'name' => $nromanual, 'qty' => 1, 'price' => 0.00,
        'options' => [
            "parent_id" => $this->docxpagar_id, 
            "item" =>  $item,
            "ordentrabajo_id" => $ordentrabajo_id, 
            "cotizacion_id" => $cotizacion_id,
            "nromanual" => $nromanual,
            "ventana" => $ventana,
            "vendedor_id" => null // CUANDO VIENE DE UNA SALIDA DE ALMACEN
        ]]);

    }


    private function obtener_item_docxpagar_otrabajo(){

        $ultimoCart = Cart::instance($this->docxpagar_otrabajo)->content()->sortByDesc("id")->first();

        if ($ultimoCart == null) {
    
            $ultimoBd = DocumentToPayWork::where('parent_id', $this->docxpagar_id)->get()->last(); 
            $item = !$ultimoBd ? 0 : $ultimoBd->item;
    
        } else {
    
            $item = $ultimoCart->id; // id = item
    
        }

        $item++;

        return $item;

    }


    public function eliminar_carrito_docxpagar_otrabajo(){

        Cart::instance($this->docxpagar_otrabajo)->destroy();

    }

    private function getctaventapdto($detalle, $request){

        $cuenta_id = null;
        $cuenta = ''; 
        $pide_ccosto = 0;

        $getctaventapdto = Productos::getctaventapdto_billing($detalle->options->producto_id, $request->tipo_venta);

        if($getctaventapdto){

            $cuenta_id = ( empty($getctaventapdto->cuenta_id) ) ? null : $getctaventapdto->cuenta_id;
            $cuenta = ( empty($getctaventapdto->codigo) ) ? '' :  $getctaventapdto->codigo;
            $pide_ccosto = ( empty($getctaventapdto->pide_ccosto) ) ? 0 :  $getctaventapdto->pide_ccosto;
            
        }

        $data['cuenta_id'] = $cuenta_id;
        $data['cuenta'] = $cuenta;
        $data['pide_ccosto'] = $pide_ccosto;

        return $data;

    }

    public function eliminar_carritos(){

        Cart::instance($this->referencia_detalle)->destroy();
        Cart::instance($this->docxpagar_otrabajo)->destroy();


    }

    public function grabar_carritos($docxpagar){

        if($this->docxpagar_id != 0) { return null; } // NO SE REALIZA NINGUNA ACCIÓN EN EL EDITAR

        // GRABAR DOCXPAGAR_OTRABAJO

        $cart_docxpagar_otrabajo = Cart::instance($this->docxpagar_otrabajo)->content();

        foreach ($cart_docxpagar_otrabajo as $cart) {
            
            $existe = DocumentToPayWork::where("parent_id", $docxpagar->id)->where("item", $cart->id)->exists();
            
            if ($existe) { //Editar                

                $docxpagar_otrabajo = DocumentToPayWork::findOrFail($docxpagar->id);
                $docxpagar_otrabajo->ordentrabajo_id = $cart->options->ordentrabajo_id;
                $docxpagar_otrabajo->cotizacion_id = $cart->options->cotizacion_id;
                
                $docxpagar_otrabajo->save();

            } else { //Crear
                
                $docxpagar_otrabajo = new DocumentToPayWork();
                $docxpagar_otrabajo->parent_id = $docxpagar->id;
                $docxpagar_otrabajo->item = $cart->id;
                $docxpagar_otrabajo->ordentrabajo_id = $cart->options->ordentrabajo_id;
                $docxpagar_otrabajo->cotizacion_id = $cart->options->cotizacion_id;
                
                $docxpagar_otrabajo->save();
                
            }
        }    


    }

    public function agregar_docxpagar_otrabajo_desde_salidadealmacen($nromanual, $ordentrabajo_id, $cotizacion_id, $ventana, $vendedor_id){

        $item = $this->obtener_item_docxpagar_otrabajo();
        
        Cart::instance($this->docxpagar_otrabajo)->add(['id' => $item, 'name' => $nromanual, 'qty' => 1, 'price' => 0.00,
        'options' => [
            "parent_id" => $this->docxpagar_id, 
            "item" =>  $item,
            "ordentrabajo_id" => $ordentrabajo_id, 
            "cotizacion_id" => $cotizacion_id,
            "nromanual" => $nromanual,
            "ventana" => $ventana,
            "vendedor_id" => $vendedor_id 
        ]]);

    }

    public function llenar_carritos($docxpagar){

        $docxpagar_otrabajo = DocumentToPayWork::selectparentcmd($docxpagar->id);
        
        foreach($docxpagar_otrabajo as $d){

            Cart::instance($this->docxpagar_otrabajo)->add(['id' => $d->item, 'name' => $d->nromanual, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "parent_id" => $d->parent_id, 
                "item" => $d->item,
                "ordentrabajo_id" => $d->ordentrabajo_id, 
                "cotizacion_id" => $d->cotizacion_id,
                "nromanual" => $d->nromanual,
                "ventana" => $d->ventana,
                "vendedor_id" => null // CUANDO VIENE DE UNA SALIDA DE ALMACEN
            ]]);            


        }

    }


}
