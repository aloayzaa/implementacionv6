<?php


namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class AlmacenProcedure{
//procedures para salida almacen
    public function getAjusteDiferenciaInventario($pId,$pRet)
    {
        return DB::select('CALL generaajustedc_inv(?,?)',array($pId,$pRet));
    }

    public function getValorizaKardex($pDesde, $pHasta, $pRet)
    {
        return DB::select('CALL valorizakardex(?,?,?)',array($pDesde, $pHasta, $pRet));
    }
// procedure para pedido almacen
    public function getPedidoCompra($doc,$tercero,$desde,$phasta,$pId,$pValida)
    {
        return DB::select('CALL w_getpedidocompra(?,?,?,?,?,?)',array($doc,$tercero,$desde,$phasta, $pId,$pValida));
    }

// procedure para ingreso de almacen

    public function getOrdenCompraPorAtender($hasta, $id, $tipomov)
    {
        return DB::select('CALL getordencompraxatender(?,?,?)', array($hasta, $id, $tipomov));
    }

    public function getMovCtaCte($pId)
    {
        return DB::select('CALL getmovctacte(?)',array($pId));
    }

    public function CntIngresoAlmacen($pId,$pRet)
    {
        return DB::select('CALL cntingresoalmacen(?,?)',array($pId,$pRet));
    }

    public function setOrdenCompraEstado($pId,$pTabla)
    {
        return DB::select('CALL setordencompraestado(?,?)',array($pId,$pTabla));
    }

    public function GeneraProvisionAlmacen($pId,$pRet)
    {
        return DB::select('CALL generaprovalmacen(?,?)',array($pId,$pRet));
    }

// procedures para salida de almacen

    public function getStock($palmacen, $pId, $pLote, $pSerie, $pFecha, $pDocAl, $pDocPed)
    {
        return DB::select('CALL getstock(?,?,?,?,?,?,?)',array($palmacen, $pId, $pLote, $pSerie, $pFecha, $pDocAl, $pDocPed));
    }
    public function CntSalidaAlmacen($pId,$pRet)
    {
        return DB::select('CALL cntsalidaalmacen(?,?)',array($pId,$pRet));
    }
    public function getPedidoAlmacenxAtender($pnro,$pdesde,$phasta,$pop,$pid,$pvalida)
    {
        return DB::select('CALL w_getpedidoalmacenxatender(?,?,?,?,?,?)',array($pnro,$pdesde,$phasta,$pop,$pid,$pvalida));
    }

// procedure partes de transformación
    public function CntTransformacionAlmacen($pId,$pRet)
    {
        return DB::select('CALL cnttransforalmacen(?,?)',array($pId,$pRet));
    }

// procedure control inventario
    public function getControlInventario($phasta,$pId,$pTipo)
    {
        return DB::select('CALL rptstockalmacen(?,?,?)',array($phasta,$pId,$pTipo));
    }

    public function gettransferenciaxatender($desde, $hasta, $id, $tercero_id, $grupo){ // clasic, clase: ingresoalmacen, método: gettransferenciaxatender
        //dd($desde . ' / '. $hasta . ' / ' . $id . ' / ' . $tercero_id . ' / '. $grupo);
        return DB::select('CALL gettransferenciaxatender(?,?,?,?,?)',array($desde, $hasta, $id, $tercero_id, $grupo));
        
    }
}
