<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountingRequest extends FormRequest
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
            /*Contabilidad | Transacciones | Ajsutes por diferencia*/
            'sucursal_id' => 'sometimes|required',
            'subdiary' => 'sometimes|required',
            'changerate' => 'sometimes|required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'sucursal_id.required' => 'Seleccione una sucursal',
            'subdiary.required' => 'Seleccione un Subdiario',
            'changerate.min' => 'El tipo de cambio es necesario'
        ];
    }
}
