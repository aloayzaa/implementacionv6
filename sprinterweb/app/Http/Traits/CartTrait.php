<?php

namespace App\Http\Traits;

use App\AccountCte\Entities\MovementCte;
use App\AccountingPlan\Entities\AccountingPlan;
use App\DocumentToPay\Entities\DocumentToPay;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
trait CartTrait
{
    public function lista($tipo)
    {
        $carts = Cart::instance($tipo)->content();
        return DataTables::of($carts)->make();
    }

    public function SoloCabeceras($items, $referencias){
        Cart::instance($this->instancia)->destroy();  //Siempre se destruye, se llena con inyección :v
        foreach ($items as $item) {
            foreach (Cart::instance($referencias)->content() as $cart) {
                if ($item['id'] == $cart->id) {
                    $referencia['id'] = $cart->id;
                    $referencia['documento'] = $cart->options->documento;
                    $data['aplicar'] = $item['aplicar'];
                    $this->agregar_detalle_desde_referencia($cart, $data);
                }
            }
        };
    }

    public function SoloCabecerasProductos($items, $referencias){
       // Cart::instance($this->instancia)->destroy();  //para acumular productos
      //  dd($items);
        foreach ($items as $item) {
            foreach (Cart::instance($referencias)->content() as $cart) {
                if ($item['id'] == $cart->id) {
                    $referencia['id'] = $cart->id;
                    $data['cantidad'] = $item['cantidad']; //puede venir de afuera
                    $data['precio'] = $item['precio'];
                    $data['descuento'] = $item['descuento'];

                        $var = Cart::instance($this->instancia)->content();
                        if(!$var->contains('id', $cart->id)){
                            $this->agregar_producto($cart, $data);
                        }else{
                            foreach (Cart::instance($this->instancia)->content() as $detalle){
                                // dd($detalle->id .'------'.$cart->id );
                                if ($detalle->id == $cart->id) {
                                    $this->actualizar_producto($detalle, $data);
                                }
                            }
                        }

                }
            }
        };
    }

    public function UnaCabeceraVariosDetalles($items, $referencias){
        foreach ($items as $item) {
            foreach (Cart::instance($referencias)->content() as $cart) {
                $index = explode('|', $item['id']);
                if ($index[0] == $cart->id && $index[1] == $cart->name) {

                   $consulta = MovementCte::cta($cart->id);

                   if($consulta){
                       foreach ($consulta as $consulta){
                           $data['referencia_id'] = $consulta->id;
                           $data['docrefer'] = $consulta->docrefer;
                           $data['cuenta_id'] = $consulta->cuenta_id;
                           $data['glosa'] = $consulta->glosa;
                           $data['total'] = $consulta->total;
                           $this->agregar_detalle_desde_orden($cart, $data);
                       }

                   }
                   return $cart->id;
                }
            }
        };
    }

