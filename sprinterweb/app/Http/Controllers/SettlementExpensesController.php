<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Period\Entities\Period;
use App\PurchaseDocument\Entities\PurchaseDocument;
use App\PurchasesType\Entities\PurchasesType;
use App\Subsidiaries\Entities\Subsidiaries;
use Illuminate\Http\Request;
use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;


class SettlementExpensesController extends Controller
{
    private $var = 'settlementexpenses';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function index(Request $request)
    {

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Compras', 'Transacción', 'Liquidación de Gastos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('settlementexpenses.list', $data);
    }

    public function list()
    {
        $periodo = Session::get('period_id');
        $resultado = DocumentToPay::lista_liquidaciongasto($periodo);

        return DataTables::of($resultado)->rawColumns(['actions', 'estado'])->make();
    }

    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['hoy'] = date('Y-m-d');
//        $data['instancia']= $this->instancia;
        $data['route'] = route('settlementexpenses');
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['tipocompras'] = PurchasesType::all();
        $data['terceros'] = Customer::all();
        $data['monedas'] = Currency::all();
        $data['documentoscompra'] = PurchaseDocument::all();
        $data['sucursales'] = Subsidiaries::all();
        $data['view'] = link_view('Compras', 'Transacción', 'Liquidación de Gastos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');


        return view('settlementexpenses.create', $data);
    }

    public function detalle_liquidacion()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['hoy'] = date('Y-m-d');
//        $data['instancia']= $this->instancia;
        $data['route'] = route('settlementexpenses');
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['tipocompras'] = PurchasesType::all();
        $data['terceros'] = Customer::all();
        $data['monedas'] = Currency::all();
        $data['documentoscompra'] = PurchaseDocument::all();
        $data['sucursales'] = Subsidiaries::all();
        $data['view'] = link_view('Compras', 'Transacción', 'Liquidación de Gastos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');


        return view('settlementexpenses.create', $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function agregar_liquidacion_detalle(){


    }
    public function rucproveedor(Request $request)
    {
        $tercero = Customer::findOrFail($request->id);
        return $tercero;
    }

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
}
