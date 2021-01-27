<?php

namespace App\Http\Controllers;

use App\NoHabido\Collections\NoHabidosCollection;
use App\NoHabido\Entities\Nohabido;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NoHabidosController extends Controller
{
    protected $var = 'nohabidos';
    private $nohabidoscollection;

    public function __construct(NoHabidosCollection $nohabidoscollection)
    {
        $this->nohabidoscollection = $nohabidoscollection;
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
        $data['route'] = $request->route;
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Contribuyentes Renuncia Exoneración I.G.V.','');

        return view('nohabidos.list', $data);
    }

    public function list()
    {
        $nohabido = Nohabido::lista_nohabidos();
        $this->nohabidoscollection->actions($nohabido);

        return DataTables::of($nohabido)->rawColumns(['estado'])->make();
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
