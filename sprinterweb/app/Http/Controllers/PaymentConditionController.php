<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaestroRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\PaymentCondition\Entities\PaymentCondition;
use Illuminate\Http\Request;
use App\Period\Entities\Period;
use App\TypeIsc\Entities\TypeIsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
class PaymentConditionController extends Controller
{
    use CartTrait, Tables;
    private $var = 'paymentcondition';
    private $tabla_cabecera = 'condicionpago';

    public function __construct()
    {
        $this->obtener_cliente();
    }

    public function list(Request $request)
    {
        $paymentcondition = PaymentCondition::all();
        return Datatables::of($paymentcondition)->make();
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
        $data['view'] = link_view('Maestros', 'Terceros', 'Condiciones de Pago','');
        $data['header'] = headeroptions($this->var, 'list','','');
            return view('paymentcondition.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $condicionpagos=PaymentCondition::all()->where('estado',1)->last();
        if($condicionpagos->codigo < 10) {
            $data['codigo'] = str_pad($condicionpagos->codigo + 1, 2, '0', STR_PAD_LEFT);
        }else{
            $data['codigo']= $condicionpagos->codigo +1;
        }
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['view'] = link_view('Maestros', 'Terceros', 'Condiciones de Pago','');
        $data['header'] = headeroptions($this->var, 'crea','','');

        return view('paymentcondition.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {

        try {

            $condicionpago = new PaymentCondition();
            $condicionpago->codigo = $request->txt_codigo_cp;
            $condicionpago->descripcion = $request->txt_descripcion_cp;
            //$condicionpago->codsunat = $request->;
            $condicionpago->simbolo = $request->txt_simbolo;
            //$condicionpago->tipo =;
            $condicionpago->dias = $request->txt_dias_credito;
            $condicionpago->estado = 1;
            $condicionpago->usuario = Session::get('usuario');
            $id=null;
            $id=DB::transaction(function () use ($condicionpago) {
                $condicionpago->save();
                $id=$condicionpago->id;
                 return $id;

            });
            $r["ruta"] = route('edit.paymentcondition', $id);
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
        $data['condicionpago'] = PaymentCondition::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['route'] = route('paymentcondition');
        $data['var'] = $this->var;
        $data['view'] = link_view('Maestros', 'Terceros', 'Condiciones de Pago','');
        $data['header'] = headeroptions($this->var, 'edita','','');

        return view('paymentcondition.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaestroRequest $request, $id)
    {
        try {

            $condicionpago = PaymentCondition::findOrfail($id);
            $condicionpago->codigo = $request->txt_codigo_cp;
            $condicionpago->descripcion = $request->txt_descripcion_cp;
            //$condicionpago->codsunat = $request->;
            $condicionpago->simbolo = $request->txt_simbolo;
            //$condicionpago->tipo =;
            $condicionpago->dias = $request->txt_dias_credito;
            $condicionpago->estado = 1;
            $condicionpago->usuario = Session::get('usuario');
            $id=null;
            $id=DB::transaction(function () use ($condicionpago) {
                $condicionpago->save();
                $id=$condicionpago->id;
                return $id;
            });
            $r["ruta"] = route('edit.paymentcondition', $id);
            $r["estado"] = "ok";
            $r["succcess"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
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

    public function obtener_dias_condicionpago(Request $request)
    {
        $condicion = PaymentCondition::findOrFail($request->condpago);

        $days = $condicion->dias;

        $data['date'] = sumar_dias($request->fecha, $days);

        return $data;
    }
}
