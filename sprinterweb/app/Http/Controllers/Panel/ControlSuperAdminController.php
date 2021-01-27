<?php

namespace app\Http\Controllers\Panel;

use App\Accounting\Entities\Estudio;
use App\Companies\Entities\Empresa;
use App\Http\Controllers\Controller;
use App\Panel\Accounting\Collections\AccountingCollection;
use App\Suscriptions\Entities\Suscripcion;
use App\Users\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class ControlSuperAdminController extends Controller
{
    private $var = 'superadmin';
    private $accountingcollection;

    public function __construct(AccountingCollection $accountingcollection)
    {
        $this->accountingcollection = $accountingcollection;
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
        $data['proceso'] = $request->proceso;
        $data['view'] = link_view('Panel', 'Empresa Principal', '', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('controlsuperadmin.list', $data);
    }

    public function list()
    {
        $empresas = Empresa::listado();
        $this->accountingcollection->actions($empresas);
        return DataTables::of($empresas)->rawColumns(['empresas', 'usuarios', 'suscripcion', 'actions', 'emp_estado'])->make();
    }

    public function indexcompanies(Request $request, $id_empresa)
    {
        $data['id_empresa'] = $id_empresa;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Panel', 'Empresas por Empresa Principal', '', '');
        $array = ["id_empresa" => $id_empresa];
        $data['header'] = headeroptions('empresas', 'list', $array, '');

        return view('controlsuperadmin.listcompanies', $data);
    }

    public function listcompanies($id_empresa)
    {
        $companies = Empresa::listado_empresas_x_empresaprincipal($id_empresa);
        $this->actions_companies($companies);
        return DataTables::of($companies)->rawColumns(['sprinter'])->make();
    }

    public function indexusers(Request $request, $id_empresa)
    {
        $data['id_empresa'] = $id_empresa;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Panel', 'Usuarios por Empresas', '', '');
        $array = ["id_empresa" => $id_empresa];
        $data['header'] = headeroptions('users', 'list', $array, '');

        return view('controlsuperadmin.listusers', $data);
    }

    public function listusers($id_empresa)
    {
        $users = User::lista_usuarios_por_empresa($id_empresa);
        return DataTables::of($users)->make();
    }

    public function indexsuscriptions($id_estudio)
    {
        $data['id_estudio'] = $id_estudio;
        $data['view'] = link_view('Panel', 'Suscripciones por Empresas', '', '');
        $data['header'] = headeroptions('subscriptions', '', '', '');

        return view('controlsuperadmin.listsuscriptions', $data);
    }

    public function listsuscriptions($id_estudio)
    {
        $suscripciones = Suscripcion::where('est_id', $id_estudio);
        $this->actions_suscriptions($suscripciones);
        return DataTables::of($suscripciones)->rawColumns(['sus_estado', 'actions'])->make();
    }

    private function actions_companies($companies)
    {
        foreach ($companies as $company) {
            $company->sprinter = '<a href="' . route('conexion.panel', ['id_empresa' => $company->id]) . '"
                        data-toggle="tooltip" data-placement="left" title="Sprinter" class="icon-button pointSale" id="btnPoint">
                        <i class="fa fa-laptop"></i><span></span></a>';
        }
    }

    private function actions_suscriptions($suscripciones)
    {
        foreach ($suscripciones as $suscripcion) {

            $suscripcion->actions = '<div class="btn-group">
                                    <div class="btn-group">
                                       <a data-toggle="dropdown" class="icon-button dropdownn dropdown-toggle "> 
                                            <i class="fa fa-angle-double-down"></i><span></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right">';
            if ($suscripcion->sus_estado == 1) {
                $suscripcion->actions .= '<li>
                                    <a onclick="procesar_actualizar(' . $suscripcion->id . ', 0)">Anular <span class="fa fa-close text-danger"></span></a>
                                </li>';
            } else {
                $suscripcion->actions .= '<li>
                                    <a onclick="procesar_actualizar(' . $suscripcion->id . ', 1)">Activar <span class="fa fa-check text-success"></span></a>
                                </li>';
            }
            $suscripcion->actions .= '</ul> </div> </div>';


            if ($suscripcion->sus_estado == 1) {
                $suscripcion->sus_estado = '<span class="label label-success">Activo</span>';
            } elseif ($suscripcion->sus_estado == 0) {
                $suscripcion->sus_estado = '<span class="label label-danger">Desactivado</span>';
            } elseif ($suscripcion->sus_estado == 2) {
                $suscripcion->sus_estado = '<span class="label label-warning">Pendiente</span>';
            }
        }
    }

    public function activar_anular(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Estudio::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
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
        //
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
        //
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
