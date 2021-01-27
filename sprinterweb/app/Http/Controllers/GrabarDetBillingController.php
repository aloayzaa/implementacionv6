<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Procedures\ProvisionesPorPagarProcedure;
use Illuminate\Support\Facades\DB;

class GrabarDetBillingController extends Controller
{

    private $docxpagar; // REGISTRO DOCXPAGAR 
    private $warehouseReferenceBilling; // OBJETO REFERENCIA DE SALIDA ALMACEN

    public function __construct($docxpagar)
    {
        $this->docxpagar = $docxpagar;

        $this->provisionesPorPagarProcedure = new ProvisionesPorPagarProcedure;

    }


    public function grabardet($warehouseReferenceBilling){

        // INICIALIZAR OBJETO REFERENCIA DE SALIDA ALMACEN
        $this->warehouseReferenceBilling = $warehouseReferenceBilling; 


        $this->provisionesPorPagarProcedure->setupdateingalm($this->docxpagar->id, 'AA');
        

        if ($this->docxpagar->noalmacen == 0){

            // ELIMINAR LA SALIDA DE ALMACEN CREADO EN EL SETUPDATEINGALM O EN EL CURSOR (PREGUNTAR)
            
            DB::table('ingresoalmacen')->where('docxpagar_id', $this->docxpagar->id)->delete();

            // ACTUALIZAR SALIDA ALMACEN OBTENIDA DE TXTOCOMPRA
            $this->actualizar_salidaalmacen_ocompra();

        }


    }

    private function actualizar_salidaalmacen_ocompra(){

        
        $estado = $this->docxpagar->estado;

        $referencias_salidaalmacen = $this->warehouseReferenceBilling->obtener_tmpguias();

        foreach($referencias_salidaalmacen as $salidaalmacen){

            
            $docxpagar_id = ($estado == 'ANULADO') ? null : trim($this->docxpagar->id);

            DB::table('ingresoalmacen')
            ->where('id', $salidaalmacen->id)
            ->update(['docxpagar_id' => $docxpagar_id]);

        }

    }

}
