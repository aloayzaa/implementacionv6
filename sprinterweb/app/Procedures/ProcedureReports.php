<?php
/**
 * Created by PhpStorm.
 * User: anikamagroup
 * Date: 05/03/19
 * Time: 06:06 PM
 */

namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class ProcedureReports
{

// COMRPAS

    /*public static function rptCtaCtexPagar($pTercero, $pOrigen, $pFecha, $pvista)
    {
        return DB::select('CALL rptctactexpagar(?,?,?,?)', array($pTercero, $pOrigen, $pFecha, $pvista));
    }*/
    public static function rptCtaCtexPagar($pTercero, $pOrigen, $pFecha)
    {
        return DB::select('CALL rptctactexpagar(?,?,?)', array($pTercero, $pOrigen, $pFecha));
    }

    public static function rptResumenCompra($pdesde, $phasta, $pVista, $pTipo)
    {
        return DB::select('CALL rptcomprasresumen(?,?,?,?)', array($pdesde, $phasta, $pVista, $pTipo));
    }

    public static function rptDetalleCompra($pdesde, $phasta, $pTipo)
    {
        return DB::select('CALL rptcomprasdetalle(?,?,?)', array($pdesde, $phasta, $pTipo));
    }

    public static function rptSaldosPorPagar($ptercero, $porigen, $pFecha)
    {
        return DB::select('CALL w_rptsaldosxpagar(?,?,?)', array($ptercero, $porigen, $pFecha));
    }
    public function rptDocPorPagar($pId, $pTipo)
    {
        return DB::select('CALL rptdocxpagar(?,?)',array($pId,$pTipo));
    }
// VENTAS

    /*public static function rptCtaCtexCobrar($pTercero, $pOrigen, $pFecha, $pvista)
    {
        return DB::select('CALL rptctactexpagar(?,?,?,?)', array($pTercero, $pOrigen, $pFecha, $pvista));
    }*/

    public static function rptCtaCtexCobrar($pTercero, $pOrigen, $pFecha)
    {
        return DB::select('CALL rptctactexpagar(?,?,?)', array($pTercero, $pOrigen, $pFecha));
    }

    public function rptResumenVenta($pdesde, $phasta, $pVista, $pTipo, $pSucursal)
    {
        return DB::select('CALL rptventasresumen(?,?,?,?,?)', array($pdesde, $phasta, $pVista, $pTipo, $pSucursal));
    }

    public function rptDetalleVentas($pDesde, $pHasta, $pVista, $pSucursal, $pFiltro)
    {
        return DB::select('CALL rptventasdetalle(?,?,?,?,?)', array($pDesde, $pHasta, $pVista, $pSucursal, $pFiltro));
    }

    public function rptSaldosPorCobrar($ptercero, $porigen, $pFecha)
    {
        return DB::select('CALL w_rptsaldosxpagar(?,?,?)', array($ptercero, $porigen, $pFecha));
    }

// ACTIVOS
    public function rptLibroActivoFijo($pdesde, $phasta, $pTipo)
    {
        return DB::select('CALL rptlibroactivofijo(?,?,?)', array($pdesde, $phasta, $pTipo));
    }

    public function rptActivoHistorial($pId, $phasta)
    {
        return DB::select('CALL rptactivohist(?,?)', array($pId, $phasta));
    }

    public function rptDepreFinancieraVsTributaria($pdesde, $phasta)
    {
        return DB::select('CALL rptdepfinavstrib(?,?)', array($pdesde, $phasta));
    }

// ALMACENCES
    public function rptKardex($pId, $pSuc, $pAlm, $pDesde, $pHasta)
    {
        return DB::select('CALL rptkardex(?,?,?,?,?)', array($pId, $pSuc, $pAlm, $pDesde, $pHasta));
    }

    public function rptLibroKardex($pSuc, $pAlm, $pDesde, $pHasta)
    {
        return DB::select('CALL rptlibrokardex(?,?,?,?)', array($pSuc, $pAlm, $pDesde, $pHasta));
    }

    public function rptStockxAlmacen($phasta, $pId, $pTipo)
    {
        return DB::select('CALL rptstockalmacen(?,?,?)', array($phasta, $pId, $pTipo));
    }

    public function rptProvisionCompra($pdesde, $phasta)
    {
        return DB::select('CALL rptprovisioncompra(?,?)', array($pdesde, $phasta));
    }

    public function rptMovAlmacenxTipo($pSuc, $pAlm, $pdesde, $phasta, $pgrupo, $pTipo, $pPdto)
    {
        return DB::select('CALL rptmovalmacenxtipo(?,?,?,?,?,?,?)', array($pSuc, $pAlm, $pdesde, $phasta, $pgrupo, $pTipo, $pPdto));
    }

// TESORERIA

    public function rptLibroBanco($pbanco, $pcuenta, $pdesde, $phasta, $ptipo)
    {
        return DB::select('CALL w_rptlibrobanco(?,?,?,?,?)',
            array($pbanco, $pcuenta, $pdesde, $phasta, $ptipo));
    }

    public function rptConsolidadoBancos($pbanco, $pdesde, $phasta, $pMon)
    {
        return DB::select('CALL rptconsolidadobancos(?,?,?,?)',
            array($pbanco, $pdesde, $phasta, $pMon));
    }

    public function rptChequesEmitidos($pbanco, $pdesde, $phasta)
    {
        return DB::select('CALL rptcheques_emitidos(?,?,?)',
            array($pbanco, $pdesde, $phasta));
    }

// CONTABILIDAD
    public function rptMovimientoxCuenta($pdesde, $phasta, $pcuenta)
    {
        return DB::select('CALL rptmovimientoxcuenta(?,?,?)',
            array($pdesde, $phasta, $pcuenta));
    }

    public function rptLibroDiario($pdesde, $phasta, $ptipo)
    {
        return DB::select('CALL rptlibrodiario(?,?,?)',
            array($pdesde, $phasta, $ptipo));
    }

    public function rptLibroMayor($pdesde, $phasta, $ptipo)
    {
        return DB::select('CALL rptlibromayor(?,?,?)',
            array($pdesde, $phasta, $ptipo));
    }

    public function rptValidacionAsientos($pdesde, $phasta)
    {
        return DB::select('CALL rptvalidaasiento(?,?)',
            array($pdesde, $phasta));
    }

    public function rptLibroId10($pperiodo, $pcuenta)
    {
        return DB::select('CALL rptlibroib10(?,?)',
            array($pperiodo, $pcuenta));
    }

    public function rptLibroId12($pperiodo, $pcuenta, $pcuenta2)
    {
        return DB::select('CALL rptlibroib12(?,?,?)',
            array($pperiodo, $pcuenta, $pcuenta2));
    }

    public function rptBalanceComprobacion($pdesde, $phasta, $pmoneda, $pdigitos, $pcierre)
    {
        return DB::select('CALL rptbalcomprobacion(?,?,?,?,?)',
            array($pdesde, $phasta, $pmoneda, $pdigitos, $pcierre));
    }

    public function rptEstadoFinancieroAnual($pdesde, $phasta, $pId, $pmoneda)
    {
        return DB::select('CALL rpteeff(?,?,?,?)',
            array($pdesde, $phasta, $pId, $pmoneda));
    }

    public function rptLibroBanboDetalle($ptipo, $pperiodo)
    {
        return DB::select('CALL rptlibrobanco_detalle(?,?)',
            array($ptipo, $pperiodo));
    }

    public function rptSaldoxCuenta($pdesde, $phasta, $pcuenta)
    {
        return DB::select('CALL rptsaldoxcuenta(?,?,?)',
            array($pdesde, $phasta, $pcuenta));
    }

    //TRIBUTARIA
    public function rptPdtOperacionesTerceros($pPeriodo, $ptipo)
    {
        return DB::select('CALL rptpdt(?,?)', array($pPeriodo, $ptipo));
    }

    public function rptDiarioRetencion($pdesde, $phasta, $ptipo)
    {
        return DB::select('CALL rptpdt_diarioretencion(?,?,?)', array($pdesde, $phasta, $ptipo));
    }

    public function rptComprasRetencion($pdesde, $phasta, $ptipo)
    {
        return DB::select('CALL rptcomprasretencion(?,?,?)', array($pdesde, $phasta, $ptipo));
    }

    public function rptPlanillaElectronica($pPeriodo, $ptipo)
    {
        return DB::select('CALL rptplanillaelectronica(?,?)', array($pPeriodo, $ptipo));
    }

    public function rptVentasRegistro($pdesde, $phasta, $ptipo)
    {
        return DB::select('CALL rptventasregistro(?,?,?)', array($pdesde, $phasta, $ptipo));
    }

    public function rptComprasRegistro($pdesde, $phasta)
    {
        return DB::select('CALL rptcomprasregistro(?,?)', array($pdesde, $phasta));
    }

}
