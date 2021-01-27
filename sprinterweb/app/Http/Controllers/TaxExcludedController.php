<?php

namespace App\Http\Controllers;

use App\TaxExcluded\Collection\TaxExcludedCollection;
use App\TaxExcluded\Entities\TaxExcluded;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaxExcludedController extends Controller
{
    protected $var = 'taxexcluded';
    private $taxexcludedcollection;

    public function __construct(TaxExcludedCollection $taxexcludedcollection)
    {
        $this->taxexcludedcollection = $taxexcludedcollection;
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

        return view('nohabidos.list', $data);
    }

    public function list()
    {
        $taxexcluded = TaxExcluded::all();
        $this->taxexcludedcollection->actions($taxexcluded);
        return DataTables::of($taxexcluded)->rawColumns(['estado'])->make();
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
