<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\TypeHeading\Entities\TypeHeading;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MaestroRequest;
class TypeHeadingController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'typeheading';
    private $tabla_cabecera = 'tiporubro';
    protected $user;
    protected $privilegios = '01.02.10';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $typeheading = TypeHeading::all();
        return Datatables::of($typeheading)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Rubro','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('typeheading.list', $data);
    }

    public function create()
    {
        $rubro = TypeHeading::all()->where('estado', 1)->last();
        if ($rubro->codigo < 10){
            $data['codigo'] = str_pad($rubro->codigo +1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $rubro->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Rubro','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('typeheading.create', $data);
    }

    public function store(MaestroRequest $request)
    {
        try {

            $rubro = new TypeHeading();
            $rubro->codigo = $request->txt_codigo_rubro;
            $rubro->descripcion = $request->txt_descripcion_rubro;
            $rubro->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $rubro->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$rubro->tipo = $request->;
            $rubro->estado = 1;
            $rubro->usuario = Session::get('usuario');

            $id = DB::transaction(function () use ($rubro) {
                $rubro->save();
                return $rubro->id;
            });

            $data['ruta']  = route('edit.typeheading', $id);

        } catch (PDOException $e) {
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        $data["proceso"] = $request->proceso;

        return $data;
    }

    public function edit($id)
    {
        $type = TypeHeading::findOrFail($id);
        $data['typeheading'] = $type;
        $data['proceso'] = 'edita';
        $data['route'] = route('zonetype');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Rubro','');
        $data['header'] = headeroptions($this->var, 'edita','', ($type->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('typeheading.edit', $data);
    }

    public function update(MaestroRequest $request, $id)
    {
        try {

            $rubro = TypeHeading::findOrfail($id);
            $rubro->codigo = $request->txt_codigo_rubro;
            $rubro->descripcion = $request->txt_descripcion_rubro;
            $rubro->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $rubro->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$rubro->tipo = $request->;
            $rubro->estado = 1;
            $rubro->usuario = Session::get('usuario');

            $id = null;
            $id = DB::transaction(function () use ($rubro) {
                $rubro->save();
                return $rubro->id;
            });
            $r["ruta"] = route('edit.typeheading', $id);
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