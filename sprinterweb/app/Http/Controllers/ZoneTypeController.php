<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\ZoneType\Entities\ZoneType;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MaestroRequest;
class ZoneTypeController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'zonetype';
    private $tabla_cabecera = 'tipozona';
    protected $user;
    protected $privilegios = '01.02.09';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $zonetype = ZoneType::all();
        return Datatables::of($zonetype)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Zona','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('zonetype.list', $data);
    }

    public function create()
    {
        $types = ZoneType::all()->where('estado', 1)->last();
        if ($types->codigo < 10){
            $data['codigo'] = str_pad($types->codigo +1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $types->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Zona','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('zonetype.create', $data);
    }

    public function store(MaestroRequest $request)
    {
        try {

            $types = new ZoneType();
            $types->codigo = $request->txt_codigo_tipo_zona;
            $types->descripcion = $request->txt_descripcion_tipo_zona;
            $types->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $types->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$clasetercero->tipo = $request->;
            $types->estado = 1;
            $types->usuario = Session::get('usuario');

            $id = DB::transaction(function () use ($types) {
                $types->save();
                return $types->id;
            });

            $data['ruta']  = route('edit.zonetype', $id);

        } catch (PDOException $e) {
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        $data["proceso"] = $request->proceso;

        return $data;
    }

    public function edit($id)
    {
        $zone = ZoneType::findOrFail($id);
        $data['zonetype'] = $zone;
        $data['proceso'] = 'edita';
        $data['route'] = route('zonetype');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Zona','');
        $data['header'] = headeroptions($this->var, 'edita','', ($zone->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('zonetype.edit', $data);
    }

    public function update(MaestroRequest $request, $id)
    {
        try {

            $types = ZoneType::findOrfail($id);
            $types->codigo = $request->txt_codigo_tipo_zona;
            $types->descripcion = $request->txt_descripcion_tipo_zona;
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
            $r["ruta"] = route('edit.zonetype', $id);
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
