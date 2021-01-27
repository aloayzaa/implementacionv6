<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyStoreRequest extends FormRequest
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
            'txt_codigo' => 'unique:familiapdto,codigo,'.$this->id,
            'txt_descripcion' => 'sometimes|required',
            'cbo_pro_sunat' => 'sometimes|required',
            'txt_codigo_sunat' => 'sometimes|required',

        ];
    }

    public function messages()
    {
        return [
            //agregar detalle
            'txt_codigo.unique' => 'Este Dato ya se enceuntra registrado',
            'txt_descripcion.required' => 'Ingrese una descripcion',
            'cbo_pro_sunat.required' => 'Seleccione el Producto  sunat',
            'txt_codigo_sunat.required' => 'Ingrese Codigo Sunat',

        ];
    }
}
