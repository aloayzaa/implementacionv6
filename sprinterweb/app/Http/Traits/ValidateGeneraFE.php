<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

trait ValidateGeneraFE{

    public function validar_reportecmd(){
        
        session()->forget('respuesta_sunat');

        
        // SOLO CUANDO VIENE DE : MOV_FACTURAVENTA Y MOV_GUIAREMISION

        if ( strtolower($this->docxpagar->ventana) != 'mov_facturaventa'  &&  strtolower($this->docxpagar->ventana) != 'mov_guiaremision') {return null;}



        // EL MENSAJE FUNCIONA CUANDO VIENE DE ENVIO CPE
        // CUANDO VIENE DE MOV_FACTURAVENTA, SOLO SIRVE PARA CORTAR EL PROCESO Y NO GENERAR LA FACTURA ELECTRÓNICA

        $reportecmd_collect = collect($this->reportecmd);

        if ($reportecmd_collect->isEmpty()){

            $data['estado'] = 'error';
            $data['descripcion'] = 'No se encuentra el documento';             

            return $data;

        }


        // EL MENSAJE FUNCIONA CUANDO VIENE DE ENVIO CPE
        // CUANDO VIENE DE MOV_FACTURAVENTA, SOLO SIRVE PARA CORTAR EL PROCESO Y NO GENERAR LA FACTURA ELECTRÓNICA
        // LA SIGUIENTE VALIDACIÓN  FUNCIONA EN DOCUMENTOS QUE CREADOS EN MOV_FACTURAVENTA
        
        if(strtolower($this->docxpagar->ventana) == 'mov_facturaventa'){
            
            $detalles =$reportecmd_collect->filter(function ($detalle) {
                return (($detalle->cantidad > 0 && $detalle->precio > 0) || $detalle->referencia_id > 0);
            });
    
            if ($detalles->count() <= 0){

                $data['estado'] = 'error'; 
                $data['descripcion'] = 'Los detalles del documento ' . $this->documento->tipodoc . '-' . $this->documento->seriedoc . '-' . $this->documento->numerodoc . ' no cumplen las condiciones';
    
                return $data;
    
            }

        }



        return null;

    }

    public function validar_generafe(){
        
        // SOLO CUANDO VIENE DE : MOV_FACTURAVENTA Y MOV_GUIAREMISION

        if ( strtolower($this->docxpagar->ventana) != 'mov_facturaventa' &&  strtolower($this->docxpagar->ventana) != 'mov_guiaremision') {return null;} 


        // EL MENSAJE FUNCIONA CUANDO VIENE DE ENVIO CPE
        // CUANDO VIENE DE MOV_FACTURAVENTA, SOLO SIRVE PARA CORTAR EL PROCESO Y NO GENERAR LA FACTURA ELECTRÓNICA


        if ( $this->lcpe != 1 ){

            $data['estado'] = 'error';            
            $data['descripcion'] = 'La facturación electrónica no esta activo';
            return $data;

        }

        // LA PROPIEDAD ORIGEN_ENVIO LO AGREGUÉ CUANDO VIENE DE ENVIO CPE
        // LAS VALIDACIONES SIGUIENTES SOLO LAS USO CUANDO EL DOCUMENTO VIENE DE MOV_FACTURAVENTA
        // ES DIFERENTE UN DOCUMENTEO CREADO EN MOV_FACTURAVENTA A UN DOCUMENTO ENVIADO DESDE MOV_FACTURAVENTA
        // POR ESO LA VALIDACIÓN POR ORIGEN_ENVIO

        if(isset($this->docxpagar->origen_envio) == false){ // SI NO EXISTE LA PROPIEDAD ORIGEN_ENVIO

            $codsunat = $this->docxpagar->documento['codsunat'];

            $seriedoc1 = substr($this->docxpagar->seriedoc, 0, 1);
    
            if ( !in_array($codsunat, array('01', '03', '07', '08', '20')) || !in_array($seriedoc1, array('F','T','B','R')) ){

                // NO ES NECESARIO EL MENSAJE, CUANDO SE ENVIA DE MOV_FACTURAVENTA (NO SE MUESTRA EL MENSAJE)

                $data['estado'] = 'error';             
                return $data;
    
            }


        }



        return null;

    }    


    public function existe_xml(){

        // VERIFICAR SI EXISTE EL XML
        //$exists = Storage::disk('FIRMAXML')->exists('/' . $this->ruc . '/' . $this->xml_nombre);
        

        // EL MENSAJE FUNCIONA CUANDO VIENE DE ENVIO CPE
        // CUANDO VIENE DE MOV_FACTURAVENTA, SOLO SIRVE PARA CORTAR EL PROCESO Y NO GENERAR LA FACTURA ELECTRÓNICA

        /*if ($exists) { // SI EXISTE

            $data['estado'] = 'error'; 
            $data['descripcion'] = 'El documento ya ha sido generado';
            
            return $data;  

        } */            

        return null;
    }

    public function validar_archivo_comprimido(){

        $archivo_comprimido_collect = collect($this->archivo_comprimido)->first();


        // EL MENSAJE FUNCIONA CUANDO VIENE DE ENVIO CPE
        // CUANDO VIENE DE MOV_FACTURAVENTA, SOLO SIRVE PARA CORTAR EL PROCESO Y NO GENERAR LA FACTURA ELECTRÓNICA
        
        if ($archivo_comprimido_collect ==  null){

            $data['estado'] = 'error'; 
            $data['descripcion'] = 'No se encuentra el archivo ZIP';

            return $data;

        }        

        return null;
    }


    public function valida_enviar_sunat(){

        // CUANDO SE ENVIA DESDE ENVIO CPE, AGREGO LA PROPIEDAD ORIGEN_ENVIO  
        // LAS BOLETAS Y LOS DOCUMENTOS QUE TIENEN COMO REFERENCIA UNA BOLETA NO SE ENVIAN DESDE ENVIO CPE
        // ESTOS DOCUMENTOS SE ENVÍAN DESDE RESUMENES DE BOLETAS

        if ( isset($this->docxpagar->origen_envio) &&  $this->docxpagar->origen_envio == "envio_cpe"){

            if($this->documento->tipodoc != '09'){

                if($this->documento->tipodoc == '03' || $this->documento->tipodocref == '03'){

                    // SI DEVUELVE EL MENSAJE, NO SE ENVIA EL DOCUMENTO
                    return 'NO PROCESAR';

                }


            }


        }

        return null;

    }

}
