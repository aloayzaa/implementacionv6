<?php

namespace App\Http\Requests;

use App\Documents\Entities\BankDocument;
use App\Period\Entities\Period;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class BankMovementRequest extends FormRequest
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
            'paymentway' => 'required',
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
            'comment' => 'required',
            //'txt_total_detalle' => 'required',
            'sucursal' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('cheque', 'unique:docbanco,nrocheque,'.$this->id, function($input) {
            if($input->cheque){
                $docbanco_cheque = BankDocument::where('ctactebanco_id', $input->currentaccount)->where('nrocheque', $input->cheque)->where('tipo', 'E')->where('id','<>', $this->id)->first();
                return ($docbanco_cheque) ? true : false;
            }
        });
    }

    public function messages()
    {
        return [
            'paymentway.required' => 'El medio de pago es requerido',
            'txt_fecha.required' => 'La fecha es requerida',  //fecha del proceso
            'changerate.required' => 'Tipo de cambio es requerida',
            'type.required' => 'El tipo de movimiento es requerido',
            'bank.required' => 'El banco es requerido',
            'currentaccount.required' => 'La cuenta corriente es requerido',
            'comment.required' => 'La glosa es requerida',
            //'txt_total_detalle.required' => 'El total es requerido',
            'sucursal.required' => 'La sucursal es requerida',
            'cheque.unique' => 'El número de cheque ya existe'
        ];
    }
}
