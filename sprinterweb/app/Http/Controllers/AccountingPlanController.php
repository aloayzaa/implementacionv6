<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Currency\Entities\Currency;
use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class AccountingPlanController extends Controller
{
    use CartTrait, Tables, UserPrivileges;

    protected $var = 'accountingplans';
    protected $tabla_cabecera = 'pcg';

    protected $user;
    protected $privilegios = '01.02.03';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = route('accountingplans');
        $data['view'] = link_view('Maestos', 'Costos', 'Plan de Cuentas', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('accountingplan.list', $data);
    }

    public function list()
    {
        $cuentas = AccountingPlan::all();
        return DataTables::of($cuentas)->make();
    }

    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['currencies'] = Currency::all();
        $data['clases'] = AccountingPlan::clases();
        $data['auxiliares'] = AccountingPlan::auxiliares();
        $data['naturalezas'] = AccountingPlan::naturalezas();
        $data['ajustes'] = AccountingPlan::ajustes();
        $data['tiposdecambio'] = AccountingPlan::tipocambio();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['view'] = link_view('Maestros', 'Costos', 'Plan de Cuentas', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('accountingplan.create', $data);

    }

    public function edit($id)
    {
        $data['var'] = $this->var;
        $data['plancuentas'] = AccountingPlan::findOrFail($id);
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['currencies'] = Currency::all();
        $data['clases'] = AccountingPlan::clases();
        $data['auxiliares'] = AccountingPlan::auxiliares();
        $data['naturalezas'] = AccountingPlan::naturalezas();
        $data['ajustes'] = AccountingPlan::ajustes();
        $data['tiposdecambio'] = AccountingPlan::tipocambio();
        $data['view'] = link_view('Maestros', 'Costos', 'Plan de Cuentas', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['plancuentas']->estado == 1 ? 'Activo' : 'Inactivo', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('accountingplan.edit', $data);
    }

    public function store(MaestroRequest $request)
    {
        DB::beginTransaction();

        try {
            $plancuentas = new AccountingPlan();
            $plancuentas->codigo = $request->code_pcg;
            $plancuentas->descripcion = $request->descripcion;

            if($request->es_divisionaria){
                $plancuentas->es_titulo = 1;
            }else if(!$request->es_analisis){
                $plancuentas->es_titulo = 0;
            }

            if($request->es_analisis){
                $plancuentas->es_titulo = 2;
            }else if(!$request->es_divisionaria){
                $plancuentas->es_titulo = 0;
            }

            $plancuentas->pide_ccosto = validaCheck($request->pide_ccosto);
            $plancuentas->pide_op = validaCheck($request->pide_op);

            $plancuentas->tipo_cuenta = $request->tipo_cuenta == null ? '' : $request->tipo_cuenta;
            $plancuentas->dbalance = $request->dbalance == null ? '' : $request->dbalance;
            $plancuentas->estado = 1;
            $plancuentas->moneda_id = $request->moneda;
            $plancuentas->tipo_auxiliar = $request->tipo_auxiliar;
            $plancuentas->tipo_ajuste = $request->tipo_ajuste;
            $plancuentas->tipo_cambio = $request->tipo_cambio == null ? '' : $request->tipo_cambio;

            $plancuentas->ctacargo_id = $request->ctacargo_id;
            $plancuentas->ctaabono_id = $request->ctaabono_id;
            $plancuentas->save();

            DB::commit();
            $data["ruta"] = route('edit.accountingplans', $plancuentas->id);

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;

    }

    public function update(MaestroRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $plancuentas = AccountingPlan::findOrFail($id);

            $plancuentas->codigo = $request->code_pcg;
            $plancuentas->descripcion = $request->descripcion;

            if($request->es_divisionaria){
                $plancuentas->es_titulo = 1;
            }

            if($request->es_analisis){
                $plancuentas->es_titulo = 2;
            }

            $plancuentas->pide_ccosto = validaCheck($request->pide_ccosto);
            $plancuentas->pide_op = validaCheck($request->pide_op);

            $plancuentas->tipo_cuenta = $request->tipo_cuenta == null ? '' : $request->tipo_cuenta;
            $plancuentas->dbalance = $request->dbalance == null ? '' : $request->dbalance;
            $plancuentas->estado = 1;
            $plancuentas->moneda_id = $request->moneda;

            $plancuentas->tipo_auxiliar = $request->tipo_auxiliar;
            $plancuentas->tipo_ajuste = $request->tipo_ajuste;
            $plancuentas->tipo_cambio = $request->tipo_cambio == null ? '' : $request->tipo_cambio;

            $plancuentas->ctacargo_id = $request->ctacargo_id;
            $plancuentas->ctaabono_id = $request->ctaabono_id;
            $plancuentas->save();

            DB::commit();

            $data["success"] = "Se guardaron los cambios correctamente";

        }catch(\Exception $e){
            DB::rollback();
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;
    }

    public function pcgs(Request $request)
    {
        $cuentas = AccountingPlan::select2($request->term);
        return $cuentas;
    }

}
