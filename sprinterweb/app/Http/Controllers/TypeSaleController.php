<?php

namespace App\Http\Controllers;

use App\Documents\Entities\Commercial;
use App\Http\Requests\SalesRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\MovementTypes\Entities\MovementType;
use App\PaymentMethod\Entities\PaymentMethod;
//use App\SalesTypes\Collections\TypeSaleCollection;
use App\SalesTypes\Entities\SaleType;
use App\Shopping\Entities\TypeBut;
use App\Subdiaries\Entities\Subdiaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use Zend\Diactoros\Response\SapiEmitter;

class TypeSaleController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'typeSales';
    protected $typeSaleCollection;

    private $tabla_cabecera = 'tipoventa';
    protected $user;
    protected $privilegios = '03.01.01';

    public function __construct()
    {
        $this->obtener_cliente();
//        $this->typeSaleCollection = $typeSaleCollection;
        $this->user = 'ADMINISTRADOR';
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Tipos de Venta','');

        return view('typeSales.list', $data);
    }

    public function list()
    {
        $typeSales = SaleType::all();
        //$this->typeSaleCollection->actions($typeSales, 'edit.typeSales');
        //return DataTables::of($typeSales)->rawColumns(['actions', 'estado'])->make();
        return DataTables::of($typeSales)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['code'] = SaleType::get()->last();
        $data['buys'] = TypeBut::where('estado', 1)->get();
        $data['movements'] = MovementType::where('estado', 1)->get();
        $data['subdiaries'] = Subdiaries::where('estado', 1)->get();
        $data['documents'] = Commercial::where('estado', 1)->get();
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Tipos de Venta','');

        return view('typeSales.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesRequest $request)
    {
        if (isset($request->deferred) && $request->deferred == 'on'){
            $rules = array(
                'document' => 'required',
            );
            $message = array(
                'document.required' => 'Seleccione el Tipo Documento Asociado',
            );
            $this->validate($request, $rules, $message);
        }
        try {
            $typeSale = new SaleType();
            $typeSale->codigo = $request->code;
            $typeSale->descripcion = ($request->name == '') ? '' : strtoupper($request->name);
            $typeSale->codsunat = ($request->codSunat == '') ? '' : $request->codSunat;
            $typeSale->codsunat_to = ($request->codsunat_to == '') ? '' : $request->codsunat_to;


            if (isset($request->deferred) && $request->deferred == 'on') {
                $typeSale->esanticipo = 1;
                $typeSale->documento_id = $request->document;
            } else {
                $typeSale->esanticipo = 0;
                $typeSale->documento_id = ($request->document == '') ? '' : $request->document;
            }

            $typeSale->esexporta = (isset($request->byExports) && $request->byExports == 'on') ? 1 : 0;
            $typeSale->esservicio = (isset($request->service) && $request->service == 'on') ? 1 : 0;
            $typeSale->esgratuito = (isset($request->free) && $request->free == 'on') ? 1 : 0;
            $typeSale->conkardex = (isset($request->warehouse) && $request->warehouse == 'on') ? 1 : 0;
            $typeSale->noafecto = (isset($request->unaffected) && $request->unaffected == 'on') ? 1 : 0;
            $typeSale->afectoivap = (isset($request->unaffectedivap) && $request->unaffectedivap == 'on') ? 1 : 0;
            $typeSale->esretencion = (isset($request->retention) && $request->retention == 'on') ? 1 : 0;

            $typeSale->estado = 1;
            $typeSale->tipotransaccion_id = $request->buy;
            $typeSale->tipoafectaigv_id = null;
            $typeSale->movimientotipo_id = $request->movement;
            $typeSale->subdiario_id = $request->subdiario;
            $typeSale->precio = (isset($request->precio)) ? $request->precio : '';
            $typeSale->usuario = Session::get('usuario');;
            $id = null;
            $id = DB::transaction(function () use ($typeSale) {
                $typeSale->save();
                return $typeSale->id;
            });

            $r["ruta"] = route('edit.typeSales', $id);
            $r["estado"] = "ok";
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
        $typeSales = SaleType::findOrFail($id);
        $data['typeSale'] = $typeSales;
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['buys'] = TypeBut::all();
        $data['movements'] = MovementType::where('estado', 1)->get();
        $data['subdiaries'] = Subdiaries::where('estado', 1)->get();
        $data['documents'] = Commercial::where('estado', 1)->get();
        $data['header'] = headeroptions($this->var, 'edita','',($typeSales->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Ventas', 'Configuración', 'Tipos de Venta','');

        return view('typeSales.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SalesRequest $request, $id)
    {
        if (isset($request->deferred) && $request->deferred == 'on'){
            $rules = array(
                'document' => 'required',
            );
            $message = array(
                'document.required' => 'Seleccione el Tipo Documento Asociado',
            );
            $this->validate($request, $rules, $message);
        }
        try {
            $typeSale = SaleType::findOrFail($id);
            $typeSale->codigo = $request->code;
            $typeSale->descripcion = ($request->name == '') ? '' : strtoupper($request->name);
            $typeSale->codsunat = ($request->codSunat == '') ? '' : $request->codSunat;
            $typeSale->codsunat_to = ($request->codsunat_to == '') ? '' : $request->codsunat_to;


            if (isset($request->deferred) && $request->deferred == 'on') {
                $typeSale->esanticipo = 1;
                $typeSale->documento_id = $request->document;
            } else {
                $typeSale->esanticipo = 0;
                $typeSale->documento_id = ($request->document == '') ? '' : $request->document;
            }

            $typeSale->esexporta = (isset($request->byExports) && $request->byExports == 'on') ? 1 : 0;
            $typeSale->esservicio = (isset($request->service) && $request->service == 'on') ? 1 : 0;
            $typeSale->esgratuito = (isset($request->free) && $request->free == 'on') ? 1 : 0;
            $typeSale->conkardex = (isset($request->warehouse) && $request->warehouse == 'on') ? 1 : 0;
            $typeSale->noafecto = (isset($request->unaffected) && $request->unaffected == 'on') ? 1 : 0;
            $typeSale->afectoivap = (isset($request->unaffectedivap) && $request->unaffectedivap == 'on') ? 1 : 0;
            $typeSale->esretencion = (isset($request->retention) && $request->retention == 'on') ? 1 : 0;

            $typeSale->tipotransaccion_id = $request->buy;
            $typeSale->movimientotipo_id = $request->movement;
            $typeSale->subdiario_id = $request->subdiario;
            $typeSale->precio = (isset($request->precio)) ? $request->precio : '';
            $typeSale->usuario = Session::get('usuario');;
            $id = null;
            $id = DB::transaction(function () use ($typeSale) {
                $typeSale->save();
                return $typeSale->id;
            });

            $r["ruta"] = route('edit.typeSales', $id);
            $r["estado"] = "ok";
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

    public function activeCancel(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                SaleType::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
