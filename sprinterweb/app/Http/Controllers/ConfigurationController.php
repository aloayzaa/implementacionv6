<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
//use App\Companies\Entities\Pempresa;
use App\Panel\Companies\Entities\Pempresa;
use App\Costs\Entities\Costs;
use App\Ubigeos\Entities\Ubigeo;
use App\Documents\Entities\Commercial;
use App\Http\Traits\Controls\UserPrivileges;
use App\MovementTypes\Entities\MovementType;
use App\OperationType\Entities\OperationType;
use App\Period\Entities\Period;
use App\Subdiaries\Entities\Subdiaries;
use App\Taxes\Entities\Taxes;
use App\TransactionType\Entities\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConfigurationController extends Controller
{
    use UserPrivileges;
    private $var = 'configuration';

    protected $user;
    protected $privilegios = '99.01.01';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
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
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $pempresa=$this->validapempresa('APERTURA', 'BANCOS', 'Periodo de Apertura', '');
        $data['id_periodo'] = $pempresa->valor;

        //CENTRALIZACION DE ALMACEN
        $pempresa=$this->validapempresa('CEN_ALMAC', 'GENERAL', 'Centralización módulo de almacén', '');
        $data['chkalmacen'] = $pempresa->valor;

        //CENTRALIZACION DE VENTAS
        $pempresa=$this->validapempresa('CEN_VENTA', 'GENERAL', 'Centralización módulo de Ventas', '');
        $data['chkVentas'] = $pempresa->valor;

        //CENTRALIZACION DE PRODUCTOS
        $pempresa=$this->validapempresa('CEN_COMPR', 'GENERAL', 'Centralización módulo de Cuentas por pagar', '');
        $data['chkCpagar'] = $pempresa->valor;

        //CENTRALIZACION DE TESOSERIA
        $pempresa=$this->validapempresa('CEN_BANCO', 'GENERAL', 'Centralización módulo de Tesorería', '');
        $data['chkTesoreria'] = $pempresa->valor;

        //CENTRALIZACION DE ACTIVOS
        $pempresa=$this->validapempresa('CEN_AFIJO', 'GENERAL', 'Centralización módulo de Activos', '');
        $data['chkActivos'] = $pempresa->valor;

        //INTERFACES PARA VISUALIZAR FICHAS O FORMULAS
        $pempresa=$this->validapempresa('MOD_MAXIMO', 'GENERAL', 'Número máximo de módulos que se ven en el menú', '');
        $data['nroMaxModulo'] = $pempresa->valor;

        //PACIENTE COMUN
        $pempresa=$this->validapempresa('USR_EXPIRA', 'GENERAL', 'La contraseña de los usuarios expira en N días', '0');
        $data['nroUserExpira'] = $pempresa->valor;

        //FUENTE
        $pempresa=$this->validapempresa('AGENTERETE', 'VALORES', 'La empresa es Agente de Retención', '');
        $data['chkAgente'] = $pempresa->valor;

        //TAMAÑO
        $pempresa=$this->validapempresa('AGENTEDESDE', 'GENERAL', 'Es Agente de Retención desde el', '');
        $fecha = new Carbon($pempresa->valor);
        $data['agenteDesde'] =    $fecha->format('Y-m-d');

        //PUNTO
        $pempresa=$this->validapempresa('PRY_PRIVIL', 'GENERAL', 'Activar control de puntos de retención', '');
        $data['chkPunto'] = $pempresa->valor;

        //CPE's
        $pempresa=$this->validapempresa('TASK2_ACTI', 'GENERAL', 'Activar Tarea 2 Avisar Límite de Tiempo para CPEs', '');
        $data['chkCPEs'] = $pempresa->valor;

        //Cada N minutos
        $pempresa=$this->validapempresa('TASK2_TIME', 'GENERAL', 'Ejectar Tarea 2 cada N minutos', '');
        $data['nminutos'] = $pempresa->valor;


        $data['periodo'] = Period::all()->where('estado', 1);

        $data['varconf'] = "generales";
        return view('system_configuration.generales', $data);
    }

    public function procesar_generales(Request $request){
        try{
            //PROCESA NOMENCLATURA FAMILIA
            $id_periodo = ($request->txt_periodo>=1) ? $request->txt_periodo : NULL;
            $nombreParametro='APERTURA';
            $descripcion="PERIODO DE APERTURA";
            $tipo="TODOS";
            $result=$this->procesaPempresa($nombreParametro,$id_periodo,$descripcion,$tipo);

            //PROCESA CHKD
            $chkalmacen = ($request->chk_logistica_almacenes == 'on') ? 1 : 0;
            $nombreParametro='CEN_ALMAC';
            $descripcion="Centralización módulo de almacén";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkalmacen,$descripcion,$tipo);

            //PROCESA CHKD
            $chkVentas = ($request->chk_ventas_factura == 'on') ? 1 : 0;
            $nombreParametro='CEN_VENTA';
            $descripcion="Centralización módulo de Ventas";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkVentas,$descripcion,$tipo);

            //PROCESA CHKD
            $chkCpagar = ($request->chk_cta_pagar == 'on') ? 1 : 0;
            $nombreParametro='CEN_COMPR';
            $descripcion="Centralización módulo de Cuentas por pagar";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkCpagar,$descripcion,$tipo);

            //PROCESA CHKD
            $chkTesoreria = ($request->chk_tesoreria == 'on') ? 1 : 0;
            $nombreParametro='CEN_BANCO';
            $descripcion="Centralización módulo de Tesorería";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkTesoreria,$descripcion,$tipo);

            //PROCESA CHKD
            $chkActivos = ($request->chk_activos == 'on') ? 1 : 0;
            $nombreParametro='CEN_AFIJO';
            $descripcion="Centralización módulo de Activos";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkActivos,$descripcion,$tipo);

            //PROCESA MODULO MAXIMO
            $nroMaxModulo=$request->txt_maximo_mod;
            $nombreParametro='MOD_MAXIMO';
            $descripcion="Número máximo de módulos que se ven en el menú";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$nroMaxModulo,$descripcion,$tipo);

            //PROCESA EXPIRACION USER
            $nroUserExpira=$request->txt_expira_contra;
            $nombreParametro='USR_EXPIRA';
            $descripcion="La contraseña de los usuarios expira en N días";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$nroUserExpira,$descripcion,$tipo);

            //PROCESA AGENTE DE RETENCION
            $chkAgente = ($request->chk_ag_retencion == 'on') ? 1 : 0;
            $nombreParametro='AGENTERETE';
            $descripcion="LA EMPRESA ES AGENTE DE RETENCION";
            $tipo="VALORES";
            $result=$this->procesaPempresa($nombreParametro,$chkAgente,$descripcion,$tipo);

            //PROCESA AGENTE DESDE
            $agenteDesde=$request->txt_vig_desde;
            $nombreParametro='AGENTEDESDE';
            $descripcion="Es Agente de Retención desde el";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$agenteDesde,$descripcion,$tipo);

            $chkPunto = ($request->chk_activar_pto_rete == 'on') ? 1 : 0;
            $nombreParametro='PRY_PRIVIL';
            $descripcion="Activar control de puntos de retención";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkPunto,$descripcion,$tipo);

            $chkCPEs = ($request->chkCPEs == 'on') ? 1 : 0;
            $nombreParametro='TASK2_ACTI';
            $descripcion="Activar Tarea 2 Avisar Límite de Tiempo para CPEs";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$chkCPEs,$descripcion,$tipo);

            $nMinutos = $request->nminutos;
            $nombreParametro='TASK2_TIME';
            $descripcion="Ejectar Tarea 2 cada N minutos";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$nMinutos,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function maestros(Request $request){
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        //NOMENCLATURA FAMILIAS
        $pempresa=$this->validapempresa('FAM_NOMENC', 'CATALOGO', 'Nomenclatura para las familias de productos', '2-2-2');
        $data['nomFamilia'] = $pempresa->valor;

        //NOMENCLATURA CENTRO COSTO
        $pempresa=$this->validapempresa('NOM_CCOSTO', 'CATALOGO', 'Nomenclatura para los centros de costo', '1-2-2-n');
        $data['nomCcosto'] = $pempresa->valor;

        //NOMENCLATURA PLAN DE CUENTAS
        $pempresa=$this->validapempresa('NOM_CUENTA', 'CATALOGO', 'Nomenclatura para el plan de cuentas', '2-1-1-n');
        $data['nomPcuentas'] = $pempresa->valor;

        //NOMENCLATURA GENERACION CODIGO PRODUCTO
        $pempresa=$this->validapempresa('PRD_CODIGO', 'CATALOGO', 'Generación del código del producto', 'AUTOMATICO');
        $data['cboComboSegenera'] = $pempresa->valor;

        //NOMENCLATURA LONGITUD DEL CODIGO
        $pempresa=$this->validapempresa('PRD_LONGIT', 'CATALOGO', 'Longitud máxima del código del producto', '6');
        $data['longCodigo'] = $pempresa->valor;


        //NOMENCLATURA GESTION DE PRODUCTO INDEPENDIENTE POR EMPRESA
        $pempresa=$this->validapempresa('PDTOXEMPRE', 'CATALOGO', 'La gestión de productos es independiente por empresa', '0');
        $data['chkGestionIndependiente'] = $pempresa->valor;

        //INTERFACES PARA VISUALIZAR FICHAS O FORMULAS
        $pempresa=$this->validapempresa('PRD_WINDOW', 'CATALOGO', 'Interface para visualizar las fichas o fórmulas', 'GENERAL');
        $data['InterFicha'] = $pempresa->valor;

        //PACIENTE COMUN
        $pempresa=$this->validapempresa('PAC_COMUN', 'CATALOGO', 'La gestión de pacientes es en la BD COMUN', '0');
        $data['chckPcComun'] = $pempresa->valor;


        //IMPRESORA
        $pempresa=$this->validapempresa('TIK_PRINTER', 'CATALOGO', 'Tipo Impresora para tickets', '');
        $data['impresora'] = $pempresa->valor;

        //FUENTE
        $pempresa=$this->validapempresa('TIK_FUENTE', 'CATALOGO', 'Letra de Ticket', '');
        $data['fuente'] = $pempresa->valor;

        //TAMAÑO
        $pempresa=$this->validapempresa('TIK_TAMAÑO', 'CATALOGO', 'Yamaño letra de Ticket', '');
        $data['tamanio'] = $pempresa->valor;

        //COLUMNA
        $pempresa=$this->validapempresa('TIK_COLUMNA', 'CATALOGO', 'Nro. de columnas del ticket', '');
        $data['columna'] = $pempresa->valor;

        //LINEA1
        $pempresa=$this->validapempresa('TIK_LINEA1', 'CATALOGO', 'Línea 1 de Ticket', '');
        $data['linea1'] = $pempresa->valor;
        //LINEA2
        $pempresa=$this->validapempresa('TIK_LINEA2', 'CATALOGO', 'Línea 2 de Ticket', '');
        $data['linea2'] = $pempresa->valor;
        //LINEA3
        $pempresa=$this->validapempresa('TIK_LINEA3', 'CATALOGO', 'Línea 3 de Ticket', '');
        $data['linea3'] = $pempresa->valor;
        //LINEA1
        $pempresa=$this->validapempresa('TIK_LINEA4', 'CATALOGO', 'Línea 4 de Ticket', '');
        $data['linea4'] = $pempresa->valor;
        //LINEA1
        $pempresa=$this->validapempresa('TIK_LINEA5', 'CATALOGO', 'Línea 5 de Ticket', '');
        $data['linea5'] = $pempresa->valor;
        //LINEA1
        $pempresa=$this->validapempresa('TIK_LINEA6', 'CATALOGO', 'Línea 6 de Ticket', '');
        $data['linea6'] = $pempresa->valor;
        //LINEA1
        $pempresa=$this->validapempresa('TIK_LINEA7', 'CATALOGO', 'Línea 7 de Ticket', '');
        $data['linea7'] = $pempresa->valor;

        //IMPRIMIR CODIGO DE PRODUCTO
        $pempresa=$this->validapempresa('TIK_IMPCOD', 'CATALOGO', 'Tickets: Imprimir el código del producto', '0');
        $data['codProducto'] = $pempresa->valor;

        //IMPRIMIR VENDEDOR
        $pempresa=$this->validapempresa('TIK_IMPVEN', 'CATALOGO', 'Tickets: Imprimir el vendedor', '0');
        $data['impVendedor'] = $pempresa->valor;


        $data['varconf'] = "maestros";

        return view('system_configuration.maestros', $data);
    }
    public function procesar_maestros(Request $request)
    {
        //dd($request->all());
        try{
            //PROCESA NOMENCLATURA FAMILIA
            $valor=$request->txt_nom_familia;
            $nombreParametro='FAM_NOMENC';
            $descripcion="Nomenclatura para las familias de productos";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //PROCESA NOMENCLATURA CENTRO COSTO
            $nomCcosto=$request->txt_nom_ccosto;
            $nombreParametro='NOM_CCOSTO';
            $descripcion="Nomenclatura para los centros de costo";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$nomCcosto,$descripcion,$tipo);

            //PROCESA NOMENCLATURA PARA EL PLAN DE CUENTAS
            $nomPcuentas=$request->txt_nom_pcuentas;
            $nombreParametro='NOM_CUENTA';
            $descripcion="Nomenclatura para el plan de cuentas";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$nomPcuentas,$descripcion,$tipo);

            //PROCESA COMBO CODIGO DE PRODUCTOS
            $cboCodigoGenera=$request->cboCodigoGenera;
            $nombreParametro='PRD_CODIGO';
            $descripcion="GENERACION DE CODIGO DEL PRODUCTO";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$cboCodigoGenera,$descripcion,$tipo);

            $LongMaxCodProducto=$request->txt_long_codigo;
            $nombreParametro='PRD_LONGIT';
            $descripcion="LONGITUD DE PRODUCTOS PARA CALCULAR CORRELATIVO";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$LongMaxCodProducto,$descripcion,$tipo);

            $GestionInpEmpresa=($request->chk_ind_empresa == 'on') ? 1 :0;
            //PROCESAR GESTION PRODUCTOS INDEPENDIENTE POR EMPRESA
            $nombreParametro='PDTOXEMPRE';
            $descripcion="La gestión de productos es independiente por empresa";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$GestionInpEmpresa,$descripcion,$tipo);

            //PROCESAR INTERFASE
            $cboInterfaFichas=$request->cboInterfacesFichas;
            $nombreParametro='PRD_WINDOW';
            $descripcion="Interface para visualizar las fichas o fórmulas";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$cboInterfaFichas,$descripcion,$tipo);

            //PROCESA BD COMUN
            $chkPacienteBdComun=($request->chk_bd_comun == 'on') ? 1 : 0;
            $nombreParametro='PAC_COMUN';
            $descripcion="La gestión de pacientes es en la BD COMUN";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$chkPacienteBdComun,$descripcion,$tipo);

            //PROCESA DATOS TICKETS

            $impresora=$request->txt_impresora_impr;
            $nombreParametro='TIK_PRINTER';
            $descripcion="Tipo Impresora para tickets";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$impresora,$descripcion,$tipo);

            $fuente=$request->txt_fuente_impr;
            $nombreParametro='TIK_FUENTE';
            $descripcion="Letra de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$fuente,$descripcion,$tipo);

            $tamanio=$request->txt_tamanio_impr;
            $nombreParametro='TIK_TAMAÑO';
            $descripcion="Tamaño letra de Tick";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$tamanio,$descripcion,$tipo);

            $columna=$request->txt_columna_impr;
            $nombreParametro='TIK_COLUMNA';
            $descripcion="Nro. de columnas del ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$columna,$descripcion,$tipo);

            $titulo1=$request->txt_titulo1_impr;
            $nombreParametro='TIK_LINEA1';
            $descripcion="Línea 1 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$titulo1,$descripcion,$tipo);

            $titulo2=$request->txt_titulo2_impr;
            $nombreParametro='TIK_LINEA2';
            $descripcion="Línea 2 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$titulo2,$descripcion,$tipo);

            $titulo3=$request->txt_titulo3_impr;
            $nombreParametro='TIK_LINEA3';
            $descripcion="Línea 3 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$titulo3,$descripcion,$tipo);

            $titulo4=$request->txt_titulo4_impr;
            $nombreParametro='TIK_LINEA4';
            $descripcion="Línea 4 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$titulo4,$descripcion,$tipo);

            $titulo5=$request->txt_titulo5_impr;
            $nombreParametro='TIK_LINEA5';
            $descripcion="Línea 5 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$titulo5,$descripcion,$tipo);

            $pie1=$request->txt_pie1;
            $nombreParametro='TIK_LINEA6';
            $descripcion="Línea 6 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$pie1,$descripcion,$tipo);

            $pie2=$request->txt_pie2;
            $nombreParametro='TIK_LINEA7';
            $descripcion="Línea 7 de Ticket";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$pie2,$descripcion,$tipo);

            $imp_codigo_producto=($request->chk_prim_pro == 'on') ? 1 : 0;
            $nombreParametro='TIK_IMPCOD';
            $descripcion="Tickets: Imprimir el código del producto";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$imp_codigo_producto,$descripcion,$tipo);

            $impr_vend=($request->chk_imp_vend == 'on') ? 1 : 0;
            $nombreParametro='TIK_IMPVEN';
            $descripcion="Tickets: Imprimir el vendedor";
            $tipo="CATALOGO";
            $result=$this->procesaPempresa($nombreParametro,$impr_vend,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function compras(Request $request){
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        $pempresa=$this->validapempresa('DOC_ANTXPAG', 'COMPRAS', 'Documento para los anticipos a proveedores', '');
        $data['antxpagar'] = $pempresa->valor;

        $pempresa=$this->validapempresa('DOC_DETRAC', 'COMPRAS', 'Documento para las detracciones', '');
        $data['docdetrac'] = $pempresa->valor;

        $pempresa=$this->validapempresa('DOC_RETENC', 'COMPRAS', 'Documento para los Comprobantes de Retención', '');
        $data['docreten'] = $pempresa->valor;

        $pempresa=$this->validapempresa('OPE_PAGOS', 'COMPRAS', 'Operación bancaria para pago a proveedores', '');
        $data['opepagos'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_EXCPDTO', 'COMPRAS', 'Exluir de las busquedas los productos terminados', '');
        $data['excpdto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FIJ_FECHAOC', 'COMPRAS', 'Fija el campo fecha en las Ordenes de Compra', '');
        $data['fijfechaoc'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_EXCANU', 'COMPRAS', 'Excluye los documentos anulados del Registro de Compras', '1');
        $data['excanul'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_ACTVTA', 'COMPRAS', 'Actualiza precio de venta desde las Ordenes de Compra', '0');
        $data['actpventa'] = $pempresa->valor;

        $pempresa=$this->validapempresa('RED_DETRXP', 'COMPRAS', 'Redondear cálculo detracción a (n) decimales', '0');
        $data['reddetrac'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SLD_RANGOP', 'COMPRAS', 'Rango días para reporte de saldos por pagar', '');
        $data['rangoxpag'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_CONFOS', 'COMPRAS', 'Se requiere confirmar Ordenes de Servicio', '0');
        $data['reqoserv'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_TIPOPESO', 'COMPRAS', 'La Orden de Compra solicita Tipo Peso', '0');
        $data['tipopeso'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_PRINTOC', 'COMPRAS', 'La Orden de Compra requiere aprobación para ser impresa', '0');
        $data['printocom'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_ITEMREPE', 'COMPRAS', 'No se permiten items repetidos en la orden de compra', '0');
        $data['itemrepecom'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_OCCPED', 'COMPRAS', 'Bloquear la OC cuando se jala Pedidos', '0');
        $data['occpedcom'] = $pempresa->valor;

        $pempresa=$this->validapempresa('COM_CONFGG', 'COMPRAS', 'Las Confirmaciones generar provisión de gasto', '0');
        $data['confggcom'] = $pempresa->valor;

        $data['documentotipo'] = Commercial::all()->where('estado', 1);

        $data['varconf'] = "compras";
        return view('system_configuration.compras',$data);
    }
    public function procesar_compras(Request $request)
    {
        try{
            $antxpagar = ($request->txt_anticipo_proveedor >= 1) ? $request->txt_anticipo_proveedor : NULL;
            $nombreParametro='DOC_ANTXPAG';
            $descripcion="Documento para los anticipos a proveedores";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$antxpagar,$descripcion,$tipo);

            $docdetrac = ($request->txt_documento_detraccion >= 1) ? $request->txt_documento_detraccion : NULL;
            $nombreParametro='DOC_DETRAC';
            $descripcion="Documento para las detracciones";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$docdetrac,$descripcion,$tipo);

            $docreten = ($request->txt_documento_retencion >= 1) ? $request->txt_documento_retencion : NULL;
            $nombreParametro='DOC_RETENC';
            $descripcion="Documento para los Comprobantes de Retención";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$docreten,$descripcion,$tipo);

            $opepagos = ($request->txt_operacion_id >= 1) ? $request->txt_operacion_id : NULL;
            $nombreParametro='OPE_PAGOS';
            $descripcion="Operación bancaria para pago a proveedores";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$opepagos,$descripcion,$tipo);

            $excpdto = ($request->chk_excluir_productos == 'on') ? 1 : 0;
            $nombreParametro='COM_EXCPDTO';
            $descripcion="Exluir de las busquedas los productos terminados";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$excpdto,$descripcion,$tipo);

            $fijfechaoc = ($request->chk_fijar_fecha == 'on') ? 1 : 0;
            $nombreParametro='FIJ_FECHAOC';
            $descripcion="Fija el campo fecha en las Ordenes de Compra";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$fijfechaoc,$descripcion,$tipo);

            $excanul = ($request->chk_excluir_anulados == 'on') ? 1 : 0;
            $nombreParametro='COM_EXCANU';
            $descripcion="Excluye los documentos anulados del Registro de Compras";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$excanul,$descripcion,$tipo);

            $actpventa = ($request->chk_actualizar_precios == 'on') ? 1 : 0;
            $nombreParametro='COM_ACTVTA';
            $descripcion="Actualiza precio de venta desde las Ordenes de Compra";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$actpventa,$descripcion,$tipo);

            $reddetrac=$request->txt_numero_decimales;
            $nombreParametro='RED_DETRXP';
            $descripcion="Redondear cálculo detracción a (n) decimales";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$reddetrac,$descripcion,$tipo);

            $rangoxpag=$request->txt_dias_reporte;
            $nombreParametro='SLD_RANGOP';
            $descripcion="Rango días para reporte de saldos por pagar";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$rangoxpag,$descripcion,$tipo);

            $reqoserv = ($request->chk_requiere_orden == 'on') ? 1 : 0;
            $nombreParametro='COM_CONFOS';
            $descripcion="Se requiere confirmar Ordenes de Servicio";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$reqoserv,$descripcion,$tipo);

            $tipopeso = ($request->chk_tipo_peso == 'on') ? 1 : 0;
            $nombreParametro='COM_TIPOPESO';
            $descripcion="La Orden de Compra solicita Tipo Peso";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$tipopeso,$descripcion,$tipo);

            $printocom = ($request->chk_aprueba == 'on') ? 1 : 0;
            $nombreParametro='COM_PRINTOC';
            $descripcion="La Orden de Compra requiere aprobación para ser impresa";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$printocom,$descripcion,$tipo);

            $itemrepecom = ($request->chk_itemrepecom == 'on') ? 1 : 0;
            $nombreParametro='COM_ITEMREPE';
            $descripcion="No se permiten items repetidos en la orden de compra";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$itemrepecom,$descripcion,$tipo);

            $occpedcom = ($request->chk_occpedcom == 'on') ? 1 : 0;
            $nombreParametro='COM_OCCPED';
            $descripcion="Bloquear la OC cuando se jala Pedidos";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$occpedcom,$descripcion,$tipo);

            $confggcom = ($request->chk_confggcom == 'on') ? 1 : 0;
            $nombreParametro='COM_CONFGG';
            $descripcion="Las Confirmaciones generar provisión de gasto";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$confggcom,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function ventas(Request $request){
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '',$this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        $pempresa=$this->validapempresa('FTCONNEGAT', 'VENTAS', 'Se permite valores negativos en la facturación', '0');
        $data['valneg'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FTCONUNICO', 'VENTAS', 'Sólo se permite una línea por producto en la facturación', '0');
        $data['unicopdto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FTCONSTOCK', 'VENTAS', 'Validar disponibilidad de stock en la facturación', '0');
        $data['constock'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_VENAUX', 'VENTAS', 'Llenado de ítems en ventana auxiliar', '0');
        $data['ventaux'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_TPRECIO', 'VENTAS', 'Mostrar Tipo Precio (Tarifa)', '0');
        $data['tipoprecio'] = $pempresa->valor;

        $pempresa=$this->validapempresa('RED_DETRXC', 'VENTAS', 'Redondear cálculo de detraccion a (n) decimales', '0');
        $data['reddetrac'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CON_IMPTOS', 'VENTAS', 'El precio de venta incluye impuestos', '0');
        $data['precioimp'] = $pempresa->valor;

        $pempresa=$this->validapempresa('PRD_UTILID', 'VENTAS', 'Margen utilidad(%)', '');
        $data['marutilidad'] = $pempresa->valor;

        $pempresa=$this->validapempresa('PRD_GTOADM', 'VENTAS', 'Gasto administrativo (%)', '');
        $data['gastoadm'] = $pempresa->valor;

        $pempresa=$this->validapempresa('PRD_TCAMBIO', 'VENTAS', 'Tipo de Cambio Precio venta', '');
        $data['tcambio'] = $pempresa->valor;

        $pempresa=$this->validapempresa('PRD_NEMPRE', 'VENTAS', 'Nemónico para el precio de venta', '');
        $data['nemprecio'] = $pempresa->valor;

        $pempresa=$this->validapempresa('EDT_PVENTA', 'VENTAS', 'El usuario puede cambiar precio de venta', '0');
        $data['preventa'] = $pempresa->valor;

        $pempresa=$this->validapempresa('PDTOXPUNTO', 'VENTAS', 'Los productos deben ser asignados a un punto de venta', '0');
        $data['pdtoxpunto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FECHAXPUNTO', 'VENTAS', 'Los productos deben ser asignados a un punto de venta', '0');
        $data['fechaxpunto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FTDIAXPUNTO', 'VENTAS', 'Sólo Facturar los pedidos del día', '0');
        $data['fxpunto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FTDOCXPUNTO', 'VENTAS', 'Validar Tipo de Documento por Punto de Venta', '0');
        $data['fdocxpunto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FTPEDGKAR', 'VENTAS', 'Generar Kardex al Aprobar Pedido de Venta', '0');
        $data['fpedkardex'] = $pempresa->valor;

        $pempresa=$this->validapempresa('MOV_VENTA', 'VENTAS', 'Tipo de movimiento de almacén para salida por ventas', '');
        $data['tmoventa'] = $pempresa->valor;

        $pempresa=$this->validapempresa('IMPTO_IGV', 'VENTAS', 'Impuesto que identifica al IGV', '');
        $data['impigv'] = $pempresa->valor;

        $pempresa=$this->validapempresa('IMPTO_CON', 'VENTAS', 'Impuesto que identifica al Consumo', '');
        $data['impcon'] = $pempresa->valor;

        $pempresa=$this->validapempresa('DOC_COMANDA', 'VENTAS', 'Tipo de Documento - Comandas', '');
        $data['docomanda'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_CTADSCT', 'VENTAS', 'Cuenta para Descuentos Concedidos a Terceros', '');
        $data['vendescter'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_CTADSCR', 'VENTAS', 'Cuenta para Descuentos Concedidos a Relacionadas', '');
        $data['vendescrel'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SLD_RANGOC', 'VENTAS', 'Rango días para reporte de saldos', '');
        $data['rangorep'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CTRLCREDITO', 'VENTAS', 'Activar control de créditos de venta', '0');
        $data['ctrlcred'] = $pempresa->valor;

        $pempresa=$this->validapempresa('BLOQPRINT', 'VENTAS', 'Bloquear impresión de documentos después de impresos', '0');
        $data['bloqprint'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VALIDA_RV', 'VENTAS', 'Validar Correlatividad en Registo de Ventas', '1');
        $data['valcorr'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_TARIFA', 'VENTAS', 'Tipo Tarifario', '0');
        $data['valtarifa'] = $pempresa->valor;

        $pempresa=$this->validapempresa('PRINTFTPED', 'VENTAS', 'Activar Impresión de Facturación de Pedidos', '0');
        $data['valprintftped'] = $pempresa->valor;

        $pempresa=$this->validapempresa('FTDIGCANT', 'VENTAS', 'Se debe digitar la cantidad para pasar al siguiente item', '0');
        $data['valftdigcant'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_CENDTO', 'VENTAS', 'Centralizar los descuentos concedidos', '0');
        $data['val_vta_cendto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_ANUBCO', 'VENTAS', 'Anular Mov.Caja al anular Venta (sólo contados)', '0');
        $data['val_vta_anubco'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SLD_RANGOC', 'VENTAS', 'Rango días para reporte de saldos', '');
        $data['val_sld_rangoc'] = $pempresa->valor;

        $pempresa=$this->validapempresa('VTA_CALCOM', 'VENTAS', 'Calcular Comisión de Venta por', '');
        $data['val_vta_calcom'] = $pempresa->valor;


        $data['tipomovimiento'] = MovementType::all()->where('estado', 1);
        $data['taxes'] = Taxes::all()->where('estado', 1);
        //$data['subdiaries'] = Subdiaries::all()->where('estado', 1);
        $data['tipo_operacion'] = OperationType::all()->where('estado', 1);
        $data['documento'] = Commercial::all()->where('estado', 1);
        $data['pcg'] = AccountingPlan::all()->where('estado', 1);

        $data['varconf'] = "ventas";
        return view('system_configuration.ventas', $data);
    }
    public function procesar_ventas(Request $request)
    {
        try{
            $valneg = ($request->chk_negativos == 'on') ? 1 : 0;
            $nombreParametro='FTCONNEGAT';
            $descripcion="Se permite valores negativos en la facturación";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valneg,$descripcion,$tipo);

            $unicopdto = ($request->chk_excluir_productos == 'on') ? 1 : 0;
            $nombreParametro='FTCONUNICO';
            $descripcion="Sólo se permite una línea por producto en la facturación";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$unicopdto,$descripcion,$tipo);

            $constock = ($request->chk_validad_stock == 'on') ? 1 : 0;
            $nombreParametro='FTCONSTOCK';
            $descripcion="Validar disponibilidad de stock en la facturación";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$constock,$descripcion,$tipo);

            $ventaux = ($request->chk_llenado_item == 'on') ? 1 : 0;
            $nombreParametro='VTA_VENAUX';
            $descripcion="Llenado de ítems en ventana auxiliar";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$ventaux,$descripcion,$tipo);

            $tipoprecio = ($request->chk_tipo_precio == 'on') ? 1 : 0;
            $nombreParametro='VTA_TPRECIO';
            $descripcion="Mostrar Tipo Precio (Tarifa)";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$tipoprecio,$descripcion,$tipo);

            $reddetrac=$request->txt_numero_decimales;
            $nombreParametro='RED_DETRXC';
            $descripcion="Redondear cálculo de detraccion a (n) decimales";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$reddetrac,$descripcion,$tipo);

            $precioimp = ($request->chk_incluye_igv == 'on') ? 1 : 0;
            $nombreParametro='CON_IMPTOS';
            $descripcion="El precio de venta incluye impuestos";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$precioimp,$descripcion,$tipo);

            $marutilidad=$request->txt_utilidad;
            $nombreParametro='PRD_UTILID';
            $descripcion="Margen utilidad(%)";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$marutilidad,$descripcion,$tipo);

            $gastoadm=$request->txt_gasto_administrativo;
            $nombreParametro='PRD_GTOADM';
            $descripcion="Gasto administrativo (%)";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$gastoadm,$descripcion,$tipo);

            $tcambio=$request->txt_tipo_gasto;
            $nombreParametro='PRD_TCAMBIO';
            $descripcion="Tipo de Cambio Precio venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$tcambio,$descripcion,$tipo);

            $nemprecio=$request->txt_nemonico;
            $nombreParametro='PRD_NEMPRE';
            $descripcion="Nemónico para el precio de venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$nemprecio,$descripcion,$tipo);

            $preventa=$request->cbo_cambia_precio;
            $nombreParametro='EDT_PVENTA';
            $descripcion="El usuario puede cambiar precio de venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$preventa,$descripcion,$tipo);

            $pdtoxpunto = ($request->chk_productos_xpunto == 'on') ? 1 : 0;
            $nombreParametro='PDTOXPUNTO';
            $descripcion="Los productos deben ser asignados a un punto de venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$pdtoxpunto,$descripcion,$tipo);

            $fechaxpunto = ($request->chk_fecha_productos == 'on') ? 1 : 0;
            $nombreParametro='FECHAXPUNTO';
            $descripcion="Los productos deben ser asignados a un punto de venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$fechaxpunto,$descripcion,$tipo);

            $fxpunto = ($request->chk_facturar_pedidos == 'on') ? 1 : 0;
            $nombreParametro='FTDIAXPUNTO';
            $descripcion="Sólo Facturar los pedidos del día";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$fxpunto,$descripcion,$tipo);

            $fdocxpunto = ($request->chk_valida_documento == 'on') ? 1 : 0;
            $nombreParametro='FTDOCXPUNTO';
            $descripcion="Validar Tipo de Documento por Punto de Venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$fdocxpunto,$descripcion,$tipo);

            $fpedkardex = ($request->chk_valida_documento == 'on') ? 1 : 0;
            $nombreParametro='FTPEDGKAR';
            $descripcion="Generar Kardex al Aprobar Pedido de Venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$fpedkardex,$descripcion,$tipo);

            $tmoventa = $request->txt_tmovimiento;
            $nombreParametro='MOV_VENTA';
            $descripcion="Tipo de movimiento de almacén para salida por ventas";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$tmoventa,$descripcion,$tipo);

            $impigv = $request->txt_impuesto_igv;
            $nombreParametro='IMPTO_IGV';
            $descripcion="Impuesto que identifica al IGV";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$impigv,$descripcion,$tipo);

            $impcon = $request->txt_impuesto_consumo;
            $nombreParametro='IMPTO_CON';
            $descripcion="Impuesto que identifica al Consumo";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$impcon,$descripcion,$tipo);

            $docomanda = $request->txt_tdocumento_comanda;
            $nombreParametro='DOC_COMANDA';
            $descripcion="Tipo de Documento - Comandas";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$docomanda,$descripcion,$tipo);

            $vendescter = $request->txt_cuenta_terceros;
            $nombreParametro='VTA_CTADSCT';
            $descripcion="Cuenta para Descuentos Concedidos a Terceros";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$vendescter,$descripcion,$tipo);

            $vendescrel = $request->txt_cuenta_relacionadas;
            $nombreParametro='VTA_CTADSCR';
            $descripcion="Cuenta para Descuentos Concedidos a Relacionadas";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$vendescrel,$descripcion,$tipo);

            $rangorep=$request->txt_rango_reporte;
            $nombreParametro='SLD_RANGOC';
            $descripcion="Rango días para reporte de saldos";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$rangorep,$descripcion,$tipo);

            $ctrlcred = ($request->chk_control_cred == 'on') ? 1 : 0;
            $nombreParametro='CTRLCREDITO';
            $descripcion="Activar control de créditos de venta";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$ctrlcred,$descripcion,$tipo);

            $bloqprint = ($request->chk_bloquea_print == 'on') ? 1 : 0;
            $nombreParametro='BLOQPRINT';
            $descripcion="Bloquear impresión de documentos después de impresos";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$bloqprint,$descripcion,$tipo);

            $valcorr = ($request->chk_val_correlativo == 'on') ? 1 : 0;
            $nombreParametro='VALIDA_RV';
            $descripcion="Validar Correlatividad en Registo de Ventas";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = $request->cbo_valtarifa;
            $nombreParametro='VTA_TARIFA';
            $descripcion="Tipo Tarifario";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = ($request->chk_printftped == 'on') ? 1 : 0;
            $nombreParametro='PRINTFTPED';
            $descripcion="Activar Impresión de Facturación de Pedidos";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = ($request->chk_ftdigcant == 'on') ? 1 : 0;
            $nombreParametro='FTDIGCANT';
            $descripcion="Se debe digitar la cantidad para pasar al siguiente item";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = ($request->chk_vta_cendto == 'on') ? 1 : 0;
            $nombreParametro='VTA_CENDTO';
            $descripcion="Centralizar los descuentos concedidos";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = ($request->chk_vta_anubco == 'on') ? 1 : 0;
            $nombreParametro='VTA_ANUBCO';
            $descripcion="Anular Mov.Caja al anular Venta (sólo contados)";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = $request->text_sld_rangoc;
            $nombreParametro='SLD_RANGOC';
            $descripcion="Rango días para reporte de saldos";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $valcorr = $request->cbo_vta_calcom;
            $nombreParametro='VTA_CALCOM';
            $descripcion="Calcular Comisión de Venta por";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valcorr,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    /*public function buscar_tipomovimiento(Request $request){
        $resultado = MovementType::select3();
        return $resultado;
    }

    public function buscar_taxes(Request $request){
        $resultado = Taxes::select3();
        return $resultado;
    }

    public function buscar_subdiaries(Request $request){
        $resultado = Subdiaries::select3();
        return $resultado;
    }

    public function buscar_tipooperacion(Request $request){
        $resultado = OperationType::select3();
        return $resultado;
    }

    public function buscar_commercial(Request $request){
        $resultado = Commercial::select3();
        return $resultado;
    }

    public function buscar_pcg(Request $request){
        $resultado = AccountingPlan::select3();
        return $resultado;
    }*/

    public function logistica(Request $request){
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        //TRANSFERENCIA AUTOMÁTICA
        $pempresa=validapempresa('ALM_TRANSF', 'ALMACEN', 'La transferencia entre almacenes es automática (un sólo paso)', '1');
        $data['alm_transf'] = $pempresa->valor;

        //INGRESOS EXIGEN ORDEN DE COMPRA
        $pempresa=validapempresa('ALM_PIDEOC', 'ALMACEN', 'Los ingresos por compra exigen Orden de Compra', '0');
        $data['alm_pideoc'] = $pempresa->valor;

        //USUARIOS REQUIEREN PERMISOS ESPECIALES
        $pempresa=validapempresa('ALM_PRIVIL', 'ALMACEN', 'Los usuarios requieren privilegios especiales para ver documentos de almacén', '0');
        $data['alm_privil'] = $pempresa->valor;

        //VER DATO ALMACEN
        $pempresa=validapempresa('ALM_VERDET', 'ALMACEN', 'Ver el dato [ALMACEN] en el detalle del documento', '0');
        $data['alm_verdet'] = $pempresa->valor;

        //VER MOV. ANULADO EN KARDEX
        $pempresa=validapempresa('ALM_VERANU', 'ALMACEN', 'Ver movimientos anulados en el kardex', '0');
        $data['alm_veranu'] = $pempresa->valor;

        //NO UNIDAD DE MEDIDA EN SALIDA
        $pempresa=validapempresa('ALM_SELEUM', 'ALMACEN', 'No se permite seleccionar unidad de medida en las salidas', '0');
        $data['alm_seleum'] = $pempresa->valor;

        // VALORIZAR POR ORDEN DE PRODUCCIÓN
        $pempresa=validapempresa('ALM_VALOROP', 'ALMACEN', 'Valorizar por orden de producción', '0');
        $data['alm_valorop'] = $pempresa->valor;

        // VER PESO EN KARDEX
        $pempresa=validapempresa('ALM_VERPESO', 'ALMACEN', 'Mostrar Peso en el Kardex', '0');
        $data['alm_verpeso'] = $pempresa->valor;

        // MODIFICAR GUIAS YA FACTURADAS
        $pempresa=validapempresa('ALM_EDTGUIA', 'ALMACEN', 'No se permite modificar las guías/salidas ya facturadas', '0');
        $data['alm_edtguia'] = $pempresa->valor;

        // MODIFICAR INGRESOS CON ORDEN COMPRA
        $pempresa=validapempresa('ALM_EDTIOC', 'ALMACEN', 'No se permite modificar los ingresos con Orden de Compra', '0');
        $data['alm_edtioc'] = $pempresa->valor;

        //EL CONTROL DE STOCK ES POR
        $pempresa=validapempresa('ALM_CSTOCK', 'ALMACEN', 'El control de los stocks de almacén es por', 'CODIGO');
        $data['alm_cstock'] = $pempresa->valor;

        //DOCUMENTO PARA PROVISION DE COMPRAS
        $pempresa=validapempresa('ALM_DOCUME', 'ALMACEN', 'Documento para provisión de compras', '');
        $data['alm_docume'] = $pempresa->valor;

        //DOCUMENTO PROVISION COMPRAS POR DEVOLUCION
        $pempresa=validapempresa('ALM_DOCSAL', 'ALMACEN', 'Documento para provisión de compras por devoluciones', '');
        $data['alm_docsal'] = $pempresa->valor;

        //TRANSACCION PARA PROVISION DE COMPRAS
        $pempresa=validapempresa('ALM_TIPTRA', 'ALMACEN', 'Tipo transacción para provisión de compras', '');
        $data['alm_tiptra'] = $pempresa->valor;

        //SUBDIARIO PARA INGRESOS
        $pempresa=validapempresa('SUB_INGALM', 'ALMACEN', 'Subdiario para ingresos de almacén', '');
        $data['sub_ingalm'] = $pempresa->valor;

        //SUBDIARIO PARA SALIDA
        $pempresa=validapempresa('SUB_SALALM', 'ALMACEN', 'Subdiario para salidas de almacén', '');
        $data['sub_salalm'] = $pempresa->valor;

        //CUENTA PARA INGRESOS
        $pempresa = validapempresa('ALM_CTADON', 'CATALOGO', 'Cuenta para los ingresos por donación', '');
        $data['alm_ctadon'] = $pempresa->valor;

        // MODIFICAR PROVEEDOR SOBRE LA FACTURA
        $pempresa=validapempresa('ALM_VERGUIA', 'ALMACEN', 'En el karded predomina la guía del proveedor sobre la factura', '0');
        $data['alm_verguia'] = $pempresa->valor;

        // MODIFICAR CANTIDAD DECIMALES EN REPORTES
        $pempresa=validapempresa('ALM_DECIMAL', 'ALMACEN', 'Mostrar cantidad de decimales en reportes', '0');
        $data['alm_decimal'] = $pempresa->valor;

        // MODIFICAR ITEMS REPETIDOS EN LA TRANSFERENCIA
        $pempresa=validapempresa('ALM_ITEMREPE', 'ALMACEN', 'No se permiten items repetidos en las transferencias', '0');
        $data['alm_itemrepe'] = $pempresa->valor;

        // MODIFICAR SALIDAS DE ALMACEN
        $pempresa=validapempresa('ALM_SALAPRO', 'ALMACEN', 'Las salidas de almacén requieren documento de referencia aprobado', '0');
        $data['alm_salapro'] = $pempresa->valor;

        // MODIFICAR ANULAR PEDIDO VENTA
        $pempresa=validapempresa('ALM_ANUPED', 'ALMACEN', 'Anular Pedido de Venta al anular el documento de venta', '0');
        $data['alm_anuped'] = $pempresa->valor;

        // MODIFICAR PARTES TRANSACCION
        $pempresa=validapempresa('ALM_PARTGCC', 'ALMACEN', 'Partes Transformación por Fichas (Tipo GCC)', '0');
        $data['alm_partgcc'] = $pempresa->valor;

        // MODIFICAR PARTES TRANSACCION
        $pempresa=validapempresa('ALM_VESTADO', 'ALMACEN', 'Activar control de estados en los ingresos a almacén', '0');
        $data['alm_vestado'] = $pempresa->valor;


        $data['transaccion'] = TransactionType::all()->where('estado', 1);
        $data['documento'] = Commercial::all()->where('estado', 1);
        $data['subdiaries'] = Subdiaries::all()->where('estado', 1);
        $data['pcg'] = AccountingPlan::all()->where('estado', 1);

        $data['varconf'] = "logistica";

        return view('system_configuration.logistica',$data);
    }
    public function procesar_logistica(Request $request)
    {
        try{
            $valor = ($request->rb_orden_obligatoria == 'on') ? 1 : 0;
            $nombreParametro='ALM_PIDEOC';
            $descripcion="Los ingresos por compra exigen Orden de Compra";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->txt_tipo_transaccion >= 1) ? $request->txt_tipo_transaccion : NULL;
            $nombreParametro='ALM_TIPTRA';
            $descripcion="Tipo transacción para provisión de compras";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->txt_documento_ingreso >= 1) ? $request->txt_documento_ingreso : NULL;
            $nombreParametro='ALM_DOCUME';
            $descripcion="Documento para provisión de compras";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->txt_documento_salida >= 1) ? $request->txt_documento_salida : NULL;
            $nombreParametro='ALM_DOCSAL';
            $descripcion="Documento para provisión de compras por devoluciones";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->txt_subdiario_ingreso >= 1) ? $request->txt_subdiario_ingreso : NULL;
            $nombreParametro='SUB_INGALM';
            $descripcion="Subdiario para ingresos de almacén";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->txt_subdiario_salida >= 1) ? $request->txt_subdiario_salida : NULL;
            $nombreParametro='SUB_SALALM';
            $descripcion="Subdiario para salidas de almacén";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->txt_cuenta_ingreso_donacion >= 1) ? $request->txt_cuenta_ingreso_donacion : NULL;
            $nombreParametro='ALM_CTADON';
            $descripcion="Cuenta para los ingresos por donación";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cbo_tipo_control;
            $nombreParametro='ALM_CSTOCK';
            $descripcion="El control de los stocks de almacén es por";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_almacen_control == 'on') ? 1 : 0;
            $nombreParametro='ALM_VERDET';
            $descripcion="Ver el dato [ALMACEN] en el detalle del documento";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_valorizacion_productos == 'on') ? 1 : 0;
            $nombreParametro='ALM_VALOROP';
            $descripcion="Valorizar por orden de producción";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_kardex_anulados == 'on') ? 1 : 0;
            $nombreParametro='ALM_VERANU';
            $descripcion="Ver movimientos anulados en el kardex";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_peso_kardex == 'on') ? 1 : 0;
            $nombreParametro='ALM_VERPESO';
            $descripcion="Mostrar Peso en el Kardex";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_transferencia_automatica == 'on') ? 1 : 0;
            $nombreParametro='ALM_TRANSF';
            $descripcion="La transferencia entre almacenes es automática (un sólo paso)";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_privilegios_especiales == 'on') ? 1 : 0;
            $nombreParametro='ALM_PRIVIL';
            $descripcion="Los usuarios requieren privilegios especiales para ver documentos de almacén";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_umedida == 'on') ? 1 : 0;
            $nombreParametro='ALM_SELEUM';
            $descripcion="No se permite seleccionar unidad de medida en las salidas";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_permite_modificar == 'on') ? 1 : 0;
            $nombreParametro='ALM_EDTIOC';
            $descripcion="No se permite modificar los ingresos con Orden de Compra";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_permite_modGuia == 'on') ? 1 : 0;
            $nombreParametro='ALM_EDTGUIA';
            $descripcion="No se permite modificar las guías/salidas ya facturadas";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rb_alm_verguia == 'on') ? 1 : 0;
            $nombreParametro='ALM_VERGUIA';
            $descripcion="En el karded predomina la guía del proveedor sobre la factura";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);


            $valor = $request->text_alm_decimal;
            $nombreParametro='ALM_DECIMAL';
            $descripcion="Mostrar cantidad de decimales en reportes";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rbo_alm_itemrepe == 'on') ? 1 : 0;
            $nombreParametro='ALM_ITEMREPE';
            $descripcion="No se permiten items repetidos en las transferencias";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rbo_alm_salapro == 'on') ? 1 : 0;
            $nombreParametro='ALM_SALAPRO';
            $descripcion="Las salidas de almacén requieren documento de referencia aprobado";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rbo_alm_anuped == 'on') ? 1 : 0;
            $nombreParametro='ALM_ANUPED';
            $descripcion="Anular Pedido de Venta al anular el documento de venta";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rbo_alm_partgcc == 'on') ? 1 : 0;
            $nombreParametro='ALM_PARTGCC';
            $descripcion="Partes Transformación por Fichas (Tipo GCC)";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->rbo_alm_vestado == 'on') ? 1 : 0;
            $nombreParametro='ALM_VESTADO';
            $descripcion="Activar control de estados en los ingresos a almacén";
            $tipo="ALMACEN";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function tesoreria(Request $request){
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $pempresa=$this->validapempresa('BCO_PAGOAP', 'BANCOS', 'Los pagos de documentos requieren aprobación', '');
        $data['banaprueba'] = $pempresa->valor;

        $pempresa=$this->validapempresa('BCO_TCCAJA', 'BANCOS', 'Tipo de cambio para los movimientos de caja', 'VENTA');
        $data['tcaja'] = $pempresa->valor;

        $pempresa=$this->validapempresa('BCO_TCBANC', 'BANCOS', 'Tipo de Cambio para los movimientos de bancos', 'VENTA');
        $data['tbanco'] = $pempresa->valor;

        $pempresa=$this->validapempresa('BCO_TRANMN', 'BANCOS', 'Cuenta M.N. de Tránsito para Transferencias', '');
        $data['ctaTransmn'] = $pempresa->valor;

        $pempresa=$this->validapempresa('BCO_TRANME', 'BANCOS', 'Cuenta M.E. de Tránsito para Transferencias', '');
        $data['ctaTransme'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SUB_INGBCO', 'BANCOS', 'Subdiario para ingresos de caja y bancos', '');
        $data['subing'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SUB_EGRBCO', 'BANCOS', 'Subdiario para egresos de caja y bancos', '');
        $data['subeg'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SUB_LTXCOB', 'BANCOS', 'Subdiario para Letras por Cobrar', '');
        $data['sublcobrar'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SUB_LTXPAG', 'BANCOS', 'Subdiario para Letras por Cobrar', '');
        $data['sublpagar'] = $pempresa->valor;

        $pempresa=$this->validapempresa('OPE_ANTXP', 'BANCOS', 'Operación para Anticipos de Proveedores', '');
        $data['opeprov'] = $pempresa->valor;

        $pempresa = $this->validapempresa('OPE_ANTXC', 'BANCOS', 'Operación para Anticipos de Clientes', '');
        $data['opecli'] = $pempresa->valor;

        $pempresa = $this->validapempresa('OPE_COBRO', 'VENTAS', 'Operación para cobranza', '');
        $data['opecobro'] = $pempresa->valor;

        $pempresa = $this->validapempresa('OPE_PAGOS', 'COMPRAS', 'Operación bancaria para pago a proveedores', '');
        $data['opepagos'] = $pempresa->valor;

        $pempresa = $this->validapempresa('BCO_PRGPAGO', 'BANCOS', 'La programación de pagos no es consolidada', '');
        $data['bco_prgpago'] = $pempresa->valor;

        $pempresa = $this->validapempresa('BCO_CHEQUE', 'BANCOS', 'Controlar numeración de cheques (bloquear correlativo)', '');
        $data['bco_cheque'] = $pempresa->valor;


        $data['pcg'] = AccountingPlan::all()->where('estado', 1);
        $data['subdiaries'] = Subdiaries::all()->where('estado', 1);
        $data['tipo_operacion_I'] = OperationType::listaIngresos()->where('estado', 1);
        $data['tipo_operacion_E'] = OperationType::listaEgresos()->where('estado', 1);

        $data['varconf'] = "tesoreria";
        return view('system_configuration.tesoreria',$data);
    }
    public function procesar_tesoreria(Request $request)
    {
        try{
            $banaprueba = ($request->chk_aprueba == 'on') ? 1 : 0;
            $nombreParametro='BCO_PAGOAP';
            $descripcion="Los pagos de documentos requieren aprobación";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$banaprueba,$descripcion,$tipo);

            $tcaja=$request->cbo_caja;
            $nombreParametro='BCO_TCCAJA';
            $descripcion="Tipo de cambio para los movimientos de caja";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$tcaja,$descripcion,$tipo);

            $tbanco=$request->cbo_banco;
            $nombreParametro='BCO_TCBANC';
            $descripcion="Tipo de Cambio para los movimientos de bancos";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$tbanco,$descripcion,$tipo);

            $ctaTransmn=$request->txt_cuentamn;
            $nombreParametro='BCO_TRANMN';
            $descripcion="Cuenta M.N. de Tránsito para Transferencias";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$ctaTransmn,$descripcion,$tipo);

            $ctaTransme=$request->txt_cuentame;
            $nombreParametro='BCO_TRANME';
            $descripcion="Cuenta M.E. de Tránsito para Transferencias";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$ctaTransme,$descripcion,$tipo);

            $subing=$request->txt_subingreso;
            $nombreParametro='SUB_INGBCO';
            $descripcion="Subdiario para ingresos de caja y bancos";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$subing,$descripcion,$tipo);

            $subeg=$request->txt_subegreso;
            $nombreParametro='SUB_EGRBCO';
            $descripcion="Subdiario para egresos de caja y bancos";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$subeg,$descripcion,$tipo);

            $sublcobrar=$request->txt_subxcobrar;
            $nombreParametro='SUB_LTXCOB';
            $descripcion="Subdiario para Letras por Cobrar";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$sublcobrar,$descripcion,$tipo);

            $sublpagar=$request->txt_subxpagar;
            $nombreParametro='SUB_LTXPAG';
            $descripcion="Subdiario para Letras por Cobrar";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$sublpagar,$descripcion,$tipo);

            $opeprov=$request->txt_anticipo_proveedor_tes;
            $nombreParametro='OPE_ANTXP';
            $descripcion="Operación para Anticipos de Proveedores";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$opeprov,$descripcion,$tipo);

            $opecli=$request->txt_anticipo_cliente_tes;
            $nombreParametro='OPE_ANTXC';
            $descripcion="Operación para Anticipos de Clientes";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$opecli,$descripcion,$tipo);

            $opecobro=$request->cbo_opecobro;
            $nombreParametro='OPE_COBRO';
            $descripcion="Operación para cobranza";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$opecobro,$descripcion,$tipo);

            $opepagos=$request->cbo_opepagos;
            $nombreParametro='OPE_PAGOS';
            $descripcion="Operación bancaria para pago a proveedores";
            $tipo="COMPRAS";
            $result=$this->procesaPempresa($nombreParametro,$opepagos,$descripcion,$tipo);

            $bcoprgpago=($request->chk_bcoprgpago == 'on') ? 1 : 0;
            $nombreParametro='BCO_PRGPAGO';
            $descripcion="La programación de pagos no es consolidada";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$bcoprgpago,$descripcion,$tipo);

            $bcocheque=($request->chk_bcocheque == 'on') ? 1 : 0;
            $nombreParametro='BCO_CHEQUE';
            $descripcion="Controlar numeración de cheques (bloquear correlativo)";
            $tipo="BANCOS";
            $result=$this->procesaPempresa($nombreParametro,$bcocheque,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function contable(Request $request){

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $pempresa=$this->validapempresa('PCG_TIPO', 'CONTAB', 'Plan de Cuentas usado por la empresa', '');
        $data['etiqueta'] = $pempresa->valor;

        $pempresa=$this->validapempresa('EEFF_TIPO', 'CONTAB', 'Tipo de Estado Financiero', '');
        $data['eeff_tipo'] = $pempresa->valor;

        $pempresa=$this->validapempresa('ACT_TCAMBIO', 'CONTAB', 'El tipo de cambio se registra', 'AUTOMATICO');
        $data['act_cambio'] = $pempresa->valor;

        $pempresa=$this->validapempresa('GXD_CUENTA', 'CONTAB', 'Cuenta (2 dígitos) para gastos por distribuir', '');
        $data['gxd_cuentadig'] = $pempresa->valor;

        $pempresa=$this->validapempresa('DIF_CTAGAN', 'CONTAB', 'Cuenta para Ganancia por Diferencia de Cambio', '');
        $data['gan_ctadif'] = $pempresa->valor;

        $pempresa=$this->validapempresa('DIF_CTAPER', 'CONTAB', 'Cuenta para Pérdida por Diferencia de Cambio', '');
        $data['per_ctadif'] = $pempresa->valor;

        $pempresa=$this->validapempresa('DIF_CCOSTO', 'CONTAB', 'Centro de Costo para Pérdida por Diferencia de Cambio', '');
        $data['dif_ccosto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('RED_CTAGAN', 'CONTAB', 'Cuenta para Ganancia por Redondeo', '');
        $data['gan_ctaredon'] = $pempresa->valor;

        $pempresa=$this->validapempresa('RED_CTAPER', 'CONTAB', 'Cuenta para Pérdida por Redondeo', '');
        $data['per_ctaredon'] = $pempresa->valor;

        $pempresa=$this->validapempresa('RED_CCOSTO', 'CONTAB', 'Centro de Costo para Redondeo', '');
        $data['redon_ccosto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CIE_CTAGAN', 'CONTAB', 'Cuenta para Ganancia por Cierre de Periodo', '');
        $data['cierre_ctagan'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CIE_CTAPER', 'CONTAB', 'Cuenta para Pérdida por Cierre de Periodo', '');
        $data['cierre_ctaper'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CON_PIDEOP', 'CONTAB', 'Rige la configuración de la cuenta PIDE_OP', '0');
        $data['rigeconfig'] = $pempresa->valor;

        $pempresa=$this->validapempresa('GXD_MONFUN', 'CONTAB', 'Distribuir gastos con moneda funcional', '0');
        $data['gxd_monfun'] = $pempresa->valor;

        $data['pcg'] = AccountingPlan::all()->where('estado', 1);
        $data['costo'] = Costs::all()->where('estado', 1);


        $data['varconf'] = "contabilidad";
        return view('system_configuration.contabilidad', $data);
    }
    public function procesar_contable(Request $request)
    {
        try {
            $etiqueta=$request->cbo_etiqueta;
            $nombreParametro='PCG_TIPO';
            $descripcion="Plan de Cuentas usado por la empresa";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$etiqueta,$descripcion,$tipo);

            $eeff_tipo=$request->cbo_eeff_tipo;
            $nombreParametro='EEFF_TIPO';
            $descripcion="Tipo de Estado Financiero";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$eeff_tipo,$descripcion,$tipo);

            $act_cambio=$request->cbo_tcambio;
            $nombreParametro='ACT_TCAMBIO';
            $descripcion="El tipo de cambio se registra', 'AUTOMATICO";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$act_cambio,$descripcion,$tipo);

            $gxd_cuentadig=$request->cbo_ctagasxdis;
            $nombreParametro='GXD_CUENTA';
            $descripcion="Cuenta (2 dígitos) para gastos por distribuir";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$gxd_cuentadig,$descripcion,$tipo);

            $gan_ctadif=$request->txt_cuentaganancia;
            $nombreParametro='DIF_CTAGAN';
            $descripcion="Cuenta para Ganancia por Diferencia de Cambio";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$gan_ctadif,$descripcion,$tipo);

            $per_ctadif=$request->txt_cuentaperdida;
            $nombreParametro='DIF_CTAPER';
            $descripcion="Cuenta para Pérdida por Diferencia de Cambio";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$per_ctadif,$descripcion,$tipo);

            $dif_ccosto=$request->txt_centrocosto;
            $nombreParametro='DIF_CCOSTO';
            $descripcion="Centro de Costo para Pérdida por Diferencia de Cambio";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$dif_ccosto,$descripcion,$tipo);

            $gan_ctaredon=$request->txt_cuentaganancia_redondeo;
            $nombreParametro='RED_CTAGAN';
            $descripcion="Cuenta para Ganancia por Redondeo";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$gan_ctaredon,$descripcion,$tipo);

            $per_ctaredon=$request->txt_cuentaperdida_redondeo;
            $nombreParametro='RED_CTAPER';
            $descripcion="Cuenta para Pérdida por Redondeo";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$per_ctaredon,$descripcion,$tipo);

            $redon_ccosto=$request->txt_centrocosto_redondeo;
            $nombreParametro='RED_CCOSTO';
            $descripcion="Centro de Costo para Redondeo";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$redon_ccosto,$descripcion,$tipo);

            $cierre_ctagan=$request->txt_cuentaganancia_cierre;
            $nombreParametro='CIE_CTAGAN';
            $descripcion="Cuenta para Ganancia por Cierre de Periodo";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$cierre_ctagan,$descripcion,$tipo);

            $cierre_ctaper=$request->txt_cuentaperdida_cierre;
            $nombreParametro='CIE_CTAPER';
            $descripcion="Cuenta para Pérdida por Cierre de Periodo";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$cierre_ctaper,$descripcion,$tipo);

            $rigeconfig=($request->chk_rige_configuracion == 'on') ? 1 : 0;
            $nombreParametro='CON_PIDEOP';
            $descripcion="Rige la configuración de la cuenta PIDE_OP";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$rigeconfig,$descripcion,$tipo);


            $gxd_monfun=($request->chk_gxd_monfun == 'on') ? 1 : 0;
            $nombreParametro='GXD_MONFUN';
            $descripcion="Distribuir gastos con moneda funcional";
            $tipo="CONTAB";
            $result=$this->procesaPempresa($nombreParametro,$gxd_monfun,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;

    }

    public function activos(Request $request){

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        //FORMA DE GENERAR CODIGO DE ACTIVO
        $pempresa=validapempresa('ACT_CODIGO', 'ACTIVO', 'Generación del Código del Activo', '');
        $data['act_codigo'] = $pempresa->valor;

        //LONGITUD PARA CODIGO DE ACTIVOS
        $pempresa=validapempresa('ACT_LONGIT', 'ACTIVO', 'Longitud máxima del código del activo', '');
        $data['act_longit'] = $pempresa->valor;

        //IDENTIFICACION PARA IDENTIFICAR EJERCICIO ANTERIROR
        $pempresa=validapempresa('ACT_EJEANT', 'ACTIVOS', 'Periodo que identifica al Ejercicio Anterior', '');
        $data['act_ejeant'] = $pempresa->valor;
        //$periodo_des=Periodo::filtroDescrip($act_ejeant);

        //SUBDIARIO PARA BAJA DE ACTIVOS
        $pempresa=validapempresa('SUB_BAJACT', 'ACTIVOS', 'Subdiario para baja de activos', '');
        $data['sub_bajact'] = $pempresa->valor;
        //$baja_des=Subdiario::filtroDescrip($sub_bajact);

        //SUBDIARIO PARA DEPRECIACION DE ACTIVOS
        $pempresa=validapempresa('SUB_DEPACT', 'ACTIVOS', 'Subdiario para depreciación de activos', '');
        $data['sub_depact'] = $pempresa->valor;
        //$depreciacion_des=Subdiario::filtroDescrip($sub_depact);

        //DEPRECIAR POR DIAS EN EL PERIODO INICIAL
        $pempresa=validapempresa('ACT_DPDIAS', 'ACTIVOS', 'Depreciar por días en el periodo inicial', '');
        $data['act_dpdias'] = $pempresa->valor;

        //existencia
        $pempresa=validapempresa('ACT_CATEXI', 'ACTIVO', 'Catálogo de Existencias', '');
        $data['act_catexi'] = $pempresa->valor;

        $data['periodo'] = Period::all()->where('estado', 1);
        $data['subdiaries'] = Subdiaries::all()->where('estado', 1);

        $data['varconf'] = "activos";
        return view('system_configuration.activos', $data);
    }
    public function procesar_activos(Request $request)
    {
        try{
            $valor=$request->cbo_genera_codigo;
            $nombreParametro='ACT_CODIGO';
            $descripcion="Generación del Código del Activo";
            $tipo="ACTIVO";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=$request->txt_longitud_codigo;
            $nombreParametro='ACT_LONGIT';
            $descripcion="Longitud máxima del código del activo";
            $tipo="ACTIVO";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=$request->txt_periodo_anterior;
            $nombreParametro='ACT_EJEANT';
            $descripcion="Periodo que identifica al Ejercicio Anterior";
            $tipo="ACTIVOS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=$request->txt_baja;
            $nombreParametro='SUB_BAJACT';
            $descripcion="Subdiario para baja de activos";
            $tipo="ACTIVOS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=$request->txt_depreciacion;
            $nombreParametro='SUB_DEPACT';
            $descripcion="Subdiario para depreciación de activos";
            $tipo="ACTIVOS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->rb_es_consignacion == 'on') ? 1 : 0;
            $nombreParametro='ACT_DPDIAS';
            $descripcion="Depreciar por días en el periodo inicial";
            $tipo="ACTIVOS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=$request->cbo_act_catexi;
            $nombreParametro='ACT_CATEXI';
            $descripcion="Catálogo de Existencias";
            $tipo="ACTIVO";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function planilla(Request $request){
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        //SUBDIARIES
        $pempresa=validapempresa('SUB_PLANILLA', 'PLANILLAS', 'Subdiario para los movimientos de planillas', '');
        $data['sub_planilla'] = $pempresa->valor;

        //VER MES
        $pempresa=validapempresa('PLA_VERMES', 'PLANILLAS', 'Mostrar sólo las planillas del mes en proceso', '0');
        $data['pla_vermes'] = $pempresa->valor;

        //CONFIRMACION DE ASISTENCIA
        $pempresa=validapempresa('PLA_CONASIST', 'PLANILLAS', 'Pedir confirmación de asistencia', '0');
        $data['pla_conasist'] = $pempresa->valor;

        //PARTE ASISTENCIA
        $pempresa=validapempresa('PLA_ASIXTIPO', 'PLANILLAS', 'El Parte de Asistencia es por tipo de planilla', '0');
        $data['pla_asixtipo'] = $pempresa->valor;

        //TAREO
        $pempresa=validapempresa('PLA_ASTAREO', 'PLANILLAS', 'El Tareo requiere asistencia del personal', '0');
        $data['pla_astareo'] = $pempresa->valor;

        //Horas extras
        $pempresa=validapempresa('PLA_AUTHEXT', 'PLANILLAS', 'Las horas extras requieren autorización', '0');
        $data['pla_authext'] = $pempresa->valor;

        //Aignar CTS
        $pempresa=validapempresa('PLA_GRACTS', 'PLANILLAS', 'Asignar 1/6 de Gratificación al cálculo de la Provisión de CTS', '0');
        $data['pla_gracts'] = $pempresa->valor;

        //Provisions Fijos
        $pempresa=validapempresa('PLA_PROVFIJO', 'PLANILLAS', 'Calcular Provisión sólo sobre los conceptos fijos', '0');
        $data['pla_provfijo'] = $pempresa->valor;

        //Meses cumputables
        $pempresa=validapempresa('PLA_GRACOM', 'PLANILLAS', 'Calcular meses completos para periodo computable', '0');
        $data['pla_gracom'] = $pempresa->valor;

        //Gractificacion periodo
        $pempresa=validapempresa('PLA_GRAMES', 'PLANILLAS', 'Calcular gratificación con periodo laborado menor a un mes', '0');
        $data['pla_grames'] = $pempresa->valor;

        //SERVIDOR
        $pempresa=validapempresa('PLA_ESERVIDOR', 'PLANILLAS', 'Planillas. Servidor Correo', '');
        $data['pla_servidor'] = $pempresa->valor;

        //PUERTO
        $pempresa=validapempresa('PLA_EPUERTO', 'PLANILLAS', 'Planillas. Puerto para Correo', '');
        $data['pla_puerto'] = $pempresa->valor;

        //USUARIO
        $pempresa=validapempresa('PLA_EUSUARIO', 'PLANILLAS', 'Planillasa. Usuario Correo', '');
        $data['pla_usuario'] = $pempresa->valor;

        //CLAVE
        $pempresa=validapempresa('PLA_ECLAVE', 'PLANILLAS', 'Planillas. Clave Correo', '');
        $data['pla_clave'] = $pempresa->valor;

        //COPIA A
        $pempresa=validapempresa('PLA_ECOPIA', 'PLANILLAS', 'Planillas. Copiar A', '');
        $data['pla_copiaa'] = $pempresa->valor;

        //PDF
        $pempresa=validapempresa('PLA_VERFIRMA', 'PLANILLAS', 'Mostrar la firma digital en el documento PDF', '0');
        $data['pla_verfirma'] = $pempresa->valor;

        //Horas ordinarias
        $pempresa=validapempresa('PLA_PLAMEHO', 'PLANILLAS', 'Usar para horas ordinarias', '');
        $data['pla_plameho'] = $pempresa->valor;

        $data['subdiaries'] = Subdiaries::all()->where('estado', 1);

        $data['varconf']="planilla";
        return view('system_configuration.planilla', $data);
    }
    public function procesar_planilla(Request $request)
    {
        try{
            $valor=$request->txt_subdiario_movpla;
            $nombreParametro='SUB_PLANILLA';
            $descripcion="Subdiario para los movimientos de planillas";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_planillas_proceso == 'on') ? 1 : 0;
            $nombreParametro='PLA_VERMES';
            $descripcion="Mostrar sólo las planillas del mes en proceso";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_confirmar_asistencia == 'on') ? 1 : 0;
            $nombreParametro='PLA_CONASIST';
            $descripcion="Pedir confirmación de asistencia";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_asistencia_tipopla == 'on') ? 1 : 0;
            $nombreParametro='PLA_ASIXTIPO';
            $descripcion="El Parte de Asistencia es por tipo de planilla";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_registro_tareo == 'on') ? 1 : 0;
            $nombreParametro='PLA_ASTAREO';
            $descripcion="El Tareo requiere asistencia del personal";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_horas_extras == 'on') ? 1 : 0;
            $nombreParametro='PLA_AUTHEXT';
            $descripcion="Las horas extras requieren autorización";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_calculo_prov == 'on') ? 1 : 0;
            $nombreParametro='PLA_GRACTS';
            $descripcion="Asignar 1/6 de Gratificación al cálculo de la Provisión de CTS";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_conceptos_prov == 'on') ? 1 : 0;
            $nombreParametro='PLA_PROVFIJO';
            $descripcion="Calcular Provisión sólo sobre los conceptos fijos";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_meses_gratificacion == 'on') ? 1 : 0;
            $nombreParametro='PLA_GRACOM';
            $descripcion="Calcular meses completos para periodo computable";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_gratificacion_mes == 'on') ? 1 : 0;
            $nombreParametro='PLA_GRAMES';
            $descripcion="Calcular gratificación con periodo laborado menor a un mes";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->txt_servidor_pla == '') ? '' : $request->txt_servidor_pla;
            $nombreParametro='PLA_ESERVIDOR';
            $descripcion="Planillas. Servidor Correo";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->txt_puerto_pla == '') ? '' : $request->txt_puerto_pla;
            $nombreParametro='PLA_EPUERTO';
            $descripcion="Planillas. Puerto para Correo";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->txt_usuario_pla == '') ? '' : $request->txt_usuario_pla;
            $nombreParametro='PLA_EUSUARIO';
            $descripcion="Planillasa. Usuario Correo";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->txt_clave_pla == '') ? '' : $request->txt_clave_pla;
            $nombreParametro='PLA_ECLAVE';
            $descripcion="Planillas. Clave Correo";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->txt_copia_pla == '') ? '' : $request->txt_copia_pla;
            $nombreParametro='PLA_ECOPIA';
            $descripcion="Planillas. Copiar A";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->chk_firma_digital == 'on') ? 1 : 0;
            $nombreParametro='PLA_VERFIRMA';
            $descripcion="Mostrar la firma digital en el documento PDF";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor=($request->txt_pla_plameho == '') ? '' : $request->txt_pla_plameho;
            $nombreParametro='PLA_PLAMEHO';
            $descripcion="Usar para horas ordinarias";
            $tipo="PLANILLAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function produccion(Request $request){

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        //NUMERACION MANUAL
        $pempresa=validapempresa('PRO_NUMEOP', 'PRODUCCION', 'La numeración de las órdenes es manual', '0');
        $data['pro_numeop'] = $pempresa->valor;

        //FORMULACION MANUAL
        $pempresa=validapempresa('PRO_GENPED', 'PRODUCCION', 'Los pedidos de las órdenes de producción se generan manualmente', '0');
        $data['pro_genped'] = $pempresa->valor;

        //PEDIDOS ORDENES
        $pempresa=validapempresa('PRO_GENFOR', 'PRODUCCION', 'La formulación de las órdenes de producción se generan manualmente', '0');
        $data['pro_genfor'] = $pempresa->valor;

        //PEDIDOS ACTIVIDAD
        //falta encontrar el campo

        //FACTURAR ORDENES
        $pempresa=validapempresa('PRO_FACTOP', 'PRODUCCION', 'Se puede facturar Ordenes sin cerrar', '0');
        $data['pro_factop'] = $pempresa->valor;

        //ASIGNAR GASTOS
        $pempresa=validapempresa('TRS_ORDFACT', 'TRANSPORTE', 'Se puede asignar gastos a las órdenes luego de cerrado o facturado', '0');
        $data['trs_ordfact'] = $pempresa->valor;

        //MOVIMIENTO PRODUCCION
        $pempresa=validapempresa('PRO_MOTIVO', 'PRODUCCION', 'Tipo de Movement para generar los pedidos', '');
        $data['pro_motivo'] = $pempresa->valor;

        //MOVIMIENTO VENTA
        $pempresa=validapempresa('VTA_MOTIVO', 'PRODUCCION', 'Tipo de Movement para generar los pedidos de venta', '');
        $data['vta_motivo'] = $pempresa->valor;

        //REGISTROS APLICACION
        $pempresa=validapempresa('AGR_PIDEOA', 'AGRICOLA', 'Los registros de aplicación exigen Orden de Aplicación', '0');
        $data['arg_pideoa'] = $pempresa->valor;

        //PRODUCTO AGUA
        $pempresa=validapempresa('AGR_PDTOAG', 'AGRICOLA', 'Producto para identificar al agua', '');
        $data['arg_pdtoag'] = $pempresa->valor;

        $data['tipo_movimiento'] = MovementType::all();
        $data['varconf']="produccion";
        return view('system_configuration.produccion', $data);
    }
    public function procesar_produccion(Request $request)
    {
        try{
            //NUMERACION MANUAL
            $valor = ($request->chk_pro_numeop == 'on') ? 1 : 0;
            $nombreParametro='PRO_NUMEOP';
            $descripcion="La numeración de las órdenes es manual";
            $tipo="PRODUCCION";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //FORMULACION MANUAL
            $valor = ($request->chk_pro_genped == 'on') ? 1 : 0;
            $nombreParametro='PRO_GENPED';
            $descripcion="Los pedidos de las órdenes de producción se generan manualmente";
            $tipo="PRODUCCION";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //PEDIDOS ORDENES
            $valor = ($request->chk_pro_genfor == 'on') ? 1 : 0;
            $nombreParametro='PRO_GENFOR';
            $descripcion="La formulación de las órdenes de producción se generan manualmente";
            $tipo="PRODUCCION";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);
            //PEDIDOS ACTIVIDAD
            //falta encontrar el campo

            //FACTURAR ORDENES
            $valor = ($request->chk_pro_factop == 'on') ? 1 : 0;
            $nombreParametro='PRO_FACTOP';
            $descripcion="Se puede facturar Ordenes sin cerrar";
            $tipo="PRODUCCION";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //ASIGNAR GASTOS
            $valor = ($request->chk_trs_ordfact == 'on') ? 1 : 0;
            $nombreParametro='TRS_ORDFACT';
            $descripcion="Se puede asignar gastos a las órdenes luego de cerrado o facturado";
            $tipo="TRANSPORTE";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //MOVIMIENTO PRODUCCION
            $valor = ($request->cbo_pro_motivo >= 1) ? $request->cbo_pro_motivo : null;
            $nombreParametro='PRO_MOTIVO';
            $descripcion="Tipo de Movement para generar los pedidos";
            $tipo="PRODUCCION";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //MOVIMIENTO VENTA
            $valor = ($request->cbo_vta_motivo >= 1) ? $request->cbo_vta_motivo : null;
            $nombreParametro='VTA_MOTIVO';
            $descripcion="Tipo de Movement para generar los pedidos de venta";
            $tipo="PRODUCCION";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //REGISTROS APLICACION
            $valor = ($request->chk_arg_pideoa == 'on') ? 1 : 0;
            $nombreParametro='AGR_PIDEOA';
            $descripcion="Los registros de aplicación exigen Orden de Aplicación";
            $tipo="AGRICOLA";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //PRODUCTO AGUA
            /*$valor = ($request->cbo_arg_pdtoag >= 1) ? $request->cbo_arg_pdtoag : null;
            $nombreParametro='AGR_PDTOAG';
            $descripcion="Producto para identificar al agua";
            $tipo="AGRICOLA";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);*/

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    public function cpe(Request $request){

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        //emisor
        $pempresa=$this->validapempresa('CPE_RUC', 'GENERAL', 'Factura Electrónica. RUC Emisor', '');
        $data['cpe_ruc'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_NOMBRE', 'GENERAL', 'Factura Electrónica. Razón Social Emisor', '');
        $data['cpe_nombre'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_DIRECCION', 'GENERAL', 'Factura Electrónica. Dirección Emisor', '');
        $data['cpe_direccion'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_UBIGEO', 'GENERAL', 'Factura Electrónica. Ubigeo Emisor', '');
        $data['cpe_ubigeo'] = Ubigeo::where('codigo', $pempresa->valor)->first();

        $pempresa=$this->validapempresa('CPE_CIUDAD', 'GENERAL', 'Factura Electrónica. Ciudad Emisor', '');
        $data['cpe_ciudad'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SOL_USUARIO', 'GENERAL', 'Factura Electrónica. Usuario SOL', '');
        $data['cpe_usuario'] = $pempresa->valor;

        $pempresa=$this->validapempresa('SOL_CLAVE', 'GENERAL', 'Factura Electrónica. Clave SOL', '');
        $data['cpe_clave'] = $pempresa->valor;

        //cpe_eclave ->email
        //digital

        $pempresa=$this->validapempresa('CER_RUC', 'GENERAL', 'Factura Electrónica. RUC Firma Digital', '');
        $data['cer_ruc'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CER_NOMBRE', 'GENERAL', 'Factura Electrónica. Razón Social Firma Digital', '');
        $data['cer_nombre'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CER_FILEPFX', 'GENERAL', 'Factura Electrónica. Archivo Certificado PFX', '');
        $data['cer_pfx'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CER_CLAVE', 'GENERAL', 'Factura Electrónica. Clave Certificado', '');
        $data['cer_clave'] = $pempresa->valor;

        //otros parametros

        $pempresa=$this->validapempresa('CPE_ACTIVAR', 'GENERAL', 'Factura Electrónica. Activar', '0');
        $data['cpe_activar'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_SERVIDOR', 'GENERAL', 'Factura Electrónica. Tipo Servidor', '0');
        $data['cpe_servidor'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_FTPSERV', 'GENERAL', 'Factura Electrónica. Servidor FTP', '');
        $data['cpe_ftpserv'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_REPOSIT', 'GENERAL', 'Factura Electrónica. Carpeta repositorio de archivos', '');
        $data['cpe_reposit'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_UBL', 'GENERAL', 'Facturación Electrónica. Versión UBL', '');
        $data['cpe_ubl'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_PLINEA', 'GENERAL', 'Factura Electrónica. Procesamiento en Línea', '0');
        $data['cpe_plinea'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_VERPDF', 'GENERAL', 'Factura Electrónica. Mostrar PDF', '0');
        $data['cpe_verpdf'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_VERMSG', 'GENERAL', 'Factura Electrónica. Ver Mensajes', '0');
        $data['cpe_vermsg'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_VALRUC', 'GENERAL', 'Facturación Electrónica. Validar RUC', '0');
        $data['cpe_valruc'] = $pempresa->valor;

        //correo eletcronico
        $pempresa=$this->validapempresa('CPE_ESERVIDOR', 'GENERAL', 'Factura Electrónica. Servidor Correo', '');
        $data['cpe_eservidor'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_EPUERTO', 'GENERAL', 'Factura Electrónica. Puerto para Correo', '');
        $data['cpe_epuerto'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_EUSUARIO', 'GENERAL', 'Factura Electrónica. Usuario Correo', '');
        $data['cpe_eusuario'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CPE_ECLAVE', 'GENERAL', 'Factura Electrónica. Clave Correo', '');
        $data['cpe_eclave'] = $pempresa->valor;

        ///

        $pempresa=$this->validapempresa('CXC_LIMCRE1', 'VENTAS', 'Límite de crédito otorgado a socios', '');
        $data['cxc_limcre1'] = $pempresa->valor;

        $pempresa=$this->validapempresa('CXC_INTERES', 'CTACTE', 'Tasa de Interés Mensual', '');
        $data['cxc_interes'] = $pempresa->valor;

        $data['varconf']="cpe";
        return view('system_configuration.cpe', $data);
    }

    public function procesar_cpe(Request $request)
    {
        try{
            $valor = $request->cpe_ruc;
            $nombreParametro='CPE_RUC';
            $descripcion="Factura Electrónica. RUC Emisor";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_nombre;
            $nombreParametro='CPE_NOMBRE';
            $descripcion="Factura Electrónica. Razón Social Emisor";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_direccion;
            $nombreParametro='CPE_DIRECCION';
            $descripcion="Factura Electrónica. Dirección Emisor";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);


            /*dd($request->cpe_ubigeo);
            $valor = $request->cpe_ubigeo;
            $nombreParametro='CPE_UBIGEO';
            $descripcion="Factura Electrónica. Ubigeo Emisor";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);*/

            $valor = $request->cpe_ciudad;
            $nombreParametro='CPE_CIUDAD';
            $descripcion="Factura Electrónica. Ciudad Emisor";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_usuario;
            $nombreParametro='SOL_USUARIO';
            $descripcion="Factura Electrónica. Usuario SOL";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_clave;
            $nombreParametro='SOL_CLAVE';
            $descripcion="Factura Electrónica. Clave SOL";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //digital
            $valor = $request->cer_ruc;
            $nombreParametro='CER_RUC';
            $descripcion="Factura Electrónica. RUC Firma Digital";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cer_nombre;
            $nombreParametro='CER_NOMBRE';
            $descripcion="Factura Electrónica. Razón Social Firma Digital";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            /*$valor = $request->cer_nombre;
            $nombreParametro='CER_NOMBRE';
            $descripcion="Factura Electrónica. Razón Social Firma Digital";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);*/

            $valor = $request->cer_clave;
            $nombreParametro='CER_CLAVE';
            $descripcion="Factura Electrónica. Clave Certificado";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //otro parametros
            $valor = ($request->cpe_activar == 'on') ? 1 : 0;
            $nombreParametro='CPE_ACTIVAR';
            $descripcion="Factura Electrónica. Activar";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_servidor;
            $nombreParametro='CPE_SERVIDOR';
            $descripcion="Factura Electrónica. Tipo Servidor";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_ftpserv;
            $nombreParametro='CPE_FTPSERV';
            $descripcion="Factura Electrónica. Servidor FTP";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            /*$valor = $request->cer_nombre;
            $nombreParametro='CER_NOMBRE';
            $descripcion="Factura Electrónica. Razón Social Firma Digital";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);*/

            $valor = $request->cpe_ubl;
            $nombreParametro='CPE_UBL';
            $descripcion="Facturación Electrónica. Versión UBL";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->cpe_plinea == 'on') ? 1 : 0;
            $nombreParametro='CPE_PLINEA';
            $descripcion="Factura Electrónica. Procesamiento en Línea";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->cpe_verpdf == 'on') ? 1 : 0;
            $nombreParametro='CPE_VERPDF';
            $descripcion="Factura Electrónica. Mostrar PDF";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->cpe_vermsg == 'on') ? 1 : 0;
            $nombreParametro='CPE_VERMSG';
            $descripcion="Factura Electrónica. Ver Mensajes";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = ($request->cpe_valruc == 'on') ? 1 : 0;
            $nombreParametro='CPE_VALRUC';
            $descripcion="Facturación Electrónica. Validar RUC";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //correo electronico
            $valor = $request->cpe_eservidor;
            $nombreParametro='CPE_ESERVIDOR';
            $descripcion="Factura Electrónica. Servidor Correo";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_epuerto;
            $nombreParametro='CPE_EPUERTO';
            $descripcion="Factura Electrónica. Puerto para Correo";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_eusuario;
            $nombreParametro='CPE_EUSUARIO';
            $descripcion="Factura Electrónica. Usuario Correo";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cpe_eclave;
            $nombreParametro='CPE_ECLAVE';
            $descripcion="Factura Electrónica. Clave Correo";
            $tipo="GENERAL";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            //
            $valor = $request->cxc_limcre1;
            $nombreParametro='CXC_LIMCRE1';
            $descripcion="Límite de crédito otorgado a socios";
            $tipo="VENTAS";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $valor = $request->cxc_interes;
            $nombreParametro='CXC_INTERES';
            $descripcion="Tasa de Interés Mensual";
            $tipo="CTACTE";
            $result=$this->procesaPempresa($nombreParametro,$valor,$descripcion,$tipo);

            $r["mensaje"] = "Grabado Correctamente";
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
        return $r;
    }

    //falta procesar CPE

    public function transporte(Request $request){

        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Configuración', ' del ', 'sistema', '');
        $data['header'] = headeroptions($this->var, '', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        $data['varconf']="transporte";
        return view('system_configuration.transporte', $data);
    }

    //falta procesar Transporte

    public function validapempresa($nombreParametro,$tipo,$descripcion,$valor){
        $pempresa=Pempresa::where('parametro','=',$nombreParametro)->first();
        if($pempresa){

        }
        else{
            $pempresa=new Pempresa();
            $pempresa->parametro=$nombreParametro;
            $pempresa->valor=$valor;
            $pempresa->descripcion=$descripcion;
            $pempresa->tipo=$tipo;
            $pempresa->usuario='';
            $pempresa->save();
            $pempresa=Pempresa::where('parametro','=',$nombreParametro)->first();
        }
        return $pempresa;
    }

    private function obtenerUsuario(){
        if(Auth::guest())
        {
            $usuario='';
        }
        else
        {
            $usuario=Auth::user()->usuario;
        }
        return $usuario;
    }

    private function procesaPempresa($nombreParametro,$valor,$descripcion,$tipo)
    {
        try{
            $usuario=$this->obtenerUsuario();
            $pempresa=new Pempresa();
            $result=Pempresa::existe($nombreParametro);
            if($result->coincidencias==0){
                //"no existe";
                $pempresa->parametro=$nombreParametro;
                $pempresa->descripcion=$descripcion;
                $pempresa->valor=trim(strtoupper($valor));
                $pempresa->tipo=$tipo;
                $pempresa->usuario=$usuario;
                $pempresa->save();
            }
            else{
                //"existe";
                Pempresa::actualizar($nombreParametro,trim(strtoupper($valor)),$usuario);
            }
        }catch (PDOException $e){

        }
        return true;
    }

    public function buscar_ubigeo(Request $request){
        $resultado = Ubigeo::select2($request->term);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
