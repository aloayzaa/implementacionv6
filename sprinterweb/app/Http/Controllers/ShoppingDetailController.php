<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ShoppingDetailController extends Controller
{
    use UserPrivileges;
    private $var = 'shoppingdetail';
    protected $privilegios = '04.04.05';
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
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['var'] = $this->var;
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['view'] = link_view('Compras', 'Reportes', 'Detalles de Compras','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('shoppingdetail.list', $data);
    }

    public function rptcompras(Request $request){
        try {
            $pDesde = $request->lcDesde;
            $pHasta = $request->lcHasta;
            $pPeriodo = 0;
            $pTipo = $request->lctipo;
            $resultado = '';
            switch ($pTipo){
                case 'D':
                case 'M': $resultado = ProcedureReports::rptDetalleCompra($pDesde, $pHasta, $pTipo); break;
                case 'R': $resultado = ProcedureReports::rptResumenCompra($pDesde, $pHasta, $pPeriodo, $pTipo); break;
                case 'L': $resultado = ProcedureReports::rptComprasRegistro($pDesde, $pHasta); break;
                case '4': $resultado = ProcedureReports::rptComprasRetencion($pDesde, $pHasta, $pTipo); break;
            }
            $r = $resultado;
        }catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    /*public function list(Request $request)
    {
        $fecha_inicio = $request->initialdate;
        $fecha_fin = $request->finishdate;

        if (isset($request->initialdate)){
            $resultado = ProcedureReports::rptDetalleCompra($fecha_inicio, $fecha_fin, '');
        }else{
            $resultado = null;
        }

        $resultados = collect($resultado);

        return Datatables::of($resultados)->make();
    }*/

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
