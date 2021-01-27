<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Procedures\AlmacenProcedure;
use App\DocumentToPay\Entities\DocumentToPayDetail;

class AuxiliaryApplyNCController extends Controller // clasic, formulario : AUX_APLICANC
{
    protected $tercero_id;
    protected $origen;
    protected $tabla;
    protected $nombre_tabla;
    protected $factor;
    protected $chkguia;
    protected $ccuenta;

    public function __construct($tercero_id, $origen, Model $tabla) // método INIT
    {


        $this->tercero_id = $tercero_id;

        $obtener_origen_chkguia = $this->obtener_origen_chkguia($origen);
        $this->origen = $obtener_origen_chkguia['origen'];
        $this->chkguia = $obtener_origen_chkguia['chkguia'];

        $this->tabla = $tabla;

        $obtener_nombretabla_ccuenta = $this->obtener_nombretabla_ccuenta();
        $this->nombre_tabla = $obtener_nombretabla_ccuenta['nombre_tabla'];
        $this->ccuenta = $obtener_nombretabla_ccuenta['ccuenta'];

        $this->factor = $this->obtener_factor($origen);

    }

    private function obtener_origen_chkguia($origen){

        $chkguia = 0; //evaluar
        $origen_env = substr($origen, 0, 1);
        
        if ($origen_env == 'G') {

            $origen_env = 'C';
            $chkguia = 1; 
        }
        
        $data['chkguia'] = $chkguia;
        $data['origen'] = $origen_env;

        return $data;
    }


    private function obtener_factor($origen){

        $factor = 1;        

        if ( empty(substr($origen, 1, 1)) ){ // si el segundo valor es vacío

            $factor = 1;

        } else {

            if ( substr($origen, 1, 1) == "-"){

                $factor = -1;

            }else{

                $factor = 1;

            }
        }

        return $factor;
    }

    private function obtener_nombretabla_ccuenta(){

        $nombre_tabla = 'docxpagar';
        $ccuenta = '';

        if ( substr($this->tabla->getTable(), -3) == '609' ) {

            $ccuenta = '609';
            $nombre_tabla = substr($tabla, strlen($tabla) - 3); // PROBAR EN CASO EMPIECE CON CRS BORRAR ESA PARTE

        }

        $data['nombre_tabla'] = $nombre_tabla;
        $data['ccuenta'] = $ccuenta;
        return $data;
    }

    public function buscar_notacreditodebito_cabecera($desde, $hasta, $serie, $numero, $docxpagar_id){ // clasic, objeto: btnrefresh, método: click

        $data = null;
        
        if ( $this->nombre_tabla != 'docxpagar'){

            // desarrollarlo cuando se necesite

        }else {

            if ( !empty($desde) ) {

                $documentos_cabecera =  $this->tabla->buscar_nota_creditodebito_desde($this->tercero_id, $desde, $hasta, $serie, $numero, $this->origen, $this->factor, $docxpagar_id); // cuando existe le fecha desde
    
            } else {
                //dd($this->tercero_id . ' / ' . $hasta . ' / ' . $serie);
                $documentos_cabecera =  $this->tabla->buscar_nota_creditodebito($this->tercero_id, $hasta, $serie, $numero, $this->origen, $this->factor, $docxpagar_id); // cuando NO existe le fecha desde
    
            }

        }

        
        if ( $documentos_cabecera == null ) {  return $documentos_cabecera;  }
        
        return $documentos_cabecera;
        
         

    }

