<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Traits\CartTrait;
use App\MovementTypes\Entities\MovementType;
use App\Period\Entities\Period;
use App\Procedures\AlmacenProcedure;
use App\Products\Entities\Productos;
use App\WareHouses\Collections\WarehouseCollection;
use App\Warehouses\Entities\WarehouseIncome;
use App\Warehouses\Entities\WarehouseIncomeDetail;
use App\Warehouses\Entities\WareHouses;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExitToWarehouseController extends Controller
{
    use CartTrait;
    private $var = 'exittowarehouse';
    private $instancia = 'detalle_salida_almacen';
    protected $almacenProcedure;

    public function __construct(AlmacenProcedure $almacenProcedure)
    {
        $this->almacenProcedure = $almacenProcedure;
        $this->obtener_cliente();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'list', '', '');
        $data['view'] = link_view('Almacenes', 'Transacción', 'Salidas de Almacén', '');

        return view('exitToWarehouse.list', $data);
    }

    public function list()
    {
        $ExitWarehouse = WarehouseIncome::listExit('S', Session::get('period_id'));
        return DataTables::of($ExitWarehouse)->make();
    }

    public function create()
    {
        session()->put('item_to_delete', []);

        $data['var'] = $this->var;
        $data['almacenes'] = WareHouses::all();
        $data['unidades'] = BusinessUnit::all();
        $data['mov_type'] = MovementType::where('factor', -1)->get();
        $data['terceros'] = Customer::all();
        $data['monedas'] = Currency::all();
        $data['productos'] = Productos::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'crea', '', '');
        $data['view'] = link_view('Almacenes', 'Transacción', 'Salidas de Almacén', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        return view('exitToWarehouse.create', $data);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try{
            $newsalida = new WarehouseIncome();
            $newsalida->periodo_id = Session::get('period_id');
            $newsalida->unegocio_id = $request->txt_unegocio;
            $newsalida->fecha = $request->txt_fecha;
            $newsalida->tcambio = $request->txt_tcambio;
            $newsalida->almacen_id = $request->txt_almacen;
            $almacen = WareHouses::findOrFail($request->txt_almacen);
            $newsalida->sucursal_id = $almacen->sucursal_id;
            $newsalida->glosa = $request->txt_glosa;
            $newsalida->movimientotipo_id = $request->txt_movimiento;
            $movimiento_tipo = MovementType::findOrFail($request->txt_movimiento);
            $newsalida->tipo = $movimiento_tipo->factor == 1 ? 'I' : 'S';
            $newsalida->tercero_id = $request->txt_tercero;
            $newsalida->moneda_id = $request->txt_moneda;
            $newsalida->refgremision = "";
            $newsalida->refprovision = "";

            $newsalida->total = "";
            $newsalida->totalmn = "";
            $newsalida->totalme = "";
            $newsalida->ventana ='MOV_SALIDAALMACEN';
            $newsalida->usuario = Session::get('usuario');
            $newsalida->save();

            DB::commit();

            $id = $newsalida->id;
            $this->save_details($id);
            $data['ruta']  = route('edit.exittowarehouse', $id);

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    public function update(StoreRequest $request)
    {

        DB::beginTransaction();

        try{
            $salida = WarehouseIncome::find($request->id);

            $salida->unegocio_id = $request->txt_unegocio;
            $salida->fecha = $request->txt_fecha;
            $salida->tcambio = $request->txt_tcambio;
            $salida->almacen_id = $request->txt_almacen;
            $almacen=WareHouses::findOrFail($request->txt_almacen);
            $salida->sucursal_id = $almacen->sucursal_id;
            $salida->glosa = $request->txt_glosa;
            $salida->movimientotipo_id = $request->txt_movimiento;
            $movimiento_tipo = MovementType::findOrFail($request->txt_movimiento);
            $salida->tipo = $movimiento_tipo->factor == 1 ? 'I' : 'S';
            $salida->tercero_id=$request->txt_tercero;
            $salida->moneda_id=$request->txt_moneda;
            $salida->refgremision="";
            $salida->refprovision="";

            $salida->total="";
            $salida->totalmn= "";
            $salida->totalme= "";
            $salida->ventana='MOV_SALIDAALMACEN';
            $salida->save();

            $id = $request->id;
            $this->save_details($id);
            $data["success"] = "Se guardaron los cambios correctamente";

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }
    }

    public function listar_carrito()
    {

        return $this->lista($this->instancia);

    }

    public function edit(Request $request, $id)
    {
        session()->put('item_to_delete', []);

        $salida = WarehouseIncome::findOrFail($id);
        $salidaDetalle = WarehouseIncomeDetail::data($id);
        $almacenes = WareHouses::all();
        $unidades = BusinessUnit::all();
        $terceros = Customer::all();
        $mov_type = MovementType::where('factor', -1)->get();
        $productos = Productos::all();
        $monedas = Currency::all();
        $id_factura = $salida->docxpagar_id;
        $factura = $id_factura != null ? DocumentToPay::findOrFail($id_factura) : null;

        $data['factura'] = $factura;
        $data['proceso'] = 'show';
        $data['unidades'] = $unidades;
        $data['salida_detalle'] = $salidaDetalle;
        $data['salida'] = $salida;
        $data['var'] = $this->var;
        $data['almacenes'] = $almacenes;
        $data['mov_type'] = $mov_type;
        $data['terceros'] = $terceros;
        $data['productos'] = $productos;
        $data['monedas'] = $monedas;
        $data['route'] = $request->route;
        $data['header'] = headeroptions($this->var, 'edita', '', '');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['view'] = link_view('Almacenes', 'Transacción', 'Salidas de Almacén', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);  //evito poner la instancia en el trait

        $this->llenarCarritoSalidaAlmacen($salidaDetalle);

        return view('exitToWarehouse.detail', $data);
    }

    public function agregar_item(StoreCartRequest $request){

       $instancia = 'detalle_salida_almacen';
       $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if($ultimoCart == null){
            $ultimoBd = WarehouseIncomeDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        }else{
            $item = $ultimoCart->options->item;;
        }

        $producto = Productos::find($request->producto_id);

        Cart::instance($instancia)->add(['id' => $item+1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'parent_id' => $request->parent_id,
                'item' => $item+1,
                'producto_id' => $request->producto_id,
                'producto_codigo' => $producto->codigo,
                'producto_desc' => $producto->descripcion,
                'unid_id' => $request->id_unid,
                'um' => $request->um,
                'lote' => $request->lote,
                'fechaDetalle' => $request->fechaDetalle,
                'serie' => $request->serie,
                'cantidad' => $request->cantidad != null ? $request->cantidad : '',
                'stock' => $request->stock,
                'costoUnitario' => $request->costoUnitario != null ? $request->costoUnitario : '',
                'costoTotal'=>$request->costoTotal != null ? $request->costoTotal : '',
                'CCosto_id' => $request->id_CCosto,
                'CCosto_codigo'=>$request->cCosto_codigo,
                'CCosto_desc'=>$request->cCosto_descripcion,
                'peso' => $request->peso,
            ]]);
    }


    public function update_carrito(UpdateCartRequest $request)
    {
        $instancia = 'detalle_salida_almacen';

        $id = $request->row_id;
        $item = Cart::instance($instancia)->get($id);

        $producto = Productos::find($request->producto_id);

        $options = $item->options->merge([
            'producto_id' => $request->producto_id,
            'producto_desc' => $producto->descripcion,
            'producto_codigo' => $producto->codigo,
            'cantidad' => $request->cantidad,
            'lote' => $request->lote,
        ]);

        Cart::instance($instancia)->update($id, [
            'options' => $options]);

    }


    public function save_details($id){

        if(Cart::instance($this->instancia)->count() == 0) {
            session()->put('item_to_delete', []);   //se puede eliminar, testear bien
            return response()->json(['errors'=>'Ingrese un producto']);
        }else{

            $items = Session::get('item_to_delete');
            foreach ($items as $item){
                WarehouseIncomeDetail::where(['parent_id'=>$id,'item'=>$item])->delete();
                session()->put('item_to_delete', []);
            }

            foreach (Cart::instance($this->instancia)->content() as $cart) {

                $existe = WarehouseIncomeDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();
                //  dd($consulta);
                if( !$existe) { //!! no existe

                    $salida_detalle = new WarehouseIncomeDetail();
                    $salida_detalle->parent_id = $id;
                    $salida_detalle->item = $cart->options->item;
                    $salida_detalle->producto_id = $cart->options->producto_id;
                    $salida_detalle->glosa = $cart->options->producto_desc;
                    $salida_detalle->umedida_id = $cart->options->unid_id;
                    $salida_detalle->serie = '';
                    $salida_detalle->lote = $cart->options->lote;;
                    $salida_detalle->vencimiento = $cart->options->fechaDetalle != '' ? $cart->options->fechaDetalle : null;
                    $salida_detalle->cantidad = $cart->options->cantidad;
                    $salida_detalle->precio = '';
                    $salida_detalle->preciomn = '';
                    $salida_detalle->preciome = '';
                    $salida_detalle->importe = '';
                    $salida_detalle->importemn = '';
                    $salida_detalle->importeme = '';
                    $salida_detalle->centrocosto_id = $cart->options->CCosto_id;
                    $salida_detalle->peso = 0.00;
                    $salida_detalle->almacen_id = $cart->options->almacen_id;
                    $salida_detalle->usuario = Session::get('usuario');
                    $salida_detalle->save();
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

                    WarehouseIncomeDetail::saveDetails($id, $item, $data);
                }

            }
        }
    }

    public function eliminar_item(Request $request){
        Cart::instance($this->instancia)->remove($request->rowId);
        $item = $request->item;
        session()->push('item_to_delete', $item);
    }

    public function destroy($id)
    {
        //
    }
}
