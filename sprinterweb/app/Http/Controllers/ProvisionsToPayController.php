<?php

namespace App\Http\Controllers;

use App\AccountingNote\Entities\AccountingNote;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Bank\Entities\Bank;
use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\CurrentAccountMovement\Entities\CurrentAccountMovement;
use App\Customers\Entities\Customer;
use App\Deductions\Entities\Deduction;
use App\DetractionType\Entities\DetractionType;
use App\Documents\Entities\BankDocument;
use App\Documents\Entities\BankDocumentDetail;
use App\Documents\Entities\Commercial;
use App\Documents\Entities\CommercialCountable;
use App\DocumentToPay\Entities\DocumentoToPayDetraction;
use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\DocumentToPay\Entities\DocumentToPayPaymentWay;
use App\Expenses\Entities\Expense;
use App\FamilyAccountingProduct\Entities\FamilyAccountingProduct;
use App\GoodsServicesType\Entities\GoodsServicesType;
use App\Http\Requests\StoreRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\PaymentCondition\Entities\PaymentCondition;
use App\PaymentMethod\Entities\PaymentMethod;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Products\Entities\Productos;
use App\PurchaseOrder\Entities\PurchaseOrder;
use App\PurchasesType\Entities\PurchasesType;
use App\ServiceOrders\Entities\ServiceOrders;
use App\Subdiaries\Entities\Subdiaries;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Taxes\Entities\Taxes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use PhpParser\Node\Expr\Array_;
use Yajra\DataTables\DataTables;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProvisionsToPayController extends Controller
{
    use CartTrait, Movement, UserPrivileges;
    private $var = 'provisionstopay';
    private $var_ruta = 'ProvisionesPorPagar';
    private $tabla_cabecera = 'docxpagar';
    private $instancia = 'detalle_docxpagar';
    private $ventana = 'MOV_DOCXPAGAR'; //Nombre ventana
    private $provisionToPayProcedure;
    protected $user;
    protected $privilegios = '04.02.04';

    public function __construct(ProvisionesPorPagarProcedure $provisionesPorPagarProcedure)
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
        $this->provisionToPayProcedure = $provisionesPorPagarProcedure;
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var_ruta;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Compras', 'Transacción', 'Provisiones por Pagar', '');
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('provisionstopay.list', $data);
    }

    public function list()
    {
        $provisiones = DocumentToPay::listaProvisiones(Session::get('period_id'));
        return DataTables::of($provisiones)->make();
    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }

    public function create()
    {
        session()->put('item_to_delete', []);

        $data['proceso'] = 'create';
        $data['today'] =  Carbon::today()->format('Y-m-d');
        $data['unidades'] = BusinessUnit::all();
        $data['documentoscompra'] = Commercial::all();
        $data['tipocompras'] = PurchasesType::all();
        $data['monedas'] = Currency::all();
        $data['centroscosto'] = Costs::all();
        $data['cuentas'] = AccountingPlan::all();
        $data['condicionpagos'] = PaymentCondition::all();
        $data['tipodetraccion'] = Deduction::all();
        $data['formasdepago'] = PaymentMethod::all();
        $data['bancos'] = Bank::all();
        $data['mediospago'] = PaymentType::all();
        $data['productos'] = Productos::all();
        $data['sucursales'] = Subsidiaries::all();
        $data['servicios'] = GoodsServicesType::all();
        $data['tipogasto'] = Expense::listar_ordenado();
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        $data['impuestos3'] = Taxes::crsimpuesto3();
        $data['period'] = Period::findOrFail(Session::get('period_id'));

        $data['docxpagarFormaPago'] = null;
        $data['docxpagarDetraccion'] = null;

        $data['view'] = link_view('Compras', 'Transacción', 'Provisiones por Pagar', '');

        $data['header'] = headeroptions($this->var, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        Cart::instance('provision-references')->destroy();

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        return view('provisionstopay.create', $data);
    }

    public function edit($id)
    {
        session()->put('item_to_delete', []);

        $docxpagar = DocumentToPay::findOrFail($id);
        $provisionDetalle = DocumentToPayDetail::detalle($docxpagar->id);

    //    dd($provisionDetalle);
        $asiento = AccountingNote::where('referencia_id', $docxpagar->id)->where('tablareferencia', 'docxpagar')->first();

        $data['docxpagar'] = $docxpagar;
        $data['ordencompra_id'] = $docxpagar->ordencompra_id;
        $data['codigo_orden'] = $docxpagar->ordencompra_id != null ?  $docxpagar->ordencompra->codigoReferencia : null;

        $data['proceso'] = 'edit';
        $data['instancia'] = $this->instancia;

        $data['var'] = $this->var;
        $data['asiento_id'] = $asiento != null ? $asiento->id : 0;
        $data['unidades'] = BusinessUnit::all();
        $data['tipodetraccion'] = Deduction::all();
        $data['formasdepago'] = PaymentMethod::all();
        $data['mediospago'] = PaymentType::all();
        $data['productos'] = Productos::all();
        $data['sucursales'] = Subsidiaries::all();
        $data['servicios'] = GoodsServicesType::all();
        $data['tipocompras'] = PurchasesType::all();
        $data['documentoscompra'] = Commercial::all();
        $data['condicionpagos'] = PaymentCondition::all();
        $data['centroscosto'] = Costs::all();
        $data['monedas'] = Currency::all();

        $data['documentosCompra'] = Commercial::crsdocumentocom($this->ventana);

        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        $data['impuestos3'] = Taxes::crsimpuesto3();
        $data['impuestos4'] = Taxes::crsimpuesto4();

        $data['docxpagarDetraccion'] = DocumentoToPayDetraction::where('parent_id', $id)->first();
        $data['docxpagarFormaPago'] =  DocumentToPayPaymentWay::where('parent_id', $id)->get()->last();//null;

        $data['movctactes'] = CurrentAccountMovement::MuestraHistorial($id);

        $data['period'] = Period::findOrFail(Session::get('period_id'));

        $data['view'] = link_view('Compras', 'Transacción', 'Provisiones por Pagar', '');

        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);




        Cart::instance('provision-references')->destroy();

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        $this->llenarCarritoProvision($provisionDetalle);

        $tienepadre = $docxpagar->parent()->get();

        if($tienepadre->isEmpty()){

            $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
            return view('provisionstopay.edit', $data);
        }
       else{
           $data['header'] = headeroptions($this->var, 'no-edit', '', '',  $this->privilegios(), $this->user);
           return view('provisionstopay.show', $data);
       }

    }

    public function store(StoreRequest $request){

        DB::beginTransaction();

        try{

            $newprovision = new DocumentToPay();

            $newprovision->origen = 'P';
            $newprovision->periodo_id = Session::get('period_id');

            $newprovision->unegocio_id = $request->txt_unegocio;
            $newprovision->fechaproceso = $request->txt_fecha;

            $newprovision->sucursal_id = $request->txt_sucursal;
            $newprovision->tipocompra_id = $request->txt_tipocompra;

            $newprovision->tercero_id = $request->txt_tercero;
            $newprovision->documento_id = $request->txt_tipodoc;

            $newprovision->seriedoc = $request->txt_seriedoc == null ? "" : $request->txt_seriedoc;
            $newprovision->numerodoc = $request->txt_numerodoc == null ? "" : $request->txt_numerodoc;
            $newprovision->fechadoc = $request->txt_fechadoc;

            $newprovision->condicionpago_id = $request->txt_condicionpago;
            $newprovision->vencimiento = $request->txt_vencimiento;

            $newprovision->moneda_id = $request->txt_moneda;
            $newprovision->tcambio = $request->txt_tcambio == null ? "" : $request->txt_tcambio;
            $newprovision->glosa = $request->txt_glosa == null ? "" : $request->txt_glosa;

            $newprovision->tipotransaccion_id = $request->txt_clasificacion;
            $newprovision->tipoadq = $request->txt_adquisicion;

            $newprovision->impuesto_id = $request->txt_igv_id;
            $newprovision->impuesto2_id = $request->txt_percepcion_id;
            $newprovision->impuesto3_id = $request->txt_renta_id;

            $newprovision->base = $request->txt_base == null ? 0.00 : $request->txt_base;
            $newprovision->inafecto = $request->txt_inafecto == null ? 0.00 : $request->txt_inafecto;
            $newprovision->impuesto = $request->txt_igv == null ? 0.00 : $request->txt_igv;
            $newprovision->impuesto2 = $request->txt_percepcion == null ? 0.00 : $request->txt_percepcion;
            $newprovision->total = $request->txt_total == null ? 0.00 : $request->txt_total;

            $newprovision->conpercepcion = $request->check_impuesto == 'on' ? 1: 0 ;

            $newprovision->ordencompra_id = $request->txt_ordencompra_id;

            $newprovision->ventana = $this->ventana;
            $newprovision->usuario = Session::get('usuario');
            $newprovision->save();

            DB::commit();

            $id = $newprovision->id;
            $this->save_details($id);

            if($request->tipodetraccion != ""){
                $this->agregar_detraccion($id, $request);
            }

            if($request->txt_formapago != ""){
                $formapago = PaymentMethod::findOrFail($request->txt_formapago);
                $banco_id = $this->agregar_docbanco($id, $request, $formapago);
                $this->agregar_formadepago($id, $banco_id,  $formapago, $request);

                $lcRet = $this->provisionToPayProcedure->generaCtacte($banco_id, 'docbanco', '');
                if($lcRet != null){
                    return response()->json(['error' => $lcRet]);
                }

                $lcRet = $this->provisionToPayProcedure->cntDocBanco($banco_id, '');
                if($lcRet != null){
                    return response()->json(['error' => $lcRet]);
                }
            }
            //Cambiar estado, contabilizar y centralizar

          //  setOrdenCompraEstado al último porque necesita contabilizar parece
            $this->provisionToPayProcedure->setUpdateIngalm($id, 'C');

            $lcRet = $this->provisionToPayProcedure->generaCtacte($id, 'docxpagar', '');
            if($lcRet != null){
                return response()->json(['error' => $lcRet]);
            }

            $lcRet = $this->provisionToPayProcedure->cntDocxPagar($id, '');
            if($lcRet != null){
                return response()->json(['error' => $lcRet]);
            }

            $this->provisionToPayProcedure->setOrdenCompraEstado($newprovision->ordencompra_id,'docxpagar'.$newprovision->estado);

            $data['ruta'] = route('edit.provisionstopay', $id);

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
                DocumentToPayDetail::where(['parent_id' => $id, 'item' => $item])->delete();
                session()->put('item_to_delete', []);
            }

            foreach (Cart::instance($this->instancia)->content() as $cart) {

                $existe = DocumentToPayDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();
                //  dd($consulta);
                if (!$existe) { //!! no existe

                    $provision_detalle = new DocumentToPayDetail();
                    $provision_detalle->parent_id = $id;
                    $provision_detalle->item = $cart->options->item;
                    $provision_detalle->cuenta_id = $cart->options->cuenta_id;
                    $provision_detalle->esnuevo = $cart->options->esnuevo;
                    $provision_detalle->glosa = $cart->options->cuenta_desc;

                    $provision_detalle->referencia_id = $cart->options->referencia_id;
                    $provision_detalle->importe = $cart->options->importe;

                    $provision_detalle->usuario = Session::get('usuario');
                    $provision_detalle->save();
                }
                else{
                    //Editando
                    $item = $cart->options->item;
                    $data['cuenta_id'] = $cart->options->cuenta_id;
                    $data['glosa'] = $cart->options->cuenta_desc;
                    $data['centrocosto_id'] = $cart->options->CCosto_id;
                    if($cart->options->op_id != null){
                        $data['ordentrabajo_id'] = $cart->options->op_id;
                    }

                    $data['referencia_id'] = $cart->options->referencia_id;
                    $data['importe'] = $cart->options->importe;

                    DocumentToPayDetail::saveDetails($id, $item, $data);
                }

            }

        }

    }

    public function update(StoreRequest $request)
    {

        DB::beginTransaction();

        try{
            $provision = DocumentToPay::find($request->id);

            $provision->origen = 'P';

            $provision->unegocio_id = $request->txt_unegocio;
            $provision->fechaproceso = $request->txt_fecha;

            $provision->sucursal_id = $request->txt_sucursal;
            $provision->tipocompra_id = $request->txt_tipocompra;

            $provision->tercero_id = $request->txt_tercero;
            $provision->documento_id = $request->txt_tipodoc;

            $provision->seriedoc = $request->txt_seriedoc == null ? "" : $request->txt_seriedoc;
            $provision->numerodoc = $request->txt_numerodoc == null ? "" : $request->txt_numerodoc;
            $provision->fechadoc = $request->txt_fechadoc;

            $provision->condicionpago_id = $request->txt_condicionpago;
            $provision->vencimiento = $request->txt_vencimiento;

            $provision->moneda_id = $request->txt_moneda;
            $provision->tcambio = $request->txt_tcambio == null ? "" : $request->txt_tcambio;
            $provision->glosa = $request->txt_glosa == null ? "" : $request->txt_glosa;

            $provision->tipotransaccion_id = $request->txt_clasificacion;
            $provision->tipoadq = $request->txt_adquisicion;

            $provision->impuesto_id = $request->txt_igv_id;
            $provision->impuesto2_id = $request->txt_percepcion_id;
            $provision->impuesto3_id = $request->txt_renta_id;

            $provision->base = $request->txt_base == null ? 0.00 : $request->txt_base;
            $provision->inafecto = $request->txt_inafecto == null ? 0.00 : $request->txt_inafecto;
            $provision->impuesto = $request->txt_igv == null ? 0.00 : $request->txt_igv;
            $provision->impuesto2 = $request->txt_percepcion == null ? 0.00 : $request->txt_percepcion;
            $provision->total = $request->txt_total == null ? 0.00 : $request->txt_total;

            $provision->conpercepcion = $request->check_impuesto == 'on' ? 1: 0 ;

            $provision->ordencompra_id = $request->txt_ordencompra_id;

            $provision->ventana = $this->ventana;
            $provision->usuario = Session::get('usuario');
            $provision->save();

            DB::commit();

            $id = $provision->id;
            $this->save_details($id);

            if($request->detraccion_id == ""){
                if($request->tipodetraccion != ""){
                    $this->agregar_detraccion($id, $request);
                }
            }else{
                $this->update_detraccion($id, $request);
            }

            if($request->txt_formapago != ""){
              $formapago = PaymentMethod::findOrFail($request->txt_formapago);
              $banco_id = $this->agregar_docbanco($id, $request, $formapago);
              $this->agregar_formadepago($id, $banco_id,  $formapago, $request);

              $lcRet = $this->provisionToPayProcedure->generaCtacte($banco_id, 'docbanco', '');
              if($lcRet != null){
                  return response()->json(['error' => $lcRet]);
              }

              $lcRet = $this->provisionToPayProcedure->cntDocBanco($banco_id, '');
              if($lcRet != null){
                  return response()->json(['error' => $lcRet]);
              }

              $data["actualizar"] = 1;

            }

            //Cambiar estado, contabilizar y centralizar
            $this->provisionToPayProcedure->setOrdenCompraEstado($provision->ordencompra_id, 'docxpagar'.$provision->estado);
            $this->provisionToPayProcedure->setUpdateIngalm($id, 'C');

            $lcRet = $this->provisionToPayProcedure->generaCtacte($id, 'docxpagar', '');
            if($lcRet != null){
                return response()->json(['error' => $lcRet]);
            }

            $lcRet = $this->provisionToPayProcedure->cntDocxPagar($id, '');
            if($lcRet != null){
                return response()->json(['error' => $lcRet]);
            }

            $data["success"] = "Se guardaron los cambios correctamente";


            //Para actualizar el documento de los detalles  sin recargar la página
            Cart::instance($this->instancia)->destroy();
            Cart::instance($this->instancia);
            $provisionDetalle = DocumentToPayDetail::detalle($request->id);
            $this->llenarCarritoProvision($provisionDetalle);

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    protected function agregar_cuenta(Request $request)
    {
        $this->validate($request, [
            'cuenta_id' => "required",
            'importe' => "required",
        ],[
            'cuenta_id.required' => "La cuenta es requerida",
           'importe.required' => "El importe es requerido"
            ]);

        $cuenta = AccountingPlan::find($request->cuenta_id);

        $rules = [];
        if($cuenta->pide_ccosto == 1 ) {
            $rules['centrocosto_id'] = 'required';
            $this->validate($request, $rules,['centrocosto_id.required' => "La cuenta exige Centro de costo"]);
        }else{
            $rules['centrocosto_id'] = 'in:';
            $this->validate($request, $rules,['centrocosto_id.in' => "Esta cuenta no exige Centro de Costo"]);
        }


        $instancia = 'detalle_docxpagar';
        $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if($ultimoCart == null){
            $ultimoBd = DocumentToPayDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        }else{
            $item = $ultimoCart->options->item;
        }

        $cuenta = AccountingPlan::find($request->cuenta_id);
        $centrocosto = Costs::find($request->centrocosto_id);

        Cart::instance($instancia)->add(['id' => $item+1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'parent_id' => $request->parent_id,
                'item' => $item+1,

                'referencia_id' => '',
                'codigo_ref' => '',
                'esnuevo' => 1,

                'cuenta_id' => $request->cuenta_id,
                'cuenta_codigo' => $cuenta != null ? $cuenta->codigo : null,
                'cuenta_desc' => $cuenta != null ? $cuenta->descripcion : null,

                'CCosto_id' => $request->centrocosto_id,
                'CCosto_codigo' => $centrocosto != null ? $centrocosto->codigo : null,
                'CCosto_desc' => $centrocosto != null ? $centrocosto->descripcion : null,

                'op_id' => $request->ordentrabajo_id,
                'op_codigo' => $request->ordentrabajo_id,

                'importe' => $request->importe,
            ]]);
    }

    public function update_carrito(Request $request)
    {

        $cuenta = AccountingPlan::findOrFail($request->cuenta_id);
       // $this->validar_cuenta($request, $cuenta);

        $rules = [];
        if($cuenta->pide_ccosto == 1 ) {
            $rules['centrocosto_id'] = 'required';
            $this->validate($request, $rules,['centrocosto_id.required' => "La cuenta exige Centro de costo"]);
        }else{
            $rules['centrocosto_id'] = 'in:';
            $this->validate($request, $rules,['centrocosto_id.in' => "Esta cuenta no exige Centro de Costo"]);
        }


        $instancia = 'detalle_docxpagar';

        $id = $request->row_id;
        $item = Cart::instance($instancia)->get($id);

        $centrocosto = Costs::find($request->centrocosto_id);

        $options = $item->options->merge([
            'CCosto_id' => $request->centrocosto_id,
            'CCosto_codigo' => $centrocosto != null ? $centrocosto->codigo : null,
            'CCosto_desc' => $centrocosto != null ? $centrocosto->descripcion : null,
            'op' => $request->op,
            'importe' => $request->importe,
        ]);

        Cart::instance($instancia)->update($id, [
            'options' => $options]);

    }

    public function eliminar_item(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);
        $item = $request->item;
        session()->push('item_to_delete', $item);
    }

    public function condicionpago(Request $request){
        $condicionpago = PaymentCondition::findOrfail($request->id);
        $dias = $condicionpago->dias;

        $fechavencimiento = (new Carbon($request->fecha))->addDays($dias);
        return $fechavencimiento->format('Y-m-d');
    }


    public function getordenesCompra(Request $request, $id){
        Cart::instance('ordenes-provision')->destroy();
        Cart::instance('ordenes-compra-detail')->destroy();

        $arrayvacio = [];
      //  $grupo = MovementType::find($request->tipomov)->grupo;
        $grupo = 'C';

        if ($grupo == 'C'){
            $ordenescompra = $this->provisionToPayProcedure->getOrdenesCompra($request->fechahasta, $id);

            if($ordenescompra == null){
                return DataTables::of($arrayvacio)->make();
            }else{

                Cart::instance('ordenes-provision');
                $this->llenar_ordenes_to_provision($ordenescompra);

                $collection = collect($ordenescompra);
                $unicos = $collection->unique('id');

                return DataTables::of($unicos)->make();
            }
        }
        else{
            return DataTables::of($arrayvacio)->make();
        }

    }


    public function ordendetail(Request $request, $id)
    {
        Cart::instance('ordenesdetail-provision')->destroy();

        if($request->accion == 'agregar'){
            foreach (Cart::instance('ordenes-provision')->content() as $cart) {
                if ($id == $cart->options->id) {
                    $this->llenar_ordendetail_to_provision($cart);
                }
            }
        }else{
            Cart::instance('ordenesdetail-provision')->destroy();
        }

        return DataTables::of(Cart::instance('ordenesdetail-provision')->content())->make();

    }

    public function insertar_ordencompra(Request $request){
        $data = [];
        Cart::instance($this->instancia)->destroy();
        $orden_id =  $this->UnaCabeceraVariosDetalles($request->items, 'ordenesdetail-provision');

        $orden =  PurchaseOrder::find($orden_id);

        $data['tercero_id'] = $orden->tercero_id;
        $data['tercero_codigo'] = $orden->tercero->codigo;
        $data['tercero_desc'] = $orden->tercero->descripcion;

        $data['almacen_id'] = $orden->almacen_id;
        $data['sucursal_id'] = $orden->sucursal_id;
        $data['moneda_id'] = $orden->moneda_id;
        $data['condicionpago_id'] = $orden->condicionpago_id;
        $data['glosa'] = $orden->glosa;

        $data['base'] = $orden->base;

        $data['impuesto_id'] = $orden->impuesto_id == null ? '' : $orden->impuesto_id;
        $data['impuesto2_id'] = $orden->impuesto2_id == null ? '' : $orden->impuesto2_id;
        $data['impuesto3_id'] = $orden->impuesto3_id == null ? '' : $orden->impuesto3_id;

        $data['impuesto'] = $orden->impuesto;
        $data['impuesto2'] = $orden->impuesto2;
        $data['impuesto3'] = $orden->impuesto3;
        $data['inafecto'] = $orden->inafecto;

        $data['total'] = $orden->total;

        $data['orden_codigo'] = $orden->getCodigoReferenciaAttribute();
        $data['orden_id'] = $orden->id;

        return $data;

    }

    private function agregar_detalle_desde_orden($cart, $data){

        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if ($ultimoCart == null) {
            $ultimoBd = DocumentToPayDetail::where('parent_id', $cart->id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;;
        }

        $cuenta = AccountingPlan::find($data['cuenta_id']);

        Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $cart->id,
                'item' => $item + 1,

                'referencia_id' => $data['referencia_id'],
                'codigo_ref' => $data['docrefer'],
                'esnuevo' => 0,

                'cuenta_id' => $data['cuenta_id'],
                'cuenta_codigo' => $cuenta != null ? $cuenta->codigo : null,
                'cuenta_desc' => $data['glosa'],

                'CCosto_id' => null,
                'CCosto_codigo' => "",
                'CCosto_desc' => "",

                'op_id' => null,
                'op_codigo' => "",

                'importe' => $data['total'],

            ]]);


    }

    public function references(Request $request, $tercero)
    {
        Cart::instance('provision-references')->destroy();
        Cart::instance('provision-references');

        if($request->accion == 'limpiar'){
            return DataTables::of([])->make();
        }

        $referencias = $this->provisionToPayProcedure->getCtaCte($tercero, 'P', $request->fechaproceso, 0, 0);

        if($referencias == null){
            return DataTables::of([])->make();
        }else {
            $this->llenar_references_to_provision($referencias);
            return $this->lista('provision-references');
        }
    }

    public function insertar_referencia(Request $request){

        if($request->items){
            $this->SoloCabeceras($request->items, 'provision-references');
            $this->update_checkbox($request->items);
        }else{
            $this->agregar_cuenta($request);
        }

    }

    private function update_checkbox($items){

        foreach (Cart::instance('provision-references')->content() as $cart) {
            foreach ($items as $item) {
                if ($item['id'] == $cart->id) {
                    $item = Cart::instance('provision-references')->get($cart->rowId);
                    $options = $item->options->merge([
                        'elegido' => 1,
                    ]);
                    Cart::instance('provision-references')->update($cart->rowId, [
                        'options' => $options]);
                }
            }
        };
    }


    private function agregar_detalle_desde_referencia($cart, $data){

        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if ($ultimoCart == null) {
            $ultimoBd = DocumentToPayDetail::where('parent_id', $cart->id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;;
        }

        $cuenta = AccountingPlan::find($cart->options->cuenta_id);

        Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $cart->id,
                'item' => $item + 1,

                'referencia_id' => $cart->options->id,
                'codigo_ref' => $cart->options->documento,

                'esnuevo' => 0,

                'cuenta_id' => $cart->options->cuenta_id,
                'cuenta_codigo' => $cuenta != null ? $cuenta->codigo : null,
                'cuenta_desc' => $cuenta != null ? $cuenta->descripcion : null,

                'CCosto_id' => null,
                'CCosto_codigo' => "",
                'CCosto_desc' => "",

                'op_id' => null,
                'op_codigo' => "",

                'importe' => $data['aplicar'],

            ]]);


    }


    public function aplicar_importes(Request $request){

        $rules = array(
            'igv' => 'required_without_all:percepcion,renta,base',
            'percepcion' => 'required_without_all:igv,renta,base',
            'renta' => 'required_without_all:igv,percepcion,base',
            'base' => 'required_without_all:igv,percepcion,renta',
        );

        $this->validate($request, $rules,[]);

        $data['igv'] = formatear_numero(0.00, 2);
        $data['percepcion'] = formatear_numero(0.00, 2);
        $data['renta'] = formatear_numero(0.00, 2);

        $igv = Taxes::find($request->igv);
        $renta = Taxes::find($request->renta);
        $percepcion = Taxes::find($request->percepcion);
        $check = $request->check;

        if($igv){
            if ($igv->tipocalculo == 'P') {
                $data['igv'] = formatear_numero($request->base * ($igv->valor / 100), 2);
            }
        }

        if($percepcion){
            if ($percepcion->tipocalculo == 'P') {
                $data['percepcion'] = formatear_numero(($request->base + $request->inafecto)  * ($percepcion->valor / 100), 2);
            }
        }

        if($renta){
            if ($renta->tipocalculo == 'P') {
                $data['renta'] = formatear_numero($request->inafecto * ($renta->valor / 100), 2);
            }
        }

        if($check == 'on'){
            $data['total'] = formatear_numero($request->base  +  $request->inafecto + $data['igv'] + $data['percepcion'] - $data['renta'], 2);
            return $data;

        }else{
            $data['total'] =  formatear_numero($request->base  +  $request->inafecto + $data['igv'] - $data['renta'], 2);
            return $data;
        }

    }

    public function verifica_documento_registrado(Request $request)
    {
        $tercero_id = $request->tercero;
        $seriedoc = $request->serie;
        $documento_id = $request->documento;
        $origen = $request->origen;
        $numero = $request->numero;
        $data['documento'] = DocumentToPay::verifica_documento_registro($tercero_id, $seriedoc, $documento_id, $origen, $numero);
        $data['mensaje'] = getMesaage(28);

        return $data;
    }

    public function ctactebanco(Request $request)
    {
        $formapago = PaymentMethod::findOrFail($request->formapago);
        $data['ctactebanco'] = $formapago->ctactebanco;
        $data['currency'] = $formapago->ctactebanco->currency;
        return $data;
    }

    public function getporcentaje(Request $request)
    {
        $tipodetraccion = DetractionType::findOrFail($request->tipodetraccion);
        $data['valor'] = $tipodetraccion->valor;
        return $data;
    }

    public function getdetraccion(Request $request)
    {
        $total = (float)$request->total;
        $valor_ref = (float)$request->valor_ref;
        $porcentaje = (float)$request->porcentaje;

        $valor_mayor = max($total, $valor_ref);
        $importe  = formatear_numero($valor_mayor * ($porcentaje / 100),2) ;
        return $importe;
    }

    private function agregar_docbanco($id, $request, $formapago){

        $banco = new BankDocument();
        $banco->periodo_id = Session::get('period_id');
        $banco->fechaproceso = $request->txt_fecha;
        $banco->unegocio_id = $request->txt_unegocio;
        $banco->tipo = 'E';
        $banco->tcambio = $request->txt_tcambio;
        $banco->sucursal_id = $request->txt_sucursal;
        $banco->banco_id = $formapago->ctactebanco->banco_id;
        $banco->ctactebanco_id = $formapago->ctactebanco_id;
        $banco->moneda_id = $formapago->ctactebanco->moneda_id;
        $banco->tcmoneda = 0;
        $banco->mediopago_id = $request->mediopago;
        $banco->transaccion = $request->transaccion_pago != null ? $request->transaccion_pago : "";
        $banco->total = $request->importepago;
        $banco->ventana = $formapago->ctactebanco->banco->efectivo = 1 ? 'mov_caja' : 'mov_banco';
        $banco->fechacobro = null;
        $banco->estado = 'Activo';
        $banco->glosa = 'PAGO ' . $request->txt_glosa;
        $banco->usuario = Session::get('usuario');

        $banco->save();

        $cuenta = CommercialCountable::cuenta($request->txt_tipodoc, 1);
        $moneda = $formapago->ctactebanco->currency;

        $bancoDetalle = new BankDocumentDetail();
        $bancoDetalle->parent_id = $banco->id;
        $bancoDetalle->item = 1;
        $bancoDetalle->tipooperacion_id = 10; //falta dinamico
        $bancoDetalle->tercero_id  =  $request->txt_tercero;
        $bancoDetalle->referencia_id =  $id;  //Para chekear en store
        $bancoDetalle->esnuevo = 0;
        $bancoDetalle->cuenta_id = $moneda->tipo = 'N' ? $cuenta[0]->pcuentamn_id : $cuenta[0]->pcuentame_id;
        $bancoDetalle->centrocosto_id = null;
        $bancoDetalle->glosa = 'PAGO ' . $request->txt_glosa;
        $bancoDetalle->importe = $request->importepago;
        $bancoDetalle->usuario = Session::get('usuario');

        $bancoDetalle->save();

        return $banco->id;
    }

    private function agregar_formadepago($parent_id, $banco_id, $formapago, $request)
    {

        $ultimoBd = DocumentToPayPaymentWay::where('parent_id', $parent_id)->get()->last();
        $item = !$ultimoBd ? 0 : $ultimoBd->item;

        $docxpagarformapago = new  DocumentToPayPaymentWay();

        $docxpagarformapago->parent_id = $parent_id;
        $docxpagarformapago->item = $item+1;
        $docxpagarformapago->formapago_id = $request->txt_formapago;
        $docxpagarformapago->ctactebanco_id = $formapago->ctactebanco_id;
        $docxpagarformapago->importe = $request->importepago != null ? $request->importepago : 0.00;
        $docxpagarformapago->mediopago_id = $request->mediopago;
        $docxpagarformapago->transaccion = $request->transaccion_pago != null ? $request->transaccion_pago : "";
        $docxpagarformapago->docbanco_id = $banco_id;
        $docxpagarformapago->nrocheque = '';
        $docxpagarformapago->usuario = Session::get('usuario');

        DB::transaction(function () use ($docxpagarformapago) {
            $docxpagarformapago->save();
        });

    }

    private function agregar_detraccion($parent_id, $request){

        $docxpagar= new DocumentoToPayDetraction;
        $docxpagar->parent_id = $parent_id;
        $docxpagar->item = 1;
        $docxpagar->referencial_valor = 0; //probar
        $docxpagar->referencial_factor = 0;  //probar
        $docxpagar->referencial = $request->refvalue != null ? $request->refvalue : 0.00;
        $docxpagar->tipodetraccion_id = $request->tipodetraccion;
        $docxpagar->nrodetraccion = $request->detra_numero;
        $docxpagar->fechadetraccion = $request->detra_fecha;
        $docxpagar->totaldetraccion = $request->total_detra;
        $docxpagar->save();

    }

    private function update_detraccion($parent_id, $request){

        $formapago = DocumentToPayPaymentWay::where('parent_id', $parent_id)->get();

        if($formapago->isEmpty()){
            $data['referencial'] = $request->refvalue != null ? $request->refvalue : 0.00;
            $data['tipodetraccion_id']= $request->tipodetraccion;
            $data['nrodetraccion'] = $request->detra_numero;
            $data['fechadetraccion'] = $request->detra_fecha;
            $data['totaldetraccion'] = $request->total_detra;
        }else{
            $data['nrodetraccion'] = $request->detra_numero;
            $data['fechadetraccion'] = $request->detra_fecha;
        }

        DocumentoToPayDetraction::updateDetraccion($parent_id, $data);

    }

//Puede ir en trait
    public function historial($id){
       $historial = $this->provisionToPayProcedure->getMovctacte($id);
        return DataTables::of($historial)->make();
    }


    public function cuenta_medida(Request $request)
    {
        $productos = Productos::findOrFail($request->products);

        $data['unidades'] = UnidadesMedida::findOrFail($productos->umedida_id);
        $data['cuenta'] = FamilyAccountingProduct::getctacomprapdto($productos->familiapdto_id);
        return $data;
    }


    public function activar_anular_provisionstopay(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                DocumentToPay::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
