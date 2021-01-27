<?php


namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class ContabilidadProcedure
{
    public function getAjusteDiferenciaCambio($pId, $pRet)
    {
        return DB::select('CALL generaajustedc(?,?)', array($pId, $pRet));
    }

    public function getAperturaxCuenta($pId, $pCuenta)
    {
        return DB::select('CALL getaperturaxcuenta(?,?)', array($pId, $pCuenta));
    }

    public function generaCierre($pId, $pRet)
    {
        return DB::select('CALL generacierre(?,?)', array($pId, $pRet));
    }

    public function generaCtacte($pId, $ptabla, $pRet)
    {
        return DB::select('CALL generactacte(?,?,?)', array($pId, $ptabla, $pRet));
    }

    public function cntEsAutomatica($pId, $pRet)
    {
        return DB::select('CALL cntautomaticas(?,?)', array($pId, $pRet));
    }

    public function prcCentralizaContabilidad($pdesde, $phasta, $ptabla, $porigen)
    {
        return DB::select('CALL prccentraliza(?,?,?,?)', array($pdesde, $phasta, $ptabla, $porigen));
    }

    public function rptKardex($pId, $pSuc, $pAlm, $pDesde, $pHasta)
    {
        return DB::select('CALL rptkardex(?,?,?,?,?)',array($pId, $pSuc, $pAlm, $pDesde, $pHasta));
    }
}
