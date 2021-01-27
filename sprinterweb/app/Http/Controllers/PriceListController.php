<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Panel\Companies\Entities\Pempresa;
use App\Products\Entities\Productos;
use App\Tarifariosuc\Entities\Ratsuc;
use Illuminate\Http\Request;
use App\Subsidiaries\Entities\Subsidiaries;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;


class PriceListController extends Controller
{
    use Tables, UserPrivileges;

    private $var = 'pricelist';
    private $tabla_cabecera = 'producto';
    protected $user;
    protected $privilegios = '03.01.03';
    private $vtatarifario;


    public function __construct()
    {
        obtener_cliente();
        $this->user = 'ADMINISTRADOR';
        $this->vtatarifario = Pempresa::get_parametro('VTA_TARIFA');

    }
    public function listar_detalle()
    {
        $ratesuct = Ratsuc::selectparentcmd();
        return DataTables::of($ratesuct)->make();
    }


    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['route']=$request->route;
        $data['proceso']= $request->proceso;
        $data['view'] = link_view('Gestión de Ventas', 'Configuración', 'Lista de Precios', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['sucursales'] = Subsidiaries::all();
        $data['productos'] = Productos::all();
        $data['monedas'] = Currency::all()->where('estado', 1);


        $v = $this->ventanas($this->vtatarifario);
        return view("pricelist.$v", $data);
    }

