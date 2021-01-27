<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DocumentsSendingCpeController;
use App\Http\Controllers\GeneraFEController;
use App\Http\Controllers\CPEGetStatusCDRController;
use App\Http\Controllers\LinkInvoiceController;
use App\Http\Controllers\ReporteCmdController;
use App\Http\Controllers\SendInvoiceMailController;
use App\Http\Traits\Controls\UserPrivileges;
use App\Procedures\DocumentToPayProcedure;
use App\Procedures\ProvisionesPorPagarProcedure;
use Yajra\DataTables\DataTables;
use App\Warehouses\Entities\WarehouseIncomeCpe;
use App\Panel\Companies\Entities\Pempresa;
use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayCPE;
use App\DocComunicacion\DocComunicacionDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SendingCpeController extends Controller
{

    use UserPrivileges;

    protected $user;
    protected $privilegios = '03.03.04';
    private $var = 'sendingCpe';

    private $documentsSendingCpeController; // CONTROLADOR PARA LOS DOCUMENTOS LISTADOS

    private $lcpe;  // ACTIVAR FACTURACIÓN ELECTRÓNICA
    private $ruc; // FACTURACIÓN ELECTRÓNICA. RUC FIRMA DIGITAL
    private $tipo_transp; // NO AGRUPAR LAS GUÍAS  DE TRANSPORTISTA EN EL FORMATO DE FACTURA

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');

        $this->lcpe = Pempresa::get_parametro('CPE_ACTIVAR')->valor;
        $this->ruc = Pempresa::get_parametro('CPE_RUC')->valor; 
        $this->tipo_transp = (Pempresa::get_parametro('TRS_FMTFACT')->valor == 1) ? 21 : 2;

        $this->documentsSendingCpeController = new DocumentsSendingCpeController();

    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;

        $data['view'] = link_view('Ventas', 'Procesos', 'Envio CPE', '');
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['today'] =  Carbon::today()->format('Y-m-d');

        return view('sendingCpe.list', $data);

    }


    public function mostrar(Request $request){
        
        $documentToPayProcedure = new DocumentToPayProcedure;
        $listado = $documentToPayProcedure->rptvalidacpe($request->txt_desde, $request->txt_hasta);


        // REGISTROS ÚNICOS
        $listado = collect($listado)->unique(function ($item){
            return $item->tipodoc . $item->seriedoc . $item->numerodoc;                   
        });


        if($request->cbo_tipo_doc != '0'){
            
           // FILTRO POR DOCUMENTO 
           $listado = collect($listado)->where('tipo', $request->cbo_tipo_doc); 

        }

        $this->documentsSendingCpeController->eliminar_documentos();  
        $this->documentsSendingCpeController->llenar_documentos($listado);        


        return DataTables::of($listado)->make();

    }


    public function procesar(Request $request){

        if ($this->lcpe != 1) { return null; }
        
        
        $data = json_decode($request->data);
        
        $count = count($data);
        
        if($count < 1) {  return response()->json(['errors' => array('detalle' => "Seleccione un documento" )],422);}


        $this->llenar_seleccionados($data);



        switch($request->cbo_tipo_procesamiento){

            case 'S':
                
                $this->validar_s();

                $enviar_sunar = $this->enviar_sunat();

                if ($enviar_sunar['estado'] == 'error'){

                    return response()->json(['errors' => array('detalle' => $enviar_sunar['descripcion'] )],422);

                }
                
                $recuperar_cdr = $this->recuperar_cdr('S');

                if($recuperar_cdr['estado'] == 'error'){

                    return response()->json(['errors' => array('detalle' => $recuperar_cdr['descripcion'] )],422);

                }

            break;

            case 'C':
                
                $this->valida_c();

                $recuperar_cdr = $this->recuperar_cdr('C');

                if($recuperar_cdr['estado'] == 'error'){

                    return response()->json(['errors' => array('detalle' => $recuperar_cdr['descripcion'] )],422);

                }

            break;

            case 'E':

                $this->valida_e();

                $this->enviar_correo();

            break;



        }
        dd($request->all());


    }


    public function limpiar_datatable(){

        return DataTables::of([])->make();
        
    }


    private function enviar_sunat(){


        $detalles = $this->documentsSendingCpeController->documentos_seleccionados;
        
        foreach($detalles as $detalle){
            
            //$this->formato_impresion($detalle);


            if (strtolower($detalle->ventana) == 'mov_guiaremision'){

                $model = new WarehouseIncomeCpe;
                $model->ventana = $detalle->options->ventana;
                $model->id = $detalle->id;

                // PROPIEDAD ADICIONAL - CONDICIONAL PARA ACTUALIZAR DOCXPAGAR_CPE Y VALIDACIONES
                $model->origen_envio = 'envio_cpe'; 
    
                $generaFEController = new GeneraFEController($detalle->id, $this->lcpe, $model);
                $respuesta_generafe = $generaFEController->generaFE();                

                return $respuesta_generafe;



            }else{


                $this->provisionesPorPagarProcedure = new ProvisionesPorPagarProcedure;
                $this->provisionesPorPagarProcedure->setupdateingalm($detalle->id, 'AA');


                $model = new DocumentToPay;
                $model->ventana = $detalle->options->ventana;
                $model->id = $detalle->id;

                // PROPIEDAD ADICIONAL - CONDICIONAL PARA ACTUALIZAR DOCXPAGAR_CPE Y VALIDACIONES
                $model->origen_envio = 'envio_cpe'; 


                $GeneraFEController = new GeneraFEController($detalle->id, $this->lcpe, $model);
                $respuesta_generafe = $GeneraFEController->generaFE();
                
                return $respuesta_generafe;

            } // FIN IF


        } // FIN FOR


    }


    private function llenar_seleccionados($data){


        $this->documentsSendingCpeController->llenar_seleccionados($data);


    }

    private function validar_s(){

        $this->documentsSendingCpeController->validar_s();

    }

    private function valida_c(){

        $this->documentsSendingCpeController->valida_c();

    }

    private function valida_e(){

        $this->documentsSendingCpeController->valida_e();

    }

    private function recuperar_cdr($tipo){


        $detalles = $this->documentsSendingCpeController->documentos_seleccionados;
    

        foreach($detalles as $detalle){

            $respuesta_cod = $this->respuestacod_segun_tipo($detalle, $tipo);


            if($detalle->options->tipodoc != '03'){
                
                if( $respuesta_cod != '0'){
       
                    $cPEGetStatusCDRController =  new CPEGetStatusCDRController($detalle->options->tipodoc, $detalle->options->seriedoc, $detalle->options->numerodoc);                    
                    $cPEGetStatusCDRController->status();
                    if($respueta = $cPEGetStatusCDRController->consult()) { return $respueta; }
                    $cPEGetStatusCDRController->procesar_respuesta();


                    // PROBABLEMENTE NO SEA NECESARIO GUARDAR LOS VINCULOS
                    /*
                    $nombre = $this->ruc . '-' . $detalle->options->tipodoc . '-' . $detalle->options->seriedoc . '-' . $detalle->options->numerodoc . '.xml';

                    $existe = existe_xml_firma($this->ruc, $nombre);

                    if($existe == false){

                        $this->vincular($detalle->id, $detalle->options->ventana, $detalle->options->tipodoc, $detalle->options->seriedoc, $detalle->options->numerodoc);

                    }*/


                    
                    if($cPEGetStatusCDRController->obtener_code_cdr() == '0'){

                        $this->aceptado_sunat($detalle);

                    }else{

                        $this->error_sunat($detalle, $cPEGetStatusCDRController);

                    }
                    
                }


            }


        }


    }

    private function vincular($id, $ventana, $tipodoc, $seriedoc, $numerodoc){

        $xml_nombre =  $this->ruc . "-" . $tipodoc . "-" . $seriedoc . "-" . $numerodoc . ".xml";
        $nombre_tabla = 'docxpagar';
        $model = new DocumentToPay;
        $model->ventana = $ventana;


        $linkInvoiceController = new LinkInvoiceController($id, $xml_nombre, $this->ruc, $model, $nombre_tabla);
        $linkInvoiceController->store();


    }

    private function aceptado_sunat($detalle){

        switch($detalle->options->ventana){

            case 'mov_facturatransporte': $tipo = $this->tipo_transp; break;

            case 'mov_facturaexporta': $tipo = 8; break;

            default: $tipo = 0; break;

        }

        
        // EJCUTAR REPORTECMD DE LA CLASE DOCXPAGAR
        // Y CON LA DATA DEL PROCEDURA ENVIAR EL EMAIL

    }

    private function error_sunat($detalle, $cPEGetStatusCDRController){

        $codigo_cdr = $cPEGetStatusCDRController->obtener_code_cdr();

        if( entre_intervalo($codigo_cdr, 2010, 2172) && !in_array($codigo_cdr, array('2080','2017','2027','2145','2325')) || $codigo_cdr == '3235'){

            $documentToPayProcedure = new DocumentToPayProcedure;
            $documentToPayProcedure->setanulacpe($detalle->id);

        }else{

            $respuesta_mensaje = $cPEGetStatusCDRController->obtener_respuesta_mensaje();

            if(strtoupper($respuesta_mensaje) == 'ANULADO'){

                $this->error_documento_anulado($detalle);

            }

        }


    }

    private function respuestacod_segun_tipo($detalle, $tipo){

        $respuesta_cod = ($tipo == "S") ? $this->docxpagarcpe_respuestacod($detalle) : $detalle->options->respuesta_cod;

        return $respuesta_cod;

    }


    private function docxpagarcpe_respuestacod($detalle){

        $respuesta_cod = DocumentToPayCPE::select('respuesta_cod')->where('docxpagar_id', $detalle->id)->first()->respuesta_cod;

        return $respuesta_cod;

    }


    private function error_documento_anulado($detalle){

        $docComunicacionDetalle = DocComunicacionDetalle::baja($detalle->id);

        $baja = ($docComunicacionDetalle) ? 1 : 0;

        if ($baja == 0){

            DocumentToPay::actualizar_estado($detalle->id, 'ACTIVO');

            // PREGUNTAR POR EL ID ENVIADO
            $this->provisionesPorPagarProcedure = new ProvisionesPorPagarProcedure;
            $this->provisionesPorPagarProcedure->setupdateingalm($detalle->id, 'AA');
            $this->provisionesPorPagarProcedure->generaCtacte($detalle->id, 'docxpagar', '');
            $this->provisionesPorPagarProcedure->cntDocxCobrar($detalle->id, '');

        }

    }

    private function enviar_correo(){

        $tipo_servidor = Pempresa::get_parametro('CPE_SERVIDOR')->valor; // FACTURA ELECTRÓNICA. TIPO SERVIDOR

        // SOLE SE DEBEN ENVIAR EN PRODUCCIÓN
        //if($tipo_servidor > 2) { return null; }


        $detalles = $this->documentsSendingCpeController->documentos_seleccionados;


        foreach($detalles as $detalle){

            // VER FORMATOS DE IMPRESIÓN
            // AUN FALTA LOS PDF

            // EN CASO NO EXISTA EL DOCUMENTO FIRMADO CREARLO
            // COMO AHORA SOLO SE VAN A SELECCIONAR LOS ACEPTADOS POR SUNAT
            // A DIFERENCIA DEL CLASIC, NO ES NECESARIO GENERAR FIRMAXML 
            // YA QUE PREVIAMENTE HA SIDO CREADO
            // EN EL CLASIC SE HACIA PORQUE SE ENVIA AL CORREO, SIN ENVIAR A SUNAT

            // $this->generar_firmaxml($detalle);

            $sendInvoiceMailController = new SendInvoiceMailController($detalle->id, $detalle->options->ventana);
            $sendInvoiceMailController->enviar();



        }

    }    


    private function generar_firmaxml(){

        $nombre_xml = $this->ruc . '-' . $detalle->options->tipodoc . '-' . $detalle->options->seriedoc . '-' . $detalle->options->numerodoc . '.xml';

        $existe = existe_xml_firma($this->ruc, $nombre_xml);
        
        if($existe == false){

            $model = new DocumentToPay;
            $model->ventana = $detalle->options->ventana;

            $GeneraFEController = new GeneraFEController($detalle->id, $this->ruc, $model);
            $GeneraFEController->reportecmd();
            $GeneraFEController->obtener_tipodoc_cpedoc();
            $GeneraFEController->crear_nombre_xml();
            $GeneraFEController->estructura_xml();
            $GeneraFEController->crear_archivo_xml();
            $GeneraFEController->firmar_archivo_xml();
            

        }

    }


    private function formato_impresion(){

        switch(strtolower($detalle->ventana)){


            case 'mov_facturaveh':


            break;


            case 'mov_facturatransporte':


            break;
            

            case 'mov_retencion':


            break;


            case 'mov_facturaexporta':


            break;


            default:



            break;


        } // FIN SWITCH


    }

}
