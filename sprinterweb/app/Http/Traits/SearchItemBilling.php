<?php

namespace App\Http\Traits;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\SalesTypes\Entities\SaleType;
use App\Taxes\Entities\Taxes;
use App\Documents\Entities\Commercial;
use App\Currency\Entities\Currency;
use App\AffectsIgv\Entities\AffectIgv;
use App\Products\Entities\Productos;
use Illuminate\Http\Request;
use App\Parancelaria\Entities\Parancelaria;

trait SearchItemBilling
{

    protected function inicializar_buscaritem($request){ 

        if ( isset($request->id) ) { // cuando se llama desde el modal

            $producto = collect(Cart::instance($this->cart_productos)->content())->firstWhere('id', $request->id);

        }

        if ( isset($request->item) ) { // cuando se llama desde el docxpagar_detalle

            $producto = collect(Cart::instance($this->cart_docxpagar_detalle)->content())->firstWhere('id', $request->item);

        }

        $producto = (isset($producto)) ? $producto : null;

        $tipo_venta = ( isset($request->tipo_venta) ) ? SaleType::select('esgratuito')->where('id', $request->tipo_venta)->first() : null;

        $igv = ( isset($request->igv) ) ? Taxes::select('codigo','tipocalculo')->where('id', $request->igv)->first() : null;

        $documentocom = ( isset($request->tipo_doc) ) ? Commercial::find($request->tipo_doc) : null;

        $moneda = ( isset($request->moneda) ) ? Currency::find($request->moneda) : null;

        $tipoafectaigv = ( isset($request->tipo_afectacion_igv) ) ? AffectIgv::select('codigo')->where('id', $request->tipo_afectacion_igv)->first() : null;


        $r['tipo_venta'] = $tipo_venta;
        $r['igv'] = $igv;
        $r['producto'] = $producto;
        $r['documentocom'] = $documentocom;
        $r['moneda'] = $moneda;
        $r['tipoafectaigv'] = $tipoafectaigv;

        return $r;

    }


    protected function buscaritem($request){ // formulario: mov_facturaventa, método: buscaritem

        // se usa cuando se desencadena el evento blur de los inputs del modal o del docxpagar_detalle

        extract($this->inicializar_buscaritem($request));

        $cantidad_productos_repetidos = 0;
        $cantidad = (empty($request->cantidad)) ? 0 : $request->cantidad; // input
        $descuento = (empty($request->descuento)) ? 0 : $request->descuento; // input
        $preciolista = (empty($request->precio)) ? 0 : $request->precio; // input
        $serie = (empty($request->serie)) ? '' : $request->serie; // input

        $productodb = Productos::select('tipoproducto', 'peso')->where('id', $producto->options->id)->first();

        $tipoprecio = ( $producto->options->tipoprecio == 0 ) ? 1 : $producto->options->tipoprecio; // se actuliza el tipo_precio

        $precio = ( $tipo_venta->esgratuito == 1 && $igv->codigo == '99' ) ? 0 : $preciolista * ( 1 - ( $request->descuento / 100 ) ); // se actuliza el precio

        $cantidad = ( $tipoprecio == 1 || $tipoprecio == 4 ) ? $cantidad : $productodb->peso;

        $importe = $cantidad * $precio; // se actualiza el importe

        $parancelaria = Parancelaria::selectparentcmd($producto->options->id);

        $isc = 0;
        $icbper = 0;

        if ($parancelaria) {

            if ( $parancelaria->tipo == 'N' ) { // calcular $icbper y $isc

                if ( trim($parancelaria->tipoisc) == 'ICBPER' ) {
                    $icbper = $cantidad * $parancelaria->isc;  // se actualiza icbper
                } else {
                    $isc = $cantidad * $parancelaria->isc; // se actualiza isc
                }

            } else {

                if (trim($parancelaria->tipoisc) == 'ICBPER') {
                    $icbper = $importe * ($parancelaria->isc/100); // se actualiza icbper
                } else {
                    $isc = $importe * ($parancelaria->isc/100); // se actualiza isc
                }

            }

        }

        $icbper = ( $moneda->tipo != 'N' ) ?  $icbper / $request->tcambio : $icbper; // se actualiza icbper

        $importe = $importe + ( $isc * 1.18 ); // se actualiza el importe

        $data['tipoprecio'] = $tipoprecio;
        $data['precio'] = $precio; // el valor alterado es precio y el que no cambia preciolista (en detalle y en el modal)
        $data['cantidad'] = $cantidad;
        $data['importe'] = $importe;
        $data['isc'] = $isc;
        $data['icbper'] = $icbper;
        $data['descuento'] = $descuento; // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        $data['preciolista'] = $preciolista;   // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        $data['serie'] = $serie; // adicional no esta en el clasic pero se necesita, para actualizar el datatable
        return $data;

        // después normalmente llama al sumar en el clasic

    }

