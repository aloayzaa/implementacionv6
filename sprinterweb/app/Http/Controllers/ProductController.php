<?php

namespace App\Http\Controllers;

use App\Color\Entities\Color;
use App\Panel\Companies\Entities\Pempresa;
use App\Families\Entities\Familia;
use App\Http\Requests\ProductsDetailsRequest;
use App\Http\Traits\Controls\BasicSelector;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Marks\Entities\Marca;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\Models\Entities\Modelo;
use App\MovementTypes\Entities\MovementType;
use App\Nutrient\Entities\Nutrient;
use App\Products\Entities\Productos;
use App\Products\Entities\ProductoSunat;
use App\ProductGroup\Entities\ProductGroup;
use App\Products\Entities\ProductoUbicacion;
use App\Warehouses\Entities\WareHouses;
use App\Products\Entities\ProductoNPK;
use App\Parancelaria\Entities\Parancelaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PDOException;
use Yajra\DataTables\DataTables;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Requests\ProductsRequest;
class ProductController extends Controller
{
    use BasicSelector, Tables, UserPrivileges;
    private $instancia_almacen = 'ubicacion_almacen';
    private $instancia_npk = 'datos_npk';
    protected $var = 'products';
    private $tabla_cabecera = "producto";
    protected $user;
    protected $privilegios = '01.01';
    private $cgencodigo;
    private $nlencodigo;

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';

        $this->cgencodigo = ($cgencodigo = Pempresa::get_parametro('PRD_CODIGO')->valor) ? $cgencodigo : "AUTOMATICO";
        $this->nlencodigo = ($nlencodigo = Pempresa::get_parametro('PRD_LONGIT')->valor) ? $nlencodigo : 10; // LONGITUD DE PRODUCTOS PARA CALCULAR CORRELATIVO

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->eliminar_variables_de_sesion();

        $data['var'] = $this->var;
        $data['route'] = $request->route;
        $data['proceso'] = $request->proceso;
        $data['view'] = link_view('Maestros', 'Productos', 'Cátalogo de Productos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('products.list', $data);
    }