    public function buscar_notacreditodebito_detalle($documento_id){
        
        $array_enviar = array();
            
        if ($this->origen == "C"){

            $AlmacenProcedure = new AlmacenProcedure;

            if ($documento_id){

                if ( $this->chkguia == 1) {

                    $detalles = $AlmacenProcedure->gettransferenciaxatender(date("Y-m-d"), date("Y-m-d"), $documento_id, $this->tercero_id, 'G');
    
                }else{
                    
                    $detalles = $AlmacenProcedure->gettransferenciaxatender(date("Y-m-d"), date("Y-m-d"), $documento_id, $this->tercero_id, 'F');
                    
                }

            }

        }else{
            
            if ($documento_id){

                $detalles =  DocumentToPayDetail::selectidcmd($d->id);

            }

        }
        
        if (isset($detalles)){

            foreach ($detalles as $detalle) {
            
                if ($this->origen == 'P' && !empty($d->id)){

                    // desarrollarlo cuando se necesite

                }else {
                    
                    $prd_cod = $detalle->prd_cod;
                    $cantidad = $detalle->cantidad;

                    if ( !($this->nombre_tabla != 'docxpagar' || $this->origen == 'C') ){
                        
                        $prd_cod =  $detalle->cuenta;
                        $cantidad = $detalle->importe;

                    }
                    
                    $array = array(
                        "id" => $detalle->id,
                        "item" => $detalle->item,
                        "producto_id" => (empty($detalle->producto_id)) ? null : $detalle->producto_id,
                        "umedida_id" => (empty($detalle->umedida_id)) ? null : $detalle->umedida_id,
                        "ume_cod" => (empty($detalle->ume_cod)) ? '' : $detalle->ume_cod,
                        "cuenta_id" => (empty($detalle->cuenta_id)) ? null : $detalle->cuenta_id,
                        "prd_cod" => (empty($prd_cod)) ? '' : $prd_cod,
                        "prd_dsc" => (empty($detalle->glosa)) ? '' : trim($detalle->glosa),
                        "cantidad" => $cantidad,
                        "esafecto" => (empty($detalle->esafecto)) ? 0 : $detalle->esafecto,                        
                        "consumo" => (empty($detalle->consumo)) ? 0 : $detalle->consumo,
                        "cuenta" => (empty($detalle->cuenta)) ? '' : $detalle->cuenta,                        
                        "centrocosto_id" => (empty($detalle->centrocosto_id)) ? null : $detalle->centrocosto_id,
                        "cco_cod" => (empty($detalle->cco_cod)) ? '' : $detalle->cco_cod,
                        "cco_dsc" => (empty($detalle->cco_dsc)) ? '' : $detalle->cco_dsc,
                        "actividad_id" => (empty($detalle->actividad_id)) ? null : $detalle->actividad_id,
                        "act_cod" => (empty($detalle->act_cod)) ? '' : $detalle->act_cod,
                        "act_dsc" => (empty($detalle->act_dsc)) ? '' : $detalle->act_dsc,
                        "tipoprecio" => $detalle->tipoprecio,
                        "precio" => $detalle->precio,
                        "preciolista" => $detalle->preciolista,
                        "importe" => $detalle->importe,
                        "lote" => (empty($detalle->lote)) ? '' : $detalle->lote,
                        "vencelote" => (empty($detalle->vencimiento)) ? '' : $detalle->vencimiento,
                        "serie" => (empty($detalle->serie)) ? '' : $detalle->serie,                        
                        "atendido" => 0,
                        "aplicar" => $detalle->cantidad - $detalle->atendido,
                        "stock" => 0,
                        "referencia_id" => (empty($detalle->referencia_id)) ? 0 : $detalle->referencia_id,
                        "docrefer" => (empty($detalle->docrefer)) ? '' : $detalle->docrefer,
                        "tipoafectaigv_id" => (empty($detalle->tipoafectaigv_id)) ? null : $detalle->tipoafectaigv_id,
                        "afeigv_cod" => (empty($detalle->afeigv_cod)) ? '' : $detalle->afeigv_cod,
                        "proyecto_id" => (empty($detalle->proyecto_id)) ? null : $detalle->proyecto_id,
                        "pry_cod" => (empty($detalle->pry_cod)) ? '' : $detalle->pry_cod,
                        "pry_dsc" => (empty($detalle->pry_dsc)) ? '' : $detalle->pry_dsc,
                    );
                    $array_enviar[] = $array;

                }

            }


        }

        //dd($array_enviar);
        return $array_enviar;
    }
}
