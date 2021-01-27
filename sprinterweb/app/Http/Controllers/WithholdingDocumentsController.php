<?php

namespace App\Http\Controllers;

use App\AffectsIgv\Entities\AffectIgv;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\DocumentToPay\Collections\WithholdingDocumentsCollection;
use App\DocumentToPay\Entities\DocumentToPay;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\PurchaseDocument\Entities\PurchaseDocument;
use App\SalesTypes\Entities\SaleType;
use App\Taxes\Entities\Taxes;
use App\TransactionType\Entities\TransactionType;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class WithholdingDocumentsController extends Controller
{
    protected $var = 'withholdingdocuments';
    private $ventana = 'MOV_RETENCION';
    private $instancia = 'withholding_detail';
    private $provisionstopayprocedure;
    private $withholdingdocumentscollection;

    public function __construct(ProvisionesPorPagarProcedure $provisionstopayprocedure, WithholdingDocumentsCollection $withholdingdocumentscollection)
    {
        $this->obtener_cliente();
        $this->provisionstopayprocedure = $provisionstopayprocedure;
        $this->withholdingdocumentscollection = $withholdingdocumentscollection;
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
        $data['view'] = link_view('Gestión Tributaria', 'Transacción', 'Comprobantes de Retención', '');
        $data['header'] = headeroptions($this->var, 'list','','');

        return view('withholdingdocuments.list', $data);
    }

    public function list()
    {
        $documents = DocumentToPay::lista_retencion();
        return DataTables::of($documents)->rawColumns(['actions', 'estado'])->make();
    }

    public function listreference(Request $request)
    {
        $customer = $request->customer;
        $cbo_tipo = $request->type . 'docxpagar';
        $fecha_hasta = $request->finishdate;
        $resultados = $this->provisionstopayprocedure->getCtaCte($customer, $cbo_tipo, $fecha_hasta, 0, 0);
        $this->withholdingdocumentscollection->reference($resultados);

        return DataTables::of($resultados)->rawColumns(['elegido', 'aplicar'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Cart::instance($this->instancia)->destroy();
        $data['proceso'] = 'crea';
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['route'] = route('withholdingdocuments');
        $data['documentosCompra'] = PurchaseDocument::all();//PurchaseDocument::crsdocumentocom($this->ventana); ARREGLAR
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        $data['header'] = headeroptions($this->var, 'crea','','');

        //INPUTS POR DEFECTO
        $data['monedas'] = Currency::all();
        $data['pto_venta'] = PointSale::findOrFail(1);
        $data['tipo_venta'] = SaleType::findOrFail(1);
        $data['condicion_pagos'] = PaymentCondition::all();
        $data['tipoafectaigv'] = AffectIgv::findOrFail(1);
        $data['tipotransacciones'] = TransactionType::all();
        $data['terceros'] = Customer::all();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Gestión Tributaria', 'Transacción', 'Comprobantes de Retención', '');

        return view('withholdingdocuments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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

    public function listar_detalle(Request $request)
    {
        $data['proceso'] = $request->proceso;
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['carts'] = Cart::instance($data['instancia'])->content();
        $data['total'] = Cart::instance($data['instancia'])->count();

        return view('withholdingdocuments.' . $data['instancia'] . '', $data);
    }

    public function rucproveedor(Request $request)
    {
        $tercero = Customer::findOrFail($request->id);

        return $tercero;
    }

    public function ultimo_numero_serie(Request $request)
    {
        $seriedoc = $request->series;
        $documento_id = $request->documenttype;
        $origen = $request->type;
        $numero = DocumentToPay::ultimo_numero_serie($seriedoc, $documento_id, $origen);

        if ($numero == null) {
            return 1;
        } else {
            return $numero;
        }
    }

    public function consulta_documento(Request $request)
    {
        $tipodoc = PurchaseDocument::findOrFail($request->documenttype);

        return $tipodoc;
    }

    public function carro_retencion(Request $request)
    {
        $datos = json_decode($request->datatable);

        foreach ($datos as $data) {
            $txtaplica = 'txt_aplica' . $data->id;
            $aplicar = $request->$txtaplica;

            Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $data->id ? $data->id : '',
                    'aplicar' => $aplicar ? $aplicar : '',
                    'documento' => $data->documento ? $data->documento : '',
                    'fecha' => $data->fechaproceso ? $data->fechaproceso : '',
                    'vencimiento' => $data->vencimiento ? $data->vencimiento : '',
                    'moneda' => $data->moneda ? $data->moneda : '',
                    'saldomn' => $data->saldomn ? $data->saldomn : '',
                    'saldome' => $data->saldome ? $data->saldome : '',
                    'glosa' => $data->glosa ? $data->glosa : '',
                    'tipodoc' => $data->tipodoc ? $data->tipodoc : '',
                    'seriedoc' => $data->seriedoc ? $data->seriedoc : '',
                    'numerodoc' => $data->numerodoc ? $data->numerodoc : '',
                    'cuenta_id' => $data->cuenta_id ? $data->cuenta_id : '',
                    'tcambio' => $data->tcambio ? $data->tcambio : '',
                    'tipomoneda' => $data->tipomoneda ? $data->tipomoneda : '',
                    'ventana' => $data->ventana ? $data->ventana : '',
                    'origen' => $data->origen ? $data->origen : '',
                    'factor' => $data->factor ? $data->factor : '',
                    'estado' => $data->estado ? $data->estado : '',
                ]
            ]);
        }

        return $this->instancia;
    }


    /*public function preinserta_carro_retencion()
    {
        $instancia = $this->instancia;
        $carts = Cart::instance($instancia)->content();

        foreach ($carts as $carro) {
            if ($carro->options->chkselecciona == 1) {

                Cart::instance($instancia . '2')->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                    'options' => [
                        'parent_id' => $carro->options->has('parent_id') ? $carro->options->parent_id : '',
                        'item' => $carro->options->has('item') ? $carro->options->item : '',
                        'chkselecciona' => $carro->options->has('chkselecciona') ? $carro->options->chkselecciona : '',
                        'aplicar' => $carro->options->has('aplicar') ? $carro->options->aplicar : '',
                        'documento' => $carro->options->has('documento') ? $carro->options->documento : '',
                        'fecha' => $carro->options->has('fecha') ? $carro->options->fecha : '',
                        'vencimiento' => $carro->options->has('vencimiento') ? $carro->options->vencimiento : '',
                        'moneda' => $carro->options->has('moneda') ? $carro->options->moneda : '',
                        'saldomn' => $carro->options->has('saldomn') ? $carro->options->saldomn : '',
                        'saldome' => $carro->options->has('saldome') ? $carro->options->saldome : '',
                        'glosa' => $carro->options->has('glosa') ? $carro->options->glosa : '',
                        'sp_id' => $carro->options->has('sp_id') ? $carro->options->sp_id : '',
                        'tipodoc' => $carro->options->has('tipodoc') ? $carro->options->tipodoc : '',
                        'seriedoc' => $carro->options->has('seriedoc') ? $carro->options->seriedoc : '',
                        'numerodoc' => $carro->options->has('numerodoc') ? $carro->options->numerodoc : '',
                        'cuenta_id' => $carro->options->has('cuenta_id') ? $carro->options->cuenta_id : '',
                        'tcambio' => $carro->options->has('tcambio') ? $carro->options->tcambio : '',
                        'tipomoneda' => $carro->options->has('tipomoneda') ? $carro->options->tipomoneda : '',
                        'ventana' => $carro->options->has('ventana') ? $carro->options->ventana : '',
                        'origen' => $carro->options->has('origen') ? $carro->options->origen : '',
                        'factor' => $carro->options->has('factor') ? $carro->options->factor : '',
                        'estado' => $carro->options->has('estado') ? $carro->options->estado : '',
                    ]
                ]);
            }
        }

        $r['mensaje'] = 'ok';

        return $r;
    }*/

    /*public function edita_detalle_retencion(Request $request)
    {

        dd($request->all());
        $id_carrito = $request->rowid;
        $chk = $request->chk;
        $saldomn = $request->saldomn;

        Cart::instance($this->instancia)->update($id_carrito, [
            'options' => [
                'parent_id' => '',
                'item' => '',
                'chkselecciona' => $chk,
                'aplicar' => $saldomn
            ]
        ]);

        $r["estado"] = "ok";
        $r["instancia"] = $this->instancia;

        return $r;
    }*/
}
