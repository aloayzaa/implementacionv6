<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Documents\Collections\AccountDocumentSaleCollection;
use App\Documents\Entities\Commercial;
use App\Documents\Entities\CommercialCountable;
use App\Http\Traits\Controls\UserPrivileges;
use App\TransactionType\Entities\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class DocumentAccountSaleController extends Controller
{
    use UserPrivileges;
    private $var = 'accountsDocumentsSales';
    private $accountDocumentSaleCollection;

    protected $user;
    protected $privilegios = '03.01.04';

    public function __construct(AccountDocumentSaleCollection $accountDocumentSaleCollection)
    {
        $this->obtener_cliente();
        $this->accountDocumentSaleCollection = $accountDocumentSaleCollection;
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
        $data['view'] = link_view('Ventas', 'Configuración', 'Cuentas por Documento','');

        return view('accountsDocumentsSales.list', $data);
    }

    public function list()
    {
        /*$accountsDocumentsSales = Commercial::where('origen','A')->get();
        $this->accountDocumentSaleCollection->actions($accountsDocumentsSales, 'edit.accountsDocumentsSales');

        return DataTables::of($accountsDocumentsSales)->rawColumns(['actions', 'estado'])->make();*/

        $accounts = Commercial::where('origen','C')->orWhere('origen','A')->get();
        return DataTables::of($accounts)->make();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $docum = Commercial::findOrFail($id);
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['route'] = route('accounts');
        $data['header'] = headeroptions($this->var, 'edita', '', ($docum->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['commercial'] = $docum;
        //los datos que ya existen en la tabla detalle
        $data['CommercialCountable'] = CommercialCountable::consulta_document_conta($id);
        //agragar los nuevos registros de la tabla tipo transaccion a la lista
        $condiciones = array();
        foreach ($data['CommercialCountable'] as $aa => $aaa ){
            array_push($condiciones, " id != ".$aaa->tipotransaccion_id);
        }
        $condiciones2 = "";
        if (count($condiciones) > 0){
            $condiciones2 .= "and ". implode(" and ",$condiciones);
        }
        $data['TransactionType'] = TransactionType::consulta_devolver($condiciones2);
        $data['view'] = link_view('Ventas', 'Configuración', 'Cuentas por Documento','');

        return view('accountsDocumentsSales.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*try {
            $commercialCountables = CommercialCountable::where('documentocom_id', $id)->get();
            $i = 0;

            foreach ($commercialCountables as $commercialCountable) {
                $i++;
                $commercialCountable->documentocom_id = $request->id;
                $commercialCountable->tipotransaccion_id = $i;
                if ($i == 1) {
                    if ($request->N1 != 0 && $request->E1 != 0) {
                        $commercialCountable->ccuentamn_id = $request->N1;
                        $commercialCountable->ccuentame_id = $request->E1;
                    } else {
                        $commercialCountable->ccuentamn_id = null;
                        $commercialCountable->ccuentame_id = null;
                    }
                } else {
                    if ($request->N2 != 0 && $request->E2 != 0) {
                        $commercialCountable->ccuentamn_id = $request->N2;
                        $commercialCountable->ccuentame_id = $request->E2;
                    } else {
                        $commercialCountable->ccuentamn_id = null;
                        $commercialCountable->ccuentame_id = null;
                    }
                }
                $commercialCountable->usuario = Auth::user()->usu_usuario;

                DB::transaction(function () use ($commercialCountable) {
                    $commercialCountable->save();
                });
            }

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = 'edita';*/
        try {
            foreach ($request->tipotransaccion as $tipo => $resul){
                $commercialCountables = CommercialCountable::where('documentocom_id', $id)->where('tipotransaccion_id', $resul)->exists();
                if ($commercialCountables){
                    //$type->documentocom_id = $id;
                    //$data['tipotransaccion_id'] = $resul;
                    $data['pcuentamn_id'] = ($request->N[$tipo] == '') ? null : $request->N[$tipo];
                    $data['pcuentame_id'] = ($request->E[$tipo] == '') ? null : $request->E[$tipo];
                    CommercialCountable::update_data($id,$resul,$data);
                }else{
                    $type = new CommercialCountable();
                    $type->documentocom_id = $id;
                    $type->tipotransaccion_id = $resul;
                    $type->pcuentamn_id = ($request->N[$tipo] == '') ? null : $request->N[$tipo];
                    $type->pcuentame_id = ($request->E[$tipo] == '') ? null : $request->E[$tipo];
                    $type->usuario = Session::get('usuario');
                    $type->save();
                }
            }
            $r["ruta"] = route('edit.accounts', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

            return $r;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function buscar_accounts(Request $request){
        $resultado = AccountingPlan::select2($request->term);
        return $resultado;
    }
}
