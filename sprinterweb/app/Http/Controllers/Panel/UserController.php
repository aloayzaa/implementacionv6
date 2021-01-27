<?php

namespace app\Http\Controllers\Panel;

use App\Company;
use App\Http\Controllers\Controller;
use App\Jobs\CreateConecction;
use App\Jobs\CreateDatabase;
use App\Jobs\CreateUserAdmin;
use App\Jobs\SendEmail;
use App\Mail\UserActived;
use App\Mail\UserCreated;
use App\Period\Entities\Period;
use App\Subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDOException;


class UserController extends Controller
{
    protected $supervisor;

    public function __construct()
    {
        $this->supervisor = env('MAIL_SUPERVISOR');
    }

    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function create()
    {
        return view('panel/login.register');
    }

    public function validarAdmin(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        return $user ? '1' : '0';
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'txt_ruc' => 'required|unique:companies,ruc',
            'txt_empresa' => 'required',
            'txt_address' => 'required',
            'txt_ubigeo' => 'required',
            'txt_apellidos' => 'required',
            'txt_nombre' => 'required',
            'txt_celular' => 'required',
            'txt_correo' => 'required|unique:users,email',
            'txt_password' => 'required',
            'check_terminos' => 'required',
        ], [
            'txt_ruc.required' => 'El Ruc es requerido',
            'txt_ruc.unique' => 'Ese Ruc ya se encuentra registrado',
            'txt_empresa.required' => 'La empresa es requerida',
            'txt_address.required' => 'La dirección es requerida',
            'txt_apellidos.required' => 'El appelido es requerido',
            'txt_nombre.required' => 'El nombre es requerido',
            'txt_celular.required' => 'El celular es requerido',
            'txt_correo.required' => 'El email es requerido',
            'txt_correo.unique' => 'El email es ya está registrado',
            'txt_password.required' => 'La contraseña es requerida',
            'check_terminos.required' => 'Debe Aceptar los Términos y Condiciones',
        ]);

        try {

            $user = new User();
            $user->name = $request->txt_nombre;
            $user->phone = $request->txt_celular;
            $user->email = $request->txt_correo;
            $user->password = bcrypt($request->txt_password);
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generaVerificationToken();
            $user->state = 0; //al crecar bd cambiar

            DB::transaction(function () use ($user) {
                $user->save();
            });
            //Empresa
            $company = new Company();
            $company->name = $request->txt_empresa;
            $company->ruc = $request->txt_ruc;
            $company->ubigeo_code = $request->txt_ubigeo;
            $company->contact = $request->txt_nombre;
            $company->direction = 'nuse';
            $company->user_id = $user->id;
            $company->save();

            //Suscripcion Prueba
            $suscripcion = new Subscription();
            $suscripcion->name = 'Suscripcion Prueba';
            $suscripcion->company_id = $company->id;
            $suscripcion->plan_id = 1;
            $suscripcion->started_at = Carbon::now()->format('Y-m-d'); //podria empezar cuandu gustavo active usuario
            $suscripcion->finished_at = Carbon::now()->addMonth()->format('Y-m-d');
            $suscripcion->renewal = Subscription::RENUEVA_SUSCRIPCION;
            $suscripcion->save();

            Mail::to($user->email)->send(new UserCreated($user));

            $data['ruta'] = route('enter');


        } catch (PDOException $e) {

            return response()->json(['errors' => $e->getMessage()]);
        }

        return $data;
    }

    public function show(User $user)
    {
        return $user;
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if ($user) {
            if($user->verified == User::USUARIO_NO_VERIFICADO) {

                $user->verified = User::USUARIO_VERIFICADO;
                $user->save();
                Mail::to($this->supervisor)->send(new UserActived($user));

            }
                $data['message'] = 'Le enviaremos un mensaje cuando su base de datos esté lista';
                return view('emails/views/verified', $data);
        }

        return view('emails/views/notoken');

    }

    public function verify_by_supervisor($token)
    {
        $user = User::where('verification_token', $token)->first();

        if ($user) {

            $user->verification_token = null;
            $user->save();

            // $this->ejecutar_script($user->companies->ruc);
            // $this->create_database($user->companies->pluck('ruc')->first());

            $ruc =  $user->companies()->get()->pluck('ruc')->first();

            CreateConecction::withChain([
                new CreateDatabase($ruc),
                new CreateUserAdmin($user, $ruc),
                new SendEmail($user),
            ])->dispatch($ruc);

            $data['message'] = 'Base de datos creada exitosamente, le enviaremos un email al usuario';

            return view('emails/views/verified', $data);
        }

        return view('emails/views/notoken');

    }

    public function companies(Request $request)
    {
        $email = $request->usu_correo;
        $user = User::Where('email', $email)->first();

        if ($user) {

            return $user->companies;

        } else {
            abort(422, "No existe ese usuario");
        }

    }

    public function periods(Request $request)
    {
        $empresa = Company::where('ruc', $request->ruc)->first();

        if($empresa){
            DB::setDefaultConnection('DB_CONNECTION_' . $empresa->ruc);
            DB::purge('DB_CONNECTION_' . $empresa->ruc);

            $periodos = Period::all();
            return $periodos;
        }else{
            return response()->json(['errors' => ['mensaje' => 'No existe el ruc']], 422);
        }

    }

}
