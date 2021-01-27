<?php

namespace App\Http\Controllers;

use App\Documents\Entities\Commercial;
use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class CommercialController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'commercials';
    private $tabla_cabecera = 'documentocom';
    protected $user;
    protected $privilegios = '01.02.06';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['route'] = $request->route;
        $data['proceso'] = $request->proceso;
        $data['view'] = link_view('Maestros', 'Terceros', 'Documentos Comerciales', '');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('commercials.list', $data);
    }

    public function list()
    {

        $commercials = Commercial::all();
        return DataTables::of($commercials)->make();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crear';
        $data['var'] = $this->var;
        $data['route'] = route($this->var);
        $data['view'] = link_view('Maestros', 'Terceros', 'Documentos Comerciales', '');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('commercials.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {
       // dd($request->all());
        try{

            $commercial = new Commercial();
            $commercial->codigo = $request->code_doc;
            $commercial->descripcion = $request->name;
            $commercial->codsunat = ($request->codSunat == '') ? '' : $request->codSunat;
            $commercial->factor = ($request->factor == '') ? '' : $request->factor;
            $commercial->origen = ($request->origin =='')? '': $request ->origin;

            $commercial->usuario = Session ::get('usuario');

            if (isset($request->control)) {
                $commercial->controlnum = 1;
            } else {
                $commercial->controlnum = 0;
            }
            if (isset($request->bloquear)) {
                $commercial->bloquear = 1;
            } else {
                $commercial->bloquear = 0;
            }
            if (isset($request->compra)) {
                $commercial->verenrcompra = 1;
            } else {
                $commercial->verenrcompra = 0;
            }
            if (isset($request->venta)) {
                $commercial->verenrventa = 1;
            } else {
                $commercial->verenrventa = 0;
            }

            $id= null;
            $id= DB::transaction(function () use ($commercial) {
                $commercial->save();
                $id=$commercial->id;
                return $id;
            });
            $r["ruta"] = route('edit.commercials', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        }catch(PDOException $e) {

            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
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
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['commercial'] = Commercial::findOrFail($id);
        $data['view'] = link_view('Maestros', 'Terceros', 'Documentos Comerciales', '');
        $data['header'] = headeroptions($this->var, 'edita','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('commercials.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaestroRequest $request, $id)
    {
        try {
            $commercial = Commercial::findOrFail($id);
            $commercial->codigo = $request->code_doc;
            $commercial->descripcion = $request->name;
            $commercial->codsunat = $request->codSunat;
            $commercial->factor = $request->factor;
            $commercial->origen = $request->origin;
            $commercial->usuario = Session::get('usuario');
            if (isset($request->control)) {
                $commercial->controlnum = 1;
            } else {
                $commercial->controlnum = 0;
            }
            if (isset($request->bloquear)) {
                $commercial->bloquear = 1;
            } else {
                $commercial->bloquear = 0;
            }
            if (isset($request->compra)) {
                $commercial->verenrcompra = 1;
            } else {
                $commercial->verenrcompra = 0;
            }
            if (isset($request->venta)) {
                $commercial->verenrventa = 1;
            } else {
                $commercial->verenrventa = 0;
            }
            $id=null;
            $id=DB::transaction(function () use ($commercial) {
                $commercial->save();
                $id=$commercial->id;
                return $id;
            });
            $r["ruta"] = route('edit.commercials', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
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
