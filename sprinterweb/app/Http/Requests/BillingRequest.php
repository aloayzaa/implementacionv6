<?php

namespace App\Http\Requests;

use App\Period\Entities\Period;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class BillingRequest extends FormRequest
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
            'unidad_negocio' => 'required',
            'punto_venta' => 'required',
            'tipo_venta' => 'required',
            'tercero' => 'required',
            'tipo_doc' => 'required',
            'serie_doc' => 'required',
            'numero_doc' => 'required',
            'fecha_proceso' => [   //fecha del proceso
                'required',
                function ($attribute, $value, $fail){
                $codigo = explode('-', $value);
                $perido_codigo = Period::findOrFail(Session::get('period_id'))->codigo;
                if ($codigo[0].$codigo[1] !== $perido_codigo) {
                    $fail('La fecha no coincide con la del periodo');
                }
            }],            
            'tcambio' => 'required',
            'moneda' => 'required',
            'condicion_pago' => 'required',
            'vencimiento' => 'required',
            'tipo_afectacion_igv' => 'required',
            'igv' => 'required',
            'glosa' => 'required',
            'fecha_detraccion' => 'required_with:detraccion'
        ];
    }

    public function messages()
    {
        return [
            'unidad_negocio.required' => 'La unidad de negocio es requerida',
            'punto_venta.required' => 'El punto de venta es requerido',
            'tipo_venta.required' => 'El tipo de venta es requerido',
            'tercero.required' => 'El cliente es requerido',
            'tipo_doc.required' => 'Seleccione un tipo de documento',
            'serie_doc.required' => 'La serie del documento es requerida',
            'numero_doc.required' => 'El número de documento es requerido',
            'fecha_proceso.required' => 'Ingrese la fecha del documento',
            'tcambio.required' => 'El tipo de cambio es requerido',
            'moneda.required' => 'La moneda es requerida',
            'condicion_pago.required' => 'La condición de pago es requerida',
            'vencimiento.required' => 'EL vencimiento es requerido',
            'tipo_afectacion_igv.required' => 'El tipo de afectación es obligatorio',
            'igv.required' => 'El impuesto es requerido',
            'glosa.required' => 'La glosa es requerida',
            'fecha_detraccion.required_with' => 'La fecha detracción es requerido'
        ];
    }

}
