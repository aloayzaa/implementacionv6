<?php

namespace App\Http\Traits\Controls;

use Illuminate\Support\Facades\DB;

trait Movement
{
    public $t_estados_aprobar = array('CERRADO', 'ANULADO', 'AT.TOTAL', 'AT.PARCIAL', 'FACTURADO', 'FT.PARCIAL');
    public $t_estados_borrar = array('CERRADO', 'AT.TOTAL', 'AT.PARCIAL', 'FACTURADO', 'FT.PARCIAL');
    use StateMovement, ApproveMovement, DeleteMovement, CancelMovement;
/*
    function t_validar_estado($request){
        //ver frmmovimiento validar_estado
        //$retorno = 1;
        $modulo = '';

        //if($lcierramod){

        //}
        $retorno = $this->t_validar_estado_mov($request);
        return $retorno;
    }*/

 /*   function t_validar_estado_mov($request){
        //ver frmmovimiento validar_estado
        $tabla = DB::table($this->tabla_cabecera)->where('id',$request->id)->first();
        $valor = trim($tabla->estado);
        switch($valor){
            case 'ACTIVO':
            case 'APROBADO':
                $lok = 1;
                break;
            case 'ANULADO':
            case 'CERRADO':
            case 'BLOQUEADO':
            case 'FACTURADO':
                $lok =2;
                break;
            default:
                $lok =0;
                break;
        }
        return $lok;
    }*/

    function contabilizar(){
        return '';
    }

    function setestadoref(){
        return '';
    }

    function centralizar(){
        return '';
    }

    function contabilizar_centralizar_setestadoref(){
        return '';
    }
}
