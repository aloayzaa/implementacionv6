<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Panel\Companies\Entities\Pempresa;
use App\DocumentToPay\Entities\DocumentToPayCPE;
use App\DocComunicacion\DocComunicacion;

class CpeBillingController extends Controller
{
    
    private $lcpe;
    private $docxpagar_id; // ID DEL DOCXPAGAR ID

    public function __construct($docxpagar_id, $lcpe)
    {

        $this->docxpagar_id = $docxpagar_id;
        $this->lcpe = $lcpe;

    }


    public function listado_cpe(){ // formulario: mov_facturaventa, método llenarde

        $array_enviar = array();

        if ($this->lcpe == 1){


            $docxpagar_cpe = DocumentToPayCPE::selectparentcmd($this->docxpagar_id);

            if($docxpagar_cpe){
                
                foreach(range(1, 5) as $i){

                    $item = $i;
                    $fecha = "";
                    $id = 0;

                    switch($i){

                        case 1:

                            $fecha = $docxpagar_cpe->fecha_firma;
                            $glosa = 'Generado/Firmado';

                        break;

                        case 2:

                            $fecha = $docxpagar_cpe->fecha_envio;
                            $glosa = 'Enviado a SUNAT';

                        break;

                        case 3:

                            $fecha = $docxpagar_cpe->fecha_recep;
                            $glosa = 'CDR SUNAT ' . $docxpagar_cpe->respuesta_dsc;

                        break;

                        case 4:

                            $fecha = $docxpagar_cpe->email_fecha;
                            $glosa = 'Email: ' . $docxpagar_cpe->email_destino;

                        break;

                        case 5:

                            
                            if ($docxpagar_cpe->doccomunicacion_id > 0){

                                $doccomunicacion = DocComunicacion::find($docxpagar_cpe->doccomunicacion_id);

                                if (trim($doccomunicacion->respuesta_cod) == 0){

                                    $id = $doccomunicacion->id;
                                    $fecha = (empty($doccomunicacion->fecha_recep)) ? $doccomunicacion->fecha_envio : $doccomunicacion->fecha_recep;
                                    $glosa = $doccomunicacion->respuesta_dsc;


                                }

                            }

                        break;                        

                    } // FIN SWITCH

                    if($fecha != ''){            

                        $array = array(
                            "id" => $id,
                            "item" => $item,
                            "fecha" => $fecha,
                            "glosa" => $glosa
                        );
                        $array_enviar[] = $array;


                    }

        
                } // FIN FOREACH


            } // EXISTE DOCXPAGAR_CPE ?


        } // ESTA ACTIVO LCPE ?

        return $array_enviar;

    }

}
