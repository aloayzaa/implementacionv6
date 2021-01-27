<?php

namespace App\Http\Controllers;

use App\Bank\Collections\BanksOpeningCollection;
use App\Bank\Entities\Bank;
use App\Bank\Entities\BanksOpening;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Period\Entities\Period;
use App\Subsidiaries\Entities\Subsidiaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class BanksOpeningController extends Controller
{
    protected $var = 'banksopening';
    private $banksopeningcollection;
    private $ventana = 'MOV_APERTURABANCO';

    public function __construct(BanksOpeningCollection $banksopeningcollection)
    {
        $this->obtener_cliente();
        $this->banksopeningcollection = $banksopeningcollection;
    }

    public function list()
    {
        $bancos = BanksOpening::apertura_de_bancos();
        $this->banksopeningcollection->actions($bancos);

        return DataTables::of($bancos)->rawColumns(['actions', 'estado'])->make();
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
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Apertura de Cajas y Banco','');

        return view('banksopening.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $data['route'] = route('banksopening');
        $data['sucursales'] = Subsidiaries::all();
        $data['var'] = $this->var;
        $data['bancos'] = Bank::all();
        $data['clientes'] = Customer::all();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Apertura de Cajas y Banco','');

        return view('banksopening/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $docbanco_apertura = new BanksOpening();
            $docbanco_apertura->sucursal_id = $request->subsidiary;
            $docbanco_apertura->periodo_id = Session::get('period_id');
            $docbanco_apertura->numero = $request->txt_numero;
            $docbanco_apertura->fechaproceso = $request->processdate;
            $docbanco_apertura->tcambio = $request->changerate;
            $docbanco_apertura->banco_id = $request->bank;
            $docbanco_apertura->ctactebanco_id = $request->currentaccount;
            $docbanco_apertura->moneda_id = $request->currency;
            $docbanco_apertura->tcmoneda = $request->txt_tc;
            $docbanco_apertura->total = $request->total;
            $docbanco_apertura->totalmn = $request->totalmn;
            $docbanco_apertura->totalme = $request->totalme;
            $docbanco_apertura->tercero_id = $request->customer;
            $valor_checked = validaCheck($request->check);
            $docbanco_apertura->concheque = $valor_checked;
            $docbanco_apertura->estado = 1;

            if ($valor_checked == 1) {
                $docbanco_apertura->nrocheque = $request->checktxt;
            } else {
                $docbanco_apertura->nrocheque = '';
            }

            $docbanco_apertura->glosa = $request->comment;
            $docbanco_apertura->usuario = Auth::id();
            $docbanco_apertura->ventana = $this->ventana;

            DB::transaction(function () use ($docbanco_apertura) {
                $docbanco_apertura->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r['bd'] = $e->getMessage();
        }

        $r["proceso"] = 'crea';

        return $r;
    }

    public function ctacte(Request $request)
    {
        $ctacte = BankCurrentAccount::where('banco_id', $request->bank)->get();
        return $ctacte;
    }

    public function currency(Request $request)
    {
        $ctacte = BankCurrentAccount::findOrFail($request->currentaccount);
        $currency = Currency::findOrFail($ctacte->moneda_id);
        return $currency;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['route'] = route('banksopening');
        $data['docbanco_apertura'] = BanksOpening::findOrFail($id);
        $data['ctactebanks'] = BankCurrentAccount::where('banco_id', $data['docbanco_apertura']->banco_id)->get();
        $data['sucursales'] = Subsidiaries::all();
        $data['bancos'] = Bank::all();
        $data['clientes'] = Customer::all();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['currencies'] = Currency::all();
        $data['view'] = link_view('Tesoreria', 'Transacción', 'Apertura de Cajas y Banco','');

        return view('banksopening.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $docbanco_apertura = BanksOpening::findOrFail($id);
            $docbanco_apertura->sucursal_id = $request->subsidiary;
            $docbanco_apertura->periodo_id = Session::get('period_id');
            $docbanco_apertura->numero = $request->txt_numero;
            $docbanco_apertura->fechaproceso = $request->processdate;
            $docbanco_apertura->tcambio = $request->changerate;
            $docbanco_apertura->banco_id = $request->bank;
            $docbanco_apertura->ctactebanco_id = $request->currentaccount;
            $docbanco_apertura->moneda_id = $request->currency;
            $docbanco_apertura->tcmoneda = $request->txt_tc;
            $docbanco_apertura->total = $request->total;
            $docbanco_apertura->totalmn = $request->totalmn;
            $docbanco_apertura->totalme = $request->totalme;
            $docbanco_apertura->tercero_id = $request->customer;
            $valor_checked = validaCheck($request->check);
            $docbanco_apertura->concheque = $valor_checked;

            if ($valor_checked == 1) {
                $docbanco_apertura->nrocheque = $request->checktxt;
            } else {
                $docbanco_apertura->nrocheque = '';
            }

            $docbanco_apertura->glosa = $request->comment;
            $docbanco_apertura->ventana = $this->ventana;
            $docbanco_apertura->usuario = Auth::id();

            DB::transaction(function () use ($docbanco_apertura) {
                $docbanco_apertura->save();
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al editar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
                BanksOpening::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
