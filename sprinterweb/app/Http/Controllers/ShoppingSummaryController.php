<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ShoppingSummaryController extends Controller
{
    use UserPrivileges;
    private $var = 'shoppingsummary';
    protected $privilegios = '04.04.04';
    protected $user;

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
    public function index()
    {
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['var'] = $this->var;
        $data['view'] = link_view('Compras', 'Reportes', 'Resumen de Compras','');
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('shoppingsummary.list', $data);
    }

    /*public function list(Request $request)
    {
        $periodo = Period::findOrFail(Session::get('period_id'));

        $fecha_inicio = $request->initialdate;
        $fecha_fin = $periodo->final;

        if (isset($request->initialdate)){
            $resultado = ProcedureReports::rptResumenCompra($fecha_inicio, $fecha_fin, 0, $request->type);
        }else{
            $resultado = collect(null);
        }


        return Datatables::of($resultado)->make();
    }*/

    public function rptcompras(Request $request){
        try {
            $pDesde = $request->lcDesde;
            $pHasta = $request->lcHasta;
            $pPeriodo = $request->lcPeriodo;
            $pTipo = $request->lctipogeneral;
            $resultado = null;
            switch ($pTipo){
                case 'D':
                case 'M': $resultado = ProcedureReports::rptDetalleCompra($pDesde, $pHasta, $pTipo); break;
                case 'R':
                    $resultado = ProcedureReports::rptResumenCompra($pDesde, $pHasta, $pPeriodo, $request->lctipo);
                    $periodos = $this->periodos();
                    if ($request->check == 'on'){
                        $data_all = array();
                        $data_unique = array();
                        $data_uniques = array();
                        $complents = array();
                        if ($request->lctipo !== 'A'){
                            $i= 0;
                            foreach ($resultado as $key => $value){
                                $data_all += [$i=>['periodo'=>$value->periodo,
                                        'codigo' => $value->codigo,
                                        'descripcion' => $value->descripcion,
                                        'cantidad' => $value->cantidad,
                                        'basemn' => $value->basemn,
                                        'baseme' => $value->baseme,
                                        'igvmn' => $value->igvmn,
                                        'igvme' => $value->igvme,
                                        'otrosmn' => $value->otrosmn,
                                        'otrosme' => $value->otrosme,
                                        'totalmn' => $value->totalmn,
                                        'totalme' => $value->totalme,
                                        'familia_cod' => $value->familia_cod,
                                        'familia_dsc' => $value->familia_dsc
                                        ]
                                    ];

                                $data_unique += [$value->codigo=>['periodo'=>$value->periodo,
                                        'codigo' => $value->codigo,
                                        'descripcion' => $value->descripcion,
                                        'cantidad' => $value->cantidad,
                                        'basemn' => $value->basemn,
                                        'baseme' => $value->baseme,
                                        'igvmn' => $value->igvmn,
                                        'igvme' => $value->igvme,
                                        'otrosmn' => $value->otrosmn,
                                        'otrosme' => $value->otrosme,
                                        'totalmn' => $value->totalmn,
                                        'totalme' => $value->totalme,
                                        'familia_cod' => $value->familia_cod,
                                        'familia_dsc' => $value->familia_dsc
                                        ]
                                    ];

                                //if ($value->codigo === $data_unique[$value->codigo]['codigo']){
                                $miguel = $this->conversion($periodos, $value, $data_all[$key]);
                                $data_uniques += [$value->codigo=>['periodo'=>$value->periodo,
                                    'codigo' => $value->codigo,
                                    'descripcion' => $value->descripcion,
                                    'cantidad' => $value->cantidad,
                                    'basemn' => $value->basemn,
                                    'baseme' => $value->baseme,
                                    'igvmn' => $value->igvmn,
                                    'igvme' => $value->igvme,
                                    'otrosmn' => $value->otrosmn,
                                    'otrosme' => $value->otrosme,
                                    'totalmn' => $value->totalmn,
                                    'totalme' => $value->totalme,
                                    'familia_cod' => $value->familia_cod,
                                    'familia_dsc' => $value->familia_dsc,
                                    $miguel
                                ]
                                ];

                                    /*$data_uniques += [$value->codigo=>['periodo'=>$value->periodo,
                                        'codigo' => $value->codigo,
                                        'descripcion' => $value->descripcion,
                                        'cantidad' => $value->cantidad,
                                        'basemn' => $value->basemn,
                                        'baseme' => $value->baseme,
                                        'igvmn' => $value->igvmn,
                                        'igvme' => $value->igvme,
                                        'otrosmn' => $value->otrosmn,
                                        'otrosme' => $value->otrosme,
                                        'totalmn' => $value->totalmn,
                                        'totalme' => $value->totalme,
                                        'familia_cod' => $value->familia_cod,
                                        'familia_dsc' => $value->familia_dsc,
                                        $complents
                                    ]
                                    ];*/
                                //}

                                $i++;
                            }
                            $resultado += $data_uniques;
                            dd($data_uniques);
                        }
                    }

                    break;
                case 'L': $resultado = ProcedureReports::rptComprasRegistro($pDesde, $pHasta); break;
                case '4': $resultado = ProcedureReports::rptComprasRetencion($pDesde, $pHasta, $pTipo); break;
            }
            $r = [$resultado, $periodos];
        }catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }
    public function conversion($periodos, $value, $data_unique){
        foreach ($periodos as $period => $periodo) {
            if ($value->periodo !== $data_unique['periodo']){
                return $res[]= $periodo->periodo.'mn =>'.$value->basemn.",". $periodo->periodo."me => ". $value->baseme;
            }
        }
    }

    public function periodos(){
        return Period::select('codigo as periodo')->where('estado', 1)->get();
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
