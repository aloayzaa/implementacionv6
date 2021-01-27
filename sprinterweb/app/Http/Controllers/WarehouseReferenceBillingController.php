<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Procedures\AlmacenProcedure;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Warehouses\Entities\WarehouseIncome;
use App\WorkOrder\Entities\WorkOrder;
use App\DocumentToPay\Entities\DocumentToPayWork;
use App\Warehouses\Entities\WarehouseOrder;
use App\Products\Entities\Productos;

class WarehouseReferenceBillingController extends Controller
{

    private $docxpagar_id; // ID DOCXPAGAR, CREAR - EDITAR
    private $almacenProcedure; // OBJETO PROCEDIMIENTOS ALMACEN  
    private $gettransferenciaxatender; // DATA DEL PROCEDIMIENTO ALMACENADO

    private $referencia_cabecera = 'referencia_cabecera'; // CARRITO - PARA LLENAR EL DETALLE
    private $referencia_detalle = 'referencia_detalle'; // CARRITO - PARA LLENAR EL DETALLE

    private $tmpguias = 'tmpguias'; //CARRITO VER (objeto: txtocompra, método: keyPress) - PARA ACTUALIZAR LAS SALIDAS DE ALMACEN QUE VIENEN POR REEFERENCIA, PARA EL LISTADO TAB 3

    private $detailBillingController; // OBJETO DETALLE FACTURACIÓN
    private $referenceBillingController; // OBJETO DOCUMENTOS REFERENCIA

    private $referencias_agregadas; // REFERENCIAS AGREGADAS AL DETALLE 

    public function __construct($id)
    {
        $this->docxpagar_id = $id;
        $this->almacenProcedure = new AlmacenProcedure;

    }

    public function ejecutar_gettransferenciaxatender($request){

        //$this->referencias_agregadas();

        $this->gettransferenciaxatender = $this->almacenProcedure->gettransferenciaxatender('20120101', $request->fecha_proceso, $this->docxpagar_id, $request->tercero, 'V');
        
        $this->eliminar_cabecera_detalle();


    }

