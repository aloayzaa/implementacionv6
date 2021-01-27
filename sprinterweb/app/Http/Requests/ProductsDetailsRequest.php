<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsDetailsRequest extends FormRequest
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
            'cbo_ubicacion_almacen' => 'sometimes|numeric|min:1',
            'cbo_productos_npk' => 'sometimes|numeric|min:1'
        ];
    }


    public function messages()
    {
        return [
            'cbo_ubicacion_almacen.numeric' => 'Seleccione un detalle',
            'cbo_ubicacion_almacen.min' => 'Seleccione un detalle',
            'cbo_productos_npk.numeric' => 'Seleccione un detalle',
            'cbo_productos_npk.min' => 'Seleccione un detalle',
        ];
    }
}
