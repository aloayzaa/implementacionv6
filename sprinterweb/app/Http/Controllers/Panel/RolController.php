<?php

namespace app\Http\Controllers\Panel;

use App\Security\Entities\Permission;
use App\Security\Entities\PermissonRol;
use App\Security\Entities\Rol;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolController extends Controller
{
    private $var = 'roles';

    public function index()
    {
        $data['view'] = link_view('Panel', 'Roles', '', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('roles.list', $data);
    }

    public function list()
    {
        $roles = Rol::all();
        return DataTables::of($roles)->make();
    }

    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['route'] = route('roles');
        $data['permissions'] = Permission::where('id', '>', 5)->get();
        $data['view'] = link_view('Panel', 'Roles', '', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('roles.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $rol = new Rol;
            $rol->rol_nombre = strtoupper($request->name);
            $rol->rol_estado = 1;

            DB::transaction(function () use ($rol, $request) {
                $rol->save();
                $rol->permissions()->sync($request->permissions);
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $exception) {
            $r["estado"] = "error";
            $r["bd"] = $exception->getMessage();
            $r["mensaje"] = "Datos no válidos";
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    public function edit($id)
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['route'] = route('roles');
        $data['permissions'] = Permission::where('id', '>', 5)->get();
        $data['view'] = link_view('Panel', 'Roles', '', '');
        $data['header'] = headeroptions($this->var, 'edita', '', '');
        $data['roles'] = Rol::findOrFail($id);
        $data['rol_permisos'] = PermissonRol::where('rolid', $id)->get();

        return view('roles.edit', $data);
    }

}
