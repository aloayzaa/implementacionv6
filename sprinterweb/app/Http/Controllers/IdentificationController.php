<?php

namespace App\Http\Controllers;

use App\Documents\Entities\Identification;
use App\Http\Requests\MaestroRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use PDOException;

class IdentificationController extends Controller
{
    private $var = 'identifications';

    public function __construct()
    {
        $this->obtener_cliente();
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
        $data['view'] = link_view('Maestros', 'Terceros', 'Documentos de Identidad','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('identifications.list', $data);
    }

    public function list()
    {
        $identifications = Identification::all();
        return DataTables::of($identifications)->make();
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
        $data['view'] = link_view('Maestros', 'Terceros', 'Documentos de Identidad','');
        $data['header'] = headeroptions($this->var, 'crea','','');
        return view('identifications.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {
        try{
            $documentoide = new Identification();
            $documentoide->codigo = $request->txt_codigo_identi;
            $documentoide->descripcion = $request->txt_descripcion_ident;
            $documentoide->codsunat = ($request->txt_codigo_sunat == '')? '': $request->txt_codigo_sunat;
            $documentoide->simbolo = ($request->txt_simbolo =='')? '': $request->txt_simbolo;
            $documentoide->tipo = '';
            $documentoide->estado = 2;
            $documentoide->usuario = Session::get('usuario');
            $documentoide->longitud =($request->txt_longitud == '')? '': $request->txt_longitud;

            $id=null;
            $id= DB::transaction(function () use ($documentoide) {
                $documentoide->save();
                $id= $documentoide->id;
                return $id;

            });
            $r["ruta"] = route('edit.identifications', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        }catch(PDOException $e) {

            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();

        }

        return $r;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['proceso'] = 'edita';
        $data['documentoide'] = Identification::findOrFail($id);
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Documentos de Identidad','');
        $data['header'] = headeroptions($this->var, 'edita','','');
        return view('identifications.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $documentoide = Identification::findOrFail($request->id);
            $documentoide->codigo = $request->txt_codigo_identi;
            $documentoide->descripcion = $request->txt_descripcion_ident;
            $documentoide->codsunat = ($request->txt_codigo_sunat == '')?'':$request->txt_codigo_sunat;
            $documentoide->simbolo = ($request->txt_simbolo =='')? '': $request->txt_simbolo;
            $documentoide->tipo = '';
            $documentoide->estado = 2;
            $documentoide->usuario = Session::get('usuario');
            $documentoide->longitud =($request->txt_longitud == '')? '': $request->txt_longitud;
            $id = null;
            $id=DB::transaction(function () use ($documentoide) {
                $documentoide->save();
                $id=$documentoide->id;
                return $id;

            });
            $r["ruta"] = route('edit.identifications', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }


        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
