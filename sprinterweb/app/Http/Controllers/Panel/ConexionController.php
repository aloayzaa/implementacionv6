<?php

namespace App\Http\Controllers\Panel;

use App\Companies\Entities\Empresa;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConexionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id_empresa
     * @return \Illuminate\Http\Response
     */
    public function index($id_empresa)
    {
  /*      try {
            $empresa = Empresa::findOrFail($id_empresa);

            if (($empresa->emp_estado == 1) == true) {

                session(['conexionbd' => 'DB_CONNECTION_' . $empresa->emp_codigo]);
                sesion_empresa_cliente($id_empresa);
                $data['route'] = "sprinter";

//                return Redirect()->route('sprinter');
            }
        } catch (Exception $e) {
            $data['route'] = "error";
        }

        return Redirect()->route('sprinter');//
//        return view('templates.home');*/

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
