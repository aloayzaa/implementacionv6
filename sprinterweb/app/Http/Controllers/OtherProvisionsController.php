<?php

namespace App\Http\Controllers;

use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Currency\Entities\Currency;
use App\CurrentAccountMovement\Entities\CurrentAccountMovement;
use App\Customers\Entities\Customer;
use App\Documents\Entities\BankDocument;
use App\Documents\Entities\BankDocumentDetail;
use App\DocumentToPay\Collections\OtherProvisionsCollection;
use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayPaymentWay;
use App\PaymentCondition\Entities\PaymentCondition;
use App\PaymentMethod\Entities\PaymentMethod;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\PurchaseDocument\Entities\PurchaseDocument;
use App\Subsidiaries\Entities\Subsidiaries;
use App\TransactionType\Entities\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class OtherProvisionsController extends Controller
{
    protected $var = 'otherprovisions';
    private $ventana = 'MOV_GENERAXPAGAR'; //Nombre ventana
    private $modulo_para_periodo = 'modtes';
    private $provisionesPorPagarProcedure;
    private $otherprovisionscollection;

    public function __construct(ProvisionesPorPagarProcedure $provisionesPorPagarProcedure, OtherProvisionsCollection $otherprovisionscollection)
    {
        $this->obtener_cliente();
        $this->provisionesPorPagarProcedure = $provisionesPorPagarProcedure;
        $this->otherprovisionscollection = $otherprovisionscollection;
    }

    private function ejecuta_generactacte($parent, $ventan, $arroba)
    {
        $this->provisionesPorPagarProcedure->generaCtacte($parent, $ventan, $arroba);
    }

    private function ejecuta_cntdocbanco($parent, $arroba)
    {
        $this->provisionesPorPagarProcedure->cntDocBanco($parent, $arroba);
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
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Generación de Documentos por Pagar', '');
        $data['header'] = headeroptions($this->var, 'list');
        return view('otherprovisions.list', $data);
    }

    public function list()
    {
        $documentos = DocumentToPay::listado_otras_provisiones();
        $this->otherprovisionscollection->actions($documentos);

        return DataTables::of($documentos)->rawColumns(['actions', 'estado'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['documentosCompra'] = PurchaseDocument::crsdocumentocom2($this->ventana);
        $data['sucursal'] = Subsidiaries::findOrFail(1);
        $data['transaccion'] = TransactionType::findOrFail(1);
        $data['currency'] = Currency::findOrFail(1);
        $data['condicion'] = PaymentCondition::findOrFail(1);
        $data['paymentmethods'] = PaymentMethod::all();
        $data['mediopagos'] = PaymentType::all();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['terceros'] = Customer::all();
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Generación de Documentos por Pagar', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('otherprovisions.create', $data);
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
            $otra_provision = new DocumentToPay();
            $otra_provision->periodo_id = Session::get('period_id');
            $otra_provision->unegocio_id = 1;
            $otra_provision->fechaproceso = $request->processdate;
            $otra_provision->sucursal_id = $request->subsidiary;
            $otra_provision->origen = $request->type;
            $otra_provision->tercero_id = $request->customer;
            $otra_provision->tipotransaccion_id = $request->transactiontype;
            $otra_provision->moneda_id = $request->currency;
            $moneda = Currency::findOrfail($request->currency);
            if ($moneda->tipo == 'O') {
                $otra_provision->tcmoneda = $request->txt_tc;
            } else {
                $otra_provision->tcmoneda = 0;
            }
            $otra_provision->documento_id = $request->documenttype;
            $otra_provision->seriedoc = $request->series;
            $otra_provision->numerodoc = $request->number;
            $otra_provision->fechadoc = $request->date;
            $otra_provision->tcambio = $request->changerate;
            $otra_provision->condicionpago_id = $request->paymentcondition;
            $otra_provision->vencimiento = $request->expdatea;
            $otra_provision->glosa = $request->comment;
            $otra_provision->total = $request->total;
            $otra_provision->ventana = $this->ventana;
            $tercero = Customer::findOrfail($request->customer);
            $otra_provision->razonsocial = $tercero->descripcion;
            $otra_provision->ruc = $tercero->codigo;
            $otra_provision->direccion = $request->address;
            $otra_provision->usuario = Auth::id();
            $otra_provision->estado = 1;

            DB::transaction(function () use ($otra_provision, $request) {
                $otra_provision->save();
                if ($request->paymentway != null) {
                    $this->insertar_docbanco($otra_provision->id, $request);
                }
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

    private function insertar_docbanco($parent_id, $request)
    {
        $docbanco = new BankDocument();
        $docbanco->periodo_id = Session::get('period_id');
        $docbanco->fechaproceso = $request->processdate;
        $docbanco->unegocio_id = 1;
        if ($request->type == 'C') {
            $docbanco->tipo = 'I';
            $docbanco->glosa = 'COBRO ' . $request->comment;
        } else {
            $docbanco->tipo = 'E';
            $docbanco->glosa = 'PAGO ' . $request->comment;
        }
        $docbanco->sucursal_id = 1;
        $docbanco->banco_id = $request->bank;
        $docbanco->ctactebanco_id = $request->bankcurrentaccount;
        $docbanco->moneda_id = $request->currencypd;
        if ($request->txt_tc == null) {
            $tcmoneda = 0;
        } else {
            $tcmoneda = $request->txt_tc;
        }
        $docbanco->tcmoneda = $tcmoneda;
        $docbanco->tcambio = $request->changerate;
        $docbanco->concheque = 0;
        $docbanco->nrocheque = $request->check;
        $docbanco->total = $request->amount;
        $docbanco->ventana = 'mov_caja';
        $docbanco->estado = 'ACTIVO';
        $docbanco->mediopago_id = $request->paymentwaypd;
        $docbanco->esautomatico = 0;
        $docbanco->usuario = Auth::id();

        DB::transaction(function () use ($docbanco, $parent_id, $request) {
            $docbanco->save();
            $this->inserta_docbanco_detalle($parent_id, $docbanco->id, $request);
            $this->insertar_docxpagar_formapago($parent_id, $docbanco->id, $request);
        });
    }

    private function inserta_docbanco_detalle($docxpagar_id, $docbanco_id, $request)
    {
        $docbancoDetalle = new BankDocumentDetail();
        $docbancoDetalle->parent_id = $docbanco_id;
        $docbancoDetalle->item = 1;
        $docbancoDetalle->tipooperacion_id = 10;
        $docbancoDetalle->tercero_id = $request->customer;
        $docbancoDetalle->referencia_id = $docxpagar_id;
        $docbancoDetalle->esnuevo = 0;

        if ($request->type == 'C') {
            $docbancoDetalle->glosa = 'COBRO ' . $request->comment;
        } else {
            $docbancoDetalle->glosa = 'PAGO ' . $request->comment;
        }
        $docbancoDetalle->importe = $request->amount;
        $docbancoDetalle->usuario = Auth::id();

        DB::transaction(function () use ($docbancoDetalle, $docbanco_id) {
            $docbancoDetalle->save();
            $this->ejecuta_generactacte($docbanco_id, 'docbanco', '@a');
            $this->ejecuta_cntdocbanco($docbanco_id, '@a');
        });
    }

    private function insertar_docxpagar_formapago($parent_id, $docbanco_id, $request)
    {
        $docxpagarformapago = new DocumentToPayPaymentWay();
        $docxpagarformapago->parent_id = $parent_id;
        $docxpagarformapago->item = 1;
        $docxpagarformapago->formapago_id = $request->paymentway;
        $docxpagarformapago->ctactebanco_id = $request->bankcurrentaccount;
        $docxpagarformapago->importe = $request->amount;
        $docxpagarformapago->nrocheque = $request->check;
        $docxpagarformapago->mediopago_id = $request->paymentwaypd;
        $docxpagarformapago->transaccion = $request->transaction;
        $docxpagarformapago->docbanco_id = $docbanco_id;
        $docxpagarformapago->usuario = Auth::id();

        DB::transaction(function () use ($docxpagarformapago) {
            $docxpagarformapago->save();
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
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['sucursal'] = Subsidiaries::findOrFail(1);
        $data['transaccion'] = TransactionType::findOrFail(1);
        $data['terceros'] = Customer::all();
        $data['condicion'] = PaymentCondition::findOrFail(1);
        $data['currency'] = Currency::findOrFail(1);
        $data['paymentmethods'] = PaymentMethod::all();
        $data['mediopagos'] = PaymentType::all();
        $data['documentosCompra'] = PurchaseDocument::crsdocumentocom($this->ventana);
        $data['otra_provision'] = DocumentToPay::findOrFail($id);
        $docbanco_id = 0;
        $data['docxpagarFormaPago'] = DocumentToPayPaymentWay::where('parent_id', $id)->first();
        if ($data['docxpagarFormaPago']) {
            $docbanco_id = $data['docxpagarFormaPago']->docbanco_id;
        }
        $data['docbanco'] = BankDocument::where('id', $docbanco_id)->first();
        $data['movctactes'] = CurrentAccountMovement::MuestraHistorial($id);
        $data['ctacte'] = BankCurrentAccount::findOrFail($data['docxpagarFormaPago']->docBanco['ctactebanco_id']);
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Generación de Documentos por Pagar', '');
        $data['header'] = headeroptions($this->var, 'edita');

        return view('otherprovisions.edit', $data);
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
            $otra_provision = DocumentToPay::findOrfail($id);
            $otra_provision->periodo_id = Session::get('period_id');
            $otra_provision->unegocio_id = 1;
            $otra_provision->fechaproceso = $request->processdate;
            $otra_provision->sucursal_id = $request->subsidiary;
            $otra_provision->origen = $request->type;
            $otra_provision->tercero_id = $request->customer;
            $otra_provision->tipotransaccion_id = $request->transactiontype;
            $id_moneda = $request->currency;
            $otra_provision->moneda_id = $id_moneda;
            $moneda = Currency::findOrfail($id_moneda);
            if ($moneda->tipo == 'O') {
                $otra_provision->tcmoneda = $request->txt_tc;
            } else {
                $otra_provision->tcmoneda = 0;
            }
            $otra_provision->documento_id = $request->documenttype;
            $otra_provision->seriedoc = $request->series;
            $otra_provision->numerodoc = $request->number;
            $otra_provision->fechadoc = $request->date;
            $otra_provision->tcambio = $request->changerate;
            $otra_provision->condicionpago_id = $request->paymentcondition;
            $otra_provision->vencimiento = $request->expdatea;
            $otra_provision->glosa = $request->comment;
            $otra_provision->total = $request->total;
            $otra_provision->ventana = $this->ventana;
            $tercero = Customer::findOrfail($request->customer);
            $otra_provision->razonsocial = $tercero->descripcion;
            $otra_provision->ruc = $tercero->codigo;
            $otra_provision->direccion = $request->address;
            $otra_provision->usuario = Auth::id();

            DB::transaction(function () use ($otra_provision, $request) {
                $otra_provision->save();
                if ($request->paymentway != null) {
                    $this->insertar_docbanco($otra_provision->id, $request);
                }
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

    public function tercero(Request $request)
    {
        $tercero = Customer::findOrFail($request->customer);

        return $tercero;
    }

    public function ultimodocumento(Request $request)
    {
        $seriedoc = $request->series;
        $documento_id = $request->documenttype;
        $origen = $request->type;
        $numero = DocumentToPay::ultimo_numero_serie($seriedoc, $documento_id, $origen);
        return $numero;
    }

    public function verifica_documento_registrado(Request $request)
    {
        $tercero_id = $request->customer;
        $seriedoc = $request->series;
        $documento_id = $request->documenttype;
        $origen = $request->type;
        $numero = $request->number;
        $numero = DocumentToPay::verifica_documento_registro($tercero_id, $seriedoc, $documento_id, $origen, $numero);

        if ($numero == null) {
            return 1;
        } else {
            return $numero->numerodoc;
        }
    }

    public function bank(Request $request)
    {
        $paymentmethod = PaymentMethod::findOrFail($request->paymentway);
        $ctacte = BankCurrentAccount::findOrFail($paymentmethod->id);
        return $ctacte;
    }

    public function currency(Request $request)
    {
        $paymentmethod = PaymentMethod::findOrFail($request->paymentway);
        $ctacte = BankCurrentAccount::findOrFail($paymentmethod->id);
        $currency = Currency::findOrFail($ctacte->moneda_id);
        return $currency;
    }

    public function activar(Request $request, $id)
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
