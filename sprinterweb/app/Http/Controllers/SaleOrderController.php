<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Documents\Entities\Commercial;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\MovementTypes\Entities\MovementType;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\Procedures\AlmacenProcedure;
use App\Products\Entities\Productos;
use App\SalesTypes\Entities\SaleType;
use App\Seller\Entities\Seller;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Warehouses\Entities\WarehouseOrder;
use App\Warehouses\Entities\WarehouseOrderDetail;
use App\Warehouses\Entities\WareHouses;
use App\WorkOrder\Entities\WorkOrder;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class SaleOrderController extends Controller
{
    use CartTrait, Movement, UserPrivileges;

    private $tabla_cabecera = 'pedidoalmacen';
    private $var = 'PedidosVenta';
    private $var_ruta = 'saleorder';
    private $instancia = 'detalle_pedido_venta';
    private $instancia_productos = 'lista_productos';
    protected $user;
    protected $privilegios = '03.02.04';
    protected $almacenProcedure;

    public function __construct(AlmacenProcedure $almacenProcedure)
    {
        $this->almacenProcedure = $almacenProcedure;
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Almacenes', 'Transacción', 'Pedidos De Venta', '');

        return view('saleorder.list', $data);
    }

    public function list()
    {
        $saleorders = WarehouseOrder::ListPedidosVenta(Session::get('period_id'), 'MOV_PEDIDOPTOVTA_SM');
        return DataTables::of($saleorders)->make();
    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }

    public function create()
    {
        session()->put('item_to_delete', []);

        //Modal Punto de venta Ramon -.-
        $data['fechita'] = '';
        $fechas = \session('fechaventa');
        if ($fechas !== '') {
            $fecha = new Carbon($fechas);
            $fecha = $fecha->format('Y-m-d');
            $data['fecha'] = $fecha;
        } else {
            $fecha = new Carbon(date('Y-m-d'));
            $fecha = $fecha->format('Y-m-d');
            $data['fecha'] = $fecha;
        }
        $data['fecha_pv'] = $fecha;
        ////

        $data['var'] = $this->var;
        $data['proceso'] = 'create';  //para calcular tcambio
        $data['today'] = Carbon::today()->format('Y-m-d');
        $data['puntosventa'] = PointSale::all();
        $data['tiposventa'] = SaleType::has('tipomovimiento')->get(); //Solo las que tienen tipo de movimiento
        $data['unidades'] = BusinessUnit::all();
        $data['condicionespago'] = PaymentCondition::all();
        $data['monedas'] = Currency::all();
        $data['tiposdocumento'] = Commercial::all();
        $data['vendedores'] = Seller::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Ventas', 'Transacción', 'Pedidos de Venta', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        return view('saleorder.create', $data);
    }

    public function edit($id)
    {
        session()->put('item_to_delete', []);

        $pedido = WarehouseOrder::findOrFail($id);
        $pedidoDetalle = WarehouseOrderDetail::dataVentas($id);

        $data['pedido'] = $pedido;
        $data['proceso'] = 'edit';  //para calcular tcambio
        $data['today'] = Carbon::today()->format('Y-m-d');
        $data['puntosventa'] = PointSale::all();
        $data['tiposventa'] = SaleType::all();
        $data['unidades'] = BusinessUnit::all();
        $data['condicionespago'] = PaymentCondition::all();
        $data['monedas'] = Currency::all();
        $data['tiposdocumento'] = Commercial::all();
        $data['vendedores'] = Seller::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Ventas', 'Transacción', 'Pedidos de Venta', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        $this->llenarCarritoPedidoVenta($pedidoDetalle, $pedido->fecha);


        return view('saleorder.edit', $data);
    }


    public function insertar_productos(Request $request){


        if($request->items){
            $this->SoloCabecerasProductos($request->items, 'lista_productos');
        }

    }

    private function agregar_producto($cart, $data)
    {

        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if($ultimoCart == null){
            $ultimoBd = WarehouseOrderDetail::where('parent_id', $cart->id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        }else{
            $item = $ultimoCart->options->item;
        }

        $producto = Productos::find($cart->id);

        Cart::instance($this->instancia)->add(['id' => $item+1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'parent_id' => $cart->id,
                'item' => $item+1,
                'producto_id' => $cart->id,
                'producto_codigo' => $producto->codigo,
                'producto_desc' => $producto->descripcion,
                'umedida_id' => $cart->options->umedida_id,
                'umedida' => $cart->options->umedida,
                'cantidad' => $data['cantidad'] != null ? formatear_numero($data['cantidad'], 5) : '',
                'precio' => $data['precio'] != null ? $data['precio'] : '',
                'descuento' => $data['descuento'],
                'precioventa' => $preciofinal = $data['descuento'] == '' ? $data['precio'] : $data['precio'] - $data['precio'] * ($data['descuento'] / 100),
                'importe' => $preciofinal * $data['cantidad'],
                'stock' => 0,
            ]]);
    }

    public function product_to_edit(Request $request){
        $item = Cart::instance($this->instancia)->get($request->row_id);

        $data = array(
            $request->row_id => $item
        );

     //   dd(collect($data));
        return DataTables::of($data)->make();
    }

    public function actualizar_producto($cart, $data)
    {

        $instancia = 'detalle_ingreso_almacen';

        $options = $cart->options->merge([

            'umedida_id' => $cart->options->umedida_id,
            'um' => $cart->options->umedida,
            'cantidad' => $data['cantidad'] != null ? formatear_numero($data['cantidad'], 5) : '',
            'precio' => $data['precio'] != null ? $data['precio'] : '',
            'descuento' => $data['descuento'],
            'preciofinal' => $preciofinal = $data['descuento'] == '' ? $data['precio'] : $data['precio'] - $data['precio'] * ($data['descuento'] / 100),
            'importe' => $preciofinal * $data['cantidad'],
        ]);


        Cart::instance($this->instancia)->update($cart->rowId, [
            'options' => $options]);

    }

    public function store(StoreRequest $request)
    {
        $rules = [
            'txt_tipodocumento' => 'required',
            'txt_vendedor' => 'required',
            'txt_tipoventa' => 'required',

        ];
        $message = [
            'txt_tipodocumento.required' => 'El tipo de documento es requerido',
            'txt_vendedor.required' => 'El vendedor es requerido',
            'txt_tipoventa.required' => 'El tipo de venta es requerido'
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();

        try{
            $newpedido = new WarehouseOrder();

            $newpedido->periodo_id = Session::get('period_id');
            $newpedido->fecha = $request->txt_fecha;
            $newpedido->unegocio_id = $request->txt_unegocio;
            $newpedido->tcambio = $request->txt_tcambio;
            $newpedido->tercero_id = $request->txt_tercero;

            $puntoventa = PointSale::find(Session::get('point_id'));
            $newpedido->almacen_id = $puntoventa->almacen_id;
            $newpedido->sucursal_id = $puntoventa->almacen->sucursal_id;

            $newpedido->tipoventa_id = $request->txt_tipoventa;
            $tipoventa = SaleType::find($request->txt_tipoventa);
            $newpedido->movimientotipo_id = $tipoventa->movimientotipo_id;

            $newpedido->puntoventa_id = $request->txt_puntoventa;
            $newpedido->vendedor_id = $request->txt_vendedor;

            $newpedido->moneda_id = $request->txt_moneda;
            $newpedido->condicionpago_id = $request->txt_condicionpago;
            $newpedido->direccion = $request->txt_direccion != null ? $request->txt_direccion : '';
            $newpedido->documento_id = $request->txt_tipodocumento;
            $newpedido->glosa = $request->txt_glosa;

            $newpedido->precio = '0';
            $newpedido->importe = $this->totalizar();
            $newpedido->estado = "ACTIVO";

            $newpedido->occliente = "";
            $newpedido->almacendestino_id = 0;
            $newpedido->ordentrabajo_id = null;
            $newpedido->ventana = 'MOV_PEDIDOPTOVTA_SM';

            $newpedido->usuario = Session::get('usuario');
            $newpedido->save();

            DB::commit();

            $id = $newpedido->id;
            $this->save_details($id);

            $data['ruta'] = route('edit.saleorder', $id);

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    public function update(StoreRequest $request, $id)
    {
        $rules = [
            'txt_tipodocumento' => 'required',
            'txt_vendedor' => 'required',
            'txt_tipoventa' => 'required',

        ];
        $message = [
            'txt_tipodocumento.required' => 'El tipo de documento es requerido',
            'txt_vendedor.required' => 'El vendedor es requerido',
            'txt_tipoventa.required' => 'El tipo de venta es requerido'
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();

        try{
            $newpedido = WarehouseOrder::find($id);

            $newpedido->fecha = $request->txt_fecha;
            $newpedido->unegocio_id = $request->txt_unegocio;
            $newpedido->tcambio = $request->txt_tcambio;
            $newpedido->tercero_id = $request->txt_tercero;

            $puntoventa = PointSale::find(Session::get('point_id'));
            $newpedido->almacen_id = $puntoventa->almacen_id;
            $newpedido->sucursal_id = $puntoventa->almacen->sucursal_id;

            $newpedido->tipoventa_id = $request->txt_tipoventa;
            $tipoventa = SaleType::find($request->txt_tipoventa);
            $newpedido->movimientotipo_id = $tipoventa->movimientotipo_id;

            $newpedido->puntoventa_id = $request->txt_puntoventa;
            $newpedido->vendedor_id = $request->txt_vendedor;

            $newpedido->moneda_id = $request->txt_moneda;
            $newpedido->condicionpago_id = $request->txt_condicionpago;
            $newpedido->direccion = $request->txt_direccion != null ? $request->txt_direccion : '';
            $newpedido->documento_id = $request->txt_tipodocumento;
            $newpedido->glosa = $request->txt_glosa;

            $newpedido->precio = '0';
            $newpedido->importe = $this->totalizar();
            $newpedido->estado = "ACTIVO";

            $newpedido->occliente = "";
            $newpedido->almacendestino_id = 0;
            $newpedido->ordentrabajo_id = null;
            $newpedido->ventana = 'MOV_PEDIDOPTOVTA_SM';

            $newpedido->usuario = Session::get('usuario');
            $newpedido->save();

            DB::commit();

            $id = $newpedido->id;
            $this->save_details($id);
            $data["success"] = "Se guardaron los cambios correctamente";

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }


    public function save_details($id)
    {

        if (Cart::instance($this->instancia)->count() == 0) {
            session()->put('item_to_delete', []);   //se puede eliminar, testear bien
            return response()->json(['errors' => 'Ingrese un producto']);
        } else {

            $items = Session::get('item_to_delete');
            foreach ($items as $item) {
                WarehouseOrderDetail::where(['parent_id' => $id, 'item' => $item])->delete();
                session()->put('item_to_delete', []);
            }

            foreach (Cart::instance($this->instancia)->content() as $cart) {

                $existe = WarehouseOrderDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();
                //  dd($consulta);
                if (!$existe) { //!! no existe

                    $pventa_detalle = new WarehouseOrderDetail();
                    $pventa_detalle->parent_id = $id;
                    $pventa_detalle->item = $cart->options->item;
                    $pventa_detalle->producto_id = $cart->options->producto_id;
                    $pventa_detalle->glosa = $cart->options->producto_desc;
                    $pventa_detalle->umedida_id = $cart->options->umedida_id;
                    $pventa_detalle->serie = '';
                    $pventa_detalle->lote = '';
                    $pventa_detalle->vencimiento = null;
                    //tipoprecio
                    $pventa_detalle->cantidad = $cart->options->cantidad;
                    $pventa_detalle->centrocosto_id = null;
                    $pventa_detalle->precio = $cart->options->precio;
                    $pventa_detalle->descuento = $cart->options->descuento ?: '';
                    $pventa_detalle->importe = $cart->options->importe;
                    $pventa_detalle->peso = 0.00;
                    $pventa_detalle->usuario = Session::get('usuario');
                    $pventa_detalle->save();
                }
                else{
                    //Editando
                    $item = $cart->options->item;


                    $data['producto_id'] = $cart->options->producto_id;
                    $data['umedida_id'] = $cart->options->umedida_id;
                    $data['cantidad'] = $cart->options->cantidad;
                    $data['precio'] = $cart->options->precio;
                    $data['descuento'] = $cart->options->descuento;
                    $data['importe'] = $cart->options->importe;

                    WarehouseOrderDetail::saveDetails($id, $item, $data);
                }
            }
        }

    }

    public function eliminar_item(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);
        $item = $request->item;
        session()->push('item_to_delete', $item);
    }

    public function productWithStock(Request $request){
        Cart::instance($this->instancia_productos)->destroy();

        $id = Session::get('point_id');
        $puntoventa = PointSale::find($id);

        if(!$puntoventa){
            return DataTables::of([])->make();
        }

        //obtenemos la fecha actual
        $fecha = new Carbon(date('Y-m-d'));
        $fecha =    $fecha->format('Y-m-d');
        //ejecutamos el procedimiento
        $products = DB::select('CALL getpdtobusqueda(?,?,?,?)', array($fecha, $puntoventa->almacen_id, '', 0));


        foreach ($products as $product){

            $coleccion = collect($product);
            //agrego el precio del punto de venta asignado a mi data del procedimiento
            $collapsed = $coleccion->merge(["precioventa" =>$puntoventa->precio]);

          //  dd($collapsed);
            Cart::instance($this->instancia_productos)->add(['id' => $collapsed['id'], 'name' => 'productos', 'qty' => 1, 'price' => 0.00,
                'options' =>[
                    'producto_id' => $collapsed['id'],
                    'producto_codigo' => $collapsed['codigo'],
                    'producto_desc' => $collapsed['descripcion'],
                    'umedida_id' => $collapsed['umedida_id'],
                    'umedida' => $collapsed['umedida'],

                    //elegir uno de varios precios en base al precio venta
                    'precioventa' => $collapsed['precioventa'],

                    'precio' => $collapsed['precio1'],
                    'descuento' => 0,
                    'stock' => $collapsed['stock'],
                    'cantidad' => 0.00,

                ]]);

        }

        return $this->lista($this->instancia_productos);
    }

    public function puntoventa(){
        $id = Session::get('point_id');
        if(!$id){
            return response()->json(['error' => 'Seleccione un punto de venta']);
        }
    }

    private function totalizar()
    {
        $total = 0;
        $carts = Cart::instance($this->instancia)->content();
        foreach ($carts as $cart) {
            $total += $cart->options->importe;
        }

        $total = formatear_numero($total,2);

        return $total;
    }
}
