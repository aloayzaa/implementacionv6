<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\TariffItems\Entities\TariffItems;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
class TariffItemsController extends Controller
{
    use CartTrait, Tables;
    private $var = 'tariffitems';
    private $tabla_cabecera = 'parancelaria';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function list(Request $request)
    {
        $tariffitems = TariffItems::all();
        return Datatables::of($tariffitems)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Productos', 'Partidas Arancelarias','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('tariffitems.list', $data);
    }

    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['typeisc'] = TypeIsc::all();
        $data['view'] = link_view('Maestros', 'Productos', 'Partidas Arancelarias','');
        $data['header'] = headeroptions($this->var, 'crea','','');

        return view('tariffitems.create', $data);
    }
    public function store(MaestroRequest  $request)
    {
        //dd($request->all());

        try {

            $parancelaria = new TariffItems();
            $parancelaria->codigo = $request->txt_codigo;
            $parancelaria->descripcion = $request->txt_descripcion;
            //$parancelaria->codsunat = $request->;
            //$parancelaria->simbolo = $request->;
            $parancelaria->tipo = $request->cbo_tipo;
            //$parancelaria->estado = $request->;
            $parancelaria->valor = $request->txt_arancel;
            $parancelaria->isc = $request->txt_arancel_consumo;
            $parancelaria->tipoisc_id = $request->cbo_tipo_isc;
            $parancelaria->usuario = Auth::id();
            $id=null;
            $id = DB::transaction(function () use ($parancelaria) {
                $parancelaria->save();
                $id = $parancelaria->id;
                return $id;

            });
            $r["ruta"] = route('edit.tariffitems', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }


        return $r;
    }
    public function edit($id)
    {
        $data['parancelaria'] = TariffItems::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['route'] = route('tariffitems');
        $data['var'] = $this->var;
        $data['typeisc'] = TypeIsc::all();
        $data['view'] = link_view('Maestros', 'Productos', 'Partidas Arancelarias','');
        $data['header'] = headeroptions($this->var, 'edita','','');

        return view('tariffitems.edit', $data);
    }
    public function update(Request $request, $id)
    {
        try {

            $parancelaria = TariffItems::findOrfail($id);
            $parancelaria->codigo = $request->txt_codigo;
            $parancelaria->descripcion = $request->txt_descripcion;
            //$parancelaria->codsunat = $request->;
            //$parancelaria->simbolo = $request->;
            $parancelaria->tipo = $request->cbo_tipo;
            //$parancelaria->estado = $request->;
            $parancelaria->valor = $request->txt_arancel;
            $parancelaria->isc = $request->txt_arancel_consumo;
            $parancelaria->tipoisc_id = $request->cbo_tipo_isc;
            $parancelaria->usuario = Auth::id();

            DB::transaction(function () use ($parancelaria) {
                $parancelaria->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }
}
