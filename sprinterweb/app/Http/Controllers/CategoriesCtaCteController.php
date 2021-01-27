<?php

namespace App\Http\Controllers;

use App\CategoriesCtaCte\Entities\CategoriesCtaCte;
use App\CategoriesCtaCte\Entities\CategoriesCtaCteDetail;
use App\Documents\Entities\Commercial;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MaestroRequest;

class CategoriesCtaCteController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    private $var = 'categoriesctacte';
    private $instancia = 'list_docuemntos_asignados';
    private $tabla_cabecera = 'categctacte';

    protected $user;
    protected $privilegios = '01.02.11';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    public function list(Request $request)
    {
        $categoriesctacte = CategoriesCtaCte::all();
        return Datatables::of($categoriesctacte)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Maestros', 'Terceros', 'Categorías Cta.Cte', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('categoriesctacte.list', $data);
    }

    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Categorías Cta.Cte', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['tipo_documento'] = Commercial::all()->where('estado', 1);
        $this->eliminar_instancias();
        return view('categoriesctacte.create', $data);
    }

    public function store(MaestroRequest $request)
    {
        try {
            $categoriesctacte = new CategoriesCtaCte();
            $categoriesctacte->codigo = $request->txt_codigo_categoriasctacte;
            $categoriesctacte->descripcion = $request->txt_descripcion_categoriasctacte;
            //$categoriesctacte->codsunat =
            //$categoriesctacte->simbolo =
            $categoriesctacte->estado = 1;
            $categoriesctacte->usuario = Session::get('usuario');
            $categoriesctacte->tipo = ($request->cbo_origen == '') ? '' : $request->cbo_origen;
            $id = null;
            $id = DB::transaction(function () use ($categoriesctacte) {
                $categoriesctacte->save();
                $this->guardar_docuemntos_asignados($categoriesctacte->id);
                return $categoriesctacte->id;
            });
            $r["ruta"] = route('edit.categoriesctacte', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    public function edit($id)
    {
        $this->eliminar_instancias();
        $doc = CategoriesCtaCte::findOrFail($id);
        $data['categoriesctacte'] = $doc;
        $data['proceso'] = 'edita';
        $data['route'] = route('categoriesctacte');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Categorías Cta.Cte', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($doc->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['tipo_documento'] = Commercial::all()->where('estado',1);
        $this->llenar_doc_asignados($doc->id);
        return view('categoriesctacte.edit', $data);
    }

    public function update(MaestroRequest $request, $id)
    {
        try {
            $categoriesctacte = CategoriesCtaCte::findOrfail($id);
            $categoriesctacte->codigo = $request->txt_codigo_categoriasctacte;
            $categoriesctacte->descripcion = $request->txt_descripcion_categoriasctacte;
            //$categoriesctacte->codsunat =
            //$categoriesctacte->simbolo =
            $categoriesctacte->estado = 1;
            $categoriesctacte->usuario = Session::get('usuario');
            $categoriesctacte->tipo = $request->cbo_origen;
            $id = null;
            $id = DB::transaction(function () use ($categoriesctacte) {
                $categoriesctacte->save();
                $this->guardar_docuemntos_asignados($categoriesctacte->id);
                return $categoriesctacte->id;
            });

            $r["ruta"] = route('edit.categoriesctacte', $id);
            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    public function list_docuemntos_asignados()
    {
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }

    public function eliminar_instancias()
    {
        Cart::instance($this->instancia)->destroy();
    }

    private function guardar_docuemntos_asignados($parent_id){
        //dd(Cart::instance($this->instancia)->content());
        $doc_asignados = Session::get('parent_id');
        if($doc_asignados){
            foreach ($doc_asignados as $id) {
                CategoriesCtaCteDetail::where('parent_id', $id)->delete();
                session()->put('parent_id', []);
            }
        }
        //dd(Cart::instance($instancia)->content());
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            //dd($cart);
            $existe = CategoriesCtaCteDetail::where('parent_id',$cart->options->id)->exists();
            //dd($existe);
            if($existe){
                //dd($cart->options->documento_id);
                //$data['parent_id'] = ($cart->options->id != '') ? $cart->options->id : '';
                $data['documento_id'] = $cart->options->documento_id; // 2 -> 3  // 12
                //dd($data);
                CategoriesCtaCteDetail::update_data($cart->options->id,$cart->options->doc_id,$data);
            }else{
                $doc_asignados = new CategoriesCtaCteDetail();
                $doc_asignados->parent_id = $parent_id;
                $doc_asignados->documento_id = $cart->options->documento_id;
                $doc_asignados->save();
            }
        }
    }

    public function agregar_item(MaestroRequest $request)
    {

        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first(); //fixed el bug de no traer el ultimo


        if ($ultimoCart == null) {
            $ultimoBd = CategoriesCtaCteDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;

        } else {
            $item = $ultimoCart->options->item;;
        }

        $doc = Commercial::select('id','codigo','descripcion')->where('id',$request->modal_doc_asig_id)->where('estado', 1)->first();
        Cart::instance($this->instancia)->add(['id' => $item+1, 'name' => 'hola', 'qty' => 1, 'price' => 0.00,
            'options' =>[
                'id' => '',
                'documento_id' => $doc->id,
                'doc_id' => $doc->id,
                'codigo' => $doc->codigo,
                'descripcion' => $doc->descripcion
            ]]);

    }

    private function llenar_doc_asignados($doc_id){
        $doc_asig = CategoriesCtaCteDetail::where('parent_id',$doc_id)->get();
        foreach($doc_asig as $p){
            Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $p->parent_id,
                    'documento_id' => $p->documentos['id'],
                    'doc_id' => $p->documentos['id'],
                    'codigo' => $p->documentos['codigo'],
                    'descripcion' => $p->documentos['descripcion']
                ]
            ]);
        }
    }

    public function update_item(Request $request)
    {
        $item = Cart::instance($this->instancia)->get($request->row_id);

        $doc_asig = Commercial::select('id','codigo','descripcion')->where('id',$request->modal_doc_asig_id)->where('estado', 1)->first();
        $options = $item->options->merge([
            'documento_id' => $request->modal_doc_asig_id,
            'codigo' => $doc_asig->codigo,
            'descripcion' => $doc_asig->descripcion
        ]);

        Cart::instance($this->instancia)->update($request->row_id, [
            'options' => $options
        ]);

    }

    public function eliminar_documentos_asignados(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);
        $id = $request->id;
        session()->push('parent_id', $id);
    }

}
