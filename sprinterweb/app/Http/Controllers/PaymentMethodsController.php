<?php

namespace App\Http\Controllers;

use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\PaymentMethod\Entities\PaymentMethod;
use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodsController extends Controller
{
    use Tables, UserPrivileges;
    private $var = 'paymentMethods';
    protected $tabla_cabecera = 'formapago';
    protected $user;
    protected $privilegios = '02.01.01';

    protected $messages = [
            'codigo.required' => 'El codigo es requerido',
            'codigo.unique' => 'El codigo ya existe',
            'descripcion.required' => 'La descripcion es requerida',
            'ctactebanco_id.required' => 'La cuenta asociada es requerida',
        ];


    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }


    public function index()
    {
        $data['var'] = $this->var;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Formas de Pago','');

        return view('paymentMethods.list', $data);
    }

    public function list()
    {
        $paymentMethods = PaymentMethod::all();
        return DataTables::of($paymentMethods)->make();
    }


    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['code'] = PaymentMethod::get()->last();
        $data['banks'] = BankCurrentAccount::all();
        $data['header'] = headeroptions($this->var, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Formas de Pago','');

        return view('paymentMethods.create', $data);
    }


    public function store(Request $request)
    {
        $rules = [
            'codigo' => 'required|unique:formapago,codigo',
            'descripcion' => 'required',
            'ctactebanco_id' => 'required',
        ];

        $this->validate($request, $rules, $this->messages);

        DB::beginTransaction();

        try {
            $payment = new PaymentMethod();
            $payment->codigo = $request->codigo;
            $payment->descripcion = $request->descripcion;
            $payment->ctactebanco_id = $request->ctactebanco_id;
            $payment->estado = 1;
            $payment->usuario = Session::get('usuario');
            $payment->save();

            DB::commit();

            $data['ruta'] = route('edit.paymentMethods', $payment->id);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'errors' => ['error' => 'Error al grabar'],
                'message' => $e->getMessage()],500);
        }

        return $data;

    }


    public function edit($id)
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['formapago'] = PaymentMethod::findOrFail($id);
        $data['banks'] = BankCurrentAccount::all();
        $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Formas de Pago','');

        return view('paymentMethods.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'codigo' => 'required|unique:formapago,codigo, '. $id,
            'descripcion' => 'required',
            'ctactebanco_id' => 'required',
        ];

        $this->validate($request, $rules, $this->messages);

        DB::beginTransaction();

        try {
            $payment = PaymentMethod::findOrFail($id);
            $payment->codigo = $request->codigo;
            $payment->descripcion = $request->descripcion;
            $payment->ctactebanco_id = $request->ctactebanco_id;
            $payment->estado = 1;
            $payment->usuario = Session::get('usuario');
            $payment->save();

            DB::commit();

            $data["success"] = "Se guardaron los cambios correctamente";

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'errors' => ['error' => 'Error al grabar'],
                'message' => $e->getMessage()],500);
        }

        return $data;
    }

}
