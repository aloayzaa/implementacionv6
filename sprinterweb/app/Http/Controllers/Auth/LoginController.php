<?php

namespace App\Http\Controllers\Auth;

use App\Companies\Entities\Empresa;
use App\Company;
use App\Http\Controllers\Panel\ConexionController;
use App\Mail\UserCreated;
use App\Period\Entities\Period;
use App\Ubigeos\Entities\Ubigeo;
use App\User;
use App\Users\Entities\UserByCompany;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class LoginController extends ConexionController
{

    use AuthenticatesUsers;

    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('panel/login.entry');
    }

    protected function authenticated(Request $request)
    {

        $this->validate($request, [
            'empresa' => 'required',
            'periodo' => 'required',
            'usu_correo' => 'required',
            'password' => 'required',
        ],[
            'empresa.required' => 'Seleccione una empresa',
            'periodo.required' => 'Seleccione un periodo',
            'usu_usuario.required' => 'Ingrese su usuario',
            'password.required' => 'Ingrese su contraseña',
        ]);

        $empresa = Company::where('ruc', $request->empresa)->first();

        DB::setDefaultConnection('DB_CONNECTION_' . $empresa->ruc);
        DB::purge('DB_CONNECTION_' . $empresa->ruc);

        $user_company = UserByCompany::where('email', $request->usu_correo)->first();

          if (auth()->attempt(['email' => $request->usu_correo,'password' => $request->password])) {
              $this->session($user_company, $empresa, $request->periodo); //va antes porq logout necesita datos de la sesion
              //$rol = $userCompany->rol_id;
              if ($empresa->isSubscribed()) { //todavia no veo estado o softdetaletes
                  session(['daysleft' => $empresa->DaysLeft()]);
                //sesion_empresa_cliente($empresa); parece repetido

                  $data['ruta'] = route('sprinter');
              } else {
                  return response()->json(['error' => 'Su suscripción ha expirado']);
                 //$data['ruta'] = route('logout');  //ta pa chekear
              }
              return $data;

            }else{
              return response()->json(['error' => 'Contraseña Incorrecta']);
          }
    }

    public function session($user_company, $empresa, $periodo)
    {
        $periodo = Period::findOrFail($periodo);
        $ubigeo_id = Ubigeo::where('codigo', $empresa->ubigeo_code)->first()->id;
        session([
            'conexionbd' => 'DB_CONNECTION_' . $empresa->ruc,
            'company' => $empresa->id,
            'company_ruc' => $empresa->ruc,
            'company_desc' => $empresa->name,
            'id_empresa' => $empresa->id,
            'ubigeo' => $empresa->ubigeo_code,
            'ubigeo_id' => $ubigeo_id,
            'user_rol' => 4, //checkear si es necesario
            'permissions' => [
                0 => 9,
                1 => 10,
                2 => 11,
                3 => 12,
                4 => 13,
                5 => 14,
                6 => 15,
                7 => 16,
                8 => 17,
            ],
            'period' => $periodo->descripcion,
            'period_id' => $periodo->id,
            'usuario' => $user_company->codigo
        ]);
    }

    public function logout(Request $request)
    {
        // dd(Auth::check());
        DB::setDefaultConnection('DB_CONNECTION_'. \Illuminate\Support\Facades\Session::get('company_ruc'));
        DB::purge('DB_CONNECTION_'. Session::get('company_ruc'));

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
