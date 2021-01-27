<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Panel\Companies\Entities\Pempresa;
use App\Documents\Entities\CommercialCountable;
use App\Families\Entities\Familia;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
//use App\SalesTypes\Collections\AccountTypeSaleCollection;
use App\SalesTypes\Entities\FamilySaleType;
use App\SalesTypes\Entities\SaleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class AccountsTypesSalesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'accountsTypesSales';
    private $accountTypeSaleCollection;
    protected $user;
    protected $privilegios = '03.01.05';

    public function __construct()
    {
        $this->obtener_cliente();
//        $this->accountTypeSaleCollection = $accountTypeSaleCollection;
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
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Cuentas por Tipo de Venta','');

        return view('accountsTypesSales.list', $data);
    }

    public function list()
    {
        $variable = SaleType::where('estado', 1)->get();
        return DataTables::of($variable)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $variable = SaleType::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['sale'] = $variable;
        //extraemos los datos del detalle
        $data['FamilySaleType'] = FamilySaleType::obtener_data($id);
        ////
        //agragar los nuevos registros de la tabla tipo transaccion a la lista
        $condiciones = array();
        foreach ($data['FamilySaleType'] as $key => $value ){
            array_push($condiciones, " id != ".$value->familiapdto_id);
        }
        $condiciones2 = "";
        if (count($condiciones) > 0){
            $condiciones2 .= " and ". implode(" and ",$condiciones);
        }
        $data['familia'] = $this->lista_familia($condiciones2);
        $data['header'] = headeroptions($this->var, 'edita', '', ($variable->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Cuentas por Tipo de Venta','');

        return view('accountsTypesSales.edit', $data);
    }

    public function lista_familia($condiciones2){
        //$tipo_familia = FamilySaleType::obtener_data($id);

        $lcNomec = Pempresa::select('valor')->where('parametro', 'FAM_NOMENC')->get();
        $lnCta = 0;
        for ($i=1; $i < strlen($lcNomec); $i++){
            if (substr($lcNomec, $i, 1) <> '-'){
                $lnCta = $lnCta + intval(substr($lcNomec, $i, 1));
            }
        }

        $familia = Familia::whereRaw('length(codigo) = '.$lnCta.$condiciones2)->get();
        return $familia;
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
            foreach ($request->familiapdto_id as $tipo => $resul){
                $FamilySaleType = FamilySaleType::where('tipoventa_id', $id)->where('familiapdto_id', $resul)->exists();
                if ($FamilySaleType){
                    $data['cuenta_id'] = ($request->N[$tipo] == '') ? null : $request->N[$tipo];
                    $data['ctacosto_id'] = ($request->E[$tipo] == '') ? null : $request->E[$tipo];
                    FamilySaleType::update_data($id,$resul,$data);
                }else{
                    $type = new FamilySaleType();
                    $type->tipoventa_id = $id;
                    $type->familiapdto_id = $resul;
                    $type->cuenta_id = ($request->N[$tipo] == '') ? null : $request->N[$tipo];
                    $type->ctacosto_id = ($request->E[$tipo] == '') ? null : $request->E[$tipo];
                    $type->usuario = Session::get('usuario');
                    $type->save();
                }
            }
            $r["ruta"] = route('edit.accounts', $id);
            $r["success"] = "Se guardaron los cambios correctamente";
        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = 'edita';

        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    function destroy($id)
    {
        //
    }

    public function buscar_accounts(Request $request){
        $resultado = AccountingPlan::select2_sales($request->term);
        return $resultado;
    }
}
