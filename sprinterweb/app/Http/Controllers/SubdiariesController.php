<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Subdiaries\Entities\Subdiaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class SubdiariesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'subdiaries';
    protected $user;
    protected $privilegios='01.04.06';
    private $tabla_cabecera = 'subdiario';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user='ADMINISTRADOR';
    }

    public function list()
    {
        $subdiaries = Subdiaries::all();
        return DataTables::of($subdiaries)->make();
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
        $data['view'] = link_view('Maestros', 'Otros', 'Subdiarios', '');
        $data['header'] = headeroptions($this->var, 'list', '', '',$this->privilegios(),$this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('subdiaries.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['route'] = route('subdiaries');
        $data['view'] = link_view('Maestros', 'Otros', 'Subdiarios', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '',$this->privilegios(),$this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('subdiaries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {
        try {
            $subdiaries = new Subdiaries();
            $subdiaries->codigo = $request->code_sd;
            $subdiaries->descripcion = $request->description_sd;
            $subdiaries->tcambio = $request->type;
            $subdiaries->codsunat = $request->sunat;
            $subdiaries->bimoneda = validaCheck($request->bicurrency);
            $subdiaries->deajuste = validaCheck($request->adjustment);
            $subdiaries->noautomaticas = validaCheck($request->noautomatic);
            $subdiaries->estado = 1;
            $subdiaries->usuario = Auth::id();
            $id=null;
            $id=DB::transaction(function () use ($subdiaries) {
                $subdiaries->save();
                $id= $subdiaries->id;
                return $id;
            });
            $r["ruta"] = route('edit.subdiaries', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

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
        $data['subdiario'] = Subdiaries::findOrFail($id);
        $data['var'] = $this->var;
        $data['route'] = route('subdiaries');
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Maestros', 'Otros', 'Subdiarios', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['subdiario']->estado,$this->privilegios(),$this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('subdiaries.edit', $data);
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
            $subdiaries = Subdiaries::findOrFail($id);
            $subdiaries->codigo = $request->code_sd;
            $subdiaries->descripcion = $request->description_sd;
            $subdiaries->tcambio = $request->type;
            $subdiaries->codsunat = $request->sunat;
            $subdiaries->bimoneda = validaCheck($request->bicurrency);
            $subdiaries->deajuste = validaCheck($request->adjustment);
            $subdiaries->noautomaticas = validaCheck($request->noautomatic);
            $subdiaries->usuario = Auth::id();
            $id=null;
            $id=DB::transaction(function () use ($subdiaries) {
                $subdiaries->save();
                $id=$subdiaries->id;
                return $id;
            });
            $r["ruta"] = route('edit.subdiaries', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

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

    public function activar(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Subdiaries::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