    public function llenarCarritoAsientoDiario($asientosDetalle)
    {

        foreach ($asientosDetalle as $asientoDetalle) {

            Cart::add(['id' => $asientoDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $asientoDetalle->parent_id,
                    'item' => $asientoDetalle->item,

                    'cuenta_id' => $asientoDetalle->cuenta_id,
                    'cuenta' => $asientoDetalle->cuenta['codigo'],
                    'CCosto_id' => $asientoDetalle->centrocosto_id,
                    'CCosto_codigo' => $asientoDetalle->codigo_ccosto,
                    'CCosto_desc' => $asientoDetalle->descripcion_ccosto,

                    'cargomn' => $asientoDetalle->cargomn,
                    'abonomn' => $asientoDetalle->abonomn,
                    'cargome' => $asientoDetalle->cargome,
                    'abonome' => $asientoDetalle->abonome,

                    'glosa' => $asientoDetalle->glosa,
                    'tercero_id' => $asientoDetalle->tercero_id,
                    'codigo' => $asientoDetalle->tercero['codigo'],
                    'nombre' => $asientoDetalle->tercero['descripcion'],

                    'referencia_id' => $asientoDetalle->referencia_id,
                    'codigo_ref' => DocumentToPay::find($asientoDetalle->referencia_id)['codigoprovision'],

                    'producto_id' => $asientoDetalle->producto_id,
                    'producto_codigo' => $asientoDetalle->producto['codigo'],
                    'producto_desc' => $asientoDetalle->producto['descripcion'],

                    'tablareferencia' => $asientoDetalle->tablareferencia,

                ]
            ]);
        }
    }

    public function llenarCarritoPedidoAlmacen($pedidoDetalle, $fecha, $almacen_id)
    {

        foreach ($pedidoDetalle as $pedidoDetalle) {

            $stock = $this->almacenProcedure->getStock($almacen_id, $pedidoDetalle->producto_id, '', '', $fecha, 0, 0);

            Cart::add(['id' => $pedidoDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $pedidoDetalle->parent_id,
                    'item' => $pedidoDetalle->item,
                    'producto_id' => $pedidoDetalle->producto_id,
                    'producto_codigo' => $pedidoDetalle->producto['codigo'],   //objetos
                    'producto_desc' => $pedidoDetalle->producto['descripcion'],
                    'unid_id' => $pedidoDetalle->umedida_id,
                    'um' => $pedidoDetalle->unidadmedida['codigo'],
                    'lote' => $pedidoDetalle->lote,
                    'serie' => $pedidoDetalle->serie,
                    'fechaDetalle' => $pedidoDetalle->vencimiento,
                    'ubicacion' => '',
                    'cantidad' => $pedidoDetalle->cantidad,
                    'stock' => $stock['0']->stock,
                    'CCosto_id' => $pedidoDetalle->centrocosto_id,
                    'CCosto_codigo' => $pedidoDetalle->codigo_ccosto,
                    'CCosto_desc' => $pedidoDetalle->descripcion_ccosto,
                ]
            ]);
        }
    }

    public function llenarCarritoIngresoAlmacen($ingresosDetalle, $ordencompra_id)
    {

        foreach ($ingresosDetalle as $ingresoDetalle) {

            Cart::instance($this->instancia)->add(['id' => $ingresoDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $ingresoDetalle->parent_id,
                    'item' => $ingresoDetalle->item,
                    'producto_id' => $ingresoDetalle->producto_id,
                    'producto_codigo' => $ingresoDetalle->codigo_producto,
                    'producto_desc' => $ingresoDetalle->descripcion_producto,
                    'unid_id' => $ingresoDetalle->umedida_id,
                    'um' => $ingresoDetalle->codigo_umedida,
                    'lote' => $ingresoDetalle->lote,
                    'fechaDetalle' => $ingresoDetalle->vencimiento,
                    'serie' => $ingresoDetalle->serie,

                    'cantidad' => $ingresoDetalle->cantidad,
                    'stock' => 0,
                    'ubicacion' => $ingresoDetalle->ubicacion,
                    'precio' => $ingresoDetalle->precio,
                    'importe' => $ingresoDetalle->importe,

                    'CCosto_id' => $ingresoDetalle->centrocosto_id,
                    'CCosto_codigo' => $ingresoDetalle->codigo_ccosto,
                    'CCosto_desc' => $ingresoDetalle->descripcion_ccosto,

                    'editable' => $ordencompra_id ? 0 : 1,

                ]
            ]);
        }
    }

    public function llenarCarritoSalidaAlmacen($salidaDetalle)
    {

        foreach ($salidaDetalle as $salidaDetalle) {

            Cart::add(['id' => $salidaDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $salidaDetalle->parent_id,
                    'item' => $salidaDetalle->item,
                    'producto_id' => $salidaDetalle->producto_id,
                    'producto_codigo' => $salidaDetalle->producto['codigo'],   //objetos
                    'producto_desc' => $salidaDetalle->producto['descripcion'],
                    'unid_id' => $salidaDetalle->umedida_id,
                    'um' => $salidaDetalle->codigo_umedida,
                    'lote' => $salidaDetalle->lote,
                    'fechaDetalle' => $salidaDetalle->vencimiento,
                    'serie' => $salidaDetalle->serie,
                    'ubicacion' => $salidaDetalle->ubicacion,
                    'cantidad' => $salidaDetalle->cantidad,
                    'stock' => 0,
                    'costoUnitario' => $salidaDetalle->precio,
                    'costoTotal' => 1,
                    'peso' => $salidaDetalle->peso,
                    'CCosto_id' => $salidaDetalle->centrocosto_id,
                    'CCosto_codigo' => $salidaDetalle->codigo_ccosto,
                    'CCosto_desc' => $salidaDetalle->descripcion_ccosto,
                ]
            ]);
        }
    }

    public function llenarCarritoPedidoVenta($pedidoDetalle, $fecha)
    {

        foreach ($pedidoDetalle as $pedidoDetalle) {
//dd($pedidoDetalle);
            Cart::add(['id' => $pedidoDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $pedidoDetalle->parent_id,
                    'item' => $pedidoDetalle->item,
                    'producto_id' => $pedidoDetalle->producto_id,
                    'producto_codigo' => $pedidoDetalle->producto['codigo'],   //objetos
                    'producto_desc' => $pedidoDetalle->producto['descripcion'],
                    'umedida_id' => $pedidoDetalle->umedida_id,
                    'umedida' => $pedidoDetalle->unidadmedida['codigo'],
                    'lote' => $pedidoDetalle->lote,
                    'serie' => $pedidoDetalle->serie,
                    'precio' => $precio = $pedidoDetalle->precio,
                    'descuento' => $descuento = $pedidoDetalle->descuento,
                    'importe' => $pedidoDetalle->importe,
                    'precio' => $descuento == '' ? $precio : $precio - $precio * ($descuento / 100),
                    'fechaDetalle' => $pedidoDetalle->vencimiento,
                    'ubicacion' => '',
                    'cantidad' => $pedidoDetalle->cantidad,
                    'CCosto_id' => $pedidoDetalle->centrocosto_id,
                    'CCosto_codigo' => $pedidoDetalle->codigo_ccosto,
                    'CCosto_desc' => $pedidoDetalle->descripcion_ccosto,
                    'stock' => 0
                ]
            ]);
        }
    }

    public function llenarCarrito($detalles)
    {

        foreach ($detalles as $detalle) {
            Cart::instance('detalle_factura')->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $detalle->parent_id,
                    'rowid' => '',
                    'item' => $detalle->item,
                    'producto_id' => $detalle->producto['id'],
                    'cuenta_id' => $detalle->cuenta_id,
                    'prdto_codigo' => $detalle->producto['codigo'],
                    'prdto_descripcion' => $detalle->producto['descripcion'],
                    'cantidad' => $detalle->cantidad,
                    'prec_unit' => $detalle->preciolista,
                    'porc_dscto' => $detalle->descuento,
                    'prec_dscto' => $detalle->precio,
                    'impt_det' => $detalle->importe,
                    'umedida_id' => $detalle->uMedida['id'],
                    'umedida_codigo' => $detalle->uMedida['codigo'],
                    'umedida_descripcion' => $detalle->uMedida['descripcion'],
                ]
            ]);
        }
    }
    //ordenes de compra detalle
    public function llenarCarritoOrdenCompraDetalle($ordenCompraDetalle){

        foreach ($ordenCompraDetalle as $od) {

            Cart::add(['id' => $od->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $od->parent_id,
                    'item' => $od->item,
                    'producto_id' => $od->producto_id,
                    'producto_codigo' => $od->productocodigo,
                    'producto_descripcion' => $od->productodescripcion,
                    'umedida_id' => $od->umedida_id,
                    'umedida_codigo' => $od->umedidacodigo,
                    'cantidad' => $od->cantidad,
                    'precio' => $od->precio,
                    'descuento' => $od->descuento,
                    'valor' => $od->valor,
                    'subtotal' => $od->subtotal,
                    'importe' => $od->importe,
                    'preciovta' => 0.00,
                    //'atendido' =>
                    'esafecto' => $od->esafecto,
                    'usuario' => $od->usuario,
                    'volumen' => $od->productovolumen,
                    'glosa' => $od->glosa,
                    'tipoproducto' => $od->productotipo,
                    'percepcion' => '',
                ]
            ]);

        }

    }



    public function llenarCarritoProvision($provisionDetalle)
    {

        foreach ($provisionDetalle as $provisionDetalle) {

            Cart::instance($this->instancia)->add(['id' => $provisionDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $provisionDetalle->parent_id,
                    'item' => $provisionDetalle->item,

                    'referencia_id' => $provisionDetalle->referencia_id,
                    'codigo_ref' => DocumentToPay::find($provisionDetalle->referencia_id)['codigoprovision'],

                    'cuenta_id' => $provisionDetalle->cuenta_id,
                    'cuenta_codigo' => $provisionDetalle->cuenta['codigo'],
                    'cuenta_desc' => $provisionDetalle->glosa, ///////

                    'CCosto_id' => $provisionDetalle->centrocosto_id,
                    'CCosto_codigo' => $provisionDetalle->codigo_ccosto,
                    'CCosto_desc' => $provisionDetalle->descripcion_ccosto,

                    'op_id' => $provisionDetalle->ordentrabajo_id,
                    'op_codigo' => "",

                    'importe' => $provisionDetalle->importe,

                    'esnuevo' => $provisionDetalle->esnuevo,
                ]
            ]);
        }
    }

    public function llenar_ingresos_referencia($documentos){

        foreach ($documentos as $doc) {
            Cart::add(['id' => $doc->id, 'name' => $doc->item, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $doc->id,
                    'elegido' => $doc->elegido,
                    'documento' => $doc->documento,
                    'estado' => $doc->estado,
                    'fecha' => $doc->fecha,
                    'nombre' => $doc->nombre,
                    'glosa' => $doc->glosa,

                    'codigo' => $doc->codigo,
                    'producto_id' => $doc->producto_id,
                    'umedida_id' => $doc->umedida_id,
                    'cantidad' => $doc->cantidad,
                    'precio' => $doc->precio,
                    'importe' => $doc->importe,
                    'atendido' => $doc->atendido,

                    'editable' => 0,

                    'prd_cod' => $doc->prd_cod,
                    'prd_dsc' => $doc->prd_dsc,
                    'prd_serie' => $doc->prd_serie,
                    'ume_cod' => $doc->ume_cod,
                    'centrocosto_id' => $doc->centrocosto_id,
                    'cco_cod' => $doc->cco_cod,
                    'cco_dsc' => $doc->cco_dsc,
                    'ordentrabajo_id' => $doc->ordentrabajo_id,
                    'otrabajo' => $doc->otrabajo,
                    'actividad_id' => $doc->actividad_id,
                    'act_cod' => $doc->act_cod,
                    'act_dsc' => $doc->act_dsc,
                    'proyecto_id' => $doc->proyecto_id,
                    'pry_cod' => $doc->pry_cod,
                    'pry_dsc' => $doc->pry_dsc,
                    'aplicar' => $doc->aplicar,
                    'ventana' => $doc->ventana,
                    'item' => $doc->item,
                    'preciovta' => $doc->preciovta,
                    'tolerancia' => $doc->tolerancia,
                    'pideserie' => $doc->pideserie,
                ]
            ]);
        }
    }


    private function llenar_detalle_referencia($referencia){

        Cart::instance('ordenes-compra-detail')->add(['id' => $referencia->options->id, 'name' => $referencia->options->item, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'elegido' => $referencia->options->elegido,
                'id' => $referencia->options->id,
                'documento' => $referencia->options->documento,
                'estado' => $referencia->options->prd_dsc,
                'fecha' => $referencia->options->fecha,
                'nombre' => $referencia->options->nombre,
                'glosa' => $referencia->options->glosa,

                'codigo' => $referencia->options->codigo,
                'producto_id' => $referencia->options->producto_id,
                'umedida_id' => $referencia->options->umedida_id,
                'cantidad' => $referencia->options->cantidad,
                'precio' => $referencia->options->precio,
                'importe' => $referencia->options->importe,
                'atendido' => $referencia->options->atendido,

                'prd_cod' => $referencia->options->prd_cod,
                'prd_dsc' => $referencia->options->prd_dsc,
                'prd_serie' => $referencia->options->prd_serie,
                'ume_cod' => $referencia->options->ume_cod,
                'centrocosto_id' => $referencia->options->centrocosto_id,
                'cco_cod' => $referencia->options->cco_cod,
                'cco_dsc' => $referencia->options->cco_dsc,
                'ordentrabajo_id' => $referencia->options->ordentrabajo_id,
                'otrabajo' => $referencia->options->otrabajo,
                'actividad_id' => $referencia->options->actividad_id,
                'act_cod' => $referencia->options->act_cod,
                'act_dsc' => $referencia->options->act_dsc,
                'proyecto_id' => $referencia->options->proyecto_id,
                'pry_cod' => $referencia->options->pry_cod,
                'pry_dsc' => $referencia->options->pry_dsc,
                'aplicar' => $referencia->options->aplicar,
                'ventana' => $referencia->options->ventana,
                'item' => $referencia->options->item,
                'preciovta' => $referencia->options->preciovta,
                'tolerancia' => $referencia->options->tolerancia,
                'pideserie' => $referencia->options->pideserie,
            ]
        ]);

    }

    private function llenar_references_to_provision($referencias){

        $detalles = collect();
        foreach(Cart::instance('detalle_docxpagar')->content() as $cart){   //Para ver si ya ha sido agregado
            $detalles->push(
                ['id' => $cart->options->referencia_id, 'aplicar' => $cart->options->importe]
            );
        }

        foreach ($referencias as $referencia) {

                Cart::instance('provision-references')->add(['id' => $referencia->id, 'name' => 'un name', 'qty' => 1, 'price' => 0.00,
                    'options' => [
                        'elegido' => $detalles->contains('id', $referencia->id) ? 1 : 0, //Aquí comparo
                        'id' => $referencia->id,
                        'aplicar' => $detalles->contains('id', $referencia->id) ? $detalles->where('id', $referencia->id)->pluck('aplicar') : $referencia->aplicar,
                        'documento' => $referencia->documento,
                        'cuenta_id' => $referencia->cuenta_id,
                        $cuenta = AccountingPlan::findOrFail($referencia->cuenta_id),
                        'cuenta_cod' => $cuenta != null ? $cuenta->codigo : null,
                        'cuenta_desc' => $cuenta != null ? $cuenta->descripcion : null,
                        'fechaproceso' => $referencia->fechaproceso,
                        'moneda' => $referencia->moneda,
                        'vencimiento' => $referencia->fecha,
                        'saldomn' => $referencia->saldomn,
                        'saldome' => $referencia->saldome,
                        'glosa' => $referencia->glosa,
                    ]
                ]);

        }

    }

    public function llenar_ordenes_to_provision($ordenes){

        foreach ($ordenes as $orden) {
            Cart::add(['id' => $orden->id, 'name' => $orden->producto_id, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $orden->id,
                    'elegido' => $orden->elegido,
                    'documento' => $orden->documento,
                    'estado' => $orden->estado,
                    'fecha' => $orden->fecha,
                    'nombre' => $orden->nombre,
                    'codigo' => $orden->codigo,
                    'glosa' => $orden->glosa,

                    'producto_id' => $orden->producto_id,
                    'umedida_id' => $orden->umedida_id,
                    'cantidad' => $orden->cantidad,
                    'precio' => $orden->precio,
                    'importe' => $orden->importe,
                    'atendido' => $orden->atendido,

                    'prd_cod' => $orden->prd_cod,
                    'prd_dsc' => $orden->prd_dsc,
                    'ume_cod' => $orden->ume_cod,
                    'aplicar' => $orden->aplicar,

                    'cuenta_id' => $orden->cuenta_id,
                    'cuenta' => $orden->cuenta,
                    'centrocosto_id' => $orden->centrocosto_id,
                    'cco_cod' => $orden->cco_cod,
                    'cco_dsc' => $orden->cco_dsc,

                    'proyecto_id' => $orden->proyecto_id,
                    'pry_cod' => $orden->pry_cod,
                    'pry_dsc' => $orden->pry_dsc,
                    'actividad_id' => $orden->actividad_id,
                    'act_cod' => $orden->act_cod,
                    'act_dsc' => $orden->act_dsc,

                    'ordentrabajo_id' => $orden->ordentrabajo_id,
                    'otrabajo' => $orden->otrabajo,

                ]
            ]);
        }
    }


    private function llenar_ordendetail_to_provision($orden){

        Cart::instance('ordenesdetail-provision')->add(['id' => $orden->id, 'name' => $orden->name, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => $orden->options->id,
                'elegido' => $orden->options->elegido,
                'documento' => $orden->options->documento,
                'estado' => $orden->options->estado,
                'fecha' => $orden->options->fecha,
                'nombre' => $orden->options->nombre,
                'codigo' => $orden->options->codigo,
                'glosa' => $orden->options->glosa,

                'producto_id' => $orden->options->producto_id,
                'umedida_id' => $orden->options->umedida_id,
                'cantidad' => $orden->options->cantidad,
                'precio' => $orden->options->precio,
                'importe' => $orden->options->importe,
                'atendido' => $orden->options->atendido,

                'prd_cod' => $orden->options->prd_cod,
                'prd_dsc' => $orden->options->prd_dsc,
                'ume_cod' => $orden->options->ume_cod,
                'aplicar' => $orden->options->aplicar,

                'cuenta_id' => $orden->options->cuenta_id,
                'cuenta' => $orden->options->cuenta,
                'centrocosto_id' => $orden->options->centrocosto_id,
                'cco_cod' => $orden->options->cco_cod,
                'cco_dsc' => $orden->options->cco_dsc,

                'proyecto_id' => $orden->options->proyecto_id,
                'pry_cod' => $orden->options->pry_cod,
                'pry_dsc' => $orden->options->pry_dsc,
                'actividad_id' => $orden->options->actividad_id,
                'act_cod' => $orden->options->act_cod,
                'act_dsc' => $orden->options->act_dsc,

                'ordentrabajo_id' => $orden->options->ordentrabajo_id,
                'otrabajo' => $orden->options->otrabajo,
            ]
        ]);

    }

    public function agregar_carrito($tabla,$instancia,$condicion,$request){
        $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first();

        if ($ultimoCart) {
            $item = $ultimoCart->options->item;
        } else {
            $ultimoBd = DB::table($tabla)->where($condicion)->get()->last();;
            $item = !$ultimoBd ? 0 : $ultimoBd->item;
        }

        $campos = DB::getSchemaBuilder()->getColumnListing($tabla);
        $campo = array();
        foreach($campos as $c){

            if($c == 'item'){
                $campo[$c] = $item + 1;
            }else{
                $campo[$c] = $request->input($c.'_'.$tabla);
            }

        }

        Cart::instance($instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => $campo]);

        $rowId = Cart::instance($instancia)->content()->last();
        return $rowId->rowId;
    }

    public function editar_carrito($tabla,$instancia,$rowID,$request){
        $item = Cart::instance($instancia)->get($rowID);
        $campos = DB::getSchemaBuilder()->getColumnListing($tabla);
        $campo = array();
        foreach($campos as $c){
            if($c != 'item'){
                $campo[$c] = $request->input($c.'_'.$tabla);
            }
        }
        $options = $item->options->merge($campo);

        Cart::instance($instancia)->update($rowID, [
            'options' => $options]);

        $rowId = Cart::instance($instancia)->content()->last();
        return $rowId->rowId;
    }

    public function eliminar_carrito(Request $request)
    {
        Cart::instance($request->instancia_eliminar)->remove($request->rowId);
        session()->push($request->instancia_eliminar, $request->item);
    }
}