    public function buscar_producto(Request $request)
    {
        $resultado = Productos::select2($request->term);
        return $resultado;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        if (!empty($request->id)){
//            return $this->actulizar_tarifario_sucursal($request);
//        }
//
//        $listprecio->save();

        try {
            $listprecio = new Ratsuc() ;
            $listprecio->ruta_id = null;
            $listprecio->sucursal_id = (isset($request->txt_sucursal)) ? $request->txt_sucursal : null;
            $listprecio->cliente_id = null;
            $listprecio->producto_id = (isset($request->cbo_producto)) ? $request->cbo_producto : null;
            $listprecio->umedida_id = (isset($request->un_medida)) ? $request->un_medida : null;
            $listprecio->tipoprecio = 0;
            $listprecio->moneda_id = (isset($request->currency)) ? $request->currency : null;
            $listprecio->precio = (isset($request->txt_precio1)) ? $request->txt_precio1 : 0;
            $listprecio->precio2 = (isset($request->txt_precio2)) ? $request->txt_precio2 : 0;
            $listprecio->precio3 = (isset($request->txt_precio3)) ? $request->txt_precio3 : 0;
            $listprecio->precio4 = (isset($request->txt_precio4)) ? $request->txt_precio4 : 0;
            $listprecio->precio5 = (isset($request->txt_precio5)) ? $request->txt_precio5 : 0;
            $listprecio->precio6 = (isset($request->txt_precio6)) ? $request->txt_precio6 : 0;
            $listprecio->condicionpago_id = null;
            $listprecio->factura = 0;
            $listprecio->glosa =(isset($request->comment)) ? $request->comment : '';
            $listprecio->vigencia=null;
            $listprecio->usuario = Session::get('usuario');
            $id=null;
            $id= DB::transaction(function () use ($listprecio){
                $listprecio->save();
                return  $listprecio->id;
            });
            $r["ruta"]=route('pricelist');
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        $r["proceso"] = $request->proceso;

        return $r;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        //
//    }

    public function ventanas($ventana)
    {
        switch ($ventana->valor) {
            case 0:
                $vent = 'y';
                break;
            case 1:
                $vent = 'create';
                break;
            case 3:
                $vent = 'x';
                break;
            default:
                $vent = 'a';
                break;
        }

        return $vent;
    }

    public function editar(Request $request){

        $tarifario = Ratsuc::editar_tarifario_sucursal($request->id);
        return $tarifario;

    }

    private function  actulizar_tarifario_sucursal($request){

        $validar_costo_detalle = $this->validar_costo_detalle();
        if(!is_null($validar_costo_detalle)){return $validar_costo_detalle;}

        try {
            $ordencompra = PurchaseOrder::find($request->id);
            //$ordencompra->periodo_id = Session::get('period_id');
            $ordencompra->serie = ltrim($request->txt_serie, 0);
            //$ordencompra->numero = $request->;
            $ordencompra->tipo = $request->cbo_tipo_producto;
            $ordencompra->fechaproceso = $request->txt_fecha;
            $ordencompra->unegocio_id = $request->cbo_unidad_negocio;
            $ordencompra->tercero_id = $request->cbo_razon_social;
            //obtener almacen y sucursal
            $data_alm_suc = PointSale::obtener_almacen_sucursal($request->cbo_punto_emision);
            $ordencompra->sucursal_id = $data_alm_suc->idsucursal;
            $ordencompra->almacen_id = $data_alm_suc->idalmacen;
            $ordencompra->moneda_id = $request->cbo_moneda_orden;
            $ordencompra->tcambio = $request->changerate;
            $ordencompra->tcmoneda = $request->txt_tc_orden;
            $ordencompra->condicionpago_id = $request->cbo_condicion_pago;
            $ordencompra->fechaentrega = $request->txt_fecha_entrega;
            $ordencompra->lugarentrega = $request->txt_lugar_entrega;
            $ordencompra->solicitadopor = ($request->cbo_contacto_otros_datos != '') ? $request->cbo_contacto_otros_datos : '';
            $ordencompra->ctacte = ($request->cbo_depositar_otros_datos != '') ? $request->cbo_depositar_otros_datos : '';
            $ordencompra->glosa = ($request->txt_observaciones != '') ? $request->txt_observaciones : 'PEDIDO ORDEN DE COMPRA';
            $ordencompra->impuesto_id = ($request->cbo_igv_tributos != 0) ? $request->cbo_igv_tributos : null;
            if($request->cbo_igv_tributos != 0){
                $codigo_impuesto1 = Taxes::select('codigo')->where('id',$request->cbo_igv_tributos)->first();
                if($codigo_impuesto1->codigo != 99){
                    $ordencompra->impuesto2_id = ($request->cbo_percepcion_tributos != 0) ? $request->cbo_percepcion_tributos : null;
                }else{
                    $ordencompra->impuesto2_id = null;
                }
            }
            $ordencompra->base = $request->txt_base;
            $ordencompra->inafecto = $request->txt_inafecto;
            $ordencompra->impuesto = $request->txt_impuesto;
            $ordencompra->impuesto2 = $request->txt_impuesto2;
            //$ordencompra->impuesto3 =
            $ordencompra->total = $request->txt_total;
            $ordencompra->ventana = 'MOV_ORDENCOMPRA';
            //$ordencompra->estado = 'ACTIVO';
            $ordencompra->incluyeimpto = (isset($request->chk_incluye_impuestos) && $request->chk_incluye_impuestos == "on") ? 1 : 0;
            //$ordencompra->esservicio =
            $ordencompra->tipopeso = $request->opt_tipopeso;
            $ordencompra->usuario = Session::get('usuario');
            //$ordencompra->undtransporte_id =
            $ordencompra->puntoventa_id = $request->cbo_punto_emision;
            $ordencompra->tipodetraccion_id = ($request->cbo_tipo_detraccion != 0) ? $request->cbo_tipo_detraccion : null;
            $ordencompra->referencial = ($request->txt_valor_referencial != 0 && is_numeric($request->txt_valor_referencial)) ? $request->txt_valor_referencial : 0.00;
            $ordencompra->totaldetraccion = ($request->txt_importe != 0 && is_numeric($request->txt_importe)) ? $request->txt_importe : 0.00;
            $ordencompra->notas = ($request->txt_notas_adicionales != '') ? $request->txt_notas_adicionales : '';
            //$ordencompra->save();
            //$id = $request->id;
            //$this->save_details($id);

            $id = null;
            $id = DB::transaction(function () use ($ordencompra) {
                $ordencompra->save();
                $id = $ordencompra->id;
                $this->save_details($id);
                return $id;
            });
            $r["ruta"]=route('pricelist');
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        }catch(PDOException $e) {
            $data["estado"] = "error";
            $data["error"] = "Error al Grabar";
            $data["bd"] = $e->getMessage();
        }

        return $data;

    }
}
