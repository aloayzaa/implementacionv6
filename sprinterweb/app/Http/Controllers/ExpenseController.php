<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Expenses\Entities\Expense;
use App\GoodsServicesType\Entities\GoodsServicesType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    protected $var = 'expenses';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['var'] = $this->var;
        $data['view'] = link_view('Compras', 'Configuración', 'Tipo de Gastos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('expenses.list', $data);
    }

    public function list()
    {
        $expenses = Expense::all();
        return DataTables::of($expenses)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['typeServices'] = GoodsServicesType::all();
        $data['accountingPlan'] = AccountingPlan::whereRaw('LENGTH(codigo) > 5')->get();
        $data['view'] = link_view('Compras', 'Configuración', 'Tipo de Gastos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('expenses.create', $data);
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
            $expense = new Expense();
            $expense->codigo = $request->code;
            $expense->descripcion = $request->name;
            $expense->codsunat = $request->codSunat;
            $expense->estado = 1;
            $expense->tipobienservicio_id = $request->type;
            $expense->cuenta_id = $request->account;

            DB::transaction(function () use ($expense) {
                $expense->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

//        $r["proceso"] = $request->proceso;

        return $r;

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
        $data['expense'] = Expense::findOrFail($id);
        $data['var'] = $this->var;
        $data['typeServices'] = DB::select('select * from tipobienservicio');
        $data['accountingPlan'] = AccountingPlan::whereRaw('LENGTH(codigo) > 5')->get();
        $data['view'] = link_view('Compras', 'Configuración', 'Tipo de Gastos', '');

        return view('expenses.edit', $data);
//        $data['typeServices'] = DB::select('select * from tipobienservicio');
//                                  select('tipobienservicio.*')
//                                ->join('tipobienservicio', 'tipobienservicio.id', 'tipogasto.tipobienservicio_id')
//                                ->get();
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
            $expense = Expense::findOrFail($id);
            $expense->codigo = $request->code;
            $expense->descripcion = $request->name;
            $expense->codsunat = $request->codSunat;
            $expense->estado = 1;
            $expense->tipobienservicio_id = $request->type;
            $expense->cuenta_id = $request->account;

            DB::transaction(function () use ($expense) {
                $expense->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

//        $r["proceso"] = $request->proceso;

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
