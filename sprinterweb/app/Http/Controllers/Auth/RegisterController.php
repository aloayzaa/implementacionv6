<?php

namespace App\Http\Controllers\Auth;

use App\Companies\Entities\Empresa;
use App\Contributor\Entities\Contribuyentes;
use App\Http\Controllers\Controller;
use App\Suscriptions\Entities\Suscripcion;
use App\Users\Entities\User;
use App\Users\Entities\UserCompany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use PDOException;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $user = 'root'; // localhost y servidor
    protected $pass = 'W0won5$5'; // localhost y servidor
    protected $conf_bd = 'sprinter_';

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Users\Entities\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

  /*  public function showRegistrationForm()
    {
        return view('login.register');            UserController
    }*/

    public function Register(Request $request)
    {
        $query = Contribuyentes::where('emp_ruc', $request->txt_ruc)->first();

        if (isset($query)) {
            try {
                $suscripcion = new Suscripcion();
                $suscripcion->sus_codigo = '00001';
                $suscripcion->pla_id = 1;
                $suscripcion->fop_id = 1;
                $suscripcion->sus_fechainicio = date("Y-m-d");
                $suscripcion->sus_fechafin = date('Y-m-d', strtotime('+1 month'));
                $suscripcion->sus_estado = 1;
                $suscripcion->ver_id = 3;

                $empresa = new Empresa();
                $empresa->emp_codigo = $request->txt_ruc;
                $empresa->emp_descripcion = $request->txt_empresa;
                $empresa->emp_ruc = $request->txt_ruc;
                $empresa->emp_contacto = $request->txt_apellidos . ' ' . $request->txt_nombre;
                $empresa->emp_direccion = $request->txt_address;
                $empresa->emp_telefono = $request->txt_celular;
                $empresa->emp_observaciones = '';
                $empresa->emp_estado = 1;
                $empresa->emp_razon_social = $request->txt_empresa;
                $empresa->emp_fecha_registro = date("Y-m-d");
                $empresa->ver_id = 3;

                $usuario = new User();
                $usuario->usu_usuario = strtoupper($request->txt_correo);
                $usuario->usu_nombres = strtoupper($request->txt_nombre);
                $usuario->usu_apellidos = strtoupper($request->txt_apellidos);
                $usuario->usu_clave = bcrypt($request->txt_password);
                $usuario->usu_estado = 1;
                $usuario->usu_correo = $request->txt_correo;
                $usuario->usu_telefono = $request->txt_celular;
                $usuario->usu_usuario_registra = '';
                $usuario->usu_fecha_registro = date("Y-m-d");

                $usuarioEmpresa = new UserCompany();
                $usuarioEmpresa->uem_estado = 1;
                $usuarioEmpresa->uem_usuario = $request->txt_correo;
                $usuarioEmpresa->uem_usuario = strtoupper($request->txt_correo);
                $usuarioEmpresa->rol_id = 1;

                DB::transaction(function () use ($suscripcion, $empresa, $usuario, $usuarioEmpresa, $request) {
                    $usuario->save();
                    $empresa->emp_usuario = $usuario->id;
                    $empresa->save();
                    $empresa->emp_primaria_id = $empresa->id;
                    $empresa->save();
                    $suscripcion->sus_usuario = $usuario->id;
                    $suscripcion->emp_id = $empresa->id;
                    $suscripcion->save();
                    $usuarioEmpresa->usu_id = $usuario->id;
                    $usuarioEmpresa->emp_acargo_id = $empresa->id;
                    $usuarioEmpresa->emp_pertenece_id = $empresa->id;
                    $usuarioEmpresa->save();

                    $this->crea_conexion_bd($request);
                    //$this->enviar_correo($usuario->id, $request);
                });

                Session::flash('flash_message', 'Revisa tu correo para confirmar registro');
                return Redirect()->route('enter');

            } catch (PDOException $e) {
                Session::flash('flash_message', $e->getMessage());
                return Redirect()->route('register');
            }
        }
    }

    private function crea_conexion_bd($request)
    {
        $this->inserta_datos_conexion($request);
        $this->crea_bd($request);
    }

    private function inserta_datos_conexion($request)
    {
        $filename = config_path() . '/database.php';

        $file = fopen($filename, "r+");
        $lineas = file($filename);
        foreach ($lineas as $num_linea => $linea) {
            if ($num_linea == 63) {
                fwrite($file, "        'DB_CONNECTION_" . $request->txt_ruc . "' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => '" . $this->conf_bd . $request->txt_ruc . "',
            'username'  => '" . $this->user . "',
            'password'  => '" . $this->pass . "',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'port'      => '3306',
            'strict'    => false,
        ],

");
            }

            fwrite($file, $linea);
        }
        fclose($file);
    }

    private function crea_bd($request)
    {
        $public = public_path();
        $basedatos = $this->conf_bd . $request->txt_ruc;

        $this->bd($basedatos, $public);
    }

    private function bd($basedatos, $public)
    {
        $file = $public . '/sprinterwebv1.sql';

        exec($public . '/ejecuta.sh ' . $basedatos);

        exec('/usr/bin/mysql --init-command="SET SESSION FOREIGN_KEY_CHECKS=0;" --default-character-set=latin1 -u root \'-pW0won5$5\' --port=3306 --max_allowed_packet=1024M ' . $basedatos . ' < ' . $file . '');
    }

    private function enviar_correo($usuario_id, $request)
    {
        $nombres = $request->txt_nombre;
        $apellidos = $request->txt_apellidos;

        Mail::send('emails.creacion_usuario', ['nombres' => $nombres, 'apellidos' => $apellidos], function ($msj) use ($request) {
            $msj->subject('*** Bienvenido a POS AnikamaGroup ');
            $msj->to($request->txt_correo);
        });

        /*$this->envia_correo_admin('cllave@anikamagroup.com',$usuario_id);
        $this->envia_correo_admin('allave@anikamagroup.com',$usuario_id);
        $this->envia_correo_admin('gli@anikamagroup.com',$usuario_id);
        $this->envia_correo_admin('jmendoza@anikamagroup.com',$usuario_id);*/
        $this->envia_correo_admin('aalfaro@anikamagroup.com', $usuario_id);
        /*$this->envia_correo_admin('soporte@anikamagroup.com', 'gli@anikamagroup.com' , $usuario_id);*/
    }

    private function envia_correo_admin($email_to1, $usuario_id)
    {
        $usuario = User::findorfail($usuario_id);
        $nombres = $usuario->usu_nombres;
        $apellidos = $usuario->usu_apellidos;
        $correo = $usuario->usu_usuario;

        Mail::send('emails.alerta_nuevo_usuario', ['nombres' => $nombres, 'apellidos' => $apellidos, 'correo' => $correo], function ($msj) use ($email_to1) {
            $msj->subject('*** NUEVO REGISTRO EN POS ***');
            $msj->to($email_to1);
        });
    }
}
