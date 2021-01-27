<?php


namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class LiquidacionProcedure
{
  public function  cntLiquidacionGasto ($pId,$pRef)
  {
      return DB::select('CALL cntliquidaciongasto(?,?)',
          array($pId,$pRef));
  }


}
