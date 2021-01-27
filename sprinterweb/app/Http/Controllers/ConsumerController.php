<?php

namespace App\Http\Controllers;

use App\Customers\Entities\Customer;
use App\ExchangeRate\Entities\ExchangeRate;
use App\Http\Traits\Controls\Tables;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    use Tables;

    protected $url;
    protected $token;

    public function __construct()
    {
        $this->obtener_cliente();
        $this->url = env('API_SERVICES_URL');
        $this->token = env('API_SERVICES_TOKEN');
    }

    public function consultar_ruc_contribuyente($codigo)
    {
        $data = $this->callAPI($this->url . 'consulta_ruc/' . $codigo);
        return response()->json(json_decode($data));
    }

    public function consultar_dni($dni)
    {
        //Consulta a reniec
        $data = $this->callAPI($this->url . 'consulta_dni/' . $dni);
        return response()->json(json_decode($data));
    }

    public function consultar_locales_anexos($ruc)
    {
        $data = $this->callAPI($this->url . 'locales-anexos/' . $ruc);
        return response()->json(json_decode($data));

    }

    public function consultar_tcambio($fecha)
    {
/*        $i = 1;
        $tc = ExchangeRate::where('fecha', $fecha)->first();

        if ($tc) {
            $resp['tventa'] = $tc->t_venta;
        } else {
            do {
                $fecha_anterior = restar_1_dia($fecha, $i);
                $tc = ExchangeRate::where('fecha', $fecha_anterior)->first();
                if ($tc) {
                    $resp['tventa'] = $tc->t_venta;
                } else {
                    $i++;
                }

            } while ($tc == null);
        }

        return $resp['tventa'];*/
    }

    private function callAPI($url){

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
        ));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Sprinter, Anikama Group SAC');
        $query = curl_exec($curl_handle);
        if(!$query){die("Connection Failure");}
        curl_close($curl_handle);

        return $query;
    }

    public function download($archivo)
    {
        return response()->download(public_path() . '/ples/' . $archivo);
    }
}
