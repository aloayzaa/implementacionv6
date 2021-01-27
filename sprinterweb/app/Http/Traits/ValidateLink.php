<?php
namespace App\Http\Traits;
use App\Link\Entities\Link;
use Illuminate\Support\Facades\Session;

trait ValidateLink{

    protected function existe_firmaxml(){

        $xml = obtener_xml_firma2($this->ruc, $this->xml_nombre);

        return (empty($xml)) ? false : true;        

    }

    protected function existe_sunatxml(){

        $xml = obtener_xml_sunat($this->ruc, $this->xml_nombre);

        return (empty($xml)) ? false : true; 

    }


    protected function store_vinculo(){

        $vinculo =  new Link;

        $vinculo->tablaorigen = $this->nombre_tabla;
        $vinculo->origen_id = $this->model_id;
        $vinculo->ventana = $this->model->ventana;
        $vinculo->archivo = $this->archivo;
        $vinculo->extension = $this->extension;
        $vinculo->ruta = $this->ruta;
        $vinculo->usuario = Session::get('usuario');

        $vinculo->save();

    }

}
