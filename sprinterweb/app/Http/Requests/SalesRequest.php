<?php

namespace App\Http\Requests;

use App\PointSale\Entities\PointSaleSeries;
use Illuminate\Foundation\Http\FormRequest;

class SalesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //Gestion de ventas || Configuracion || Tipo de venta
            'code' => 'sometimes|required|unique:tipoventa,codigo,'.$this->id,
            'name' => 'sometimes|required|unique:tipoventa,descripcion,'.$this->id,
            'buy' => 'sometimes|required',
            'subdiario' => 'sometimes|required',
            //Gestion de ventas || Configuracion || Punto de Venta - Detalle
            'cbo_documentocom' => 'sometimes|required',
            //Gestion de ventas || Configuracion || Punto de Venta
            'txt_codigo' => 'sometimes|required|unique:puntoventa,codigo,'.$this->id,
            'txt_descripcion' => 'sometimes|required|unique:puntoventa,descripcion,'.$this->id,
            'cbo_almacen' => 'sometimes|required',
            'cbo_tipoventa' => 'sometimes|required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('txt_serie', 'unique:puntoventa_series,serie,'.$this->pv_series_id, function($input) {
            if($input->txt_serie){
                $docserie = PointSaleSeries::where('documentocom_id', $input->cbo_documentocom)->where('puntoventa_id', $input->id)->where('serie', trim($input->txt_serie))->where('id','<>',$input->pv_series_id)->first();
                return ($docserie) ? true : false;
            }
        });
    }

    public function messages()
    {
        return [
            //Gestion de ventas || Configuracion || Tipo de venta
            'code.required' => 'Ingrese un código',
            'code.unique' => 'El código ya se encuentra registrado',
            'name.required' => 'Ingrese una descripción',
            'name.unique' => 'La descripción ya se encuentra registrado',
            'buy.required' => 'Seleccione el Tipo de Transacción Asociada',
            'subdiario.required' => 'Seleccione el SubDiario Asociado',
            //Gestion de ventas || Configuracion || Punto de Venta - Detalle
            'cbo_documentocom.required' => 'El documento es obligatorio',
            'txt_serie.unique' => 'Ya existe el documento asociado a la serie xx',
            //Gestion de ventas || Configuracion || Punto de Venta
            'txt_codigo.required' => 'El código es obligatorio',
            'txt_codigo.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion.required' => 'La descripción es obligatorio',
            'txt_descripcion.unique' => 'La descripción ya se encuentra registrada',
            'cbo_almacen.required' => 'El almacen es obligatorio',
            'cbo_tipoventa.required' => 'El tipo de venta es obligatorio'
        ];
    }
}
