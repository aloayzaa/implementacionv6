<?php

namespace App\Http\Controllers;

use App\Customers\Entities\ThirdClass;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MaestroRequest;

class ThirdClassController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'thirdclass';
    private $tabla_cabecera = 'clasetercero';

    protected $user;
    protected $privilegios = '01.02.02';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $thirdclass = ThirdClass::all();
        return Datatables::of($thirdclass)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Clasificación','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('thirdclass.list', $data);
    }

    public function create()
    {
        $brand = ThirdClass::all()->where('estado', 1)->last();
        if ($brand->codigo < 10){
            $data['codigo'] = str_pad($brand->codigo +1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $brand->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Clasificación','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('thirdclass.create', $data);
    }

    public function store(MaestroRequest $request)
    {

        try {

            $clasetercero = new ThirdClass();
            $clasetercero->codigo = $request->txt_codigo_terceros;
            $clasetercero->descripcion = $request->txt_descripcion_terceros;
            $clasetercero->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $clasetercero->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$clasetercero->tipo = $request->;
            $clasetercero->estado = 1;
            $clasetercero->usuario = Session::get('usuario');


            $id = DB::transaction(function () use ($clasetercero) {
                $clasetercero->save();
                return $clasetercero->id;
            });

            $data['ruta']  = route('edit.thirdclass', $id);

        } catch (PDOException $e) {
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        $data["proceso"] = $request->proceso;

        return $data;
    }

    public function edit($id)
    {
        $clasetercero = ThirdClass::findOrFail($id);
        $data['clasetercero'] = $clasetercero;
        $data['proceso'] = 'edita';
        $data['route'] = route('thirdclass');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Clasificación','');
        $data['header'] = headeroptions($this->var, 'edita','', ($clasetercero->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('thirdclass.edit', $data);
    }
    public function update(MaestroRequest $request, $id)
    {
        try {

            $clasetercero = ThirdClass::findOrfail($id);
            $clasetercero->codigo = $request->txt_codigo_terceros;
            $clasetercero->descripcion = $request->txt_descripcion_terceros;
            $clasetercero->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $clasetercero->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            $clasetercero->estado = 1;
            $clasetercero->usuario = Session::get('usuario');

            $id = null;
            $id = DB::transaction(function () use ($clasetercero) {
                $clasetercero->save();
                $id = $clasetercero->id;
                return $id;
            });
            $r["ruta"] = route('edit.thirdclass', $id);
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
