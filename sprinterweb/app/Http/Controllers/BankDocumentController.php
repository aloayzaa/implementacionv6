<?php

namespace App\Http\Controllers;

use App\Bank\Entities\Bank;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Currency\Entities\Currency;
use App\Documents\Collections\TransitBankDocumentCollection;
use App\Documents\Entities\BankDocument;
use App\Documents\Entities\BankDocumentDetail;
use App\Documents\Entities\TransitBankDocument;
use App\OperationType\Entities\OperationType;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class BankDocumentController extends Controller
{
    private $id_menu = 53; //Transferencias 05.02.04
    private $var = 'transfers';
    private $modulo_para_periodo = 'modtes';
    private $ventana = 'MOV_BANCO';
    private $transitbankdocumentcollection;
    private $provisionesporpagarprocedure;

    public function __construct(TransitBankDocumentCollection $transitbankdocumentcollection, ProvisionesPorPagarProcedure $provisionesporpagarprocedure)
    {
        $this->obtener_cliente();
        $this->transitbankdocumentcollection = $transitbankdocumentcollection;
        $this->provisionesporpagarprocedure = $provisionesporpagarprocedure;
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
        $data['route'] = $request->route;
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Transferencias','');
        $data['header'] = headeroptions($this->var, 'list');

        return view('transfers.list', $data);
    }

    public function list()
    {
        $banks = TransitBankDocument::lista_docBancoTransito();
        $this->transitbankdocumentcollection->actions($banks);
        return DataTables::of($banks)->rawColumns(['actions', 'estado'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['bancos'] = Bank::all();
        $data['operacionese'] = OperationType::where('tipo', 'E')->where('estransferencia', 1)->get();
        $data['operacionesi'] = OperationType::where('tipo', 'I')->where('estransferencia', 1)->get();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['mediopagos'] = PaymentType::all();
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Transferencias','');
        $data['header'] = headeroptions($this->var, 'crea');
        return view('transfers.create', $data);
    }

    public function ctacte(Request $request)
    {
        if ($request->banke) {
            $bank = $request->banke;
        } elseif ($request->banki) {
            $bank = $request->banki;
        }
        $ctacte = BankCurrentAccount::where('banco_id', $bank)->get();
        return $ctacte;
    }

    public function currency(Request $request)
    {
        if ($request->currentaccounte) {
            $currentaccount = $request->currentaccounte;
        } elseif ($request->currentaccounti) {
            $currentaccount = $request->currentaccounti;
        }

        $ctacte = BankCurrentAccount::findOrFail($currentaccount);
        $currency = Currency::findOrFail($ctacte->moneda_id);
        return $currency;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $docBancoTransito = new TransitBankDocument();
            $docBancoTransito->periodo_id = Session::get('period_id');
            $docBancoTransito->numero = $request->txt_numero;
            $docBancoTransito->fechaproceso = $request->processdate;
            $docBancoTransito->estado = 1;
            $docBancoTransito->ventana = 'MOV_TRANSFERENCIABANCO';
            $docBancoTransito->usuario = Auth::id();

            DB::transaction(function () use ($docBancoTransito, $request) {
                $docBancoTransito->save();
                $this->insertar_detalle_docBanco($docBancoTransito->id, $request);
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    private function insertar_detalle_docBanco($id_bancoTransito, $request)
    {
        //EGRESO
        $docBancoE = new BankDocument();
        $docBancoE->periodo_id = Session::get('period_id');
        $docBancoE->numero = $request->txt_numeroe;
        $docBancoE->fechaproceso = $request->processdatee;
        $docBancoE->unegocio_id = 1;
        $docBancoE->tipo = 'E';
        $docBancoE->banco_id = $request->banke;
        $docBancoE->ctactebanco_id = $request->currentaccounte;
        $docBancoE->moneda_id = $request->currencye;
        $docBancoE->tcmoneda = $request->txt_tce;
        $docBancoE->tcambio = $request->changeratee;
        $docBancoE->esdiferido = validaCheck($request->deferredcheck);
        $docBancoE->fechacobro = $request->deferred;
        $docBancoE->glosa = $request->commente;
        $valor_checked = validaCheck($request->checkchequee);
        $docBancoE->concheque = $valor_checked;
        $docBancoE->sucursal_id = 1;
        if ($valor_checked == 1) {
            $docBancoE->nrocheque = $request->checke;
            $docBancoE->giradoa = $request->namee;
        } else {
            $docBancoE->nrocheque = 0;
            $docBancoE->giradoa = '';
        }
        $docBancoE->total = $request->amounte;
        if ($request->cashe == 1) {
            $ventana = 'MOV_CAJA';
        } else {
            $ventana = 'MOV_BANCO';
        }
        $docBancoE->ventana = $ventana;
        $docBancoE->mediopago_id = $request->paymentwaye;
        $docBancoE->transferencia_id = $id_bancoTransito;
        $docBancoE->transaccion = $request->transferatione;
        $docBancoE->fechaconcilia = "";
        $docBancoE->fechaelimina = "";
        $docBancoE->fecharecep = "";
        $docBancoE->recibidopor = "";
        $docBancoE->usuario = Auth::id();

        //INGRESO
        $docBancoI = new BankDocument();
        $docBancoI->periodo_id = Session::get('period_id');
        $docBancoI->numero = $request->txt_numeroi;
        $docBancoI->fechaproceso = $request->processdatei;
        $docBancoI->unegocio_id = 1;
        $docBancoI->tipo = 'I';
        $docBancoI->banco_id = $request->banki;
        $docBancoI->ctactebanco_id = $request->currentaccounti;
        $docBancoI->moneda_id = $request->currencyi;
        $docBancoI->tcmoneda = $request->txt_tci;
        $docBancoI->tcambio = $request->changeratei;
        $docBancoI->esdiferido = 0;
        $docBancoI->fechacobro = "";
        $docBancoI->glosa = $request->commenti;
        $docBancoI->concheque = 0;
        $docBancoI->giradoa = "";
        $docBancoI->total = $request->amounti;
        $docBancoI->sucursal_id = 1;
        if ($request->cashi == 1) {
            $ventana = 'MOV_CAJA';
        } else {
            $ventana = 'MOV_BANCO';
        }
        $docBancoI->ventana = $ventana;
        $docBancoI->mediopago_id = $request->paymentwaye;
        $docBancoI->transferencia_id = $id_bancoTransito;
        $docBancoI->transaccion = $request->transferationi;
        $docBancoI->fechaconcilia = "";
        $docBancoI->fechaelimina = "";
        $docBancoI->fecharecep = "";
        $docBancoI->recibidopor = "";
        $docBancoI->usuario = Auth::id();

        DB::transaction(function () use ($docBancoE, $docBancoI, $request) {
            $docBancoE->save();
            $docBancoI->save();
            $this->insertar_detalle_docBanco_detalle($docBancoE->id, $docBancoI->id, $request);
        });
    }

    private function insertar_detalle_docBanco_detalle($idDocBancoE, $idDocBancoI, $request)
    {
        //EGRESO
        $ctactebancoe = BankCurrentAccount::findOrFail($request->currentaccounte);
        $docBanco_detalleE = new BankDocumentDetail();
        $docBanco_detalleE->parent_id = $idDocBancoE;
        $docBanco_detalleE->tipooperacion_id = $request->operatione;
        $docBanco_detalleE->cuenta_id = $ctactebancoe->cuenta_id;
        $docBanco_detalleE->glosa = $request->commente;
        $docBanco_detalleE->importe = $request->amounte;
        $docBanco_detalleE->usuario = Auth::id();
        $docBanco_detalleE->cci = "";

        //INGRESO
        $ctactebancoi = BankCurrentAccount::findOrFail($request->currentaccounti);
        $docBanco_detalleI = new BankDocumentDetail;
        $docBanco_detalleI->parent_id = $idDocBancoI;
        $docBanco_detalleI->tipooperacion_id = $request->operatione;
        $docBanco_detalleI->cuenta_id = $ctactebancoi->cuenta_id;
        $docBanco_detalleI->glosa = $request->commenti;
        $docBanco_detalleI->importe = $request->amounti;
        $docBanco_detalleI->usuario = Auth::id();
        $docBanco_detalleI->cci = "";

        DB::transaction(function () use ($docBanco_detalleE, $docBanco_detalleI, $idDocBancoE, $idDocBancoI) {
            //EGRESO
            $docBanco_detalleE->save();
            //EJECUTAR PROCEDIMIENTO ALMACENADO cntdocbanco($idDocBancoE,@a)
            $this->provisionesporpagarprocedure->cntDocBanco($idDocBancoE, '@a');
            $this->provisionesporpagarprocedure->generaCtaCte($idDocBancoE, 'docbanco', '@a');

            //INGRESO
            $docBanco_detalleI->save();
            //EJECUTAR PROCEDIMIENTO ALMACENADO cntdocbanco($idDocBancoI,@a)
            $this->provisionesporpagarprocedure->cntDocBanco($idDocBancoI, '@a');
            $this->provisionesporpagarprocedure->generaCtaCte($idDocBancoI, 'docbanco', '@a');
        });
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['docbanco_egreso'] = BankDocument::where('transferencia_id', $id)->where('tipo', 'E')->first();
        $data['docbanco_ingreso'] = BankDocument::where('transferencia_id', $id)->where('tipo', 'I')->first();
        $data['docbanco_transito'] = TransitBankDocument::findOrFail($id);
        $data['docBancoDetalle_egreso'] = BankDocumentDetail::where('parent_id', $data['docbanco_egreso']->id)->first();
        $data['docBancoDetalle_ingreso'] = BankDocumentDetail::where('parent_id', $data['docbanco_ingreso']->id)->first();
        $data['operacionese'] = OperationType::where('tipo', 'E')->where('estransferencia', 1)->get();
        $data['operacionesi'] = OperationType::where('tipo', 'I')->where('estransferencia', 1)->get();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['bancos'] = Bank::all();
        $data['currencies'] = Currency::all();
        $data['ctactebankse'] = BankCurrentAccount::where('banco_id', $data['docbanco_egreso']->banco_id)->get();
        $data['ctactebanksi'] = BankCurrentAccount::where('banco_id', $data['docbanco_ingreso']->banco_id)->get();
        $data['mediopagos'] = PaymentType::all();
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Transferencias','');
        $data['header'] = headeroptions($this->var, 'edita');
        return view('transfers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $docBancoTransito = TransitBankDocument::findOrFail($id);
            $docBancoTransito->numero = $request->txt_numero;
            $docBancoTransito->fechaproceso = $request->processdate;
            $docBancoTransito->usuario = Auth::id();

            DB::transaction(function () use ($docBancoTransito, $request) {
                $docBancoTransito->save();
                $this->editar_detalle_docBanco($docBancoTransito->id, $request);
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    private function editar_detalle_docBanco($id_docBancoT, $request)
    {
        //EGRESO
        $docBancoE = BankDocument::where('transferencia_id', $id_docBancoT)->where('tipo', 'E')->first();
        $docBancoE->numero = $request->txt_numeroe;
        $docBancoE->fechaproceso = $request->processdatee;
        $docBancoE->unegocio_id = 1;
        $docBancoE->banco_id = $request->banke;
        $docBancoE->ctactebanco_id = $request->currentaccounte;
        $docBancoE->moneda_id = $request->currencye;
        $docBancoE->tcmoneda = $request->txt_tce;
        $docBancoE->tcambio = $request->changeratee;
        $docBancoE->esdiferido = validaCheck($request->deferredcheck);
        $docBancoE->fechacobro = $request->deferred;
        $docBancoE->glosa = $request->commente;
        $valor_checked = validaCheck($request->checkchequee);
        $docBancoE->concheque = $valor_checked;
        $docBancoE->nrocheque = $request->checke;
        $docBancoE->giradoa = $request->namee;
        $docBancoE->total = $request->amounte;

        if ($request->chashe == 1) {
            $ventana = 'MOV_CAJA';
        } else {
            $ventana = 'MOV_BANCO';
        }

        $docBancoE->ventana = $ventana;
        $docBancoE->mediopago_id = $request->paymentwaye;
        $docBancoE->transferencia_id = $id_docBancoT;
        $docBancoE->transaccion = $request->transferatione;
        $docBancoE->fechaconcilia = "";
        $docBancoE->fechaelimina = "";
        $docBancoE->fecharecep = "";
        $docBancoE->recibidopor = "";
        $docBancoE->usuario = Auth::id();

        //INGRESO
        $docBancoI = BankDocument::where('transferencia_id', $id_docBancoT)->where('tipo', 'I')->first();
        $docBancoI->numero = $request->txt_numeroi;
        $docBancoI->fechaproceso = $request->processdatei;
        $docBancoI->unegocio_id = 1;
        $docBancoI->banco_id = $request->banki;
        $docBancoI->ctactebanco_id = $request->currentaccounti;
        $docBancoI->moneda_id = $request->currencyi;
        $docBancoI->tcmoneda = $request->txt_tci;
        $docBancoI->tcambio = $request->changeratei;
        $docBancoI->esdiferido = 0;
        $docBancoI->fechacobro = "";
        $docBancoI->glosa = $request->commenti;
        $docBancoI->concheque = 0;
        $docBancoI->giradoa = "";
        $docBancoI->total = $request->amounti;
        if ($request->cashi == 1) {
            $ventana = 'MOV_CAJA';
        } else {
            $ventana = 'MOV_BANCO';
        }
        $docBancoI->ventana = $ventana;
        $docBancoI->mediopago_id = $request->paymentwaye;
        $docBancoI->transferencia_id = $id_docBancoT;
        $docBancoI->transaccion = $request->transferationi;
        $docBancoI->fechaconcilia = "";
        $docBancoI->fechaelimina = "";
        $docBancoI->fecharecep = "";
        $docBancoI->recibidopor = "";
        $docBancoI->usuario = Auth::id();

        DB::transaction(function () use ($docBancoE, $docBancoI, $request) {
            $docBancoE->save();
            $docBancoI->save();
            $this->editar_detalle_docBanco_detalle($docBancoE->id, $docBancoI->id, $request);
        });
    }

    private function editar_detalle_docBanco_detalle($idDocBancoE, $idDocBancoI, $request)
    {
        //EGRESO
        $data1 = array(
            'tipooperacion_id' => $request->operatione,
            'glosa' => $request->commente,
            'importe' => $request->amounte,
            'cci' => "",
            'usuario' => Auth::id(),
        );

        //INGRESO
        $data2 = array(
            'tipooperacion_id' => $request->operationi,
            'glosa' => $request->commenti,
            'importe' => $request->amounti,
            'cci' => "",
            'usuario' => Auth::id(),
        );

        DB::transaction(function () use ($idDocBancoE, $idDocBancoI, $data1, $data2) {
            //EGRESO
            BankDocumentDetail::editar_transferencia($idDocBancoE, $data1);

            //INGRESO
            BankDocumentDetail::editar_transferencia($idDocBancoI, $data2);
        });
    }

    public function bankcash(Request $request)
    {
        $bank = Bank::findOrFail($request->bank);
        return $bank;
    }

    public function activar(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                TransitBankDocument::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
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
}
