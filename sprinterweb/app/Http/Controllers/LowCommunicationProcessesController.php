<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocComunicacion\DocComunicacion;
use App\Panel\Companies\Entities\Pempresa;
use App\DocComunicacion\DocComunicacionCPE;
use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DocComunicacion\DocComunicacionDetalle;
use Carbon\Carbon;
use App\SunatZip\ZipFly;
use App\DocumentToPay\Entities\DocumentToPayCPE;


class LowCommunicationProcessesController extends Controller
{
    private $respuesta;
    private $ticket;
    private $id;
    private $xml_archivo;

    protected $cperuc;
    protected $cperuta;
    protected $cpetiposerv;
    protected $cpevermsg;
    protected $cpeabrirpdf;
    protected $cpedoc;
    protected $cpehash;
    protected $cpevalorfirma;
    protected $cpefilexml;
    protected $cpefilepdf;
    protected $cpefilecdr;
    protected $cpefilexmlzip;
    protected $cpefilecdrzip;
    protected $cpecdrcod;
    protected $cpecdrdsc;
    protected $cpeticket;

    protected $cpegetstatus;
    private $glosa;
    private $doccominicacion_cpe_;// objeto DocComunicacion_CPE (crear o actualizar - registro)

    private $item;
    private $fecha_recep;
    private $nombre_xml;

    public function __construct($id, $respuesta, $ticket, $codigo_hash, $descripcion_hash)
    {
        $this->ticket = $ticket;
        $this->respuesta = $respuesta;
        $this->id = $id;

        $this->cperuc = trim(Pempresa::get_parametro('CPE_RUC')->valor);
        $this->cperuta = trim(Pempresa::get_parametro('CPE_REPOSIT')->valor);
        $this->cpetiposerv = intval(trim(Pempresa::get_parametro('CPE_SERVIDOR')->valor));
        $this->cpevermsg = (intval(trim(Pempresa::get_parametro('CPE_VERMSG')->valor)) == 1) ? true : false;
        $this->cpeabrirpdf  = (intval(trim(Pempresa::get_parametro('CPE_REPOSIT')->valor)) == 1) ? true : false;
        $this->cpedoc = '';
        $this->cpehash = '';
        $this->cpevalorfirma = '';
        $this->cpefilexml = '';
        $this->cpefilepdf = '';
        $this->cpefilecdr = '';
        $this->cpefilexmlzip = '';
        $this->cpefilecdrzip = '';
        $this->cpecdrcod = $codigo_hash;
        $this->cpecdrdsc = $descripcion_hash;
        $this->cpeticket = '';

        $this->fecha_recep = '';

        $this->cpegetstatus = new CpeGetStatusController;

        $this->glosa = '';
        $this->item = 0;
    }

