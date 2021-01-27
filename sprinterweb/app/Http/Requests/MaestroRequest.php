<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaestroRequest extends FormRequest
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

            //Producto||Partidas Arancelarias
            'txt_codigo' => 'sometimes|required|unique:parancelaria,codigo,'.$this->id,
            'txt_descripcion' => 'sometimes|required',
            //Producto || Grupo Productos
            'txt_codigo_gp' =>'sometimes|required|unique:grupoproducto.codigo,'.$this ->id,
            'txt_descripcion_gp' =>'sometimes|required|unique:grupoproducto.descripcion,'.$this ->id,
            //Terceros || Clasificacion
            'txt_codigo_terceros' => 'sometimes|required|unique:clasetercero,codigo,'.$this->id,
            'txt_descripcion_terceros' => 'sometimes|required|unique:clasetercero,descripcion,'.$this->id,
            //Terceros || Vendedor
            'txt_codigo_vendedores' => 'sometimes|required|unique:vendedor,codigo,'.$this->id,
            'txt_descripcion_vendedores' => 'sometimes|required|unique:vendedor,descripcion,'.$this->id,
            'modal_marca_id' =>'sometimes|required',
            'txt_email' => 'sometimes|email|nullable',
            //Terceros || Documento identidad
              'txt_codigo_identi' => 'sometimes|required|unique:documentoide,codigo,'.$this->id,
            'txt_descripcion_ident' => 'sometimes|required|unique:documentoide,descripcion,'.$this->id,
            //Terceros || Documento Comercial
            'code_doc'=> 'sometimes|required|unique:documentocom,codigo,'.$this->id,
            'name'=> 'sometimes|required|unique:documentocom,descripcion,'.$this->id,
            //Terceros  || condiciones de pago
            'txt_codigo_cp'=> 'sometimes|required|unique:condicionpago,codigo,'.$this->id,
            'txt_descripcion_cp'=> 'sometimes|required|unique:condicionpago,descripcion,'.$this->id,
            //Costos ||  centro de costos
            'code_cost' => 'sometimes|required|unique:centrocosto,codigo,'.$this->id,
            'description_costs' => 'sometimes|required|unique:centrocosto,descripcion,'.$this->id,
            'code_activ'=>'sometimes|unique:actividad,codigo,'.$this->id,
            'description_activ'=>'sometimes|unique:actividad,descripcion,'.$this->id,
            //costos || plan de cuentas
            'code_pcg' => 'sometimes|required|unique:pcg,codigo,'.$this->id,
            'descripcion' => 'sometimes|required',
            //Otros || Sucursal
            'code' => 'sometimes|required|unique:sucursal,codigo,'.$this->id,
            'description' => 'sometimes|required|unique:sucursal,descripcion,'.$this->id,
            //Otros || Almacen
            'code_almacen' => 'sometimes|required|unique:almacen,codigo,'.$this->id,
            'description_almacen' => 'sometimes|required|unique:almacen,descripcion,'.$this->id,
            'subsidiary_almacen' => 'sometimes|required',
            'shortname_almacen' => 'sometimes|required|unique:almacen,nombrecorto,'.$this->id,
            'stablishment_almacen' => 'sometimes|required',
            //Otros || Monedas
            'code_md'=> 'sometimes|required|unique:moneda,codigo,'.$this->id,
            'description_md'=> 'sometimes|required|unique:moneda,descripcion,'.$this->id,
            //Otros || Subdiarios
            'code_sd'=> 'sometimes|required|unique:subdiario,codigo,'.$this->id,
            'description_sd'=> 'sometimes|required|unique:subdiario,descripcion,'.$this->id,
            //Productos || Unidades de medidas
            'txt_codigo_unidad' => 'sometimes|required|unique:umedida,codigo,'.$this->id,
            'txt_descripcion_unidad' => 'sometimes|required|unique:umedida,descripcion,'.$this->id,
            // terceros || Catalogos de terceros - contactos
            'nombre_tercero_contacto' => 'sometimes|required',
            'email_tercero_contacto' => 'sometimes|required|email',
            // terceros || Catalogos de terceros - cuentas bancarias
            'banco_id_tercero_cuenta' => 'sometimes|required',
            'cuenta_tercero_cuenta' => 'sometimes|required',
            'moneda_id_tercero_cuenta' => 'sometimes|required',
            'tipocuenta_tercero_cuenta' => 'sometimes|required',
            // terceros || Catalogos de terceros - marcas
            'marca_id_tercero_marca' => 'sometimes|required',
            // terceros || Catalogos de terceros - rubro
            'tiporubro_id_tercero_rubro' => 'sometimes|required',
            // terceros || Catalogos de terceros - locales anexos
            'ubigeo_id_tercero_direccion' => 'sometimes|required',
            'via_nombre_tercero_direccion' => 'sometimes|required',
            // terceros || Catalogos de terceros - facturar
            'ruc_tercero_empresa' => 'sometimes|required',
            'razonsocial_tercero_empresa' => 'sometimes|required',
            'direccion_tercero_empresa' => 'sometimes|required',
            'tipo_tercero_empresa' => 'sometimes|required',
            'cbo_um' => 'sometimes|required',
            //Terceros || Tipos de transaccion
            'txt_codigo_tipo_trans' => 'sometimes|required|unique:tipotransaccion,codigo,'.$this->id,
            'txt_descripcion_tipo_trans' => 'sometimes|required|unique:tipotransaccion,descripcion,'.$this->id,
            //Terceros || Tipos de via
            'txt_codigo_tipo_via' => 'sometimes|required|unique:tipovia,codigo,'.$this->id,
            'txt_descripcion_tipo_via' => 'sometimes|required|unique:tipovia,descripcion,'.$this->id,
            //Terceros || Tipos de zona
            'txt_codigo_tipo_zona' => 'sometimes|required|unique:tipozona,codigo,'.$this->id,
            'txt_descripcion_tipo_zona' => 'sometimes|required|unique:tipozona,descripcion,'.$this->id,
            //Terceros || rubro
            'txt_codigo_rubro' => 'sometimes|required|unique:tiporubro,codigo,'.$this->id,
            'txt_descripcion_rubro' => 'sometimes|required|unique:tiporubro,descripcion,'.$this->id,
            //Terceros || Categorias Cta Cte
            'txt_codigo_categoriasctacte' => 'sometimes|required|unique:categctacte,codigo,'.$this->id,
            'txt_descripcion_categoriasctacte' => 'sometimes|required|unique:categctacte,descripcion,'.$this->id,
            'modal_doc_asig_id' => 'sometimes|required',
            //TERCEROS || PRODUCTOS || MARCA
            'code_marca' => 'sometimes|required|unique:marca,codigo,'.$this->id,
            'tradeName' => 'sometimes|required|unique:marca,descripcion,'.$this->id
        ];
    }
    public function messages()
    {
        return [
            //agregar detalle
            'txt_codigo.unique' => 'Este Dato ya se encuntra registrado',
            'txt_descripcion.required' => 'Ingrese una descripcion',
            //agregar detalle a Terceros || Clasificacion
            'txt_codigo_terceros.required' => 'Ingrese el código',
            'txt_codigo_terceros.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_terceros.required' => 'Ingrese una descripción',
            'txt_descripcion_terceros.unique' => 'La descripción ya se enceuntra registrado',
            //Agregar detalle a Terceros || Vendedor
            'txt_codigo_vendedores.required' => 'Ingrese el código',
            'txt_codigo_vendedores.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_vendedores.required' => 'Ingrese una descripción',
            'txt_descripcion_vendedores.unique' => 'La descripción ya se encuentra registrado',
            'modal_marca_id.required' => 'Seleccione la marca',
            'txt_email.email' => 'Ingrese un Email valido',
            //Agregar  Documento de identidad || Tercero
            'txt_codigo_identi.unique'=>'Este Dato ya se encuentra Registrado',
             'txt_descripcion_ident.unique'=> 'Ingrese la descripcion',
             'txt_descripcion_ident.required'=> 'Ingrese la descripcion',
            //Agregar Documento comercial
             'code_doc.unique'=>'Este Dato ya se encuentra Registrado',
            'name.unique'=>'La descripcion ya Existe',
            //Costos ||  centro de costos
            'code_cost.unique' => 'El código ya se encuentra registrado',
            'description_costs.unique' => 'La descripción ya se encuentra registrado',
            'description_activ.unique' => 'La descripción ya se encuentra registrado',
            'code_activ.unique'=> 'El codigo ya existe',
            //terceros || condicioens de pagos
            'txt_codigo_cp.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_cp.unique' => 'La descripción ya se encuentra registrado',
            //costos || plan de cuentas
            'code_pcg.required'=> 'El código es requerido',
            'code_pcg.unique'=> 'El código ya se encuentra registrado',
            'descripcion.required'=> 'La descripcion es requerida',
            //Otros || Sucursal
            'code.required' => 'Ingrese el código',
            'code.unique' => 'El código ya se encuentra registrado',
            'description.required' => 'Ingrese una descripción',
            'description.unique' => 'La descripción ya se encuentra registrado',
            //Otros || Almacen
            'code_almacen.required' => 'Ingrese el código',
            'code_almacen.unique' => 'El código ya se encuentra registrado',
            'description_almacen.required' => 'Ingrese una descripción',
            'description_almacen.unique' => 'La descripción ya se encuentra registrado',
            'subsidiary_almacen.required' => 'Seleccione la sucursal',
            'shortname_almacen.required' => 'Ingrese un Nombre Corto',
            'shortname_almacen.unique' => 'El nombre corto ya se encuentra registrado',
            'stablishment_almacen.required' => 'Ingrese un Código de Establecimiento',
            //Otros || Monenadas
            'code_md.unique' => 'El código ya se encuentra registrado',
            'description_md.unique' => 'La descripción ya se encuentra registrado',
            //Otros || subdiarios
            'code_sd.unique' => 'El código ya se encuentra registrado',
            'description_sd.unique' => 'La descripción ya se encuentra registrado',
            //Productos || Unidades de medidas
            'txt_codigo_unidad.required' => 'Ingrese el código',
            'txt_codigo_unidad.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_unidad.required' => 'Ingrese una descripción',
            'txt_descripcion_unidad.unique' => 'La descripción ya se encuentra registrado',
            'cbo_um.required' => 'Seleccione la unidad',
            // terceros || Catalogos de terceros - contactos
            'nombre_tercero_contacto.required' => 'El nombre del contacto es obligatorio',
            'email_tercero_contacto.required' => 'EL email es obligatorio',
            'email_tercero_contacto.email' => 'Email incorrecto',
            // terceros || Catalogos de terceros - cuentas bancarias
            'banco_id_tercero_cuenta.required' => 'El banco es obligatorio',
            'cuenta_tercero_cuenta.required' => 'El número de cuenta es obligatorio',
            'moneda_id_tercero_cuenta.required' => 'La moneda es obligatorio',
            'tipocuenta_tercero_cuenta.required' => 'El tipo de cuenta es obligatorio',
            // terceros || Catalogos de terceros - marcas
            'marca_id_tercero_marca.required' => 'La marca es obligatorio',
            // terceros || Catalogos de terceros - rubro
            'tiporubro_id_tercero_rubro.required' => 'El rubro es obligatorio',
            // terceros || Catalogos de terceros - locales anexos
            'ubigeo_id_tercero_direccion.required' => 'EL ubigeo es obligatorio',
            'via_nombre_tercero_direccion.required' => 'La dirección es obligatorio',
            // terceros || Catalogos de terceros - facturar
            'ruc_tercero_empresa.required' => 'El número de documento es obligatorio',
            'razonsocial_tercero_empresa.required' => 'El nombre es obligatorio',
            'direccion_tercero_empresa.required' => 'La dirección es obligatorio',
            'tipo_tercero_empresa.required' => 'El tipo es obligatorio',
            //Terceros || Tipos de transaccion
            'txt_codigo_tipo_trans.required' => 'Ingrese el código',
            'txt_codigo_tipo_trans.unique' => 'El código ya existe',
            'txt_descripcion_tipo_trans.required' => 'Ingrese la descripción',
            'txt_descripcion_tipo_trans.unique' => 'La descripción ya existe',
            //Terceros || Tipos de via
            'txt_codigo_tipo_via.required' => 'Ingrese una descripción',
            'txt_codigo_tipo_via.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_tipo_via.required' => 'Ingrese una descripción',
            'txt_descripcion_tipo_via.unique' => 'La descripción ya se encuentra registrado',
            //Terceros || Tipos de zona
            'txt_codigo_tipo_zona.required' => 'Ingrese el código',
            'txt_codigo_tipo_zona.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_tipo_zona.required' => 'Ingrese la descripción',
            'txt_descripcion_tipo_zona.unique' => 'La descripción ya se encuentra registrado',
            //Terceros || Rubro
            'txt_codigo_rubro.required' => 'Ingrese el código',
            'txt_codigo_rubro.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_rubro.required' => 'Ingrese la descripción',
            'txt_descripcion_rubro.unique' => 'La descripción ya se encuentra registrado',
            //Terceros || Categorias Cta Cte
            'txt_codigo_categoriasctacte.required' => 'Ingrese un código',
            'txt_codigo_categoriasctacte.unique' => 'El código ya se encuentra registrado',
            'txt_descripcion_categoriasctacte.required' => 'Ingrese una descripción',
            'txt_descripcion_categoriasctacte.unique' => 'La descripción ya se encuentra registrado',
            'modal_doc_asig_id.required' => 'Seleccione el documento',
            //MAESTROS || PRODUCTOS || MARCA
            'code_marca.required' => 'Ingrese un código',
            'code_marca.unique' => 'El código ya se encuentra registrado',
            'tradeName.required' => 'Ingrese una descripción',
            'tradeName.unique' => 'La descripción ya se encuentra registrado',
            //PRODUCTOS || GRUPO PRODUCTOS
            'txt_codigo_gp.unique'=>'El código ya se encuentra registrado',
            'txt_descripcion_gp.unique'=>'La descripción ya se encuentra registrado',

        ];
    }
}
