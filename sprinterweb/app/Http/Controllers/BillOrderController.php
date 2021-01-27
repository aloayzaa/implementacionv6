<?php

namespace App\Http\Controllers;

use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\Http\Traits\Controls\UserPrivileges;
use App\PaymentMethod\Entities\PaymentMethod;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Products\Entities\Productos;
use App\Warehouses\Entities\WarehouseOrder;
use App\Warehouses\Entities\WarehouseOrderDetail;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class BillOrderController extends Controller
{
    use UserPrivileges;

    private $var_ruta = 'billorder';
    protected $privilegios = '03.02.06';
    protected $ventana = 'mov_facturaventa';
    private $provisionToPayProcedure;


    public function __construct(ProvisionesPorPagarProcedure $provisionesPorPagarProcedure)
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
        $this->provisionToPayProcedure = $provisionesPorPagarProcedure;

    }

    public function index()
    {
        $data['fechita'] = '';
        $fechas = \session('fechaventa');
        if ($fechas !== '') {
            $fecha = new Carbon($fechas);
            $data['fecha'] = $fecha->format('Y-m-d');
        } else {
            $fecha = new Carbon(date('Y-m-d'));
            $data['fecha'] = $fecha->format('Y-m-d');
        }

        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Almacenes', 'Transacción', 'Pedidos al Almacén', '');

        return view('billorder.list', $data);
    }

    public function list()
    {
        if(Session::has('point_id')){
            if(Session::get('point_id') == ''){
                return DataTables::of([])->make();
            }
        }

        $OrdersWarehouse = WarehouseOrder::listPedidosVenta(Session::get('period_id'), 'MOV_PEDIDOPTOVTA_SM');
        return DataTables::of($OrdersWarehouse)->make();
    }

    public function detail_list(Request $request)
    {
        if($request->id == 0){
            return DataTables::of([])->make();
        }else{
            $pedidoDetalle = WarehouseOrderDetail::dataVentas($request->id);
         //   dd($pedidoDetalle);
            return DataTables::of($pedidoDetalle)->make();

        }

    }

    public function caja()
    {
        return DataTables::of([])->make();
    }


    public function getdata($id)
    {
        $pedido = WarehouseOrder::find($id);

        $data['id'] = $pedido->id;
        $data['fecha'] = $pedido->fecha;
        $data['moneda'] = $pedido->moneda->descripcion;
        $data['condicionpago'] = $pedido->condicionpago->descripcion;
        $data['total'] = $pedido->importe;
        $data['igv'] = $igv = $pedido->importe * 0.18;
        $data['valorventa'] = $pedido->importe - $igv;

        return $data;

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
       // dd($request->all());

        $pedido = WarehouseOrder::find($request->id);

        DB::beginTransaction();

        try{

            $newprovision = new DocumentToPay();

            $newprovision->origen = 'C';
            $newprovision->periodo_id = Session::get('period_id');

            $newprovision->unegocio_id = $pedido->unegocio_id;
            $newprovision->fechaproceso = $pedido->fecha;

            $newprovision->puntoventa_id = 1; //falta

            $newprovision->sucursal_id = $pedido->sucursal_id;
            $newprovision->almacen_id = $pedido->almacen_id;

            $newprovision->tipoventa_id = $pedido->tipoventa_id;

            $newprovision->tercero_id = $pedido->tercero_id;
            $newprovision->documento_id = $pedido->documento_id;

     /*       $newprovision->seriedoc = $request->txt_seriedoc == null ? "" : $request->txt_seriedoc;
            $newprovision->numerodoc = $request->txt_numerodoc == null ? "" : $request->txt_numerodoc;*/
            $newprovision->fechadoc = $pedido->fecha;

            $newprovision->condicionpago_id = $pedido->condicionpago_id;
            $newprovision->vencimiento = $pedido->fecha; //calcular fecha
            $newprovision->vendedor_id = $pedido->vendedor_id;

            $newprovision->moneda_id = $pedido->moneda_id;
            $newprovision->tcambio = $pedido->tcambio; //falta
            $newprovision->glosa = $pedido->glosa;

            $newprovision->tipotransaccion_id = 1;
            $newprovision->tipoafectaigv_id = 1; //de donde sale
            $newprovision->impuesto_id = 1;
        /*    $newprovision->impuesto2_id = $request->txt_percepcion_id;
            $newprovision->impuesto3_id = $request->txt_renta_id;*/

            $newprovision->base = 5;
            $newprovision->inafecto = 0;
            $newprovision->impuesto = 2;
            $newprovision->total = 7;

            $newprovision->noalmacen = 1; //que es
            $newprovision->razonsocial = 'Señor';//falfa
            $newprovision->ruc = '0001'; //falta
            $newprovision->direccion = 'Pedro herrera'; //falta
            $newprovision->referencial = 0 ;

            $newprovision->pedidoalmacen_id = $pedido->id;

            $newprovision->ventana = $this->ventana;
            $newprovision->usuario = Session::get('usuario');
            $newprovision->save();

            DB::commit();

            $id = $newprovision->id;
            $this->save_details($id, $pedido->id);


            $data['ruta'] = route('billorder');

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;

    }

    private function save_details($id, $pedido_id){

        $pedidos_detalle = WarehouseOrderDetail::where('parent_id', $pedido_id)->get();

        $item = 0;
        foreach ($pedidos_detalle as $pedido_detalle){

        /*    $producto = Productos::find($pedido_detalle['producto_id']);
            dd($producto->familia->tipoventa);*/

            $provision_detalle = new DocumentToPayDetail();
            $provision_detalle->parent_id = $id;
            $provision_detalle->item = $item+1; // falta
            $provision_detalle->cuenta_id = 11;
            $provision_detalle->glosa = $pedido_detalle['glosa'];
            $provision_detalle->centrocosto_id = null;

            $provision_detalle->tipoprecio = 1;

            $provision_detalle->producto_id = $pedido_detalle['producto_id'];
            $provision_detalle->umedida_id = null;
            $provision_detalle->cantidad = 0;

            $provision_detalle->preciolista = 0;
            $provision_detalle->precio = 0;
            $provision_detalle->importe = $pedido_detalle['importe'];

            $provision_detalle->puntoventa_id = null;
            $provision_detalle->tipoafectaigv_id = 0;

            $provision_detalle->usuario = Session::get('usuario');
            $provision_detalle->save();

            $item ++;
        }
    }



}
