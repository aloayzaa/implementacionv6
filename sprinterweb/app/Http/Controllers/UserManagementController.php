<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\UserPrivileges;
use App\Menu\Entities\Menu;
use App\Privileges\Entities\Privileges;
use App\Users\Entities\UserByCompany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    use UserPrivileges;
    private $var = 'user_management';
    protected $privilegios = '99.01.03';
    protected $user;

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }
    public function chat()
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'https://api.us-south.assistant.watson.cloud.ibm.com/instances/9d6484b9-0c94-4bc5-ba45-d70a27781ce5');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, '2nWMMU3BNFOhceiMVl6170Hft3TSdXosa-a89NkIxzjr');
        $query = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $query;
    }

    public function list(Request $request)
    {
        $user = UserByCompany::all();
        return Datatables::of($user)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Usuarios', 'del', 'Sistema', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('user_management.list', $data);
    }

    public function create(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Usuarios', 'del', 'Sistema', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['botones'] = botones('crea', $this->privilegios_navbar_menu());

        return view('user_management.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'codigo' => 'required|unique:usuario,codigo',
            'descripcion' => 'required',
            'email' => 'required|unique:usuario,email',
        ];

        $messages = [
            'codigo.required' => 'El código es requerido',
            'codigo.unique' => 'El código ya está registrado',
            'descripcion.required' => 'La descripción es requerida',
            'email.required' => 'El email es requerido',
            'email.unique' => 'El email ya está registrado',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $usuario = new UserByCompany();
            $usuario->codigo = $request->codigo;
            $usuario->descripcion = $request->descripcion;
            $usuario->password = '';
            $usuario->password = bcrypt(Session::get('company_ruc'));
            $usuario->email = $request->email;
            $usuario->estipo = ($request->estipo == 'on') ? 1 : 0;
            $usuario->vence = null;
            $usuario->pdescuento = ($request->pdescuento == '') ? 0 : $request->pdescuento;
            $usuario->usuario = Session::get('usuario');   //Está para preguntar al inge
            $usuario->estado = 1;
            $id = null;
            $id = DB::transaction(function () use ($usuario) {
                $usuario->save();
                return $usuario->id;
            });
            $r["ruta"] = route('edit.user_management', $id);
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

    public function edit($id)
    {
        $usuarios = UserByCompany::findOrFail($id);
        $data['usuario'] = $usuarios;
        $data['proceso'] = 'edita';
        $data['route'] = route('user_management');
        $data['var'] = $this->var;
        $data['view'] = link_view('Usuarios', 'del', 'Sistema', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($usuarios->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['botones'] = botones('edita', $this->privilegios_navbar_menu());

        $data['maestros'] = Menu::modulo('01');
        $data['compras'] = Menu::modulo('04');
        $data['ventas'] = Menu::modulo('03');
        $data['logistica'] = Menu::modulo('02');
        $data['tesoreria'] = Menu::modulo('05');
        $data['contabilidad'] = Menu::modulo('06');
        $data['especial'] = Menu::especial('06.04.04', $id);
        $data['especial2'] = Menu::especial('06.04.05', $id);
        $data['activos'] = Menu::modulo('07');
        $data['tributaria'] = Menu::modulo('13');
        $data['utilitarios'] = Menu::modulo('99');
        /*        $data['planillas'] = Menu::modulo('08');*/
        /*        $data['transporte'] = Menu::modulo('12');*/

        return view('user_management.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = UserByCompany::findOrfail($id);
            $usuario->codigo = $request->codigo;
            $usuario->descripcion = $request->descripcion;
            $usuario->email = $request->email;
            $usuario->estipo = ($request->estipo == 'on') ? 1 : 0;
            $usuario->vence = null;
            $usuario->pdescuento = ($request->pdescuento == '') ? 0 : $request->pdescuento;
            $usuario->usuario = Session::get('usuario');
            $usuario->estado = 1;  //Está para preguntar al inge

            DB::transaction(function () use ($usuario) {
                $usuario->save();
            });

            $r["ruta"] = route('edit.user_management', $id);
            $r["success"] = "Se guardaron los cambios correctamente";
        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function update_privilegios(Request $request)
    {
        try {
            //maestros
            foreach ($request->menu_id as $value) {
                $existe = Privileges::where('usuario_id', $request->usuario_id)->where('menu_id', $value)->exists();
                if ($existe) {
                    $data['crea'] = (isset($request->crea[$value]) && $request->crea[$value] == 'on') ? 1 : 0;
                    $data['edita'] = (isset($request->edita[$value]) && $request->edita[$value] == 'on') ? 1 : 0;
                    $data['anula'] = (isset($request->anula[$value]) && $request->anula[$value] == 'on') ? 1 : 0;
                    $data['borra'] = (isset($request->borra[$value]) && $request->borra[$value] == 'on') ? 1 : 0;
                    $data['consulta'] = (isset($request->consulta[$value]) && $request->consulta[$value] == 'on') ? 1 : 0;
                    $data['imprime'] = (isset($request->imprime[$value]) && $request->imprime[$value] == 'on') ? 1 : 0;
                    $data['aprueba'] = (isset($request->aprueba[$value]) && $request->aprueba[$value] == 'on') ? 1 : 0;
                    $data['precio'] = (isset($request->precio[$value]) && $request->precio[$value] == 'on') ? 1 : 0;
                    Privileges::update_data($request->usuario_id, $value, $data);
                    if ($request->mensaje == 'Utilitarios'){
                        $r["success"] = "Datos modificados exitosamente";
                    }else{
                        $r = null;
                    }
                } else {
                    //insertamos
                    $privilegios = new Privileges();
                    $privilegios->usuario_id = $request->usuario_id;
                    $privilegios->menu_id = $value;
                    $privilegios->crea = (isset($request->crea[$value]) && $request->crea[$value] == 'on') ? 1 : 0;
                    $privilegios->edita = (isset($request->edita[$value]) && $request->edita[$value] == 'on') ? 1 : 0;
                    $privilegios->anula = (isset($request->anula[$value]) && $request->anula[$value] == 'on') ? 1 : 0;
                    $privilegios->borra = (isset($request->borra[$value]) && $request->borra[$value] == 'on') ? 1 : 0;
                    $privilegios->consulta = (isset($request->consulta[$value]) && $request->consulta[$value] == 'on') ? 1 : 0;
                    $privilegios->imprime = (isset($request->imprime[$value]) && $request->imprime[$value] == 'on') ? 1 : 0;
                    $privilegios->aprueba = (isset($request->aprueba[$value]) && $request->aprueba[$value] == 'on') ? 1 : 0;
                    $privilegios->precio = (isset($request->precio[$value]) && $request->precio[$value] == 'on') ? 1 : 0;
                    $privilegios->usuario = Auth::id();
                    $privilegios->save();
                    if ($request->mensaje == 'Utilitarios'){$r["success"] = "Datos guardados exitosamente";}else{$r = null;}
                }
            }
        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function loaduser(Request $request)
    {
        $loaduser = UserByCompany::select('id', 'codigo', 'descripcion')->where('estado', 1)->where('estipo', 1)->get();
        return Datatables::of($loaduser)->make();
    }

    public function maestrosproductos()
    {
        $maestros = Menu::submodulos('01.01');
        return Datatables::of($maestros)->make();
    }

    public function maestrosterceros()
    {
        $maestros = Menu::submodulos('01.02');
        return Datatables::of($maestros)->make();
    }

    public function maestroscostos()
    {
        $maestros = Menu::submodulos('01.03');
        return Datatables::of($maestros)->make();
    }

    public function maestrosotros()
    {
        $maestros = Menu::submodulos('01.04');
        return Datatables::of($maestros)->make();
    }

    public function comprasconfiguracion()
    {
        $compras = Menu::submodulos('04.01');
        return Datatables::of($compras)->make();
    }

    public function comprastransacciones()
    {
        $compras = Menu::submodulos('04.02');
        return Datatables::of($compras)->make();
    }

    public function comprasprocesos()
    {
        $compras = Menu::submodulos('04.03');
        return Datatables::of($compras)->make();
    }

    public function comprasreportes()
    {
        $data['vconfiguracion'] = Menu::submodulos('03.01');
        $compras = Menu::submodulos('04.04');
        return Datatables::of($compras)->make();
    }

    public function ventasconfiguracion()
    {
        $ventas = Menu::submodulos('03.01');
        return Datatables::of($ventas)->make();
    }

    public function ventastransacciones()
    {
        $ventas = Menu::submodulos('03.02');
        return Datatables::of($ventas)->make();
    }

    public function ventasprocesos()
    {
        $ventas = Menu::submodulos('03.03');
        return Datatables::of($ventas)->make();
    }

    public function ventasreportes()
    {
        $ventas = Menu::submodulos('03.04');
        return Datatables::of($ventas)->make();
    }

    public function logisticaconfiguracion()
    {
        $logistica = Menu::submodulos('02.01');
        return Datatables::of($logistica)->make();
    }

    public function logisticatransacciones()
    {
        $logistica = Menu::submodulos('02.02');
        return Datatables::of($logistica)->make();
    }

    public function logisticaprocesos()
    {
        $logistica = Menu::submodulos('02.03');
        return Datatables::of($logistica)->make();
    }

    public function logisticareportes()
    {
        $logistica = Menu::submodulos('02.04');
        return Datatables::of($logistica)->make();
    }

    public function tesoreriaconfiguracion()
    {
        $tesoreria = Menu::submodulos('05.01');
        return Datatables::of($tesoreria)->make();
    }

    public function tesoreriatransacciones()
    {
        $tesoreria = Menu::submodulos('05.02');
        return Datatables::of($tesoreria)->make();
    }

    public function tesoreriaprocesos()
    {
        $tesoreria = Menu::submodulos('05.03');
        return Datatables::of($tesoreria)->make();
    }

    public function tesoreriareportes()
    {
        $tesoreria = Menu::submodulos('05.04');
        return Datatables::of($tesoreria)->make();
    }

    public function contabilidadconfiguracion()
    {
        $contabilidad = Menu::submodulos('06.01');
        return Datatables::of($contabilidad)->make();
    }

    public function contabilidadtransacciones()
    {
        $contabilidad = Menu::submodulos('06.02');
        return Datatables::of($contabilidad)->make();
    }

    public function contabilidadprocesos()
    {
        $contabilidad = Menu::submodulos('06.03');
        return Datatables::of($contabilidad)->make();
    }

    public function contabilidadreportes()
    {
        $contabilidad = Menu::submodulos('06.04');
        return Datatables::of($contabilidad)->make();
    }

    public function especial()
    {
        $contabilidad = Menu::submodulos_especiales('06.04.04');
        return Datatables::of($contabilidad)->make();
    }

    public function especial2()
    {
        $contabilidad = Menu::submodulos_especiales('06.04.05');
        return Datatables::of($contabilidad)->make();
    }

    public function tributosconfiguracion()
    {
        $tributos = Menu::submodulos('13.01');
        return Datatables::of($tributos)->make();
    }

    public function tributostransacciones()
    {
        $tributos = Menu::submodulos('13.02');
        return Datatables::of($tributos)->make();
    }

    public function tributosprocesos()
    {
        $tributos = Menu::submodulos('13.03');
        return Datatables::of($tributos)->make();
    }

    public function tributosreportes()
    {
        $tributos = Menu::submodulos('13.04');
        return Datatables::of($tributos)->make();
    }

    public function activosconfiguracion()
    {
        $activos = Menu::submodulos('07.01');
        return Datatables::of($activos)->make();
    }

    public function activostransacciones()
    {
        $activos = Menu::submodulos('07.02');
        return Datatables::of($activos)->make();
    }

    public function activosprocesos()
    {
        $activos = Menu::submodulos('07.03');
        return Datatables::of($activos)->make();
    }

    public function activosreportes()
    {
        $activos = Menu::submodulos('07.04');
        return Datatables::of($activos)->make();
    }

    public function utilitarioopciones()
    {
        $utilitarios = Menu::submodulos('99.01');
        return Datatables::of($utilitarios)->make();
    }

    public function loaddata(Request $request)
    {

        $id = $request->id;
        $data['mproductos'] = Menu::submodulos2('01.01', $id);
        $data['mterceros'] = Menu::submodulos2('01.02', $id);
        $data['mcostos'] = Menu::submodulos2('01.03', $id);
        $data['motros'] = Menu::submodulos2('01.04', $id);

        $data['lconfiguracion'] = Menu::submodulos2('02.01', $id);
        $data['ltransacciones'] = Menu::submodulos2('02.02', $id);
        $data['lprocesos'] = Menu::submodulos2('02.03', $id);
        $data['lreportes'] = Menu::submodulos2('02.04', $id);

        $data['vconfiguracion'] = Menu::submodulos2('03.01', $id);
        $data['vtransacciones'] = Menu::submodulos2('03.02', $id);
        $data['vprocesos'] = Menu::submodulos2('03.03', $id);
        $data['vreportes'] = Menu::submodulos2('03.04', $id);

        $data['cconfiguracion'] = Menu::submodulos2('04.01', $id);
        $data['ctransacciones'] = Menu::submodulos2('04.02', $id);
        $data['cprocesos'] = Menu::submodulos2('04.03', $id);
        $data['creportes'] = Menu::submodulos2('04.04', $id);

        $data['tconfiguracion'] = Menu::submodulos2('05.01', $id);
        $data['ttransacciones'] = Menu::submodulos2('05.02', $id);
        $data['tprocesos'] = Menu::submodulos2('05.03', $id);
        $data['treportes'] = Menu::submodulos2('05.04', $id);

        $data['coconfiguracion'] = Menu::submodulos2('06.01', $id);
        $data['cotransacciones'] = Menu::submodulos2('06.02', $id);
        $data['coprocesos'] = Menu::submodulos2('06.03', $id);
        $data['coreportes'] = Menu::submodulos2('06.04', $id);

        $data['creportesespecial'] = Menu::submodulos3('06.04.04', $id);
        $data['creportesespecial2'] = Menu::submodulos3('06.04.05', $id);

        $data['aconfiguracion'] = Menu::submodulos2('07.01', $id);
        $data['atransacciones'] = Menu::submodulos2('07.02', $id);
        $data['aprocesos'] = Menu::submodulos2('07.03', $id);
        $data['areportes'] = Menu::submodulos2('07.04', $id);

        /*        $data['pconfiguracion'] = Menu::submodulos2('08.01', $id);
                $data['ptransacciones'] = Menu::submodulos2('08.02', $id);
                $data['pprocesos'] = Menu::submodulos2('08.03', $id);
                $data['preportes'] = Menu::submodulos2('08.04', $id);

                $data['trconfiguracion'] = Menu::submodulos2('12.01', $id);
                $data['trtransacciones'] = Menu::submodulos2('12.02', $id);
                $data['trprocesos'] = Menu::submodulos2('12.03', $id);
                $data['trreportes'] = Menu::submodulos2('12.04', $id);*/

        $data['triconfiguracion'] = Menu::submodulos2('13.01', $id);
        $data['tritransacciones'] = Menu::submodulos2('13.02', $id);
        $data['triprocesos'] = Menu::submodulos2('13.03', $id);
        $data['trireportes'] = Menu::submodulos2('13.04', $id);

        $data['uopcion'] = Menu::submodulos2('99.01', $id);

        return $data;
    }

}
