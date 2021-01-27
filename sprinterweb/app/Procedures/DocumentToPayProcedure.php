<?php

namespace App\Procedures;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DocumentToPayProcedure{

    public function validafecha($documento, $serie, $numero, $fecha, $rpta) // clase docxpagar - método validafecha
    {
        DB::select('CALL validafecha(?,?,?,?,?)', array($documento, $serie, $numero, $fecha, $rpta));
        $respuesta = DB::select('select @pRet as respuesta');
        return $respuesta[0]->respuesta;
    }

    public function reportecmd($id, $tipo){
       
       $respuesta = DB::select('CALL rptdocxpagar(?,?)', array($id, $tipo));
       return $respuesta;

    }

    public function setanulacpe($docxpagar_id){

        DB::select('CALL setanulacpe(?,?)', array($docxpagar_id, Session::get('usuario')));

    }

    public function rptvalidacpe($desde, $hasta){

       return DB::select('CALL rptvalidacpe(?,?,?)', array($desde, $hasta, 'F'));

    }

}
