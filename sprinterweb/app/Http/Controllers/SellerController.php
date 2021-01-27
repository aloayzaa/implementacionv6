<?php

namespace App\Http\Controllers;

use App\Costs\Entities\Costs;
use App\Http\Requests\MaestroRequest;
//use App\Warehouses\Entities\WarehouseOrderDetail;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Seller\Entities\Seller;
use App\Customers\Entities\Customer;
use App\Users\Entities\UserByCompany;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Marks\Entities\Marca;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Seller\Entities\CommissionSeller;
class SellerController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'seller';
    private $instancia = 'lista_comision_marca';
    private $tabla_cabecera = 'vendedor';

    protected $user;
    protected $privilegios = '01.02.03';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }

    public function list(Request $request)
    {
        $seller = Seller::all();
        return Datatables::of($seller)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Vendedores','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('seller.list', $data);
    }

    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Vendedores','');
        $data['marca'] = Marca::all()->where('estado', 1);
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['personas'] =  Customer::personal_asociado();
        $data['usuarios'] = UserByCompany::all()->where('estado', 1);
        $data['ctactebanco'] = BankCurrentAccount::datos_banco();
        $this->eliminar_instancias();

        return view('seller.create', $data);
    }

    public function dataset(Request $request, $id)
    {
        $marca = Marca::find($id);
        $data['descripcion'] = $marca->descripcion;
        return $data;
    }

    public function store(MaestroRequest $request)
    {
        try {
            $vendedor = new Seller();
            $vendedor->codigo = $request->txt_codigo_vendedores;
            $vendedor->descripcion = $request->txt_descripcion_vendedores;
            $vendedor->telefono = $request->txt_telefono;
            $vendedor->email = $request->txt_email;
            $vendedor->persona_id = $request->cbo_personal_asociado;
            $vendedor->usuario_id = $request->cbo_usuario_asociado;
            $vendedor->ctactebanco_id = $request->cbo_ctacte_asociado;
            $vendedor->metaxmarca = (isset($request->chk_aplica_metamarca) && $request->chk_aplica_metamarca == "on") ? 1 : 0;
            $vendedor->usuario = Session::get('usuario');
            $vendedor->estado = 1;
            $id = null;
            $id = DB::transaction(function () use ($vendedor) {
                $vendedor->save();
                $this->guardar_vendedor_comision($vendedor->id);
                return $vendedor->id;
            });
            $r["ruta"] = route('edit.seller', $id);
            $r["estado"] = "ok";
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;
        $this->eliminar_instancias();
        return $r;
    }

    public function edit($id)
    {
        $this->eliminar_instancias();
        $vendedor = Seller::findOrFail($id);
        $data['vendedor'] = $vendedor;
        $data['proceso'] = 'edita';
        $data['route'] = route('seller');
        $data['var'] = $this->var;
        $data['marca'] = Marca::where('estado', 1)->get();
        $data['view'] = link_view('Maestros', 'Terceros', 'Vendedores','');
        $data['header'] = headeroptions($this->var, 'edita','',($vendedor->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['personas'] =  Customer::personal_asociado();
        $data['usuarios'] = UserByCompany::all();
        $data['ctactebanco'] = BankCurrentAccount::datos_banco($id);

        $this->llenar_vendedor_comision($vendedor->id);

        return view('seller.edit', $data);
    }

    public function update(MaestroRequest $request, $id)
    {
        try {
            $vendedor = Seller::findOrfail($id);
            $vendedor->codigo = $request->txt_codigo_vendedores;
            $vendedor->descripcion = $request->txt_descripcion_vendedores;
            $vendedor->telefono = $request->txt_telefono;
            $vendedor->email = $request->txt_email;
            $vendedor->persona_id = $request->cbo_personal_asociado;
            $vendedor->usuario_id = $request->cbo_usuario_asociado;
            $vendedor->ctactebanco_id = $request->cbo_ctacte_asociado;
            $vendedor->metaxmarca = (isset($request->chk_aplica_metamarca) && $request->chk_aplica_metamarca == "on") ? 1 : 0;
            $vendedor->usuario = Session::get('usuario');
            $vendedor->estado = 1;
            $id = null;
            $id = DB::transaction(function () use ($vendedor) {
                $vendedor->save();
                $this->guardar_vendedor_comision($vendedor->id);
                $id = $vendedor->id;
                return $id;
            });
            $r["ruta"] = route('edit.seller', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;
        return $r;
    }

    public function agregar_item(MaestroRequest $request)
    {

        $instancia = 'detalle_vendedor_comision';
        $ultimoCart = Cart::instance($instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo


        if ($ultimoCart == null) {
            $ultimoBd = CommissionSeller::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;;
        }

        $marca = Marca::select('codigo','descripcion')->where('id',$request->modal_marca_id)->first();
        Cart::instance($instancia)->add(['id' => $item + 1, 'name' => 'hola', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'id' => '',
                'item' => (int)$item + 1,
                'marca_id' => $request->modal_marca_id,
                'codigo' => $marca->codigo,
                'descripcion' => $marca->descripcion,
                'comision' => $request->modal_comision,
                'desde' => ($request->modal_desde == '') ? '0000-00-00' : $request->modal_desde,
                'hasta' => ($request->modal_hasta == '') ? '0000-00-00' : $request->modal_hasta,
                'meta' => $request->modal_meta,
            ]]);

    }

    public function list_marca_comision()
    {
        $instancia = 'detalle_vendedor_comision';
        //dd(cart::instance($this->instancia)->content());
        return DataTables::of(collect(Cart::instance($instancia)->content()))->make();
    }

    public function update_item(MaestroRequest $request)
    {
        $instancia = 'detalle_vendedor_comision';

        $item = Cart::instance($instancia)->get($request->row_id);

        $marca = Marca::select('codigo','descripcion')->where('id',$request->modal_marca_id)->first();
        $options = $item->options->merge([
            'marca_id' => $request->modal_marca_id,
            'codigo' => $marca->codigo,
            'descripcion' => $marca->descripcion,
            'comision' => $request->modal_comision,
            'desde' => ($request->modal_desde == '') ? '0000-00-00' : $request->modal_desde,
            'hasta' => ($request->modal_hasta == '') ? '0000-00-00' : $request->modal_hasta,
            'meta' => $request->modal_meta,
        ]);

        Cart::instance($instancia)->update($request->row_id, [
            'options' => $options
        ]);

    }

    public function eliminar_datos_comision(Request $request)
    {
        $instancia = 'detalle_vendedor_comision';
        Cart::instance($instancia)->remove($request->rowId);
        $id = $request->id;
        session()->push('parent_id', $id);
    }

    private function guardar_vendedor_comision($parent_id){
        $instancia = 'detalle_vendedor_comision';
        $vendedor_comision_id = Session::get('parent_id');
        if($vendedor_comision_id){
            foreach ($vendedor_comision_id as $id) {
                CommissionSeller::where('parent_id', $id)->delete();
                session()->put('parent_id', []);
            }
        }
        //dd(Cart::instance($instancia)->content());
        foreach (Cart::instance($instancia)->content() as $cart) {
            $existe = CommissionSeller::where('parent_id',$cart->options->id)->exists();
            //dd($existe);
            if($existe){
                $data['parent_id'] = ($cart->options->id != '') ? $cart->options->id : '';
                $data['item'] = $cart->options->item;
                $data['marca_id'] = ($cart->options->marca_id != '') ? $cart->options->marca_id : '';
                $data['comision'] = ($cart->options->comision != '') ? $cart->options->comision : '';
                $data['desde'] = ($cart->options->desde != '') ? $cart->options->desde : '';
                $data['hasta'] = ($cart->options->hasta != '') ? $cart->options->hasta : '';
                $data['meta'] = ($cart->options->meta != '') ? $cart->options->meta : '';
                //$data['usuario'] = Session::get('usuario');
                CommissionSeller::update_data($cart->options->id,$cart->options->item,$data);
            }else{
                $vendedor_comision = new CommissionSeller();
                $vendedor_comision->parent_id = $parent_id;
                $vendedor_comision->item = $cart->options->item;
                $vendedor_comision->marca_id = $cart->options->codigo;
                $vendedor_comision->comision = ($cart->options->comision != '') ? $cart->options->comision : '';
                $vendedor_comision->desde = ($cart->options->desde != '') ? $cart->options->desde : '';
                $vendedor_comision->hasta = ($cart->options->hasta != '') ? $cart->options->hasta : '';
                $vendedor_comision->meta = ($cart->options->meta != '') ? $cart->options->meta : '';
                //$vendedor_comision->usuario = Session::get('usuario');
                $vendedor_comision->save();
            }
        }
    }

    private function llenar_vendedor_comision($vendedor_id){
        $instancia = 'detalle_vendedor_comision';
        $vendedor_comision = CommissionSeller::where('parent_id',$vendedor_id)->get();
        foreach($vendedor_comision as $p){
            Cart::instance($instancia)->add(['id' => $p->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $p->parent_id,
                    'item' => $p->item,
                    'codigo' => $p->marca['codigo'],
                    'descripcion' => $p->marca['descripcion'],
                    'marca_id' => $p->marca_id,
                    'comision' => $p->comision,
                    'desde' => $p->desde,
                    'hasta' => $p->hasta,
                    'meta' => $p->meta
                ]
            ]);
        }
    }

    public function eliminar_instancias()
    {
        $instancia = 'detalle_vendedor_comision';
        Cart::instance($instancia)->destroy();
    }
}
