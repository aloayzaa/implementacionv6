<?php

namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class ProvisionesPorPagarProcedure{

    public function getOrdenesCompra($fecha, $id)
    {
        return DB::select('CALL getordencompraxfacturar(?,?)',
            array($fecha, $id));
    }

    public function getMovctacte($pId)
    {
        return DB::select('CALL getmovctacte(?)', array($pId));
    }

    public function getResumenCpe($pId, $fecha, $tipo)
    {
        return DB::select('CALL getresumencpe(?,?,?)', array($pId, $fecha, $tipo));
    }

    public function getCtaCte($ptercero, $porigen, $pfecha, $porigenid, $poperacion)
    {
        return DB::select('CALL getctacte(?,?,?,?,?)',
            array($ptercero, $porigen, $pfecha, $porigenid, $poperacion));
    }

    public function generaCtacte($docxpagar_id, $ventana, $pRet)
    {
        DB::select('CALL generactacte(?,?,?)', array($docxpagar_id, $ventana, $pRet));
        $error = DB::select('select @out as mensaje');
        return $error[0]->mensaje;
    }

    public function cntDocxPagar($docxpagar_id, $pRet)
    {
        DB::select('CALL cntdocxpagar(?, @out)', array($docxpagar_id, $pRet));
        $error = DB::select('select @out as mensaje');
        return $error[0]->mensaje;
    }

    public function cntDocBanco($docxpagar_id, $pRet)
    {
        DB::select('CALL cntdocbanco(?,?)', array($docxpagar_id, $pRet));
        $error = DB::select('select @out as mensaje');
        return $error[0]->mensaje;
    }

    public function cntDocxCobrar($docxpagar_id, $arroba)
    {
        DB::select('CALL cntdocxcobrar(?,?)', array($docxpagar_id, $arroba));
        $error = DB::select('select @out as mensaje');
        return $error[0]->mensaje;
    }

    public function setOrdenCompraEstado($pid, $ptabla)
    {
        return DB::select('CALL setordencompraestado (?,?)', array($pid, $ptabla));
    }

    public function setUpdateIngalm($pId, $pTipo)
    {
        return DB::select('CALL setupdateingalm(?,?)', array($pId, $pTipo));
    }
}
