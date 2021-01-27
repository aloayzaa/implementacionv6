<?php

namespace App\Http\Controllers;

use App\Customers\Entities\Customer;
use App\Http\Requests\ReportsRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SalesCurrentAccountsController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'currentaccounts';
    protected  $privilegios = '03.04.01';
    protected  $user;
    public function __construct()
    {
        $this->obtener_cliente();
        $this->user='ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['var'] = $this->var;
        $data['process'] = 'venta';
        $data['customers'] = Customer::all();
        $data['period'] = Period::findorfail(Session::get('period_id'));
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Reportes', 'Cuenta Corriente Cliente', '');

        return view('currentaccounts.listSales', $data);
    }

    public function list(ReportsRequest $request)
    {
        $periodo = Period::findorfail(Session::get('period_id'));

        $id_proveedor = ($request->provider == '') ? 0 : $request->provider;
        $id_tipo_reporte = $request->balances;
        $fecha = ($request->date == null) ? $periodo->inicio : $request->date;

        if ($request->provider != null) {
            //$resultadop = ProcedureReports::rptCtaCtexPagar($id_proveedor, $id_tipo_reporte, $fecha, $chk_pendiente);

                $resultadop = ProcedureReports::rptCtaCtexCobrar($id_proveedor, $id_tipo_reporte, $fecha);
                $resultados = $resultadop;

            /*if ($request->process == 'proveedor' || $request->process == null) {
                $resultadop = ProcedureReports::rptCtaCtexPagar($id_proveedor, $id_tipo_reporte, $fecha, $chk_pendiente);
                $resultados = collect($resultadop);
            } else {
                $resultadoc = ProcedureReports::rptCtaCtexCobrar($id_proveedor, $id_tipo_reporte, $fecha, $chk_pendiente);
                $resultados = collect($resultadoc);
            }*/
        } else {
            $resultados = collect(null);
        }

        return Datatables::of($resultados)->make();


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
