<?php

namespace App\Http\Requests;

use App\Period\Entities\Period;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

            //TiposMovimiento
            'txt_codigo' => 'sometimes|required|unique:movimientotipo,codigo',
            'txt_descripcion' => 'sometimes|required',
            'txt_codsunat' => 'sometimes|required',

            //Ingresos||Salidas
            'txt_unegocio' => 'sometimes|required',
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
            'txt_tcambio' => 'sometimes|required|numeric|min:1',
            'txt_almacen' => 'sometimes|required',
            'txt_movimiento' => 'sometimes|required',
            'txt_tercero' => 'sometimes|required',
            'txt_doc_venta' => 'sometimes|required',
            'txt_moneda' => 'sometimes|required',
            'txt_glosa' => 'sometimes|required',

            //Provisiones
            'txt_fechadoc' => 'sometimes|required',
            'txt_sucursal' => 'sometimes|required',
            'txt_tipocompra' => 'sometimes|required',
            'txt_seriedoc' => 'sometimes|required',
            'txt_numerodoc' => 'sometimes|required',
            'txt_condicionpago' => 'sometimes|required',
            'txt_igv_id' => 'sometimes|required',
            'mediopago' => 'required_with:txt_formapago',
            'importepago' => 'required_with:txt_formapago',
            'detra_fecha' => 'required_with:tipodetraccion',
            'total_detra' => 'required_with:tipodetraccion'
        ];
    }

    public function messages()
    {
        return [
            'txt_codigo.required' => 'El código es requerido',
            'txt_codigo.unique' => 'Ese código ya se encuentra registrado',
            'txt_descripcion.required' => 'La descripción es requerida',
            'txt_codsunat.required' => 'El código sunat es requerido',

            'txt_unegocio.required' => 'La unidad de negocio es requerida',
            'txt_fecha.required' => 'La fecha es requerida',  //fecha del proceso
            'txt_tcambio.required' => 'El tipo de cambio es requerido',
            'txt_tcambio.min' => 'No existe tipo de cambio',
            'txt_almacen.required' => 'El almacen es requerido',
            'txt_movimiento.required' => 'El tipo de movimiento es requerido',
            'txt_tercero.required' => 'El responsable es requerido',
            'txt_doc_venta.required' => 'El documento de venta es requerido',
            'txt_moneda.required' => 'La moneda es requerida',
            'txt_glosa.required' => 'Las observaciones son requeridas',

            'txt_fechadoc.required' => 'La fecha del documento es requerida',
            'txt_sucursal.required' => 'La sucursal es requerida',
            'txt_tipocompra.required' => 'El tipo de compra es requerido',
            'txt_seriedoc.required' => 'La serie del documento es requerida',
            'txt_numerodoc.required' => 'El numero del documento es requerido',
            'txt_condicionpago.required' => 'La condición de pago es requerida',
            'txt_igv_id.required' => 'El IGV es requerido',
            'mediopago.required_with' => 'El medio de pago es requerido',
            'importepago.required_with' => 'El importe es requerido',
            'detra_fecha.required_with' => 'La fecha de la detracción es requerida',
            'total_detra.required_with' => 'El importe de la detracción es requerido'
        ];
    }
}
