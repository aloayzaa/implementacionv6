<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Procedures\DocumentToPayProcedure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Panel\Companies\Entities\Pempresa;

class ReporteCmdController extends Controller
{

    private $model; // ENTIDAD DE ORIGEN
    private $documenttopayprocedure; // DOCXPAGAR PROCEDIMENTOS
    private $reportecmd; // DATA DE LA CONSULTA O PROCEDIMENTO
    private $id; // ID DEL DOCUMENTO DE ORIGEN

    private $nro; // NÚMERO DE REGISTRO

    public function __construct(Model $model = null, $id, $nro)
    {   
        $this->model = $model;
        $this->id = $id;
        $this->nro = $nro;
    }    

    public function reportecmd(){

        switch (true){

            case strtolower($this->model->ventana) == 'mov_facturaventa':

                $this->reportecmd_docxpagar(0);

            break;


            case strtolower($this->model->ventana) == 'mov_facturaveh':

                $this->reportecmd_docxpagar(0);

            break;


            case strtolower($this->model->ventana) == 'mov_facturatransporte':

                $tipo = (Pempresa::get_parametro('TRS_FMTFACT')->valor == 1) ? 21 : 2;

                $this->reportecmd_docxpagar($tipo);

            break;
            
            
            case strtolower($this->model->ventana) == 'mov_facturaexporta':

                $this->reportecmd_docxpagar(8);

            break;
            
            
            case strtolower($this->model->ventana) == 'mov_retencion':

                $this->reportecmd_docxpagar(7);

            break;


            case strtolower($this->model->ventana) == 'mov_doccomunicacion_baja':

                $this->reportecmd_baja('doccomunicacion');

            break;


            case strtolower($this->model->ventana) == 'mov_guiaremision':

                $this->reportecmd_baja('ingresoalmacen');

            break;


            case strtolower($this->model->ventana) == 'mov_doccomunicacion_resumen':

                $this->reportecmd_resumen();

            break;


        }


        return $this->reportecmd;

    }

    private function reportecmd_docxpagar($tipo){
        
        $this->documenttopayprocedure = new DocumentToPayProcedure;

        $this->reportecmd = $this->documenttopayprocedure->reportecmd($this->id, $tipo); // DOCUMENTO CON DETALLES

    }


    private function reportecmd_baja($tabla){

        $this->reportecmd = DB::select('CALL rptreportecmd(?,?)', array($this->id, $tabla));

    }


    private function reportecmd_resumen(){

        $this->reportecmd = DB::select('CALL rptdoccomunicacion(?,?)', array($this->id, $this->nro));

    }
    

}
