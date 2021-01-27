<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Warehouses\Entities\WarehouseIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class InventorySettingsWarehouseController extends Controller
{
    private $var = 'movementwarehouse';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Contabilidad', 'Procesos', 'Ajustes Diferencia Inventario','');

        return view('inventorysettingswarehouse.list', $data);
    }


    public function list(){
        $ventana ='MOV_INGRESOALMACEN_AJUSTE';
        $data = WarehouseIncome::listExit($ventana, Session::get('period_id'));
        return Datatables::of($data)->rawColumns(['estado'])->make();
    }
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
