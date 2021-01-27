<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderDetailRequest extends FormRequest
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
            //agregar detalle
            'cbo_producto' => 'sometimes|required',
            'txt_cantidad' => 'sometimes|required|numeric|not_in:0',
            'txt_costo_unitario' => 'sometimes|required|numeric|not_in:0',
            //editar detalle
            'cbo_producto_editar' => 'sometimes|required',
            'txt_cantidad_editar' => 'sometimes|required|numeric|not_in:0',
            'txt_costo_unitario_editar' => 'sometimes|required|numeric|not_in:0',
        ];
    }

    public function messages()
    {

        return [
            //agregar detalle
            'cbo_producto.required' => 'El producto es obligatorio',
            'txt_cantidad.required' => 'Ingrese la cantidad',
            'txt_cantidad.numeric' => 'El valor de la cantidad no es permitido',
            'txt_cantidad.not_in' => 'Ingrese la cantidad',
            'txt_costo_unitario.required' => 'Ingrese el costo',
            'txt_costo_unitario.numeric' => 'El valor del costo no es permitido',
            'txt_costo_unitario.not_in' => 'Ingrese el costo',
            //editar detalle
            'cbo_producto_editar.required' => 'El producto es obligatorio',
            'txt_cantidad_editar.required' => 'Ingrese la cantidad',
            'txt_cantidad_editar.numeric' => 'El valor de la cantidad no es permitido',
            'txt_cantidad_editar.not_in' => 'Ingrese la cantidad',
            'txt_costo_unitario_editar.required' => 'Ingrese el costo',
            'txt_costo_unitario_editar.numeric' => 'El valor del costo no es permitido',
            'txt_costo_unitario_editar.not_in' => 'Ingrese el costo',
        ];
    }
}
