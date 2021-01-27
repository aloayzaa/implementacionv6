<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\ProductGroup\Entities\ProductGroup;
use App\Marks\Entities\Marca;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
class ProductGroupController extends Controller
{
    use CartTrait, Tables;
    private $var = 'productgroups';
    private $tabla_cabecera = 'grupoproducto';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function list(Request $request)
    {
        $productgroup = ProductGroup::all();
        return Datatables::of($productgroup)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Productos', 'Grupo de Productos','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('productgroup.list', $data);
    }

    public function create()
    {

        $grupoproducto=ProductGroup::all()->where('estado',1)->last();
        if($grupoproducto->codigo < 10) {
            $data['codigo'] = str_pad($grupoproducto->codigo + 1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo']= $grupoproducto->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['marca'] = Marca::all();
        $data['view'] = link_view('Maestros', 'Productos', 'Grupo de Productos','');
        $data['header'] = headeroptions($this->var, 'crea','','');

        return view('productgroup.create', $data);
    }
    public function store(MaestroRequest $request)
    {

        try {
            $productgroup = new ProductGroup();
            $productgroup->codigo = $request->txt_codigo_gp;
            $productgroup->descripcion = $request->txt_descripcion_gp;
            $productgroup->tipo = $request->txt_codigo_anterior;
            $productgroup->estado = 1;
            $productgroup->usuario = Session::get('usuario');
            $productgroup->cantidad = $request->txt_cantidad;
            $productgroup->yapa = $request->txt_yapa;
            $productgroup->marca_id = $request->cbo_marca;
            $productgroup->pdescuento = $request->txt_descuento;
            $id=null;
            $id=DB::transaction(function () use ($productgroup) {
                $productgroup->save();
                $id= $productgroup->id;
                return $id;
            });
            $r["ruta"] = route('edit.productgroups', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function edit($id)
    {
        $data['productgroup'] = ProductGroup::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['route'] = route('productgroups');
        $data['var'] = $this->var;
        $data['marca'] = Marca::all();
        $data['view'] = link_view('Maestros', 'Productos', 'Grupo de Productos','');
        $data['header'] = headeroptions($this->var, 'edita','','');

        return view('productgroup.edit', $data);
    }
    public function update(MaestroRequest $request, $id)
    {
        try {

            $productgroup = ProductGroup::findOrfail($id);
            $productgroup->codigo = $request->txt_codigo_gp;
            $productgroup->descripcion = $request->txt_descripcion_gp;
            $productgroup->tipo = $request->txt_codigo_anterior;
            $productgroup->estado = 1;
            $productgroup->usuario = Session::get('usuario');
            $productgroup->cantidad = $request->txt_cantidad;
            $productgroup->yapa = $request->txt_yapa;
            $productgroup->marca_id = $request->cbo_marca;
            $productgroup->pdescuento = $request->txt_descuento;
            $id=null;
            $id=DB::transaction(function () use ($productgroup) {
                $productgroup->save();
                $id= $productgroup->id;
                return $id;
            });

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }
}