    public function processes(){
        if($this->respuesta){
            //RECEPCION DE SUNAT
            $estado = 'EN PROCESO';
            $llNuevo = false;
            $doccomunicacion_detalle = DocComunicacionDetalle::where('parent_id', intval($this->id))->get();
            $this->item = count($doccomunicacion_detalle);
            

            $doccomunicacion_cpe = $this->selectparentcmd($this->id);
            if(is_null($doccomunicacion_cpe)){
                $llNuevo = true;
                $this->glosa = 'COMUNICACION DE BAJA';
            }

            $respuesta_hash = $this->cpehash;
            
            if(empty(trim($this->ticket))){
                $this->cpeticket = $this->ticket;
            }

            $doccomunicacion = DocComunicacion::find($this->id);

            if(!empty($this->ticket)){
                $this->cpedoc = '-RA-' . str_replace('-', '',$doccomunicacion->fechaproceso) . '-' . trim($doccomunicacion->numero);
                $this->cpeticket = $this->ticket;
                //$this->cpefilecdr->save(public_path() .'/SUNATXML/' . 'R-' . $this->cperuc . $this->cpedoc . '.xml');
                //$this->cpefilecdrzip->save(public_path() .'/SUNATXML/' . 'R-' . $this->cperuc . $this->cpedoc . '.zip');
                
                $this->nombre_xml = 'R-' . $this->cperuc . $this->cpedoc;

                $lcRet = $this->cpegetstatus->status($this->cpecdrcod, $this->cpecdrdsc, $this->cpeticket, $this->cpedoc);

                if(!empty($lcRet)){
                    $data['mensaje'] = $lcRet;
                    return $lcRet;
                }
            }
            $lcEstado = '';

            switch(trim($this->cpecdrcod)){
                case '0': 
                    $lcEstado = 'ACEPTADO';
                    break;
                case '0098': 
                    $lcEstado = 'EN PROCESO';
                    break;
                case '0200':
                case '0133':
                    $lcEstado = 'ACTIVO';
                    break;
                case '2324':
                    $this->cpecdrcod = '0';
                    $lcEstado = 'ACPETADO';
                    break;
                default:
                    $lcEstado = 'CON ERROR';
                    break;
            }

            if($lcEstado == 'ACTIVO'){
                ///todo la data, hash, ticket, respuesta_cod, respuesta_dsc
                $this->cpecdrcod = '';
                $this->cpecdrdsc = '';
                $this->cpehash = $doccomunicacion_cpe->respuesta_hash;
                $this->fecha_recep = $doccuminicacion_cpe->fecha_recep;
            }else{
                ///todo la data, hash, ticket, respuesta_cod, respuesta_dsc
                $this->fecha_recep = Carbon::now();
                $this->cpehash = $this->extraer_cpehash();
            }

            if($llNuevo){
                
                //creamos el doccomunicacion_cpe
                $this->doccominicacion_cpe_ = new DocComunicacionCPE;            
                $this->doccominicacion_cpe_->parent_id = $this->id;
                $this->doccominicacion_cpe_->item = $this->item;
                $this->doccominicacion_cpe_->fecha_firma = Carbon::now();
                $this->doccominicacion_cpe_->fecha_envio = Carbon::now();
                $this->doccominicacion_cpe_->glosa = $this->glosa;
                $this->crear_doccomunicacion_cpe();
            }else{

                DocComunicacionCPE::where('parent_id', $this->id)->update(
                    [
                        'fecha_recep' => $this->fecha_recep,
                        'ticket' => $this->cpeticket,
                        'respuesta_cod' => $this->cpecdrcod,
                        'respuesta_dsc' => $this->cpecdrdsc,
                        'respuesta_hash' => $this->cpehash,
                    ]
                );
            }

            $this->doccomunicacion_estado($lcEstado);

            if($lcEstado != 'ACTIVO' && $lcEstado != 'ERROR'){
                if(trim($this->cpecdrcod) == 0){
                    // SUBIR A LA PAGINA DE CUSTODIA
                    //oapp.SubirFTP // FALTA REALIZARLO

                    //$docxpagar = DocumentToPay::find($doccomunicacion_detalle->referencia_id);

                    foreach($doccomunicacion_detalle as $doccom_detalle){
                        $id = $doccom_detalle->referencia_id;

                        //$lcSql = 'update docxpagar_cpe set doccomunicacion_id = ' . trim(intval($this->id)) + ' where docxpagar_id = ' . trim(intval($docxpagar->id));
                        //actualizamos el valor del campo doccomunicacion_id en la tabla docxpagar
                        $this->setquerycmd(trim(intval($this->id)), trim(intval($id)));
                        
                        //Metodo setanulacpe
                        $this->setanulacpe($id);

                        //ENVIAR CORREO
                        $this->reportecmd($id);
                        //$lcEmail = oapp.enviarEmail(); // FALTA REALIZARLO
                    }
                }
            }

            return $lcEstado;
            
        }
    }

    public function selectparentcmd($pId){
        $doccomunicacion_cpe = DocComunicacionCPE::where('parent_id', $pId)->first();
        return $doccomunicacion_cpe;
    }


    private function extraer_cpehash(){

        $this->xml_archivo = file_get_contents(public_path() . '/SUNATXML/' . $this->cperuc . '/' . $this->nombre_xml.'.xml');

        return trim(string_between_two_string($this->xml_archivo, '<DigestValue>', '</DigestValue>'));
    }

    public function crear_doccomunicacion_cpe(){

        $this->doccominicacion_cpe_->fecha_recep = $this->fecha_recep;
        $this->doccominicacion_cpe_->ticket = $this->cpeticket;
        $this->doccominicacion_cpe_->respuesta_cod = $this->cpecdrcod;
        $this->doccominicacion_cpe_->respuesta_dsc = $this->cpecdrdsc;
        $this->doccominicacion_cpe_->respuesta_hash = $this->cpehash;
        $this->doccominicacion_cpe_->save();
    }

    public function setanulacpe($id){
        return DB::select('CALL setanulacpe(?,?)', array($id, Session::get('usuario')));
    }

    public function setquerycmd($doccomunicacion_id, $docxpagar_id){

        return DocumentToPayCPE::where('docxpagar_id', $docxpagar_id)->update(['doccomunicacion_id' => intval($doccomunicacion_id)]);
    
    }

    public function reportecmd($id){
        
        // ID: id del documento 
        // 0: para los documentos de venta indica si se imprime por consumo o no
        return DB::select('CALL rptdocxpagar(?,?)', array(trim(intval($id)), 0));

    }

    public function doccomunicacion_estado($estado){

        return DocComunicacion::where('id', $this->id)->update(['estado' => $estado]);
    }
}
