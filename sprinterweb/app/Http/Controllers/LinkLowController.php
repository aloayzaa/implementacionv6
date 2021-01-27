<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ValidateLink;
use App\DocComunicacion\DocComunicacion;

class LinkLowController extends Controller
{

    use ValidateLink;

    private $model_id;


    private $archivo;
    private $extension;
    private $ruta;

    private $doccomunicacion; // MODEL DOCCOMUNICACION

    public function __construct($model_id, $xml_nombre, $ruc, $model, $nombre_tabla)
    {
        $this->model_id = $model_id;
        $this->xml_nombre = $xml_nombre;
        $this->ruc = $ruc;

        $this->model = $model;
        $this->nombre_tabla = $nombre_tabla;

        $this->doccomunicacion = DocComunicacion::findOrFail($this->model_id);
    }


    public function store(){

       // if($this->doccomunicacion->estado != 'ACTIVO' && $this->doccomunicacion->estado != 'ERROR'){

            $this->evaluar_vinculo();

       // }

    }

    private function evaluar_vinculo(){


        if ( $this->existe_sunatxml() ){

            $this->archivo = 'R-' . $this->xml_nombre;
            $this->extension = 'xml';

            // CON EL NOMBRE SE PUEDE ACCEDER AL STORAGE DEL DIGITAL OCEAN
            $this->ruta = ""; 

            // CUANDO SE GUARDABA EN LOCAL
            // $this->ruta = public_path() . '/SUNATXML/' . $this->ruc . '/';

            $this->store_vinculo();

        }


    }
}
