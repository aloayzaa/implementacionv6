<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\MeasurementUnits\Entities\UmedidaConversion;
use App\Privileges\Entities\Privileges;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use PDOException;
class MeasurementController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'measurements';
    private $instancia_um = 'instancia unidad de medida';
    private $tabla_cabecera = 'umedida';

    protected $user;
    protected $privilegios = '01.01.05';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Marcas', 'Productos', 'Unidades de Medida', '');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('measurementsUnits.list', $data);
    }

    public function list()
    {
        $measurementsUnits = UnidadesMedida::all();
        return DataTables::of($measurementsUnits)->make();
    }

    public function list_unidadmedida()
    {
        return DataTables::of(collect(Cart::instance($this->instancia_um)->content()))->make();
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
        $data['unidades'] = UnidadesMedida::all()->where('estado', 1);
        $data['view'] = link_view('Marcas', 'Productos', 'Unidades de Medida', '');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $this->eliminar_instancias();
        return view('measurementsUnits.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(MaestroRequest $request)
    {
        try{
            $umedida = new UnidadesMedida();
            $umedida->codigo = $request->txt_codigo_unidad;
            $umedida->descripcion = $request->txt_descripcion_unidad;
            $umedida->codsunat = ($request->txt_codigo_sunat == '') ? '' : $request->txt_codigo_sunat;
            $umedida->simbolo = '';
            $umedida->tipo = '';
            $umedida->estado = 1;
            $umedida->usuario = Session::get('usuario');

            $id = null;
            $id = DB::transaction(function () use ($umedida) {
                $umedida->save();
                $this->guardar_unidad_conversion($umedida->id);
                $id = $umedida->id;
                return $id;
            });
            $r["ruta"] = route('edit.measurements', $id);

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        }catch(PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar!";
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
        $this->eliminar_instancias();
        $unidad = UnidadesMedida::findOrFail($id);
        $data['umedida'] = $unidad;
        $data['proceso'] = 'edita';
        $data['route'] = route('measurements');
        $data['var'] = $this->var;
        $data['unidades'] = UnidadesMedida::all()->where('estado', 1);
        $data['view'] = link_view('Marcas', 'Productos', 'Unidades de Medida', '');
        $data['header'] = headeroptions($this->var, 'edita','',($unidad->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $this->llenar_conversion($unidad->id);

        return view('measurementsUnits.edit', $data);
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
            $umedida = UnidadesMedida::findOrFail($id);
            $umedida->codigo = $request->txt_codigo_unidad;
            $umedida->descripcion = $request->txt_descripcion_unidad;
            $umedida->codsunat = ($request->txt_codigo_sunat == '') ? '' : $request->txt_codigo_sunat;
            $umedida->simbolo = '';
            $umedida->tipo = '';
            //$umedida->estado = ;
            $umedida->usuario = Session::get('usuario');
            $id = null;
            $id = DB::transaction(function () use ($umedida) {
                $umedida->save();
                $this->guardar_unidad_conversion($umedida->id);
                $id = $umedida->id;
                return $id;

            });
            $r["ruta"] = route('edit.measurements', $id);
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
    public function destroy($id)
    {
        //
    }

    public function eliminar_instancias()
    {
        Cart::instance($this->instancia_um)->destroy();
    }

    public function eliminar_unidad_conversion(Request $request)
    {
        Cart::instance($this->instancia_um)->remove($request->rowId);
        $id = $request->id;
        session()->push('parent_id', $id);
    }

    public function agregar_item(MaestroRequest $request)
    {
        $r = array();
        $comparar = false;
        $ultimoCart = Cart::instance($this->instancia_um)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo
        if ($ultimoCart == null) {
            $ultimoBd = UmedidaConversion::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;
        }
        if (count(Cart::instance($this->instancia_um)->content()) == 0){
            $this->carrito_unidad($request,$item);
        }else{
            foreach (Cart::instance($this->instancia_um)->content() as $cart){
                ($request->cbo_um == $cart->options->umedida_id) ? $comparar = true : false;
            }
            if ($comparar == true){
                $r['warning'] = 'El dato ya se encuentra registrado en el detalle';
                return $r;
            }else{
                $this->carrito_unidad($request,$item);
            }
        }
    }

    public function carrito_unidad($request, $item){
        $unidad = UnidadesMedida::select('id', 'codigo','descripcion')->where('id',$request->cbo_um)->where('estado', 1)->first();
        Cart::instance($this->instancia_um)->add(['id' => $item + 1, 'name' => 'hola', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'id' => '',
                'umedida_id' => $unidad->id,
                'codigo' => $unidad->codigo,
                'descripcion' => $unidad->descripcion,
                'factor' => (float)$request->txt_factor
            ]]);
    }

    public function update_item(Request $request)
    {
        //dd($request->all());
        $item = Cart::instance($this->instancia_um)->get($request->row_id);

        $unidad = UnidadesMedida::select('id', 'codigo','descripcion')->where('id',$request->cbo_um)->where('estado', 1)->first();
        $options = $item->options->merge([
            'umedida_id' => $unidad->id,
            'codigo' => $unidad->codigo,
            'descripcion' => $unidad->descripcion,
            'factor' => (float)$request->txt_factor
        ]);

        Cart::instance($this->instancia_um)->update($request->row_id, [
            'options' => $options
        ]);

    }

    private function guardar_unidad_conversion($parent_id){
        $unidad_conversion = Session::get('parent_id');
        if($unidad_conversion){
            foreach ($unidad_conversion as $id) {
                UmedidaConversion::where('parent_id', $id)->delete();
                session()->put('parent_id', []);
            }
        }
        //dd(Cart::instance($instancia)->content());
        foreach (Cart::instance($this->instancia_um)->content() as $cart) {
            $existe = UmedidaConversion::where('parent_id',$cart->options->id)->exists();
            //dd($existe);
            if($existe){
                //$data['parent_id'] = ($cart->options->id != '') ? $cart->options->id : '';
                //$data['umedida_id'] = ($cart->options->umedida != '') ? $cart->options->umedida : '';
                $data['factor'] = ($cart->options->factor != '') ? (float)$cart->options->factor : '';
                //$data['usuario'] = Session::get('usuario');
                UmedidaConversion::update_data($cart->options->id, $cart->options->umedida_id, $data);
            }else{
                $unidad_conversion = new UmedidaConversion();
                $unidad_conversion->parent_id = $parent_id;
                $unidad_conversion->umedida_id = $cart->options->umedida_id;
                $unidad_conversion->factor = (float)$cart->options->factor;
                $unidad_conversion->usuario = Session::get('usuario');
                $unidad_conversion->save();
            }
        }
    }

    private function llenar_conversion($unidad_id){
        $conversion = UmedidaConversion::where('parent_id',$unidad_id)->get();
        foreach($conversion as $p){
            Cart::instance($this->instancia_um)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $p->parent_id,
                    'umedida_id' => $p->unidadesmedida['id'],
                    'codigo' => $p->unidadesmedida['codigo'],
                    'descripcion' => $p->unidadesmedida['descripcion'],
                    'factor' => (float)$p->factor,
                ]
            ]);
        }
    }

    public function unidad_medida($id){
        $resultado = UnidadesMedida::generar_unidad($id);
        return $resultado;
    }
}
