<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Companies\Entities\Pempresa;
use App\CostByFamily\CostByFamily;
use App\Costs\Entities\Costs;
use App\Documents\Entities\CommercialCountable;
use App\Families\Collections\FamilyCollection;
use App\Families\Entities\Familia;
use App\FamilyAccountingProduct\Entities\FamilyAccountingProduct;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use mysql_xdevapi\Collection;
use Yajra\DataTables\Facades\DataTables;

class AccountingCentralizationWarehouseController extends Controller
{
    use Tables, UserPrivileges;

    protected $tabla_cabecera = "familiapdto_contab";
    private $var = 'accountingctnwarehouse';
    protected $user;
    protected $privilegios = '02.01.02';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Almacenes', 'Configuración', 'Centralización Contable', '');
        $data['header'] = headeroptions($this->var, 'list-no-crea', '', '',  $this->privilegios(), $this->user); //definir un nuevo hedaer para list sin nuevo
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('accountingCtnWarehouse.list', $data);
    }

    public function list()
    {
        $ACWarehouse = Familia::all();
        $conf = Pempresa::where('parametro', 'FAM_NOMENC')->first();
        $index = explode('-', $conf->valor);

        $digitos = $index[0] + $index[1];

        $filtered = $ACWarehouse->filter(function ($value, $key) use($digitos) {
            return strlen($value->codigo) == $digitos;
        });


        return DataTables::of($filtered)->make();
    }

    public function edit($id)
    {
        $familia = Familia::findOrFail($id);

        $data['familia'] = $familia;

        $data['existencias'] = AccountingPlan::elemento('2')->elemento('3')->get();
        $data['compras'] = AccountingPlan::elemento('60')->get();
        $data['variacionexistencias'] = AccountingPlan::elemento('61')->get();
        $data['transferencias'] = AccountingPlan::elemento('28')->get();
        $data['consumos'] = AccountingPlan::elemento('25')->get();

        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Almacenes', 'Configuración', 'Centralizacion Contable', '');

        $data['familia_cuenta'] = FamilyAccountingProduct::find($id); //FamilyAccountingProduct::where('parent_id', $id)->get();

        $centrocosto = Costs::all();
        $data['centroscosto'] = $centrocosto;
      //  $costs = Costs::find(1);

     //   dd($costs->ccostoxfamilia);
        $a = [];
        $collect = collect($a);
        $multiplied = $centrocosto->map(function ($item, $key) use ($familia, $collect) {
            return collect($item)->push($item->ccostoxfamilia->filter(function ($value, $key) use ($familia) {
                return $value->familiapdto_id == $familia->id;
            })->pluck('id'));
        });




        $data['ccosto_family'] = CostByFamily::where('familiapdto_id', 11)->get();


        $data['consulta'] = CommercialCountable::consulta_document_conta($id);
        $condiciones = array();
        foreach ($data['consulta'] as $aa => $aaa ){
            array_push($condiciones, " id != ".$aaa->tipotransaccion_id);
        }

        return view('accountingCtnWarehouse.edit', $data);
    }


    public function store(Request $request)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $existe = FamilyAccountingProduct::where("parent_id", $id)->exists();
        //  dd($consulta);
        if( !$existe) { //!! no existe
            $familiapdto_contab = new FamilyAccountingProduct();
            $familiapdto_contab->parent_id = $id;
            $familiapdto_contab->cta20_id = $request->txt_existencias;
            $familiapdto_contab->cta25_id = $request->txt_consumo;
            $familiapdto_contab->cta60_id = $request->txt_compras;
            $familiapdto_contab->cta61_id = $request->txt_val_existencias;
            $familiapdto_contab->cta28_id = $request->txt_transferencia;
            $familiapdto_contab->usuario = Session::get('usuario');
            $familiapdto_contab->save();

            $data["success"] = "Se guardaron los cambios correctamente";
            return $data;
        }
        else{
            //Editando

            $data['cta20_id'] = $request->txt_existencias;
            $data['cta25_id'] = $request->txt_consumo;
            $data['cta60_id'] = $request->txt_compras;
            $data['cta61_id'] = $request->txt_val_existencias;
            $data['cta28_id'] = $request->txt_trasnferencia;

            FamilyAccountingProduct::saveDetails($id, $data);

            $data["success"] = "Se guardaron los cambios correctamente";
            return $data;
        }

    }

    public function destroy($id)
    {
        //
    }
}
