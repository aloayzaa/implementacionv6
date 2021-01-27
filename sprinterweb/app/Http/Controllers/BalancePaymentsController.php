<?php

namespace App\Http\Controllers;

use App\CategoriesCtaCte\Entities\CategoriesCtaCte;
use App\CategoriesCtaCte\Entities\CategoriesCtaCteDetail;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use App\Subdiaries\Entities\Subdiaries;
use App\Subsidiaries\Entities\Subsidiaries;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class BalancePaymentsController extends Controller
{
    use UserPrivileges;
    private $var = 'balancepayments';
    protected $privilegios = '04.04.02';
    protected $user;

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['var'] = $this->var;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $fecha = new Carbon(date('Y-m-d'));
        $data['categctactec'] = CategoriesCtaCte::select('id', 'descripcion')->where('tipo', 'P')->get();
        $data['sucursal'] = Subsidiaries::select('id', 'descripcion')->where('estado', 1)->get();
        $data['view'] = link_view('Compras', 'Reportes', 'Saldos por Pagar', '');
        $data['fecha'] = $fecha->format('Y-m-d');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('balancepayments.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->date == null) {
            $periodo = Period::findOrFail(Session::get('period_id'));
            $fecha_fin = $periodo->final;
        } else {
            $fecha_fin = $request->date;
        }

        if (isset($request->date)){
            $resultado = ProcedureReports::rptSaldosPorPagar(0, 'PS', $fecha_fin);
        }else{
            $resultado = collect(null);
        }

/*        $resultado = collect($resultado);*/

        return Datatables::of($resultado)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    public function consultar_detalle(Request $request){
        $documentocom = CategoriesCtaCteDetail::data($request->id);
        return $documentocom;
    }
}
