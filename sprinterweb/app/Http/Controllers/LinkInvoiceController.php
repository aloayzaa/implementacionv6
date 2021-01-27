<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ValidateLink;

class LinkInvoiceController extends Controller
{
    use ValidateLink;
    

    private $model_id;
    private $xml_nombre;
    private $ruc;

    private $model; // MODELO
    private $nombre_tabla;

    private $archivo;
    private $extension;
    private $ruta;

    public function __construct($model_id, $xml_nombre, $ruc, $model, $nombre_tabla)
    {
        $this->model_id = $model_id;
        $this->xml_nombre = $xml_nombre;
        $this->ruc = $ruc;

        $this->model = $model;
        $this->nombre_tabla = $nombre_tabla;
    }


    public function store(){


        foreach(range(1, 3) as $i){

            $this->evaluar_vinculo($i);

        }


    }


    private function evaluar_vinculo($i){

        switch($i){

            case 1: // FIRMAXML

                if ( $this->existe_firmaxml() ){
                
                    $this->archivo = $this->xml_nombre;
                    $this->extension = 'xml';

                    // CON EL NOMBRE SE PUEDE ACCEDER AL STORAGE DEL DIGITAL OCEAN
                    $this->ruta = ""; 

                    // CUANDO SE GUARDABA EN LOCAL                    
                    //$this->ruta = public_path() . '/FIRMAXML/' . $this->ruc . '/';

                    $this->store_vinculo();
                }

            break;

            case 2: // PDFXML
                return '';
            break;

            case 3: // SUNAT XML
                
                if ( $this->existe_sunatxml() ){

                    $this->archivo = 'R-' . $this->xml_nombre;
                    $this->extension = 'xml';

                    // CON EL NOMBRE SE PUEDE ACCEDER AL STORAGE DEL DIGITAL OCEAN
                    $this->ruta = ""; 

                    // CUANDO SE GUARDABA EN LOCAL    
                    //$this->ruta = public_path() . '/SUNATXML/' . $this->ruc . '/';

                    $this->store_vinculo();

                }

            break;

        }
        
    }



}
