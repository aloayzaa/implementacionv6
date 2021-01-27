<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products\Entities\Productos;
use App\Costs\Entities\Costs;
use App\AffectsIgv\Entities\AffectIgv;
use App\PointSale\Entities\PointSale;
use App\Panel\Companies\Entities\Pempresa;
use App\SalesTypes\Entities\SaleType;
use App\Currency\Entities\Currency;
use Yajra\DataTables\Facades\DataTables;
use App\Products\Entities\ProductoSerie;
use App\Users\Entities\UserByCompany;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\Http\Traits\SearchItemBilling;
use App\Documents\Entities\Commercial;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class DetailBillingController extends Controller
{

    use SearchItemBilling;

    private $lunico;
    private $pdescuento;
    private $ltarifasuc;
    private $lstock;
    private $editar_precio;

    private $docxpagar_id; // ID DEL DOCXPAGAR

    private $cart_docxpagar_detalle = 'docxpagar_detalle';
    private $cart_productos = 'productos';

    public function __construct($id)
    {
        $this->lunico = Pempresa::get_parametro('FTCONUNICO')->valor; // Sólo se permite una línea por producto en la facturación
        $this->ltarifasuc = Pempresa::get_parametro('VTA_TARIFA')->valor; // Tipo Tarifario
        $this->lstock = Pempresa::get_parametro('FTCONSTOCK')->valor; // Validar disponibilidad de stock en la facturación
        $this->editar_precio = Pempresa::get_parametro('EDT_PVENTA')->valor; // El usuario puede cambiar precio de venta

        $usuario = UserByCompany::select('pdescuento')->where('id', Auth::id())->first();
        $this->pdescuento = $usuario->pdescuento; // ver si el usuario puede aplicar descuento

        $this->docxpagar_id = $id;

    }


    public function buscar_producto(Request $request){

        Cart::instance($this->cart_productos)->destroy();

        extract($this->buscar_producto_inicializar($request, new Productos()));

        $busquedapdto = new SearchPdtoController($tabla, $valor, $where, $almacen_id, $fecha_proceso, $nventa);

        if ( !empty($codigo = trim($request->producto_codigo)) )                { $productos = $busquedapdto->codigo_valid($codigo); }
        elseif ( !empty($descripcion = trim($request->producto_descripcion)) )  { $productos = $busquedapdto->descripcion_valid($descripcion, trim($request->producto_presentacion)); }
        elseif ( !empty($presentacion = trim($request->producto_presentacion)) ){ $productos = $busquedapdto->presentacion_valid($presentacion, $descripcion); }
        
        return $this->listado_productos($productos, $precio, $sucursal, $esgratuito, $noafecto);
    }

    private function listado_productos($productos, $precio, $sucursal, $esgratuito, $noafecto){ // formando listado para el modal de productos

        $precio_0 = $esgratuito + $noafecto; //  si ambos valores son verdaderos (son 1) // clasic, objeto: txtidproducto1, método: Valid

        // EN CASO TENGA RAZÓN QUE EL PRECEDMIENTO HACE LA MISMA VALIDACIÓN Y  NO ES NECESARIO OBTENER PRECIO POR TXTIDPRODUCTO1 MÉTODO VALID
        $precio = $this->obtener_precio_procedimiento_almacenado($precio);
        $pdescuento = ($this->pdescuento > 0)  ? 1 : 0; // ver si el usuario puede aplicar descuento

        foreach ($productos as $producto){

            $productodb = Productos::select('precio1', 'precio2', 'precio3', 'precio4', 'precio5', 'precio6', 'pideserie', 'tipoproducto')->where('id', $producto->id)->first();
            //$precio = $this->obtener_precio($precio, $sucursal, $producto->id, $productodb, $producto->umedida_id); // clasic, txtidproducto1, método Valid
            
            Cart::instance($this->cart_productos)->add(['id' => $producto->id, 'name' => $producto->descripcion, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'cumseps' => $producto->cumseps,
                    'descripcion' => $producto->descripcion,
                    'caracteristicas' => $producto->caracteristicas,
                    'umedida' => $producto->umedida,
                    'ubicacion' => $producto->ubicacion,
                    'stock' => $producto->stock,
                    'precio' => ($precio_0 == 2) ? 0 : $producto->$precio, //en caso el precio sea del procedimiento almacenado // en caso sea del valid txtidproducto1 : $precio
                    'esafecto' => $producto->esafecto,
                    'flag' => $producto->flag,
                    'estado' => $producto->estado,
                    'umedida_id' => $producto->umedida_id,
                    'marca' => $producto->marca,
                    'editar_precio' => $this->editar_precio,
                    'pideserie' => $productodb->pideserie,
                    'serie' => '',
                    'tipoprecio' => 1, // este  valor se asigna en el evento del formulario nuevalinea
                    'lote' => '', // se ingresa en el modal o en el detalle
                    'importe' => 0,
                    'preciolista' => ($precio_0 == 2) ? 0 : $producto->$precio, //en caso el precio sea del procedimiento almacenado // en caso sea del valid txtidproducto1 : $precio // en principio (txtidproducto1, método: Valid) es igual a precio (cuando el precio de la bd sea cero), pero el precio puede cambiar y este se queda con el valor (ver buscaritem)
                    'cantidad' => 0,
                    'isc' => '',
                    'icbper' => '',
                    'pdescuento' => $pdescuento
                ]
            ]);

        }

        return DataTables::of(collect(Cart::instance($this->cart_productos)->content()))->make();
    }    


    private function buscar_producto_inicializar($request, $instancia){

        $punto_venta = PointSale::selectidcmd_billing($request->punto_venta); // se usa para sacar el almacen // lo correcto es buscar en tipo de venta
        $tipo_venta = SaleType::findOrFail($request->tipo_venta); // ver clasic, objeto: txtidproducto, método: Valid

        switch ($instancia->getTable()){ // evaluar origen

            case 'producto': // buscando producto

                $valor = $request->producto_codigo;
                $where = 'p.estado=1';

            break;

            case 'productoserie': // validar serie, clasic objeto: txtserie, método: keyPress

                $valor = $request->serie;
                $where = 'p.estado=1 and p.id = ' . $request->producto_id;

            break;

            default:
                $valor = null;
                $where = '';
            break;
        }

        $data['tabla'] = $instancia;
        $data['valor'] = $valor;
        $data['where'] = $where;
        $data['almacen_id'] = $punto_venta->almacen_id;
        $data['fecha_proceso'] = $request->fecha_proceso;
        $data['nventa'] = 1; //formulario: mov_facturaventa, objeto : pfdetalle - Txtidproducto1, propiedad : nventa. En productoserie no se usa
        $data['precio'] = $tipo_venta->precio; // número de precio
        $data['sucursal'] = $punto_venta->sucursal_id;
        $data['esgratuito'] = $tipo_venta->esgratuito;
        $data['noafecto'] = $tipo_venta->noafecto;

        return $data;

    }


    public function agregar_docxpagar_detalle_producto($productos, $request){

        $item = $this->obtener_item();

        foreach ($productos as $producto){

            $productodb = Productos::selectidcmd($producto->options->id); // clasic, objeto: txtxidproducto1, método: Valid            

            if(empty($productodb)){
                
                return response()->json(['errors' => array('detalle' => "el producto " . $producto->options->codigo . " esta mal registrado" )],422);

            }


            $getctaventapdto = $this->getctaventapdto($producto, $request, $productodb); // clasic, objeto: txtxidproducto1, método: Valid
            $centro_costo = Costs::select('*')->where("estado", 1)->get();
            $tipo_afecta = AffectIgv::select('*')->where("estado", 1)->get();
            $puntoventa = PointSale::find($request->punto_venta); // obtener centrocosto_id, clasic formulario: mov_facturaventa, método: nuevalinea
            
            $producto_existente = $this->se_permite_repetir_producto($producto);
            
            if ($producto_existente){

                $this->editar_docxpagar_detalle($producto_existente->rowId, $producto, $productodb, $getctaventapdto, $centro_costo, $tipo_afecta, $puntoventa);

            } else {
                
                $this->agregar_docxpagar_detalle($item, $producto, $productodb, $getctaventapdto, $centro_costo, $tipo_afecta, $puntoventa);

            }

            $item++;

        }

      //  dd(Cart::instance($this->cart_docxpagar_detalle)->content());

    }


    private function editar_docxpagar_detalle($rowId, $producto, $productodb, $getctaventapdto, $centro_costo, $tipo_afecta, $puntoventa){

        $item = Cart::instance($this->cart_docxpagar_detalle)->get($rowId);

        $options = $item->options->merge([
            'id' => $producto->options->id,
            'codigo' => $producto->options->codigo,
            'cumseps' => $producto->options->cumseps,
            'descripcion' => $producto->options->descripcion,
            'caracteristicas' => $producto->options->caracteristicas,
            'umedida_codigo' => $producto->options->umedida, // clasic, objeto: txtidproducto, método: Valid, es ume_cod
            'ubicacion' => $producto->options->ubicacion,
            'stock' => $producto->options->stock,
            'esafecto' => $producto->options->esafecto,
            'flag' => $producto->options->flag,
            'estado' => $producto->options->estado,
            'umedida_id' => $producto->options->umedida_id, // clasic, objeto: txtidproducto, método: Valid, es um1
            'marca' => $producto->options->marca,
            'editar_precio' => $producto->options->editar_precio,
            'pideserie' => $producto->options->pideserie,
            'serie' => ($producto->options->serie) ? $producto->options->serie : '', // modal o detalle
            'tipoprecio' => $producto->options->tipoprecio, // este  valor se asigna en el evento del formulario nuevalinea
            'lote' => ($producto->options->lote) ? $producto->options->lote : '',
            'importe' => $producto->options->importe,
            'preciolista' => $producto->options->preciolista,  // modal o detalle
            'cantidad' => $producto->options->cantidad, // modal o detalle
            'precio' =>  $producto->options->precio,
            'descuento' => $producto->options->descuento, // modal o detalle
            'isc_icbper' => 0, // es la suma del isc + icbper
            'referencia_codigo' => $getctaventapdto['docrefer'],
            'centro_costo_id' => (isset($puntoventa->centrocosto_id)) ? $puntoventa->centrocosto_id : null,
            'centro_costo_codigo' => '',
            'centro_costo_descripcion' => '',
            'op' => '',
            'isc' => '',
            'icbper' => '',
            'peso' => $productodb->peso,
            'consumo' => $productodb->consumo,
            'tipoproducto' => $productodb->tipoproducto,
            'utilidad' => $productodb->utilidad,
            'pidelote' => $productodb->pidelote, // tal vez en el futuro se considere en el modal
            'um2' => $productodb->ucompra_id,
            'conversion' => $productodb->conversion,
            'cuenta_id' => $getctaventapdto['cuenta_id'],
            'cuenta_codigo' => $getctaventapdto['cuenta_codigo'],
            'pide_ccosto' => $getctaventapdto['pide_ccosto'],
            'esnuevo' => $getctaventapdto['esnuevo'],
            'referencia_id' => $getctaventapdto['referencia_id'],
            'tipoafectaigv_id' => $getctaventapdto['tipoafectaigv_id'],
            'pdescuento' => $producto->options->pdescuento,
            'actividad_id' => null,
            'actividad_codigo' => '',
            'actividad_descripcion' => '',
            'proyecto_id'=> null,
            'proyecto_codigo' => '',
            'proyecto_descripcion' => '',
            'centro_costo_data' => $centro_costo,
            'tipoafectaigv_data' => $tipo_afecta
        ]);

        Cart::instance($this->cart_docxpagar_detalle)->update($rowId, [
            'options' => $options]);

    }

    private function agregar_docxpagar_detalle($item, $producto, $productodb, $getctaventapdto, $centro_costo, $tipo_afecta, $puntoventa){


        Cart::instance($this->cart_docxpagar_detalle)->add(['id' => $item, 'name' => $producto->options->id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => $producto->options->id,
                'codigo' => $producto->options->codigo,
                'cumseps' => $producto->options->cumseps,
                'descripcion' => $producto->options->descripcion,
                'caracteristicas' => $producto->options->caracteristicas,
                'umedida_codigo' => $producto->options->umedida, // clasic, objeto: txtidproducto, método: Valid, es ume_cod
                'ubicacion' => $producto->options->ubicacion,
                'stock' => $producto->options->stock,
                'esafecto' => $producto->options->esafecto,
                'flag' => $producto->options->flag,
                'estado' => $producto->options->estado,
                'umedida_id' => $producto->options->umedida_id, // clasic, objeto: txtidproducto, método: Valid, es um1
                'marca' => $producto->options->marca,
                'editar_precio' => $producto->options->editar_precio,
                'pideserie' => $producto->options->pideserie,
                'serie' => ($producto->options->serie) ? $producto->options->serie : '', // modal o detalle
                'tipoprecio' => $producto->options->tipoprecio, // este  valor se asigna en el evento del formulario nuevalinea
                'lote' => ($producto->options->lote) ? $producto->options->lote : '',
                'importe' => $producto->options->importe,
                'preciolista' => $producto->options->preciolista,  // modal o detalle
                'cantidad' => $producto->options->cantidad, // modal o detalle
                'precio' =>  $producto->options->precio,
                'descuento' => $producto->options->descuento, // modal o detalle
                'isc_icbper' => 0, // es la suma del isc + icbper
                'referencia_codigo' => $getctaventapdto['docrefer'],
                'centro_costo_id' => (isset($puntoventa->centrocosto_id)) ? $puntoventa->centrocosto_id : null,
                'centro_costo_codigo' => '',
                'centro_costo_descripcion' => '',
                'op' => '',
                'isc' => '',
                'icbper' => '',
                'peso' => $productodb->peso,
                'consumo' => $productodb->consumo,
                'tipoproducto' => $productodb->tipoproducto,
                'utilidad' => $productodb->utilidad,
                'pidelote' => $productodb->pidelote, // tal vez en el futuro se considere en el modal
                'um2' => $productodb->ucompra_id,
                'conversion' => $productodb->conversion,
                'cuenta_id' => $getctaventapdto['cuenta_id'],
                'cuenta_codigo' => $getctaventapdto['cuenta_codigo'],
                'pide_ccosto' => $getctaventapdto['pide_ccosto'],
                'esnuevo' => $getctaventapdto['esnuevo'],
                'referencia_id' => $getctaventapdto['referencia_id'],
                'tipoafectaigv_id' => $getctaventapdto['tipoafectaigv_id'],
                'pdescuento' => $producto->options->pdescuento,
                'actividad_id' => null,
                'actividad_codigo' => '',
                'actividad_descripcion' => '',
                'proyecto_id'=> null,
                'proyecto_codigo' => '',
                'proyecto_descripcion' => '',
                'centro_costo_data' => $centro_costo,
                'tipoafectaigv_data' => $tipo_afecta
            ]
        ]);


    }


    public function obtener_item(){

        $ultimoCart = Cart::instance($this->cart_docxpagar_detalle)->content()->sortByDesc("id")->first(); // en este caso el id es el item

        if ($ultimoCart == null) {

            $ultimoBd = DocumentToPayDetail::where('parent_id', session()->get('id'))->get()->last(); // buscar en los detalles de la factura actual, no del documento que viene por referencia
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {

            $item = $ultimoCart->id; // id = item

        }        

        $item++;

        return $item;

    }


    private function se_permite_repetir_producto($docxpagar_detalle){

        if ($this->lunico == 1) {

          return collect(Cart::instance($this->cart_docxpagar_detalle)->content())->firstWhere('name', $docxpagar_detalle->options->id); // Validar si el producto existe

        }

        return null;
    }


    private function getctaventapdto($docxpagar_detalle, $request, $productodb){

        $getctaventapdto = Productos::getctaventapdto_billing($docxpagar_detalle->options->id, $request->tipo_venta); // clasic, objeto: txtidproducto1, método Valid
        $tipoventa = SaleType::select('esanticipo')->where('id', $request->tipo_venta)->first();
        $moneda = Currency::select('tipo')->where('id', $request->moneda)->first();

        $cuenta_id = null;
        $cuenta_codigo = '';
        $pide_ccosto = 0;
        $tipo_auxiliar = '';
        $esnuevo = 0;
        $referencia_id = 0;
        $docrefer = '';
        $tipoafectaigv_id =  $request->tipo_afectacion_igv;

        if ( $getctaventapdto ) {

            $cuenta_id = ( $getctaventapdto->cuenta_id ) ? $getctaventapdto->cuenta_id : 0; // tabla: tipoventaxfamilia, campo: cuenta_id
            $cuenta_codigo = ( $getctaventapdto->codigo ) ? $getctaventapdto->codigo : ''; // tabla: pcg, campo: codigo
            $pide_ccosto = ( $getctaventapdto->pide_ccosto ) ? $getctaventapdto->pide_ccosto : 0;  // tabla: pcg, campo: pide_ccosto
            $tipo_auxiliar = ( $getctaventapdto->tipo_auxiliar ) ? $getctaventapdto->tipo_auxiliar: ''; // tabla: pcg, campo: tipo_auxiliar

            if ( $tipoventa->esanticipo == 1 && $cuenta_codigo == '122' && $moneda->tipo != 'N' )  {

                $cuenta_id = ( $getctaventapdto->ctacosto_id ) ? $getctaventapdto->ctacosto_id : 0; // tabla: tipoventaxfamilia, campo: ctacosto_id
                $cuenta_codigo = ( $getctaventapdto->ctacosto_cod ) ? $getctaventapdto->ctacosto_cod : ''; // tabla: pcg, campo: ctacosto_cod


            }
        }

        // si la referencia_id = 0, hacer lo siguiente:

        if ( $tipo_auxiliar == 'T' && $tipoventa->esanticipo == 0) {

            $esnuevo  = 1;

        } else {

            $esnuevo = $referencia_id = 0; $docrefer = '';

        }

        if ( $productodb->esafecto == 0 ) { // Falta una condición, testear y ver si es necesario (ver clasic)

            $tipoafectaigv_id = 8;

        }


        $data['cuenta_id'] = $cuenta_id;
        $data['cuenta_codigo'] = $cuenta_codigo;
        $data['pide_ccosto'] = $pide_ccosto;
        $data['tipo_auxiliar'] = $tipo_auxiliar;
        $data['esnuevo'] = $esnuevo;
        $data['referencia_id'] = $referencia_id;
        $data['docrefer'] = $docrefer;
        $data['tipoafectaigv_id'] = $tipoafectaigv_id;

        return $data;
    }  

    
    public function obtener_docxpagar_detalle(){

        return Cart::instance($this->cart_docxpagar_detalle)->content();

    }

    public function validar_cantidad($request){

        $cantidad = $request->cantidad; // puede ser del modal o del detalle

        $id_o_item_evaluar_origen = $this->id_o_item_evaluar_origen($request); // ver origen y buscar el carrito correspondiente

        $producto = collect(Cart::instance($id_o_item_evaluar_origen['carrito'])->content())->firstWhere('id', $id_o_item_evaluar_origen['where']);

        if ( $producto->options->referencia_id > 0){ // docxpagar_detalle(cantidad) método: When

            if ( $cantidad != $producto->options->cantidad ) :  return response()->json(['errors' => array('detalle' => 'No se puede cambiar la cantidad'), 'cantidad' => $producto->options->cantidad ],422); endif;

        }

        if ( $producto->options->pideserie ) { // en caso pida serie deberia ir un solo producto, único de la serie

            if ( $cantidad > 1 ) :  return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser mayor a 1'), 'cantidad' => 1 ],422); endif;

        }

        if ( $cantidad < 0 && $this->lnegativo == 0 ) {

            return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser negativo'), 'cantidad' => 0 ],422);

        }

        /* solo se valida cuando coinciden los lotes
        if ( $cantidad > $producto->options->stock && $this->lstock == 1 ) {

            return response()->json(['errors' => array('detalle' => 'La cantidad excede al stock'), 'cantidad' => 0 ],422);

        }*/ 


        // comprobar si el stock excede cuando los productos son repetidos y pertenece al mismo lote :
        $documentocom = Commercial::find($request->tipo_doc);
        $productodb = Productos::select('tipoproducto', 'peso')->where('id', $producto->options->id)->first();

        if ( $this->lstock == 1 && $request->crear_kardex == true && $productodb->tipoproducto != 'S' && $documentocom->factor > 0 ){

            // buscar en el docxpagar_detalle los productos repetidos(en el modal no se repiten)
            $docxpagar_detalles = collect(Cart::instance($this->cart_docxpagar_detalle)->content())->where('name', $producto->options->id);

            if ( count($docxpagar_detalles) > 0 ) { // si existen docxpagar_detalles

                $cantidad_productos_repetidos = 0;

                foreach($docxpagar_detalles as $docxpagar_detalle){ // sumar la cantidad de los mismos productos y con el mismo lote

                    if ($request->item != $docxpagar_detalle->id){ // sirve cuando estamos en el detalle

                        $cantidad_productos_repetidos +=  ( $producto->options->lote == $docxpagar_detalle->options->lote ) ? $docxpagar_detalle->options->cantidad : 0;

                    }

                }

                if ( ($cantidad_productos_repetidos + $cantidad) > $producto->options->stock ) {

                    $cantidad = ($producto->options->stock < 0 ) ? 0 : $producto->options->stock - $cantidad_productos_repetidos; // se actualiza la cantidad
                    return response()->json(['errors' => array('detalle' => 'La cantidad excede el stock'), 'cantidad' => $cantidad ],422);

                }

            }

        }


        return $this->calculos_modal_o_detalle($request); // dependiendo del origen regresará los cálculos        

    }


    public function existe_stock(Request $request){

        $producto = collect(Cart::instance($this->cart_productos)->content())->firstWhere('id', $request->id);

        return ($this->lstock == 1 && $producto->options->stock == 0 ) ? response()->json(['errors' => array('detalle' => 'No hay suficiente stock!')],422) : null;

    }

    public function valida_serie(Request $request) // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    {

        extract($this->buscar_producto_inicializar($request, new ProductoSerie())); // inicializar variables

        $busquedapdto = new SearchPdtoController($tabla, $valor, $where, $almacen_id, $fecha_proceso, $nventa);

        $series = (!empty($valor)) ?  $busquedapdto->codigo_valid($valor) : null; // buscar por código serie, objeto SearchPdtoController

        $serie = collect($series)->firstWhere('codigo', $valor); // buscar concicidencia con lo enviado

        return isset($serie->codigo) ? null /* no necesito volver nada este dato ya estaría en el front  */ : response()->json( ['errors' => array('detalle' => 'La serie ' . $valor . ' no coincide') ], 422) ;

    }


    public function agregar_productos(Request $request, $data, $productos_id){

        $buscaritem_todoelcarrito = $this->buscaritem_todoelcarrito($request, $data); // validar y unir la información antes de agregar a docxpagar_detalle

        if ( $buscaritem_todoelcarrito != null) { return $buscaritem_todoelcarrito; }
                

        $productos = collect(Cart::instance($this->cart_productos)->content())->whereIn('id', $productos_id); // productos seleccionados
        
        if ($respuesta = $this->agregar_docxpagar_detalle_producto($productos, $request)){return $respuesta;} // SI EXISTE ERROR RETORNAR

    }

    private function calculos_modal_o_detalle($request){

        if ( $request->item == '' ) { // item vacío = cart_productos, en caso contrario cart_docxpagar_detalle

            extract($this->buscaritem($request)); // afecta los inputs del modal

            $this->actualizar_buscaritem_cart_productos($request->id, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper);

            $data['cantidad'] = $cantidad;

            return $data;  // modal

        } else {

            extract($this->buscaritem($request));

            // doxpagar_detalle(datatable) se actualiza no es necesario regresar la data del buscaritem()
            $this->actualizar_buscaritem_cart_docxpagar_detalle($request->item, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper, $descuento, $preciolista, $serie);

        }

    }

    private function actualizar_buscaritem_cart_productos($id, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper){

        // cuando viene del modal id = producto_id
        // actualizo estos valores por que aun no se agregar en el docxpagar_detalle

        $cart = collect(Cart::instance($this->cart_productos)->content())->firstWhere('id', $id);
        $item = Cart::instance($this->cart_productos)->get($cart->rowId);

        $options = $item->options->merge([
            'tipoprecio' => $tipoprecio,
            'precio' => $precio, //el precio afectado por el descuento
            //'cantidad' => $cantidad, //toma el valor del input del modal
            'importe' => $importe,
            'isc' => $isc,
            'icbper' => $icbper
        ]);

        Cart::instance($this->cart_productos)->update($cart->rowId, [
            'options' => $options]);

    }


    private function actualizar_buscaritem_cart_docxpagar_detalle($item, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper, $descuento, $preciolista, $serie){

        // cuando viene del detalle id = item

        $cart = collect(Cart::instance($this->cart_docxpagar_detalle)->content())->firstWhere('id', $item);
        $item = Cart::instance($this->cart_docxpagar_detalle)->get($cart->rowId);

        $options = $item->options->merge([
            'tipoprecio' => $tipoprecio,
            'precio' => $precio, //el precio afectado por el descuento
            'cantidad' => $cantidad,
            'descuento' => $descuento,
            'importe' => $importe,
            'isc' => $isc,
            'icbper' => $icbper,
            'preciolista' => $preciolista,
            'serie' => $serie
        ]);

        Cart::instance($this->cart_docxpagar_detalle)->update($cart->rowId, [
            'options' => $options]);

    }


    public function validar_precio(Request $request){

        // el precio es el que cambia en el buscaritem, el preciolista es el que se ve

        $id_o_item_evaluar_origen = $this->id_o_item_evaluar_origen($request); // ver origen y buscar el carrito correspondiente

        $producto = collect(Cart::instance($id_o_item_evaluar_origen['carrito'])->content())->firstWhere('id', $id_o_item_evaluar_origen['where']);

        if ( $producto->options->referencia_id > 0){ // docxpagar_detalle(precio) método: When

            if ($request->precio != $producto->options->preciolista ) : return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio'), 'precio' => $producto->options->preciolista ],422); endif;

        }

        // en cuanto al preciolista, deberia consultar al método buscar_producto() y realizar el
        // proceso para obtener el precio correcto (este seria el precio origen)

        if ( $this->editar_precio == 2 ) { // no se puede editar el precio
            if ($request->precio != $producto->options->preciolista ) : return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio'), 'precio' => $producto->options->preciolista ],422);  endif;
        }

        /*if ( $this->editar_precio == 1 ) { // se puede cambiar el precio >= // se podria ejecutar el buscar_producto() para buscar el precio real

            if ($request->precio < $producto->options->preciolista ) : return response()->json(['errors' => array('detalle' => 'El precio no puede ser menor'), 'precio' => $producto->options->preciolista ],422);  endif;
        }*/

        if ( $request->precio < 0 && $this->lnegativo == 0 ) {

            return response()->json(['errors' => array('detalle' => 'El precio no puede ser negativo'), 'precio' => 0 ],422);

        }

        return $this->calculos_modal_o_detalle($request); // dependiendo del origen regresará los cálculos

    }

    public function validar_descuento(Request $request){

        $id_o_item_evaluar_origen = $this->id_o_item_evaluar_origen($request); // ver origen y buscar el carrito correspondiente

        $producto = collect(Cart::instance($id_o_item_evaluar_origen['carrito'])->content())->firstWhere('id', $id_o_item_evaluar_origen['where']);

        if ( $producto->options->referencia_id > 0){ // docxpagar_detalle(precio) método: When

            if ($request->descuento != $producto->options->descuento ) : return response()->json(['errors' => array('detalle' => 'No puede cambiar el descuento'), 'descuento' => $producto->options->descuento ],422); endif;

        }

        if ( $request->descuento < 0) { // no puede ser negativo

            return response()->json(['errors' => array('detalle' => 'No puede ingresar valores negativos'), 'descuento' => $this->pdescuento ],422);

        }

        if ( $this->pdescuento < $request->descuento ) { // no puede ser mayor a lo asignado en la configuración por usuario

            return response()->json(['errors' => array('detalle' => 'El descuento excede lo permitido'), 'descuento' => $this->pdescuento ],422);

        }

        return $this->calculos_modal_o_detalle($request); // dependiendo del origen regresará los cálculos

    }


    private function obtener_precio($precio, $sucursal, $producto_id, $productodb, $umedida_id){ // clasic, objeto: txtidproducto1, método: Valid

        $precio_a_seleccionar = 1; // ver donde varia y se le asigna otro valor (preguntar)

        if ( !$this->ltarifasuc == 2 ) {
            // el precio viene de la tabla productos
            $preciodb = 'precio'.$precio; // en caso el precio de tipo de venta sea 3 ó 2
            $preciodb1 = 'precio1'; // en caso el precio de tipo de venta NO sea 3 ó 2
            $precio = ( $precio == 3 || $precio == 2 ) ? $productodb->$preciodb : $productodb->$preciodb1;

        } else {
           // el precio viene de la tabla tarifario, selcciona el precio nº : $precio_a_seleccionar(preguntar como puede variar)
           $preciodb = 'precio'; // por defecto precio, en tarifario no existe  el precio1
           $tarifario =  Rate::getpreciosuc($sucursal, $producto_id, $umedida_id);

           $precio = $tarifario->$preciodb;
        }

        return $precio; // nombre de la variable del carrito concatenado con el tipo de precio del tipo de venta ejemplo : preciox

    }


    private function id_o_item_evaluar_origen($request){

        if ( $request->item != '') { // detalle ó modal

            $data['carrito'] = $this->cart_docxpagar_detalle;
            $data['where'] = $request->item;

        } else {

            $data['carrito'] = $this->cart_productos;
            $data['where'] = $request->id;

        }

        return $data;
    }


    public function eliminar_docxpagar_detalle(Request $request){

        Cart::instance($this->cart_docxpagar_detalle)->remove($request->rowId);
        session()->push('items_to_delete', $request->item);


    }

    public function llenar_docxpagar_detalle($docxpagar_detalles){

        $centro_costo = Costs::select('*')->where("estado", 1)->get();
        $tipo_afecta = AffectIgv::select('*')->where("estado", 1)->get();

        $pdescuento = ($this->pdescuento > 0)  ? 1 : 0;

        foreach($docxpagar_detalles as $docxpagar_detalle){

            $productodb = Productos::selectidcmd($docxpagar_detalle->producto_id);

            Cart::instance($this->cart_docxpagar_detalle)->add(['id' => $docxpagar_detalle->item, 'name' => $docxpagar_detalle->producto_id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => $docxpagar_detalle->producto_id, 
                'codigo' => $docxpagar_detalle->prd_cod, 
                'cumseps' => '',
                'descripcion' => $docxpagar_detalle->glosa, 
                'caracteristicas' => $productodb->caracteristicas,
                'umedida_codigo' => $docxpagar_detalle->ume_cod,
                'ubicacion' => '', // preguntar origen
                'stock' => $docxpagar_detalle->stock, 
                'esafecto' => $productodb->esafecto,
                'flag' => 0,
                'estado' => $productodb->estado,
                'umedida_id' => $productodb->umedida_id,
                'marca' => $productodb->marca,
                'editar_precio' => $this->editar_precio,
                'pideserie' => $productodb->pideserie,
                'serie' => $docxpagar_detalle->serie, 
                'tipoprecio' => 1,
                'vencimiento' => $docxpagar_detalle->vencimiento, 
                'importe' => $docxpagar_detalle->importe, 
                'preciolista' => $docxpagar_detalle->preciolista,
                'cantidad' => $docxpagar_detalle->cantidad,
                'precio' =>  $docxpagar_detalle->precio, 
                'descuento' => $docxpagar_detalle->descuento, 
                'isc_icbper' => $docxpagar_detalle->isc + $docxpagar_detalle->icbper,
                'referencia_codigo' => $docxpagar_detalle->docrefer,  
                'centro_costo_id' => $docxpagar_detalle->centrocosto_id,
                'centro_costo_codigo' => $docxpagar_detalle->cco_cod, 
                'centro_costo_descripcion' => $docxpagar_detalle->cco_dsc,
                'op' => '', //ver origen
                'lote' => $docxpagar_detalle->lote,                                                 
                'isc' => '',
                'icbper' => '',
                'peso' => $docxpagar_detalle->peso, 
                'consumo' => '', 
                'tipoproducto' => 0, 
                'utilidad' => 0,
                'pidelote' => 0, 
                'um2' => '', 
                'conversion' => 0, 
                'cuenta_id' => $docxpagar_detalle->cuenta_id,
                'cuenta_codigo' => $docxpagar_detalle->cuenta,
                'pide_ccosto' => 0, 
                'esnuevo' => 0, 
                'referencia_id' => 0,
                'tipoafectaigv_id' => $docxpagar_detalle->tipoafectaigv_id,
                'pdescuento' => $pdescuento, //ver si se puede agregar descuentos (esto es por usuario)
                'actividad_id' => $docxpagar_detalle->actividad_id,
                'actividad_codigo' => $docxpagar_detalle->act_cod, 
                'actividad_descripcion' => $docxpagar_detalle->act_dsc,
                'proyecto_id'=> $docxpagar_detalle->proyecto_id,
                'proyecto_codigo' => $docxpagar_detalle->pry_cod, 
                'proyecto_descripcion' => $docxpagar_detalle->pry_dsc, 
                'centro_costo_data' => $centro_costo,
                'tipoafectaigv_data' => $tipo_afecta

            ]]);

        }

    }

    public function llenar_docxpagar_detalle_notacreditodebito($item, $detalle, $importe, $preciolista, $precio, $isc, $centro_costo, $tipo_afecta){

        Cart::instance($this->cart_docxpagar_detalle)->add(['id' => $item, 'name' => $detalle->options->producto_id, 'qty' => 1, 'price' => 0.00,
        'options' => [
            'id' => $detalle->options->producto_id,
            'codigo' => $detalle->options->prd_cod,
            'cumseps' => '',
            'descripcion' => $detalle->options->prd_dsc,
            'caracteristicas' => '',
            'umedida_codigo' => $detalle->options->ume_cod,
            'ubicacion' => '',
            'stock' => $detalle->options->stock,
            'esafecto' => $detalle->options->esafecto,
            'flag' => 0,
            'estado' => 1,
            'umedida_id' => $detalle->options->umedida_id,
            'marca' => 0,
            'editar_precio' => 0,
            'pideserie' => 0,
            'serie' => $detalle->options->serie,
            'tipoprecio' => $detalle->options->tipoprecio,
            'vencimiento' => $detalle->options->vencelote, // agregar a todos los llenados de docxpagar_detalle
            'importe' => $importe,
            'preciolista' => $preciolista,
            'cantidad' => $detalle->options->aplicar,
            'precio' =>  $precio,
            'descuento' => 0,
            'isc_icbper' => 0,
            'referencia_codigo' => $detalle->options->docrefer,
            'centro_costo_id' => $detalle->options->centrocosto_id,
            'centro_costo_codigo' => $detalle->options->cco_cod,
            'centro_costo_descripcion' => $detalle->options->cco_dsc,
            'op' => '',
            'lote' => $detalle->options->lote,
            'isc' => $isc,
            'icbper' => '',
            'peso' => 0,
            'consumo' => 0,
            'tipoproducto' => 0,
            'utilidad' => 0,
            'pidelote' => 0,
            'um2' => '',
            'conversion' => 0,
            'cuenta_id' => $detalle->options->cuenta_id,
            'cuenta_codigo' => $detalle->options->cuenta,
            'pide_ccosto' => ( $detalle->options->centrocosto_id > 0) ? 1 : 0,
            'esnuevo' => 0,
            'referencia_id' => $detalle->options->referencia_id, // no tiene que ver con el cart referencia(clasic, crsreferencia)
            'tipoafectaigv_id' => $detalle->options->tipoafectaigv_id,
            'pdescuento' => 0,
            'actividad_id' => $detalle->options->actividad_id,
            'actividad_codigo' => $detalle->options->act_cod,
            'actividad_descripcion' => $detalle->options->act_dsc,
            'proyecto_id'=> $detalle->options->proyecto_id,
            'proyecto_codigo' => $detalle->options->pry_cod,
            'proyecto_descripcion' => $detalle->options->pry_dsc,
            'centro_costo_data' => $centro_costo,
            'tipoafectaigv_data' => $tipo_afecta
        ]]);


    }


    public function save_details($docxpagar){

        $docxpagar_detalles = $this->obtener_docxpagar_detalle();
     
        if ($docxpagar_detalles->count() == 0) {

            session()->put('items_to_delete', []);
            return response()->json(['errors' => 'Ingrese un detalle']);

        }

        $items = Session::get('items_to_delete');

        if ($items){
            foreach ($items as $item) {
                DocumentToPayDetail::where(['parent_id' => $docxpagar->id, 'item' => $item])->delete();
            }
            session()->put('items_to_delete', []);
        }

        foreach ($docxpagar_detalles as $cart) {
            
            $existe = DocumentToPayDetail::where("parent_id", $docxpagar->id)->where("item", $cart->id)->exists();
            
            if ($existe) { //Editar                

                $item = $cart->id;
                $data['cuenta_id'] = $cart->options->cuenta_id;
                $data['glosa'] = ($cart->options->descripcion == '') ? null : $cart->options->descripcion; // clasic, objeto: txtidproducto1, método: Valid
                $data['centrocosto_id'] = $cart->options->centro_costo_id;
                //$data['actividad_id'];
                //$data['proyecto_id'];
                $data['peso'] = $cart->options->peso ;
                $data['tipoprecio'] = $cart->options->tipoprecio;
                $data['referencia_id'] = ($cart->options->referencia_id == null) ? 0 : $cart->options->referencia_id;
                $data['esnuevo'] = $cart->options->esnuevo;
                $data['producto_id'] = $cart->options->id;
                $data['serie'] = ($cart->options->serie == null) ? '' : $cart->options->serie;
                $data['lote'] = ($cart->options->lote == null) ? '' : $cart->options->lote;
                $data['umedida_id'] = $cart->options->umedida_id;
                $data['cantidad'] = ($cart->options->cantidad == null) ? 0 : $cart->options->cantidad;
                $data['preciolista'] = ($cart->options->preciolista == null) ? 0 : $cart->options->preciolista;
                $data['precio'] = ($cart->options->precio == null) ? 0 : $cart->options->precio;
                $data['isc'] = ($cart->options->isc == null) ? 0 : $cart->options->isc;
                $data['icbper'] = ($cart->options->icbper == null) ? 0 : $cart->options->icbper;
                $data['importe'] = ($cart->options->importe == null) ? 0 : $cart->options->importe;
                $data['descuento'] = ($cart->options->descuento == null) ? 0 : $cart->options->descuento;
                $data['puntoventa_id'] = $docxpagar->puntoventa_id;
                $data['tipoafectaigv_id'] = $cart->options->tipoafectaigv_id;
                $data['usuario'] = Session::get('usuario');
                
                DocumentToPayDetail::saveDetails($docxpagar->id, $item, $data);

            } else { //Crear
                
                $docxpagar_detalle = new DocumentToPayDetail();
                $docxpagar_detalle->parent_id = $docxpagar->id;
                $docxpagar_detalle->item = $cart->id;
                $docxpagar_detalle->cuenta_id = $cart->options->cuenta_id;
                $docxpagar_detalle->glosa = ($cart->options->descripcion == '') ? null : $cart->options->descripcion;// clasic, objeto: txtidproducto1, método: Valid
                $docxpagar_detalle->centrocosto_id = $cart->options->centro_costo_id;
                //$docxpagar_detalle->actividad_id;
                //$docxpagar_detalle->proyecto_id;
                $docxpagar_detalle->peso = $cart->options->peso;
                $docxpagar_detalle->tipoprecio = $cart->options->tipoprecio;
                $docxpagar_detalle->referencia_id = ($cart->options->referencia_id == null) ? 0 : $cart->options->referencia_id;
                $docxpagar_detalle->esnuevo = $cart->options->esnuevo;
                $docxpagar_detalle->producto_id = $cart->options->id;
                $docxpagar_detalle->serie = ($cart->options->serie == null) ? '' : $cart->options->serie;
                $docxpagar_detalle->lote = ($cart->options->lote == null) ? '' : $cart->options->lote;
                $docxpagar_detalle->umedida_id = $cart->options->umedida_id;
                $docxpagar_detalle->cantidad = ($cart->options->cantidad == null) ? 0 : $cart->options->cantidad;
                $docxpagar_detalle->preciolista = ($cart->options->preciolista == null) ? 0 : $cart->options->preciolista;
                $docxpagar_detalle->precio = ($cart->options->precio == null) ? 0 : $cart->options->precio;
                $docxpagar_detalle->isc = ($cart->options->isc == null) ? 0 : $cart->options->isc;
                $docxpagar_detalle->icbper = ($cart->options->icbper == null) ? 0 : $cart->options->icbper;
                $docxpagar_detalle->importe = ($cart->options->importe == null) ? 0 : $cart->options->importe;
                $docxpagar_detalle->descuento = ($cart->options->descuento == null) ? 0 : $cart->options->descuento;
                $docxpagar_detalle->puntoventa_id = $docxpagar->puntoventa_id;
                $docxpagar_detalle->tipoafectaigv_id = $cart->options->tipoafectaigv_id;
                $docxpagar_detalle->usuario = Session::get('usuario');
                
                $docxpagar_detalle->save();
                
            }
        }

    }

    protected function obtener_precio_procedimiento_almacenado($nprecio){ // nprecio es del tipo venta

        $precio_a_seleccionar = 1; // ver como varia

        // el precio en ambos casos es del procedimiento almacenado
        if ( !$this->ltarifasuc == 2 ) {

            $precio = ( $nprecio == 3 || $nprecio == 2 ) ? 'precio' . $nprecio : 'precio' . 1;

        } else {

            // el precio nº : $precio_a_seleccionar(preguntar como puede variar)
            $precio = 'precio' . $precio_a_seleccionar;

        }

        return $precio;
    }


    public function eliminar_carritos(){

        Cart::instance($this->cart_docxpagar_detalle)->destroy();
        Cart::instance($this->cart_productos)->destroy();

    }


    public function cart_productos_destroy(){

        Cart::instance($this->cart_productos)->destroy();

    }


    public function cart_docxpagar_detalle_destroy(){

        Cart::instance($this->cart_docxpagar_detalle)->destroy();
        
    }

    public function agregar_docxpagar_detalle_salidasalmacen($registro, $getctaventapdto, $request){

        $pdescuento = ($this->pdescuento > 0)  ? 1 : 0; // ver si el usuario puede aplicar descuento

        $centro_costo = Costs::select('*')->where("estado", 1)->get();
        $tipo_afecta = AffectIgv::select('*')->where("estado", 1)->get();

        $item = $this->obtener_item();

        if ( $registro->options->tipoprecio == 1 || $registro->options->tipoprecio == 4){
            
            $importe = $getctaventapdto['precio'] * $registro->options->aplica;

        }else{

            $importe = $getctaventapdto['precio'] * $registro->options->peso;

        }
     
        // YA EXISTE LA REFERENCIA ?

        if ( $this->detalle_salidaalmacen_existente($registro, $getctaventapdto) == true) { return null; }

        Cart::instance($this->cart_docxpagar_detalle)->add(['id' => $item += 1 , 'name' => $registro->options->producto_id, 'qty' => 1, 'price' => 0.00,
        'options' => [
            'id' => $registro->options->producto_id,
            'codigo' => $registro->options->prd_cod,
            'cumseps' => '',
            'descripcion' => $registro->options->prd_dsc,
            'caracteristicas' => '',
            'umedida_codigo' => $registro->options->ume_cod,
            'ubicacion' => '',
            'stock' => $registro->options->stock,
            'esafecto' => $getctaventapdto['esafecto'],
            'flag' => 0,
            'estado' => 1,
            'umedida_id' => $registro->options->umedida_id,
            'marca' => 0,
            'editar_precio' => 0,
            'pideserie' => 0,
            'serie' => '',
            'tipoprecio' => $registro->options->tipoprecio,
            'vencimiento' => $registro->options->vencelote, // agregar a todos los llenados de docxpagar_detalle
            'importe' => $importe,
            'preciolista' => $registro->options->preciovta,
            'cantidad' => $registro->options->aplica,
            'precio' =>  $getctaventapdto['precio'],
            'descuento' => 0,
            'isc_icbper' => 0,
            'referencia_codigo' => '',
            'centro_costo_id' => $registro->options->centrocosto_id,
            'centro_costo_codigo' => $registro->options->cco_cod,
            'centro_costo_descripcion' => $registro->options->cco_dsc,
            'op' => '',
            'lote' => $registro->options->lote,
            'isc' => '',
            'icbper' => '',
            'peso' => $registro->options->peso,
            'consumo' => $getctaventapdto['consumo'],
            'tipoproducto' => 0,
            'utilidad' => 0,
            'pidelote' => 0,
            'um2' => '',
            'conversion' => 0,
            'cuenta_id' => $getctaventapdto['cuenta_id'],
            'cuenta_codigo' => $getctaventapdto['cuenta'],
            'pide_ccosto' => $getctaventapdto['pide_ccosto'],
            'esnuevo' => 0,
            'referencia_id' => 0, // no tiene que ver con el cart referencia(clasic, crsreferencia)
            'tipoafectaigv_id' => $request->tipo_afectacion_igv, // frm: mov_facturaventa, método: nuevalinea 
            'pdescuento' => $pdescuento,
            'actividad_id' => 0,
            'actividad_codigo' => '',
            'actividad_descripcion' => '',
            'proyecto_id'=> 0,
            'proyecto_codigo' => '',
            'proyecto_descripcion' => '',
            'centro_costo_data' => $centro_costo,
            'tipoafectaigv_data' => $tipo_afecta
        ]]);

        
    }

    private function detalle_salidaalmacen_existente($registro, $getctaventapdto){

        // PUEDE EXISTIR VARIOS ( CONFIGURACIÓN PRODUCTOS REPETIDOS )

        $existentes = collect(Cart::instance($this->cart_docxpagar_detalle)->content())->whereIn('name', $registro->options->producto_id); 

        foreach($existentes as $existente){

            if ( trim($existente->options->lote) == $registro->options->lote &&  $existente->options->vencimiento == $registro->options->vencelote && $existente->options->tipoprecio == $registro->options->tipoprecio && $existente->options->precio == $getctaventapdto['precio']){

                return true;

            }

        }

        return false;

    }

    public function agregar_documentos_referencia($detalle, $getctaventapdto, $request){

        $pdescuento = ($this->pdescuento > 0)  ? 1 : 0; // ver si el usuario puede aplicar descuento

        $centro_costo = Costs::select('*')->where("estado", 1)->get();
        $tipo_afecta = AffectIgv::select('*')->where("estado", 1)->get();

        
        $item = $this->obtener_item();     

        Cart::instance($this->cart_docxpagar_detalle)->add(['id' => $item , 'name' => $detalle->options->producto_id, 'qty' => 1, 'price' => 0.00,
        'options' => [
            'id' => $detalle->options->producto_id,
            'codigo' => $detalle->options->prd_cod,
            'cumseps' => '',
            'descripcion' => $detalle->options->prd_dsc,
            'caracteristicas' => '',
            'umedida_codigo' => $detalle->options->ume_cod,
            'ubicacion' => '',
            'stock' => 0,
            'esafecto' => $detalle->options->esafecto,
            'flag' => 0,
            'estado' => 1,
            'umedida_id' => $detalle->options->umedida_id,
            'marca' => 0,
            'editar_precio' => 0,
            'pideserie' => 0,
            'serie' => '',
            'tipoprecio' => 1,
            'vencimiento' => '',
            'importe' => $detalle->options->aplicar * $detalle->options->precio,
            'preciolista' => $detalle->options->preciolista,
            'cantidad' => $detalle->options->aplicar,
            'precio' =>  $detalle->options->precio, 
            'descuento' => $detalle->options->pdescuento,
            'isc_icbper' => 0,
            'referencia_codigo' => '',
            'centro_costo_id' => null,
            'centro_costo_codigo' => '',
            'centro_costo_descripcion' => '',
            'op' => '',
            'lote' => '',
            'isc' => '',
            'icbper' => '',
            'peso' => 0,
            'consumo' => null,
            'tipoproducto' => $detalle->prd_tipo,
            'utilidad' => 0,
            'pidelote' => 0,
            'um2' => '',
            'conversion' => 0,
            'cuenta_id' => $getctaventapdto['cuenta_id'],
            'cuenta_codigo' => $getctaventapdto['cuenta'],
            'pide_ccosto' => $getctaventapdto['pide_ccosto'],
            'esnuevo' => 0,
            'referencia_id' => 0, // no tiene que ver con el cart referencia(clasic, crsreferencia)
            'tipoafectaigv_id' => $request->tipo_afectacion_igv, // frm: mov_facturaventa, método: nuevalinea 
            'pdescuento' => $pdescuento,
            'actividad_id' => 0,
            'actividad_codigo' => '',
            'actividad_descripcion' => '',
            'proyecto_id'=> 0,
            'proyecto_codigo' => '',
            'proyecto_descripcion' => '',
            'centro_costo_data' => $centro_costo,
            'tipoafectaigv_data' => $tipo_afecta
        ]]);
        

    }

}
