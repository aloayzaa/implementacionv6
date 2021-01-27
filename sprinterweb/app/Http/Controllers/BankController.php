<?php

namespace App\Http\Controllers;

use App\Bank\Collections\BankCollection;
use App\Bank\Entities\Bank;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Currency\Entities\Currency;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Subdiaries\Entities\Subdiaries;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class BankController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'bank';
    protected $privilegios = '05.01.02';
    protected  $user;
    private $instancia = 'ctacte_detalle';
    private $tabla_cabecera = 'banco';


    public function __construct()
    {
        $this->obtener_cliente();
        $this->user='ADMINISTRADOR';
    }

    public function list()
    {
        $banks = Bank::all();
        return DataTables::of($banks)->make();
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
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Entidades Bancarias','');
        $data['header'] = headeroptions($this->var, 'list', '', '',$this->privilegios(),$this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('bank.list', $data);
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
        $data['pcgs'] = AccountingPlan::all();
        $data['header'] = headeroptions($this->var, 'crea', '', '',$this->privilegios(),$this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Entidades Bancarias','');
        $this->eliminar_instancias();

        return view('bank.create', $data);
    }
    public function list_docbanco()
    {
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }

//    public function listar_detalle(Request $request)
//    {
//        $data['proceso'] = $request->proceso;
//        $data['instancia'] = $this->instancia;
//        $data['var'] = $this->var;
//        $data['carts'] = Cart::instance($data['instancia'])->content();
//        $data['total'] = Cart::instance($data['instancia'])->count();
//        $data['view'] = link_view('Tesoreria', 'Configuración', 'Entidades Bancarias','');
//
//        return view('bank.' . '', $data);
//    }
    public function eliminar_instancias()
    {
//        Cart::instance($this->instancia)->destroy();
    }
    public function currency(Request $request)
    {
        $pcg = AccountingPlan::findOrFail($request->account);
        $currency = Currency::findOrFail($pcg->moneda_id);
        return $currency;
    }
    public function agregar_entidad_banco(Request $request)
    {
        $id_moneda = $request->currency;
        if ($id_moneda != null) {
            $datos_moneda = Currency::findOrFail($id_moneda);
            $moneda_codigo = $datos_moneda->codigo;
            $moneda_descripcion = $datos_moneda->descripcion;
        } else {
            $moneda_codigo = '';
            $moneda_descripcion = '';
        }

        $numero_cheque = $request->check;

            $id_subdiario_ingresos = $request->txt_id_ingreso;
        if ($id_subdiario_ingresos != null) {
            $datos_subdiario_ingresos = Subdiaries::findOrFail($id_subdiario_ingresos);
            $subdiario_ingresos_codigo = $datos_subdiario_ingresos->codigo;
            $subdiario_ingresos_descripcion = $datos_subdiario_ingresos->descripcion;
        } else {
            $subdiario_ingresos_codigo = '';
            $subdiario_ingresos_descripcion = '';
        }

        $id_subdiario_egresos = $request->txt_id_egresos;
        if ($id_subdiario_egresos != null) {
            $datos_subdiario_egresos = Subdiaries::findOrFail($id_subdiario_egresos);
            $subdiario_egresos_codigo = $datos_subdiario_egresos->codigo;
            $subdiario_egresos_descripcion = $datos_subdiario_egresos->descripcion;
        } else {
            $subdiario_egresos_codigo = '';
            $subdiario_egresos_descripcion = '';
        }
//         comidttt

        $instancia = $request->instancia;

        Cart::instance($instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id_ctactebanco' => $id_ctactebanco,
                'codigo' => $codigo,
                'id_cuenta' => $id_cuenta,
                'cod_cuenta' => $pcg_codigo,
                'descripcion_cuenta' => $pcg_descripcion,
                'id_moneda' => $id_moneda,
                'cod_moneda' => $moneda_codigo,
                'descripcion_moneda' => $moneda_descripcion,
                'numero_cheque' => $numero_cheque,
                'id_subdiario_ingresos' => $id_subdiario_ingresos,
                'cod_subdiario_ingresos' => $subdiario_ingresos_codigo,
                'descripcion_subdiario_ingresos' => $subdiario_ingresos_descripcion,
                'id_subdiario_egresos' => $id_subdiario_egresos,
                'cod_subdiario_egresos' => $subdiario_egresos_codigo,
                'descripcion_subdiario_egresos' => $subdiario_egresos_descripcion,
                'estado' => 1
            ]]);

        return $instancia;

    }