    protected function buscaritem_todoelcarrito($request, $data){ // solo cuando viene del modal

        extract($this->inicializar_buscaritem($request)); // no se envia id, item ok

        foreach ( $data as $d ) {

            $cart = collect(Cart::instance($this->cart_productos)->content())->firstWhere('id', $d->ids->value);
            $productodb = Productos::select('tipoproducto', 'peso')->where('id', $d->ids->value)->first();
            $serie = $d->series->value;
            $cantidad = ($d->cantidades->value == null) ? 0 : $d->cantidades->value;
            $preciolista = ($d->precios->value == null) ? 0 : $d->precios->value;
            $descuento = ($d->dsctos->value == null) ? 0 : $d->dsctos->value;

            // validar serie

            if ( $cart->options->pideserie == 1 ) {

                if ($serie != null) {

                    $request->request->add(['serie' => $serie]); // agrega al request un valor sin perder su clase
                    $request->request->add(['producto_id' => $cart->id]); // agrega al request un valor sin perder su clase
                    $valida_serie = $this->valida_serie($request);

                    if ( $valida_serie !== null ) : // si hay error
                        return $valida_serie;
                    endif;

                }

            }

            // validar cantidad

            if ( $cart->options->pideserie ) { // en caso pida serie deberia ir un solo producto, único de la serie

                if ( $cantidad > 1 ) :
                    return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser mayor a 1, en el producto: '. $cart->options->codigo), 'cantidad' => 1 ],422);
                endif;

            }

            if ( $cantidad < 0 && $this->lnegativo == 0 ) {

                return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser negativo, en el producto ' . $cart->options->codigo), 'cantidad' => 0 ],422);

            }

            /* solo se valida cuando coinciden los lotes
            if ( $cantidad > $cart->options->stock && $this->lstock == 1 ) {

                return response()->json(['errors' => array('detalle' => 'La cantidad excede al stock'), 'cantidad' => 0 ],422);
    
            }*/
            
            // validar precio

            // en cuanto al preciolista, deberia consultar al método buscar_producto() y realizar el
            // proceso para obtener el precio correcto (este seria el precio origen)

            $origen = collect(Cart::instance($this->cart_productos)->content())->firstWhere('id', $cart->id); // consultar al origen en el caso del modal: cart_productos
            if ( $this->editar_precio == 2 ) { // no se puede editar el precio
                if ($preciolista != $origen->options->preciolista ) :

                    return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio del producto: ' . $cart->options->codigo), 'precio' => $origen->options->preciolista ],422);

                endif;
            }

            /*
            if ( $this->editar_precio == 1 ) { // se puede cambiar el precio >=
                if ($preciolista < $origen->options->preciolista ) :

                    return response()->json(['errors' => array('detalle' => 'El precio del producto: ' . $cart->options->codigo . 'no puede ser menor'), 'precio' => $origen->options->preciolista ],422);

                endif;
            }*/

            // validar descuento

            if ( $descuento < 0) { // no puede ser negativo

                return response()->json(['errors' => array('detalle' => 'No puede ingresar valores negativos'), 'descuento' => $this->pdescuento ],422);

            }

            if ( $this->pdescuento < $descuento ) { // no puede ser mayor a lo asignado en la configuración por usuario

                return response()->json(['errors' => array('detalle' => 'El descuento excede lo permitido (' . $this->pdescuento . ')'), 'descuento' => $this->pdescuento ],422);

            }

            $tipoprecio = ( $cart->options->tipoprecio == 0 ) ? 1 : $cart->options->tipoprecio; // se actuliza el tipo_precio

            $precio = ( $tipo_venta->esgratuito == 1 && $igv->codigo == '99' ) ? 0 : $preciolista  * ( 1 - ( $descuento / 100 ) ); // se actuliza el precio

            // comprobar si el stock excede cuando los productos son repetidos y pertenece al mismo lote :

            if ( $this->lstock == 1 && !empty($request->crear_kardex) && $productodb->tipoproducto != 'S' && $documentocom->factor > 0 ){

                $docxpagar_detalles = collect(Cart::instance($this->cart_docxpagar_detalle)->content())->where('name', $cart->id);

                $cantidad_productos_repetidos = 0;

                foreach($docxpagar_detalles as $docxpagar_detalle){ // sumar la cantidad de los mismos productos y con el mismo lote

                    $cantidad_productos_repetidos +=  ( $cart->options->lote == $docxpagar_detalle->options->lote ) ? $docxpagar_detalle->options->cantidad : 0;

                }

                if ( ($cantidad_productos_repetidos + $cantidad) > $cart->options->stock ) {

                    $cantidad_mensaje = ($cart->options->stock < 0 ) ? 0 : $cart->options->stock - $cantidad_productos_repetidos; // se actualiza la cantidad

                    return response()->json(['errors' => array('detalle' => 'No hay suficiente stock : ' . $cantidad_mensaje . ' para el producto : ' . $cart->options->codigo ) ],422);

                }

            }

            $cantidad = ( $tipoprecio == 1 || $tipoprecio == 4 ) ? $cantidad : $productodb->peso;

            $importe = $cantidad * $precio; // se actualiza el importe

            $parancelaria = Parancelaria::selectparentcmd($cart->options->id);

            $isc = 0;
            $icbper = 0;

            if ($parancelaria) { // en caso exista data

                if ( $parancelaria->tipo == 'N' ) { // calcular $icbper y $isc

                    if ( trim($parancelaria->tipoisc) == 'ICBPER' ) {
                        $icbper = $cantidad * $parancelaria->isc;  // se actualiza icbper
                    } else {
                        $isc = $cantidad * $parancelaria->isc; // se actualiza isc
                    }

                } else {

                    if (trim($parancelaria->tipoisc) == 'ICBPER') {
                        $icbper = $importe * ($parancelaria->isc/100); // se actualiza icbper
                    } else {
                        $isc = $importe * ($parancelaria->isc/100); // se actualiza isc
                    }

                }

            }

            $icbper = ( $moneda->tipo != 'N' ) ?  $icbper / $request->tcambio : $icbper; // se actualiza icbper

            $importe = $importe + ( $isc * 1.18 ); // se actualiza el importe


            $item = Cart::instance($this->cart_productos)->get($cart->rowId);

            $options = $item->options->merge([ // actualizo el cart_productos porque uno los inputs con este carrito y al final se agrega al carrito de docxpagar_detalle
                'tipoprecio' => $tipoprecio,
                'precio' => $precio,
                'cantidad' => $cantidad, // input modal
                'importe' => $importe,
                'isc' => $isc,
                'icbper' => $icbper,
                'serie' => $serie, // input modal
                'preciolista' => $preciolista, // input modal
                'descuento' => $descuento // input modal
            ]);

            Cart::instance($this->cart_productos)->update($cart->rowId, [
                'options' => $options]);


        }

    }

