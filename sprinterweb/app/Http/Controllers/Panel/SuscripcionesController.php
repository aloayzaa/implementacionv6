<?php

namespace app\Http\Controllers\Panel;

use App\Companies\Entities\Empresa;
use App\Panel\Suscriptions\Collections\SuscriptionCollection;
use App\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Suscriptions\Entities\Suscripcion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PDOException;
use Yajra\DataTables\DataTables;

class SuscripcionesController extends Controller
{
    private $var = 'subscriptions';
    private $suscriptionColletction;

    public function __construct(SuscriptionCollection $suscriptionColletction)
    {
        $this->suscriptionColletction = $suscriptionColletction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['var'] = $this->var;
        $data['view'] = link_view('Panel', 'Suscripciones', '', '');
        $data['header'] = headeroptions('subscriptions', 'list', '', '');

        return view('subscriptions.list', $data);
    }


    public function list()
    {
        $subscriptions = Suscripcion::listado(Session::get('id_empresa'));
        $this->suscriptionColletction->actions($subscriptions);

        return DataTables::of($subscriptions)->rawColumns(['comprobante', 'sus_estado'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id_estudio
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['empresas'] = Empresa::listado_empresas_x_empresaprincipal(Session::get('company'));
        $data['planes'] = Plan::where('ver_id', 3)->get();
        $data['view'] = link_view('Panel', 'Suscripciones', '', '');
        $data['header'] = headeroptions('subscriptions', 'crea', '', '');

        return view('subscriptions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $sus = Suscripcion::where('emp_id', $request->company)->get();
            $plan = Plan::findOrFail($request->plan);
            $meses = $plan->pla_nro_mes;

            foreach ($sus as $su) {
                if ($plan->ver_id == $su->ver_id) {
                    $value = true;
                    $id_suscripcion = $su->id;
                }
            }

            if ($value == true) {
                $suscripcion = Suscripcion::findOrFail($id_suscripcion);
                $finaldate = $suscripcion->sus_fechafin;
            } else {
                $suscripcion = new Suscripcion();
                $finaldate = date("Y-m-d");
            }

            if ($meses == 3) {
                $suscripcion->sus_fechafin = date('Y-m-d', strtotime("+3 months", strtotime($finaldate)));
            } elseif ($meses == 12) {
                $suscripcion->sus_fechafin = date('Y-m-d', strtotime("+12 months", strtotime($finaldate)));
            }

            $cod = Suscripcion::where('emp_id', $request->company)->get()->last();
            $codigo = sprintf('%05d', $cod->sus_codigo + 1);

            $suscripcion->sus_codigo = $codigo;
            $suscripcion->sus_estado = 2;
            $suscripcion->pla_id = $request->plan;
            $suscripcion->fop_id = 1;
            $suscripcion->emp_id = $request->company;
            $suscripcion->ver_id = 3;
            $file = $request->file('imagen');

            if ($file) {
                $nombre = $codigo . '_' . $request->estudio_id . '_' . $file->getClientOriginalName();
                Storage::disk('comprobantes')->put($nombre, file_get_contents($file));
                $suscripcion->sus_comprobante = $nombre;
            }

            DB::transaction(function () use ($suscripcion, $request) {
                $suscripcion->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
            $r["mensaje"] = "Datos no válidos";
        }

        $r['header'] = headeroptions('subscriptions', 'list', '', '');
        $r['view'] = link_view('Panel', 'Suscripciones', '', '');
        $r['var'] = $this->var;
        $r["proceso"] = 'crea';

        return view('subscriptions.list', $r);
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
        try {
            $suscripcion = Suscripcion::FindOrFail($id);
            $tiempo = $request->timeSuscription;
            $finaldate = date('Y-m-d', strtotime($suscripcion->sus_fechafin . $tiempo));
            $suscripcion->sus_fechafin = $finaldate;
            $suscripcion->sus_estado = 1;

            DB::transaction(function () use ($suscripcion) {
                $suscripcion->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Datos actualizados";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al actualizar";
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

    public function obtener_plan($id)
    {
        $plan = Plan::findOrFail($id);
        return $plan->pla_preciome;
    }
}
