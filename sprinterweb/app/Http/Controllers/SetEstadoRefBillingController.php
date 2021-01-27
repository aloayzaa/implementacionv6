<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Documents\Entities\Commercial;
use App\Warehouses\Entities\WarehouseIncome;
use App\Quotation\Entities\Quotation;
use App\Procedures\WorkOrderProcedure;
use App\Transport\Entities\TransportGuide;
use App\DocumentToPay\Entities\DocumentToPayWork;
use App\DocumentToPay\Entities\DocumentToPay;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class SetEstadoRefBillingController extends Controller
{
    private $docxpagar; // REGISTRO DOCXPAGAR
    private $cart_referencia; // CARRITO DE REFERENCIA

    public function __construct($docxpagar, $cart_referencia)
    {

        $this->docxpagar = $docxpagar;
        $this->cart_referencia = $cart_referencia;

    }


    public function setestadoref(){

        
        $documentocom = Commercial::findOrFail($this->docxpagar->documento_id);

        // Actualizando referencia de almacén
        $estado = '';
        $ingresoalmacen_id = 0;

        if ($this->docxpagar->noalmacen == 1){

            //EN ESTA CASO SIEMPRE SERÁ UN REGISTRO A DIFERENCIA DEL ELSE EN LAS SALIDAS DE ALMACEN(TABLA INGRESO ALMACEN)
            $ingresoalmacen = WarehouseIncome::select("id")->where('docxpagar_id', $this->docxpagar->id)->orderBy('id','desc')->first();
            
            if($ingresoalmacen){
                $ingresoalmacen_id = $ingresoalmacen->id;
            }      

            $estado = ($this->docxpagar->estado != 'ANULADO') ?  'FACTURADO' :  'ANULADO';

        }else{
            
            if ($this->docxpagar->id > 0) {

                //obtiene el último ingreso almacen del docxpagar
                $almacen = WarehouseIncome::select("id")->where('docxpagar_id', $this->docxpagar->id)->orderBy('id','desc')->first(); 

                if ($almacen) {
                    $ingresoalmacen_id = $almacen->id; 
                }

                $estado = ($this->docxpagar->estado != 'ANULADO') ? 'FACTURADO' : 'ACTIVO';

            }

        }

        if ($ingresoalmacen_id > 0) {

            $ingresoalmacen = WarehouseIncome::findOrFail($ingresoalmacen_id);
            $ingresoalmacen->estado = $estado;
            $ingresoalmacen->save();

        }

        // Actualizando el estado de la cotización

        if ($this->docxpagar->cotizacion_id != null){

            $temporal_cotizaciones = Quotation::actulizar_cotizacion_billing($this->docxpagar->cotizacion_id);

            foreach($temporal_cotizaciones as $temporal_cotizacion){

                $estado = (!empty($temporal_cotizacion->saldo)) ? 'FT.PARCIAL' : 'FACTURADO';

                $cotizacion = Quotation::findOrFail($this->docxpagar->cotizacion_id);
                $cotizacion->estado = $estado;
                $cotizacion->usuario = Session::get('usuario');
                $cotizacion->save();


            }


        }

        if ($this->docxpagar->ordentrabajo_id != null){

            $WorkOrder = new WorkOrderProcedure;
            $WorkOrder->setestado($this->docxpagar->ordentrabajo_id);

        }

        // Si es nota de crédito por anulación de operacion, liberar las guías de transportista y ordenes de trabajo
        if ( $documentocom->codsunat  == '07'){
            
            
            if ($this->cart_referencia){ // SI existe referencia

                if ($this->cart_referencia->id > 0){// == $cart_referencia->options->referencia_id

                    if ( $this->docxpagar->tiponotacredito_id == 1){
                        
                        $data['docxpagar_id '] = null;
                        $data['estado'] = 'ACTIVO';

                        // VERIFICAR EXISTENCIA A MENOS UNO PARA ACTUALIZAR
                        $guiatransporte = TransportGuide::select('id')->where('docxpagar_id', $this->cart_referencia->id)->first();
                        
                        if($guiatransporte){
                            TransportGuide::liberar_guias_transporte_billing($this->cart_referencia->id, $data);
                        }
                                                
                        DocumentToPayWork::liberar_orden_trabajo_billing($this->cart_referencia->id);
                        
                        $data2['ordentrabajo_id'] = null;
                        DocumentToPay::liberar_orden_trabajo_billing($this->cart_referencia->id, $data2);
                    }

                } 

            }

        }


    }

}
