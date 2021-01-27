<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
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
            'cbo_familia' => 'sometimes|numeric|min:1',
            //'txt_codigo_producto' => 'sometimes|required|unique:producto,codigo',
            'txt_codigo_producto' =>  'sometimes|required|unique:producto,codigo,'. $this->id,
            //'txt_descripcion_producto' => 'sometimes|required|unique:producto,descripcion',
            'txt_descripcion_producto' => 'sometimes|required|unique:producto,descripcion,'. $this->id,
            'sunat' => 'sometimes|required',
            'kardex' => 'sometimes|required',
            'imagen' => 'sometimes|mimes:jpeg,jpg,png|max:1024',
            'compra' => 'sometimes|required',
        ];
        /*
        $rule1 = [
            'cbo_familia' => 'sometimes|numeric|min:1',
            'txt_codigo_producto' =>  'sometimes|required|unique:producto,codigo,'. $this->id,
            'txt_descripcion_producto' => 'sometimes|required|unique:producto,descripcion,'. $this->id,
            'sunat' => 'required',
            'imagen' => 'sometimes|mimes:jpeg,jpg,png|max:1024'
        ];
        $method = $this->route()->getName();
        switch($method):
            case 'store.products':
                $rule2 = ['kardex' => 'required'];
                break;
            default: break;
        endswitch;
        $rules = array_merge($rule1, $rule2);
        return $rules;*/
    }


    public function messages()
    {
        return [
            'cbo_familia.numeric' => 'Selecciona una familia',
            'cbo_familia.min' => 'Selecciona una familia',
            'txt_codigo_producto.required' => 'Ingrese el código del producto',
            'txt_codigo_producto.unique' => 'El código del producto ya existe',
            'txt_descripcion_producto.required' => 'Ingrese un nombre de producto',
            'txt_descripcion_producto.unique' => 'El producto ya existe',
            'sunat.required' => 'El código de sunat es obligatorio',
            'kardex.required' => 'La U.M. del kardex es obligatorio',
            'imagen.mimes' => 'Formato no válido solo jpeg,jpg,png',
            'imagen.max' => 'Imagenes solo hasta 1 MB',
            'compra.required' => 'La U.M. de compras es obligatorio',
        ];
    }
}
