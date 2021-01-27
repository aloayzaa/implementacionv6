<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\PaymentType\Collections\PaymentTypeCollection;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class PaymentTypeController extends Controller
{
    use Tables, UserPrivileges;
    private $var = 'paymenttype';
    protected $tabla_cabecera = 'mediopago';
    protected $privilegios = '05.01.04';
    protected $user;

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['view'] = link_view('Tesoreria', 'Configuración', 'Medios de pago','');

        return view('paymenttype.list', $data);
    }

    public function list()
    {
        $payments = PaymentType::all();
        return DataTables::of($payments)->make();
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
