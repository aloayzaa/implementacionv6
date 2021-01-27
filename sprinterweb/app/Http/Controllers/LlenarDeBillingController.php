<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouses\Entities\WarehouseIncome;
use App\Documents\Entities\Commercial;
use Illuminate\Database\Eloquent\Model;

class LlenarDeBillingController extends Controller
{

    private $docxpagar; // REGISTRO DEL DOCXPAGAR
    private $id; // CREAR O EDITAR
    private $request; // DATA FORMULARIO

    public function __construct($id)
    {

        $this->id = $id;

    }


    public function llenarde_guardar(Model $docxpagar, $request){ 

        $this->docxpagar = $docxpagar;
        $this->request = $request;

        if ($this->id == 0){ // CREAR 

            $this->ingreso_almacen_create();

        } else { // EDITAR

            $this->ingreso_almacen_update();

        }


    }


    private function ingreso_almacen_create(){

        if ( $this->docxpagar->noalmacen == 0 ) : return null; endif;

        $ingreso_almacen = WarehouseIncome::find($this->docxpagar->ingresoAlmacen[0]['id']);
        
        if ($ingreso_almacen){
            if ($this->request->serierem != '' && $this->request->nrorem != ''){

                $ingreso_almacen->tipo = "S";
                $ingreso_almacen->ventana = "mov_guiaremision";
                $ingreso_almacen->estado = "ACTIVO";
                $ingreso_almacen->ordencompra_id = null;
                $ingreso_almacen->seriedoc = $this->request->serierem;
                $ingreso_almacen->numerodoc = $this->request->nrorem;
                $ingreso_almacen->save();

            }

        }

    }

    private function ingreso_almacen_update(){

        if ( $this->docxpagar->noalmacen == 0 ) : return null; endif;

        $ingreso_almacen = WarehouseIncome::find($this->docxpagar->ingresoAlmacen[0]['id']);
        
        if ($ingreso_almacen){
            if ($this->request->serierem != '' && $this->request->nrorem != ''){

                $ingreso_almacen->tipo = "S";
                $ingreso_almacen->ventana = "mov_guiaremision";
                $ingreso_almacen->unegocio_id = 1; 
                $ingreso_almacen->estado = "ACTIVO";
                $ingreso_almacen->ordencompra_id = null;
                $ingreso_almacen->seriedoc = $this->request->serierem;
                $ingreso_almacen->numerodoc = $this->request->nrorem;
                $ingreso_almacen->save();

            }

        }

    }

    public function obtener_data_edit(Model $docxpagar){

        $this->docxpagar = $docxpagar;

        $segun_noalmacen =  $this->segun_noalmacen();
        
        return $segun_noalmacen;

    }


    private function segun_noalmacen(){

        $data['ocompra'] = "";
        $data['referencia_salidaalmacen_id'] = null;
        $data['serierem'] = "";
        $data['numerorem'] = "";
        $data['ventana'] = "";

        if ($this->docxpagar->noalmacen == 1){ // SI NO ES POR REFERENCIA

            $salida_almacen = WarehouseIncome::selectrefcmd_x($this->docxpagar->id);
            
            $salida_almacen = collect($salida_almacen)->first(); // SI NOALMACEN ES 1 - EN ESE CASO SIEMPRE HABRÁ UN REGISTRO

            if($salida_almacen){

                if($salida_almacen->numero > 0){
                    
                    $documentocom = Commercial::findOrFail($this->docxpagar->documento_id);

                    $codigo_ocompra = trim($salida_almacen->periodo) . '-' . str_pad($salida_almacen->numero, 5, '0', STR_PAD_LEFT);

                    $data['ocompra'] = ($documentocom->codsunat == "07") ? "IA " . $codigo_ocompra : "SA " . $codigo_ocompra;

                    $data['referencia_salidaalmacen_id'] = $salida_almacen->id;

                    $data['serierem'] = $salida_almacen->seriedoc;

                    $data['numerorem'] = $salida_almacen->numerodoc;

                    $data['ventana'] = $salida_almacen->ventana;


                }


            }


        }else{ // POR REFERENCIA

            if ($this->docxpagar->id > 0){

                $salida_almacen = WarehouseIncome::referencia_salidaalmacen_facturacion($this->docxpagar->id);
                
                if($salida_almacen){

                    $documentocom = Commercial::findOrFail($this->docxpagar->documento_id);

                    $codigo_ocompra = trim($salida_almacen->periodo) . '-' . str_pad($salida_almacen->numero, 5, '0', STR_PAD_LEFT);

                    $data['ocompra'] = ($documentocom->codsunat == "07") ? "IA " . $codigo_ocompra : "SA " . $codigo_ocompra;

                    $data['referencia_salidaalmacen_id'] = $salida_almacen->id;

                    $data['serierem'] = $salida_almacen->seriedoc;

                    $data['numerorem'] = $salida_almacen->numerodoc;

                    $data['ventana'] = $salida_almacen->ventana;

                }


            }


        }

        return $data;

    }

}
