<?php

namespace App\Http\Requests;

use App\Period\Entities\Period;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class CashMovementStore extends FormRequest
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
            'changerate' => 'required',
            'type' => 'required',
            'bank' => 'required',
            'currentaccount' => 'required',
            //'cobrador' => 'sometimes|required',
            'comment' => 'required',
            //'txt_total_detalle' => 'required',
            'sucursal' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'txt_fecha.required' => 'La fecha es requerida',  //fecha del proceso
            'changerate.required' => 'Tipo de cambio es requerido',
            'type.required' => 'El tipo de movimiento es requerido',
            'bank.required' => 'El tipo de caja es requerida',
            'currentaccount.required' => 'La cuenta corriente es requerida',
            //'cobrador.required' => 'El cobrador es requerido',
            'comment.required' => 'La glosa es requerida',
            //'txt_total_detalle.required' => 'El total es requerido',
            'sucursal.required' => 'La sucursal es requerida'
        ];
    }
}
