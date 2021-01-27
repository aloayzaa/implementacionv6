<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MovementWarehouseController extends Controller
{
    private $var = 'movementwarehouse';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function index(Request $request) //opcional
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Contabilidad', 'Procesos', 'Valorizar Movimientos de Almacén','');

        return view('warehouseMovement.list', $data);
    }


    public function value(Request $request){

        $pRet='@a';
        $pdo = DB::connection()->getPdo();
        $pdo->exec('CALL valorizakardex("'.$request->fecha_inicio.'","'.$request->fecha_fin.'","'.$pRet.'")');
        $res = $pdo->query('select @pRet as mensaje')->fetch();
        return $res;

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
