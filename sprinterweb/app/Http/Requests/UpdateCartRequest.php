<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
            'producto_id' => 'sometimes|required',
            'cantidad' => 'sometimes|required|numeric|min:1',
            'stock' => 'sometimes|required|numeric|gte:cantidad',
            'um' => 'sometimes|required',
        ];
    }

    public function messages()
    {
        return [
            'producto_id.required' => 'El Producto es requerido',
            'cantidad.required' => 'La Cantidad es requerido',
            'cantidad.min' => 'La cantidad mínima es 1',
            'stock.required' => 'El Stock es requerido',
            'stock.gte' => 'No hay stock suficiente',
            'um.required' => 'La Unidad de medida es requerido',
        ];
    }

}
