<?php

namespace App\Http\Requests;

use App\Period\Entities\Period;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //GESTION DE COMPRAS | CONFIGURACION | TIPOS DETRACCION
            'code' => 'sometimes|required|unique:tipodetraccion,codigo,'.$this->id,
            'description' => 'sometimes|required|unique:tipodetraccion,descripcion,'.$this->id,
            'code_tipocompras' => 'sometimes|required|unique:tipocompra,codigo,'.$this->id,
            'description_tipocompras' => 'sometimes|required|unique:tipocompra,descripcion,'.$this->id,
            'type_tipocompras' => 'sometimes|required',
            'asociado_tipocompras' => 'sometimes|required',
            //GESTION DE COMPRAS | TRANSACCION | ORDENES DE SERVICIO
            'period' => 'sometimes|required',
            'cbo_unegocio' => 'sometimes|required',
            'txt_series' => 'sometimes|required',
            'pointsale' => 'sometimes|required',
            'comment' => 'sometimes|required',
            'currency' => 'sometimes|required',
            'tercero_id' => 'sometimes|required',
            'producto_id' => 'sometimes|required',
            'processdate' => [    //fecha del proceso
                'sometimes',
                'required',
                function ($attribute, $value, $fail){
                    $codigo = explode('-', $value);
                    $perido_codigo = Period::findOrFail(Session::get('period_id'))->codigo;
                    if ($codigo[0].$codigo[1] !== $perido_codigo) {
                        $fail('La fecha no coincide con la del periodo');
                    }
                }],
            'cantidad' => 'sometimes|required|numeric|not_in:0',
            'costounidad' => 'sometimes|required|numeric|not_in:0',
            'changerate' => 'sometimes|required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            //GESTION DE COMPRAS | CONFIGURACION | TIPOS DETRACCION
            'code.required' => 'Ingrese un código',
            'code.unique' => 'El código ya se encuentra registrado',
            'description.required' => 'Ingrese una descripción',
            'description.unique' => 'La descripción ya existe',
            'code_tipocompras.required' => 'Ingrese un código',
            'code_tipocompras.unique' => 'El código ya se encuentra registrado',
            'description_tipocompras.required' => 'Ingrese una descripción',
            'description_tipocompras.unique' => 'La descripción ya se encuentra registrado',
            'type_tipocompras.required' => 'Seleccione el Tipo de Transacción Asociada',
            'asociado_tipocompras.required' => 'Seleccione el Subdiario Asociado',
            //GESTION DE COMPRAS | TRANSACCION | ORDENES DE SERVICIO
            'period.required' => 'sometimes|required',
            'cbo_unegocio.required' => 'Seleccione la Unidad de Negocio',
            'txt_series.required' => 'Ingrese una Serie',
            'processdate.required' => 'Ingrese una Fecha',
            'changerate.min' => 'El tipo de cambio no existe',
            'pointsale.required' => 'Seleccione el Punto de Emisión',
            'comment.required' => 'Ingrese Observaciones',
            'currency.required' => 'Seleccione la Moneda',
            'tercero_id.required' => 'Seleccione Código y Razón Social',
            'producto_id.required' => 'Seleccione Código y Descripción',
            'cantidad.required' => 'Ingrese una cantidad',
            'cantidad.numerico' => 'El valor del costo no es permitido',
            'cantidad.not_in' => 'El valor de la cantidad debe ser mayor a 0',
            'costounidad.required' => 'Ingrese el costo unitario',
            'costounidad.numerico' => 'El valor del costo no es permitido',
            'costounidad.not_in' => 'El valor del costo unitario debe ser mayor a 0',
        ];
    }
}
