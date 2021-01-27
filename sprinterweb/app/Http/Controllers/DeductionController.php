<?php

namespace App\Http\Controllers;

use App\Deductions\Entities\Deduction;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\PurchaseRequest;

class DeductionController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'deductions';
    private $deductionCollection;
    private $tabla_cabecera = 'tipodetraccion';
    protected $user;
    protected $privilegios = '04.01.01';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
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
        $data['view'] = link_view('Compras', 'Configuración', 'Tipo de Detracción', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('deductions.list', $data);
    }

    public function list()
    {
        $deductions = Deduction::all();
        return DataTables::of($deductions)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deduction = Deduction::all()->where('estado', 1)->last();
        if ($deduction->codigo < 10){
            $data['codigo'] = str_pad($deduction->codigo +1, 3, '00', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $deduction->codigo +1;
        }
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['route'] = route($this->var);
        $data['view'] = link_view('Compras', 'Configuración', 'Tipo de Detracción', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('deductions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseRequest $request)
    {
        try {
            $deduction = new Deduction();
            $deduction->codigo = $request->code;
            $deduction->descripcion = $request->description;
            $deduction->codsunat = ($request->sunatcode == '') ? '' : $request->sunatcode;
            $deduction->simbolo = ($request->symbol == '') ? '' : $request->symbol;
            $deduction->valor = ($request->value == '') ? '' : $request->value;
            $deduction->estado = 1;
            $deduction->usuario = Session::get('usuario');
            $id = null;
            $id = DB::transaction(function () use ($deduction) {
                $deduction->save();
                return $deduction->id;
            });
            $r["ruta"] = route('edit.deductions', $id);

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
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
        $deduction = Deduction::findOrFail($id);
        $data['deduction'] = $deduction;
        $data['var'] = $this->var;
        $data['route'] = route($this->var);
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Compras', 'Configuración', 'Tipo de Detracción', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($deduction->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('deductions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseRequest $request, $id)
    {
        try {
            $deduction = Deduction::findOrFail($id);
            $deduction->codigo = $request->code;
            $deduction->descripcion = $request->description;
            $deduction->codsunat = ($request->sunatcode == '') ? '' : $request->sunatcode;
            $deduction->simbolo = ($request->symbol == '') ? '' : $request->symbol;
            $deduction->valor = ($request->value == '') ? '' : $request->value;
            $deduction->usuario = Session::get('usuario');

            DB::transaction(function () use ($deduction) {
                $deduction->save();
            });

            $r["ruta"] = route('edit.deductions', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
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
}
