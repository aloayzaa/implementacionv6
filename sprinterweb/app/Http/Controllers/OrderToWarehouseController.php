<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\MovementTypes\Entities\MovementType;
use App\Period\Entities\Period;
use App\Procedures\AlmacenProcedure;
use App\Products\Entities\Productos;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Warehouses\Entities\WarehouseIncome;
use App\Warehouses\Entities\WarehouseOrder;
use App\Warehouses\Entities\WarehouseOrderDetail;
use App\Warehouses\Entities\WareHouses;

use App\WorkOrder\Entities\WorkOrder;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OrderToWarehouseController extends Controller
{
    use CartTrait, Movement, UserPrivileges;

    private $tabla_cabecera = 'pedidoalmacen';
    private $var = 'PedidosAlmacen';
    private $var_ruta = 'ordertowarehouse';
    private $instancia = 'detalle_pedido_almacen';
    protected $user;
    protected $privilegios = '02.02.02';
    protected $almacenProcedure;

    public function __construct(AlmacenProcedure $almacenProcedure)
    {
        $this->almacenProcedure = $almacenProcedure;
        $this-> obtener_cliente();
        $this->user = Session::get('usuario');
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Almacenes', 'Transacción', 'Pedidos al Almacén', '');

        return view('orderToWarehouse.list', $data);
    }

    public function list()
    {
        $OrdersWarehouse = WarehouseOrder::listPedidos(Session::get('period_id'));
        return DataTables::of($OrdersWarehouse)->make();
    }

    public function create()
    {
        session()->put('item_to_delete', []);

        $data['var'] = $this->var;
        $data['proceso'] = 'create';  //para calcular tcambio
        $data['today'] = Carbon::today()->format('Y-m-d');
        $data['sucursales'] = Subsidiaries::all();
        $data['almacenes'] = WareHouses::all();
        $data['unidades'] = BusinessUnit::all();
        $data['mov_type'] = MovementType::all();
        $data['monedas'] = Currency::all();
        $data['centroscosto'] = Costs::all();
        $data['workorders'] = WorkOrder::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        $data['view'] = link_view('Almacenes', 'Transacción', 'Pedidos al Almacén', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        return view('orderToWarehouse.create', $data);
    }


    public function store(StoreRequest $request)
    {

        DB::beginTransaction();

        try{
            $newpedido = new WarehouseOrder();

            $newpedido->periodo_id = Session::get('period_id');
            $newpedido->fecha = $request->txt_fecha;
            $newpedido->unegocio_id = $request->txt_unegocio;
            $newpedido->tcambio = $request->txt_tcambio;
            $newpedido->almacen_id = $request->txt_almacen;
            $newpedido->sucursal_id = $request->txt_sucursal; //chekear
            $newpedido->glosa = $request->txt_glosa;
            $newpedido->movimientotipo_id = $request->txt_movimiento;

            $newpedido->tercero_id = $request->txt_tercero;
            $newpedido->moneda_id = $request->txt_moneda;

            $newpedido->precio = 1;
            $newpedido->importe = "";
            $newpedido->estado = "ACTIVO";

            $newpedido->occliente = "";
            $newpedido->almacendestino_id = 0;
            $newpedido->ordentrabajo_id = $request->txt_ordentrabajo;

            $newpedido->ventana = 'MOV_PEDIDOALMACEN';
            $newpedido->usuario = Session::get('usuario');
            $newpedido->save();

            DB::commit();

            $id = $newpedido->id;
            $this->save_details($id);

            //set estado ref
            //$ingreso = WarehouseIncome::where('pedidoalmacen_id', $id)->Notwhere('estado', 'ANULADO');

            $data['ruta'] = route('edit.ordertowarehouse', $id);

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    public function update(StoreRequest $request){

        DB::beginTransaction();

        try{

            $pedido = WarehouseOrder::find($request->id);

            $pedido->fecha = $request->txt_fecha;
            $pedido->unegocio_id = $request->txt_unegocio;
            $pedido->tcambio = $request->txt_tcambio;
            $pedido->almacen_id = $request->txt_almacen;
            $pedido->sucursal_id = $request->txt_sucursal;
            $pedido->glosa = $request->txt_glosa;
            $pedido->movimientotipo_id = $request->txt_movimiento;

            $pedido->tercero_id = $request->txt_tercero;
            $pedido->moneda_id = $request->txt_moneda;

                //  $pedido->precio=1; ni lo mando porq siempre es 1
            $pedido->importe = "";

            $pedido->occliente = "";
            $pedido->almacendestino_id = 0;
            $pedido->ordentrabajo_id = $request->txt_ordentrabajo;

            $pedido->ventana = 'MOV_PEDIDOALMACEN';
            $pedido->save();

            DB::commit();

            $id = $request->id;
            $this->save_details($id);
            $data["success"] = "Se guardaron los cambios correctamente";

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;

    }

    public function edit(Request $request, $id)
    {
        session()->put('item_to_delete', []);

        $pedido = WarehouseOrder::findOrFail($id);
        $pedidoDetalle = WarehouseOrderDetail::data($id);

        $data['proceso'] = 'edit';
        $data['pedido'] = $pedido;
        $data['unidades'] = BusinessUnit::all();
        $data['almacenes'] = WareHouses::all();
        $data['sucursales'] = Subsidiaries::all();
        $data['mov_type'] = MovementType::all();
        $data['monedas'] = Currency::all();
        $data['centroscosto'] = Costs::all();
        $data['workorders'] = WorkOrder::all();
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Almacenes', 'Transacción', 'Pedido al Almacén', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);  //evito poner la instancia en el trait

        $this->llenarCarritoPedidoAlmacen($pedidoDetalle, $pedido->fecha, $pedido->almacen_id);
        return view('orderToWarehouse.edit', $data);

    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }

    public function dataset(Request $request, $id)
    {
        $producto = Productos::find($id);
        $data['stock'] = $this->almacenProcedure->getStock($request->almacen, $id, '', '', $request->fecha, 0, 0);
        $data['unidad_codigo'] = $producto->unidadmedida->codigo;
        return $data;
    }

    public function change(Request $request, $id)
    {
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);
        $pedidoDetalle = WarehouseOrderDetail::data($id);
        $this->llenarCarritoPedidoAlmacen($pedidoDetalle, $request->fecha, $request->almacen);
    }

    public function agregar_item(StoreCartRequest $request)
    {
        $instancia = 'detalle_pedido_almacen';
        $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

    //  dd($ultimoCart);

        if($ultimoCart == null){
            $ultimoBd = WarehouseOrderDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        }else{
            $item = $ultimoCart->options->item;
        }

        $producto = Productos::find($request->producto_id);

        $centrocosto = Costs::find($request->centrocosto_id);

        Cart::instance($instancia)->add(['id' => $item+1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'parent_id' => $request->parent_id,
                'item' => $item+1,
                'producto_id' => $request->producto_id,
                'producto_codigo' => $producto->codigo,
                'producto_desc' => $producto->descripcion,
                'unid_id' => $producto->umedida_id,
                'um' => $request->um,
                'lote' => $request->lote != null ? $request->lote : '',
                'fechaDetalle' => $request->fechaDetalle,
                'serie' => $request->serie,
                'cantidad' => $request->cantidad != null ? formatear_numero($request->cantidad, 5) : '',
                'stock' => $request->stock_pedido,
                'CCosto_id' => $request->centrocosto_id,
                'CCosto_codigo' => $centrocosto != null ? $centrocosto->codigo : null,
                'CCosto_desc' => $centrocosto != null ? $centrocosto->descripcion : null,
            ]]);
    }

    public function update_carrito(UpdateCartRequest $request)
    {
        $instancia = 'detalle_pedido_almacen';

        $id = $request->row_id;
        $item = Cart::instance($instancia)->get($id);

        $producto = Productos::find($request->producto_id);
        $centrocosto = Costs::find($request->centrocosto_id);

        $options = $item->options->merge([
            'producto_id' => $request->producto_id,
            'producto_desc' => $producto->descripcion,
            'producto_codigo' => $producto->codigo,
            'unid_id' => $producto->umedida_id,
            'um' => $request->um,
            'stock' => $request->stock_pedido,
            'cantidad' => formatear_numero($request->cantidad,5),
            'lote' => $request->lote != null ? $request->lote : '',
            'CCosto_id' => $request->centrocosto_id,
            'CCosto_codigo' => $centrocosto != null ? $centrocosto->codigo : null,
            'CCosto_desc' => $centrocosto != null ? $centrocosto->descripcion : null,
        ]);

        Cart::instance($instancia)->update($id, [
            'options' => $options]);

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

                    $pedido_detalle = new WarehouseOrderDetail();
                    $pedido_detalle->parent_id = $id;
                    $pedido_detalle->item = $cart->options->item;
                    $pedido_detalle->producto_id = $cart->options->producto_id;
                    $pedido_detalle->glosa = $cart->options->producto_desc;
                    $pedido_detalle->umedida_id = $cart->options->unid_id;
                    $pedido_detalle->serie = '';
                    $pedido_detalle->lote = $cart->options->lote;;
                    $pedido_detalle->vencimiento = $cart->options->fechaDetalle != '' ? $cart->options->fechaDetalle : null;
                    $pedido_detalle->cantidad = $cart->options->cantidad;
                    $pedido_detalle->centrocosto_id = $cart->options->CCosto_id;
                    $pedido_detalle->peso = 0.00;
                    $pedido_detalle->usuario = Session::get('usuario');
                    $pedido_detalle->save();
                }
                else{
                    //Editando
                    $item = $cart->options->item;
                    $data['producto_id'] = $cart->options->producto_id;
                    $data['glosa'] = $cart->options->producto_desc;
                    $data['umedida_id'] = $cart->options->unid_id;
                    $data['serie'] = $cart->options->serie;
                    $data['cantidad'] = $cart->options->cantidad;
                    $data['lote'] = $cart->options->lote;
                    $data['centrocosto_id'] = $cart->options->CCosto_id;

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


    public function getAlmacenes($id){
        if($id != 0){
            $sucursal = Subsidiaries::find($id);
            return $sucursal->almacen;
        }else{
            $alamacenes = WareHouses::all(['id', 'descripcion']);
            return $alamacenes;
        }

    }
}
