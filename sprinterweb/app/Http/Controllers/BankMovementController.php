<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
//use App\Companies\Entities\Pempresa;
use App\Panel\Companies\Entities\Pempresa;
use App\AccountingNote\Entities\AccountingNote;
use App\Activity\Entities\Activity;
use App\Bank\Entities\Bank;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Documents\Entities\BankDocument;
use App\Documents\Entities\BankDocumentDetail;
use App\Http\Requests\BankMovementRequest;
use App\Http\Requests\CashMovementDetail;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\OperationType\Entities\OperationType;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Procedures\TerceroProcedure;
use App\Project\Entities\Project;
use App\Subsidiaries\Entities\Subsidiaries;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Company\Entities\CompanyDiscount;
use App\Taxes\Entities\Taxes;
use App\Documents\Entities\TransitBankDocument;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class BankMovementController extends Controller
{
    use CartTrait, Movement, UserPrivileges;
    protected $var = 'bankmovement';
    protected $instancia = 'detalle_movimientocaja';
    protected $instancia_sp = 'detalle_sp';
    private $ventana = 'MOV_BANCO';
    private $provisionesporpagarprocedure;
    private $terceroProcedure;
    private $alias = "crsdocbanco";
    private $tabla_cabecera = 'docbanco';
    protected $user;
    protected $privilegios = '05.02.03';
    private $lagenteret;
    private $dagenteret;

    public function __construct(ProvisionesPorPagarProcedure $provisionesporpagarprocedure, TerceroProcedure $terceroProcedure)
    {
        $this->obtener_cliente();
        $this->provisionesporpagarprocedure = $provisionesporpagarprocedure;
        $this->terceroProcedure = $terceroProcedure;
        $this->user = 'ADMINISTRADOR';
        $lagenteret = Pempresa::get_parametro('AGENTERETE'); //ver método init - MOV_BANCO
        $this->lagenteret = ($lagenteret->valor == 1) ? true : false;
        $this->dagenteret = '';
        $lcparam = Pempresa::get_parametro('AGENTEDESDE');
        if($lcparam->valor != ''){
            $this->dagenteret = $lcparam->valor;
        }
    }

    public function list()
    {
        $banks = BankDocument::lista_banco('MOV_BANCO');
        return DataTables::of($banks)->make();
    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Movimiento de Bancos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('bankmovement.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session()->put('item_to_delete', []);

        $data['lagenteret'] = ($this->lagenteret) ? 1 : 0;
        $data['today'] =  Carbon::today()->format('Y-m-d');
        $data['route'] = route('store.bankmovement');
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['mediopagos'] = PaymentType::all()->where('estado',1);/*----*/
        //$data['cobradores'] = PersonalCustomer::cobrador();
        $data['bancos'] = Bank::where('efectivo', 0)->where("estado",1)->get(); /*----*/
        $data['sucursales'] = Subsidiaries::all()->where('estado',1); /*----*/
        $data['cuentas'] = AccountingPlan::all()->where('estado',1);
        $data['costos'] = Costs::all()->where('estado',1);
        $data['proyectos'] = Project::all()->where('estado',1);
        $data['actividades'] = Activity::all()->where('estado',1);
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['unegocios'] = BusinessUnit::all()->where('estado',1);
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Movimientos de Bancos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        //dd($data);
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_sp)->destroy();

        return view('bankmovement.create', $data);
    }

    public function store(BankMovementRequest $request)
    {

        $ctactebanco = BankCurrentAccount::select('moneda_id')->where('id',$request->currentaccount)->first();

        try {
            $banco = new BankDocument();
            $banco->periodo_id = Session::get('period_id');
            //$banco->numero = $request->txt_numero;
            $banco->fechaproceso = $request->txt_fecha;
            $banco->unegocio_id = $request->cbo_unidad_negocio;
            $banco->tipo = $request->type;
            $banco->sucursal_id = $request->sucursal;
            $banco->banco_id = $request->bank;
            $banco->ctactebanco_id = $request->currentaccount;
            $banco->moneda_id = $ctactebanco->moneda_id;
            //$banco->tcmoneda = $request->txt_tc;
            $banco->tcambio = $request->changerate;
            $banco->esdiferido = (isset($request->chkdiffer) && $request->chkdiffer == "on") ? 1 : 0;
            $banco->fechacobro = (isset($request->chkdiffer) && $request->chkdiffer == "on" && $request->differ != '') ? $request->differ : null;
            $banco->glosa = $request->comment;
            $banco->concheque = (isset($request->chkcheque) && $request->chkcheque == "on") ? 1 : 0;
            $banco->nrocheque = (isset($request->chkcheque) && $request->chkcheque == "on" && $request->cheque != '') ? $request->cheque : '';
            $banco->giradoa = ($request->girado != '') ? $request->girado : '';
            $banco->total = ($request->txt_total_detalle != '' || $request->txt_total_detalle != null) ? $request->txt_total_detalle : 0;
            $banco->ventana = $this->ventana;
            $banco->estado = "ACTIVO";
            $banco->mediopago_id = $request->paymentway;
            //$banco->transferencia_id = $request->;
            $banco->transaccion = ($request->nrotransaction != '') ? $request->nrotransaction : '';
            //$banco->fechaconcilia = $request->;
            //$banco->fechaelimina = $request->;
            //$banco->cobrador_id = $request->;
            //$banco->fecharecep = $request->;
            //$banco->recibidopor = $request->;
            //$banco->esautomatico = $request->;
            $banco->usuario = Session::get('usuario');
            //$banco->seriecre = $request->txt_comprobacion_retencion; // No existe el campo seriecre en la tabla docbanco - ver la bd del clasic

            $id = null;
            $id = DB::transaction(function () use ($banco, $request) {
                $banco->save();
                $this->save_details($banco->id);
                $this->provisionesporpagarprocedure->generaCtacte($banco->id, 'docbanco', '@a'); //Para actualizar cta.cte antes de verificar deuda
                $this->eliminar_documentos_percepcion();//eliminando documentos de percepción
                $this->validar_documento_descuento($request->txt_fecha, $request->type, $request->paymentway, $banco->id); //Validando si el documento tiene descuento (ingresos)
                $this->setestadoref();
                $this->provisionesporpagarprocedure->generaCtacte($banco->id, 'docbanco', '@a');
                $this->provisionesporpagarprocedure->cntDocBanco($banco->id, '@a');

                return $banco->id;
            });

            $data["ruta"] = route('edit.' . $this->var, $id);
            $data["estado"] = "ok";
            $data["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $data["estado"] = "error";
            $data["error"] = "Error al Grabar!";
            $data["bd"] = $e->getMessage();
        }

        Cart::instance($this->instancia)->destroy();

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        session()->put('item_to_delete', []);
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_sp)->destroy();

        $data['lagenteret'] = ($this->lagenteret) ? 1 : 0;
        $banco = BankDocument::findOrFail($id);
        $data['banco'] = $banco;
        $bancodetalle = BankDocumentDetail::editar_docbanco($id);//BankDocumentDetail::where('parent_id', $id)->get();
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['bancos'] = Bank::where('efectivo', 0)->get();
        $data['ctacte'] = BankCurrentAccount::where('banco_id', $banco->banco_id)->get();
        $data['mediopagos'] = PaymentType::all()->where('estado',1);
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['sucursales'] = Subsidiaries::all()->where('estado',1);
        $data['cuentas'] = AccountingPlan::all();
        $data['costos'] = Costs::all()->where('estado',1);
        $data['unegocios'] = BusinessUnit::all()->where('estado',1);
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Movimientos de Bancos', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $banco->estado, $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['proyectos'] = Project::all()->where('estado',1);
        $data['actividades'] = Activity::all()->where('estado',1);
        $data['asiento_id'] = AccountingNote::where('referencia_id', $banco->id)->first();
        //dd($data['asiento_id']);
        $this->llenarCarritoBanco($bancodetalle);

        //Transferencia
        $data['cod_transfer'] = '';
        $docbanco_transito = TransitBankDocument::docbanco_transferencia($banco->transferencia_id);
        if ($docbanco_transito){
            $data['cod_transfer'] = $docbanco_transito->codigo . '-' . str_pad($docbanco_transito->numero, 5, '0', STR_PAD_LEFT);
        }

        $data['calculos'] = $this->sumar(); //calulos

        return view('bankmovement.edit', $data);
    }

    private function llenarCarritoBanco($bancodetalle)
    {
        //dd($bancodetalle);
        $detalles = collect();

        foreach (Cart::instance($this->instancia)->content() as $cart) {
            $detalles->push(['id' => $cart->options->referencia_id, 'aplicar' => $cart->options->importe]);
        }

        foreach ($bancodetalle as $bancoDetalle) {

            Cart::instance($this->instancia)->add(['id' => $bancoDetalle->item, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'elegido' => $detalles->contains('id', $bancoDetalle->id) ? 1 : 0,
                    'parent_id' => $bancoDetalle->parent_id,
                    'item' => $bancoDetalle->item,
                    'operacion_id' => $bancoDetalle->tipooperacion_id ,
                    'operacion_codigo' => $bancoDetalle->ope_cod,
                    'operacion_descripcion' => $bancoDetalle->ope_dsc,
                    'esanticipo' => $bancoDetalle->esanticipo,
                    'tercero_id' => $bancoDetalle->tercero_id,
                    'tercero_codigo' => $bancoDetalle->codigo != null ? $bancoDetalle->codigo : '',
                    'tercero_descripcion' => $bancoDetalle->nombre != null ? $bancoDetalle->nombre : '',
                    'referencia_id' => $bancoDetalle->referencia_id,
                    'referencia_codigo' => $bancoDetalle->docrefer != null ? $bancoDetalle->docrefer : '',
                    'importe' => $bancoDetalle->importe,
                    'cuenta_id' => $bancoDetalle->cuenta_id,
                    'cuenta_codigo' => $bancoDetalle->cuenta != null ? $bancoDetalle->cuenta : '',
                    'cuenta_descripcion' => $bancoDetalle->cuenta_descripcion != null ? $bancoDetalle->cuenta_descripcion : '',
                    'pide_ccosto' => $bancoDetalle->pide_ccosto,
                    'glosa' => $bancoDetalle->glosa,
                    'esnuevo' => $bancoDetalle->esnuevo,
                    'percepcion' => $bancoDetalle->percepcion,
                    'refpercepcion_id' => $bancoDetalle->refpercepcion_id,
                    'descuento' => $bancoDetalle->descuento,
                    'retencion' => $bancoDetalle->retencion,
                    'retencion_renta' => $bancoDetalle->retencion_renta,
                    'retencion_pension' => $bancoDetalle->retencion_pension,
                    'cci' => $bancoDetalle->cci != null ? $bancoDetalle->cci : '',
                    'total' => $bancoDetalle->importe + $bancoDetalle->percepcion - $bancoDetalle->retencion - $bancoDetalle->retencion_renta - $bancoDetalle->retencion_pension,
                    'centrocosto_id' => $bancoDetalle->centrocosto_id,
                    'centrocosto_codigo' => $bancoDetalle->cco_cod != null ? $bancoDetalle->cco_cod : '',
                    'centrocosto_descripcion' => $bancoDetalle->cco_dsc != null ? $bancoDetalle->cco_dsc : '',
                    'proyecto_id' => $bancoDetalle->proyecto_id,
                    'proyecto_codigo' => $bancoDetalle->pry_cod != null ? $bancoDetalle->pry_cod : '',
                    'proyecto_desc' => $bancoDetalle->pry_dsc != null ? $bancoDetalle->pry_dsc : '',
                    'actividad_id' => $bancoDetalle->actividad_id,
                    'actividad_codigo' => $bancoDetalle->act_cod != null ? $bancoDetalle->act_cod : '',
                    'actividad_descripcion' => $bancoDetalle->act_dsc != null ? $bancoDetalle->act_dsc : '',
                    'refretrenta_id' => $bancoDetalle->refretrenta_id,
                    'refretpension_id' => $bancoDetalle->refretpension_id,
                ]]);
        }
        //dd(Cart::instance($this->instancia)->content());
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BankMovementRequest $request, $id)
    {

        $ctactebanco = BankCurrentAccount::select('moneda_id')->where('id',$request->currentaccount)->first();

        try {
            $banco = BankDocument::findOrFail($id);
            //$banco->periodo_id = Session::get('period_id');
            //$banco->numero = $request->txt_numero;
            $banco->fechaproceso = $request->txt_fecha;
            $banco->unegocio_id = $request->cbo_unidad_negocio;
            $banco->tipo = $request->type;
            $banco->sucursal_id = $request->sucursal;
            $banco->banco_id = $request->bank;
            $banco->ctactebanco_id = $request->currentaccount;
            $banco->moneda_id = $ctactebanco->moneda_id;
            //$banco->tcmoneda = $request->txt_tc;
            $banco->tcambio = $request->changerate;
            $banco->esdiferido = (isset($request->chkdiffer) && $request->chkdiffer == "on") ? 1 : 0;
            $banco->fechacobro = (isset($request->chkdiffer) && $request->chkdiffer == "on" && $request->differ != '') ? $request->differ : null;
            $banco->glosa = $request->comment;
            $banco->concheque = (isset($request->chkcheque) && $request->chkcheque == "on") ? 1 : 0;
            $banco->nrocheque = (isset($request->chkcheque) && $request->chkcheque == "on" && $request->cheque != '') ? $request->cheque : '';
            $banco->giradoa = ($request->girado != '') ? $request->girado : '';
            $banco->total = ($request->txt_total_detalle != '' || $request->txt_total_detalle != null) ? $request->txt_total_detalle : 0;
            $banco->ventana = $this->ventana;
            //$banco->estado = "ACTIVO";
            $banco->mediopago_id = $request->paymentway;
            //$banco->transferencia_id = $request->;
            $banco->transaccion = ($request->nrotransaction != '') ? $request->nrotransaction : '';
            //$banco->fechaconcilia = $request->;
            //$banco->fechaelimina = $request->;
            //$banco->cobrador_id = $request->;
            //$banco->fecharecep = $request->;
            //$banco->recibidopor = $request->;
            //$banco->esautomatico = $request->;
            $banco->usuario = Session::get('usuario');
            //$banco->seriecre = $request->txt_comprobacion_retencion; // No existe el campo seriecre en la tabla docbanco - ver la bd del clasic

            $id = null;
            DB::transaction(function () use ($banco, $request) {
                $banco->save();
                $this->save_details($banco->id);
                $this->provisionesporpagarprocedure->generaCtacte($banco->id, 'docbanco', '@a'); //Para actualizar cta.cte antes de verificar deuda
                $this->eliminar_documentos_percepcion();//eliminando documentos de percepción
                $this->validar_documento_descuento($request->txt_fecha, $request->type, $request->paymentway, $banco->id); //Validando si el documento tiene descuento (ingresos)
                $this->setestadoref();
                $this->provisionesporpagarprocedure->generaCtacte($banco->id, 'docbanco', '@a');
                $this->provisionesporpagarprocedure->cntDocBanco($banco->id, '@a');
            });

            $data["estado"] = "ok";
            $data["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $data["estado"] = "error";
            $data["error"] = "Error al Grabar!";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function save_details($id)
    {

        if (Cart::instance($this->instancia)->count() == 0) {
            session()->put('item_to_delete', []);
            return response()->json(['errors' => 'Ingrese un detalle']);
        } else {
            $items = Session::get('item_to_delete');
            if ($items){
                foreach ($items as $item) {
                    BankDocumentDetail::where(['parent_id' => $id, 'item' => $item])->delete();
                }
                session()->put('item_to_delete', []);
            }

            foreach (Cart::instance($this->instancia)->content() as $cart) {
                $existe = BankDocumentDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();
                if ($existe) { //Editar

                    $item = $cart->options->item;
                    $data['tipooperacion_id'] = $cart->options->has('operacion_id') ? $cart->options->operacion_id : null;
                    $data['tercero_id'] = $cart->options->has('tercero_id') ? $cart->options->tercero_id : null;
                    $data['referencia_id'] = $cart->options->has('referencia_id') ? $cart->options->referencia_id : null;
                    $data['esnuevo'] = $cart->options->has('esnuevo') ? $cart->options->esnuevo : 0;
                    $data['cuenta_id'] = $cart->options->has('cuenta_id') ? $cart->options->cuenta_id : null;
                    $data['centrocosto_id'] = $cart->options->has('centrocosto_id') ? $cart->options->centrocosto_id : null;
                    $data['glosa'] = $cart->options->has('glosa') ? $cart->options->glosa : '';
                    $data['importe'] = $cart->options->has('importe') ? $cart->options->importe : 0;
                    $data['actividad_id'] = $cart->options->has('actividad_id') ? $cart->options->actividad_id : null;
                    $data['proyecto_id'] = $cart->options->has('proyecto_id') ? $cart->options->proyecto_id : null;
                    $data['percepcion'] = $cart->options->has('percepcion') ? $cart->options->percepcion : 0;
                    //$data['refpercepcion_id'] = $cart->options->has('refpercepcion_id') ? $cart->options->refpercepcion_id : '';
                    $data['descuento'] = $cart->options->has('descuento') ? $cart->options->descuento : 0;
                    $data['retencion'] = $cart->options->has('retencion') ? $cart->options->retencion : 0;
                    //$data['refretencion_id'] = $cart->options->has('refretencion_id') ? $cart->options->refretencion_id : '';
                    $data['usuario'] = Session::get('usuario');
                    $data['cci'] = $cart->options->has('cci') ? $cart->options->cci : '';
                    $data['retencion_renta'] = $cart->options->has('retencion_renta') ? $cart->options->retencion_renta : 0;
                    $data['retencion_pension'] = $cart->options->has('retencion_pension') ? $cart->options->retencion_pension : 0;
                    //$data['refretrenta_id'] = $cart->options->has('refretrenta_id') ? $cart->options->refretrenta_id : '';
                    //$data['refretpension_id'] = $cart->options->has('refretpension_id') ? $cart->options->refretpension_id : '';

                    BankDocumentDetail::saveDetails($id, $item, $data);
                } else { //Crear
                    $detalle = new BankDocumentDetail();
                    $detalle->parent_id = $id;
                    $detalle->item = $cart->options->has('item') ? $cart->options->item : 0;
                    $detalle->tipooperacion_id = $cart->options->has('operacion_id') ? $cart->options->operacion_id : null;
                    $detalle->tercero_id = $cart->options->has('tercero_id') ? $cart->options->tercero_id : null;
                    $detalle->referencia_id = $cart->options->has('referencia_id') ? $cart->options->referencia_id : null;
                    $detalle->esnuevo = $cart->options->has('esnuevo') ? $cart->options->esnuevo : 0;
                    $detalle->cuenta_id = $cart->options->has('cuenta_id') ? $cart->options->cuenta_id : null;
                    $detalle->centrocosto_id = $cart->options->has('centrocosto_id') ? $cart->options->centrocosto_id : null;
                    $detalle->glosa = $cart->options->has('glosa') ? $cart->options->glosa : '';
                    $detalle->importe = $cart->options->has('importe') ? $cart->options->importe : 0;
                    $detalle->actividad_id = $cart->options->has('actividad_id') ? $cart->options->actividad_id : null;
                    $detalle->proyecto_id = $cart->options->has('proyecto_id') ? $cart->options->proyecto_id : null;
                    $detalle->percepcion = $cart->options->has('percepcion') ? $cart->options->percepcion : 0;
                    //$detalle->refpercepcion_id = $cart->options->has('refpercepcion_id') ? $cart->options->refpercepcion_id : '';
                    $detalle->descuento = $cart->options->has('descuento') ? $cart->options->descuento : 0;
                    $detalle->retencion = $cart->options->has('retencion') ? $cart->options->retencion : 0;
                    //detalle->refretencion_id = $cart->options->has('refretencion_id') ? $cart->options->refretencion_id : ''
                    $detalle->usuario = Session::get('usuario');
                    $detalle->cci = $cart->options->has('cci') ? $cart->options->cci : '';
                    $detalle->retencion_renta = $cart->options->has('retencion_renta') ? $cart->options->retencion_renta : 0;
                    $detalle->retencion_pension = $cart->options->has('retencion_pension') ? $cart->options->retencion_pension : 0;
                    //$detalle->refretrenta_id = $cart->options->has('refretrenta_id') ? $cart->options->refretrenta_id : '';
                    //$detalle->refretpension_id = $cart->options->has('refretpension_id') ? $cart->options->refretpension_id : '';
                    $detalle->save();
                }
            }
        }
    }

    public function agregar_item(CashMovementDetail $request)
    {
        //dd($request->all());
        $ver_retencion = false;
        $base_retencion = $total_retencion = $tasa = 0;
        $fecha_retencion = $request->txt_fecha; // Fecha para retencion igv
        $percepcion = $retencion = $retencion_renta = $retencion_pension = 0; //carrito

        $data = json_decode($request->data);
        $count = count($data);

        $cci = ($request->txt_cci != null) ? $request->txt_cci : '';
        $activity = $request->activity;
        $project = $request->project;
        if ($count > 1){
            $cci = '';
            $activity = null;
            $project = null;
        }

        $operacion = OperationType::find($request->operation);
        $proyecto = Project::find($project);
        $actividad = Activity::find($activity);
        $moneda = Currency::find($request->currency);

        if ($count > 0) {

            if ($this->lagenteret && $this->dagenteret <= $request->txt_fecha){
                $impuesto = Taxes::select('valor','baseretencion','vigentedesde','vigentehasta')->where('retencion', 1)->where('iva', 1)->orderBy('id','desc')->first(); // VER SI SE NECESITA EL ÙLTIMO REGISTRO
                foreach (Cart::instance($this->instancia_sp)->content() as $cart) {
                    foreach ($data as $d) {
                        if ($d->ids->value == $cart->id) {
                            if ($cart->options->condetraccion == 0){$base_retencion += $d->aplicar->value;}
                        }
                    }
                }
                if ($moneda->tipo != 'N'){$base_retencion = $base_retencion * $request->changerate;}
            }

            foreach (Cart::instance($this->instancia_sp)->content() as $cart) {
                foreach ($data as $d) {
                    if ($d->ids->value == $cart->id) {
                        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first();
                        if ($ultimoCart == null) {
                            $ultimoBd = BankDocumentDetail::where('parent_id', $request->parent_id)->get()->last();
                            $item = !$ultimoBd ? 0 : $ultimoBd->item;
                        } else {
                            $item = $ultimoCart->options->item;
                        }

                        $tercero = Customer::find($cart->options->tercero_id);
                        $pcg = AccountingPlan::find($cart->options->cuenta_id);

                        $afecto_retencion = ($cart->options->conretencion == null) ? 0 : $cart->options->conretencion; //Datos para cálculo de Retención igv
                        $cbo_tipo = ($request->type == 'I') ? 'C' : 'P';
                        $importe = $d->aplicar->value;

                        $count = 0;
                        $carts = Cart::instance($this->instancia)->content();

                        if (!is_null($cart->options->id) || $cart->options->id != 0){ // VER: MOV_BANCO - TXTDOCREFERENCIA1 - KEYPRESS

                            $docxpagar = DocumentToPay::importe_referencia_docbanco($cart->options->id);
                            $codsunat = ($docxpagar->codsunat == null) ? '' : $docxpagar->codsunat;

                            if ($docxpagar){ // calcular importe en caso se cumpla
                                if($codsunat == '07' && $operacion->origen == $cbo_tipo || $docxpagar->periodoemi  == 'ARRFIN'){
                                    $importe = $importe * $cart->options->factor;
                                }

                                if($operacion->origen == $cbo_tipo && $operacion->esanticipo == 0 && $cart->options->factor == -1){
                                    $importe = intval($importe) * -1;
                                    if(($operacion->origen = 'P' && $pcg->dbalance == 'A') || ($operacion->origen == 'C' && $pcg->dbalance = 'D') && $codsunat != '07'){
                                        $importe = intval($importe);
                                    }
                                }
                            }

                            if ($request->type == 'E'){ //Validando si el documento tiene afecto el impuesto de percepción (egresos)

                                $docxpagar_percepcion = DocumentToPay::percepcion_referencia_docbanco($cart->options->id);

                                if ($docxpagar_percepcion){
                                    $percepcion_parent_id = ($docxpagar_percepcion->parent_id != null) ? $docxpagar_percepcion->parent_id : 0;
                                    $percepcion = ($docxpagar_percepcion->impuesto2 != null) ? $docxpagar_percepcion->impuesto2 : 0;

                                    if ($percepcion_parent_id != $request->parent_id && $docxpagar_percepcion->parent_id > 0){
                                        $percepcion = 0;
                                    }
                                }

                                if ($this->lagenteret && $afecto_retencion == 1){ // Validando si el documento está afecto a retencion igv (egresos)

                                    $docxpagar_retencion = DocumentToPay::retencion_referencia_docbanco($cart->options->id);

                                    if ($docxpagar_retencion){
                                        $retencion_impuesto = ($docxpagar_retencion->impuesto != null) ? $docxpagar_retencion->impuesto : 0;
                                        $retencion_detraccion_id = ($docxpagar_retencion->detraccion_id != null) ? $docxpagar_retencion->detraccion_id : 0;

                                        if ($retencion_impuesto != 0 && $retencion_detraccion_id == 0){
                                            $ver_retencion =  true;
                                            $fecha_retencion = $docxpagar_retencion->fechadoc;
                                            $total_retencion = ($docxpagar_retencion->total != null) ? $docxpagar_retencion->total : 0;
                                        }

                                    }

                                    if ($ver_retencion && $impuesto){

                                        $vigente_hasta = ($impuesto->vigentehasta != null) ? $impuesto->vigentehasta : '2999-12-31';

                                        if(entre_intervalo($fecha_retencion, $impuesto->vigentedesde, $vigente_hasta) && ($total_retencion >= $impuesto->baseretencion || $base_retencion > $impuesto->baseretencion)){
                                            $valor = ($impuesto->valor != null) ? $impuesto->valor : 0;
                                            $tasa = $valor;
                                        }
                                        $retencion = ($tasa * $importe / 100);
                                    }
                                }else{
                                    $docxpagar_retencion2 = DocumentToPay::retencion_referencia_docbanco2($cart->options->id);
                                    if ($docxpagar_retencion2){
                                        $retencion = $docxpagar_retencion2->impuesto4;
                                    }
                                }
                                //Validando si el documento está afecto a retencion renta y/o pension (egresos)
                                $docxpagar_retencion_renta_pension = DocumentToPay::retencion_renta_pension($cart->options->id);
                                if ($docxpagar_retencion_renta_pension){
                                    $impuesto3 = ($docxpagar_retencion_renta_pension->impuesto3 != null) ? $docxpagar_retencion_renta_pension->impuesto3 : 0;
                                    $impuesto5 = ($docxpagar_retencion_renta_pension->impuesto5 != null) ? $docxpagar_retencion_renta_pension->impuesto5 : 0;
                                    if($impuesto3 != 0 || $impuesto5 != 0){ // retencion renta
                                        if ($docxpagar_retencion_renta_pension->total != $importe){
                                            if ($docxpagar_retencion_renta_pension->moneda_id == 1){
                                                $ret_rentamn = ($docxpagar_retencion_renta_pension->ret_rentamn != null) ? $docxpagar_retencion_renta_pension->ret_rentamn : 0;
                                                if ($request->currency == 1){
                                                    $retencion_renta = $impuesto3 - $ret_rentamn;
                                                    $retencion_pension = $impuesto5;
                                                }else{
                                                    $retencion_renta = ($impuesto3 - $ret_rentamn) / $request->changerate;
                                                    $retencion_pension = $impuesto5 / $request->changerate;
                                                }
                                            }else{
                                                $ret_rentame  = ($docxpagar_retencion_renta_pension->ret_rentame != null) ? $docxpagar_retencion_renta_pension->ret_rentame : 0;
                                                if ($request->currency == 1){
                                                    $retencion_renta = ($impuesto3 - $ret_rentame) * $request->changerate;
                                                    $retencion_pension = $impuesto5 * $request->changerate;
                                                }else{
                                                    $retencion_renta = $impuesto3 - $ret_rentame;
                                                    $retencion_pension = $impuesto5;
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                        }

                        foreach ($carts as $Carts) { // NO REPETIR EN EL CARRITO LA MISMA REFERENCIA
                            if ($Carts->options->referencia_id == $d->ids->value) {
                                $this->actualizar_referencia($Carts->rowId, $importe, $percepcion, $retencion, $retencion_renta, $retencion_pension, $cci, $request->cost, $project, $activity);
                                $count++;
                            }
                        }

                        if ($count == 0) {
                            Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                                'options' => [
                                    'parent_id' => $request->parent_id,  // id docbanco
                                    'item' => $item + 1,
                                    'operacion_id' => $operacion->id,
                                    'operacion_codigo' => $operacion->codigo,
                                    'operacion_descripcion' => $operacion->descripcion,
                                    'esanticipo' => $operacion->esanticipo,
                                    'tercero_id' => (isset($tercero->id)) ? $tercero->id : null,
                                    'tercero_codigo' => (isset($tercero->codigo)) ? $tercero->codigo : null,
                                    'tercero_descripcion' => (isset($tercero->descripcion)) ? $tercero->descripcion : null,
                                    'referencia_id' => $cart->options->id,
                                    'referencia_codigo' => $cart->options->documento,
                                    'importe' => $importe,
                                    'cuenta_id' => $pcg->id,
                                    'cuenta_codigo' => $pcg->codigo,
                                    'cuenta_descripcion' => $pcg->descripcion,
                                    'pide_ccosto' => $pcg->pide_ccosto,
                                    'glosa' => trim($cart->options->glosa),
                                    'esnuevo' => 0,
                                    'factor' => $cart->options->factor,
                                    'origen' => $cart->options->origen,
                                    'fechaproceso' => $cart->options->fechaproceso,
                                    'percepcion' => $percepcion,
                                    'refpercepcion_id' => null,
                                    'descuento' => 0, // se llena desde otro formulario y en el edit puede traer la data
                                    'retencion' => $retencion,
                                    'retencion_renta' => $retencion_renta,
                                    'retencion_pension' => $retencion_pension,
                                    'cci' => $cci,
                                    'total' => $importe + $percepcion - $retencion - $retencion_renta - $retencion_pension,
                                    'pidedocumento' => $operacion->pidedocumento, //ver si son necesarios
                                    'dbalance' => $pcg->dbalance,
                                    'centrocosto_id' => null,//($request->cost != '') ? $request->cost : null,
                                    'centrocosto_codigo' => '',//(isset($centrocosto->codigo)) ? $centrocosto->codigo : null,
                                    'centrocosto_descripcion' => '',//(isset($centrocosto->descripcion)) ? $centrocosto->descripcion : null,
                                    'proyecto_id' => (isset($proyecto->id)) ? $proyecto->id : null,
                                    'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : null,
                                    'proyecto_desc' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : null,
                                    'actividad_id' => (isset($actividad->id)) ? $actividad->id : null,
                                    'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : null,
                                    'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : null,
                                    'refretrenta_id' => 0,
                                    'refretpension_id' => 0,
                                ]]);
                        }

                    }
                }
            }
        } else {

            $tercero = Customer::find($request->customer);
            $pcg = AccountingPlan::find($request->account);
            $centrocosto = Costs::find($request->cost);

            if ($request->rowId != ''){
                $this->actualizar_detalle($request->rowId, $operacion, $tercero, $request->amount, $pcg, $request->comment, $request->txt_fecha, $cci, $centrocosto, $proyecto, $actividad);
            }else {

                $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first();
                if ($ultimoCart == null) {
                    $ultimoBd = BankDocumentDetail::where('parent_id', $request->parent_id)->get()->last();
                    $item = !$ultimoBd ? 0 : $ultimoBd->item;
                } else {
                    $item = $ultimoCart->options->item;
                }

                Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                    'options' => [
                        'parent_id' => $request->parent_id,
                        'item' => $item + 1,
                        'operacion_id' => $request->operation,
                        'operacion_codigo' => $operacion->codigo,
                        'operacion_descripcion' => $operacion->descripcion,
                        'esanticipo' => $operacion->esanticipo,
                        'tercero_id' => (isset($tercero->id)) ? $tercero->id : null,
                        'tercero_codigo' => (isset($tercero->codigo)) ? $tercero->codigo : null,
                        'tercero_descripcion' => (isset($tercero->descripcion)) ? $tercero->descripcion : null,
                        'referencia_id' => null,
                        'referencia_codigo' => '',
                        'importe' => $request->amount,
                        'cuenta_id' => ($request->account != '') ? $request->account : null,
                        'cuenta_codigo' => (isset($pcg->codigo)) ? $pcg->codigo : '',
                        'cuenta_descripcion' => (isset($pcg->descripcion)) ? $pcg->descripcion : '',
                        'pide_ccosto' => $pcg->pide_ccosto,
                        'glosa' => (isset($pcg->descripcion)) ? $pcg->descripcion : trim($request->comment),
                        'esnuevo' => ($operacion->esanticipo == 1) ? 1 : 0,
                        'factor' => '',
                        'origen' => '',
                        'fechaproceso' => $request->txt_fecha,
                        'percepcion' => 0,
                        'refpercepcion_id' => null,
                        'descuento' => 0, // se llena desde otro formulario y en el edit puede traer la data
                        'retencion' => 0,
                        'retencion_renta' => 0,
                        'retencion_pension' => 0,
                        'cci' => $cci,
                        'total' => $request->amount,
                        'pidedocumento' => $operacion->pidedocumento,
                        'dbalance' => $pcg->dbalance,
                        'centrocosto_id' => ($request->cost != '') ? $request->cost : null,
                        'centrocosto_codigo' => (isset($centrocosto->codigo)) ? $centrocosto->codigo : null,
                        'centrocosto_descripcion' => (isset($centrocosto->descripcion)) ? $centrocosto->descripcion : null,
                        'proyecto_id' => (isset($proyecto->id)) ? $proyecto->id : null,
                        'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : null,
                        'proyecto_desc' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : null,
                        'actividad_id' => (isset($actividad->id)) ? $actividad->id : null,
                        'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : null,
                        'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : null,
                        'refretrenta_id' => 0,
                        'refretpension_id' => 0,
                    ]]);
            }

        }

        return $this->sumar();
    }
    public function reference(Request $request)
    {

        Cart::instance($this->instancia_sp)->destroy();

        if($request->type  == 'clean'){ return DataTables::of([])->make(); }
        if($request->type  == null){ return DataTables::of([])->make(); }

        //origen
        //$origen = 'P';
        //if ($request->type == 'I') { $origen = 'C'; }
        $tipooperacion = OperationType::select('origen')->where('id',$request->operation)->first();
        $origen = $tipooperacion->origen;
        $origen = $origen.substr($this->alias,3);

        $id_tercero = $request->customer;
        $id_operacion = $request->operation;
        $fecha_hasta = $request->finaldate;
        $id = $request->id;
        $referencias = $this->provisionesporpagarprocedure->getCtaCte($id_tercero, $origen, $fecha_hasta, $id, $id_operacion);
        $resultado = collect($referencias);
        //dd($resultado);
        $this->llenar_referencias($resultado, $id_tercero, $request->changerate);
        return DataTables::of(Cart::instance($this->instancia_sp)->content())->make();
    }


    private function llenar_referencias($data, $tercero_filtro, $tcambio)
    {

        $detalles = collect();

        foreach (Cart::instance($this->instancia)->content() as $cart) {
            $detalles->push(['id' => $cart->options->referencia_id, 'aplicar' => $cart->options->importe]);
        }
        foreach ($data as $d) {
            $cuenta = AccountingPlan::find($d->cuenta_id);
            Cart::instance($this->instancia_sp)->add(['id' => $d->id, 'name' => $d->documento, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'elegido' => $detalles->contains('id', $d->id) ? 1 : 0,
                    'id' => $d->id,
                    'documento' => $d->documento,
                    'tipodoc' => $d->tipodoc,
                    'seriedoc' => $d->seriedoc,
                    'numerodoc' => $d->numerodoc,

                    'cuenta_id' => $d->cuenta_id,
                    'cuenta_codigo' => $cuenta['codigo'],
                    'cuenta_desc' => $cuenta['descripcion'],

                    'fecha' => $d->fecha,
                    'tcambio' => $d->tcambio,
                    'vencimiento' => $d->vencimiento,
                    'moneda' => $d->moneda,
                    'tipomoneda' => $d->tipomoneda,
                    'ventana' => $d->ventana,
                    'saldomn' => ($d->tipomoneda != 'N') ? formatear_numero($d->saldome * $tcambio,2) : $d->saldomn,
                    'saldome' => ($d->tipomoneda == 'N') ? formatear_numero($d->saldomn / $tcambio,2) : $d->saldome,
                    'aplicar' => $detalles->contains('id', $d->id) ? $detalles->where('id', $d->id)->pluck('aplicar') : $d->aplicar,
                    'origen' => $d->origen,
                    'factor' => $d->factor,
                    'estado' => $d->estado,
                    'glosa' => ($tercero_filtro == null ) ? $d->razonsocial :  $d->glosa,
                    'fechaproceso' => $d->fechaproceso,
                    'codigo' => $d->codigo,
                    'razonsocial' => $d->razonsocial,
                    'totalmn' => $d->totalmn,
                    'tercero_id' => $d->tercero_id,
                    'conretencion' => $d->conretencion,
                    'condetraccion' => $d->condetraccion,
                ]
            ]);
        }
        // dd(Cart::instance($this->instancia_sp)->content());
    }

    public function eliminar_documentos_percepcion(){
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            if ($cart->options->refpercepcion_id != null && $cart->options->refpercepcion_id > 0 ){
                DocumentToPay::destroy($cart->options->refpercepcion_id);
            }
        }
    }

    public function validar_documento_descuento($fecha_proceso, $tipo, $medio_pago_id, $banco_id){
        $tercero_id = $cuenta_id = $tipooperacion_id = null; //datos de envio
        $descuento = 0; //datos de envio
        $ret = null;
        $mediopago = PaymentType::select('codigo')->where('id', $medio_pago_id)->first();

        foreach (Cart::instance($this->instancia)->content() as $cart) {
            if ($cart->options->tercero_id != $tercero_id){
                $tercero_id = $cart->options->tercero_id;
                $ret = $this->terceroProcedure->validaDeudaSocio($tercero_id, $fecha_proceso,'@a');
            }
            if ($cart->options->referencia_id > 0 && $tipo == "I" && ($mediopago->codigo == '008' || $mediopago->codigo == '009')){
                if ($ret == 1){
                    $docxpagar = DocumentToPay::select('tercero_id','documento_id','fechadoc','periodoemi ')->where('id', $cart->options->referencia_id)->first();
                    if ($docxpagar){
                        $pempresa_dscto = CompanyDiscount::selectcmd($docxpagar->documento_id);
                        if ($pempresa_dscto){
                            if (substr(str_replace("-","", $fecha_proceso),0, 6) < $docxpagar->periodoemi || $fecha_proceso - $docxpagar->fechadoc < $pempresa_dscto->diasemitida && date("d", $fecha_proceso) <= $pempresa_dscto->diasemitida){

                                $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first();
                                if ($ultimoCart == null) {
                                    $ultimoBd = BankDocumentDetail::where('parent_id', $banco_id)->get()->last();
                                    $item = !$ultimoBd ? 0 : $ultimoBd->item;
                                } else {
                                    $item = $ultimoCart->options->item;
                                }
                                $detalle = new BankDocumentDetail();
                                $detalle->parent_id = $banco_id;
                                $detalle->item = $item;
                                $detalle->tercero_id = $docxpagar-$tercero_id;
                                $detalle->tipooperacion_id = $pempresa_dscto->tipooperacion_id;
                                $detalle->cuenta_id = $pempresa_dscto->cuenta_id;
                                $detalle->importe = $pempresa_dscto->porcentaje * -1;
                                $detalle->proyecto_id = null;
                                $detalle->actividad_id = null;
                                $detalle->centrocosto_id = null;
                                $detalle->refpercepcion_id = null;
                                $detalle->esnuevo = 2;
                                $detalle->referencia_id = null;
                                $detalle->glosa = 'DESCUENTO PRONTO PAGO';
                                $detalle->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function editar_detalle(Request $request){

        Cart::instance($this->instancia_sp)->destroy();

        $cart = Cart::instance($this->instancia)->get($request->rowId);

        $tipooperacion = OperationType::select('origen')->where('id',$cart->options->operacion_id)->first();
        $origen = $tipooperacion->origen;
        $origen = $origen.substr($this->alias,3);
        $id_tercero = $cart->options->tercero_id;
        $id_operacion = $cart->options->operacion_id;
        $fecha = $request->txt_fecha;
        $id = $cart->options->parent_id;

        $referencias = $this->provisionesporpagarprocedure->getCtaCte($id_tercero, $origen, $fecha, $id, $id_operacion);

        $referencia_env = array();
        foreach($referencias as $r):
            if ($cart->options->referencia_id == $r->id):
                $referencia_env[] = $r;
                $this->llenar_referencias($referencia_env, $id_tercero, $request->changerate);
            endif;
        endforeach;

        return $cart;
    }

    public function reference_edit(){
        return DataTables::of(Cart::instance($this->instancia_sp)->content())->make();
    }

    public function sumar(){
        $total = $descuento = $retencion = $retencion_renta = $retencion_pension = 0;
        $carts = Cart::instance($this->instancia)->content();
        foreach ($carts as $cart) {
            $total += $cart->options->importe + $cart->options->percepcion - $cart->options->retencion - $cart->options->retencion_renta - $cart->options->retencion_pension;
            $descuento += $cart->options->descuento;
            $retencion += $cart->options->retencion;
            $retencion_renta += $cart->options->retencion_renta;
            $retencion_pension += $cart->options->retencion_pension;
        }
        $ver_retencion_renta = false;
        $ver_retencion_pension = false;
        if ($retencion_renta > 0 && $ver_retencion_renta == false){
            $ver_retencion_renta = true;
        }
        if ($retencion_pension > 0 && $ver_retencion_pension == false){
            $ver_retencion_pension = true;
        }
        $data['total'] = formatear_numero($total,2);
        $data['descuento'] = formatear_numero($descuento,2);
        $data['retencion'] = formatear_numero($retencion,2);
        $data['retencion_renta'] = formatear_numero($retencion_renta,2);
        $data['retencion_pension'] = formatear_numero($retencion_pension, 2);
        return $data;
    }

    private function actualizar_referencia($rowId, $importe, $percepcion, $retencion, $retencion_renta, $retencion_pension, $cci, $cost, $project, $activity){
        //$centrocosto = Costs::find($cost);
        $proyecto = Project::find($project);
        $actividad = Activity::find($activity);

        $item = Cart::instance($this->instancia)->get($rowId);
        $options = $item->options->merge([
            'importe' => $importe,
            'percepcion' => $percepcion,
            'retencion' => $retencion,
            'retencion_renta' => $retencion_renta,
            'retencion_pension' => $retencion_pension,
            'cci' => $cci,
            'total' => $importe + $percepcion - $retencion - $retencion_renta - $retencion_pension,
            //'centrocosto_id' => ($cost != '') ? $cost : null, // Ya se agreagó por referencia, siempre es null
            //'centrocosto_codigo' => (isset($centrocosto->codigo)) ? $centrocosto->codigo : null,
            //'centrocosto_descripcion' => (isset($centrocosto->descripcion)) ? $centrocosto->descripcion : null,
            'proyecto_id' => (isset($proyecto->id)) ? $proyecto->id : null,
            'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : null,
            'proyecto_desc' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : null,
            'actividad_id' => (isset($actividad->id)) ? $actividad->id : null,
            'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : null,
            'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : null,
        ]);

        Cart::instance($this->instancia)->update($rowId, [
            'options' => $options]);
    }

    private function actualizar_detalle($rowId, $operacion, $tercero, $importe, $pcg, $glosa, $fecha_proceso, $cci, $cost, $proyecto, $actividad){

        $item = Cart::instance($this->instancia)->get($rowId);

        $options = $item->options->merge([
            'operacion_id' => (isset($operacion->id)) ? $operacion->id : null,
            'operacion_codigo' => (isset($operacion->codigo)) ? $operacion->codigo : '',
            'operacion_descripcion' => (isset($operacion->descripcion)) ? $operacion->descripcion : '',
            'tercero_id' => (isset($tercero->id)) ? $tercero->id : null,
            'tercero_codigo' => (isset($tercero->codigo)) ? $tercero->codigo : '',
            'tercero_descripcion' => (isset($tercero->descripcion)) ? $tercero->descripcion : '',
            'referencia_id' => null,
            'referencia_codigo' => '',
            'importe' => $importe,
            'cuenta_id' => (isset($pcg->id)) ? $pcg->id : null,
            'cuenta_codigo' => (isset($pcg->codigo)) ? $pcg->codigo : '',
            'cuenta_descripcion' => (isset($pcg->descripcion)) ? $pcg->descripcion : '',
            'pide_ccosto' => $pcg->pide_ccosto,
            'glosa' => (isset($pcg->descripcion)) ? $pcg->descripcion : trim($glosa),
            'esnuevo' => (isset($operacion->esanticipo) && $operacion->esanticipo == 1) ? 1 : 0,
            'fechaproceso' => $fecha_proceso,
            'cci' => $cci,
            'total' => $importe,
            'pidedocumento' => (isset($operacion->pidedocumento))? $operacion->pidedocumento : null,
            'dbalance' => (isset($pcg->dbalance)) ? $pcg->dbalance : null,
            'centrocosto_id' => (isset($cost->id)) ? $cost->id : null,
            'centrocosto_codigo' => (isset($cost->codigo)) ? $cost->codigo : '',
            'centrocosto_descripcion' => (isset($cost->descripcion)) ? $cost->descripcion : '',
            'proyecto_id' => (isset($proyecto->id)) ? $proyecto->id : null,
            'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : '',
            'proyecto_desc' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : '',
            'actividad_id' => (isset($actividad->id)) ? $actividad->id : null,
            'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : '',
            'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : '',
        ]);

        Cart::instance($this->instancia)->update($rowId, [
            'options' => $options]);
    }
    public function eliminar_item(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);
        $item = $request->item;
        session()->push('item_to_delete', $item);
        return $this->sumar();
    }
    public function setestadoref(){
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            if ($cart->options->esanticipo == 1 && $cart->options->referencia_id != null){
                DocumentToPay::where('id', $cart->options->referencia_id)->update(['estado' => 'AT.TOTAL' ]);
            }
        }
    }
    public function eliminar_importe(){
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            //actualizar el carrito
            $item = Cart::instance($this->instancia)->get($cart->rowId);
            $options = $item->options->merge([
                'importe' => 0,
                'total' => 0
            ]);
            Cart::instance($this->instancia)->update($cart->rowId, [
                'options' => $options]);
            //---------------------
        }

        return $this->sumar();
    }

}
