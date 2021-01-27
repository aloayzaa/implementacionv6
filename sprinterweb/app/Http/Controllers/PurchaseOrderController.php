<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\PurchaseOrder\Entities\PurchaseOrder;
use App\PurchaseOrder\Entities\ATPurchaseWarehouseOrder;
use App\ServiceOrders\Entities\ServiceOrdersDetail;
use App\BusinessUnit\Entities\BusinessUnit;
use App\PointSale\Entities\PointSale;
use App\Customers\Entities\Customer;
use App\Customers\Entities\CustomerAccount;
use App\Customers\Entities\CustomerContact;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Products\Entities\Productos;
use App\Currency\Entities\Currency;
use App\Taxes\Entities\Taxes;
use App\Deductions\Entities\Deduction;
use App\Warehouses\Entities\WarehouseIncome;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Warehouses\Entities\WarehouseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseOrderStoreRequest;
use App\Http\Requests\PurchaseOrderDetailRequest;
use App\Period\Entities\Period;
use Illuminate\Queue\RedisQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Traits\CartTrait;
use App\Procedures\PurchaseOrderProcedure;

class PurchaseOrderController extends Controller
{
    use CartTrait, Movement, UserPrivileges;
    private $var = 'purchaseorder';
    private $instancia = 'detalle_documento';
    private $purchaseOrderProcedure;
    private $instancia_pd = 'pedido_detalle';
    private $instancia_pdm = 'pedido_detalle_modal';
    private $instancia_atcompra = 'pedidoalmacen_at_compra';
    private $tabla_cabecera = 'ordencompra';
    protected $user;
    protected $privilegios = '04.02.02';

    public function __construct(PurchaseOrderProcedure $purchaseOrderProcedure)
    {
        $this->obtener_cliente();
        $this->purchaseOrderProcedure = $purchaseOrderProcedure;
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $periodo = Session::get('period_id');
        $purchaseorder = PurchaseOrder::listado_orden_compra($periodo);
        return DataTables::of($purchaseorder)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Compras', 'Transacciones', 'Ordenes de Compra', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('purchaseorder.list', $data);
    }

    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['instancia'] = $this->instancia;
        $data['view'] = link_view('Compras', 'Transacciones', 'Ordenes de Compra', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['unegocio'] = BusinessUnit::all()->where('estado',1);
        $data['puntoventa'] = PointSale::all();
        $data['condicionpago'] = PaymentCondition::all();
        $data['moneda'] = Currency::all();
        $data['impuesto_one'] = Taxes::crsimpuesto();
        $data['impuesto_two'] = Taxes::crsimpuesto2();

        $data['tipos_detraccion'] = Deduction::all();
        $data['today'] = Carbon::today()->format('Y-m-d');
        //$data['productos'] = Productos::productos_modal_ordencompra();

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_pd)->destroy();
        Cart::instance($this->instancia_pdm)->destroy();
        Cart::instance($this->instancia_atcompra)->destroy();
        Cart::instance($this->instancia);

