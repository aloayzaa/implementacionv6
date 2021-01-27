<?php

namespace App\Http\Controllers;

use App\Costs\Entities\Costs;
use App\Customers\Entities\Customer;
use App\Families\Entities\Familia;
use App\Marks\Entities\Marca;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\Procedures\ProcedureReports;
use App\Products\Entities\Productos;
use App\SalesTypes\Entities\SaleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SalesDetailsController extends Controller
{
    private $var = 'salesdetails';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
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
        $data['view'] = link_view('Ventas', 'Reportes', 'Detalle de Ventas','');

        return view('salesdetails.list', $data);
    }

    public function list(Request $request)
    {
        if(!$request->initialdate){
            $period = Period::where('descripcion', Session::get('period'))->first();
            $fecha_inicial = $period->inicio;
            $fecha_final = $period->final;
        } else{
            $fecha_inicial = $request->initialdate;
            $fecha_final = $request->finishdate;
        }

        if (!$request->type) {
            $tipo = "C";
        } else {
            $tipo = $request->type;
        }

        $id_filtro = $request->id_filtro;
        if (!$id_filtro) {
            $id_filtro = 0;
        } else {
            $id_filtro = $request->id_filtro;
        }

        $rb_tipo = $request->rdtype;

        $filtro = $tipo . $id_filtro;

        $resultado = $this->procedurereports->rptDetalleVentas($fecha_inicial, $fecha_final, $rb_tipo, 1, $filtro);

        $resultado = collect($resultado);

        return Datatables::of($resultado)->make();
    }

    public function filtro(Request $request)
    {
        $data = null;
        $tipo = $request->type;

        if ($tipo == 'C') {
            $data = Customer::all();
        } elseif ($tipo == 'B') {
            $data = Productos::all();
        } elseif ($tipo == 'M') {
            $data = Marca::all();
        } elseif ($tipo == 'F') {
            $data = Familia::all();
        } elseif ($tipo == 'P') {
            $data = PointSale::all();
        } elseif ($tipo == 'T') {
            $data = SaleType::all();
        } elseif ($tipo == 'O') {
            $data = Costs::all();
        }

        return $data;
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
