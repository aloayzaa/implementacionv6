<?php

namespace App\Http\Controllers;

use App\CategoriesCtaCte\Entities\CategoriesCtaCteDetail;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class BalancesReceivableController extends Controller
{
    use UserPrivileges;
    private $var = 'balancesreceivable';
    protected  $privilegios = '03.04.01';
    protected  $user;
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
        $this->user='ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['var'] = $this->var;
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Reportes', 'Saldos por Cobrar', '');

        return view('balancesreceivable.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->finishdate == null) {
            $period = Period::findOrFail(Session::get('period_id'));
            $fecha_fin = $period->final;
        } else {
            $fecha_fin = $request->finishdate;
        }
        if (isset($request->date)){
            //'CS'
            $resultado = $this->procedurereports->rptSaldosPorCobrar(0, 'CS', $fecha_fin);
        }else{
            $resultado = collect(null);
        }

        return Datatables::of($resultado)->make();
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