    public function llenar_cabecera(){

        if (count($this->gettransferenciaxatender) == 0) : return null; endif;
        
        // TODOS LOS REGISTROS 
        foreach($this->gettransferenciaxatender as $detalle){

            // id = id // name = id concat item 

            Cart::instance($this->referencia_cabecera)->add(['id' => $detalle->id, 'name' => $detalle->id . $detalle->item, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "id" => $detalle->id,
                "documento" => $detalle->documento,
                "fecha" => $detalle->fecha,
                "nombre" => empty($detalle->nombre) ? '' : $detalle->nombre,
                "moneda_id" => 1,
                "glosa" => trim($detalle->glosa),
                "ventana" => empty($detalle->ventana) ? '' : $detalle->ventana,
                "elegido" => 0,

                // DETALLES
                "item" => $detalle->item,
				"producto_id" => $detalle->producto_id,
				"umedida_id" => $detalle->umedida_id,
				"prd_cod" => $detalle->prd_cod,
				"prd_dsc" => $detalle->prd_dsc,
				"ume_cod" => $detalle->ume_cod,
				"centrocosto_id" => ( empty($detalle->centrocosto_id) ) ? null : $detalle->centrocosto_id,
				"cco_cod" => ( empty($detalle->cco_cod) ) ? '' : $detalle->cco_cod,
				"cco_dsc" => ( empty($detalle->cco_dsc) ) ? '' : $detalle->cco_dsc,
				"lote" => $detalle->lote,
				"vencelote" => empty($detalle->vencelote) ? '' : $detalle->vencelote,
				"cantidad" => $detalle->cantidad,
				"atendido" => $detalle->atendido,
				"aplica" => $detalle->aplicar,
				"peso" => $detalle->peso,
				"preciovta" => $detalle->preciovta,
				"tipoprecio" => $detalle->tipoprecio,
                "stock" => 0,
                "recibir" => $detalle->cantidad - $detalle->atendido                
            ]]);

        }


    }

    public function obtener_cabecera(){

        //dd(collect(Cart::instance($this->referencia_cabecera)->content())->unique('id'));
        $registros = Cart::instance($this->referencia_cabecera)->content();

        // SELECCIONAR CABECERAS
        $registros_unicos = collect($registros)->unique('id');

        return $registros_unicos;

    }

    public function llenar_quitar_detalle($request){
        
        if ($request->chk_ingresoalmacen_cab == "true"){
            
            $this->llenar_detalle($request);

        }else{
            
            $this->quitar_detalle($request);
            
        }
        

    }


    private function llenar_detalle($request){
        
        $cabecera_seleccionados = collect(Cart::instance($this->referencia_cabecera)->content())->where('id', $request->documento_id);
        
        foreach($cabecera_seleccionados as $cart){

            // id = id // name = id concat item 

            Cart::instance($this->referencia_detalle)->add(['id' => $cart->id, 'name' => $cart->name, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "documento" => $cart->options->documento,
				"producto_id" => $cart->options->producto_id,
				"umedida_id" => $cart->options->umedida_id,
				"prd_cod" => $cart->options->prd_cod,
				"prd_dsc" => $cart->options->prd_dsc,
				"ume_cod" => $cart->options->ume_cod,
				"centrocosto_id" => ( empty($cart->options->centrocosto_id) ) ? null : $cart->options->centrocosto_id,
				"cco_cod" => ( empty($cart->options->cco_cod) ) ? '' : $cart->options->cco_cod,
				"cco_dsc" => ( empty($cart->options->cco_dsc) ) ? '' : $cart->options->cco_dsc,
				"lote" => trim($cart->options->lote),
				"vencelote" => empty($cart->options->vencelote) ? '' : $cart->options->vencelote,
				"cantidad" => $cart->options->cantidad,
				"atendido" => $cart->options->atendido,
				"aplica" => $cart->options->aplicar,
				"peso" => $cart->options->peso,
				"preciovta" => $cart->options->preciovta,
				"tipoprecio" => $cart->options->tipoprecio,
                "stock" => 0,
                "recibir" => $cart->options->cantidad - $cart->options->atendido,
                //SELECCIÓN
                "elegido" => 1,
            ]]);

        }


    }

    private function quitar_detalle($request){
        
        $detalles_eliminar = collect(Cart::instance($this->referencia_detalle)->content())->where('id', $request->documento_id);

        foreach($detalles_eliminar as $detalle){

            Cart::instance($this->referencia_detalle)->remove($detalle->rowId);

        }

    }

    public function obtener_detalle(){
        
        return Cart::instance($this->referencia_detalle)->content();

    }

    public function eliminar_carritos(){

        Cart::instance($this->referencia_cabecera)->destroy();
        Cart::instance($this->referencia_detalle)->destroy();
        Cart::instance($this->tmpguias)->destroy();
        
    }

    public function agregar_salidaalmacen_referencia($request, $detailBillingController, $referenceBillingController, $data){
        
        if (collect(Cart::instance($this->referencia_detalle)->content())->isEmpty()) : return null; endif;

        // OBJETO DOCXPAGAR_DETALLE
        $this->detailBillingController = $detailBillingController;

        // OBJETO DOCUMENTOS REFERENCIA
        $this->referenceBillingController = $referenceBillingController;

        // ELIMINAR LOS CARRITOS NECESARIOS
        $this->eliminar_carritos_nueva_salidaalmacen();

        // VALIDAR CANTIDAD Y UNIR LA DATA CON EL CARRITO
        $this->validar_cantidad_salidaalmacen_referencia($data);


        // ID ITEMS SELECCIONADOS
        $detalle_id = array_column(array_column($data, 'ids'),'value'); 


        // REGISTROS SELECCIONADOS
        $detalles = collect(Cart::instance($this->referencia_detalle)->content())->whereIn('name', $detalle_id); // items seleccionados

        // REGISTROS ÚNICOS
        $detalles_unicos = collect($detalles)->unique('id');


         
        
        // CON CABECERAS // objeto:txtocompra, método:keyPress
        foreach($detalles_unicos as $detalle){
            
            $ingresoalmacen = WarehouseIncome::selectidcmd($detalle->id);            

            $this->agregar_tmpguias($ingresoalmacen);

            $this->evaluar_ordentrabajo_id($ingresoalmacen->ordentrabajo_id);

            $this->evaluar_cotizacion_id($ingresoalmacen->pedidoalmacen_id);


        }

        
        // CON DETALLES // objeto:txtocompra, método:keyPress
        foreach($detalles as $detalle){

            $getctaventapdto = $this->getctaventapdto($detalle, $request);

            $this->detailBillingController->agregar_docxpagar_detalle_salidasalmacen($detalle, $getctaventapdto, $request);

        }

        

        $data['txtocompra'] =  'SA ' . trim($ingresoalmacen->periodo) . '-' . str_pad($ingresoalmacen->numero, 5, '0', STR_PAD_LEFT);
        $data['txtserierem'] = trim($ingresoalmacen->seriedoc);
        $data['txtnrorem'] = trim($ingresoalmacen->numerodoc);


        return $data;

    }


    private function evaluar_ordentrabajo_id($ordentrabajo_id){
        
        if ($ordentrabajo_id <= 0) { return null; }

        $docxpagar_otrabajo = DocumentToPayWork::select('id')->where('ordentrabajo_id', $ordentrabajo_id)->first();
        
        // SI NO EXISTE, AGREGAR 
        if ( isset($docxpagar_otrabajo->id) == false){

            $ordentrabajo = WorkOrder::select('id', 'nromanual', 'ventana')->where('id', $ordentrabajo_id)->first();

            $this->agregar_docxpagar_otrabajo($ordentrabajo, 'ordentrabajo');

        }

    }

    private function agregar_docxpagar_otrabajo($otrabajo, $condicion){

        if ($condicion == 'ordentrabajo'){

            $ordentrabajo_id = $otrabajo->id;
            $cotizacion_id = null;
            $nromanual = trim($otrabajo->nromanual);
            $ventana = trim($otrabajo->ventana);
            $vendedor_id = null; // SOLO EN COTIZACIÓN REEMPLAZAR VENDEDOR EN EL FORMULARIO

        }else{ // COTIZACIÓN

            $ordentrabajo_id = null;
            $cotizacion_id = $otrabajo->id;
            $nromanual = 'CO ' . str_pad($otrabajo->serie, 3, '0', STR_PAD_LEFT) . '-' . str_pad($otrabajo->numero, 5, '0', STR_PAD_LEFT);
            $ventana = trim($otrabajo->ventana);
            $vendedor_id = $otrabajo->vendedor_id; // SOLO EN COTIZACIÓN REEMPLAZAR VENDEDOR EN EL FORMULARIO

        }

        $this->referenceBillingController->agregar_docxpagar_otrabajo_desde_salidadealmacen($nromanual, $ordentrabajo_id, $cotizacion_id, $ventana, $vendedor_id);
        

    }


    private function evaluar_cotizacion_id($pedidoalmacen_id){

        $pedido_almacen = WarehouseOrder::cotizacion_billing($pedidoalmacen_id);

        if ($pedido_almacen){

            if ($pedido_almacen->cotizacion_id <= 0) { return null; }

            $this->agregar_docxpagar_otrabajo($pedido_almacen, 'cotizacion');

        }

    }


    private function getctaventapdto($registro, $request){

        $cuenta_id = null;
        $cuenta = '';
        $esafecto = 1;
        $precio = $registro->preciovta;
        $pide_ccosto = $consumo = 0;

        $getctaventapdto = Productos::getctaventapdto_billing($registro->options->producto_id, $request->tipo_venta);
        
        if($getctaventapdto){

            $cuenta_id = ( empty($getctaventapdto->cuenta_id) ) ? null : $getctaventapdto->cuenta_id;
            $cuenta = ( empty($getctaventapdto->codigo) ) ? '' :  $getctaventapdto->codigo;
            $pide_ccosto = ( empty($getctaventapdto->pide_ccosto) ) ? 0 :  $getctaventapdto->pide_ccosto;

            if ( $precio == 0 ) {

                 $precio = ( empty($getctaventapdto->precio1) ) ? 0 : $getctaventapdto->precio1;

            }

            $esafecto = $getctaventapdto->esafecto;
            $consumo = $getctaventapdto->consumo;

        }
             

        $data['esafecto'] = $esafecto;
        $data['precio'] = $precio;
        $data['consumo'] = $consumo;
        $data['cuenta_id'] = $cuenta_id;
        $data['cuenta'] = $cuenta;
        $data['pide_ccosto'] = $pide_ccosto;

        return $data;

    }


    private function validar_cantidad_salidaalmacen_referencia($data){

        foreach($data as $d){

            $cart = collect(Cart::instance($this->referencia_detalle)->content())->firstWhere('name', $d->ids->value);

            $aplicar = $d->aplicar->value;  // input modal nota crédito débito

            if ( $cart->options->cantidad < $d->aplicar->value){

                $aplicar = $cart->options->cantidad;

            }

            $item = Cart::instance($this->referencia_detalle)->get($cart->rowId);

            $options = $item->options->merge([
                'aplica' => $aplicar,
            ]);

            Cart::instance($this->referencia_detalle)->update($cart->rowId, [
                'options' => $options]);

        }

    }


    private function referencias_agregadas(){


        $this->referencias_agregadas =  collect(Cart::instance($this->referencia_detalle)->content())->pluck('name');
        

    }


    private function seleccionar_referencias_agregadas(){

        $referencias_seleccionadas = collect(Cart::instance($this->referencia_cabecera)->content())->whereIn('name', $this->referencias_agregadas);

        foreach($referencias_seleccionadas as $cart){


            $item = Cart::instance($this->referencia_cabecera)->get($cart->rowId);

            $options = $item->options->merge([
                'elegido' => 1,
            ]);

            Cart::instance($this->referencia_cabecera)->update($cart->rowId, [
                'options' => $options]);



            // id = id // name = id concat item 

            Cart::instance($this->referencia_detalle)->add(['id' => $cart->id, 'name' => $cart->name, 'qty' => 1, 'price' => 0.00,
            'options' => [
                "documento" => $cart->options->documento,
				"producto_id" => $cart->options->producto_id,
				"umedida_id" => $cart->options->umedida_id,
				"prd_cod" => $cart->options->prd_cod,
				"prd_dsc" => $cart->options->prd_dsc,
				"ume_cod" => $cart->options->ume_cod,
				"centrocosto_id" => ( empty($cart->options->centrocosto_id) ) ? 0 : $cart->options->centrocosto_id,
				"cco_cod" => ( empty($cart->options->cco_cod) ) ? '' : $cart->options->cco_cod,
				"cco_dsc" => ( empty($cart->options->cco_dsc) ) ? '' : $cart->options->cco_dsc,
				"lote" => trim($cart->options->lote),
				"vencelote" => empty($cart->options->vencelote) ? '' : $cart->options->vencelote,
				"cantidad" => $cart->options->cantidad,
				"atendido" => $cart->options->atendido,
				"aplica" => $cart->options->aplicar,
				"peso" => $cart->options->peso,
				"preciovta" => $cart->options->preciovta,
				"tipoprecio" => $cart->options->tipoprecio,
                "stock" => 0,
                "recibir" => $cart->options->cantidad - $cart->options->atendido,
                //SELECCIÓN
                "elegido" => 1,
            ]]);



        }

        //dd(Cart::instance($this->referencia_cabecera)->content());
    }

    private function agregar_tmpguias($ingresoalmacen){

        Cart::instance($this->tmpguias)->add(['id' => $ingresoalmacen->id, 'name' => $ingresoalmacen->seriedoc . '-' . $ingresoalmacen->numerodoc, 'qty' => 1, 'price' => 0.00,
        'options' => [
            "seriedoc" => $ingresoalmacen->seriedoc,
            "numerodoc" => $ingresoalmacen->numerodoc,
            "fecha" => $ingresoalmacen->fecha,
            "glosa" => $ingresoalmacen->glosa,
            "ventana" => $ingresoalmacen->ventana
        ]]);

    }

    private function eliminar_cabecera_detalle(){

        Cart::instance($this->referencia_cabecera)->destroy();
        Cart::instance($this->referencia_detalle)->destroy();

    }

    private function eliminar_carritos_nueva_salidaalmacen(){

        Cart::instance($this->tmpguias)->destroy();

        // ELIMINAR DATA CART_PRODUCTOS , CART_DOCXPAGAR_DETALLE
        $this->detailBillingController->eliminar_carritos();

        // SI SE VA AGREGAR DOCXPAGAR_OTRABAJO DESDE SALIDA DE ALMACEN 
        // NO DEBE HABER DATA EN EL DOCXPAGAR_OTRBAJO DE DOCUMENTOS DE REFERENCIA
        $this->referenceBillingController->eliminar_carrito_docxpagar_otrabajo();

    }

    public function obtener_tmpguias(){

        return Cart::instance($this->tmpguias)->content();
        
    }

    public function grabar_carritos($docxpagar){

         // SE AGREGABA A DOCXPAGAR_OTRABAJO PERO AHORA SOLO SE REALIZA DESDE DOCUMENTOS DE REFERENCIA

    }

    public function llenar_carritos($docxpagar){ // formulario: mov_facturaventa, método: llenarde

        $ingresos_almacen = WarehouseIncome::selectrefcmd_x($docxpagar->id);

        if($ingresos_almacen){

            foreach($ingresos_almacen as $ingresoalmacen){

                $seriedoc = trim($ingresoalmacen->seriedoc);

                $seriedoc = (empty($seriedoc)) ? $ingresoalmacen->periodo : $seriedoc;


                $numerodoc = trim($ingresoalmacen->numerodoc);

                $numerodoc = (empty($numerodoc)) ? str_pad($ingresoalmacen->numero, 5, '0', STR_PAD_LEFT) : $numerodoc;

                Cart::instance($this->tmpguias)->add(['id' => $ingresoalmacen->id, 'name' => $seriedoc . '-' . $numerodoc, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    "seriedoc" => $seriedoc,
                    "numerodoc" => $numerodoc,
                    "fecha" => $ingresoalmacen->fecha,
                    "glosa" => $ingresoalmacen->glosa,
                    "ventana" => $ingresoalmacen->ventana
                ]]);


            }


        }


    }


}
