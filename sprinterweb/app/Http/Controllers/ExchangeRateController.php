<?php

namespace App\Http\Controllers;

use App\ExchangeRate\Entities\ExchangeRate;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\TcambioProcedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ExchangeRateController extends Controller
{
    use Tables, UserPrivileges;
    private $var = 'exchangerate';
    private $exchangeratecollection;
    private $tcambioProcedure;
    protected $user;
    protected $privilegios = '13.01.03';

    public function __construct(TcambioProcedure $tcambioProcedure)
    {
        $this->obtener_cliente();
        $this->tcambioProcedure = $tcambioProcedure;
        $this->user = 'ADMINISTRADOR';
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Tipo de Cambio', '');
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('exchangerate.list', $data);
    }

    public function list(Request $request)
    {
        //Ya no inicializa buscando el id en sesion porq si se elimina y se vuelve a crear otro igual será otro id y error

        if($request->has('period') && $request->has('year')){
            $primera = $request->period;
            $segunda = $request->year;

            $codigo = $segunda . $primera;

        }else{
            return DataTables::of([])->make();
        }

        $periods = ExchangeRate::filtravalores($codigo);
        return DataTables::of($periods)->make();
    }

    public function edit($id)
    {

        $data["var"] = $this->var;
        $data["tipocambio"]  = ExchangeRate::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Gestión Tributaria', 'Configuración', 'Tipo de Cambio','');
        $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('exchangerate.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            't_compra' => 'required',
            't_venta' => 'required',
        ], [
           't_compra.required' => 'El tipo de compra es necesario',
           't_venta.required' => 'El tipo de venta es necesario',
        ]);

        $tipocambio = ExchangeRate::findOrFail($id);
        $tipocambio->t_compra = $request->t_compra;
        $tipocambio->t_venta = $request->t_venta;

        DB::transaction(function () use ($tipocambio) {
            $tipocambio->save();
        });

        $this->tcambioProcedure->setTcambio($tipocambio->fecha);


        $data["success"] = "Se guardaron los cambios correctamente";

        return $data;

    }

    public function getTipoCambioSunat(Request $request){

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes='.$request->period.'&anho='.$request->year.'&accion=init');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Sprinter, Anikama Group SAC');
        $html = curl_exec($curl_handle);
        curl_close($curl_handle);

        $dom = new \DOMDocument();

        @$dom->loadHTML($html);

        $dom->strictErrorChecking = FALSE;

        libxml_use_internal_errors(false);
        $xml = simplexml_import_dom($dom);

        $fecha = $xml->xpath("//center/h3"); //titulo de fecha sunat
        $dias = $xml->xpath("//table/tr/td[@class='H3']");
        $compra_venta = $xml->xpath("//table/tr/td[@class='tne10']");

        $rtn = array();

      //  $periodo = $request->period.'-'.$request->year;  // mes - año

        if( !empty($fecha) )
        {
            $periodo = (string)$fecha[0]; //titulo de la fecha a consumir
        }
        if( !empty($dias) && !empty($compra_venta) && count((array)$dias) == count((array)$compra_venta)/2 )
        {
            foreach($dias as $i => $obj)
            {
                $rtn[$i]['dia'] = str_pad(trim((string)$obj->strong),2,0,STR_PAD_LEFT);
                //$rtn[$i]['fecha'] = str_pad(trim((string)$obj->strong),2,0,STR_PAD_LEFT) . '/'. $mes.'/'.$anio;
            }
            $cont = 0;
            foreach($compra_venta as $i=>$obj)
            {
                if( ($i+1)%2==0 )
                {
                    $rtn[$cont]['venta'] = trim((string)$obj);
                    $cont++;
                }
                else
                {
                    $rtn[$cont]['compra'] = trim((string)$obj);
                }
            }
        }

        $collect = collect($rtn);

        if($collect->isEmpty()){

            return response()->json(['errors' => array('detalle' => 'No existe data para esa fecha')],422);

        }else {

            $codigo = $request->year.$request->period;

            $intancias = ExchangeRate::where('codigo', 'like', $codigo . '%')  ->orderBy('fecha', 'asc')->get();

            if($intancias->isEmpty()){
                return response()->json(['errors' => array('detalle' => 'No existe el periodo')],422); //Se supone que si hay periodo si hay tcambios
            }

            $compra = 0.00;
            $venta = 0.00;

            $ultimosunat = $collect->last();


            foreach ($intancias as $instancia){
                $fechita = explode('-', $instancia->fecha);

                if($fechita[2] > $ultimosunat['dia'] ){
                   break;
                }

                $item = $collect->firstWhere('dia', $fechita[2]);

                if($item){

                    $instancia->t_compra = $item['compra'];
                    $instancia->t_venta = $item['venta'];
                    $instancia->usuario = Session::get('usuario');

                    $compra = $instancia->t_compra;
                    $venta = $instancia->t_venta;

                }else{

                    $instancia->t_compra = $compra;
                    $instancia->t_venta = $venta;
                    $instancia->usuario = Session::get('usuario');

                }

                DB::transaction(function () use ($instancia) {
                    $instancia->save();
                    $this->tcambioProcedure->setTcambio($instancia->fecha);
                });

            }
        }

        $data['message'] = 'Se actualizaron los datos de: '.$periodo;
        return $data;
    }

    public function show($fecha)
    {
        $tcambio = ExchangeRate::where('fecha', $fecha)->first();

        if($tcambio){
            //return $tcambio->t_compra;
            return [
                "t_venta" => $tcambio->t_venta,
                "t_compra" => $tcambio->t_compra
            ];
        }

    }

    public function destroy($id)
    {
        //
    }
}
