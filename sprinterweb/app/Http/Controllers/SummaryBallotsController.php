<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\DocComunicacion\DocComunicacion;
use App\DocComunicacion\DocComunicacionDetalle;
use App\Http\Requests\StoreRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\MovementTypes\Entities\MovementType;
use App\Panel\Companies\Entities\Pempresa;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Warehouses\Entities\WarehouseIncome;
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

class SummaryBallotsController extends Controller
{
    use CartTrait, Movement, UserPrivileges;

    private $var_ruta = 'summaryballots';
    private $var = 'SummaryBallouts';
   // private $tabla_cabecera = 'ingresoalmacen';
    private $instancia = 'detalle_summary';

    protected $user;
    protected $privilegios = '02.02.03'; //para ingresos

    protected $provision_procedure;
    protected $lcpe;

    public function __construct(ProvisionesPorPagarProcedure $provision_procedure)
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
        $this->provision_procedure = $provision_procedure;
        $this->lcpe = Pempresa::get_parametro('CPE_ACTIVAR')->valor; // Activar Factura Electrónica
    }

    public function index(Request $request){

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Gestión de Ventas', 'Procesos', 'Resumen Boletas Electronicas', '');

        return view('summaryballots.list', $data);
    }

    public function list()
    {

        $data = DocComunicacion::listDoc('MOV_DOCCOMUNICACION_RESUMEN'); //Falta el dato Motivo


//        $data = [
//            '0' =>[
//                'id' => '1',
//                'numero' => '000',
//                'fecha' => '2020/205',
//                'motivo' => 'Resumen de boletas',
//                'estado' => 'Activo'
//            ],
//            '1' =>[
//                'id' => '2',
//                'numero' => '000',
//                'fecha' => '2020/205',
//                'motivo' => 'Resumen de boletas',
//                'estado' => 'Activo'
//            ],
//        ];

        return DataTables::of($data)->make();

    }

    public function create()
    {
        session()->put('item_to_delete', []);

        $data['var'] = $this->var;
        $data['proceso'] = 'create';  //para calcular tcambio
        $data['today'] = Carbon::today()->format('Y-m-d');

        $data['unidades'] = BusinessUnit::all();
        $data['monedas'] = Currency::all();
        $data['centroscosto'] = Costs::all();
        $data['workorders'] = WorkOrder::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Gestión de Ventas', 'Procesos', 'Resumen Boletas Electronicas', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);
        return view('summaryballots.create', $data);
    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }

    public function edit(Request $request, $id)
    {
        dd($request);
        session()->put('item_to_delete', []);

        $pedido = WarehouseOrder::findOrFail($id);
        $pedidoDetalle = WarehouseOrderDetail::data($id);

        $data['proceso'] = 'edit';
        $data['pedido'] = $pedido;
        $data['unidades'] = BusinessUnit::all();
        $data['monedas'] = Currency::all();
        $data['centroscosto'] = Costs::all();
        $data['workorders'] = WorkOrder::all();
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var_ruta, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Gestión de Ventas', 'Procesos', 'Resumen Boletas Electronicas', '');


       // $this->llenarCarritoPedidoAlmacen($pedidoDetalle, $pedido->fecha, $pedido->almacen_id);
        return view('summaryballots.edit', $data);

    }

    public function aplicadoc(Request $request, $fechaproceso){

        if($request->accion == 'limpiar'){
            return DataTables::of([])->make();
        }
        dd($request->all());
    }

    public function getResumen(Request $request){
        $boletas = $this->provision_procedure->getResumenCpe(0,'2020-01-10', 'B');

        if($boletas == null){
            return DataTables::of([])->make();
        }else{

            Cart::instance($this->instancia)->destroy();
            Cart::instance($this->instancia);

            foreach ($boletas as $boleta) {
                Cart::add(['id' => $boleta->id, 'name' => $boleta->codigo, 'qty' => 1, 'price' => 0.00,
                    'options' => [
                        'id' => $boleta->id,
                        'codigo' => $boleta->codigo,
                        'descripcion' => $boleta->descripcion,
                        'docrefer' => $boleta->docrefer,
                        'estado' => $boleta->estado,
                        'moneda' => $boleta->moneda,
                        'seriedoc' => $boleta->seriedoc,
                        'tcambio' => $boleta->tcambio,
                        'tcmoneda' => $boleta->tcmoneda,
                        'tercero_id' => $boleta->tercero_id,
                        'tipo' => $boleta->tipo,
                        'tipodoc' => $boleta->tipodoc,
                        'total' => $boleta->tipo == 'N' ? $boleta->total : $boleta->total * $boleta->tcambio,  //validar moneda
                        'ventana' => $boleta->ventana,

                    ]
                ]);
            }

            return $boletas;
        }
    }

    public function store(StoreRequest $request)
    {

        DB::beginTransaction();

        try{
            $docComunicacion = new DocComunicacion();

            $docComunicacion->periodo_id = Session::get('period_id');

            $docComunicacion->tipo = "RC"; //Preguntar al inge
            $docComunicacion->tiporesumen = "B"; //Preguntar al inge
            $docComunicacion->numero = $request->txt_numero;

            $docComunicacion->fechaproceso = $request->txt_fecha;


            $docComunicacion->moneda_id = $request->txt_moneda;

            $docComunicacion->tcambio = $request->txt_tcambio;
            $docComunicacion->glosa = $request->txt_glosa;

            $docComunicacion->estado = "ACTIVO";


            $docComunicacion->ventana = 'MOV_DOCCOMUNICACION_RESUMEN';
            $docComunicacion->usuario = Session::get('usuario');
            $docComunicacion->save();

            DB::commit();

            $id = $docComunicacion->id;
            $this->save_details($id);

            $data['ruta'] = route('edit.summaryballots', $id);



         /*   //generafe
            $GeneraFEController = new GeneraFEController($id, 'mov_facturaventa', $this->lcpe, $docComunicacion);
            $respuesta_generafe = $GeneraFEController->generaFE();

            if($respuesta_generafe['estado'] != 'ok') {

                    return response()->json(['errors' => array('detalle' => $respuesta_generafe['mensaje'])], 422);
            }*/

        }catch(\Exception $e){
            DB::rollback();
            return response()->json((['errors' => [$e->getMessage()]]), 422);
           // $data["error"] = "Error al Grabar";
           // $data["bd"] = $e->getMessage();
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
                DocComunicacionDetalle::where(['parent_id' => $id, 'item' => $item])->delete();
                session()->put('item_to_delete', []);
            }

            foreach (Cart::instance($this->instancia)->content() as $cart) {

                $existe = DocComunicacionDetalle::where("parent_id", $id)->where("item", $cart->options->item)->exists();

                if (!$existe) { //!! no existe

                    $docComunicacion_detalle = new DocComunicacionDetalle();
                    $docComunicacion_detalle->parent_id = $id;
                    $docComunicacion_detalle->item = $cart->options->item;
                    $docComunicacion_detalle->tercero_id = $cart->options->tercero_id;
                    $docComunicacion_detalle->referencia_id = $cart->options->id; //debe ser id
                    $docComunicacion_detalle->importe = $cart->options->total;

                    $docComunicacion_detalle->save();
                }
                else{
                    //Editando
                    $item = $cart->options->item;
                    $data['tercero_id'] = $cart->options->tercero_id;
                    $data['referencia_id'] = $cart->options->id; //debe ser id
                    $data['importe'] = $cart->options->total;

                    DocComunicacionDetalle::saveDetails($id, $item, $data);
                }

            }

        }


    }

}
