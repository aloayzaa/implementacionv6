<?php

namespace app\Http\Controllers\Panel;

use App\Companies\Entities\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use App\Http\Controllers\Controller;
use App\Panel\Companies\Collections\CompanyCollection;
use Yajra\DataTables\DataTables;

class EmpresasController extends Controller
{
    protected $user = 'root';
    protected $pass = 'W0won5$5';
    protected $conf_bd = 'sprinter_';
    private $var = 'empresas';
    private $companiesCollection;

    public function __construct(CompanyCollection $companiesCollection)
    {
        $this->companiesCollection = $companiesCollection;
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Panel', 'Empresas', '', '');
        $array = ['id_empresa' => Session::get('id_empresa')];
        $data['header'] = headeroptions($this->var, 'list', $array, '');

        return view('companies.list', $data);
    }

    public function list()
    {
        $companies = Empresa::listado_empresas_x_empresaprincipal(Session::get('id_empresa'));
        $this->companiesCollection->actions($companies);
        return DataTables::of($companies)->rawColumns(['sprinter'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id_estudio
     * @return \Illuminate\Http\Response
     */
    public function create($id_empresa)
    {
        $proceso = 'crea';
        $data['var'] = $this->var;
        $data['proceso'] = $proceso;
        $data['id_empresa'] = $id_empresa;
        $data['view'] = link_view('', 'Panel', 'Empresas', '');
        $array = ['id_empresa' => $id_empresa];
        $data['header'] = headeroptions($this->var, 'crea', $array, '');

        if (Session::get('user_rol') == 3) {
            $data['route'] = route('index.companies', ['id_empresa' => $id_empresa]);
        } else {
            $data['route'] = route('empresas');
        }

        return view('companies.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $empresa = new Empresa;
            $empresa->emp_codigo = $request->txt_ruc;
            $empresa->emp_ruc = $request->txt_ruc;
            $empresa->emp_razon_social = $request->txt_empresa;
            $empresa->emp_contacto = $request->contact;
            $empresa->emp_descripcion = $request->txt_empresa;
            $empresa->emp_direccion = $request->txt_address;
            $empresa->emp_telefono = $request->phone;
            $empresa->emp_estado = 1;
            $empresa->emp_observaciones = $request->observation;
            $empresa->ver_id = 3;
            $empresa->emp_primaria_id = $request->id_empresa;
            $empresa->emp_usuario = Auth::id();
            $empresa->emp_fecha_registro = date('Y-m-d');

            DB::transaction(function () use ($empresa, $request) {
                $empresa->save();
                //$this->crea_conexion_bd($request);
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado";

        } catch (PDOException $exception) {
            $r["estado"] = "error";
            $r["bd"] = $exception->getMessage();
            $r["mensaje"] = "Datos no válidos";
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    private function crea_conexion_bd($request)
    {
        $this->inserta_datos_conexion($request);
        $this->crea_bd($request);
    }

    //NO TOCAR PARA NADA! CONSULTE AL CREADOR :3
    private function inserta_datos_conexion($request)
    {
        $filename = config_path() . '/database.php';

        $file = fopen($filename, "r+");
        $lineas = file($filename);
        foreach ($lineas as $num_linea => $linea) {
            if ($num_linea == 63) {
                fwrite($file, "        'DB_CONNECTION_" . $request->txt_ruc . "' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => '" . $this->conf_bd . $request->txt_ruc . "',
            'username'  => '" . $this->user . "',
            'password'  => '" . $this->pass . "',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'port'      => '3306',
            'strict'    => false,
        ],

");
            }

            fwrite($file, $linea);
        }
        fclose($file);
    }

    private function crea_bd($request)
    {
        $public = public_path();
        $basedatos = $this->conf_bd . $request->txt_ruc;

        $this->bd($basedatos, $public);
    }

    private function bd($basedatos, $public)
    {
        $file = $public . '/sprinterwebv1.sql';

        exec($public . '/ejecuta.sh ' . $basedatos);

        exec('/usr/bin/mysql --init-command="SET SESSION FOREIGN_KEY_CHECKS=0;" --default-character-set=latin1 -u root \'-pW0won5$5\' --port=3306 --max_allowed_packet=1024M ' . $basedatos . ' < ' . $file . '');
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
     * @param $id_empresa
     * @return \Illuminate\Http\Response
     */
    public function edit($id_empresa)
    {
        $data['company'] = Empresa::findOrFail($id_empresa);
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Panel', 'Empresas', '', '');
        $array = ['id_empresa' => $id_empresa];
        $data['header'] = headeroptions($this->var, 'edita', $array, $data['company']->emp_estado);

        if (Session::get('user_rol') == 3) {
            $data['route'] = route('index.companies', ['id_empresa' => $id_empresa]);
        } else {
            $data['route'] = route('empresas');
        }

        return view('companies.edit', $data);
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
            $empresa = Empresa::findOrFail($id);
            $empresa->emp_codigo = $request->ruc;
            $empresa->emp_ruc = $request->ruc;
            $empresa->emp_razon_social = $request->name;
            $empresa->emp_contacto = $request->contact;
            $empresa->emp_descripcion = $request->name;
            $empresa->emp_direccion = $request->address;
            $empresa->emp_telefono = $request->phone;

            DB::transaction(function () use ($empresa) {
                $empresa->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado";

        } catch (PDOException $exception) {
            $r["estado"] = "error";
            $r["bd"] = $exception->getMessage();
            $r["mensaje"] = "Datos no válidos";
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    public function valida_ruc_no_repetido(Request $request)
    {
        $resultado = Empresa::valida_ruc($request->ruc);

        if ($resultado) {
            $r['estado'] = 'repetido';
            $r['mensaje'] = 'El ruc ingresado ya se encuentra registrado, verifique por favor.';
        } else {
            $r['estado'] = 'ok';
            $r['mensaje'] = 'El ruc ingresado es válido.';
        }
        return $r;
    }

    public function activar_anular_empresa(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Empresa::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function company_check(Request $request)
    {
        $resultado = Empresa::where('emp_codigo', '=', $request->ruc)->get();

        if (isset($resultado[0]->id)) {
            return 1;
        } else {
            return 0;
        }
    }
}
