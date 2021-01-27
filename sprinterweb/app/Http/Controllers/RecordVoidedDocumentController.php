<?php

namespace App\Http\Controllers;

use App\Customers\Entities\Customer;
use App\Documents\Collections\OpeningReceivableCollection;
use App\Documents\Collections\RecordVoidedDocumentCollection;
use App\Documents\Entities\Commercial;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use sprinter\DocumentoPorPagar;
use sprinter\Tercero;
use Yajra\DataTables\Facades\DataTables;

class RecordVoidedDocumentController extends Controller
{
    private $var = 'recordVoidedDocument';
    private $recordVoidedDocumentCollection;
//    private $provisionesPorPagarProcedure;

    public function __construct(RecordVoidedDocumentCollection $recordVoidedDocumentCollection/*, ProvisionesPorPagarProcedure $provisionesPorPagarProcedure*/)
    {
        $this->obtener_cliente();
        $this->recordVoidedDocumentCollection = $recordVoidedDocumentCollection;
//        $this->provisionesPorPagarProcedure = $provisionesPorPagarProcedure;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Ventas', 'Transacción', 'Registros Documentos Anulados','');

        return view('recordVoidedDocuments.list', $data);
    }

    public function list()
    {
        $records = DocumentToPay::lista_aperturaRecordVoidedDocuments();
        $this->recordVoidedDocumentCollection->actions($records);

        return DataTables::of($records)->rawColumns(['actions', 'estado'])->make();
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
        $data['periods'] = Period::all();
        $data['date'] = date('Y-m-d');
        $data['customers'] = Customer::all();
        $data['documents'] = Commercial::opening();
        $data['view'] = link_view('Ventas', 'Transacción', 'Registros Documentos Anulados','');

        return view('recordVoidedDocuments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $document = new DocumentToPay();
            $document->periodo_id = $request->period;
//            $opening->numero = $request->number;
            $date = date("Y/m/d", strtotime($request->date));
            $document->fechaproceso = $date;
//            $opening->unegocio_id = 1;
            $document->horaproceso = date('h:i:s');
            $document->origen = 'Documento anulado';
            $document->tercero_id = $request->customer;
//            $opening->puntoventa_id = 1;
//            $opening->sucursal_id = 1;
//            $opening->almacen_id = 1;
//            $opening->tipotransaccion_id = 1;
//            $opening->tipocompra_id = $request->buy;
//            $opening->tipoventa_id = $request->sale;
//            $opening->moneda_id = $request->currency;
            /*            $opening->tcmoneda = '';*/
            $document->documento_id = $request->document;
            $document->seriedoc = $request->series;
            $document->numerodoc = $request->numberOfSeries;
            $document->fechadoc = $date;
            $document->tcambio = $request->changerate;
//            $opening->condicionpago_id = 1;
//            $document->recepcion = $request->dateInitial;
//            $document->vencimiento = $request->dateEnd;
//            $opening->vendedor_id = 1;
            $document->glosa = 'Documento anulado';
//            $opening->tiponotacredito_id = 1;
//            $opening->tiponotadebito_id = 1;
//            $opening->tipoafectaigv_id = 1;
//            $opening->impuesto_id = $request->tax;
//            $opening->impuesto2_id = 1;
//            $opening->impuesto3_id = 1;
//            $opening->impuesto4_id = 1;
//            $opening->impuesto5_id = 1;
//            $opening->tipobienservicio_id = 1;
//            $opening->tipoadq = 1;
//            $opening->base = 1;
//            $opening->inafecto = 1;
//            $opening->gratuito = 1;
//            $opening->descuento = 1;
//            if ($request->tax == 1) {
//                $opening->impuesto = $request->igv;
//            } else {
//                $opening->impuesto = 0.00;
//            }
//            $opening->impuesto2 = 1;
//            $opening->impuesto3 = 1;
//            $opening->impuesto4 = 1;
//            $opening->impuesto5 = 1;
//            $opening->isc = 1;
//            $opening->total = $request->total;
//            $opening->ventana = 1;
            $document->estado = 1;
//            $opening->esapertura = 1;
//            $opening->conpercepcion = 1;
//            $opening->refgremision = 1;
//            $opening->oscliente = 1;
//            $opening->noalmacen = 1;
//            $opening->periodoemi = 1;
            $customer = Customer::findOrFail($request->customer);
            $document->razonsocial = $customer->descripcion;
            $document->ruc = $customer->codigo;
//            $document->direccion = $request->address;
//            $opening->centraliza = 1;
//            $opening->referencial = 1;
//            $opening->ingresoalmacen_id = 1;
//            $opening->liquidacion_id = 1;
//            $opening->detraccion_id = 1;
//            $opening->ordencompra_id = 1;
//            $opening->cotizacion_id = 1;
//            $opening->ordentrabajo_id = 1;
//            $opening->pedidoalmacen_id = 1;
//            $opening->guiatransporte_id = 1;
//            $opening->docplanilla_id = 1;
            $document->usuario = Auth::id();
            $document->observaciones = 'Documento anulado';

            DB::transaction(function () use ($document) {
                $document->save();
//                $this->inserta_movctacte($opening);
//                $this->executeGenerateCte($opening->id, 'docxpagar', '@a');
            });


            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = 'crea';

        return $r;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activeCancel(Request $request, $id)
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