        return view('purchaseorder.create', $data);
    }

    public function store(PurchaseOrderStoreRequest $request)
    {
        $validar_costo_detalle = $this->validar_costo_detalle();
        if(!is_null($validar_costo_detalle)){return $validar_costo_detalle;}

        try {
            $ordencompra = new PurchaseOrder();
            $ordencompra->periodo_id = Session::get('period_id');
            $ordencompra->serie = ltrim($request->txt_serie, 0);
            //$ordencompra->numero = $request->;
            $ordencompra->tipo = $request->cbo_tipo_producto;
            $ordencompra->fechaproceso = $request->txt_fecha;
            $ordencompra->unegocio_id = $request->cbo_unidad_negocio;
            $ordencompra->tercero_id = $request->cbo_razon_social;
            //obtener almacen y sucursal
            $data_alm_suc = PointSale::obtener_almacen_sucursal($request->cbo_punto_emision);
            $ordencompra->sucursal_id = $data_alm_suc->idsucursal;
            $ordencompra->almacen_id = $data_alm_suc->idalmacen;
            $ordencompra->moneda_id = $request->cbo_moneda_orden;
            $ordencompra->tcambio = $request->changerate;
            $ordencompra->tcmoneda = $request->txt_tc_orden;
            $ordencompra->condicionpago_id = $request->cbo_condicion_pago;
            $ordencompra->fechaentrega = $request->txt_fecha_entrega;
            $ordencompra->lugarentrega = $request->txt_lugar_entrega;
            $ordencompra->solicitadopor = ($request->cbo_contacto_otros_datos != '') ? $request->cbo_contacto_otros_datos : '';
            $ordencompra->ctacte = ($request->cbo_depositar_otros_datos != '') ? $request->cbo_depositar_otros_datos : '';
            $ordencompra->glosa = ($request->txt_observaciones != '') ? $request->txt_observaciones : 'PEDIDO ORDEN DE COMPRA';
            $ordencompra->impuesto_id = ($request->cbo_igv_tributos != 0) ? $request->cbo_igv_tributos : null;
            if($request->cbo_igv_tributos != 0){
                 $codigo_impuesto1 = Taxes::select('codigo')->where('id',$request->cbo_igv_tributos)->first();
                 if($codigo_impuesto1->codigo != 99){
                     $ordencompra->impuesto2_id = ($request->cbo_percepcion_tributos != 0) ? $request->cbo_percepcion_tributos : null;
                 }else{
                     $ordencompra->impuesto2_id = null;
                 }
            }
            $ordencompra->base = $request->txt_base;
            $ordencompra->inafecto = $request->txt_inafecto;
            $ordencompra->impuesto = $request->txt_impuesto;
            $ordencompra->impuesto2 = $request->txt_impuesto2;
            //$ordencompra->impuesto3 =
            $ordencompra->total = $request->txt_total;
            $ordencompra->ventana = 'MOV_ORDENCOMPRA';
            $ordencompra->estado = 'ACTIVO';
            $ordencompra->incluyeimpto = (isset($request->chk_incluye_impuestos) && $request->chk_incluye_impuestos == "on") ? 1 : 0;
            //$ordencompra->esservicio =
            $ordencompra->tipopeso = $request->opt_tipopeso;
            $ordencompra->usuario = Session::get('usuario');
            //$ordencompra->undtransporte_id =
            $ordencompra->puntoventa_id = $request->cbo_punto_emision;
            $ordencompra->tipodetraccion_id = ($request->cbo_tipo_detraccion != 0) ? $request->cbo_tipo_detraccion : null;
            $ordencompra->referencial = ($request->txt_valor_referencial != 0 && is_numeric($request->txt_valor_referencial)) ? $request->txt_valor_referencial : 0.00;
            $ordencompra->totaldetraccion = ($request->txt_importe != 0 && is_numeric($request->txt_importe)) ? $request->txt_importe : 0.00;
            $ordencompra->notas = ($request->txt_notas_adicionales != '') ? $request->txt_notas_adicionales : '';
            //$ordencompra->save();
            //$this->save_details($id);
            //$ruta = route('edit.purchaseorder', $id);
            //return $ruta;
            $id = null;
            $id = DB::transaction(function () use ($ordencompra) {
                $ordencompra->save();
                $id = $ordencompra->id;
                $this->save_details($id);
                return $id;
            });
            $r["ruta"] = route('edit.purchaseorder', $id);
            $r["estado"] = "ok";

        }catch(PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function edit($id)
    {
        session()->put('item_to_delete', []);

        $ordencompra = PurchaseOrder::findOrFail($id);
        //dd($ordencompra);
        $ordencompra_detalle = ServiceOrdersDetail::dataCarritoPurcharseOrder($id);

        $data['ordencompra'] = $ordencompra;
        $data['var'] = $this->var;
        $data['view'] = link_view('Compras', 'Transacciones', 'Ordenes de Compra', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $ordencompra->estado, $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['periodo'] = Period::find($ordencompra->periodo_id);
        $data['unegocio'] = BusinessUnit::all();
        $data['puntoventa'] = PointSale::all();
        $data['condicionpago'] = PaymentCondition::all();
        $data['moneda'] = Currency::all();
        $data['impuesto_one'] = Taxes::crsimpuesto();
        $data['impuesto_two'] = Taxes::crsimpuesto2();
        $data['proceso'] = 'edit';

        $data['lugar_entrega'] = $ordencompra->lugarentrega;
        $data['tipos_detraccion'] = Deduction::all();


        $data['tercero_cuenta'] = CustomerAccount::data_depositar_en($ordencompra->tercero_id);
        $data['tercero_contacto'] = CustomerContact::select('*')->where('tercero_id', $ordencompra->tercero_id)->get();

        $data['detraccion'] = Deduction::select('valor')->where('id', $ordencompra->tipodetraccion_id)->first();

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_pd)->destroy();
        Cart::instance($this->instancia_pdm)->destroy();
        Cart::instance($this->instancia_atcompra)->destroy();

        Cart::instance($this->instancia);  //evito poner la instancia en el trait

        $this->llenarCarritoOrdenCompraDetalle($ordencompra_detalle);
        return view('purchaseorder.edit', $data);

    }

    public function update(PurchaseOrderStoreRequest $request)
    {
        $validar_costo_detalle = $this->validar_costo_detalle();
        if(!is_null($validar_costo_detalle)){return $validar_costo_detalle;}

        try {
            $ordencompra = PurchaseOrder::find($request->id);
            //$ordencompra->periodo_id = Session::get('period_id');
            $ordencompra->serie = ltrim($request->txt_serie, 0);
            //$ordencompra->numero = $request->;
            $ordencompra->tipo = $request->cbo_tipo_producto;
            $ordencompra->fechaproceso = $request->txt_fecha;
            $ordencompra->unegocio_id = $request->cbo_unidad_negocio;
            $ordencompra->tercero_id = $request->cbo_razon_social;
            //obtener almacen y sucursal
            $data_alm_suc = PointSale::obtener_almacen_sucursal($request->cbo_punto_emision);
            $ordencompra->sucursal_id = $data_alm_suc->idsucursal;
            $ordencompra->almacen_id = $data_alm_suc->idalmacen;
            $ordencompra->moneda_id = $request->cbo_moneda_orden;
            $ordencompra->tcambio = $request->changerate;
            $ordencompra->tcmoneda = $request->txt_tc_orden;
            $ordencompra->condicionpago_id = $request->cbo_condicion_pago;
            $ordencompra->fechaentrega = $request->txt_fecha_entrega;
            $ordencompra->lugarentrega = $request->txt_lugar_entrega;
            $ordencompra->solicitadopor = ($request->cbo_contacto_otros_datos != '') ? $request->cbo_contacto_otros_datos : '';
            $ordencompra->ctacte = ($request->cbo_depositar_otros_datos != '') ? $request->cbo_depositar_otros_datos : '';
            $ordencompra->glosa = ($request->txt_observaciones != '') ? $request->txt_observaciones : 'PEDIDO ORDEN DE COMPRA';
            $ordencompra->impuesto_id = ($request->cbo_igv_tributos != 0) ? $request->cbo_igv_tributos : null;
            if($request->cbo_igv_tributos != 0){
                $codigo_impuesto1 = Taxes::select('codigo')->where('id',$request->cbo_igv_tributos)->first();
                if($codigo_impuesto1->codigo != 99){
                    $ordencompra->impuesto2_id = ($request->cbo_percepcion_tributos != 0) ? $request->cbo_percepcion_tributos : null;
                }else{
                    $ordencompra->impuesto2_id = null;
                }
            }
            $ordencompra->base = $request->txt_base;
            $ordencompra->inafecto = $request->txt_inafecto;
            $ordencompra->impuesto = $request->txt_impuesto;
            $ordencompra->impuesto2 = $request->txt_impuesto2;
            //$ordencompra->impuesto3 =
            $ordencompra->total = $request->txt_total;
            $ordencompra->ventana = 'MOV_ORDENCOMPRA';
            //$ordencompra->estado = 'ACTIVO';
            $ordencompra->incluyeimpto = (isset($request->chk_incluye_impuestos) && $request->chk_incluye_impuestos == "on") ? 1 : 0;
            //$ordencompra->esservicio =
            $ordencompra->tipopeso = $request->opt_tipopeso;
            $ordencompra->usuario = Session::get('usuario');
            //$ordencompra->undtransporte_id =
            $ordencompra->puntoventa_id = $request->cbo_punto_emision;
            $ordencompra->tipodetraccion_id = ($request->cbo_tipo_detraccion != 0) ? $request->cbo_tipo_detraccion : null;
            $ordencompra->referencial = ($request->txt_valor_referencial != 0 && is_numeric($request->txt_valor_referencial)) ? $request->txt_valor_referencial : 0.00;
            $ordencompra->totaldetraccion = ($request->txt_importe != 0 && is_numeric($request->txt_importe)) ? $request->txt_importe : 0.00;
            $ordencompra->notas = ($request->txt_notas_adicionales != '') ? $request->txt_notas_adicionales : '';
            //$ordencompra->save();
            //$id = $request->id;
            //$this->save_details($id);

            $id = null;
            $id = DB::transaction(function () use ($ordencompra) {
                $ordencompra->save();
                $id = $ordencompra->id;
                $this->save_details($id);
                return $id;
            });

            $data["estado"] = "ok";
            $data["success"] = "Grabado Correctamente";

        }catch(PDOException $e) {
            $data["estado"] = "error";
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;

    }

    public function save_details($id)
    {

        if (Cart::instance($this->instancia)->count() == 0) {
            session()->put('item_to_delete', []);
            return response()->json(['errors' => 'Ingrese un producto']);
        } else {
            $items = Session::get('item_to_delete');

            if ($items) {
                foreach ($items as $item) {
                    ServiceOrdersDetail::where(['parent_id' => $id, 'item' => $item])->delete();
                    session()->put('item_to_delete', []);
                }
            }
            $pedido_almacen_id = 0; //comprobar un unico pedido almacen

           // dd(Cart::instance($this->instancia)->content());

            foreach (Cart::instance($this->instancia)->content() as $cart) {

                $existe = ServiceOrdersDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();

                if ($existe) {
                    //Editando
                    $item = $cart->options->item;
                    $data['producto_id'] = $cart->options->producto_id;
                    $data['umedida_id'] = $cart->options->umedida_id;
                    $data['cantidad'] = $cart->options->cantidad;
                    $data['precio'] = $cart->options->precio;
                    $data['descuento'] = ($cart->options->descuento > 0) ? $cart->options->descuento : '';;
                    $data['valor'] = $cart->options->valor;
                    $data['subtotal'] = $cart->options->subtotal;
                    $data['importe'] = $cart->options->importe;
                    $data['glosa'] = $cart->options->glosa;
                    $data['esafecto'] = $cart->options->esafecto;
                    $data['usuario'] = $cart->options->usuario;

                    ServiceOrdersDetail::saveDetails($id, $item, $data);
                    $this->store_pedidoalmacen_atcompra($id);
                    if(isset($cart->options->pedido_almacen_id) && $pedido_almacen_id != $cart->options->pedido_almacen_id){
                        $pedido_almacen_id = $cart->options->pedido_almacen_id;
                        $this->update_pedidoalmacen($id,$cart->options->pedido_almacen_id);
                    }
                    if(isset($cart->options->precio) && $cart->options->precio > 0){
                        $this->update_producto($id,$cart->options->producto_id,$cart->options->precio);
                    }
                }else{
                    $ordencompra_detalle = new ServiceOrdersDetail();
                    $ordencompra_detalle->parent_id = $id;
                    $ordencompra_detalle->item = $cart->options->item;
                    $ordencompra_detalle->producto_id = $cart->options->producto_id;
                    $ordencompra_detalle->umedida_id = $cart->options->umedida_id;
                    $ordencompra_detalle->cantidad = $cart->options->cantidad;
                    $ordencompra_detalle->precio = $cart->options->precio;
                    $ordencompra_detalle->descuento = ($cart->options->descuento > 0) ? $cart->options->descuento : '';
                    $ordencompra_detalle->valor = $cart->options->valor;
                    $ordencompra_detalle->subtotal = $cart->options->subtotal;
                    $ordencompra_detalle->importe = $cart->options->importe;
                    $ordencompra_detalle->glosa = $cart->options->glosa;
                    $ordencompra_detalle->esafecto = $cart->options->esafecto;
                    $ordencompra_detalle->usuario = $cart->options->usuario;

                    if ($ordencompra_detalle->save()) {
                        $this->store_pedidoalmacen_atcompra($id);
                        if(isset($cart->options->pedido_almacen_id) && $pedido_almacen_id != $cart->options->pedido_almacen_id){
                            $pedido_almacen_id = $cart->options->pedido_almacen_id;
                            $this->update_pedidoalmacen($id,$cart->options->pedido_almacen_id);
                        }
                        if(isset($cart->options->precio) && $cart->options->precio > 0){
                            $this->update_producto($id,$cart->options->producto_id,$cart->options->precio);
                        }
                    }
                }

            }

        }
        /*
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_pd)->destroy();
        Cart::instance($this->instancia_pdm)->destroy();*/

    }

    private function store_pedidoalmacen_atcompra($id)
    {

        $existe_data = count(Cart::instance($this->instancia_atcompra)->content());
        //dd($existe_data);
        if ($existe_data > 0) {
            foreach (Cart::instance($this->instancia_atcompra)->content() as $cart) {
                //dd($cart->options->atendido);
                $pedidoalmacen_atcompra = new ATPurchaseWarehouseOrder;
                $pedidoalmacen_atcompra->parent_id = $cart->options->parent_id;
                $pedidoalmacen_atcompra->item = $cart->options->item;
                //$pedidoalmacen_atcompra->subitem = ;
                $pedidoalmacen_atcompra->atendido = $cart->options->atendido;
                $pedidoalmacen_atcompra->ordencompra_id = $id;
                $pedidoalmacen_atcompra->save();
            }
        }
        Cart::instance($this->instancia_atcompra)->destroy();
    }

    private function update_pedidoalmacen($ordencompra_id, $pedido_almacen_id){
        $pedidoalmacen = WarehouseOrder::findOrFail($pedido_almacen_id);
        $pedidoalmacen->ordencompra_id = $ordencompra_id;
        $pedidoalmacen->save();
    }

    private function update_producto($ordencompra_id, $producto_id, $precio){
        $ordencompra = PurchaseOrder::find($ordencompra_id);
        $impuesto_valor = $ordencompra->impuesto['valor'];
        $impuesto = 1 + ($impuesto_valor / 100);
        if($ordencompra->moneda['tipo'] == 'N'){
            $lnPrecio1 = $precio;
            $lnPrecio2 = $precio / $ordencompra->tcambio;
        }else{
            $lnPrecio1 = $precio * $ordencompra->tcambio;
            $lnPrecio2 = $precio;
        }
        if($ordencompra->incluyeimpto = 0){
            $lnPrecio1 = $lnPrecio1 * $impuesto;
            $lnPrecio2 = $lnPrecio2 * $impuesto;
        }
        $producto = Productos::find($producto_id);
        $producto->precio1 = $lnPrecio1;
        $producto->precio2 = $lnPrecio2;
        $producto->save();
    }

    public function agregar_detalle_documento(PurchaseOrderDetailRequest $request)
    {
        //dd($request->all());
        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first();

        if ($ultimoCart == null) {
            $ultimoBd = ServiceOrdersDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;
        }

        $dbproducto = Productos::find($request->cbo_producto);
        $dbumedida = UnidadesMedida::select('codigo')->where('id',$request->txt_umedida)->first();
        //$volumen = $request->txt_cantidad * $dbproducto->volumen;
        Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $request->parent_id,
                'item' => $item + 1,
                'producto_id' => $request->cbo_producto,
                'producto_codigo' => $dbproducto->codigo,
                'producto_descripcion' => $dbproducto->descripcion,
                'umedida_id' => $request->txt_umedida,
                'umedida_codigo' => $dbumedida->codigo,
                'cantidad' => $request->txt_cantidad,
                'precio' => $request->txt_costo_unitario,
                'descuento' => $request->txt_descuento,
                'valor' => $request->txt_valor,
                'subtotal' => $request->txt_subtotal,
                'importe' => $request->txt_importe_producto,
                'preciovta' => 0.00,
                //'atendido' =>
                'esafecto' => $dbproducto->esafecto,
                'usuario' => Session::get('usuario'),
                //'volumen' => $volumen,
                'volumen' => $dbproducto->volumen,
                'glosa' => $request->txt_glosa,
                'tipoproducto' => $dbproducto->tipoproducto,
                'percepcion' => $dbproducto->percepcion,
            ]
        ]);

        //dd(Cart::instance($this->instancia)->content());

        //fin
        $r["estado"] = "ok";
        $r["instancia"] = $this->instancia;

        $r = $this->volumen_cantidad();

        return $r;
    }

    public function listar_detalle(Request $request)
    {
        return $this->lista($this->instancia);
    }

    public function editar_detalle_documento(PurchaseOrderDetailRequest $request)
    {
        //dd($request->all());
        $instancia = $this->instancia;

        $id = $request->modal_edit_row_id;
        $item = Cart::instance($instancia)->get($id);
        //dd($item);
        $dbproducto = Productos::find($request->cbo_producto_editar);
        $dbumedida = UnidadesMedida::select('codigo')->where('id',$request->txt_umedida_editar)->first();
        //$volumen = $request->txt_cantidad_editar * $dbproducto->volumen;

        $options = $item->options->merge([
            'producto_id' => $request->cbo_producto_editar,
            'producto_codigo' => $dbproducto->codigo,
            'producto_descripcion' => $dbproducto->descripcion,
            'umedida_id' => $request->txt_umedida_editar,
            'umedida_codigo' => $dbumedida->codigo,
            'cantidad' => $request->txt_cantidad_editar,
            'precio' => $request->txt_costo_unitario_editar,
            'descuento' => $request->txt_descuento_editar,
            'valor' => $request->txt_valor_editar,
            'subtotal' => $request->txt_subtotal_editar,
            'importe' => $request->txt_importe_producto_editar,
            'preciovta' => 0.00,
            //'atendido' =>
            'esafecto' => $dbproducto->esafecto,
            'usuario' => Session::get('usuario'),
            //'volumen' => $volumen,
            'volumen' => $dbproducto->volumen,
            'glosa' => $request->txt_glosa_editar,
            'tipoproducto' => $dbproducto->tipoproducto,
            'percepcion' => $dbproducto->percepcion,
        ]);

        $r["estado"] = "ok";
        $r["instancia"] = $this->instancia;

        Cart::instance($instancia)->update($id, [
            'options' => $options]);

        $r = $this->volumen_cantidad();

        return $r;

    }

    public function eliminar_detalle_documento(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);
        $item = $request->item;
        session()->push('item_to_delete', $item);

        $r = $this->volumen_cantidad();

        return $r;

    }

    public function tipo_producto(Request $request)
    {

        $tipo = "";
        if (strcmp($request->tipoproducto, "P") == 0) {
            $tipo = "M";
        } else {
            $tipo = $request->tipoproducto;
        }

        $productos = Productos::productos_modal_ordencompra($tipo, $request->term);

        return $productos;

    }

    public function totalizar(Request $request)
    {
        //dd($request->all());
        $lnitem = $lnafecto = $lninafecto = $lnvalor = 0;

        if ($request->cbo_igv_tributos > 0) {
            $id = $request->cbo_igv_tributos;
            $valor = Taxes::select('valor')->where('id', $id)->first();
            $lnvalor = $valor->valor / 100;
        }
        $lnAfePer = 0;
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            //dd($cart->rowId);
            $importe_a = $cart->options->importe;
            $precio = $cart->options->precio;

            if (isset($request->chk_incluye_impuestos) && $request->chk_incluye_impuestos == "on") {
                if ($lnvalor > 0) {
                    $importe_a = $cart->options->subtotal / (1 + $lnvalor);
                } else {
                    $importe_a = $cart->options->subtotal;
                }
                $importe_b = $cart->options->subtotal;

            } else {

                $importe_a = $cart->options->subtotal;
                $importe_b = $cart->options->subtotal * (1 + $lnvalor);

            }
            if ($cart->options->cantidad > 0) {
                $precio = $importe_a / $cart->options->cantidad;
            }
            if ($cart->options->esafecto == 1) {
                $lnafecto = $lnafecto + $importe_a;
            } else {
                $lninafecto = $lninafecto + $importe_a;
            }
            if ($cart->options->percepcion == 1) {
                $lnAfePer = $lnAfePer + $importe_b;
            }

            //actualizar el carrito

            $item = Cart::instance($this->instancia)->get($cart->rowId);
            $options = $item->options->merge([
                'precio' => $precio,
                'importe' => $importe_a,
            ]);
            Cart::instance($this->instancia)->update($cart->rowId, [
                'options' => $options]);
            //---------------------
        }
        if ($lnvalor == 0) {
            $txt_base = 0;
            $txt_inafecto = $lnafecto + $lninafecto;
        } else {
            $txt_base = $lnafecto;
            $txt_inafecto = $lninafecto;
        }

        $txt_num1 = $lnAfePer;

        $r["txt_base"] = formatear_numero($txt_base,2);
        $r["txt_inafecto"] = formatear_numero($txt_inafecto,2);
        $r["txt_num1"] = $txt_num1;

        return $r;
    }

    public function sumar(Request $request)
    {
        //dd($request->all());
        $txt_total = $lntotal = 0;
        $txt_impuesto = $request->txt_impuesto;
        if (isset($request->chk_incluye_impuestos) && $request->chk_incluye_impuestos == "on") {
            foreach (Cart::instance($this->instancia)->content() as $cart) {
                $lntotal += $cart->options->subtotal;
            }
            //$txt_total = $lntotal + $request->txt_impuesto2;
            $txt_impuesto = $lntotal - $request->txt_base - $request->txt_inafecto;


            $txt_total = $request->txt_base + $request->txt_inafecto + $txt_impuesto + $request->txt_impuesto2;


        } else {
            //dd($txt_impuesto);
            $txt_total = $request->txt_base + $request->txt_inafecto + $request->txt_impuesto + $request->txt_impuesto2;
        }

        $r["txt_total"] = formatear_numero($txt_total,2);
        $r["txt_impuesto"] = formatear_numero($txt_impuesto,2);

        return $r;
    }

    public function cambiar_impuesto(Request $request)
    {

        $txt_impuesto = $lnvalor = 0;
        if ($request->cbo_igv_tributos) {
            $impuesto = Taxes::find($request->cbo_igv_tributos);
            if ($impuesto->tipocalculo == 'P') {
                $lnvalor = $request->txt_base * ($impuesto->valor / 100);
            } else {
                $lnvalor = $impuesto->valor;
            }
        }
        if ($lnvalor > 0) {
            $txt_impuesto = $lnvalor;
        } else {
            $txt_impuesto = 0;
        }
        //dd($txt_impuesto);
        $r["txt_impuesto"] = formatear_numero($txt_impuesto,2);
        return $r;
    }

    public function cambiar_impuesto2(Request $request)
    {
        $txt_impuesto2 = $lnvalor = 0;
        if ($request->cbo_percepcion_tributos > 0) {
            $impuesto2 = Taxes::find($request->cbo_percepcion_tributos);
            if ($impuesto2->tipocalculo == 'P') {
                $lnvalor = $request->txt_num1 * ($impuesto2->valor / 100);
            } else {
                $lnvalor = $impuesto2->valor;
            }
        }
        if ($lnvalor > 0) {
            $txt_impuesto2 = $lnvalor;
        } else {
            $txt_impuesto2 = 0;
        }
        $r["txt_impuesto2"] = formatear_numero($txt_impuesto2,2);

        return $r;
    }

    private function volumen_cantidad()
    {
        $total_carrito = 0;
        $total_volumen = 0;
        $total_cantidad = 0;
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            $total_carrito++;
            //$total_volumen += $cart->options->volumen;
            $total_volumen += $cart->options->cantidad * $cart->options->volumen;
            $total_cantidad += $cart->options->cantidad;
        }
        $r["total_carrito"] = formatear_numero($total_carrito,2);
        $r["total_volumen"] = formatear_numero($total_volumen,2);
        $r["total_cantidad"] = formatear_numero($total_cantidad,2);

        return $r;
    }

    public function volumen_cantidad_front()
    {
        $total_carrito = 0;
        $total_volumen = 0;
        $total_cantidad = 0;
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            $total_carrito++;
            //$total_volumen += $cart->options->volumen;
            $total_volumen += $cart->options->cantidad * $cart->options->volumen;
            $total_cantidad += $cart->options->cantidad;
        }
        $r["total_carrito"] = formatear_numero($total_carrito,2);
        $r["total_volumen"] = formatear_numero($total_volumen,2);
        $r["total_cantidad"] = formatear_numero($total_cantidad,2);

        return $r;
    }

    public function datos_adicionales(Request $request)
    {
        $direccion_almacen = PointSale::obtener_almacen_sucursal($request->punto_emision);
        $r["direccionalmacen"] = $direccion_almacen->direccionalmacen;
        return $r;
    }

    public function pedido_cabecera(Request $request)
    {
        $fecha_hasta = ($request->fecha_hasta) ? $request->fecha_hasta : '';
        $id = ($request->id) ? $request->id : 0;
        $array_enviar = array();
        Cart::instance($this->instancia_pd)->destroy();
        Cart::instance($this->instancia_pdm)->destroy();
        $data = $this->purchaseOrderProcedure->getPedidoCompra($fecha_hasta, $id);
        $resultado = collect($data);
        $this->llenar_detalle_pedido($resultado);
        $comprobar = 0;
        foreach (Cart::instance($this->instancia_pd)->content() as $cart) {
            if ($comprobar != $cart->options->id) {
                $pila = array(
                    "id" => $cart->options->id,
                    'item' => $cart->options->item,
                    'documento' => $cart->options->documento,
                    'fecha' => $cart->options->fecha,
                    'codigo' => $cart->options->codigo,
                    'nombre' => $cart->options->nombre,
                    'producto_id' => $cart->options->producto_id,
                    'umedida_id' => $cart->options->umedida_id,
                    'ucompra_id' => $cart->options->ucompra_id,
                    'conversion' => $cart->options->conversion,
                    'esafecto' => $cart->options->esafecto,
                    'cantidad' => $cart->options->cantidad,
                    'pedido' => $cart->options->pedido,
                    'prd_cod' => $cart->options->prd_cod,
                    'prd_dsc' => $cart->options->prd_dsc,
                    'ume_cod' => $cart->options->ume_cod,
                    'ucompra_cod' => $cart->options->ucompra_cod,
                    'ventana' => $cart->options->ventana,
                    'glosa' => $cart->options->glosa,
                    'ordencompra_id' => $cart->options->ordencompra_id,
                    'op' => $cart->options->op,
                    'ccosto' => $cart->options->ccosto
                );
                $comprobar = $cart->options->id;
                $array_enviar[] = $pila;
            }
        }

        return DataTables::of($array_enviar)->make();
    }

    private function llenar_detalle_pedido($data)
    {
        foreach ($data as $d) {
            Cart::instance($this->instancia_pd)->add(['id' => $d->id, 'name' => $d->item, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'elegido' => $d->elegido,
                    'id' => $d->id,
                    'item' => $d->item,
                    'documento' => $d->documento,
                    'fecha' => $d->fecha,
                    'codigo' => $d->codigo,
                    'nombre' => $d->nombre,
                    'producto_id' => $d->producto_id,
                    'umedida_id' => $d->umedida_id,
                    'ucompra_id' => $d->ucompra_id,
                    'conversion' => $d->conversion,
                    'esafecto' => $d->esafecto,
                    'cantidad' => $d->cantidad,
                    'pedido' => $d->pedido,
                    'prd_cod' => $d->prd_cod,
                    'prd_dsc' => $d->prd_dsc,
                    'ume_cod' => $d->ume_cod,
                    'ucompra_cod' => $d->ucompra_cod,
                    'ventana' => $d->ventana,
                    'glosa' => $d->glosa,
                    'ordencompra_id' => $d->ordencompra_id,
                    'op' => $d->op,
                    'ccosto' => $d->ccosto,
                ]
            ]);
        }
    }

    public function pedido_detalle(Request $request)
    {

        if ($request->accion == 'agregar') {
            foreach (Cart::instance($this->instancia_pd)->content() as $cart) {
                if ($request->id == $cart->options->id) {

                    $this->llenar_detalle_pedido_modal($cart);
                }
            }
            //return DataTables::of($array_enviar)->make();
        } else {
            $ids = json_decode($request->ids_cab);
            Cart::instance($this->instancia_pdm)->destroy();
            foreach (Cart::instance($this->instancia_pd)->content() as $cart) {
                foreach ($ids as $id) {
                    if ($id->ids->value == $cart->options->id) {
                        $this->llenar_detalle_pedido_modal($cart);
                    }
                }
            }
        }

        $index_checks = json_decode($request->ids_check_det);

        if (isset($index_checks) || $index_checks !== "" || !empty($index_checks)) {
            $this->llenar_detalle_pedido_modal_elegido($index_checks);
        }

        return DataTables::of(Cart::instance($this->instancia_pdm)->content())->make();
    }

    private function llenar_detalle_pedido_modal($data)
    {
        Cart::instance($this->instancia_pdm)->add(['id' => $data->options->id, 'name' => $data->options->item, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'elegido' => $data->options->elegido,
                'id' => $data->options->id,
                'item' => $data->options->item,
                'documento' => $data->options->documento,
                'fecha' => $data->options->fecha,
                'codigo' => $data->options->codigo,
                'nombre' => $data->options->nombre,
                'producto_id' => $data->options->producto_id,
                'umedida_id' => $data->options->umedida_id,
                'ucompra_id' => $data->options->ucompra_id,
                'conversion' => $data->options->conversion,
                'esafecto' => $data->options->esafecto,
                'cantidad' => $data->options->cantidad,
                'pedido' => $data->options->pedido,
                'prd_cod' => $data->options->prd_cod,
                'prd_dsc' => $data->options->prd_dsc,
                'ume_cod' => $data->options->ume_cod,
                'ucompra_cod' => $data->options->ucompra_cod,
                'ventana' => $data->options->ventana,
                'glosa' => $data->options->glosa,
                'ordencompra_id' => $data->options->ordencompra_id,
                'op' => $data->options->op,
                'ccosto' => $data->options->ccosto,
            ]
        ]);

    }

    public function llenar_detalle_pedido_modal_elegido($index_checks)
    {

        if (isset($index_checks) || $index_checks != 0) {
            foreach (Cart::instance($this->instancia_pdm)->content() as $cart) {
                $cont = 0;
                foreach ($index_checks as $indexs) {
                    $index = explode('|', $indexs->ids->value); // 0 : id / 1 : item
                    if ($index[0] == $cart->id && $index[1] == $cart->name) {

                        //actualizar el carrito
                        $item = Cart::instance($this->instancia_pdm)->get($cart->rowId);
                        $options = $item->options->merge([
                            'elegido' => 1,
                            'pedido' => $index_checks[$cont]->pedido->value
                        ]);

                        Cart::instance($this->instancia_pdm)->update($cart->rowId, [
                            'options' => $options]);
                        //---------------------

                    }
                    $cont++;
                }
            }
        }
    }

    public function agregar_pedidos(Request $request)
    {
        $data = json_decode($request->data);
        $n = count($data);
        //dd($data);
        if (isset($data) && $data != 0 && $n > 0) {
            //dd(json_decode($request->data));
            $this->eliminar_instancias_antesdepedidos();
            //Cart::instance($this->instancia)->destroy();
            //Cart::instance($this->instancia_atcompra)->destroy();
            //dd($data[0]->pedido->value);
            $id = $request->id;
            $cantidad = $request->txt_total_cantidad;
            $descuento = $request->txt_dscto_base;
            //dd(Cart::instance($this->instancia_pdm)->content());
            foreach (Cart::instance($this->instancia_pdm)->content() as $cart) {
                $cont = 0;
                foreach ($data as $d) {
                    $index = explode('|', $d->ids->value); // 0 : id / 1 : item
                    if ($index[0] == $cart->id && $index[1] == $cart->name) {

                        $this->agregar_detalle_documento_two($cart, $id, $cantidad, $descuento, $data[$cont]->pedido->value);
                        $this->agregar_pedidoalmacen_atcompra($cart, $data[$cont]->pedido->value);

                    }
                    $cont++;
                }
            }
        }
        $this->volumen_cantidad();
    }

    private function agregar_detalle_documento_two($data, $id, $cantidad, $descuento, $pedido)
    {
        $productos_iguales = $this->pedidos_productos_iguales($data->options->producto_id);
        if($productos_iguales != null){
            //dd($productos_iguales);
            $this->unir_pedido_producto_igual($productos_iguales, $data, $pedido);
        }else{
            //dd()
            $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first();
            if ($ultimoCart == null) {

                $ultimoBd = ServiceOrdersDetail::where('parent_id', $id)->get()->last();
                $item = !$ultimoBd ? 0 : $ultimoBd->item;

            } else {
                $item = $ultimoCart->options->item;
            }

            $dbproducto = Productos::find($data->options->producto_id);
            //$volumen = $cantidad * $dbproducto->volumen;
            Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $id,
                    'item' => $item + 1,
                    'producto_id' => $data->options->producto_id,
                    'producto_codigo' => $data->options->prd_cod,
                    'producto_descripcion' => $data->options->prd_dsc,
                    'umedida_id' => $data->options->umedida_id,
                    'umedida_codigo' => $data->options->ume_cod,
                    'cantidad' => $pedido,
                    'precio' => 0,
                    'descuento' => $descuento,
                    'valor' => 0,
                    'subtotal' => 0,
                    'importe' => 0,
                    'preciovta' => 0.00,
                    //'atendido' =>
                    'esafecto' => $data->options->esafecto,
                    'usuario' => Session::get('usuario'),
                    //'volumen' => $volumen,
                    'volumen' => $dbproducto->volumen,
                    'glosa' => $data->options->glosa,
                    'tipoproducto' => $dbproducto->tipoproducto,
                    'percepcion' => $dbproducto->percepcion,
                    'pedido_almacen_id' => $data->options->id,
                ]
            ]);
        }


    }

    private function agregar_pedidoalmacen_atcompra($data, $pedido)
    {
        //$ultimoBd = ATPurchaseWarehouseOrder::where('parent_id', $data->options->id)->get()->last();
        //$item = !$ultimoBd ? 0 : $ultimoBd->item;

        Cart::instance($this->instancia_atcompra)->add(['id' => $data->options->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $data->options->id,
                'item' => $data->options->item,
                'atendido' => $pedido,
                'ordencompra_id' => 0,
            ]
        ]);
    }

    public function referencia_pedido_almacen(Request $request)
    {
        $data = ATPurchaseWarehouseOrder::referencia_orden_compra($request->ordencompra_id);
        return DataTables::of($data)->make();
    }

    public function contacto_depositar(Request $request)
    {
        $tercero_cuenta = CustomerAccount::data_depositar_en($request->tercero_id);
        $tercero_contacto = CustomerContact::select('*')->where('tercero_id', $request->tercero_id)->get();
        $data['tercero_cuenta'] = $tercero_cuenta;
        $data['tercero_contacto'] = $tercero_contacto;

        return $data;
    }

    public function cambiar_estado(Request $request, $id){
        $ordencompra = PurchaseOrder::findOrFail($id);
        $this->validar_estado($request, $ordencompra, "La orden de compra ya está siendo procesada");

        $ordencompra->estado = $request->newstate;
        $ordencompra->save();
        return $ordencompra->estado;
    }

    public function ingreso_almacen(Request $request){
        //$request->ordencompra_id
        $ingresoalmacen = WarehouseIncome::data_ordencompra($request->ordencompra_id);
        return DataTables::of($ingresoalmacen)->make();
    }

    public function provisiones(Request $request){
        //$request->ordencompra_id
        $docxpagar = DocumentToPay::data_ordencompra($request->ordencompra_id);
        $data_env = array();
        foreach($docxpagar as $d){
            if($d->moneda['tipo'] != 'N'){
                $moneda_extranjera = $d->total;
                $moneda_nacional = $d->total * $d->tcambio;
            }else{
                $moneda_extranjera = $d->total / $d->tcambio;
                $moneda_nacional = $d->total;
            }
            $data = array(
                'fecha' => $d->fechaproceso,
                'referencia' => $d->referencia,
                'moneda_nacional' => $moneda_nacional,
                'moneda_extranjera' => $moneda_extranjera,
                'glosa' => $d->glosa
            );
            $data_env[] = $data;
        }

        return DataTables::of($data_env)->make();

    }
    public static function sumar_footer_referencias(Request $request){
        //$request->ordencompra_id
        $ingresoalmacen = WarehouseIncome::data_ordencompra($request->ordencompra_id);
        $docxpagar = DocumentToPay::data_ordencompra($request->ordencompra_id);

        $sum_ingresoalmacen_n = 0;
        $sum_ingresoalmacen_e = 0;
        $sum_provision_n = 0;
        $sum_provision_e = 0;
        foreach ($ingresoalmacen as $i) {
            $sum_ingresoalmacen_n += $i->totalmn;
            $sum_ingresoalmacen_e += $i->totalme;
        }
        foreach($docxpagar as $d){
            if($d->moneda['tipo'] != 'N'){
                $moneda_extranjera = $d->total;
                $moneda_nacional = $d->total * $d->tcambio;
            }else{
                $moneda_extranjera = $d->total / $d->tcambio;
                $moneda_nacional = $d->total;
            }
            $sum_provision_n += $moneda_nacional;
            $sum_provision_e += $moneda_extranjera;
        }
        $data['sum_ingresoalmacen_n'] = formatear_numero($sum_ingresoalmacen_n,2);
        $data['sum_ingresoalmacen_e'] = formatear_numero($sum_ingresoalmacen_e,2);
        $data['sum_provision_n'] = formatear_numero($sum_provision_n,2);
        $data['sum_provision_e'] = formatear_numero($sum_provision_e,2);
        return $data;
    }
    public function setestado($estado, $id){
        if($id != 0){
            if(empty($estado)){
                $this->purchaseOrderProcedure->setordencompraestado($id, 'ingresoalmacen');
            }else{
                PurchaseOrder::where('id', $id)->update(['estado' => $estado]);
            }
        }

        $r = array();
        $estado = PurchaseOrder::select('estado')->where('id',$id)->first();
        if($estado){
            $r['estado'] = 'ok';
            $r['mensaje'] = 'se ha cambiado el estado';
            $r['estado_tabla'] = $estado->estado;
        }
        return $r;
    }
    public function archivar(Request $request){
        $ordencompra = PurchaseOrder::select('estado')->where('id', $request->id)->first();
        if($ordencompra->estado == 'ARCHIVADO'){
           return  $this->setestado('',$request->id);
        }else{
            return $this->setestado('ARCHIVADO',$request->id);
        }
    }
    public function validar_costo_detalle(){
        $comprobar = false;
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            if($cart->options->valor == 0){$comprobar = true;}
        }
        if($comprobar == true){
            return response()->json(['errors' => array('detalle' => 'Ingrese el costo unitario en el detalle')],422);
        }
    }

    public function historial_precio(Request $request){
        $precio = 0;
        $precio_historico = WarehouseIncome::historial_precio_oc($request->producto_id);
        if ($precio_historico){
            $moneda = Currency::find($request->moneda_id);
            $precio = ($moneda->codigo == '01') ? $precio_historico[0]->preciomn : $precio_historico[0]->preciome;
        }
        return $precio;
    }

    public function eliminar_instancias_antesdepedidos(){

        foreach (Cart::instance($this->instancia)->content() as $cart) {

            Cart::instance($this->instancia)->remove($cart->rowId);
            $item = $cart->options->item;
            session()->push('item_to_delete', $item);

        }
        //Cart::instance($this->instancia)->destroy();
    }

    private function pedidos_productos_iguales($producto_id){

        foreach (Cart::instance($this->instancia)->content() as $cart) {
            if($producto_id == $cart->options->producto_id){ return $cart->rowId;}
        }
    }

    private function unir_pedido_producto_igual($rowId, $data, $pedido){
        $item = Cart::instance($this->instancia)->get($rowId);
        $options = $item->options->merge([
            'cantidad' => $item->options->cantidad + $pedido,
            'glosa' => $item->options->glosa . ", ". $data->options->glosa,
        ]);

        Cart::instance($this->instancia)->update($rowId, [
            'options' => $options]);
    }
}
