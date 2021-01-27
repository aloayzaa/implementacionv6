<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\TransactionType\Entities\TransactionType;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
class TransactionTypesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'transactiontypes';
    private $tabla_cabecera = 'tipotransaccion';
    protected $user;
    protected $privilegios = '01.02.07';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $transactiontypes = TransactionType::all();
        return Datatables::of($transactiontypes)->make();
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Transacción','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('transactiontypes.list', $data);
    }

    public function create()
    {
        $transaction = TransactionType::all()->where('estado', 1)->last();
        if ($transaction->codigo < 10){
            $data['codigo'] = str_pad($transaction->codigo +1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $transaction->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Transacción','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('transactiontypes.create', $data);
    }

    public function store(MaestroRequest $request)
    {

        try {
            $tipotransaccion = new TransactionType();
            $tipotransaccion->codigo = $request->txt_codigo_tipo_trans;
            $tipotransaccion->descripcion = $request->txt_descripcion_tipo_trans;
            $tipotransaccion->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $tipotransaccion->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$tipotransaccion->tipo = $request->;
            $tipotransaccion->estado = 1;
            $tipotransaccion->usuario = Session::get('usuario');

            $id = null;
            $id = DB::transaction(function () use ($tipotransaccion) {
                $tipotransaccion->save();
                $id = $tipotransaccion->id;
                return $id;
            });
            $r["ruta"] = route('edit.transactiontypes', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    public function edit($id)
    {
        $transactions = TransactionType::findOrFail($id);
        $data['tipotransaccion'] = $transactions;
        $data['proceso'] = 'edita';
        $data['route'] = route('transactiontypes');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Tipo de Transacción','');
        $data['header'] = headeroptions($this->var, 'edita','',($transactions->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('transactiontypes.edit', $data);
    }

    public function update(MaestroRequest $request, $id)
    {
        try {

            $tipotransaccion = TransactionType::findOrfail($id);
            $tipotransaccion->codigo = $request->txt_codigo_tipo_trans;
            $tipotransaccion->descripcion = $request->txt_descripcion_tipo_trans;
            $tipotransaccion->codsunat = ($request->txt_cod_sunat == '') ? '' : $request->txt_cod_sunat;
            $tipotransaccion->simbolo = ($request->txt_simbolo == '') ? '' : $request->txt_simbolo;
            //$tipotransaccion->tipo = $request->;
            $tipotransaccion->estado = 1;
            $tipotransaccion->usuario = Session::get('usuario');
            $id = null;
            $id = DB::transaction(function () use ($tipotransaccion) {
                $tipotransaccion->save();
                $id = $tipotransaccion->id;
                return $id;
            });

            $r["ruta"] = route('edit.transactiontypes', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }
}
