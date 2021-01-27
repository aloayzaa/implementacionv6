<?php

namespace App\Http\Requests;

use App\Period\Entities\Period;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderStoreRequest extends FormRequest
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
            //ordenes de compra
            'txt_periodo' => 'sometimes|required',
            'txt_serie' => 'sometimes|required',
            //'txt_numero' => 'sometimes|required',
            'cbo_tipo_producto' => 'sometimes|min:1',
            'txt_fecha' => [    //fecha del proceso
                'sometimes',
                'required',
                function ($attribute, $value, $fail){
                    $codigo = explode('-', $value);
                    $perido_codigo = Period::findOrFail(Session::get('period_id'))->codigo;
                    if ($codigo[0].$codigo[1] !== $perido_codigo) {
                        $fail('La fecha no coincide con la del periodo');
                    }
                }],
            'cbo_unidad_negocio' => 'sometimes|required',
            'cbo_razon_social' => 'sometimes|required',
            'cbo_punto_emision' => 'sometimes|required',
            'cbo_moneda_orden' => 'sometimes|required',
            'changerate' => 'sometimes|required|numeric|min:1',
            //'txt_tc_orden' => 'sometimes|required',
            'cbo_condicion_pago' => 'sometimes|required',
            'txt_fecha_entrega'  => 'sometimes|required',
            //'txt_lugar_entrega' => 'sometimes|required',
            'txt_total' => 'sometimes|required',
            'txt_lugar_entrega' => 'sometimes|required',
        ];
    }

    public function messages()
    {
        //ordenes de compra
        return [
            'txt_periodo.required' => 'Periodo inválido',
            'txt_serie.required' => 'La serie es requerida',
            //'txt_numero.required' => 'El número es requerida',
            'cbo_tipo_producto.min' => 'El tipo de producto es requerido',
            'txt_fecha.required' => 'La fecha de proceso es requerida',
            'cbo_unidad_negocio.required' => 'La unidad de negocio es requerida',
            'cbo_razon_social.required' => 'El tercero es requerido',
            'cbo_punto_emision.required' =>  'El punto de emisión es requerido',
            'cbo_moneda_orden.required' => 'EL tipo de moneda es requerido',
            'changerate.required' => 'El tipo de cambio es requerido',
            'changerate.min' => 'No existe tipo de cambio',
            //'txt_tc_orden' => '',
            'cbo_condicion_pago.required' => 'El tipo de pago es requerido',
            'txt_fecha_entrega.required'  => 'La fecha de entrega es requerida',
            //'txt_lugar_entrega.required' => '',
            'txt_total.required' => 'Ingrese al menos un producto',
            'txt_lugar_entrega.required' => 'Ingrese el lugar de entrega',
        ];
    }

}