    public function buscaritem_todoelcarrito_store($request){ // solo cuando viene del docxpagar_detalle

        extract($this->inicializar_buscaritem($request));

        // no llamar o crear un objeto de nombre producto, al inicializar(en este caso) envio un id(haciendo referencia al id del docxpagar), el inicializar espera tambien un id del carrito, puede devolver el objeto producto con información no requerida

        foreach ( Cart::instance($this->cart_docxpagar_detalle)->content() as $cart ) {

            $serie = $request->input('serie'.$cart->id);
            $cantidad = ($request->input('cantidad'.$cart->id) == null) ? 0 : $request->input('cantidad'.$cart->id);
            $preciolista = ($request->input('precio'.$cart->id) == null) ? 0 : $request->input('precio'.$cart->id);
            $descuento = ($request->input('descuento'.$cart->id) == null) ? 0 : $request->input('descuento'.$cart->id);
            $cantidad_productos_repetidos = 0;

            // validar serie

            if ( $cart->options->pideserie == 1 ) {

                if ($serie != null) {

                    $request->request->add(['serie' => $serie]); // agrega al request un valor sin perder su clase
                    $request->request->add(['producto_id' => $cart->name]); // agrega al request un valor sin perder su clase
                    $valida_serie = $this->valida_serie($request);

                    if ( $valida_serie !== null ) : // si hay error
                        return $valida_serie;
                    endif;

                }

            }

            // validar cantidad

            if ( $cart->options->pideserie ) { // en caso pida serie deberia ir un solo producto, único de la serie

                if ( $cantidad > 1 ) :
                    return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser mayor a 1, en el producto: '. $cart->options->codigo), 'cantidad' => 1 ],422);
                 endif;

            }

            if ( $cantidad < 0 && $this->lnegativo == 0 ) {
                return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser negativo, en el producto ' . $cart->options->codigo), 'cantidad' => 0 ],422);

            }

            /* solo se valida cuando coinciden los lotes
            if ( $cantidad > $cart->options->stock && $this->lstock == 1) {

                return response()->json(['errors' => array('detalle' => 'La cantidad excede al stock'), 'cantidad' => 0 ],422);
    
            }*/

            // validar precio

            // en cuanto al preciolista, deberia consultar al método buscar_producto() y realizar el
            // proceso para obtener el precio correcto (este seria el precio origen)


            if ( $this->editar_precio == 2 ) { // no se puede editar el precio
                if ( $preciolista != $cart->options->preciolista ) :
                    return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio del producto: ' . $cart->options->codigo), 'precio' => $cart->options->preciolista ],422);
                endif;
            }

            /*
            if ( $this->editar_precio == 1 ) { // se puede cambiar el precio >=
                if ($preciolista < $cart->options->preciolista ) :
                    return response()->json(['errors' => array('detalle' => 'El precio del producto: ' . $cart->options->codigo . 'no puede ser menor'), 'precio' => $cart->options->preciolista ],422);
                endif;
            }
            */

            // validar descuento

            if ( $descuento < 0) { // no puede ser negativo
                return response()->json(['errors' => array('detalle' => 'No puede ingresar valores negativos'), 'descuento' => $this->pdescuento ],422);

            }

            if ( $this->pdescuento < $descuento ) { // no puede ser mayor a lo asignado en la configuración por usuario
                return response()->json(['errors' => array('detalle' => 'El descuento excede lo permitido (' . $this->pdescuento . ')'), 'descuento' => $this->pdescuento ],422);

            }

            $tipoprecio = ( $cart->options->tipoprecio == 0 ) ? 1 : $cart->options->tipoprecio; // se actuliza el tipo_precio

            $precio = ( $tipo_venta->esgratuito == 1 && $igv->codigo == '99' ) ? 0 : $preciolista  * ( 1 - ( $descuento / 100 ) ); // se actuliza el precio

            // comprobar si el stock excede cuando los productos son repetidos y pertenece al mismo lote :

            if ( $this->lstock == 1 && !empty($request->crear_kardex) && $cart->options->tipoproducto != 'S' && $documentocom->factor > 0 ){

                $docxpagar_detalles = collect(Cart::instance($this->cart_docxpagar_detalle)->content())->where('name', $cart->name);
                $cantidad_productos_repetidos = 0;
                foreach($docxpagar_detalles as $docxpagar_detalle){ // sumar la cantidad de los mismos productos y con el mismo lote

                    if ($cart->id != $docxpagar_detalle->id){
                        $cantidad_productos_repetidos +=  ( $cart->options->lote == $docxpagar_detalle->options->lote ) ? $docxpagar_detalle->options->cantidad : 0;
                    }

                }

                if ( ($cantidad_productos_repetidos + $cantidad) > $cart->options->stock ) {

                    $cantidad_mensaje = ($cart->options->stock < 0 ) ? 0 : $cart->options->stock - $cantidad_productos_repetidos; // se actualiza la cantidad

                    return response()->json(['errors' => array('detalle' => 'No hay suficiente stock : ' . $cantidad_mensaje . ' para el producto : ' . $cart->options->codigo ) ],422);

                }

            }

            $cantidad = ( $tipoprecio == 1 || $tipoprecio == 4 ) ? $cantidad : $cart->options->peso;

            $importe = $cantidad * $precio; // se actualiza el importe

            $parancelaria = Parancelaria::selectparentcmd($cart->options->id);

            $isc = 0;
            $icbper = 0;

            if ($parancelaria) { // en caso exista data

                if ( $parancelaria->tipo == 'N' ) { // calcular $icbper y $isc

                    if ( trim($parancelaria->tipoisc) == 'ICBPER' ) {
                        $icbper = $cantidad * $parancelaria->isc;  // se actualiza icbper
                    } else {
                        $isc = $cantidad * $parancelaria->isc; // se actualiza isc
                    }

                } else {

                    if (trim($parancelaria->tipoisc) == 'ICBPER') {
                        $icbper = $importe * ($parancelaria->isc/100); // se actualiza icbper
                    } else {
                        $isc = $importe * ($parancelaria->isc/100); // se actualiza isc
                    }

                }

            }

            $icbper = ( $moneda->tipo != 'N' ) ?  $icbper / $request->tcambio : $icbper; // se actualiza icbper

            $importe = $importe + ( $isc * 1.18 ); // se actualiza el importe

            $item = Cart::instance($this->cart_docxpagar_detalle)->get($cart->rowId);

            $options = $item->options->merge([ // actualizo el cart_docxpagar_detalle para posteriormente agregar a la tabla docxpagar_detalle
                'tipoprecio' => $tipoprecio,
                'precio' => $precio,
                'cantidad' => $cantidad, // input docxpagar_detalle
                'importe' => $importe,
                'isc' => $isc,
                'icbper' => $icbper,
                'serie' => $serie, // input docxpagar_detalle
                'preciolista' => $preciolista, // input docxpagar_detalle
                'descuento' => $descuento, // input docxpagar_detalle
                'centro_costo_id' =>  ( empty($request->input('centro_costo_detalle'.$cart->id)) ) ? null : $request->input('centro_costo_detalle'.$cart->id), // select docxpagar_detalle
                'tipoafectaigv_id' => ( empty($request->input('tipo_afecta_detalle'.$cart->id)) ) ? null : $request->input('tipo_afecta_detalle'.$cart->id) // select docxpagar_detalle
            ]);

            Cart::instance($this->cart_docxpagar_detalle)->update($cart->rowId, [
                'options' => $options]);

        }
    }

}
