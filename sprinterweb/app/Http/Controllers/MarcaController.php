<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Marks\Entities\Marca;
use App\Models\Entities\Modelo;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\MaestroRequest;

class MarcaController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'trademarks';
    private $instancia = 'lista_modelo';
    private $tabla_cabecera = 'marca';

    protected $user;
    protected $privilegios = '01.01.03';

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
        $data['view'] = link_view('Maestros', 'Productos', 'Marcas y Modelos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('trademarks.list', $data);
    }

    public function list()
    {
        $marks = Marca::all();
        return Datatables::of($marks)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Marca::all()->Where('estado', 1)->last();
        if ($brand->codigo < 10){
            $data['codigo'] = str_pad($brand->codigo +1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo'] = $brand->codigo +1;
        }
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['instancia'] = $this->instancia;
        $data['route'] = route('trademarks');
        $data['view'] = link_view('Maestros', 'Productos', 'Marcas y Modelos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->eliminar_instancias();

        return view('trademarks.create', $data);
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
            $marca = new Marca();
            $marca->codigo = $request->code_marca;
            $marca->descripcion = $request->tradeName;
            $marca->estado = 1;
            $marca->usuario = Session::get('usuario');

            $id = null;
            $id = DB::transaction(function () use ($marca) {
                $marca->save();
                $this->guardar_modelo($marca->id);
                return $marca->id;
            });
            $r["ruta"] = route('edit.trademarks', $id);
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

    public function edit($id)

    {
        $this->eliminar_instancias();
        $data['var'] = $this->var;
        $data['marca'] = Marca::findOrFail($id);
        $data['route'] = route('trademarks');
        $data['instancia'] = $this->instancia;
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Maestros', 'Productos', 'Marcas y Modelos', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($data['marca']->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->llenar_modelos($data['marca']->id);

        return view('trademarks.edit', $data);
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
            $marca = Marca::findOrFail($id);
            $marca->codigo = $request->code_marca;
            $marca->descripcion = $request->tradeName;
            DB::transaction(function () use ($marca) {
                $marca->save();
                $this->guardar_modelo($marca->id);
            });

            $r["ruta"] = route('edit.trademarks', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
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

    public function activar_anular_marca(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Marca::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function lista_modelo()
    {
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }
    public function eliminar_instancias()
    {
        Cart::instance($this->instancia)->destroy();
    }

    public function autogenerar_codigo(MaestroRequest $request)
    {
        //dd($request->id);
        $cantidad = Cart::instance($this->instancia)->count();
        if ($cantidad == 0){
            $subcodigo = $cantidad + 1;
            if ($subcodigo < 10) {
                $codigo = $request->id . '00' . $subcodigo;
            } elseif ($subcodigo >= 10 && $subcodigo < 100) {
                $codigo = $request->id . '0' . $subcodigo;
            } else {
                $codigo = $request->id . $subcodigo;
            }
        }else{
            $cantidad = Cart::instance($this->instancia)->content()->sortByDesc("codigo")->first();
            $codigo = $cantidad->options->codigo + 1;
        }



        return $codigo;
    }

    public function agregar_item(Request $request)
    {
        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo

        if ($ultimoCart == null) {
            $ultimoBd = Modelo::where('marca_id', $request->marca_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;
        } else {
            $item = $ultimoCart->options->item;;
        }

        Cart::instance($this->instancia)->add(['id' => $item + 1, 'name' => 'hola', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'id' => '',
                'marca_id' => $request->marca_id,
                'codigo' => $request->modelo_id,
                'descripcion' => $request->modelo_descripcion,
                'nombrecomercial' => $request->modelo_nombrecomercial
            ]]);

    }
    public function update_item(MaestroRequest $request)
    {
        $item = Cart::instance($this->instancia)->get($request->row_id);

        $options = $item->options->merge([
            'marca_id' => $request->marca_id,
            'codigo' => $request->modelo_id,
            'descripcion' => $request->modelo_descripcion,
            'nombrecomercial' => $request->modelo_nombrecomercial
        ]);

        Cart::instance($this->instancia)->update($request->row_id, [
            'options' => $options
        ]);

    }

    public function eliminar_modelo(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);
        $id = $request->id;
        session()->push('marca_id', $id);
    }

    private function guardar_modelo($marca_id){
        $modelo = Session::get('marca_id');
        if($modelo){
            foreach ($modelo as $id) {
                Modelo::where('id', $id)->delete();
                session()->put('marca_id', []);
            }
        }
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            $existe = Modelo::where('marca_id',$cart->options->marca_id)->where('codigo', $cart->options->codigo)->exists();
            if($existe){
                //$data['marca_id'] = ($cart->options->marca_id != '') ? $cart->options->marca_id : '';
                $data['codigo'] = ($cart->options->codigo != '') ? $cart->options->codigo : '';
                $data['descripcion'] = ($cart->options->descripcion != '') ? $cart->options->descripcion : '';
                $data['nombrecomercial'] = ($cart->options->nombrecomercial != '') ? $cart->options->nombrecomercial : '';
                $data['usuario'] = Session::get('usuario');
                Modelo::update_data($cart->options->marca_id, $cart->options->codigo,$data);
            }else{
                $modelo = new Modelo();
                $modelo->marca_id = $marca_id;
                $modelo->codigo = $cart->options->codigo;
                $modelo->descripcion = ($cart->options->descripcion != '') ? $cart->options->descripcion : '';
                $modelo->nombrecomercial = ($cart->options->nombrecomercial != '') ? $cart->options->nombrecomercial : '';
                $modelo->usuario = Session::get('usuario');
                $modelo->save();
            }
        }
    }

    private function llenar_modelos($marca_id){
        $modelo = Modelo::where('marca_id',$marca_id)->get();
        foreach($modelo as $p){
            Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $p->id,
                    'marca_id' => $p->marca_id,
                    'codigo' => $p->codigo,
                    'descripcion' => $p->descripcion,
                    'nombrecomercial' => $p->nombrecomercial,
                ]
            ]);
        }
    }

}
