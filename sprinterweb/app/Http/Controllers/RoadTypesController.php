<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\RoadTypes\Entities\RoadTypes;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MaestroRequest;

class RoadTypesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'roadtypes';
    private $tabla_cabecera = 'tipovia';
    protected $user;
    protected $privilegios = '01.02.08';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $roadtypes = RoadTypes::all();
        return Datatables::of($roadtypes)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Via','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('roadtypes.list', $data);
    }

    public function create()
    {
        $types = RoadTypes::all()->where('estado', 1)->last();
        if ($types->codigo < 10){
            $data['codigo'] = str_pad($types->codigo +1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $types->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Via','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('roadtypes.create', $data);
    }

    public function store(MaestroRequest $request)
    {
        try {

            $types = new RoadTypes();
            $types->codigo = $request->txt_codigo_tipo_via;
            $types->descripcion = $request->txt_descripcion_tipo_via;
            $types->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $types->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$clasetercero->tipo = $request->;
            $types->estado = 1;
            $types->usuario = Session::get('usuario');

            $id = DB::transaction(function () use ($types) {
                $types->save();
                return $types->id;
            });

            $data['ruta']  = route('edit.roadtypes', $id);

        } catch (PDOException $e) {
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        $data["proceso"] = $request->proceso;

        return $data;
    }

    public function edit($id)
    {
        $roadtypes = RoadTypes::findOrFail($id);
        $data['roadtypes'] = $roadtypes;
        $data['proceso'] = 'edita';
        $data['route'] = route('roadtypes');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Via','');
        $data['header'] = headeroptions($this->var, 'edita','', ($roadtypes->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('roadtypes.edit', $data);
    }

    public function update(MaestroRequest $request, $id)
    {
        try {

            $types = RoadTypes::findOrfail($id);
            $types->codigo = $request->txt_codigo_tipo_via;
            $types->descripcion = $request->txt_descripcion_tipo_via;
            $types->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $types->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$clasetercero->tipo = $request->;
            $types->estado = 1;
            $types->usuario = Session::get('usuario');

            $id = null;
            $id = DB::transaction(function () use ($types) {
                $types->save();
                return $types->id;
            });
            $r["ruta"] = route('edit.roadtypes', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }
}
