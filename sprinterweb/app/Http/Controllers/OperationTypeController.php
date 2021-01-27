<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\OperationType\Entities\OperationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class OperationTypeController extends Controller
{
    use Tables, UserPrivileges;
    protected $var = 'operationtype';
    protected $tabla_cabecera = 'tipooperacion';
    protected $user;
    protected $privilegios = '05.01.01';

    public function __construct()
    {
        $this->obtener_cliente();
        //$this->user = Session::get('usuario');
        $this->user = 'ADMINISTRADOR';
    }

    public function index(Request $request)
    {
        //dd(\session()->all());
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Tipos de Operación','');

        return view('operationtype.list', $data);
    }

    public function list()
    {
        $operaciones = OperationType::all();
        return DataTables::of($operaciones)->make();
    }


    public function create()
    {
        $data['tipos'] = OperationType::tipos();
        $data['header'] = headeroptions($this->var, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Tipos de Operación', '');

        return view('operationtype.create', $data);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'codigo' => 'required|unique:tipooperacion',
            'descripcion' => 'required'
        ],[
            'codigo.required' => 'El codigo es requerido',
            'codigo.unique' => 'El codigo ya se encuentra registrado',
            'descripcion.required' => 'La descripcion es requerida',
            ]);


        DB::beginTransaction();

        try{
            $operation = new OperationType();
            $operation->codigo = $request->codigo;
            $operation->descripcion = $request->descripcion;
            $operation->tipo = $request->tipo;

            if($request->esajuste){
                $operation->esajuste = 1;
            }

            if($request->estransferencia){
                $operation->estransferencia = 1;
            }

            if($request->esanticipo){
                $operation->esanticipo = 1;
            }

            if($request->pidedocumento){
                $operation->pidedocumento = 1;
                $operation->origen = $request->origen;
            }

            if($request->verflujo){
                $operation->verflujo = $request->verflujo == 'on' ? 1 : 0;
            }else{
                $operation->verflujo = 0;
            }

            $operation->estado = 1;
            $operation->usuario = Session::get('usuario');

            $operation->save();
            DB::commit();

            $data['ruta'] = route('edit.operationtype', $operation->id);

        }

        catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'errors' => ['error' => 'Error al grabar'],
                'message' => $e->getMessage()],500);
        }

        return $data;

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $operationtype = OperationType::findOrFail($id);

        $data['operationtype'] = $operationtype;
        $data['tipos'] = OperationType::tipos();
        $data['origenes'] = OperationType::origenes();
        $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Tipos de Operación', '');

        return view('operationtype.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'codigo' => 'required|unique:tipooperacion,codigo,'.$id,
            'descripcion' => 'required'
        ],[
            'codigo.required' => 'El codigo es requerido',
            'codigo.unique' => 'El codigo ya se encuentra registrado',
            'descripcion.required' => 'La descripcion es requerida',
        ]);

        $operation = OperationType::findOrFail($id);

        $operation->codigo = $request->codigo;
        $operation->descripcion = $request->descripcion;
        $operation->tipo = $request->tipo == null ? '' : $request->tipo;


        if($request->esajuste){
            $operation->esajuste = 1;
            $operation->estransferencia = 0;
        }else{
            $operation->esajuste = 0;
        }

        if($request->estransferencia){
            $operation->estransferencia = 1;
            $operation->esajuste = 0;
        }else{
            $operation->estransferencia = 0;
        }

        if($request->esanticipo){
            $operation->esanticipo = 1;
        }else{
            $operation->esanticipo = 0;
        }

        if($request->pidedocumento){
            $operation->pidedocumento = 1;
            $operation->origen = $request->origen;
        }else{
            $operation->pidedocumento = 0;
            $operation->origen = "";
        }

        if($request->verflujo){
            $operation->verflujo = $request->verflujo == 'on' ? 1 : 0;
        }else{
            $operation->verflujo = 0;
        }

        if($operation->isDirty()){
            $operation->save();
        }

        $operation->save();

        $data["success"] = "Se guardaron los cambios correctamente";

        return $data;
    }


    public function destroy($id)
    {
        //
    }
}
