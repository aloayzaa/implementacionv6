<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Panel\Companies\Entities\Pempresa;

class CpeGetStatusController extends Controller
{
    protected $lcuser;
    protected $lcpwd;
    protected $lnTipoServ;

    public function __construct()
    {
        $lcuser = trim(Pempresa::get_parametro('SOL_USUARIO')->valor);
        $lcpwd = trim(Pempresa::get_parametro('SOL_CLAVE')->valor);
        $lnTipoServ = intval(Pempresa::get_parametro('CPE_SERVIDOR')->valor);

    }
    
    public function status_(){
        
        switch($this->lnTipoServ){
            case 1:
                $lcUrl = "https://ose.bizlinks.com.pe/ol-ti-itcpe/billService?wsdl";
            case 2:
                if(in_array($lctipodoc, array('RR', 'RP'))){
                    $lcUrl = "https://e-factura.sunat.gob.pe/ol-ti-itemision-otroscpe-gem/billService";
                }else{
                    $lcUrl = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService";
                }
            case 3: 
                $lcUrl = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            default:
                return response()->json(['errors' => array('No se ha configurado tipo de servidor para envío')],422);
        }
    }

    public function status($cod, $dsc, $ticket, $doc){
        $lcRespuesta = $cod. ' ' . $dsc;

        $cpeticket = $ticket;

        $cpedoc = $doc;

        $cpecdrcod = $cod;

        $cpecdrdsc = $dsc;

        switch($cpecdrcod){
            case '0098':
            case '98':
                $cpecdrdsc = $cpecdrdsc . ' se encuentra en proceso. Ticket Nro: ['. $cpeticket . "]";
            case '0099':
            case '99':
                $cpecdrdsc = $cpecdrdsc . ' presenta errores, Ticket Nro: [' . $cpeticket . "]";
            case '0':
                $cpecdrdsc = $cpecdrdsc . $cpedoc . " ha sido aceptada";
            default: 
                $cpecdrdsc = $lcRespuesta;
        }
        $lcErr = '';
        if(empty(trim($lcRespuesta))){
            $lcErr = 'Error de respuesta';
        }

        return $lcErr;
    }
}
