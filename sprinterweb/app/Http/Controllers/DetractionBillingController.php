<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AffectsIgv\Entities\AffectIgv;
use App\DetractionType\Entities\DetractionType;
use App\Currency\Entities\Currency;
use App\Panel\Companies\Entities\Pempresa;
use App\DocumentToPay\Entities\DocumentoToPayDetraction;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Customers\Entities\CustomerAccount;

class DetractionBillingController extends Controller
{

    private $lnRedondeo;
    private $docxpagar_id;

    public function __construct($docxpagar_id)
    {

        $this->lnRedondeo = Pempresa::get_parametro('RED_DETRXC')->valor; // Redondear cálculo de detraccion a (n) decimales

        $this->docxpagar_id = $docxpagar_id;

    }

    
    public function validar_detraccion_por_tipoafecta($request){

        if($data = $this->se_puede_aplicar_detraccion()){ return $data; }


        return $this->validar_tipo_afecta_30($request);

    }

    public function validar_detraccion($request){

        if($this->se_puede_aplicar_detraccion()){ return null; } // NO REGRESO EL MENSAJE PORQUE EL MENSAJE APARECE EN EL PRIMER MÉTODO VER JS

        $tipodetraccion = DetractionType::find($request->detraccion);

        if(empty($tipodetraccion)){return null;}

        $moneda = Currency::select('tipo')->where('id', $request->moneda)->first();



        $valor = (empty($tipodetraccion->valor)) ? 0 : $tipodetraccion->valor;

        $tasa =  $valor / 100;

        // El valor referencial siempre es en moneda nacional

        switch(trim($moneda->tipo)){

            case 'N': 

                $total = $request->total;

            break;

            case 'E': 

                $total = $request->total * $request->tcambio;

            break;

            case 'O': 

                $total = $request->total * $request->tcmoneda;

            break;


        } // FIN SWITCH

        $redondeo =  $this->lnRedondeo;
        if (empty($redondeo)){

            $redondeo = 0;

        }
        
        if ($total > $request->valor_referencial){

            $total_detraccion = formatear_numero($total * $tasa, $redondeo);

        }else{

            $total_detraccion = formatear_numero($request->valor_referencial * $tasa, $redondeo);

        }

        $data['total_detraccion'] = $total_detraccion;
        $data['valor'] = $tipodetraccion->valor;

        return $data;        


    } 


    public function grabar_carritos($docxpagar, $request){
    
        if(empty($request->detraccion)) {return null;}

        $docxpagar_detraccion = DocumentoToPayDetraction::find($docxpagar->id);

        if (empty($docxpagar_detraccion)) {

            $docxpagar_detraccion = new DocumentoToPayDetraction;
            $docxpagar_detraccion->parent_id = $docxpagar->id;
            $docxpagar_detraccion->item = 1;

        }
                
        $docxpagar_detraccion->referencial = (empty($request->valor_referencial)) ? 0 : $request->valor_referencial;
        $docxpagar_detraccion->tipodetraccion_id = $request->detraccion;
        $docxpagar_detraccion->nrodetraccion = (empty($request->numero_detraccion)) ? '' : $request->numero_detraccion;
        $docxpagar_detraccion->fechadetraccion = (empty($request->fecha_detraccion)) ? null : $request->fecha_detraccion;
        $docxpagar_detraccion->totaldetraccion = (empty($request->importe_detraccion)) ? 0 : $request->importe_detraccion;

        $docxpagar_detraccion->save();
    
    }


    public function obtener_refencia_detracion($docxpagar){ // formulario: mov_facturaventa, método: llenar
        
        $referencia_detraccion = '';
        $referencia_detraccion_id = null;

        if(empty($docxpagar->detraccion_id) == false){ // si no es vacio

            $docxpagar_temp = DocumentToPay::select("id", "seriedoc", "numerodoc")->where("id", $docxpagar->detraccion_id)->first();

            if ($docxpagar_temp){

               $referencia_detraccion = $docxpagar_temp->numerodoc;
               $referencia_detraccion_id = $docxpagar_temp->id;

            }
        
        }else{

            if($docxpagar->id){

                $docxpagar_temp = DocumentToPay::select("id", "seriedoc", "numerodoc")->where("detraccion_id", $docxpagar->id)->first();

                if ($docxpagar_temp){

                    $referencia_detraccion = trim($docxpagar_temp->seriedoc) . '-' . trim($docxpagar_temp->numerodoc);
                    $referencia_detraccion_id = $docxpagar_temp->id;

                }

            }

        }

        $data['referencia_detraccion'] = $referencia_detraccion;
        $data['referencia_detraccion_id'] = $referencia_detraccion_id;

        return $data;

    }

    private function se_puede_aplicar_detraccion(){

        $cuenta = '';

        $ruc_emisor = trim(Pempresa::get_parametro('CPE_RUC')->valor); // NÚMERO DE RUC DEL EMISOR
        
        $tercero_cuenta = CustomerAccount::selectparentcmd_codigo($ruc_emisor);

        

        if($tercero_cuenta){

            if($tercero_cuenta->tipocuenta == "D"){

                $cuenta = trim($tercero_cuenta->cuenta);

            }

        }

        return ($cuenta == '') ? response()->json(['errors' => array('detalle' => 'Necesita una cuenta de tipo detracción')],422) : null;


    }
    
    public function validar_tipo_afecta_30($request){

        $tipoafectaigv = AffectIgv::findOrFail($request->tipo_afectacion_igv);

        if ($tipoafectaigv){
            
            return ($tipoafectaigv->codigo >= 30) ? 0 : 1;

        } 

        return 1;


    }

}
