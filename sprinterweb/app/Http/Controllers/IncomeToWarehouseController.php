<?php

namespace App\Http\Controllers;

use App\AccountingNote\Entities\AccountingNote;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\DocumentToPay\Entities\DocumentToPay;
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
use App\PurchaseOrder\Entities\PurchaseOrder;
use App\Warehouses\Entities\WarehouseIncome;
use App\Warehouses\Entities\WarehouseIncomeDetail;
use App\Warehouses\Entities\WarehouseIncomePending;
use App\Warehouses\Entities\WareHouses;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class IncomeToWarehouseController extends Controller
{
    use CartTrait, Movement, UserPrivileges;

    private $var_ruta = 'incometowarehouse';
    private $var = 'IngresosAlmacen';
    private $tabla_cabecera = 'ingresoalmacen';
    private $instancia = 'detalle_ingreso_almacen';
    private $instancia_existencias = 'ingreso_exr';
    protected $user;
    protected $privilegios = '02.02.03'; //para ingresos
    protected $almacenProcedure;

    public function __construct(AlmacenProcedure $almacenProcedure)
    {
        $this->obtener_cliente();
        $this->almacenProcedure = $almacenProcedure;
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
        $data['view'] = link_view('Almacenes', 'Transacción', 'Ingresos a Almacén', '');

        return view('incomeToWarehouse.list', $data);
    }

    public function list()
    {

        $IWarehouse = WarehouseIncome::listExit('I', Session::get('period_id'));
        return DataTables::of($IWarehouse)->make();

    }

    public function create()
    {
        session()->put('item_to_delete', []);

        $data['var'] = $this->var;
        $data['proceso'] = 'create';
        $data['today'] = Carbon::today()->format('Y-m-d');
        $data['mov_type'] = MovementType::where('factor', 1)->get();
        $data['monedas'] = Currency::all();
        $data['almacenes'] = WareHouses::all();
        $data['centroscosto'] = Costs::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Almacenes', 'Transacción', 'Ingresos a Almacén', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_existencias)->destroy();
        Cart::instance($this->instancia);
        Cart::instance($this->instancia_existencias);

        return view('incomeToWarehouse.create', $data);
    }

    public function edit(Request $request, $id)
    {

        session()->put('item_to_delete', []);

        $ingreso = WarehouseIncome::findOrFail($id);
        $ingresosDetalle = WarehouseIncomeDetail::data($id);
        $ingreso_exr = WarehouseIncomePending::data($id);

        $documento = DocumentToPay::where('ingresoalmacen_id', $ingreso->id)->first();
        $asiento = AccountingNote::where('referencia_id', $ingreso->id)->where('tablareferencia', 'ingresoalmacen')->first();

        $id_factura = $ingreso->docxpagar_id;
        $factura = $id_factura != null ? DocumentToPay::findOrFail($id_factura) : null;

        $data['factura'] = $factura;
        $data['proceso'] = 'edit';
        $data['ingreso'] = $ingreso;
        $data['var'] = $this->var;
        $data['almacenes'] = WareHouses::all();
        $data['monedas'] = Currency::all();
        $data['mov_type'] = MovementType::where('factor', 1)->get();
        $data['centroscosto'] = Costs::all();
        $data['referencia_id'] = $ingreso->ordencompra_id;
        $data['codigo_referencia'] = $ingreso->ordencompra_id != null ? $ingreso->ordencompra->codigoReferencia : null;
        $data['documento_id'] = $documento != null ? $documento->id : null;
        $data['codigo_provision'] = $documento != null ? $documento->codigoprovision : '';
        $data['route'] = $request->route;
        $data['asiento_id'] = $asiento != null ? $asiento->id : 0;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Almacenes', 'Transacción', 'Ingresos a Almacén', '');

        // dd($data);
        Cart::instance('ingresos-referencia')->destroy();
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_existencias)->destroy();
        Cart::instance($this->instancia);  //evito poner la instancia en el trait
        Cart::instance($this->instancia_existencias);

        $this->llenarCarritoIngresoAlmacen($ingresosDetalle, $ingreso->ordencompra_id);
        $this->llenarCarritoExistencias($ingreso_exr, $ingreso->total);

        return view('incomeToWarehouse.detail', $data);
    }

    public function store(StoreRequest $request)
    {
        $moneda = Currency::findOrFail($request->txt_moneda);
        $tipomoneda = $moneda->tipo;

        DB::beginTransaction();

        try{
            $newingreso = new WarehouseIncome();
            $newingreso->periodo_id = Session::get('period_id');
            $newingreso->unegocio_id = 1;
            $newingreso->fecha = $request->txt_fecha;
            $newingreso->tcambio = $request->txt_tcambio;
            $newingreso->tercero_id = $request->txt_tercero;
            $newingreso->moneda_id = $request->txt_moneda;
            $newingreso->glosa = $request->txt_glosa;

            $newingreso->movimientotipo_id = $request->txt_movimiento;
            $movimiento_tipo = MovementType::findOrFail($request->txt_movimiento);
            $newingreso->tipo = $movimiento_tipo->factor == 1 ? 'I' : 'S';

            $newingreso->almacen_id = $request->txt_almacen;
            $almacen = WareHouses::findOrFail($request->txt_almacen);
            $newingreso->sucursal_id = $almacen->sucursal_id;

            $newingreso->refgremision = $request->txt_guia == null ? "" : $request->txt_guia;
            $newingreso->refprovision = $request->txt_factura == null ? "" : $request->txt_factura;
            $newingreso->fechadoc = $request->txt_fechafactura == null ? $request->txt_fecha : $request->txt_fechafactura;

            $newingreso->total = $request->txt_total;
            if ($tipomoneda == 'N') {
                 $newingreso->totalmn = $request->txt_total;
                 $newingreso->totalme = ($request->txt_total / $request->txt_tcambio);
            } elseif ($tipomoneda == 'E') {
                  $newingreso->totalmn = ($request->txt_total * $request->txt_tcambio);
                  $newingreso->totalme = $request->txt_total;
            }

             $newingreso->ventana = 'mov_ingresoalmacen';
             $newingreso->usuario = Session::get('usuario');
             $newingreso->ordencompra_id = $request->txt_referencia_id;

             $newingreso->save();

            if(!Cart::instance($this->instancia_existencias)->content()->isEmpty()){
                $this->insertar_ingreso_exr($newingreso->id);
            }

             DB::commit();

             $id = $newingreso->id;
             $almacen_id = $newingreso->almacen_id;
             $tipocambio = $newingreso->tcambio;

             $this->save_details($id, $almacen_id, $tipocambio, $tipomoneda);

             if($newingreso->ordencompra_id > 1){
                 $this->almacenProcedure->setOrdenCompraEstado($newingreso->ordencompra_id, 'ingresoalmacen');
                 $this->almacenProcedure->setOrdenCompraEstado($newingreso->ordencompra_id, 'docxpagar');
             }

             $this->almacenProcedure->GeneraProvisionAlmacen($id, '');
             $this->almacenProcedure->CntIngresoAlmacen($id, '');

             $data['ruta']  = route('edit.incometowarehouse', $id);


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
            $ingreso = WarehouseIncome::find($request->id);
            $moneda = Currency::findOrFail($request->txt_moneda);
            $tipomoneda = $moneda->tipo;

            $ingreso->fecha = $request->txt_fecha;
            $ingreso->tcambio = $request->txt_tcambio;
            $ingreso->tercero_id = $request->txt_tercero;
            $ingreso->moneda_id = $request->txt_moneda;
            $ingreso->glosa = $request->txt_glosa;

            $ingreso->movimientotipo_id = $request->txt_movimiento;
            $movimiento_tipo = MovementType::findOrFail($request->txt_movimiento);
            $ingreso->tipo = $movimiento_tipo->factor == 1 ? 'I' : 'S';

            $ingreso->almacen_id = $request->txt_almacen;
            $almacen = WareHouses::findOrFail($request->txt_almacen);
            $ingreso->sucursal_id = $almacen->sucursal_id;

            $ingreso->refgremision = $request->txt_guia == null ? "" : $request->txt_guia;
            $ingreso->refprovision = $request->txt_factura == null ? "" : $request->txt_factura;
            $ingreso->fechadoc = $request->txt_fechafactura;

            $ingreso->total = $request->txt_total;
            if ($tipomoneda == 'N') {
                $ingreso->totalmn = $request->txt_total;
                $ingreso->totalme = ($request->txt_total / $request->txt_tcambio);
            } elseif ($tipomoneda == 'E') {
                $ingreso->totalmn = ($request->txt_total * $request->txt_tcambio);
                $ingreso->totalme = $request->txt_total;
            }

            $ingreso->ventana = 'mov_ingresoalmacen';
            $ingreso->ordencompra_id = $request->txt_referencia_id;

            $ingreso->save();

            DB::commit();

            $id = $request->id;
            $almacen_id = $ingreso->almacen_id;
            $tipocambio = $ingreso->tcambio;

            $this->save_details($id, $almacen_id, $tipocambio, $tipomoneda);

            if($ingreso->ordencompra_id > 1){
                $this->almacenProcedure->setOrdenCompraEstado($ingreso->ordencompra_id, 'ingresoalmacen');
                $this->almacenProcedure->setOrdenCompraEstado($ingreso->ordencompra_id, 'docxpagar');
            }

            $this->almacenProcedure->GeneraProvisionAlmacen($id, '');
            $this->almacenProcedure->CntIngresoAlmacen($id, '');

            $data["success"] = "Se guardaron los cambios correctamente";

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    public function listar_carrito()
    {

        return $this->lista($this->instancia);

    }

    public function agregar_item(StoreCartRequest $request)
    {

        $instancia = 'detalle_ingreso_almacen';
        $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo


        if ($ultimoCart == null) {
            $ultimoBd = WarehouseIncomeDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;
        }

        $producto = Productos::find($request->producto_id);
        $centrocosto = Costs::find($request->centrocosto_id);

        Cart::instance($instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $request->parent_id,
                'item' => $item + 1,
                'producto_id' => $request->producto_id,
                'producto_codigo' => $producto->codigo,
                'producto_desc' => $producto->descripcion,
                'unid_id' => $producto->umedida_id,

                'um' => $request->um,
                'lote' => $request->lote != null ? $request->lote : '',
                'fechaDetalle' => $request->fechaDetalle,
                'serie' => $request->serie,

                'cantidad' => $request->cantidad != null ? formatear_numero($request->cantidad, 6) : '',
                'stock' => $request->stock,
                'precio' => formatear_numero($request->importe / $request->cantidad, 6),
                'importe' => formatear_numero($request->importe, 2),

                'CCosto_id' => $request->centrocosto_id,
                'CCosto_codigo' => $centrocosto != null ? $centrocosto->codigo : null,
                'CCosto_desc' => $centrocosto != null ? $centrocosto->descripcion : null,

                'editable' => 1,
            ]]);
    }

    public function update_carrito(UpdateCartRequest $request)
    {
        $instancia = 'detalle_ingreso_almacen';

        $id = $request->row_id;
        $item = Cart::instance($instancia)->get($id);

        $producto = Productos::find($request->producto_id);
        $centrocosto = Costs::find($request->centrocosto_id);


        if($item->options->editable == 0){
            $options = $item->options->merge([
                'lote' => $request->lote != null ? $request->lote : '',
            ]);
        }else{
            $options = $item->options->merge([
                'producto_id' => $request->producto_id,
                'producto_desc' => $producto->descripcion,
                'producto_codigo' => $producto->codigo,
                'unid_id' => $producto->umedida_id,
                'um' => $request->um,
                'stock' => $request->stock,
                'cantidad' => formatear_numero($request->cantidad, 6),
                'precio' => formatear_numero($request->importe / $request->cantidad, 6),
                'importe' => formatear_numero($request->importe, 2),
                'lote' => $request->lote != null ? $request->lote : '',
                'CCosto_id' => $request->centrocosto_id,
                'CCosto_codigo' => $centrocosto != null ? $centrocosto->codigo : null,
                'CCosto_desc' => $centrocosto != null ? $centrocosto->descripcion : null,
            ]);
        }

        Cart::instance($instancia)->update($id, [
            'options' => $options]);

    }

    public function save_details($id, $almacen_id, $tipocambio, $tipomoneda)
    {

        if (Cart::instance($this->instancia)->count() == 0) {
            session()->put('item_to_delete', []);   //se puede eliminar, testear bien
            return response()->json(['errors' => 'Ingrese un producto']);
        } else {

            $items = Session::get('item_to_delete');
            foreach ($items as $item) {
                WarehouseIncomeDetail::where(['parent_id' => $id, 'item' => $item])->delete();
                session()->put('item_to_delete', []);
            }

            foreach (Cart::instance($this->instancia)->content() as $cart) {

                $existe = WarehouseIncomeDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();
                //  dd($consulta);
                if (!$existe) { //!! no existe

                    $ingreso_detalle = new WarehouseIncomeDetail();
                    $ingreso_detalle->parent_id = $id;
                    $ingreso_detalle->item = $cart->options->item;
                    $ingreso_detalle->producto_id = $cart->options->producto_id;
                    $ingreso_detalle->glosa = $cart->options->producto_desc;
                    $ingreso_detalle->umedida_id = $cart->options->unid_id;
                    $ingreso_detalle->serie = '';
                    $ingreso_detalle->lote = $cart->options->lote;;
                    $ingreso_detalle->vencimiento = $cart->options->fechaDetalle != '' ? $cart->options->fechaDetalle : null;
                    $ingreso_detalle->cantidad = $cart->options->cantidad;
                    $ingreso_detalle->precio = $cart->options->precio;
                    $ingreso_detalle->importe = $cart->options->importe;

                    if ($tipomoneda == 'N') {
                        $ingreso_detalle->preciomn = $cart->options->precio;
                        $ingreso_detalle->preciome = $cart->options->precio / $tipocambio;
                        $ingreso_detalle->importemn = $cart->options->importe;
                        $ingreso_detalle->importeme = $cart->options->importe / $tipocambio;
                    } elseif ($tipomoneda == 'E') {
                        $ingreso_detalle->preciomn = $cart->options->precio * $tipocambio;
                        $ingreso_detalle->preciome = $cart->options->precio;
                        $ingreso_detalle->importemn = $cart->options->importe * $tipocambio;
                        $ingreso_detalle->importeme = $cart->options->importe;
                    }

                    $ingreso_detalle->centrocosto_id = $cart->options->CCosto_id;
                    $ingreso_detalle->peso = 0.00;
                    $ingreso_detalle->almacen_id = $almacen_id;
                    $ingreso_detalle->usuario = Session::get('usuario');
                    $ingreso_detalle->save();
                } else {
                    //Editando
                    $item = $cart->options->item;
                    $data['producto_id'] = $cart->options->producto_id;
                    $data['glosa'] = $cart->options->producto_desc;
                    $data['umedida_id'] = $cart->options->unid_id;
                    $data['serie'] = $cart->options->serie;
                    $data['lote'] = $cart->options->lote;
                    $data['cantidad'] = $cart->options->cantidad;
                    $data['precio'] = $cart->options->precio;
                    $data['importe'] = $cart->options->importe;

                    if ($tipomoneda == 'N') {
                        $data['preciomn'] = $cart->options->precio;
                        $data['preciome'] = $cart->options->precio / $tipocambio;
                        $data['importemn'] = $cart->options->importe;
                        $data['importeme'] = $cart->options->importe / $tipocambio;
                    } elseif ($tipomoneda == 'E') {
                        $data['preciomn'] = $cart->options->precio * $tipocambio;
                        $data['preciome'] = $cart->options->precio;
                        $data['importemn'] = $cart->options->importe * $tipocambio;
                        $data['importeme'] = $cart->options->importe;
                    }

                    $data['almacen_id'] = $almacen_id;
                    $data['centrocosto_id'] = $cart->options->CCosto_id;

                    WarehouseIncomeDetail::saveDetails($id, $item, $data);
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

    public function totalizar()
    {
        $total = 0;
        $cantidad = 0;
        $carts = Cart::instance($this->instancia)->content();
        foreach ($carts as $cart) {
            $total += $cart->options->importe;
            $cantidad += $cart->options->cantidad;
        }

        $data['total'] = formatear_numero($total,2);
        $data['cantidad'] = formatear_numero($cantidad, 2);

        return $data;
    }

    public function destroy($id)
    {
        //
    }

    public function provision($id)
    {

        $documento = DocumentToPay::find($id);
        $data['periodo'] = $documento->periodo->descripcion;
        $data['unegocio'] = $documento->unegocio->codigo . ' | ' . $documento->unegocio->descripcion;
        $data['numero'] = $documento->numero;
        $data['fechaproceso'] = $documento->fechaproceso;
        $data['fechadoc'] = $documento->fechadoc;
        $data['sucursal'] = $documento->sucursal->codigo . ' | ' . $documento->sucursal->descripcion;
        $data['tipotran'] = $documento->tipotransaccion->codigo . ' | ' . $documento->tipotransaccion->descripcion;
        $data['tercero'] = $documento->tercero->codigo . ' | ' . $documento->tercero->descripcion;
        $data['tipodoc'] = $documento->documento->codigo;
        $data['seriedoc'] = $documento->seriedoc;
        $data['numerodoc'] = $documento->numerodoc;
        $data['moneda'] = $documento->currency->codigo . ' | ' . $documento->currency->descripcion;
        $data['vencimiento'] = $documento->vencimiento;
        $data['tcambio'] = $documento->tcambio;
        $data['glosa'] = $documento->glosa;
        $data['inafecto'] = $documento->inafecto;
        $data['total'] = $documento->total;


        return $data;
    }

    public function provision_detalle($id)
    {

        $provision_detalle = $this->almacenProcedure->getMovCtaCte($id);
        return DataTables::of($provision_detalle)->make();
    }

    public function references(Request $request)
    {
        Cart::instance('ordenes-compra')->destroy();
        Cart::instance('ordenes-compra-detail')->destroy();

        $arrayvacio = [];
        $grupo = MovementType::find($request->tipomov)->grupo;

        if ($grupo == 'C'){
            $referencias = $this->almacenProcedure->getOrdenCompraPorAtender($request->fechahasta, $request->id, $grupo);

            if($referencias == null){
                return DataTables::of($arrayvacio)->make();
            }else{

                Cart::instance('ordenes-compra');
                $this->llenar_ingresos_referencia($referencias);

                $collection = collect($referencias);
                $unicos = $collection->unique('id');
                return DataTables::of($unicos)->make();
            }
        }
        else{
            return DataTables::of($arrayvacio)->make();
        }
    }

    public function references_detail(Request $request, $accion)
    {
        Cart::instance('ordenes-compra-detail')->destroy();

        if($accion == 'agregar'){
            foreach (Cart::instance('ordenes-compra')->content() as $cart) {
                if ($request->id == $cart->options->id) {
                    $this->llenar_detalle_referencia($cart);
                }
            }
        }else{
            Cart::instance('ordenes-compra-detail')->destroy();
        }

     return DataTables::of(Cart::instance('ordenes-compra-detail')->content())->make();

    }

    public function insertar_referencia(Request $request)
    {

        $ordencompra = [];

        Cart::instance('detalle_ingreso_almacen')->destroy();
        foreach ($request->items as $item) {
            foreach (Cart::instance('ordenes-compra-detail')->content() as $cart) {
                $index = explode('|',  $item['id']);
                if ($index[0] == $cart->id && $index[1] == $cart->name) {
                    $ordencompra['id'] = $cart->id;
                    $ordencompra['documento'] = $cart->options->documento;
                    if( $item['aplicar'] > $cart->options->cantidad ){ //Si elige un valor mayor, asignarle lo maximo a elegir
                      $item['aplicar'] = $cart->options->cantidad - $cart->options->atendido;
                    }
                    $this->agregar_detalle_desde_referencia($cart, $item['aplicar']);
                }
            }
        };

        $orden =  PurchaseOrder::find($ordencompra['id']);
        $data['tercero_id'] = $orden->tercero_id;
        $data['tercero_codigo'] = $orden->tercero->codigo;
        $data['tercero_desc'] = $orden->tercero->descripcion;
        $data['almacen_id'] = $orden->almacen_id;
        $data['moneda_id'] = $orden->moneda_id;
        $data['glosa'] = $cart->options->glosa;
        $data['documento'] = $cart->options->documento;
        $data['referencia_id'] = $ordencompra['id'];

        Cart::instance($this->instancia_existencias);
        $existencias = WarehouseIncome::existencias($ordencompra['id']);
        $colleccion = collect($existencias);
        if(!$colleccion->isEmpty()){
            $total = 0;
            $carts = Cart::instance($this->instancia)->content();
            foreach ($carts as $cart) {
                $total += $cart->options->importe;
            }
            $this->llenarCarritoExistencias($colleccion, $total);
        }

        return $data;
    }

    public function llenarCarritoExistencias($existencias, $total){

        $totalaux = $total;
        $item = 1;
        foreach ($existencias as $existencia){

            Cart::instance($this->instancia_existencias)->add(['id' => 1, 'name' => 'existencias', 'qty' => 1, 'price' => $totalaux > $existencia->total ? $existencia->total : $totalaux,
                'options' => [
                    'parent_id' => $existencia->parent_id,
                    'item' => $item,
                    'docxpagar_id' => array_key_exists('referencia_id', $existencia) ? $existencia->referencia_id :  $existencia->docxpagar_id,
                    'documento' => $existencia->documento,
                    'fechadoc' => $existencia->fechadoc,
                    'moneda' => $existencia->moneda,
                    'glosa' => $existencia->glosa,
                ]
            ]);

            $item = $item+1;

            if(Cart::instance($this->instancia_existencias)->subtotal() == $totalaux){
                break;
            };

            $totalaux = $total - Cart::instance($this->instancia_existencias)->subtotal();
          //  dd($totalaux);

        }

    }

    private function insertar_ingreso_exr($ingreso){

        foreach (Cart::instance($this->instancia_existencias)->content() as $cart) {
            $ingreso_exr = New WarehouseIncomePending();
            $ingreso_exr->parent_id = $ingreso;
            $ingreso_exr->item = $cart->options->item;
            $ingreso_exr->docxpagar_id = $cart->options->docxpagar_id;
            $ingreso_exr->importe = $cart->price;
            $ingreso_exr->save();
        }

    }

    private function agregar_detalle_desde_referencia($cart, $aplicar){

        $instancia = 'detalle_ingreso_almacen';
        $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if ($ultimoCart == null) {
            $ultimoBd = WarehouseIncomeDetail::where('parent_id', $cart->id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;;
        }

        Cart::instance($instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $cart->id,
                'item' => $item + 1,
                'producto_id' => $cart->options->producto_id,
                'producto_codigo' => $cart->options->prd_cod,
                'producto_desc' => $cart->options->prd_dsc,
                'unid_id' => $cart->options->umedida_id,

                'um' => $cart->options->ume_cod,
                'lote' => '',
                'fechaDetalle' => '',
                'serie' => '',

                'cantidad' => $aplicar != null ? $aplicar : '',
                'stock' => '0',
                'precio' => $cart->options->precio,
                'importe' => formatear_numero($aplicar * $cart->options->precio, 2),

                'CCosto_id' => null,
                'CCosto_codigo' => "",
                'CCosto_desc' => "",

                'editable' => 0,
            ]]);

    }

    public function tabla_references(){

        return $this->lista($this->instancia_existencias);
    }

    private function contabilizar_centralizar_setestadoref($id){
        $this->almacenProcedure->GeneraProvisionAlmacen($id, '');
    }

}
