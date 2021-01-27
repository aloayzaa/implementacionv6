<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouses\Entities\WarehouseIncome;

class ReferenceGuideBillingController extends Controller
{
    private $lcpe;
    private $docxpagar_id; // ID DEL DOCXPAGAR CREAR - EDITAR

    private $serierem; // SERIEREM RECIBIDO
    private $numerorem; // NUMEROREM RECIBIDO

    private $serierem_retorno; // SERIEREM DE RETORNO
    private $numerorem_retorno; // NUMEROREM DE RETORNO

    public function __construct($lcpe, $docxpagar_id, $serierem, $numerorem)
    {

        $this->lcpe = $lcpe;
        $this->docxpagar_id = $docxpagar_id;

        $this->serierem = $serierem;
        $this->numerorem = $numerorem;

    }    

    public function evaluar_serierem(){

        if ($this->lcpe == 1){

            $this->obtener_serierem_retorno_cpe();

        }else{

            $this->obtener_serierem_retorno();

        }
        
    }

    private function obtener_serierem_retorno_cpe(){


        if ( ctype_alpha(substr($this->serierem, 0, 1)) ){

            $serierem_temp = substr($this->serierem, 1, 3);

            $this->serierem_retorno = strtoupper(substr($this->serierem, 0, 1)) . str_pad($serierem_temp, 3, "0", STR_PAD_LEFT);

        }else{

            $this->serierem_retorno = str_pad(trim($this->serierem), 4, "0", STR_PAD_LEFT);

        }


    }

    private function obtener_serierem_retorno(){

        $this->serierem_retorno = str_pad(trim($this->serierem), 5, "0", STR_PAD_LEFT);

    }

    public function obtener_serierem_numerorem(){

        $serierem_temp = rtrim($this->serierem_retorno);
        
        $numerorem_temp = ( $this->numerorem == '00000000' ) ? '' : trim($this->numerorem);
        $this->numerorem_retorno = $numerorem_temp;

        // CREAR Ó ( EDITAR Y NÚMERO IGUAL A VACIO )
        if ( $this->docxpagar_id == 0 || ( $this->docxpagar_id != 0 && empty($numerorem_temp) ) ){

            $numerorem_temp = WarehouseIncome::ultimonumero($serierem_temp, 'S');
            $numerorem_temp->numerodoc += 1;
            $this->numerorem_retorno = str_pad($numerorem_temp->numerodoc, 8, '0', STR_PAD_LEFT);
            

        }
        
        if ( $data = $this->validar_ingreso_almacen($serierem_temp, $this->numerorem_retorno) ) {return $data;}



        return $this->obtener_serierem_numerorem2();

    }


    private function obtener_serierem_numerorem2(){

        $data['serierem'] = $this->serierem_retorno;
        $data['numerorem'] = $this->numerorem_retorno;

        return $data;

    }

    private function validar_ingreso_almacen($serierem_temp, $numerorem_temp){

        // SERIEDOC Y NUMERODOC YA ESTÁ REGISTRADO EN UN INGRESO ALMACEN ?
        $ingreso_almacen = WarehouseIncome::validadoc($serierem_temp, $numerorem_temp, "S"); 

        if ($ingreso_almacen) {
            
            // SI EXISTE LA SERIEDOC Y NUMERODOC DEBE PERTENECER AL INGRESO DE ALMACEN CORRESPONDIENTE
            $ingreso_almacen_id = $this->ingreso_almacen_id();

            
            if ( $ingreso_almacen->id > 0 && $ingreso_almacen->id != $ingreso_almacen_id ) { 

                return response()->json(['errors' => array('detalle' => getMesaage(28) )],422);
            
            }

        }
        
        return null;
    }

    public function obtener_numerorem(){
        
        $serierem_temp = (empty($this->serierem)) ? '' : rtrim($this->serierem);
        $numerorem_temp = (empty($this->numerorem)) ? '' : rtrim($this->numerorem);


        if ( $data = $this->validar_ingreso_almacen($serierem_temp, $numerorem_temp) ) {return $data;}

        
        $data['numerorem'] = str_pad($this->numerorem, 8, '0', STR_PAD_LEFT);

        return $data;

    }


    private function ingreso_almacen_id(){ // INGRESO ALMACEN ACTUAL

        $ingreso_almacen = WarehouseIncome::select('id')->where('docxpagar_id', $this->docxpagar_id)->first(); 

        return ($ingreso_almacen) ? $ingreso_almacen->id : 0;

    }

}