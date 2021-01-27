<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    use CartTrait, Tables;
    protected $var = 'currencies';
    private $tabla_cabecera = 'moneda';


    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function list()
    {
        $currencies = Currency::all();
        return DataTables::of($currencies)->make();
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
        $data['route'] = $request->route;
        $data['proceso'] = $request->proceso;
        $data['view'] = link_view('Maestros', 'Otros', 'Monedas', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('currencies.list', $data);
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
        $data['route'] = route('currencies');
        $data['view'] = link_view('Maestros', 'Otros', 'Monedas', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('currencies.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {
        try {
            $currency = new Currency();
            $currency->codigo = $request->code_md;
            $currency->descripcion = $request->description_md;
            $currency->simbolo = $request->symbol;

            if (validaCheck($request->typen) == 1) {
                $type = 'N';
            }

            if (validaCheck($request->typee) == 1) {
                $type = 'E';
            }

            if (validaCheck($request->typeo) == 1) {
                $type = 'O';
            }

            $currency->tipo = $type;
            $currency->estado = 1;
            $currency->codsunat = $request->sunat;
            $currency->usuario = Auth::id();
            $id=null;
            $id=DB::transaction(function () use ($currency) {
                $currency->save();
                $id=$currency->id;
                return $id;
            });
            $r["ruta"] = route('edit.currencies', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

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
        $currencies = Currency::findOrFail($id);
        $data['currencies']= $currencies;
        $data['var'] = $this->var;
        $data['route'] = route('currencies');
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Maestros', 'Otros', 'Monedas', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($currencies->estado == 1) ? 'ACTIVO' : 'ANULADO');


        return view('currencies.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaestroRequest $request, $id)
    {
        try {
            $currency = Currency::findOrFail($id);
            $currency->codigo = $request->code_md;
            $currency->descripcion = $request->description_md;
            $currency->simbolo = $request->symbol;

            if (validaCheck($request->typen) == 1) {
                $type = 'N';
            }

            if (validaCheck($request->typee) == 1) {
                $type = 'E';
            }

            if (validaCheck($request->typeo) == 1) {
                $type = 'O';
            }

            $currency->tipo = $type;
            $currency->codsunat = $request->sunat;

            $id=null;

            $id=DB::transaction(function () use ($currency) {
                $currency->save();
                $id=$currency->id;
                return $id;
            });
            $r["ruta"] = route('edit.currencies', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
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
        //
    }

    public function activar(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Currency::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
