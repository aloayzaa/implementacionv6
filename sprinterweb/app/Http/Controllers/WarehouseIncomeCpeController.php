<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Warehouses\Entities\WarehouseIncomeCpe;
use Carbon\Carbon;

class WarehouseIncomeCpeController extends Controller
{
    private $selectparentcmd; // objeto consulta (ver existencia del cpe)
    private $ingresoalmacen_cpe; // objeto WarehouseIncomeCpe (crear o actualizar - registro)
    private $ingresoalmacen_id;

    private $ruc;
    private $xml_archivo; // ARCHIVO XML EN STRING
    private $xml_nombre;
    private $cpetiposerv;

    public function __construct($docxpagar_id, $ruc, $xml_nombre, $cpetiposerv)
    {

        $this->ruc = $ruc;
        $this->xml_nombre = $xml_nombre;
        $this->cpetiposerv = $cpetiposerv;

        $this->ingresoalmacen_id = $docxpagar_id;
        $this->selectparentcmd = WarehouseIncomeCpe::selectparentcmd($this->ingresoalmacen_id);
        
    }  


    public function existencia_cpe(){

        if ($this->selectparentcmd){


            $this->ingresoalmacen_cpe = WarehouseIncomeCpe::findOrFail($this->selectparentcmd->id);
            

        }else{

            $this->ingresoalmacen_cpe = new WarehouseIncomeCpe;            
            $this->ingresoalmacen_cpe->ingresoalmacen_id = $this->ingresoalmacen_id;
            $this->ingresoalmacen_cpe->fecha_genera = Carbon::now();


        }
        
    }


    public function obtener_archivo(){

        // CUANDO SE GRABABA EN LOCAL
        // $this->xml_archivo = file_get_contents(public_path() . '/FIRMAXML/' . $this->ruc . '/' . $this->xml_nombre);

        // STORAGE DIGITAL OCEAN
        $this->xml_archivo = obtener_xml_firma2($this->ruc, $this->xml_nombre);

    }


    public function actualizar_o_crear_cpe(){

        $this->ingresoalmacen_cpe->fecha_firma = Carbon::now();
        $this->ingresoalmacen_cpe->respuesta_hash = $this->extraer_cpehash(); 
        $this->ingresoalmacen_cpe->respuesta_firma = $this->extraer_cpevalorfirma();
        $this->ingresoalmacen_cpe->cpe_servidor = $this->cpetiposerv;
        $this->ingresoalmacen_cpe->save();
        
    }


    private function extraer_cpehash(){

        return trim(string_between_two_string($this->xml_archivo, '<ds:DigestValue>', '</ds:DigestValue>'));
    }

    private function extraer_cpevalorfirma(){

        return trim(string_between_two_string($this->xml_archivo, '<ds:SignatureValue>', '</ds:SignatureValue>'));

    }

    public function obtener_cpe_id(){

        $id = $this->ingresoalmacen_cpe->id;
        return $id;
    }
}
