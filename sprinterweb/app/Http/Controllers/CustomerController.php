<?php

namespace App\Http\Controllers;

use App\Bank\Entities\Bank;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Customers\Entities\CustomerAccount;
use App\Customers\Entities\CustomerContact;
use App\Customers\Entities\CustomerAddress;
use App\Customers\Entities\ThirdClass;
use App\Customers\Entities\ThirdCompany;
use App\Customers\Entities\ThirdItem;
use App\Customers\Entities\ThirdMark;
use App\Documents\Entities\Identification;
use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\UserPrivileges;
use App\Marks\Entities\Marca;
use App\Nationalities\Entities\Nationality;
use App\Seller\Entities\Seller;
use App\Subsidiaries\Entities\Subsidiaries;
use App\TypeHeading\Entities\TypeHeading;
use App\TypeVia\Entities\TypeVia;
use App\Ubigeos\Entities\Ubigeo;
use App\ZoneType\Entities\ZoneType;
use App\PaymentCondition\Entities\PaymentCondition;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\Controls\Tables;
class CustomerController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'customers';
    private $contactos = 'tercero_contacto';
    private $cuentas_bancarias = 'tercero_cuenta';
    private $marcas = 'tercero_marca';
    private $rubro = 'tercero_rubro';
    private $direccion = 'tercero_direccion';
    private $empresa = 'tercero_empresa';
    private $tabla_cabecera = 'tercero';
    protected $user;
    protected $privilegios = '01.02';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
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
        $data['view'] = link_view('Maestros', 'Terceros', 'Cátalogo de Terceros','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->eliminar_instancias();

        return view('customers.list', $data);
    }

    public function list()
    {
        $customers = Customer::all();
        //$customers = Customer::all()->toJson();
        //return Datatables::of(Customer::select('*')->OrderBy('id','asc')->limit(2000)->get())->toJson();
        //dd($customers);
        return DataTables::of($customers)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crear';
        $data['tipo_via'] = TypeVia::all()->where('estado',1);
        $data['documentoide'] = Identification::all()->where('estado',1);
        $data['tipo_zona'] = ZoneType::all()->where('estado',1);
        $data['banco'] = Bank::all()->where('estado',1);
        $data['moneda'] = Currency::all()->where('estado',1);
        $data['tiporubro'] = TypeHeading::all()->where('estado',1);
        $data['clasetercero'] = ThirdClass::all()->where('estado',1);
        $data['condicion_pago'] = PaymentCondition::select('id','codigo','descripcion')->where('codigo','01')->first();
        $data['today'] = Carbon::today()->format('Y-m-d');
        $data['view'] = link_view('Maestros', 'Terceros', 'Cátalogo de Terceros','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->eliminar_instancias();
        return view('customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $rules = array(
            'code' => 'required|unique:tercero,codigo',
            'descripcion_tercero'  => 'sometimes|required_without_all:txt_apellido_paterno,txt_apellido_materno,txt_nombres',
            'txt_apellido_paterno' => 'sometimes|required_without_all:descripcion_tercero',
            'txt_apellido_materno' => 'sometimes|required_without_all:descripcion_tercero',
            'txt_nombres' => 'sometimes|required_without_all:descripcion_tercero',
            'cbo_documento' => 'required',
            'txt_documento' => 'required',
        );
        $message = array(
            'code.required' => 'EL código es obligatorio',
            'code.unique' => 'EL código ya se encuentra registrado',
            'descripcion_tercero.required_without_all' => 'La razón social es obligatorio',
            'txt_apellido_paterno.required_without_all' => 'El apellido paterno es obligatorio',
            'txt_apellido_materno.required_without_all' => 'El apellido materno es obligatorio',
            'txt_nombres.required_without_all' => 'El nombre es obligatorio',
            'cbo_documento.required' => 'EL tipo de documento es obligatorio',
            'txt_documento.required' => 'EL número de documento es obligatorio',
        );
        $this->validate($request, $rules, $message);

        try {

            $tercero = new Customer();
            $tercero->codigo = $request->code;
            $tercero->descripcion = (isset($request->descripcion_tercero)) ? $request->descripcion_tercero : $request->txt_apellido_paterno . ' ' . $request->txt_apellido_materno . ' ' . $request->txt_nombres;
            $tercero->nombrecomercial = ($request->txt_nombre_comercial != '') ? $request->txt_nombre_comercial : '';
            $tercero->fechanac = $request->txt_fecha_nacimiento_otros;
            $tercero->tratamiento = ($request->cbo_tratamiento_otros != '') ? $request->cbo_tratamiento_otros : '';
            $tercero->tipopersona = $request->cbo_tipo;
            //$tercero->ruc = $request->
            $tercero->clasetercero_id = $request->cbo_clasificacion_otros;
            $tercero->telefono1 = ($request->txt_telefono1 != '') ? $request->txt_telefono1 : '';
            $tercero->telefono2 = ($request->txt_telefono2 != '') ? $request->txt_telefono2 : '';
            $tercero->telefono3 = ($request->txt_telefono3 != '') ? $request->txt_telefono3 : '';
            $tercero->web = ($request->txt_web != '') ? $request->txt_web : '';
            $tercero->escliente = (isset($request->chk_cliente) && $request->chk_cliente == "on") ? 1 : 0;
            $tercero->precio = $request->opt_tipo_precio;
            $tercero->lineacredito = ($request->txt_linea_credito != '') ? $request->txt_linea_credito : 0.000;
            $tercero->condicioncobr_id = $request->cbo_condicion_pago_cliente;
            $tercero->esproveedor = (isset($request->chk_proveedor) && $request->chk_proveedor == "on") ? 1 : 0;
            $tercero->condicionpago_id = $request->cbo_condicion_pago_proveedor;
            $tercero->conretencion = (isset($request->chk_sujeto_retencion) && $request->chk_sujeto_retencion == "on") ? 1 : 0;
            $tercero->condetraccion = (isset($request->chk_sujeto_detraccion) && $request->chk_sujeto_detraccion == "on") ? 1 : 0;
            $tercero->estrabajador = (isset($request->chk_es_trabajador) && $request->chk_es_trabajador == "on") ? 1 : 0;
            $tercero->esservidor = ($request->cbo_medico != '') ? $request->cbo_medico : 0;
            $tercero->apellidopaterno = ($request->txt_apellido_paterno != '') ? $request->txt_apellido_paterno : '';
            $tercero->apellidomaterno = ($request->txt_apellido_materno != '') ? $request->txt_apellido_materno : '';
            $tercero->nombres = ($request->txt_nombres != '') ? $request->txt_nombres : '';
            $tercero->documentoide_id = $request->cbo_documento;
            $tercero->nrodocidentidad = ($request->txt_documento != '') ? $request->txt_documento : '';
            $tercero->dni_id = $request->cbo_dni;
            $tercero->dni = ($request->txt_dni != '') ? $request->txt_dni : '';
            $tercero->pais_id = $request->cbo_pais;
            $tercero->observaciones = ($request->txt_observaciones_otros != '') ? $request->txt_observaciones_otros : '';
            $tercero->estado = 1;
            $tercero->tipovia_id = $request->cbo_via_tipo;
            $tercero->via_nombre = ($request->txt_nombre_via != '') ? $request->txt_nombre_via : '';
            $tercero->via_numero = ($request->txt_numero_via != '') ? $request->txt_numero_via : '';
            $tercero->via_interior = ($request->txt_interior_via != '') ? $request->txt_interior_via : '';
            //$tercero->foto = $request->
            $tercero->tipozona_id = $request->cbo_tipo_zona;
            $tercero->zona_nombre = ($request->txt_nombre_zona != '') ? $request->txt_nombre_zona : '';
            $tercero->email = ($request->txt_email != '') ? $request->txt_email : '';
            //$tercero->referencia = $request->
            $tercero->ubigeo_id = $request->cbo_ubigeo;
            $tercero->sucursal_id = $request->cbo_ciudad;
            $tercero->nropermiso = ($request->txt_servicio_otros != '') ? $request->txt_servicio_otros : '';
            $tercero->vendedor_id = $request->cbo_vendedor_cobrador;
            $tercero->conyuge = ($request->txt_conyuge_otros != '') ? $request->txt_conyuge_otros : '';
            $tercero->usuario = Session::get('usuario');
            $tercero->fechaafilia = $request->txt_fecha_afiliacion_otros;

            $id = null;
            $id = DB::transaction(function () use ($tercero) {
                $tercero->save();

                $this->guardar_tercero_contacto($tercero->id);
                $this->guardar_tercero_cuenta($tercero->id);
                $this->guardar_tercero_marca($tercero->id);
                $this->guardar_tercero_rubro($tercero->id);
                $this->guardar_tercero_direccion($tercero->id);
                $this->guardar_tercero_empresa($tercero->id);

                $id = $tercero->id;
                return $id;
            });

            $r["ruta"] = route('edit.customers', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        $this->eliminar_instancias();

        return $r;
    }

    public function guardar_tercero_contacto($tercero_id){
        $item = Session::get('tercero_contacto');

        if($item){
            foreach ($item as $i) {
                CustomerContact::where('tercero_id',$tercero_id)->where('item', $i)->delete();
                session()->put('tercero_contacto', []);
            }
        }

        foreach (Cart::instance($this->contactos)->content() as $cart) {
            
            $existe = CustomerContact::where('tercero_id',$tercero_id)->where('item',$cart->options->item)->exists();
            
            if($existe){

                $data['nrodocidentidad'] = ($cart->options->nrodocidentidad != '') ? $cart->options->nrodocidentidad : '';
                $data['nombre'] = ($cart->options->nombre != '') ? $cart->options->nombre : '';
                $data['cargo'] = ($cart->options->cargo != '') ? $cart->options->cargo : '';
                $data['email'] = $cart->options->email;
                $data['telefono'] = ($cart->options->telefono != '') ? $cart->options->telefono : '';
                $data['usuario'] = Session::get('usuario');
                $data['cpe'] = ($cart->options->cpe == "on") ? 1 : 0;
                CustomerContact::update_data($tercero_id, $cart->options->item, $data);

            }else{

                $tercero_contacto = new CustomerContact();
                $tercero_contacto->tercero_id = $tercero_id;
                $tercero_contacto->item = ($cart->options->item != '') ? $cart->options->item : '';
                $tercero_contacto->nrodocidentidad = ($cart->options->nrodocidentidad != '') ? $cart->options->nrodocidentidad : '';
                $tercero_contacto->nombre = ($cart->options->nombre != '') ? $cart->options->nombre : '';
                $tercero_contacto->cargo = ($cart->options->cargo != '') ? $cart->options->cargo : '';
                $tercero_contacto->email = $cart->options->email;
                $tercero_contacto->telefono = ($cart->options->telefono != '') ? $cart->options->telefono : '';
                $tercero_contacto->usuario = Session::get('usuario');
                $tercero_contacto->cpe = ($cart->options->cpe == "on") ? 1 : 0;
                $tercero_contacto->save();
            }
        }
    }

    public function guardar_tercero_cuenta($tercero_id){
        $item = Session::get('tercero_cuenta');
        if($item){
            foreach ($item as $i) {
                CustomerAccount::where('tercero_id',$tercero_id)->where('item', $i)->delete();
                session()->put('tercero_cuenta', []);
            }
        }
        foreach (Cart::instance($this->cuentas_bancarias)->content() as $cart) {
            $existe = CustomerAccount::where('tercero_id',$tercero_id)->where('item',$cart->options->item)->exists();
            if($existe){
                $data['banco_id'] = $cart->options->banco_id;
                $data['cuenta'] = ($cart->options->cuenta != '') ? $cart->options->cuenta : '';
                $data['defecto'] = ($cart->options->defecto == "on") ? 1 : 0;
                $data['moneda_id'] = $cart->options->moneda_id;
                $data['moneda_id'] = $cart->options->moneda_id;
                $data['usuario'] = Session::get('usuario');
                $data['cci'] = ($cart->options->cci != '') ? $cart->options->cci : '';
                $data['tipocuenta'] = ($cart->options->tipocuenta != '') ? $cart->options->tipocuenta : '';
                $data['swift'] = ($cart->options->swift != '') ? $cart->options->swift : '';
                CustomerAccount::update_data($tercero_id, $cart->options->item, $data);
            }else{

                $tercero_cuenta = new CustomerAccount();
                $tercero_cuenta->tercero_id = $tercero_id;
                $tercero_cuenta->item = ($cart->options->item != '') ? $cart->options->item : '';
                $tercero_cuenta->banco_id = $cart->options->banco_id;
                $tercero_cuenta->cuenta = ($cart->options->cuenta != '') ? $cart->options->cuenta : '';
                $tercero_cuenta->defecto = ($cart->options->defecto == "on") ? 1 : 0;
                $tercero_cuenta->moneda_id = $cart->options->moneda_id;
                $tercero_cuenta->usuario = Session::get('usuario');
                $tercero_cuenta->cci = ($cart->options->cci != '') ? $cart->options->cci : '';
                $tercero_cuenta->tipocuenta = ($cart->options->tipocuenta != '') ? $cart->options->tipocuenta : "H";
                $tercero_cuenta->swift = ($cart->options->swift != '') ? $cart->options->swift : '';
                $tercero_cuenta->save();
            }
        }
    }

    public function guardar_tercero_marca($tercero_id){
        $item = Session::get('tercero_marca');
        if($item){
            foreach ($item as $i) {
                ThirdMark::where('tercero_id',$tercero_id)->where('item', $i)->delete();
                session()->put('tercero_marca', []);
            }
        }

        foreach (Cart::instance($this->marcas)->content() as $cart) {
            $existe = ThirdMark::where('tercero_id',$tercero_id)->where('item',$cart->options->item)->exists();
            if($existe){
                $data['marca_id'] = $cart->options->marca_id;
                $data['usuario'] = Session::get('usuario');
                ThirdMark::update_data($tercero_id, $cart->options->item, $data);
            }else{

                $tercero_marca = new ThirdMark();
                $tercero_marca->tercero_id = $tercero_id;
                $tercero_marca->item = ($cart->options->item != '') ? $cart->options->item : '';
                $tercero_marca->marca_id = $cart->options->marca_id;
                $tercero_marca->usuario = Session::get('usuario');
                $tercero_marca->save();
            }
        }
    }

    public function guardar_tercero_rubro($tercero_id){
        $item = Session::get('tercero_rubro');
        if($item){
            foreach ($item as $i) {
                ThirdItem::where('tercero_id',$tercero_id)->where('item', $i)->delete();
                session()->put('tercero_rubro', []);
            }
        }
        foreach (Cart::instance($this->rubro)->content() as $cart) {
            $existe = ThirdItem::where('tercero_id',$tercero_id)->where('item',$cart->options->item)->exists();
            if($existe){
                $data['tiporubro_id'] = $cart->options->tiporubro_id;
                $data['usuario'] = Session::get('usuario');
                ThirdItem::update_data($tercero_id, $cart->options->item, $data);
            }else{

                $tercero_rubro = new ThirdItem();
                $tercero_rubro->tercero_id = $tercero_id;
                $tercero_rubro->item = ($cart->options->item != '') ? $cart->options->item : '';
                $tercero_rubro->tiporubro_id = $cart->options->tiporubro_id;
                $tercero_rubro->usuario = Session::get('usuario');
                $tercero_rubro->save();
            }
        }
    }

    public function guardar_tercero_direccion($tercero_id){
        $item = Session::get('tercero_direccion');

        if($item){
            foreach ($item as $i) {
                CustomerAddress::where('tercero_id',$tercero_id)->where('item', $i)->delete();
                session()->put('tercero_direccion', []);
            }
        }
        foreach (Cart::instance($this->direccion)->content() as $cart) {
            $existe = CustomerAddress::where('tercero_id',$tercero_id)->where('item',$cart->options->item)->exists();
            if($existe){
                //$data['tipovia_id'] = $cart->options->tipovia_id;
                $data['via_nombre'] = ($cart->options->via_nombre != '') ? $cart->options->via_nombre : '';
               // $data['via_numero'] = ($cart->options->via_numero != '') ? $cart->options->via_numero : '';
               // $data['via_interior'] = ($cart->options->via_interior != '') ? $cart->options->via_interior : '';
                $data['ubigeo_id'] = $cart->options->ubigeo_id;
                $data['usuario'] = Session::get('usuario');
                CustomerAddress::update_data($tercero_id, $cart->options->item, $data);
            }else{

                $tercero_direccion = new CustomerAddress();
                $tercero_direccion->tercero_id = $tercero_id;
                $tercero_direccion->item = ($cart->options->item != '') ? $cart->options->item : '';
                //$tercero_direccion->tipovia_id = $cart->options->tipovia_id;
                $tercero_direccion->via_nombre = ($cart->options->via_nombre != '') ? $cart->options->via_nombre : '';
                //$tercero_direccion->via_numero = ($cart->options->via_numero != '') ? $cart->options->via_numero : '';
                //$tercero_direccion->via_interior = ($cart->options->via_interior != '') ? $cart->options->via_interior : '';
                $tercero_direccion->ubigeo_id = $cart->options->ubigeo_id;
                $tercero_direccion->usuario = Session::get('usuario');
                $tercero_direccion->save();
            }
        }
    }

    public function guardar_tercero_empresa($tercero_id){
        $item = Session::get('tercero_empresa');
        if($item){
            foreach ($item as $i) {
                ThirdCompany::where('tercero_id',$tercero_id)->where('item', $i)->delete();
                session()->put('tercero_empresa', []);
            }
        }
        foreach (Cart::instance($this->empresa)->content() as $cart) {
            $existe = ThirdCompany::where('tercero_id',$tercero_id)->where('item',$cart->options->item)->exists();
            if($existe){
                $data['ruc'] = ($cart->options->ruc != '') ? $cart->options->ruc : '';
                $data['razonsocial'] = ($cart->options->razonsocial != '') ? $cart->options->razonsocial : '';
                $data['direccion'] = ($cart->options->direccion != '') ? $cart->options->direccion : '';
                $data['tipo'] = ($cart->options->tipo != '') ? $cart->options->tipo : '';
                $data['usuario'] = Session::get('usuario');
                ThirdCompany::update_data($tercero_id, $cart->options->item, $data);
            }else{

                $tercero_empresa = new ThirdCompany();
                $tercero_empresa->tercero_id = $tercero_id;
                $tercero_empresa->item = ($cart->options->item != '') ? $cart->options->item : '';
                $tercero_empresa->ruc = ($cart->options->ruc != '') ? $cart->options->ruc : '';
                $tercero_empresa->razonsocial = ($cart->options->razonsocial != '') ? $cart->options->razonsocial : '';
                $tercero_empresa->direccion = ($cart->options->direccion != '') ? $cart->options->direccion : '';
                $tercero_empresa->tipo = ($cart->options->tipo != '') ? $cart->options->tipo : '';
                $tercero_empresa->usuario = Session::get('usuario');
                $tercero_empresa->save();
            }
        }
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
        $this->eliminar_instancias();

        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Maestros', 'Terceros', 'Cátalogo de Terceros','');
        $data['header'] = headeroptions($this->var, 'edita','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['tercero'] = Customer::findOrFail($id);
        $data['tipo_via'] = TypeVia::all()->where('estado',1);
        $data['documentoide'] = Identification::all()->where('estado',1);
        $data['tipo_zona'] = ZoneType::all()->where('estado',1);
        $data['banco'] = Bank::all()->where('estado',1);
        $data['moneda'] = Currency::all()->where('estado',1);
        $data['tiporubro'] = TypeHeading::all()->where('estado',1);
        $data['clasetercero'] = ThirdClass::all()->where('estado',1);
        $data['condicion_pago'] = PaymentCondition::select('id','codigo','descripcion')->where('codigo','01')->first();


        $this->llenar_tercero_contactos($id);
        $this->llenar_tercero_cuentas($id);
        $this->llenar_tercero_marcas($id);
        $this->llenar_tercero_rubro($id);
        $this->llenar_tercero_direccion($id);
        $this->llenar_tercero_empresa($id);


        return view('customers.edit', $data);
    }

    private function llenar_tercero_contactos($tercero_id){
        $tercero_contacto = CustomerContact::select('*')->where('tercero_id',$tercero_id)->get();
        foreach ($tercero_contacto as $tc){
            Cart::instance($this->contactos)->add(['id' => $tc->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'tercero_id' => $tc->tercero_id,
                    'item' => $tc->item,
                    'nrodocidentidad' => $tc->nrodocidentidad,
                    'nombre' => $tc->nombre,
                    'cargo' => $tc->cargo,
                    'email' => $tc->email,
                    'telefono' => $tc->telefono,
                    'usuario' => $tc->usuario,
                    'cpe' => ($tc->cpe == 1) ? "on" : "",
                ]
            ]);
        }
    }


    private function llenar_tercero_cuentas($tercero_id){
        $tercero_cuenta = CustomerAccount::select('*')->where('tercero_id',$tercero_id)->get();
        foreach ($tercero_cuenta as $tc){

            $banco = Bank::findOrFail($tc->banco_id);
            $moneda = Currency::findOrFail($tc->moneda_id);

            switch($tc->tipocuenta){
                case 'H':
                    $tipocuenta_descripcion = 'Ahorro';
                    break;
                case 'T':
                    $tipocuenta_descripcion = 'Cta. Cte.';
                    break;
                case 'D':
                    $tipocuenta_descripcion = 'Detracción';
                    break;
                case 'M':
                    $tipocuenta_descripcion = 'Maestro';
                    break;
                default:
                    break;
            }

            Cart::instance($this->cuentas_bancarias)->add(['id' => $tc->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'tercero_id' => $tc->tercero_id,
                    'item' => $tc->item,
                    'banco_id' => $tc->banco_id,
                    'banco_codigo' => $banco->codigo,
                    'cuenta' => $tc->cuenta,
                    'defecto' => ($tc->defecto == 1) ? "on": "",
                    'moneda_id' => $tc->moneda_id,
                    'moneda_descripcion' => $moneda->descripcion,
                    'usuario' => $tc->usuario,
                    'cci' => $tc->cci,
                    'tipocuenta' => $tc->tipocuenta,
                    'tipocuenta_descripcion' => $tipocuenta_descripcion,
                    'swift' => $tc->swift,
                ]
            ]);
        }
    }


    private function llenar_tercero_marcas($tercero_id){

        $tercero_marca = ThirdMark::select('*')->where('tercero_id',$tercero_id)->get();
        foreach ($tercero_marca as $tm){

            $marca = Marca::findOrFail($tm->marca_id);

            Cart::instance($this->marcas)->add(['id' => $tm->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'tercero_id' => $tm->tercero_id,
                    'item' => $tm->item,
                    'marca_id' => $tm->marca_id,
                    'marca_codigo' => $marca->codigo,
                    'marca_descripcion' => $marca->descripcion,
                    'usuario' => $tm->usuario,
                ]
            ]);
        }
    }


    private function llenar_tercero_rubro($tercero_id){

        $tercero_rubro = ThirdItem::select('*')->where('tercero_id',$tercero_id)->get();

        foreach ($tercero_rubro as $tr){

            $tiporubro = TypeHeading::findOrFail($tr->tiporubro_id);

            Cart::instance($this->rubro)->add(['id' => $tr->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'tercero_id' => $tr->tercero_id,
                    'item' => $tr->item,
                    'tiporubro_id' => $tr->tiporubro_id,
                    'rubro_codigo' => $tiporubro->codigo,
                    'rubro_descripcion' => $tiporubro->descripcion,
                    'usuario' => $tr->usuario,
                ]
            ]);
        }
    }


    private function llenar_tercero_direccion($tercero_id){

        $tercero_direccion = CustomerAddress::select('*')->where('tercero_id',$tercero_id)->get();
        foreach ($tercero_direccion as $td){

            $ubigeo = Ubigeo::findOrFail($td->ubigeo_id);

            Cart::instance($this->direccion)->add(['id' => $td->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'tercero_id' => $td->tercero_id,
                    'item' => $td->item,
                    'via_nombre' => $td->via_nombre,
                    'ubigeo_id' => $td->ubigeo_id,
                    'ubigeo_codigo' => $ubigeo->codigo,
                    'ubigeo_completo' => $ubigeo->completo,
                    'usuario' => $td->usuario,
                ]
            ]);
        }
    }


    private function llenar_tercero_empresa($tercero_id){

        $tercero_empresa = ThirdCompany::select('*')->where('tercero_id',$tercero_id)->get();
        foreach ($tercero_empresa as $te){

            Cart::instance($this->empresa)->add(['id' => $te->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'tercero_id' => $te->tercero_id,
                    'item' => $te->item,
                    'ruc' => $te->ruc,
                    'razonsocial' => $te->razonsocial,
                    'direccion' => $te->direccion,
                    'tipo' => $te->tipo,
                    'usuario' => $te->usuario,
                ]
            ]);
        }
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
        $rules = array(
            'code' => 'required|unique:tercero,codigo,'.$id,
            'descripcion_tercero'  => 'sometimes|required_without_all:txt_apellido_paterno,txt_apellido_materno,txt_nombres',
            'txt_apellido_paterno' => 'sometimes|required_without_all:descripcion_tercero',
            'txt_apellido_materno' => 'sometimes|required_without_all:descripcion_tercero',
            'txt_nombres' => 'sometimes|required_without_all:descripcion_tercero',
            'cbo_documento' => 'required',
            'txt_documento' => 'required',
        );
        $message = array(
            'code.required' => 'EL código es obligatorio',
            'code.unique' => 'El codigo ya se encuntra registrado',
            'descripcion_tercero.required_without_all' => 'La razón social es obligatorio',
            'txt_apellido_paterno.required_without_all' => 'El apellido paterno es obligatorio',
            'txt_apellido_materno.required_without_all' => 'El apellido materno es obligatorio',
            'txt_nombres.required_without_all' => 'El nombre es obligatorio',
            'cbo_documento.required' => 'EL tipo de documento es obligatorio',
            'txt_documento.required' => 'EL número de documento es obligatorio',
        );
        $this->validate($request, $rules, $message);
        //$this->eliminar_instancias();

        try {

            $tercero = Customer::findOrFail($id);
            $tercero->codigo = $request->code;
            $tercero->descripcion = (isset($request->descripcion_tercero)) ? $request->descripcion_tercero : $request->txt_apellido_paterno . ' ' . $request->txt_apellido_materno . ' ' . $request->txt_nombres;
            $tercero->nombrecomercial = ($request->txt_nombre_comercial != '') ? $request->txt_nombre_comercial : '';
            $tercero->fechanac = $request->txt_fecha_nacimiento_otros;
            $tercero->tratamiento = ($request->cbo_tratamiento_otros != '') ? $request->cbo_tratamiento_otros : '';
            $tercero->tipopersona = $request->cbo_tipo;
            //$tercero->ruc = $request->
            $tercero->clasetercero_id = $request->cbo_clasificacion_otros;
            $tercero->telefono1 = ($request->txt_telefono1 != '') ? $request->txt_telefono1 : '';
            $tercero->telefono2 = ($request->txt_telefono2 != '') ? $request->txt_telefono2 : '';
            $tercero->telefono3 = ($request->txt_telefono3 != '') ? $request->txt_telefono3 : '';
            $tercero->web = ($request->txt_web != '') ? $request->txt_web : '';
            $tercero->escliente = (isset($request->chk_cliente) && $request->chk_cliente == "on") ? 1 : 0;
            $tercero->precio = $request->opt_tipo_precio;
            $tercero->lineacredito = ($request->txt_linea_credito != '') ? $request->txt_linea_credito : 0.000;
            $tercero->condicioncobr_id = $request->cbo_condicion_pago_cliente;
            $tercero->esproveedor = (isset($request->chk_proveedor) && $request->chk_proveedor == "on") ? 1 : 0;
            $tercero->condicionpago_id = $request->cbo_condicion_pago_proveedor;
            $tercero->conretencion = (isset($request->chk_sujeto_retencion) && $request->chk_sujeto_retencion == "on") ? 1 : 0;
            $tercero->condetraccion = (isset($request->chk_sujeto_detraccion) && $request->chk_sujeto_detraccion == "on") ? 1 : 0;
            $tercero->estrabajador = (isset($request->chk_es_trabajador) && $request->chk_es_trabajador == "on") ? 1 : 0;
            $tercero->esservidor = ($request->cbo_medico != '') ? $request->cbo_medico : 0;
            $tercero->apellidopaterno = ($request->txt_apellido_paterno != '') ? $request->txt_apellido_paterno : '';
            $tercero->apellidomaterno = ($request->txt_apellido_materno != '') ? $request->txt_apellido_materno : '';
            $tercero->nombres = ($request->txt_nombres != '') ? $request->txt_nombres : '';
            $tercero->documentoide_id = $request->cbo_documento;
            $tercero->nrodocidentidad = ($request->txt_documento != '') ? $request->txt_documento : '';
            $tercero->dni_id = $request->cbo_dni;
            $tercero->dni = ($request->txt_dni != '') ? $request->txt_dni : '';
            $tercero->pais_id = $request->cbo_pais;
            $tercero->observaciones = ($request->txt_observaciones_otros != '') ? $request->txt_observaciones_otros : '';
            //$tercero->estado = 1;
            $tercero->tipovia_id = $request->cbo_via_tipo;
            $tercero->via_nombre = ($request->txt_nombre_via != '') ? $request->txt_nombre_via : '';
            $tercero->via_numero = ($request->txt_numero_via != '') ? $request->txt_numero_via : '';
            $tercero->via_interior = ($request->txt_interior_via != '') ? $request->txt_interior_via : '';
            //$tercero->foto = $request->
            $tercero->tipozona_id = $request->cbo_tipo_zona;
            $tercero->zona_nombre = ($request->txt_nombre_zona != '') ? $request->txt_nombre_zona : '';
            $tercero->email = ($request->txt_email != '') ? $request->txt_email : '';
            //$tercero->referencia = $request->
            $tercero->ubigeo_id = $request->cbo_ubigeo;
            $tercero->sucursal_id = $request->cbo_ciudad;
            $tercero->nropermiso = ($request->txt_servicio_otros != '') ? $request->txt_servicio_otros : '';
            $tercero->vendedor_id = $request->cbo_vendedor_cobrador;
            $tercero->conyuge = ($request->txt_conyuge_otros != '') ? $request->txt_conyuge_otros : '';
            $tercero->usuario = Session::get('usuario');
            $tercero->fechaafilia = $request->txt_fecha_afiliacion_otros;

            DB::transaction(function () use ($tercero) {
                $tercero->save();

                $this->guardar_tercero_contacto($tercero->id);
                $this->guardar_tercero_cuenta($tercero->id);
                $this->guardar_tercero_marca($tercero->id);
                $this->guardar_tercero_rubro($tercero->id);
                $this->guardar_tercero_direccion($tercero->id);
                $this->guardar_tercero_empresa($tercero->id);

            });
            $r["success"] = "Grabado Correctamente";
        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }


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


    public function activar_anular_producto(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Customer::anular($request->id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function buscar_ubigeo(Request $request){
        $resultado = Ubigeo::select2($request->term);
        return $resultado;
    }

    private function obtiene_ubigeo($codigo){
        $ubigeo_id = Ubigeo::devuelveId($codigo);
        $ubigeo = Ubigeo::find($ubigeo_id['id']);
        $r['ubigeo_id'] = (isset($ubigeo_id->id)) ? $ubigeo_id->id : null;
        $r['ubigeo_codigo'] = (isset($ubigeo->codigo)) ? $ubigeo->codigo : '';
        $r['ubigeo_descripcion'] = (isset($ubigeo->descripcion)) ? $ubigeo->descripcion : '';
        return $r;
    }

    public function buscar_pais(Request $request){
        $resultado = Nationality::select2($request->term);
        return $resultado;
    }
    public function list_datos_npk(){
        return DataTables::of(collect(Cart::instance($this->contactos)->content()))->make();
    }
    public function agregar_contactos(MaestroRequest $request)
    {
        $condicion = array(['tercero_id','=',$request->id]);
        $rowId = $this->agregar_carrito('tercero_contacto',$this->contactos,$condicion,$request);

    }

    public function editar_contactos(MaestroRequest $request){

        $this->editar_carrito('tercero_contacto',$this->contactos,$request->row_id_contacto,$request);

    }

    public function list_cuentas_bancarias(){
        return DataTables::of(collect(Cart::instance($this->cuentas_bancarias)->content()))->make();
    }

    public function buscar_condicion_pago(Request $request){
        $resultado = PaymentCondition::select2($request->term);
        return $resultado;
    }
    public function buscar_vendedor_cobrador(Request $request){
        $resultado = Seller::select2($request->term);
        return $resultado;
    }
    public function buscar_sucursal(Request $request){
        $resultado = Subsidiaries::select2($request->term);
        return $resultado;
    }
    public function agregar_cuentas_bancarias(MaestroRequest $request){

        $condicion = array(['tercero_id','=',$request->id]);
        $rowId = $this->agregar_carrito('tercero_cuenta',$this->cuentas_bancarias,$condicion,$request);

        $banco = Bank::findOrFail($request->banco_id_tercero_cuenta);
        $moneda = Currency::findOrFail($request->moneda_id_tercero_cuenta);
        $tipocuenta_descripcion = '';
        switch($request->tipocuenta_tercero_cuenta){
            case 'H':
                $tipocuenta_descripcion = 'Ahorro';
                break;
            case 'T':
                $tipocuenta_descripcion = 'Cta. Cte.';
                break;
            case 'D':
                $tipocuenta_descripcion = 'Detracción';
                break;
            case 'M':
                $tipocuenta_descripcion = 'Maestro';
                break;
            default:
                break;
        }

        $item = Cart::instance($this->cuentas_bancarias)->get($rowId);
        $options = $item->options->merge([
            'banco_codigo' => $banco->codigo,
            'moneda_descripcion' => $moneda->descripcion,
            'tipocuenta_descripcion' => $tipocuenta_descripcion,

        ]);

        Cart::instance($this->cuentas_bancarias)->update($rowId, [
            'options' => $options]);
    }
    public function editar_cuentas_bancarias(MaestroRequest $request){

        $rowId = $this->editar_carrito('tercero_cuenta',$this->cuentas_bancarias,$request->row_id_cuentasbancarias,$request);

        $banco = Bank::findOrFail($request->banco_id_tercero_cuenta);
        $moneda = Currency::findOrFail($request->moneda_id_tercero_cuenta);

        $tipocuenta_descripcion = '';
        switch($request->tipocuenta_tercero_cuenta){
            case 'H':
                $tipocuenta_descripcion = 'Ahorro';
                break;
            case 'T':
                $tipocuenta_descripcion = 'Cta. Cte.';
                break;
            case 'D':
                $tipocuenta_descripcion = 'Detracción';
                break;
            case 'M':
                $tipocuenta_descripcion = 'Maestro';
                break;
            default:
                break;
        }

        $item = Cart::instance($this->cuentas_bancarias)->get($rowId);
        $options = $item->options->merge([
            'banco_codigo' => $banco->codigo,
            'moneda_descripcion' => $moneda->descripcion,
            'tipocuenta_descripcion' => $tipocuenta_descripcion,

        ]);

        Cart::instance($this->cuentas_bancarias)->update($rowId, [
            'options' => $options]);
    }

    public function buscar_marca_tercero(Request $request){
        $resultado = Marca::select2($request->term);
        return $resultado;
    }

    public function list_tercero_marca(){
        return DataTables::of(collect(Cart::instance($this->marcas)->content()))->make();
    }

    public function agregar_tercero_marca(MaestroRequest $request){
        $condicion = array(['tercero_id','=',$request->id]);
        $rowId = $this->agregar_carrito('tercero_marca',$this->marcas,$condicion,$request);
        $marca = Marca::findOrFail($request->marca_id_tercero_marca);

        $item = Cart::instance($this->marcas)->get($rowId);
        $options = $item->options->merge([
            'marca_codigo' => $marca->codigo,
            'marca_descripcion' => $marca->descripcion,
        ]);

        Cart::instance($this->marcas)->update($rowId, [
            'options' => $options]);
    }

    public function editar_tercero_marca(MaestroRequest $request){

        $rowId = $this->editar_carrito('tercero_marca', $this->marcas, $request->row_id_marca, $request);
        $marca = Marca::findOrFail($request->marca_id_tercero_marca);

        $item = Cart::instance($this->marcas)->get($rowId);
        $options = $item->options->merge([
            'marca_codigo' => $marca->codigo,
            'marca_descripcion' => $marca->descripcion,
        ]);

        Cart::instance($this->marcas)->update($rowId, [
            'options' => $options]);
    }

    public function list_tercero_rubro(){
        return DataTables::of(collect(Cart::instance($this->rubro)->content()))->make();
    }

    public function agregar_tercero_rubro(MaestroRequest $request){
        $condicion = array(['tercero_id','=',$request->id]);
        $rowId = $this->agregar_carrito('tercero_rubro',$this->rubro,$condicion,$request);
        $tiporubro = TypeHeading::findOrFail($request->tiporubro_id_tercero_rubro);

        $item = Cart::instance($this->rubro)->get($rowId);
        $options = $item->options->merge([
            'rubro_codigo' => $tiporubro->codigo,
            'rubro_descripcion' => $tiporubro->descripcion,
        ]);

        Cart::instance($this->rubro)->update($rowId, [
            'options' => $options]);
    }

    public function editar_tercero_rubro(MaestroRequest $request){

        $rowId = $this->editar_carrito('tercero_rubro', $this->rubro, $request->row_id_rubro, $request);
        $tiporubro = TypeHeading::findOrFail($request->tiporubro_id_tercero_rubro);

        $item = Cart::instance($this->rubro)->get($rowId);
        $options = $item->options->merge([
            'rubro_codigo' => $tiporubro->codigo,
            'rubro_descripcion' => $tiporubro->descripcion,
        ]);

        Cart::instance($this->rubro)->update($rowId, [
            'options' => $options]);
    }

    public function agregar_tercero_direccion(MaestroRequest $request){
        $condicion = array(['tercero_id','=',$request->id]);
        $rowId = $this->agregar_carrito('tercero_direccion',$this->direccion,$condicion,$request);
        $ubigeo = Ubigeo::findOrFail($request->ubigeo_id_tercero_direccion);

        $item = Cart::instance($this->direccion)->get($rowId);
        $options = $item->options->merge([
            'ubigeo_codigo' => $ubigeo->codigo,
            'ubigeo_completo' => $ubigeo->completo,
        ]);

        Cart::instance($this->direccion)->update($rowId, [
            'options' => $options]);
    }
    public function editar_tercero_direccion(MaestroRequest $request){
        $rowId = $this->editar_carrito('tercero_direccion', $this->direccion, $request->row_id_tercero_direccion, $request);
        $ubigeo = Ubigeo::findOrFail($request->ubigeo_id_tercero_direccion);

        $item = Cart::instance($this->direccion)->get($rowId);
        $options = $item->options->merge([
            'ubigeo_codigo' => $ubigeo->codigo,
            'ubigeo_completo' => $ubigeo->completo,
        ]);

        Cart::instance($this->direccion)->update($rowId, [
            'options' => $options]);
    }

    public function list_tercero_direccion(){
        return DataTables::of(collect(Cart::instance($this->direccion)->content()))->make();
    }

    public function agregar_tercero_empresa(MaestroRequest $request){
        $condicion = array(['tercero_id','=',$request->id]);
        $rowId = $this->agregar_carrito('tercero_empresa',$this->empresa,$condicion,$request);
    }

    public function list_tercero_empresa(){
        return DataTables::of(collect(Cart::instance($this->empresa)->content()))->make();
    }

    public function editar_tercero_empresa(MaestroRequest $request){
        $rowId = $this->editar_carrito('tercero_empresa', $this->empresa, $request->row_id_tercero_empresa, $request);
    }

    private function eliminar_instancias(){
        Cart::instance($this->contactos)->destroy();
        Cart::instance($this->cuentas_bancarias)->destroy();
        Cart::instance($this->marcas)->destroy();
        Cart::instance($this->rubro)->destroy();
        Cart::instance($this->direccion)->destroy();
        Cart::instance($this->empresa)->destroy();
    }

    public function valida_codigo(Request $request){

        $r['tipo_persona'] = 0;
        $r['documentoide_id'] = 0;
        $r['ubigeo'] = 0;
        $r['persona'] = 0;
        $r['estado'] = 'no';
        $data_tercero = 0;

        //validar la vista crear id = 0 | edit id > 0

        if($request->id == 0){
            if($this->verificar_codigo($request->codigo) != 0 ){
                $r['estado'] = "si";
            }else{
                $data_tercero = $this->data_tercero($request->codigo, $request->id);
            }
        }elseif($request->id > 0){
            $tercero = Customer::find($request->id);
            if($tercero){
                $codigo_existente = $this->verificar_codigo_edit($tercero, $request->codigo);
                if($codigo_existente == false){
                    $this->eliminar_locales_anexos(); //otro codigo consultado
                }
            }
            $data_tercero = $this->data_tercero($request->codigo, $request->id);
        }

        if($data_tercero != 0){
            $r['tipo_persona'] =  $data_tercero['tipopersona'];
            $r['documentoide_id'] = $data_tercero['documentoide_id'];
            $r['ubigeo'] = $data_tercero['ubigeo'];
            $r['persona'] = $data_tercero['persona'];
        }

        return $r;
    }

    public function verificar_codigo($codigo){
        $resultado = Customer::where('codigo', '=', $codigo)->count();
        return $resultado;
    }

    public function data_tercero($codigo, $tercero_id){
        $r['tipopersona'] = 0;
        $r['documentoide_id'] = 0;
        $r['ubigeo'] = 0;
        $r['persona'] = 0;

        $consumerController = new ConsumerController();

        switch(strlen($codigo)){

            case 8:

                Cart::instance($this->direccion)->destroy(); // ELIMINAR LOCALES ANEXOS

                $persona = $consumerController->consultar_dni($codigo);
                $persona = ($persona->data == []) ? null : $persona->data["0"]; 
 
                if($persona){
                    $documentoide = Identification::select('id')->where('codigo','DNI')->first();
                    $r['documentoide_id'] = $documentoide->id;
                    $r['tipopersona'] = 2;
                }
                $r['persona'] = $persona;
                break;

            case 11:

                $persona = $consumerController->consultar_ruc_contribuyente($codigo); 
                $persona = ($persona->data == []) ? null : $persona->data["0"]; 
                
                if($persona){
                    
                    $this->locales_anexos($persona->emp_ruc, $tercero_id);
                    
                    $ubigeo = $this->obtiene_ubigeo($persona->emp_ubigeo);

                    $tipo = substr($codigo, 0,2);

                    if($tipo == 20){

                        $r['tipopersona'] = 1;

                    }elseif($tipo == 10){

                        $r['tipopersona'] = 2;

                    }

                    $documentoide = Identification::select('id')->where('codigo','RUC')->first();
                    $r['documentoide_id'] = $documentoide->id;
                    $r['ubigeo'] = $ubigeo;
                }

                $r['persona'] = $persona;

                break;
            default:
                break;
        }
        
        return $r;
    }

    public function verificar_codigo_edit($tercero_bd, $codigo_vista){
        if($tercero_bd->codigo == $codigo_vista){
            $respuesta = true;
        }else{
            $respuesta = false;
        }
        return $respuesta;
    }

    public function locales_anexos($ruc, $tercero_id){

        $obj = $this->consulta_locales_anexos($ruc);

        foreach ($obj as $local) {

            $via_nombre = $this->construir_via_nombre($local);
            $comprobar = false;
            foreach (Cart::instance($this->direccion)->content() as $cart) {
                if(trim(str_replace(" ","",$cart->options->via_nombre)) == trim(str_replace(" ","",$via_nombre))){
                    $comprobar = true;
                }
            }

            if($comprobar == false){

                $ultimoCart = Cart::instance($this->direccion)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

                if ($ultimoCart == null) {
                    $ultimoBd = CustomerAddress::where('tercero_id', $tercero_id)->get()->last();
                    $item = !$ultimoBd ? 0 : $ultimoBd->item;

                } else {
                    $item = $ultimoCart->options->item;;
                }

                $item_env = $item + 1;
                $ubigeo = Ubigeo::devuelveId($local->loc_ubigeo);
                if($ubigeo){
                    Cart::instance($this->direccion)->add(['id' => $item_env, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                        'options' => [
                            'via_nombre' => $via_nombre,
                            'ubigeo_codigo' => $local->loc_ubigeo,
                            'ubigeo_completo' => $ubigeo->completo,
                            //'tercero_id' => '',
                            'item' => $item_env,
                            'ubigeo_id' => $ubigeo->id,
                            //'usuario' => '',
                        ]
                    ]);
                }

            }
        }
    }
    public function consulta_locales_anexos($ruc){
                
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://demosprinter.anikamagroup.com/locales-anexos/' . $ruc);
        $result = curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($result);
        
        return $obj;
    }
    public function construir_via_nombre($local){
        //$tipo_zona = ($local->loc_tipo_zona != '' || $local->loc_tipo_zona != '-') ? $local->loc_tipo_zona : '';
        //$codigo_zona = ($local->loc_codigo_zona != '' || $local->loc_codigo_zona != '-') ? $local->loc_codigo_zona : '';
        $tipo_via = ($local->loc_tipo_via != "-") ? ' '. $local->loc_tipo_via . ' ': '';
        $nombre_via = ($local->loc_nombre_via != "-") ? ' '. $local->loc_nombre_via . ' ' : '';
        $numero_via = ($local->loc_numero != " - ") ? ' NRO. ' . $local->loc_numero . ' ' : '';
        $kilometro = ($local->loc_kilometro != '-') ? ' KM. ' . $local->loc_kilometro . ' ' : '';
        $manzana = ($local->loc_manzana != "-") ? ' MZA. ' . $local->loc_manzana . ' ' : '';
        $interior = ($local->loc_interior != "-") ? ' INT. ' . $local->loc_interior . ' ' : '';
        $departamento = ($local->loc_departamento != "-") ? ' DPTO. ' . $local->loc_departamento . ' ' : '';
        $lote = ($local->loc_lote != "-") ? ' LOTE ' . $local->loc_lote  . ' ': '';

        $via_nombre = $tipo_via . $nombre_via . $numero_via . $kilometro . $manzana . $interior . $departamento . $lote;

        return $via_nombre;
    }
    public function eliminar_locales_anexos(){
        foreach (Cart::instance($this->direccion)->content() as $cart) {
            session()->push($this->direccion, $cart->options->item);
        }
        Cart::instance($this->direccion)->destroy();
    }
    public function buscar_tercero(Request $request){
        $resultado = Customer::select2($request->term);
        return $resultado;
    }
}
