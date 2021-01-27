<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequest;
use App\Http\Traits\Controls\UserPrivileges;
use App\Http\Traits\Controls\Tables;
use App\PointSale\Entities\PointSale;
use App\PointSale\Entities\PointSaleSeries;
use App\Warehouses\Entities\WareHouses;
use App\SalesTypes\Entities\SaleType;
use App\Costs\Entities\Costs;
use App\Project\Entities\Project;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Documents\Entities\Commercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class SellingPointsController extends Controller
{
    use Tables, UserPrivileges;
    private $var = 'sellingpoints';
    private $tabla_cabecera = 'puntoventa';
    protected $user;
    protected $privilegios = '03.01.02';
    private $documentos_asociados = 'documentos_asociados';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function index(){
        $data['var'] = $this->var;
        $data['view'] = link_view('Gestión de Ventas','Configuración','Punto de venta','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('sellingpoints.list', $data);
    }

    public function list(){
        $puntoventa = PointSale::all();
        //dd($puntoventa);
        return DataTables::of($puntoventa)->make();
    }

    public function create(){

        $data['var'] = $this->var;
        $data['proceso'] = 'crear';
        $data['view'] = link_view('Gestión de Ventas','Configuración','Punto de venta','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['almacenes'] = WareHouses::where('estado', 1)->get();
        $data['tipoventas'] = SaleType::where('estado', 1)->get();
        $data['centrocostos'] = Costs::where('estado', 1)->get();
        $data['proyectos'] = Project::where('estado', 1)->get();
        $data['ctactebancos'] = BankCurrentAccount::where('estado', 1)->get();
        $data['documentocoms'] = Commercial::where('estado', 1)->get();

        Cart::instance($this->documentos_asociados)->destroy();
        return view('sellingpoints.create', $data);
    }

    public function documentos_asociados(){
        return DataTables::of(collect(Cart::instance($this->documentos_asociados)->content()))->make();
    }

    public function agregar_documentos_asociados(SalesRequest $request){
        $doc_serie_repetidos = $this->doc_serie_repetidos($request);
        if(!is_null($doc_serie_repetidos)){return $doc_serie_repetidos;} //Validar si existe un documento con la misma serie.
        $documentocom = Commercial::findOrFail($request->cbo_documentocom);
        Cart::instance($this->documentos_asociados)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => null,
                'puntoventa_id' => $request->id,
                'documentocom_id' => $request->cbo_documentocom,
                'documentocom_codigo' => $documentocom->codigo,
                'documentocom_descripcion' => $documentocom->descripcion,
                'serie' => $request->txt_serie,
                'lineas' => $request->txt_lineas,
                'usuario' => '', //Session::get('usuario')
            ]
        ]);
    }

    public function editar_documentos_asociados(SalesRequest $request){
        $item = Cart::instance($this->documentos_asociados)->get($request->rowId);
        $documentocom = Commercial::findOrFail($request->cbo_documentocom);

        $options = $item->options->merge([
            'id' => $request->pv_series_id,
            'puntoventa_id' => $request->id,
            'documentocom_id' => $request->cbo_documentocom,
            'documentocom_codigo' => $documentocom->codigo,
            'documentocom_descripcion' => $documentocom->descripcion,
            'serie' => $request->txt_serie,
            'lineas' => $request->txt_lineas,
            'usuario' => '', //Session::get('usuario')
        ]);

        Cart::instance($this->documentos_asociados)->update($request->rowId, [
            'options' => $options]);
    }

    public function eliminar_documentos_asociados(Request $request){
        //dd($request->all());
        Cart::instance($this->documentos_asociados)->remove($request->rowId);
        session()->push('pv_series_id', $request->id);
    }

    public function store(SalesRequest $request){
        try {
            $puntoventa = new PointSale();
            $puntoventa->codigo = $request->txt_codigo;
            $puntoventa->descripcion = $request->txt_descripcion;
            $puntoventa->estado = 1;
            $puntoventa->almacen_id = ($request->cbo_almacen != '') ? $request->cbo_almacen : null;
            $puntoventa->centrocosto_id = ($request->cbo_centrocosto != '') ? $request->cbo_centrocosto : null;
            $puntoventa->ctactebanco_id = ($request->cbo_ctactebanco != '') ? $request->cbo_ctactebanco : null;
            $puntoventa->proyecto_id = ($request->cbo_proyecto != '') ? $request->cbo_proyecto : null;
            $puntoventa->tipoventa_id = ($request->cbo_tipoventa != '') ? $request->cbo_tipoventa : null;
            $puntoventa->usuario = Session::get('usuario');
            //$puntoventa->mesas =
            $puntoventa->precio = (isset($request->opt_precioventa) && $request->opt_precioventa != null) ? $request->opt_precioventa : 0;
            $id = null;
            $id = DB::transaction( function() use ($puntoventa, $request){
                $puntoventa->save();
                $this->guardar_puntoventa_series($puntoventa->id, $request);
                return $puntoventa->id;
            });

            $r["ruta"] = route('edit.sellingpoints', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    private function guardar_puntoventa_series($puntoventa_id, $request){
        $item = Session::get('pv_series_id');
        if($item){
            foreach ($item as $i) {
                PointSaleSeries::where('id', $i)->delete();
            }
            session()->put('pv_series_id', []);
        }

       //dd(Cart::instance($this->documentos_asociados)->content());
        foreach (Cart::instance($this->documentos_asociados)->content() as $cart) {
            $existe = PointSaleSeries::where('id', $cart->options->id)->exists();
            if($existe){
                $data['puntoventa_id'] = $puntoventa_id;
                $data['documentocom_id'] = $cart->options->documentocom_id;
                $data['serie'] = ($cart->options->serie != null) ? $cart->options->serie : 0;
                $data['lineas'] = ($cart->options->lineas != null) ? $cart->options->lineas : 0;
                $data['usuario'] = Session::get('usuario');
                PointSaleSeries::update_data($cart->options->id, $data);
            }else{
                $puntoventa_series = new PointSaleSeries();
                $puntoventa_series->puntoventa_id = $puntoventa_id;
                $puntoventa_series->documentocom_id = $cart->options->documentocom_id;;
                $puntoventa_series->serie = ($cart->options->serie != null) ? $cart->options->serie : 0;
                $puntoventa_series->lineas = ($cart->options->lineas != null) ? $cart->options->lineas : 0;
                $puntoventa_series->usuario = Session::get('usuario');
                $puntoventa_series->save();
            }
        }
    }

    public function edit($id){

        session()->put('pv_series_id', []);

        $data['puntoventa'] = PointSale::findOrFail($id);
        $puntoventa_series = PointSaleSeries::where('puntoventa_id', $id)->get();
        //dd($puntoventa_series);
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Gestión de Ventas','Configuración','Punto de venta','');
        $data['header'] = headeroptions($this->var, 'edita','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['almacenes'] = WareHouses::where('estado', 1)->get();
        $data['tipoventas'] = SaleType::where('estado', 1)->get();
        $data['centrocostos'] = Costs::where('estado', 1)->get();
        $data['proyectos'] = Project::where('estado', 1)->get();
        $data['ctactebancos'] = BankCurrentAccount::where('estado', 1)->get();
        $data['documentocoms'] = Commercial::where('estado', 1)->get();

        Cart::instance($this->documentos_asociados)->destroy();
        $this->llenar_guardar_puntoventa_series($puntoventa_series);
        return view('sellingpoints.edit', $data);
    }

    private function llenar_guardar_puntoventa_series($puntoventa_series){
        //dd($puntoventa_series);
        foreach ($puntoventa_series as $puntoventa_serie){
            $documentocom = Commercial::findOrFail($puntoventa_serie->documentocom_id);
            Cart::instance($this->documentos_asociados)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $puntoventa_serie->id,
                    'puntoventa_id' => $puntoventa_serie->puntoventa_id,
                    'documentocom_id' => $puntoventa_serie->documentocom_id,
                    'documentocom_codigo' => $documentocom->codigo,
                    'documentocom_descripcion' => $documentocom->descripcion,
                    'serie' => $puntoventa_serie->serie,
                    'lineas' => $puntoventa_serie->lineas,
                    'usuario' => $puntoventa_serie->usuario,
                ]
            ]);
        }
    }

    public function update(SalesRequest $request, $id){

        try {

            $puntoventa = PointSale::findOrFail($id);
            $puntoventa->codigo = $request->txt_codigo;
            $puntoventa->descripcion = $request->txt_descripcion;
            //$puntoventa->estado = 1;
            $puntoventa->almacen_id = ($request->cbo_almacen != '') ? $request->cbo_almacen : null;
            $puntoventa->centrocosto_id = ($request->cbo_centrocosto != '') ? $request->cbo_centrocosto : null;
            $puntoventa->ctactebanco_id = ($request->cbo_ctactebanco != '') ? $request->cbo_ctactebanco : null;
            $puntoventa->proyecto_id = ($request->cbo_proyecto != '') ? $request->cbo_proyecto : null;
            $puntoventa->tipoventa_id = ($request->cbo_tipoventa != '') ? $request->cbo_tipoventa : null;
            $puntoventa->usuario = Session::get('usuario');
            //$puntoventa->mesas =
            $puntoventa->precio = (isset($request->opt_precioventa) && $request->opt_precioventa != null) ? $request->opt_precioventa : 0;

            DB::transaction( function() use ($puntoventa, $request){
                $puntoventa->save();
                $this->guardar_puntoventa_series($puntoventa->id, $request);

            });

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    private function doc_serie_repetidos($request){
        $comprobar = false;
        foreach (Cart::instance($this->documentos_asociados)->content() as $cart) {
            if($request->cbo_documentocom == $cart->options->documentocom_id && $request->txt_serie == $cart->options->serie && $request->rowId != $cart->rowId){$comprobar = true;}
        }
        if($comprobar == true){
            return response()->json(['errors' => array('detalle' => 'Ya existe el documento asociado a la serie')],422);
        }
    }
}
