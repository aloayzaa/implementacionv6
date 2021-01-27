<?php

namespace App\Http\Controllers;

use App\Families\Entities\Familia;
use App\Http\Requests\FamilyStoreRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Products\Entities\ProductoSunat;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use PDOException;
use App\Http\Traits\Controls\Movement;
class FamilyController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'families';
    private $tabla_cabecera = 'familiapdto';

    protected $user;
    protected $privilegios = '01.01.02';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Productos', 'Familias', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('families.list', $data);
    }

    public function list()
    {
        $families = Familia::all();
        return DataTables::of($families)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crear';
        $data['view'] = link_view('Maestros', 'Productos', 'Familia', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('families.create', $data);
    }


    public function producto_sunat(Request $request)
    {
        //dd($request->term);
        $resultado = ProductoSunat::sunat_products_eight_digits($request->term);

        $data = array();

        foreach ($resultado as $key => $value) {
            if($resultado){
                $data[] = array('id' => $value->id,'codigo' => $value->codigo, 'descripcion' => $value->descripcion);
            }
        }


        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FamilyStoreRequest $request)
    {

        try {
            $familia = new Familia();
            $familia->codigo = $request->txt_codigo;
            $familia->descripcion = $request->txt_descripcion;
            $familia->codsunat = $request->txt_codigo_sunat;
            $familia->simbolo = '';
            $familia->tipo = '';
            $familia->usuario = Session::get('usuario');
            $familia->tipoorden_id = null;
            $familia->productosunat_id = $request->cbo_pro_sunat;

            $id = null;
            $id = DB::transaction(function () use ($familia) {
                $familia->save();
                $id = $familia->id;
                return $id;
            });

            $r["ruta"] = route('edit.families', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['proceso'] = 'edit';
        $data['familia'] = Familia::findOrFail($id);
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Productos', 'Familias', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($data['familia']->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('families.edit', $data);
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

            $familia = Familia::findOrFail($request->id);
            $familia->codigo = $request->txt_codigo;
            $familia->descripcion = $request->txt_descripcion;
            $familia->codsunat = $request->txt_codigo_sunat;
            $familia->simbolo = '';
            $familia->tipo = '';
            $familia->estado = 2;
            $familia->usuario = Session::get('usuario');
            $familia->tipoorden_id = null;
            $familia->productosunat_id = $request->cbo_pro_sunat;
            $id = null;
            $id = DB::transaction(function () use ($familia) {
                $familia->save();
                $id = $familia->id;
                return $id;
            });

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
