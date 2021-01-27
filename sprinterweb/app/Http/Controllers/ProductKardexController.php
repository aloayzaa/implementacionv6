<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ContabilidadProcedure;
use App\Products\Entities\Productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use sprinter\Periodo;
use Yajra\DataTables\Facades\DataTables;

class ProductKardexController extends Controller
{
    private $var = 'productkardex';
    private $rptKardex;

    public function __construct(ContabilidadProcedure $rptKardex)
    {
        $this->obtener_cliente();
        $this->rptKardex = $rptKardex;
    }

    public function index(Request $request){

        $productos = Productos::all();

        $periodo=Period::findOrFail(Session::get('period_id')); //esta mal debe ser del modelo


        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = $periodo;
        $data['productos'] = $productos;

        $data['view'] = link_view('Contabilidad', 'Procesos', 'kardex de Producto','');

        return view('productKardex.list', $data);
    }

    public function list(Request $request){


        if($request->product_id){

            $periodo = Period::findOrFail(Session::get('period_id')); //esta mal debe ser del modelo

            $fecha_inicio = $request->txt_desde == "" ? $periodo->inicio : $request->txt_desde;
            $fecha_fin = $request->txt_hasta == "" ? $periodo->final : $request->txt_hasta;

            $resultado = $this->rptKardex->rptKardex($request->product_id,0,0,$fecha_inicio,$fecha_fin);
          //  $resultado = collect($resultado);

            $resultados = array();

            foreach ($resultado as $res) {
             //   $item = $res->ingresos - $res->almacen;
                $res->stock=$res->ingresos-$res->salidas;
                 array_push($resultados,$res);
            }

        }

        else{
            $resultados = [];
        }
     //   dd($resultados);

        return Datatables::of($resultados)->make(true);


    }
}
