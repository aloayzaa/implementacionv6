<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointOfSaleRequest extends FormRequest
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
            //Transaccion || Punto de venta
            'code' => 'sometimes|required',
            'cbotipodoc' => 'sometimes|required',
            'txtserie' => 'sometimes|required',
            'txtnumero' => 'sometimes|required',
            'txt_fecha' => 'sometimes|required',
            'tcambio' => 'sometimes|required',
            'vendedor_id' => 'sometimes|required',
            'txt_descripcioncp' => 'sometimes|required',
            'txt_vence' => 'sometimes|required',
        ];
    }

    public function messages()
    {
        return [
            //Gestion de ventas || //Transaccion || Punto de venta
            'code.required' => 'Ingrese DNI o RUC del cliente.',
            'cbotipodoc.required' => 'Seleccione el Tipo Documento.',
            'txtserie.required' => 'Ingrese una serie.',
            'txtnumero.required' => 'Ingrese el número de documento',
            'txt_fecha.required' => 'Ingrese una fecha',
            'tcambio.required' => 'No existe tcambio para la fecha seleccionada.',
            'vendedor_id.required' => 'Seleccione un vendedor.',
            'txt_descripcioncp.required' => 'Seleccione una condición de pago.',
            'txt_vence.required' => 'No existe vencimiento del documento.'
        ];
    }
}
