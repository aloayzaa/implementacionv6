<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\MovementTypes\Entities\MovementType;
use App\Period\Entities\Period;

use App\SalesTypes\Entities\SaleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class MovementTypeController extends Controller
{
    use Tables, UserPrivileges;
    private $var = 'movementtype';
    protected $var_ruta = 'TiposMovimiento';
    protected $tabla_cabecera = 'movimientotipo';
    protected $user;
    protected $privilegios = '02.01.01';

    public function __construct( )
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var_ruta;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Almacenes', 'Configuración', 'Tipos de Movement', '');

        return view('movementType.list', $data);
    }

    public function list(){

        $tiposMovimiento = MovementType::all();
        return DataTables::of($tiposMovimiento)->make();

    }

    public function create()
    {
        $data['var'] = $this->var;
        $data['grupos'] = MovementType::grupos();
        $data['movimientos'] = MovementType::movimientos();
        $data['calculos'] = MovementType::calculos();
        $data['cambios'] = MovementType::cambios();
        $data['t_ventas'] = SaleType::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Almacenes', 'Configuración', 'Tipos de Movement', '');

        return view('movementType.create', $data);
    }


    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $movementtype = new MovementType();
            $movementtype->codigo = $request->txt_codigo;
            $movementtype->descripcion = $request->txt_descripcion;
            $movementtype->codsunat = $request->txt_codsunat == null ? 0 : $request->txt_codsunat;
            $movementtype->grupo = $request->txt_grupo;
            $movementtype->factor = $request->txt_movtype;
            $movementtype->tipocalculo = $request->txt_typecalc;
            $movementtype->tipocambio = $request->txt_tipocambio;

            $movementtype->pidevalor = $request->check ? 1 : 0;
            $movementtype->tipoventa_id = $request->txt_tventa;

            $movementtype->usuario = Session::get('usuario');
            $movementtype->save();

            DB::commit();

            $data['ruta'] = route('edit.movementtype', $movementtype->id);
        }
        catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    'errors' => ['error' => 'Error al grabar'],
                    'message' => $e->getMessage()],500);
            }

        return $data;
    }


    public function edit($id)
    {
        $tipomovimiento = MovementType::find($id);

        $data['var'] = $this->var;
        $data['tipomovimiento'] = $tipomovimiento;
        $data['grupos'] = MovementType::grupos();
        $data['movimientos'] = MovementType::movimientos();
        $data['calculos'] = MovementType::calculos();
        $data['cambios'] = MovementType::cambios();
        $data['t_ventas'] = SaleType::all();
        $data['period'] = Period::find(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'edita', '', $tipomovimiento->estado = 1 ? 'Activo' : 'Inactivo',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Almacenes', 'Configuración', 'Tipos de Movement', '');

        return view('movementType.edit', $data);
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $movementtype = MovementType::findOrFail($id);
            $movementtype->codigo = $request->txt_codigo;
            $movementtype->descripcion = $request->txt_descripcion;
            $movementtype->codsunat = $request->txt_codsunat == null ? 0 : $request->txt_codsunat;
            $movementtype->grupo = $request->txt_grupo;
            $movementtype->factor = $request->txt_movtype;
            $movementtype->tipocalculo = $request->txt_typecalc;
            $movementtype->tipocambio = $request->txt_tipocambio;
            $movementtype->pidevalor = $request->check ? 1 : 0;
            $movementtype->tipoventa_id = $request->txt_tventa;

            $movementtype->save();
            DB::commit();

            $data["success"] = "Se guardaron los cambios correctamente";
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'errors' => ['error' => 'Error al grabar'],
                'message' => $e->getMessage()],500);
        }

        return $data;

    }

}