////    public function eliminar_detalle_general(Request $request)
//    {
//        Cart::instance($this->instancia)->remove($request->rowId);
//        $id = $request->id;
//        session()->push('parent_id', $id);
//    }
//

    public function ver_detalle(Request $request)
    {
        $instancia = $request->instancia;
        $id = $request->id;
        $cart = Cart::instance($instancia)->get($id);

        if ($cart) {
            $data["estado"] = "ok";
            $data["id"] = $id;
            $data["id_ctactebanco"] = $cart->options->has('id_ctactebanco') ? $cart->options->id_ctactebanco : '';
            $data["codigo"] = $cart->options->has('codigo') ? $cart->options->codigo : '';
            $data["id_cuenta"] = $cart->options->has('id_cuenta') ? $cart->options->id_cuenta : '';
            $data["cod_cuenta"] = $cart->options->has('cod_cuenta') ? $cart->options->cod_cuenta : '';
            $data["descripcion_cuenta"] = $cart->options->has('descripcion_cuenta') ? $cart->options->descripcion_cuenta : '';
            $data["id_moneda"] = $cart->options->has('id_moneda') ? $cart->options->id_moneda : '';
            $data["cod_moneda"] = $cart->options->has('cod_moneda') ? $cart->options->cod_moneda : '';
            $data["descripcion_moneda"] = $cart->options->has('descripcion_moneda') ? $cart->options->descripcion_moneda : '';
            $data["numero_cheque"] = $cart->options->has('numero_cheque') ? $cart->options->numero_cheque : '';
            $data["id_subdiario_ingresos"] = $cart->options->has('id_subdiario_ingresos') ? $cart->options->id_subdiario_ingresos : '';
            $data["cod_subdiario_ingresos"] = $cart->options->has('cod_subdiario_ingresos') ? $cart->options->cod_subdiario_ingresos : '';
            $data["descripcion_subdiario_ingresos"] = $cart->options->has('descripcion_subdiario_ingresos') ? $cart->options->descripcion_subdiario_ingresos : '';
            $data["id_subdiario_egresos"] = $cart->options->has('id_subdiario_egresos') ? $cart->options->id_subdiario_egresos : '';
            $data["cod_subdiario_egresos"] = $cart->options->has('cod_subdiario_egresos') ? $cart->options->cod_subdiario_egresos : '';
            $data["descripcion_subdiario_egresos"] = $cart->options->has('descripcion_subdiario_egresos') ? $cart->options->descripcion_subdiario_egresos : '';
            $data["state"] = $cart->options->has('estado') ? $cart->options->estado : '';
        } else {
            $data["estado"] = "Ocurrió un error inesperado";
        }

        return $data;
    }

    public function editar_detalle(Request $request)
    {
        $id_carrito = $request->id_cart;
        $instancia = $request->instancia;
        $codigo = $request->code;
        $id_ctactebanco = $request->id_ctactebanco;
        $estado = $request->estado_modal;

        $id_cuenta = $request->account;
        if ($id_cuenta != null) {
            $datos_pcg = AccountingPlan::findOrFail($id_cuenta);
            $pcg_codigo = $datos_pcg->codigo;
            $pcg_descripcion = $datos_pcg->descripcion;
        } else {
            $pcg_codigo = '';
            $pcg_descripcion = '';
        }

        $id_moneda = $request->currency;
        if ($id_moneda != null) {
            $datos_moneda = Currency::findOrFail($id_moneda);
            $moneda_codigo = $datos_moneda->codigo;
            $moneda_descripcion = $datos_moneda->descripcion;
        } else {
            $moneda_codigo = '';
            $moneda_descripcion = '';
        }

        $numero_cheque = $request->check;

        $id_subdiario_ingresos = $request->txt_id_ingreso;
        if ($id_subdiario_ingresos != null) {
            $datos_subdiario_ingresos = Subdiaries::findOrFail($id_subdiario_ingresos);
            $subdiario_ingresos_codigo = $datos_subdiario_ingresos->codigo;
            $subdiario_ingresos_descripcion = $datos_subdiario_ingresos->descripcion;
        } else {
            $subdiario_ingresos_codigo = '';
            $subdiario_ingresos_descripcion = '';
        }

        $id_subdiario_egresos = $request->txt_id_egresos;
        if ($id_subdiario_egresos != null) {
            $datos_subdiario_egresos = Subdiaries::findOrFail($id_subdiario_egresos);
            $subdiario_egresos_codigo = $datos_subdiario_egresos->codigo;
            $subdiario_egresos_descripcion = $datos_subdiario_egresos->descripcion;
        } else {
            $subdiario_egresos_codigo = '';
            $subdiario_egresos_descripcion = '';
        }

        Cart::instance($instancia)->update($id_carrito, [
            'options' => [
                'id_ctactebanco' => $id_ctactebanco,
                'codigo' => $codigo,
                'id_cuenta' => $id_cuenta,
                'cod_cuenta' => $pcg_codigo,
                'descripcion_cuenta' => $pcg_descripcion,
                'id_moneda' => $id_moneda,
                'cod_moneda' => $moneda_codigo,
                'descripcion_moneda' => $moneda_descripcion,
                'numero_cheque' => $numero_cheque,
                'id_subdiario_ingresos' => $id_subdiario_ingresos,
                'cod_subdiario_ingresos' => $subdiario_ingresos_codigo,
                'descripcion_subdiario_ingresos' => $subdiario_ingresos_descripcion,
                'id_subdiario_egresos' => $id_subdiario_egresos,
                'cod_subdiario_egresos' => $subdiario_egresos_codigo,
                'descripcion_subdiario_egresos' => $subdiario_egresos_descripcion,
                'estado' => $estado,
            ]
        ]);

        $data["estado"] = "ok";
        $data["instancia"] = $instancia;

        return $data;
    }

    public function borrar_item(Request $request)
    {
        $instancia = $request->instancia;
        $rowId = $request->id_tmp;

        $cart = Cart::instance($instancia)->get($rowId);
        $resultado = BankCurrentAccount::where('id', $cart->options->id_ctactebanco)->first();

        if ($resultado) {
            Cart::instance($instancia)->remove($rowId);
            BankCurrentAccount::destroy($cart->options->id_ctactebanco);
        } else {
            Cart::instance($instancia)->remove($rowId);
        }
        return $instancia;
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
            $bancos = new Bank();
            $bancos->codigo = $request->bcode;
            $bancos->descripcion = $request->bdescription;
            $bancos->codsunat = $request->bsunat;
            $bancos->efectivo = validaCheck($request->bcheck);
            $bancos->usuario = Auth::id();
            $id=null;

            $id=DB::transaction(function () use ($bancos) {
                $bancos->save();
                $id= $bancos->id;
                $this->insertar_ctactebanco($bancos->id, $this->instancia);
               return $id;
            });
            $r["ruta"] = route('edit.bank', $id);

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        $this->eliminar_instancias();

        return $r;
    }

    private function insertar_ctactebanco($id_banco, $instancia)
    {

        $carts = Cart::instance($instancia)->content();
        $cont = 1;

        foreach ($carts as $cart) {
            $detalle = new BankCurrentAccount();
            $detalle->banco_id = $id_banco;

            $detalle->codigo = $cart->options->has('codigo') ? $cart->options->codigo : '';
            $detalle->descripcion = $cart->options->has('descripcion_cuenta') ? $cart->options->descripcion_cuenta : '';
            $detalle->cuenta_id = $cart->options->has('id_cuenta') ? $cart->options->id_cuenta : '';
            $detalle->moneda_id = $cart->options->has('id_moneda') ? $cart->options->id_moneda : '';
            $detalle->nrocheque = $cart->options->has('numero_cheque') ? $cart->options->numero_cheque : '';
            $detalle->subdiarioi_id = $cart->options->has('id_subdiario_ingresos') ? $cart->options->id_subdiario_ingresos : '';
            $detalle->subdiarioe_id = $cart->options->has('id_subdiario_egresos') ? $cart->options->id_subdiario_egresos : '';
            $detalle->usuario = Auth::id();
            $detalle->save();
            $cont++;
        }
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
        Cart::instance($this->instancia)->destroy();
        $data['proceso'] = 'edita';
        $data['banco'] = Bank::findOrFail($id);
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $this->carga_carros($id, $this->instancia);
        $data['header'] = headeroptions($this->var, 'edita','','', $this->privilegios(), $this->admin);
        $data['footermenu'] = footermenu($this->privilegios_footermenu(), $this->admin);
        $data['pcgs'] = AccountingPlan::all();
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Entidades Bancarias','');

        return view('bank.edit', $data);
    }

    private function carga_carros($id, $instancia)
    {
        $ctactebancos = BankCurrentAccount::cuentaCliente_deBanco($id);
        foreach ($ctactebancos as $ctactebanco) {
            $id_ctactebanco = $ctactebanco->id;
            $codigo_ctactebanco = $ctactebanco->codigo;
            $id_cuenta = $ctactebanco->cuenta_id;

            if ($id_cuenta != null) {
                $datos_pcg = AccountingPlan::findOrFail($id_cuenta);
                $pcg_codigo = $datos_pcg->codigo;
                $pcg_descripcion = $datos_pcg->descripcion;
            } else {
                $pcg_codigo = '';
                $pcg_descripcion = '';
            }

            $id_moneda = $ctactebanco->moneda_id;
            if ($id_moneda != '') {
                $datos_moneda = Currency::findOrFail($id_moneda);
                $moneda_codigo = $datos_moneda->codigo;
                $moneda_descripcion = $datos_moneda->descripcion;
            } else {
                $moneda_codigo = '';
                $moneda_descripcion = '';
            }

            $numero_cheque = $ctactebanco->nrocheque;

            $id_subdiario_ingresos = $ctactebanco->subdiarioi_id;
            if ($id_subdiario_ingresos != null) {
                $datos_subdiario_ingresos = Subdiaries::findOrFail($id_subdiario_ingresos);
                $subdiario_ingresos_codigo = $datos_subdiario_ingresos->codigo;
                $subdiario_ingresos_descripcion = $datos_subdiario_ingresos->descripcion;
            } else {
                $subdiario_ingresos_codigo = '';
                $subdiario_ingresos_descripcion = '';
            }

            $id_subdiario_egresos = $ctactebanco->subdiarioe_id;
            if ($id_subdiario_egresos != null) {
                $datos_subdiario_egresos = Subdiaries::findOrFail($id_subdiario_egresos);
                $subdiario_egresos_codigo = $datos_subdiario_egresos->codigo;
                $subdiario_egresos_descripcion = $datos_subdiario_egresos->descripcion;
            } else {
                $subdiario_egresos_codigo = '';
                $subdiario_egresos_descripcion = '';
            }

            Cart::instance($instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id_ctactebanco' => $id_ctactebanco,
                    'codigo' => $codigo_ctactebanco,
                    'id_cuenta' => $id_cuenta,
                    'cod_cuenta' => $pcg_codigo,
                    'descripcion_cuenta' => $pcg_descripcion,
                    'id_moneda' => $id_moneda,
                    'cod_moneda' => $moneda_codigo,
                    'descripcion_moneda' => $moneda_descripcion,
                    'numero_cheque' => $numero_cheque,
                    'id_subdiario_ingresos' => $id_subdiario_ingresos,
                    'cod_subdiario_ingresos' => $subdiario_ingresos_codigo,
                    'descripcion_subdiario_ingresos' => $subdiario_ingresos_descripcion,
                    'id_subdiario_egresos' => $id_subdiario_egresos,
                    'cod_subdiario_egresos' => $subdiario_egresos_codigo,
                    'descripcion_subdiario_egresos' => $subdiario_egresos_descripcion,
                    'estado' => $ctactebanco->estado,
                ]]);
        }
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
            $bancos = Bank::findOrFail($id);
            $bancos->codigo = $request->bcode;
            $bancos->descripcion = $request->bdescription;
            $bancos->codsunat = $request->bsunat;
            $bancos->efectivo = validaCheck($request->bcheck);
            $bancos->usuario = Auth::id();

            DB::transaction(function () use ($bancos) {
                $bancos->save();
                $this->editar_ctactebanco($bancos->id, $this->instancia);
            });

            $r['estado'] = 'ok';
            $r['mensaje'] = 'Editado Correctamente';

        } catch (PDOException $e) {
            $r['estado'] = 'error';
            $r['mensaje'] = 'Error al Editar!';
            $r['bd'] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;
        Cart::instance($this->instancia)->destroy();

        return $r;
    }

    private function editar_ctactebanco($id_banco, $instancia)
    {
        $carts = Cart::instance($instancia)->content();
        foreach ($carts as $cart) {
            $id_ctactebanco = $cart->options->has('id_ctactebanco') ? $cart->options->id_ctactebanco : '';
            $codigo = $cart->options->has('codigo') ? $cart->options->codigo : '';
            $descripcion = $cart->options->has('descripcion_cuenta') ? $cart->options->descripcion_cuenta : '';
            $moneda_id = $cart->options->has('id_moneda') ? $cart->options->id_moneda : '';
            $cuenta_id = $cart->options->has('id_cuenta') ? $cart->options->id_cuenta : '';
            $nrocheque = $cart->options->has('numero_cheque') ? $cart->options->numero_cheque : '';
            $usuario = Auth::id();
            $subdiarioi_id = $cart->options->has('id_subdiario_ingresos') ? $cart->options->id_subdiario_ingresos : '';
            $subdiarioe_id = $cart->options->has('id_subdiario_egresos') ? $cart->options->id_subdiario_egresos : '';

            $resultado = BankCurrentAccount::where('id', $id_ctactebanco)->first();

            if ($resultado) {
                $ctactebanco = BankCurrentAccount::findOrFail($id_ctactebanco);
                $ctactebanco->codigo = $codigo;
                $ctactebanco->descripcion = $descripcion;
                $ctactebanco->banco_id = $id_banco;
                $ctactebanco->moneda_id = $moneda_id;
                $ctactebanco->cuenta_id = $cuenta_id;
                $ctactebanco->nrocheque = $nrocheque;
                $ctactebanco->usuario = $usuario;
                $ctactebanco->subdiarioi_id = $subdiarioi_id;
                $ctactebanco->subdiarioe_id = $subdiarioe_id;
                $ctactebanco->save();
            } else {
                $ctactebanco = new BankCurrentAccount;
                $ctactebanco->codigo = $codigo;
                $ctactebanco->descripcion = $descripcion;
                $ctactebanco->banco_id = $id_banco;
                $ctactebanco->moneda_id = $moneda_id;
                $ctactebanco->cuenta_id = $cuenta_id;
                $ctactebanco->nrocheque = $nrocheque;
                $ctactebanco->usuario = $usuario;
                $ctactebanco->subdiarioi_id = $subdiarioi_id;
                $ctactebanco->subdiarioe_id = $subdiarioe_id;
                $ctactebanco->save();
            }
        }
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
