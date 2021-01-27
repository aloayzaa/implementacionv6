<?php

namespace App\Http\Requests;

use App\AccountingPlan\Entities\AccountingPlan;
use Illuminate\Foundation\Http\FormRequest;

class CashMovementDetail extends FormRequest
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
            'operation' => 'required',
            'amount' => 'sometimes|required|gt:0',
            //'perception' => 'required',
            'account' => 'sometimes|required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('customer', 'required', function($input) {
            //if($input->reference != ''){ return 0;}
            if($input->account){
                $cuenta =  AccountingPlan::findOrFail($input->account);
                return strcasecmp($cuenta->tipo_auxiliar, 'T') == 0;
            }
        });
        $validator->sometimes('cost', 'required', function($input) {
            if($input->account){
                $cuenta = AccountingPlan::findOrFail($input->account);
                return strcasecmp($cuenta->pide_ccosto, '1') == 0;
            }
        });
    }

    public function messages()
    {
        return [
            'operation.required' => 'El tipo de operación es requerdido',
            'amount.required' => 'El importe es requerido',
            'amount.gt' => 'El importe debe ser mayor a 0.00',
            //'perception.required' => 'La percepción es requerida',
            'account.required' => 'La cuenta es requerida',
            'customer.required' => 'La cuenta exige un tercero',
            'cost.required' => 'El centro de costo es requerido'
        ];
    }
}
