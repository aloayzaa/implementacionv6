<?php

namespace App\Http\Controllers;
use App\Assets\Collections\AssetsCollection;
use App\Assets\Entities\Assets;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class AssetsController extends Controller
{

    protected $var = 'assets';
    private $assetscollection;

    public function __construct( AssetsCollection  $assetscollection)
    {
        $this->obtener_cliente();
        $this-> assetscollection = $assetscollection ;
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
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Activos', 'Configuracion', 'Activos','');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('assets.list', $data);
    }

    public function list()
    {
        $activos = Assets::all();
        $this->assetscollection->actions($activos);
        return DataTables::of($activos)->rawColumns(['estado', 'actions'])->make();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        //
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
        //
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
}
