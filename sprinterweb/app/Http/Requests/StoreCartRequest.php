<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
            'producto_id' => 'required',
            'cantidad' => 'required|numeric|min:1',
            'stock_pedido' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|numeric|gte:cantidad',
            'um' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'producto_id.required' => 'El Producto es requerido',
            'cantidad.required' => 'La cantidad es requerido',
            'cantidad.min' => 'La cantidad mínima es 1',
            'stock_pedido.required' => 'El stock pedido es requerido',
            'stock.required' => 'El stock es requerido',
            'stock.gte' => 'No hay stock suficiente',
            'um.required' => 'La unidad de medida es requerida'
        ];
    }
}
