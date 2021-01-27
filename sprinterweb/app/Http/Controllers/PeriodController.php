<?php

namespace App\Http\Controllers;

use App\ExchangeRate\Entities\ExchangeRate;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class PeriodController extends Controller
{
    use Tables, UserPrivileges;
    private $var = 'periods';
    private $tabla_cabecera = 'periodo';
    protected $user;
    protected $privilegios = '13.01.02';


    public function __construct( )
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list()
    {
        $periods = Period::all();
        return Datatables::of($periods)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Periodo','');
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('periods.list', $data);
    }


    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['route'] = route('periods');
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Periodo','');
        $data['header'] = headeroptions($this->var, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('periods.create', $data);
    }


    public function store(Request $request)
    {
        $rules = [
            'codigo' => 'required|string|size:6',
            'descripcion' => 'required',
            'inicio' => 'required|unique:periodo,inicio',
            'final' => 'required|unique:periodo,final',
        ];
        $this->validate($request, $rules, [
            'codigo.required' => 'El código es obligatorio',
            'codigo.size' => 'El código no cumple con el formato',
            'descripcion.required' => 'La descripcion es obligatoria',
            'inicio.required' => 'La fecha inicial es obligatoria',
            'inicio.unique' => 'Ya existe un periodo con esa fecha incial',
            'final.required' => 'La fecha final es obligatoria',
            'final.unique' => 'Ya existe un periodo con esa fecha final',
        ]);

        //dd($request->all());

        DB::beginTransaction();

        try {
            $periodo = new Period();
            $periodo->codigo = $request->codigo;
            $periodo->descripcion = $request->descripcion;
            $periodo->inicio =  date_format(date_create($request->inicio), "Y-m-d");
            $periodo->final =  date_format(date_create($request->final), "Y-m-d");
            $periodo->usuario = Session::get('usuario');

            $periodo->save();
            DB::commit();

            $this->crear_tipos_cambio($periodo->inicio, $periodo->final);

            $data['ruta'] = route('edit.periods', $periodo->id);
        }
        catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    'errors' => ['error' => 'Error al grabar'],
                    'message' => $e->getMessage()],500);
        }

        return $data;
    }

    private function crear_tipos_cambio($inicio, $final){
        $fechainicio = explode('-', $inicio);
        $fechafinal = explode('-', $final);

        $cont = 0;
        while($fechainicio[2] <= $fechafinal[2]){
            $fecha = (new Carbon($inicio))->addDays($cont);

            $tipocambio = new ExchangeRate;
            $tipocambio->codigo = $fecha->format('Ymd');
            $tipocambio->fecha = $fecha->format('Y-m-d');
            $tipocambio->t_compra = 0.00;
            $tipocambio->t_venta = 0.00;
            $tipocambio->usuario = 'ADMINISTRADOR';
            $tipocambio->estado = 1;
            $tipocambio->save();

            $fechainicio[2]++;
            $cont++;
        }

    }

    public function edit($id)
    {
        $data["var"] = $this->var;
        $data["periodo"] = Period::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Periodo','');
        $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('periods.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'codigo' => 'required',
            'descripcion' => 'required',
        ];
        $this->validate($request, $rules, [
            'codigo.required' => 'El código es obligatorio',
            'descripcion.required' => 'La descripcion es obligatoria',
        ]);

        DB::beginTransaction();

        try {
            $periodo = Period::findOrFail($id);
            $periodo->descripcion = $request->descripcion;
            $periodo->usuario = Session::get('usuario');

            $periodo->save();
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



    public function period(Request $request)
    {
        $data['period'] = Period::findOrFail($request->period);
        session(['period_id' => $request->period]);
        session(['period' => $data['period']->descripcion]);

        return $data;
    }


    public function getFechas(Request $request)
    {
        $this->validate($request, ['codigo' => 'required|string|size:6'], ['codigo.size' => 'El código no cumple con el formato']);

        $year = substr($request->codigo, 0, 4);
        $mes = substr($request->codigo, 4, 2);

        try {
            $fecha = (new Carbon($year.'-'.$mes.'-01'));

        }catch (Exception $e){
            return response()->json(['errors' => array('detalle' => 'El Formato del código es: AñoMes')],422);
        }


        $data['fecha_inicio'] = $fecha->firstOfMonth()->format('d-m-Y');
        $data['fecha_final'] = $fecha->lastOfMonth()->format('d-m-Y');
        return $data;
    }

    private function eliminar_tablas_hijas($periodo){
        try {
            $tcambios = ExchangeRate::where('codigo', 'like', $periodo->codigo . '%')->delete();
        }catch (Exception $e){
            return response()->json(['errors' => array('detalle' => 'No se pudo eliminar los tipos de cambio')],422);
        }

    }

}
