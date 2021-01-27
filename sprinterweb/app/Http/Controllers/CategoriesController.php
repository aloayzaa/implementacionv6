<?php

namespace App\Http\Controllers;


use App\Categories\Collections\CategoriesCollection;
use App\Categories\Entities\Categories;
//use App\Http\Requests\StoreRequest;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;


class CategoriesController extends Controller
{
    protected $var = 'categories';
    private $categoriescollection;


    public function __construct(CategoriesCollection $categoriescollection)
    {
        $this->obtener_cliente();
        $this->categoriescollection = $categoriescollection;
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Activos', 'Configuracion', 'Categorias', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');
        return view('categories.list', $data);
    }

    public function list()
    {
        $categorias = Categories::listado();
        $this->categoriescollection->actions($categorias);
        return DataTables::of($categorias)->rawColumns(['estado', 'actions'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['route'] = route('categories');
        $data['view'] = link_view('Activos', 'Configuracion', 'Categorias', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');
        return view('categories.create', $data);
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

////        $this->validate($request, [
////                'codigo' => 'unique:categactivo|required',
//            ]);

            $categoria = new Categories();
            $categoria->codigo = $request->code;
            $categoria->descripcion = $request->name;
            $categoria->codsunat = $request->codSunat;
            $categoria->simbolo = $request->codSimbolo;
            $categoria->usuario = Auth::id();

            DB::transaction(function () use ($categoria, $request) {
                $categoria->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Datos no válidos";
            $r["bd"] = $e->getMessage();
        }
        $r["proceso"] = 'crea';

        return $r;
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
        $data['categories'] = categories::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['view'] = link_view('Activos', 'Configuracion', 'Categorias', '');
        $data['header'] = headeroptions($this->var, 'edita', '', '');

        return view('categories.edit', $data);
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
            $categories = Categories::findOrFail($id);
            $categories->codigo = $request->code;
            $categories->descripcion = $request->name;
            $categories->codsunat = $request->codSunat;
            $categories->simbolo = $request->codSimbolo;
            $categories->usuario = Auth::id();

            DB::transaction(function () use ($categories) {
                $categories->save();
            });

            $r['estado'] = 'ok';
            $r['mensaje'] = 'Editado Correctamente';

        } catch (PDOException $e) {
            $r['estado'] = 'error';
            $r['mensaje'] = 'Error al Editar!';
            $r['bd'] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

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

    }
}
