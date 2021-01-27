<?php

namespace App\Http\Controllers;

use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\PointSale\Entities\UserPointSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    use UserPrivileges;
    protected $user;
    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = \session('usuario');

    }


    public function index()
    {
        $data['periods'] = Period::all();
        $data['view'] = link_view('Panel', 'Inicio', '', '');
        $data['header'] = headeroptions('sprinter', '', '', '', '', $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('templates.home', $data);
    }

    public function sprinter()
    {
       // dd(Auth::check());
        $data['periods'] = Period::all();
        $data['view'] = link_view_inicio();
        $data['header'] = headeroptions('sprinter', '', '', '', '', $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('templates.homeSprinter', $data);
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

    public function accountingPeriod()
    {
        $period = Period::orderBy('id', 'desc')->get();

        return $period;
    }

    public function accountingPeriodUpdate(Request $request)
    {
        $data['period'] = Period::findOrFail($request->period);
        session(['period_id' => $request->period]);
        session(['period' => $data['period']->descripcion]);

        $data = '<label class="label label-primary">' . Session::get('period') . '</label>';

        return $data;
    }

    public function salesPoint()
    {
        if (Session::get('usuario') === 'ADMINISTRADOR'){
            $point = PointSale::orderBy('id', 'desc')->get();
        }else{
            $point = UserPointSale::usuario_puntoventa(Auth::id());
        }

        return $point;
    }

    public function salesPointUpdate(Request $request)
    {
        if ($request->point == ''){
            session(['point_id' => '']);
            session(['point' => 'NINGUNO']);
        }else{
            $data['point'] = PointSale::findOrFail($request->point);
            session(['point_id' => $request->point]);
            session(['point' => $data['point']->descripcion]);
        }

        session(['fechaventa' => $request->date]);

        $data = '<label class="label label-primary">' . Session::get('point') . '</label>';

        return $data;
    }
}