    public function list()
    {
        $products = Productos::lista_producto_familia();
        //$products = Productos::all();
        return DataTables::of($products)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->eliminar_variables_de_sesion();

        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['families'] = Familia::all()->where('estado',1);
        $data['view'] = link_view('Maestros', 'Productos', 'Cátalogo de Productos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $almacen = WareHouses::all()->where('estado',1);
        $data['selector_ubicacion_almacen'] = $this->selector_basico($almacen, 'ubicacion_almacen');
        $data['nutriente'] = Nutrient::all()->where('estado',1);

        $this->eliminar_instancias();

        return view('products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsRequest $request)
    {
        //dd($request->all());
        try {
            $nombre_foto = '';
            $ruc = Session::get('company_ruc');
            $file = $request->file('imagen');
            if ($file != '') {
                $nombre_foto = rand() . '_' . $ruc . '_' . $file->getClientOriginalName();
                Storage::disk('images')->put($nombre_foto, file_get_contents($file));
            }

            if(isset($request->mark)){
                $mark = Marca::findOrFail($request->mark);
                $mark_descripcion = $mark->descripcion;
            }else{
                $mark_descripcion = '';
            }

            if(isset($request->model)){
                $model = Marca::findOrFail($request->model);
                $model_descripcion = $model->descripcion;
            }else{
                $model_descripcion = '';
            }

            $tipo_producto = '';
            if ($request->opt_tipoproducto == 1){
                $tipo_producto = 'M';
            }elseif($request->opt_tipoproducto == 2){
                $tipo_producto = 'S';
            }elseif($request->opt_tipoproducto == 3){
                $tipo_producto = 'A';
            }

            $tipo_mercaderia = '';
            if ($tipo_producto == 'S'){
                if($request->opt_mercaderia == 3){
                    $tipo_mercaderia = 'T';
                }else{
                    $tipo_mercaderia = '';
                }
            }else{
                if ($request->opt_mercaderia == 1){
                    $tipo_mercaderia = 'M';
                }elseif($request->opt_mercaderia == 2){
                    $tipo_mercaderia = 'I';
                }elseif($request->opt_mercaderia == 3){
                    $tipo_mercaderia = 'T';
                }
            }

            $producto = new Productos;
            $producto->codigo = $request->txt_codigo_producto;
            $producto->cumseps = ($request->cumseps != '') ? $request->cumseps : '';
            $producto->descripcion = strtoupper($request->txt_descripcion_producto);
            $producto->nombrecomercial = ($request->name != '') ? $request->name : '';
            $producto->familiapdto_id = $request->cbo_familia;
            $producto->umedida_id = $request->kardex;
            $producto->ucompra_id = (isset($request->compra)) ? $request->compra : null;
            $producto->conversion = $request->conversion;
            $producto->marca_id = (isset($request->mark)) ? $request->mark : null;
            $producto->modelo_id = (isset($request->model)) ? $request->model : null;
            $producto->grupoproducto_id = (isset($request->txt_grupo)) ? $request->txt_grupo : null;
            $producto->marca = $mark_descripcion;
            $producto->modelo = $model_descripcion;
            $producto->color_id = $request->color;
            $producto->caracteristicas = ($request->otros != '') ? $request->otros : '';
            $producto->ean = ($request->ean != '') ? $request->ean : '';
            $producto->peso = ($request->peso != '') ? $request->peso : '';
            $producto->volumen = ($request->volumen != '') ? $request->volumen : '';
            $producto->longitud = ($request->longitud != '') ? $request->longitud : '';
            $producto->talla = ($request->talla != '') ? $request->talla : '';
            $producto->stkminimo = ($request->sminimo != '') ? $request->sminimo : '';
            $producto->stkmaximo = ($request->smaximo != '') ? $request->smaximo : '';
            $producto->pidelote = (isset($request->chklote) && $request->chklote == "on") ? 1 : 0;
            $producto->pideserie = (isset($request->chkserie) && $request->chkserie == "on") ? 1 : 0;
            $producto->esafecto = (isset($request->chkafecto) && $request->chkafecto == "on") ? 1 : 0;
            $producto->escompuesto = 0;
            //$producto->estado = 1;
            $producto->ingactivo = ($request->txt_ingred_activo != '') ? $request->txt_ingred_activo : '';
            $producto->iac = ($request->txt_concentracion_ia != '') ? $request->txt_concentracion_ia : '';
            $producto->formulacion = ($request->txt_formulacion != '') ? $request->txt_formulacion : '';
            $producto->metodoaccion = ($request->txt_metodo_accion != '') ? $request->txt_metodo_accion : '';
            $producto->percepcion = (isset($request->chkpercepcion) && $request->chkpercepcion == "on") ? 1 : 0;
            $producto->consumo = (isset($request->chkconsumo) && $request->chkconsumo == "on") ? 1 : 0;
            $producto->compra = (isset($request->chkcompra) && $request->chkcompra == "on") ? 1 : 0;
            $producto->produce = 0;
            $producto->venta = (isset($request->chkvende) && $request->chkvende == "on") ? 1 : 0;
            $producto->aumentovta = (isset($request->chkaumento) && $request->chkaumento == "on") ? 1 : 0;
            $producto->merma = (isset($request->chkmerma) && $request->chkmerma == "on") ? 1 : 0;
            $producto->bonificado = (isset($request->chkbonifica) && $request->chkbonifica == "on") ? 1 : 0;
            $producto->tipoproducto = $tipo_producto;
            $producto->tipomercaderia = $tipo_mercaderia;
            $producto->foto = '';
            $producto->gastoadm = 0;
            $producto->utilidad = ($request->utilidad != '') ? $request->utilidad : '';
            $producto->tolerancia = ($request->toleracion != '') ? $request->toleracion : '';
            $producto->impuesto1_id = null;
            $producto->impuesto2_id = null;
            $producto->precio1 = 0;
            $producto->precio2 = 0;
            $producto->precio3 = 0;
            $producto->precio4 = 0;
            $producto->precio5 = 0;
            $producto->precio6 = 0;
            $producto->tipocarga = (isset($request->cbo_tipo_carga) && $request->cbo_tipo_carga > 0) ? $request->cbo_tipo_carga : 0;
            $producto->parancelaria_id = (isset($request->cbo_partida_arancelaria)) ? $request->cbo_partida_arancelaria : null;
            $producto->productosunat_id = (isset($request->sunat)) ? $request->sunat : null;
            $producto->usuario = Session::get('usuario');
            $producto->ruta_foto = '';
            $producto->archivo_foto = $nombre_foto;

            /*$producto->save();
            $this->guardar_producto_npk($producto->id);
            $this->guardar_producto_ubicacion($producto->id);
            $id = $producto->id;

            $r["ruta"] = route('edit.products', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";*/
            $id = null;
            $id = DB::transaction(function () use ($producto) {
                $producto->save();
                $this->guardar_producto_npk($producto->id);
                $this->guardar_producto_ubicacion($producto->id);
                $id = $producto->id;
                return $id;
            });
            $r["ruta"] = route('edit.products', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        }catch(PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar";
            $r["bd"] = $e->getMessage();
        }


        $this->eliminar_instancias();

        return $r;
    }

    private function guardar_producto_npk($producto_id){
        //dd(Cart::instance($this->instancia_npk)->content());
        $nutriente_ids = Session::get('nutriente_ids');
        if($nutriente_ids){
            foreach ($nutriente_ids as $id) {
                ProductoNPK::where('id', $id)->delete();
                session()->put('nutriente_ids', []);
            }
        }
        foreach (Cart::instance($this->instancia_npk)->content() as $cart) {
            $existe = ProductoNPK::where('id',$cart->options->id)->exists();
            if($existe){
                $data['nutriente_id'] = ($cart->options->nutriente_id != '') ? $cart->options->nutriente_id : '';
                $data['cantidad'] = ($cart->options->conc != '') ? $cart->options->conc : '';
                $data['usuario'] = Session::get('usuario');
                ProductoNPK::update_data($cart->options->id,$data);
            }else{
                $producto_npk = new ProductoNPK;
                $producto_npk->producto_id = $producto_id;
                $producto_npk->nutriente_id = $cart->options->nutriente_id;
                $producto_npk->cantidad = ($cart->options->conc != '') ? $cart->options->conc : '';
                $producto_npk->usuario = Session::get('usuario');
                $producto_npk->save();
            }
        }
    }
    private function guardar_producto_ubicacion($producto_id){
        $almacen_ids = Session::get('almacen_ids');
        if($almacen_ids){
            foreach ($almacen_ids as $id) {
                ProductoUbicacion::where('id', $id)->delete();
                session()->put('almacen_ids', []);
            }
        }
        foreach (Cart::instance($this->instancia_almacen)->content() as $cart) {
            $existe = ProductoUbicacion::where('id',$cart->options->id)->exists();
            if($existe){
                $data['almacen_id'] = ($cart->options->almacen_id != '') ? $cart->options->almacen_id : '';
                $data['ubicacion'] = ($cart->options->ubicacion != '') ? $cart->options->ubicacion : '';
                $data['usuario'] = Session::get('usuario');
                ProductoUbicacion::update_data($cart->options->id,$data);
            }else{
                $producto_ubicacion = new ProductoUbicacion;
                $producto_ubicacion->producto_id = $producto_id;
                $producto_ubicacion->almacen_id = $cart->options->almacen_id;
                $producto_ubicacion->ubicacion = ($cart->options->ubicacion != '') ? $cart->options->ubicacion : '';
                $producto_ubicacion->usuario = Session::get('usuario');
                $producto_ubicacion->save();
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {/*
        $data['product'] = Productos::findOrFail($id);
        $data['var'] = $this->var;
        $data['proceso'] = 'consulta';
        $data['productosunat'] = ProductoSunat::find($data['product']->productosunat_id);
        $data['families'] = Familia::all();
        $data['marks'] = Marca::all();
        $data['models'] = Modelo::all();
        $data['units'] = UnidadesMedida::all();
        $data['view'] = link_view('Maestros', 'Productos', 'Cátalogo de Productos', '');
        $data['route'] = route('products');

        $this->eliminar_instancias();

        return view('products.edit', $data);*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        session()->put('id', $id);

        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $producto = Productos::findOrFail($id);
        $data['product'] = $producto;
        $data['families'] = Familia::all();
        $almacen = WareHouses::all();
        $data['selector_ubicacion_almacen'] = $this->selector_basico($almacen, 'ubicacion_almacen');
        $data['nutriente'] = Nutrient::all();
        $data['view'] = link_view('Maestros', 'Productos', 'Cátalogo de Productos', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($producto->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->llenar_producto_npk($producto->id);
        $this->llenar_producto_ubicacion($producto->id);

        return view('products.edit', $data);
    }

    private function llenar_producto_ubicacion($producto_id){
        $producto_ubicacion = ProductoUbicacion::select('*')->where('producto_id',$producto_id)->get();
        foreach($producto_ubicacion as $p){
            Cart::instance($this->instancia_almacen)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $p->id,
                    'almacen_id' => $p->almacen['id'],
                    'codigo' => $p->almacen['codigo'],
                    'descripcion' => $p->almacen['descripcion'],
                    'ubicacion' => $p->ubicacion
                ]
            ]);
        }

    }
    private function llenar_producto_npk($producto_id){
        $producto_npk = ProductoNPK::select('*')->where('producto_id',$producto_id)->get();
        foreach($producto_npk as $p){
            Cart::instance($this->instancia_npk)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $p->id,
                    'nutriente_id' => $p->nutriente['id'],
                    'codigo' => $p->nutriente['codigo'],
                    'descripcion' => $p->nutriente['descripcion'],
                    'conc' => $p->cantidad
                ]
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductsRequest $request, $id)
    {
       //dd($request->all());
        try {
            $nombre_foto = '';
            $ruc = Session::get('company_ruc');
            $file = $request->file('imagen');
            if (isset($file) && $file != '') {
                if($request->imagen_antigua != ''){
                    File::delete('images/' . $request->imagen_antigua);
                }
                $nombre_foto = rand() . '_' . $ruc . '_' . $file->getClientOriginalName();
                Storage::disk('images')->put($nombre_foto, file_get_contents($file));
            }

            if(isset($request->mark)){
                $mark = Marca::findOrFail($request->mark);
                $mark_descripcion = $mark->descripcion;
            }else{
                $mark_descripcion = '';
            }

            if(isset($request->model)){
                $model = Marca::findOrFail($request->model);
                $model_descripcion = $model->descripcion;
            }else{
                $model_descripcion = '';
            }

            $tipo_producto = '';
            if ($request->opt_tipoproducto == 1){
                $tipo_producto = 'M';
            }elseif($request->opt_tipoproducto == 2){
                $tipo_producto = 'S';
            }elseif($request->opt_tipoproducto == 3){
                $tipo_producto = 'A';
            }

            $tipo_mercaderia = '';
            if ($tipo_producto == 'S'){
                if($request->opt_mercaderia == 3){
                    $tipo_mercaderia = 'T';
                }else{
                    $tipo_mercaderia = '';
                }
            }else{
                if ($request->opt_mercaderia == 1){
                    $tipo_mercaderia = 'M';
                }elseif($request->opt_mercaderia == 2){
                    $tipo_mercaderia = 'I';
                }elseif($request->opt_mercaderia == 3){
                    $tipo_mercaderia = 'T';
                }
            }

            $producto = Productos::findOrFail($id);
            $producto->codigo = $request->txt_codigo_producto;
            $producto->cumseps = ($request->cumseps != '') ? $request->cumseps : '';
            $producto->descripcion = strtoupper($request->txt_descripcion_producto);
            $producto->nombrecomercial = ($request->name != '') ? $request->name : '';
            $producto->familiapdto_id = $request->cbo_familia;
            //$producto->umedida_id = $request->kardex;
            //$producto->ucompra_id = (isset($request->compra)) ? $request->compra : null;
            //$producto->conversion = $request->conversion;
            $producto->marca_id = (isset($request->mark)) ? $request->mark : null;
            $producto->modelo_id = (isset($request->model)) ? $request->model : null;
            $producto->grupoproducto_id = (isset($request->txt_grupo)) ? $request->txt_grupo : null;
            $producto->marca = $mark_descripcion;
            $producto->modelo = $model_descripcion;
            $producto->color_id = $request->color;
            $producto->caracteristicas = ($request->otros != '') ? $request->otros : '';
            $producto->ean = ($request->ean != '') ? $request->ean : '';
            $producto->peso = ($request->peso != '') ? $request->peso : '';
            $producto->volumen = ($request->volumen != '') ? $request->volumen : '';
            $producto->longitud = ($request->longitud != '') ? $request->longitud : '';
            $producto->talla = ($request->talla != '') ? $request->talla : '';
            $producto->stkminimo = ($request->sminimo != '') ? $request->sminimo : '';
            $producto->stkmaximo = ($request->smaximo != '') ? $request->smaximo : '';
            $producto->pidelote = (isset($request->chklote) && $request->chklote == "on") ? 1 : 0;
            $producto->pideserie = (isset($request->chkserie) && $request->chkserie == "on") ? 1 : 0;
            $producto->esafecto = (isset($request->chkafecto) && $request->chkafecto == "on") ? 1 : 0;
            $producto->escompuesto = 0;
            //$producto->estado = 1;
            $producto->ingactivo = ($request->txt_ingred_activo != '') ? $request->txt_ingred_activo : '';
            $producto->iac = ($request->txt_concentracion_ia != '') ? $request->txt_concentracion_ia : '';
            $producto->formulacion = ($request->txt_formulacion != '') ? $request->txt_formulacion : '';
            $producto->metodoaccion = ($request->txt_metodo_accion != '') ? $request->txt_metodo_accion : '';
            $producto->percepcion = (isset($request->chkpercepcion) && $request->chkpercepcion == "on") ? 1 : 0;
            $producto->consumo = (isset($request->chkconsumo) && $request->chkconsumo == "on") ? 1 : 0;
            $producto->compra = (isset($request->chkcompra) && $request->chkcompra == "on") ? 1 : 0;
            $producto->produce = 0;
            $producto->venta = (isset($request->chkvende) && $request->chkvende == "on") ? 1 : 0;
            $producto->aumentovta = (isset($request->chkaumento) && $request->chkaumento == "on") ? 1 : 0;
            $producto->merma = (isset($request->chkmerma) && $request->chkmerma == "on") ? 1 : 0;
            $producto->bonificado = (isset($request->chkbonifica) && $request->chkbonifica == "on") ? 1 : 0;
            $producto->tipoproducto = $tipo_producto;
            $producto->tipomercaderia = $tipo_mercaderia;
            $producto->foto = '';
            $producto->gastoadm = 0;
            $producto->utilidad = ($request->utilidad != '') ? $request->utilidad : '';
            $producto->tolerancia = ($request->toleracion != '') ? $request->toleracion : '';
            $producto->impuesto1_id = null;
            $producto->impuesto2_id = null;
            $producto->precio1 = 0;
            $producto->precio2 = 0;
            $producto->precio3 = 0;
            $producto->precio4 = 0;
            $producto->precio5 = 0;
            $producto->precio6 = 0;
            $producto->tipocarga = (isset($request->cbo_tipo_carga) && $request->cbo_tipo_carga > 0) ? $request->cbo_tipo_carga : 0;
            $producto->parancelaria_id = (isset($request->cbo_partida_arancelaria)) ? $request->cbo_partida_arancelaria : null;
            $producto->productosunat_id = (isset($request->sunat)) ? $request->sunat : null;
            $producto->usuario = Session::get('usuario');
            $producto->ruta_foto = '';
            if (isset($file) && $file != '') {
                $producto->archivo_foto = $nombre_foto;
            }
            //$producto->save();
            //$this->guardar_producto_npk($producto->id);
            //$this->guardar_producto_ubicacion($producto->id);
            //$r["ruta"] = route('edit.products', $id);
            //$r["estado"] = "ok";
            //$r["mensaje"] = "Grabado Correctamente";
           $id = null;
           $id = DB::transaction(function () use ($producto) {
                $producto->save();
                $this->guardar_producto_npk($producto->id);
                $this->guardar_producto_ubicacion($producto->id);
                $id = $producto->id;
                return $id;
            });
            $r["ruta"] = route('edit.products', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        }catch(PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar";
            $r["bd"] = $e->getMessage();
        }

        //$r['route'] = route('products');
        //$r["proceso"] = 'crea';

        $this->eliminar_instancias();

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

    public function activar_anular_producto(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Productos::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
    public function buscar_marca(Request $request){
        $resultado = Marca::select2($request->term);
        return $resultado;
    }
    public function buscar_modelo(Request $request)
    {
        $modelos = Modelo::select2($request->term,$request->marca_id);
        return $modelos;
    }


    public function filtrar_producto_sunat(Request $request)
    {
        $family = Familia::findOrFail($request->id_family);

        $data['productos'] = ProductoSunat::whereRaw('CHAR_LENGTH(codigo) = 8')->where('codigo', 'like', '' . $family->codigo . '%')->get();

        return $data;
    }

    public function list_ubicacion_almacen()
    {
        return DataTables::of(collect(Cart::instance($this->instancia_almacen)->content()))->make();
    }

    public function agregar_ubicacion_almacen(ProductsDetailsRequest $request)
    {
        $almacen = WareHouses::select('codigo', 'descripcion')->where('id', $request->cbo_ubicacion_almacen)->first();
        Cart::instance($this->instancia_almacen)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => '',
                'almacen_id' => $request->cbo_ubicacion_almacen,
                'codigo' => $almacen->codigo,
                'descripcion' => $almacen->descripcion,
                'ubicacion' => $request->txt_ubicacion_almacen
            ]
        ]);
    }

    public function editar_ubicacion_almacen(ProductsDetailsRequest $request)
    {
        $item = Cart::instance($this->instancia_almacen)->get($request->row_id);
        $almacen = WareHouses::select('codigo', 'descripcion')->where('id', $request->cbo_ubicacion_almacen)->first();
        $options = $item->options->merge([
            'id' => $request->productoubicacion_id,
            'almacen_id' => $request->cbo_ubicacion_almacen,
            'codigo' => $almacen->codigo,
            'descripcion' => $almacen->descripcion,
            'ubicacion' => $request->txt_ubicacion_almacen
        ]);
        Cart::instance($this->instancia_almacen)->update($request->row_id, [
            'options' => $options]);

    }

    public function eliminar_ubicacion_almacen(Request $request)
    {
        Cart::instance($this->instancia_almacen)->remove($request->rowId);
        $almacen_id = $request->id;
        session()->push('almacen_ids', $almacen_id);
    }

    public function list_datos_npk()
    {
        return DataTables::of(collect(Cart::instance($this->instancia_npk)->content()))->make();
    }

    public function buscar_codigo_sunat(Request $request)
    {   
        $resultado = ProductoSunat::sunat_products_eight_digits($request->term);
        return $resultado;
    }

    public function agregar_datos_npk(ProductsDetailsRequest $request)
    {
        $nutrient = Nutrient::findOrFail($request->cbo_productos_npk);
        Cart::instance($this->instancia_npk)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => '',
                'nutriente_id' => $request->cbo_productos_npk,
                'codigo' => $nutrient->codigo,
                'descripcion' => $nutrient->descripcion,
                'conc' => $request->txt_conc
            ]
        ]);
    }

    public function editar_datos_npk(ProductsDetailsRequest $request)
    {

        $item = Cart::instance($this->instancia_npk)->get($request->row_id_npk);
        $nutrient = Nutrient::findOrFail($request->cbo_productos_npk);
        $options = $item->options->merge([
            'id' => $request->productonpk_id,
            'nutriente_id' => $request->cbo_productos_npk,
            'codigo' => $nutrient->codigo,
            'descripcion' => $nutrient->descripcion,
            'conc' => $request->txt_conc
        ]);
        Cart::instance($this->instancia_npk)->update($request->row_id_npk, [
            'options' => $options]);

    }

    public function eliminar_datos_npk(Request $request)
    {
        Cart::instance($this->instancia_npk)->remove($request->rowId);
        $nutriente_id = $request->id;
        session()->push('nutriente_ids', $nutriente_id);
    }

    public function eliminar_instancias()
    {
        Cart::instance($this->instancia_almacen)->destroy();
        Cart::instance($this->instancia_npk)->destroy();
    }

    public function buscar_partida_arancelaria(Request $request)
    {
        $resultado = Parancelaria::select2($request->term);
        return $resultado;
    }

    public function obtener_codigo_producto(Request $request)
    {
        
        $data['codigo_producto'] = $data['id_producto_sunat'] = $data['codigo_producto_sunat'] = $data['descripcion_producto_sunat'] = null;

        $familiapdto = Familia::selectidcmd($request->cbo_familia);

        if(empty($familiapdto)){ return null; }


        if(empty($request->sunat)){

            $producto_sunat = ProductoSunat::find($familiapdto->productosunat_id);

            if($producto_sunat){

                $data['id_producto_sunat'] = $producto_sunat->id;
                $data['codigo_producto_sunat'] = $producto_sunat->codigo;
                $data['descripcion_producto_sunat'] = $producto_sunat->descripcion;

            }

        }

        if (Session::get('id') == 0 && empty($familiapdto->codigo) == false){ // SOLO EN LA ACCIÓN CREAR

            switch($this->cgencodigo){

                case 'AUTOMATICO':
            
                    $familiapdto_codigo = ($familiapdto->codigo) ? trim($familiapdto->codigo) : '';
                    $familiapdto_codigo_caracteres = strlen($familiapdto_codigo);


                    $ultimo_codigo_producto = Productos::get_ultimo($familiapdto_codigo)->codigo;
                    
                    
                    $codigo = substr($ultimo_codigo_producto, $familiapdto_codigo_caracteres, 20);                    
                    $codigo++;
                    

                    $data['codigo_producto'] = $familiapdto_codigo . str_pad($codigo, $this->nlencodigo - $familiapdto_codigo_caracteres, "0", STR_PAD_LEFT);
                    


                break;

                case 'INCREMENTAL':

                    $familiapdto_codigo = '';

                    $ultimo_codigo_producto = Productos::get_ultimo($familiapdto_codigo)->codigo;
                    
                    $codigo = $ultimo_codigo_producto + 1;

                    $data['codigo_producto'] = str_pad($codigo, $this->nlencodigo, "0", STR_PAD_LEFT);


                break;

            }

        }

        return $data;

    }
    public function buscar_um(Request $request){
        $resultado = UnidadesMedida::select2($request->term);
        return $resultado;
    }
    public function buscar_grupoproducto(Request $request){
        $resultado = ProductGroup::select2($request->term);
        return $resultado;
    }
    public function buscar_color(Request $request){
        $resultado = Color::select2($request->term);
        return $resultado;
    }
    public function buscar_producto(Request $request){
        $resultado = Productos::select2($request->term);
        return $resultado;
    }
    public function getProductsTypeM(Request $request){

        //filtrar por tipomov -> compra

        $products = Productos::TypeM($request->term);
        $mov = MovementType::find($request->mov);

        if($mov->grupo == 'C'){                   //filtrar por los pedidos configurados con compra
            $filtered = $products->filter(function ($value, $key) {
                return $value->compra == 1;
            });
            return $filtered;
        }else{
            return $products;
        }

    }
    public function umedidas_producto(Request $request){
        $umedidas = Productos::umedidas_producto($request->producto_id);
        return $umedidas;
    }

    private function eliminar_variables_de_sesion(){

        session()->put('id', 0);

    }
}
