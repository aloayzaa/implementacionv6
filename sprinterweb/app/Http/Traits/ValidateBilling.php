<?php
namespace App\Http\Traits;

use App\AffectsIgv\Entities\AffectIgv;
use App\SalesTypes\Entities\SaleType;
use App\Taxes\Entities\Taxes;
use Illuminate\Http\Request;
use App\Documents\Entities\Commercial;
use App\Products\Entities\Productos;
use App\Currency\Entities\Currency;
use App\TypeNote\Entities\TypeCreditNote;
use App\TypeNote\Entities\TypeDebitNote;
use App\DetractionType\Entities\DetractionType;
use App\PaymentCondition\Entities\PaymentCondition;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Customers\Entities\Customer;
use App\PointSale\Entities\PointSaleSeries;
use App\Period\Entities\Period;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use DateTime;
use App\Http\Traits\Test;
use Illuminate\Support\Facades\Session;
use App\Reference\Entities\Reference;
use App\WorkOrder\Entities\WorkOrder;
use App\Quotation\Entities\Quotation;
use Illuminate\Support\Facades\DB;
use App\DocComunicacion\DocComunicacionDetalle;
use App\Panel\Companies\Entities\Pempresa;

trait ValidateBilling{

    use Test;

    protected function sumar($request){  // objeto: MOV_FACTURAVENTA, método: sumar

        // siempre va sumar el docxpagar_detalle

        $tipo_venta = SaleType::select('esgratuito', 'noafecto')->where('id', $request->tipo_venta)->first();
        
        $igv = Taxes::select('codigo','tipocalculo','valor')->where('id', $request->igv)->first();

        $tbase = $tinafecto = $tisc = $ticbper = $tdescuento = $tgratuito = $ttotal = 0; // totales

        $impuesto_igv = ( $igv->tipocalculo == 'P' ) ? $igv->valor / 100 : 0;
        
        $docxpagar_detalles = $this->detailBillingController->obtener_docxpagar_detalle();

        foreach ( $docxpagar_detalles as $cart ) {

            $base = $inafecto = $isc = $icbper = $gratuito = $descuento = $anticipo = $bonifica = $total = 0;

            // agregar la condición tipoprecio en caso sea cero debe ser 1, ver clasic
            $inafecto_temporal = false;

            $tipoafectaigv = AffectIgv::select('codigo')->where('id', $cart->options->tipoafectaigv_id)->first();
            $tipoafectaigv_codigo = $tipoafectaigv->codigo;

            $referencia_id = $cart->options->referencia_id;

            if ( !empty($referencia_id) &&  $cart->options->esnuevo == 0){
                
                if( $cart->options->importe != 0) {

                    if ( substr($cart->options->referencia_codigo, 0, 3) == '07 ' ){

                        // nota de crédito
                        $total = $cart->options->importe;

                    }else{

                        // Anticipos
                        if ( in_array($tipoafectaigv_codigo, array('20', '21', '30', '31', '32', '33', '34', '35', '36')) ){

                            $total = $cart->options->importe;
                            $inafecto_temporal = true;

                        }else{

                            $total = formatear_numero(formatear_numero($cart->options->importe * (1 + $impuesto_igv), 3), 2);

                        }

                    }
                    $totalref = abs($total);
                    if ( ceil($totalref) - $totalref == 0.01 || $totalref - intval($totalref) == 0.01 ){

                        if ( $totalref - intval($totalref) == 0.01 ){

                            $totalref = $totalref - 0.01;

                        }else{

                            $totalref = $totalref + 0.01;

                        }

                        $total = ( $total < 0) ? $totalref * -1 : $totalref;

                    }

                }

            }else{
                
                if ( $cart->options->esafecto == 0 || $tipo_venta->noafecto == 1 || in_array($tipoafectaigv_codigo, array('20', '21', '30', '31', '32', '33', '34', '35', '36')) ) {
                    
                    $inafecto_temporal = true;

                    if ( $cart->options->importe > 0) {

                        $inafecto = $cart->options->importe; // actualizar inafecto

                        if ( $cart->options->preciolista > $cart->options->precio ) {

                            // actualizar descuento
                            $descuento = ( $cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4) ?  ( ( $cart->options->preciolista - $cart->options->precio ) * $cart->options->cantidad ) : ( ( $cart->options->preciolista - $cart->options->precio ) *  $cart->options->peso );

                        }

                    } else {
                        $bonifica = $cart->options->importe;  // ver donde se guarda
                    }

                    if ( $tipo_venta->esgratuito ==  1 ) {

                        if ( $cart->options->precio == 0) {
                            // actualizar gratuito
                            $gratuito = ( $cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4 ) ? $cart->options->preciolista * $cart->options->cantidad : $cart->options->preciolista * $cart->options->peso;
                        } else {
                            // actualizar gratuito
                            $gratuito = ( $cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4 ) ? $cart->options->precio * $cart->options->cantidad : $cart->options->precio * $cart->options->peso;
                        }
                    }

                    $total = $cart->options->importe; // actualizar total

                } else {

                    if ( $cart->options->importe >= 0) {

                        $base = $cart->options->importe; // actualizar base
                        
                        if ( $cart->options->preciolista > $cart->options->precio ) {

                            //actualizar descuento
                            $descuento = ( $cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4 ) ? ( ( $cart->options->preciolista - $cart->options->precio ) * $cart->options->cantidad ) : ( ( $cart->options->preciolista - $cart->options->precio ) * $cart->options->peso );

                        }

                    } else {

                        $bonifica = $cart->options->importe; // actualizar bonifica
                    }
                    
                    if ( $tipo_venta->esgratuito == 1 ) {

                        if ( $cart->options->precio == 0 ) {

                            // actualizar gratuito
                            $gratuito = ( $cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4 ) ? $cart->options->preciolista * $cart->options->cantidad : $cart->options->preciolista * $cart->options->peso;

                        } else {

                            // actualizar gratuito
                            $gratuito = ( $cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4 ) ? $cart->options->precio * $cart->options->cantidad : $cart->options->precio * $cart->options->peso;

                        }

                    }

                    $total = $cart->options->importe; // actualizar importe

                    if ( $this->lconimptos == 1 ) {

                        $base = $base / ( 1 + $impuesto_igv ); // actualizar base
                        $bonifica = $bonifica / ( 1 + $impuesto_igv ); // actualizar boifica
                        $descuento = $descuento / ( 1 + $impuesto_igv ); // actualizar descuento

                    } else {

                        $total = $total * ( 1 + $impuesto_igv); // actualizar total
                        $gratuito = $gratuito * ( 1 + $impuesto_igv ); // actualizar gratuito

                    }

                }

            } // fin condición si existe o no referencia
            
            if ( !$inafecto_temporal ) {
                $tbase += $base + $anticipo + $descuento; // total base
                
            } else {
                $tinafecto += $inafecto + $anticipo + $descuento;// total inafecto
            }

            $tisc += $isc; // total isc
            $ticbper += $icbper; // total icbper
            $tdescuento += $descuento + $bonifica; // total descuento
            $tgratuito += $gratuito; // total gratuito
            $ttotal += $total + $isc + $icbper; // total total

        } // fin foreach

        $tdescuento = $tdescuento * -1;
        
        if ( $tipo_venta->noafecto == 1) {

            $tinafecto = ( $tbase != 0 ) ? $tbase : $tinafecto; // total inafecto
            $tbase = 0; // total base
            $igv = 0;
            $tisc = 0; // total isc

        } else {
            
            if ( entre_intervalo($tbase, -0.02, 0.02) ) {

                $tbase = 0; // total base
                $igv = 0;
                $tisc = 0; // total isc
                $ticbper = 0; // total icbper

            } else {

                $igv = ($tbase + $tdescuento) * $impuesto_igv; // igv

            }

            if ( entre_intervalo($tinafecto, -0.02, 0.02) ) {

                $tinafecto = 0;

            }

        }

		$tisc_icbper = $tisc + $ticbper;

        if ( $ttotal != $tbase + $tinafecto + $tdescuento + $tisc_icbper + $igv ) {

            $tbase = ( $tbase != 0 ) ? $tbase + ( $ttotal - ( $tbase + $tinafecto + $tdescuento + $tisc_icbper + $igv ) ) : $tbase;

        }

        // actualizar el isc por $tisc y icbper por $ticbper - clasic
        session()->put('tisc', $tisc);
        session()->put('ticbper', $ticbper);

        if ( $tbase == 0 && $tinafecto == 0 && $igv == 0 && $tdescuento == 0 && $tgratuito == 0) {

            $ttotal = 0;

        }

        $importe = (empty($request->importe)) ? 0 : $request->importe;
        $importe = ( $importe != 0 && $importe != $ttotal) ? $ttotal : $importe;
        
        $data['tbase'] = formatear_numero($tbase, 2); // total base - txtbase
        $data['tinafecto'] = formatear_numero($tinafecto, 2); // total inafecto - txtinafecto
        $data['tdescuento'] = formatear_numero($tdescuento, 2); // total descuento - txtdescuento
        $data['tisc_icbper'] = formatear_numero($tisc_icbper, 2); // total isc - txtisc
        $data['igv'] = formatear_numero($igv, 2); // total igv - txtimpuesto
        $data['tgratuito'] = formatear_numero($tgratuito, 2); // total gratuito - txtgratuito
        $data['ttotal'] = formatear_numero($ttotal, 2); // total total - txttotal
        $data['importe'] = formatear_numero($importe, 2); // txtimporte

        return $data;

    }

    protected function valida_tipoventa(Request $request){ // clasic objeto : txtcodigo(tipoventa), método : Valid

        $tipoventa = SaleType::findOrFail($request->tipo_venta);
        $tipo_afectacion_igv = AffectIgv::findOrFail($request->tipo_afectacion_igv);

        // NULL = NO ALTERAR EN EL FORMULARIO

        $data['impuesto_id'] = null; 
        $data['tipo_afectacion_igv'] = null; 
        $data['crear_kardex_checked'] = null; 
        $data['glosa'] = null; 

        if ( $tipoventa->esexporta == 1 ){

            $data['tipo_afectacion_igv'] = 16;

        } else {

            if ( $tipoventa->afectoivap == 1 ) {

                $data['tipo_afectacion_igv'] = 18;

            } else {

                if ( $tipoventa->esgratuito == 1 ){

                    if ($tipoventa->noafecto == 0) {

                        // m.impuesto_id = thisform.nigv // preguntar, objeto: cboimpuesto, método: Valid (thisform.nigv) ahí se asigna, tengo que ver donde se reasigna para descomentar

                        if ( !entre_intervalo($tipo_afectacion_igv->codigo, 11, 16)){

                            $data['tipo_afectacion_igv'] = 4;

                        }

                    } else {

                        $impuesto = Taxes::select('*')->where('codigo', '99')->first();
                        $data['impuesto_id'] = $impuesto->id;

                        if ( !in_array($tipo_afectacion_igv->codigo, array('31','32','33','34','35','36','21')) ) {

                            $data['tipo_afectacion_igv'] = 17;
                        }

                    }

                } else {

                    if ( $tipoventa->noafecto == 0 ) {

                        // m.impuesto_id = thisform.nigv // preguntar, objeto: cboimpuesto, método: Valid (thisform.nigv) ahí se asigna, tengo que ver donde se reasigna para descomentar

                        if ( !in_array($tipo_afectacion_igv->codigo, array('10', '11','12','13','14','15','16')) ) {

                            $data['tipo_afectacion_igv'] = 1;
                        }

                    } else {

                        $impuesto = Taxes::select('*')->where('codigo', '99')->first();
                        $data['impuesto_id'] = $impuesto->id;

                        if ( !in_array($tipo_afectacion_igv->codigo, array('20','21','31','32','33','34','35','36')) ) {

                            $data['tipo_afectacion_igv'] = 9;

                        }

                    }

                }

            }

        }

        $data['crear_kardex_checked'] =  ( $tipoventa->conkardex == 0) ? false : true;

        $data['glosa'] = $tipoventa->descripcion;


        // thisform.edtobservacion.Refresh // clasic objeto padre: cnttipoventa1, objeto hijo: txtcodigo, método Valid preguntar por esta línea
        
        return $data;
    }

    public function validar_serie_documento(Request $request){ // Clasic, objeto: txtserie, método: Valid

        $tipo_doc = Commercial::select('codigo')->where('id', $request->tipo_doc)->first();
        $serie = $request->serie;
        $numero = $request->numero_doc;

        $primer_valor_serie = substr($request->serie, 0, 1);
        $segundo_valor_serie = substr($request->serie, 1, 1);

        if ( $this->lcpe == 1 ) { // si esta activada la FE

            if ( ctype_alpha($primer_valor_serie) ) { // el primer valor de la serie es un valor alfabético ?

                if ( strtoupper($primer_valor_serie) == 'E'){

                    if ( ctype_alpha($segundo_valor_serie) ) { // el segundo valor es un alfabético ?

                        $serie_numero = substr($request->serie, 2, 2); // a partir del tercer valor, dos valores
                        // toma el primer y segundo caracter de la serie(en este caso alfabéticos) y los convierte a mayuscula, concatenado con serie_numero rellenado con dos ceros a la izquierda
                        $serie = strtoupper(substr($request->serie, 0, 2)) . str_pad($serie_numero, 2, "0", STR_PAD_LEFT);

                    }else {

                        $serie_numero = substr($request->serie, 1, 3);
                        $serie = "E" . str_pad($serie_numero, 3, "0", STR_PAD_LEFT);

                    }

                } else {

                    if ( ctype_alpha($segundo_valor_serie) ) { // el segundo valor es un alfabético ?

                        $serie_numero = substr($request->serie, 2, 2);
                        $serie = strtoupper(substr($request->serie, 0, 2)) . str_pad($serie_numero, 2, "0", STR_PAD_LEFT);

                    }else {

                        $serie_numero = substr($request->serie, 1, 3);

                        $serie = strtoupper(substr($request->serie, 0, 1)) . str_pad($serie_numero, 3, "0", STR_PAD_LEFT);

                    }

                    switch(true){

                        case ( $tipo_doc->codigo == "01" || $tipo_doc->codigo == "FT" ) :

                            $serie = "F" . substr($serie, -3);

                        break;

                        case ( $tipo_doc->codigo == "03" || $tipo_doc->codigo == "BV" ):

                            $serie = "B" . substr($serie, -3);

                        break;

                        case ( in_array(trim($tipo_doc->codigo), array('07', '08', '97', '98', 'ND', 'NC')) ):

                            $serie = strtoupper(substr($serie, 0, 1)) . substr($serie, -3);

                        break;

                        default:

                            $serie = str_pad($serie_numero, 4, "0", STR_PAD_LEFT);

                        break;
                    }

                }

            } else {

                $serie_numero = $request->serie;
                $serie = str_pad($serie_numero, 4, "0", STR_PAD_LEFT);

            }

        } else {

            if ( strtoupper($primer_valor_serie) == "E" ) {

                if ( ctype_alpha($segundo_valor_serie) ){ // el segundo valor es alfabético ?

                    $serie_numero = substr($request->serie, 2, 2);
                    $serie = strtoupper(substr($request->serie, 0, 2)) . str_pad($serie_numero, 2, "0", STR_PAD_LEFT);

                } else {

                    $serie_numero = substr($request->serie, 1, 3);
                    $serie = "E" . str_pad($serie_numero, 3, "0", STR_PAD_LEFT);
                }

            } else {

                if ( strlen($request->serie) < 4) {

                    $serie = str_pad(trim($request->serie), 4, "0", STR_PAD_LEFT);

                }

            }


        }

        // solo en la acción crear
        if ( $request->id == 0) {
            $numero = $this->numero_doc($request->tipo_doc, $serie);  // regresa el numero_doc(único valor)
        }

        // verificar si el documento existe documento(tipo_doc, serie, numero), regresará el siguiente mensaje : El documento ya está registrado.
        $t_validadocumento = $this->t_validadocumento($request->tercero, $request->tipo_doc, $serie, $numero, $request->id); // solo regresa un mensaje

        if ( !empty($numero) && !empty($request->fecha_proceso) && $request->id == 0) {  // solo en la acción crear

            $t_validafecha = $this->t_validafecha($request->tipo_doc, $serie, $numero, $request->fecha_proceso);

        }


        $r['serie'] = $serie;
        $r['numero'] = $numero;
        $r['validadocumento'] = $t_validadocumento;
        $r['validafecha'] = (isset($t_validafecha)) ? $t_validafecha : null;

        return $r;
    }
    
    protected function obtener_serie_doc($documentocom, $serie){
        if ($this->lcpe == 1){
            if ($documentocom->codigo == '03' || $documentocom->codigo == 'BV'){
                $serie_doc = 'B' . str_pad($serie, 3,'0', STR_PAD_LEFT);
            }elseif (in_array($documentocom->codigo, array('01', '07', '08', '97', '98', 'FT', 'NC', 'ND'))){
                $serie_doc = 'F' . str_pad($serie, 3, '0', STR_PAD_LEFT);
            }else{
                $serie_doc = str_pad($serie, 4, '0', STR_PAD_LEFT);
            }
        }else{
            $serie_doc = str_pad($serie, 4, '0', STR_PAD_LEFT);
        }
        return $serie_doc; // refrescar txtserie
    }


    protected function tipo_documento_porpuntoventa($puntoventa_series){
        $mensaje_documento_por_puntoventa = '';
        if ($this->lvalidadocpto == 1){ // validar documento por punto de venta ?
            if (empty($puntoventa_series)){ // No existe serie para el documento
                $mensaje_documento_por_puntoventa = getMesaage(11);
            }
        }
        return $mensaje_documento_por_puntoventa;
    }

    protected function tipo_documentocom_tercero($request, $documentocom){ // mov_facturaventa - cbotipodoc - método LostFocus

        $documento_tercero = Customer::documento_tercero_facturacion($request->tercero);
        if ($documento_tercero){
            if (in_array(trim($documento_tercero->codsunat), array('6', '06', '1', '01'))){ // DNI o RUC
                for ($i = 1; $i <= strlen(trim($request->doc_identidad)); $i++){
                    //if (!entre_intervalo(substr($request->doc_identidad, $i, 1),'/','9')){ // preguntar por el simbolo '/' del clasic
                    //    $mensaje_documento_cliente =  getMesaage(97); // resetear tipo doc 0
                    //}
                }
            }

            if ($documentocom->verenrventa == 1){
                if ($documento_tercero->codsunat == '6' || $documento_tercero->codsunat == '06' || $documento_tercero->codsunat == '0'){ // si es RUC ó otros
                    $cliente_codigo = $documento_tercero->ruc;
                    if (trim($documentocom->codsunat) == '03' && !empty($documento_tercero->dni)){ // si es boleta y tiene DNI
                        $cliente_codigo = $documento_tercero->dni;
                    }
                    $cliente_id = $documento_tercero->id;
                    $cliente_descripcion = $documento_tercero->descripcion;
                }else{
                    if (trim($documentocom->codsunat) == '01'){ // diferente a RUC, otros y si es factura
                        $mensaje_documento_cliente = getMesaage(86); // resetear tipo doc 0
                    }
                }

            }
        }

        $data['mensaje_documento_cliente'] = (isset($mensaje_documento_cliente)) ? $mensaje_documento_cliente : ''; // Documento de Identidad no válido //No se puede emitir este tipo de documento para este tipo de cliente - tipo_doc 0
        $data['cliente_id'] = (isset($cliente_id)) ? $cliente_id : ''; //refrescar el cliente
        $data['cliente_codigo'] = (isset($cliente_codigo)) ? $cliente_codigo : ''; //refrescar el cliente
        $data['cliente_descripcion'] = (isset($cliente_descripcion)) ? $cliente_descripcion : ''; //refrescar el cliente
        return $data;
    }

    protected function validar_fecha_documento(Request $request){ // Clasic, objeto: txtfecha: método: Valid
        
        $data['vencimiento'] = null;

        if ( Session::get('usuario') == 0) { // solo en la acción crear
        
            if(empty($request->serie_doc) == false && empty($request->numero_doc) == false){


                if ($t_validafecha = $this->t_validafecha($request->tipo_doc, $request->serie_doc, $request->numero_doc, $request->fecha_proceso)) {

                    // LIMPIAR FECHA PROCESO
                    return response()->json(['errors' => array('detalle' => $t_validafecha)],422);                 
                    
    
                }


            }


        }
        


        $fecha_vencimiento = (empty($request->vencimiento)) ? $request->fecha_proceso : $request->vencimiento;
        $fecha_proceso = date_create($request->fecha_proceso);
        $fecha_vencimiento = date_create($fecha_vencimiento);
        
        if ( $fecha_vencimiento < $fecha_proceso ){ $fecha_vencimiento = $fecha_proceso;}


        $codigo = explode('-', $fecha_vencimiento->format("Y-m-d"));
        $perido_codigo = Period::findOrFail(Session::get('period_id'))->codigo;
 

        $data['vencimiento'] = ($codigo[0].$codigo[1] !== $perido_codigo) ? null : $fecha_vencimiento->format("Y-m-d");
        
        return $data;

    }


    protected function condicion_pago(Request $request){
        
        $condicionpago = PaymentCondition::findOrfail($request->id);
        $dias = $condicionpago->dias;

        $fechavencimiento = (new Carbon($request->fecha))->addDays($dias);
        return $fechavencimiento->format('Y-m-d');

    }


    protected function validar_aplicar_credito_debito(Request $request){

        if ( empty($request->tipo_doc) ) {
             return 0;
        }

        $tipo_doc = Commercial::findOrFail($request->tipo_doc);

        if ( !in_array($tipo_doc->codsunat, array('07', '87', '97')) ) {
             return 0;
        }

    }

    protected function punto_venta_series($request, $documentocom){ // mov_facturaventa - cbotipodoc - método InteractiveChange

        $serie = 1;

        $puntoventa_series = PointSaleSeries::selectparentcmd_documentocom($request->punto_venta, $request->tipo_doc); // MOV_FACTURAVENTA - método Init - tambien lo inicializa en puntoventa - txtcodigo ver el final del segundo if
        $mensaje_documento_puntoventa = $this->tipo_documento_porpuntoventa($puntoventa_series);

        if ($puntoventa_series){ $serie = $puntoventa_series['serie']; }

        $docxpagar = DocumentToPay::find($request->id); // existe data solo en el editar o ver

        if (empty($docxpagar->seriedoc)){ // la otra condición se cumple ver nuevalinea() o nuevo() del formulario
            $serie_doc = $this->obtener_serie_doc($documentocom, $serie); // refrescar serie_doc
        }

        $data['mensaje_documento_puntoventa'] = $mensaje_documento_puntoventa;
        $data['serie_doc'] = (isset($serie_doc)) ? $serie_doc : '';
        return $data;
    }


    protected function validatipodoc(Request $request){ // mov_facturaventa - cbotipodoc - métodos: InteractiveChange, Valid, Lostfocus

        $documentocom = Commercial::find($request->tipo_doc); //crsdocumentocom

        if ($documentocom == null) : return ''; endif;

        $punto_venta_series = $this->punto_venta_series($request, $documentocom); // mov_facturaventa - cbotipodoc - método InteractiveChange
        $nota_debito_credito = $this->nota_debito_credito($documentocom); // mov_facturaventa - cbotipodoc - método InteractiveChange - cambios tipo_nota
        $numero_doc = $this->numero_doc($request->tipo_doc, $punto_venta_series['serie_doc']); // mov_facturaventa - cbotipodoc - método InteractiveChange

        if (!empty($punto_venta_series['serie_doc']) && !empty($numero_doc)){  // cntdoccompra - método validafecha

            $validafecha = $this->t_validafecha($request->tipo_doc, $punto_venta_series['serie_doc'], $numero_doc, $request->fecha_doc);
        }

        $tipo_documentocom_tercero = $this->tipo_documentocom_tercero($request, $documentocom); // mov_facturaventa - cbotipodoc - método LostFocus

        extract($punto_venta_series);
        extract($nota_debito_credito);
        extract($tipo_documentocom_tercero);
        $data['mensaje_documento_puntoventa'] = $mensaje_documento_puntoventa;
        $data['serie_doc'] = $serie_doc;
        $data['tipo_nota_data'] = $tipo_nota_data;
        $data['tipo_nota_label'] = $tipo_nota_label;
        $data['tipo_nota_habilitar'] = $tipo_nota_habilitar;
        $data['chkalmacen_checked'] = $chkalmacen_checked;
        $data['chkalmacen_habilitar'] = $chkalmacen_habilitar;
        $data['numero_doc'] = $numero_doc;
        $data['valida_fecha'] = (isset($validafecha)) ? $validafecha : '';
        $data['mensaje_documento_cliente'] = $mensaje_documento_cliente;
        $data['cliente_id'] =  $cliente_id;
        $data['cliente_codigo'] = $cliente_codigo;
        $data['cliente_descripcion'] = $cliente_descripcion;
        $data['documentocom_codsunat'] = $documentocom->codsunat; // ver txtserierem, método When

        return $data;

    }

    protected function nota_debito_credito($documentocom){

        if (trim($documentocom->codsunat) == '08' || trim($documentocom->codsunat) == '98'){ //this.form.cnttiponotacredito - tipo_nota

            $tipo_nota_data = TypeDebitNote::where('estado', 1)->get();
            $tipo_nota_label = 'Tipo Nota de Débito';
            $chkalmacen_checked = 0;
            $chkalmacen_habilitar = false;
            $tipo_nota_habilitar = true;

        }elseif(trim($documentocom->codsunat) == '07' || trim($documentocom->codsunat) == '97'){

            $tipo_nota_data = TypeCreditNote::where('estado', 1)->get();
            $tipo_nota_label = 'Tipo Nota de Crédito';
            $chkalmacen_checked = null; // no realizar ninguna acción (null)
            $chkalmacen_habilitar = true;
            $tipo_nota_habilitar = true;

        }else{

            $tipo_nota_habilitar = false;
            $chkalmacen_habilitar = true;

        }

        $data['tipo_nota_data'] = (isset($tipo_nota_data)) ? $tipo_nota_data : array(); // // llenar tipo_nota
        $data['tipo_nota_label'] = (isset($tipo_nota_label)) ? $tipo_nota_label : ''; //label tipo_doc
        $data['tipo_nota_habilitar'] = (isset($tipo_nota_habilitar)) ? $tipo_nota_habilitar: false; //activar selector: tipo_nota
        $data['chkalmacen_checked'] = (isset($chkalmacen_checked)) ? $chkalmacen_checked : null; // checked
        $data['chkalmacen_habilitar'] = (isset($chkalmacen_habilitar)) ? $chkalmacen_habilitar: false;  //activar check crear kardex

        return $data;
    }


    protected function numero_doc($tipo_doc, $serie){
        $numero = $this->t_sgtenumero($tipo_doc, $serie, 'C'); //cntdoccompra1(propiedad origen = "C")
        $numero_doc = str_pad($numero, 8, '0', STR_PAD_LEFT); //refrescar $numero_doc
        return $numero_doc;
    }

    protected function valida_puntoventa(Request $request){ // clasic, objeto txtcodigo, método Valid

        //  Validar si es administrador

        //  odata = NEWOBJECT('puntoventa_series') <- sirve para actualizar el cursor crspuntoventa_series ver clasic objeto: cntdoccompra1, objeto cbotipodoc, método interactivechange
		//  odata.selectparentcmd(this.Parent.oregistro.id) <- sirve para actualizar el cursor crspuntoventa_series ver clasic objeto: cntdoccompra1, objeto cbotipodoc, método interactivechange

        $data = array();

        $documentocom = Commercial::find($request->tipo_doc); //crsdocumentocom

        if ( $request->tipo_doc > 0 ) {

            $punto_venta_series = $this->punto_venta_series($request, $documentocom); // mov_facturaventa - cbotipodoc - método InteractiveChange
            $nota_debito_credito = $this->nota_debito_credito($documentocom); // mov_facturaventa - cbotipodoc - método InteractiveChange - cambios tipo_nota
            $numero_doc = $this->numero_doc($request->tipo_doc, $punto_venta_series['serie_doc']); // mov_facturaventa - cbotipodoc - método InteractiveChange


            extract($punto_venta_series);
            $data['mensaje_documento_puntoventa'] = $mensaje_documento_puntoventa;
            $data['serie_doc'] = $serie_doc;

            extract($nota_debito_credito);
            $data['tipo_nota_data'] = $tipo_nota_data;
            $data['tipo_nota_label'] = $tipo_nota_label;
            $data['tipo_nota_habilitar'] = $tipo_nota_habilitar;
            $data['chkalmacen_checked'] = $chkalmacen_checked;
            $data['chkalmacen_habilitar'] = $chkalmacen_habilitar;

            $data['numero_doc'] = $numero_doc;

        }

        return $data;

    }

    protected function validar_detraccion_por_tipoafecta(Request $request){

        return $this->detractionBillingController->validar_detraccion_por_tipoafecta($request);

    }

    protected function validar_formapago(Request $request){

       return $this->paymentMethodBillingController->validar_formapago($request);

    }

    protected function validar_importefp(Request $request){

        return $this->paymentMethodBillingController->validar_importefp($request);
        
    }

    protected function validar_nrochequefp(Request $request){

        return $this->paymentMethodBillingController->validar_nrochequefp($request);

    }


    protected function obtener_notacreditodebito($docxpagar){

        if ($docxpagar->tiponotacredito_id != null){

            return TypeCreditNote::findOrFail($docxpagar->tiponotacredito_id);

        }elseif($docxpagar->tiponotadebito_id != null){

            return TypeDebitNote::findOrFail($docxpagar->tiponotadebito_id);

        }

    }

    protected function validar_detraccion(Request $request){ // objeto: cnttipodetraccion - txtcodigo, método: Valid

        return $this->detractionBillingController->validar_detraccion($request);

    }

    protected function obtener_ref_anticipo($id){ // formulario: mov_facturaventa, método: llenar

        $ref_anticipo = '';
        $ref_anticipo_id = null;

        if($id){

            $docxpagar_temp = DocumentToPay::select("id", "seriedoc", "numerodoc")->where("liquidacion_id", $id)->first();

            if($docxpagar_temp){

                $ref_anticipo = $docxpagar_temp->seriedoc . '-' - $docxpagar_temp->numerodoc;
                $ref_anticipo_id = $docxpagar_temp->id;


            }

        }

        $data['ref_anticipo'] = $ref_anticipo;
        $data['ref_anticipo_id'] = $ref_anticipo_id;

        return $data;

    }

    public function documento_aplicar_seleccionado(Request $request){

        $this->documentApllyBillingController->documento_aplicar_seleccionado($request);
        return $this->documentApllyBillingController->obtener_total_aplicar($request);        

    }

    public function cambiar_saldo_aplicar_documentos(Request $request){

        $this->documentApllyBillingController->cambiar_saldo_aplicar_documentos($request);
        return $this->documentApllyBillingController->obtener_total_aplicar($request);

    }


    public function agregar_documentos_aplicar(){

        // NO DEBEN EXISTIR REFERENCIAS DE NOTAS DE CRÉDITO O DÉBITO 
        // LO MISMO HAGO SI SE NECESITA AGREGAR NOTAS DE CRÉDITO O DÉBITO, ELIMINO EN ESE CASO LOS DOCUMENTOS A APLICAR
        Cart::instance($this->cart_referencia)->destroy(); // formulario: mov_facuraventa - O_button1, método: Click

        $this->documentApllyBillingController->agregar_documentos_aplicar();        
        return $this->documentApllyBillingController->mostrar_documento_aplicar();

    }

    protected function eliminar_variables_de_sesion(){

        session()->forget('items_docxpagar_detalle', []);
        session()->forget('tisc', 0);
        session()->forget('ticbper', 0);
        session()->put('id', 0);
        session()->forget('respuesta_sunat');
        session()->forget('respuesta_contabiliza');
        session()->forget('respuesta_centraliza');
        session()->forget('cotizacion_id');
        session()->forget('ordentrabajo_id');        

    }

    protected function agregar_documentos_referencia(Request $request){

        $data = json_decode($request->data);

        $count = count($data);

        $respuesta = array();

        if ( $count > 0 ){ // existen detalles seleccionados ?

            $respuesta = $this->referenceBillingController->agregar_detalles_referencia($request, $this->detailBillingController, $data);

        }        

        $respuesta = array_merge($respuesta, $this->sumar($request));
        
        return $respuesta;

    }

    protected function eliminar_documentos_electronicos($docxpagar){

        if ($this->lcpe == 1 && ctype_alpha(substr($docxpagar->seriedoc, 0, 1)) && entre_intervalo($this->cservcpe, "1", "3")){
            return response()->json(['errors' => array('detalle' => "Para anular este documento utilice la opción Comunicación de Baja" )],422);
        }

    }

    protected function eliminar_documentos_aplicados($docxpagar){

        $movctate = $this->movctacteBillingController->obtener_movctacte();
        $total_aplicado = 0; 
        foreach($movctate as $m){
            
            if($m->id != $m->options->origen_id && $m->options->origen_id != $docxpagar->detraccion_id){
                $total_aplicado += $m->saldomn;
            }

        }
        if($total_aplicado != 0){
            return response()->json(['errors' => array('detalle' => "El documento tiene aplicaciones. Se requiere anular sus aplicaciones previamente" )],422);
        }




        $referencia = Reference::verificar_eliminar_facturacion($docxpagar->id);
        if($referencia){
            return response()->json(['errors' => array('detalle' => "El documento tiene aplicaciones. Se requiere anular sus aplicaciones previamente" )],422);
        }

    }

    protected function eliminar_actualizar_referencias($docxpagar){

        DB::beginTransaction();

        try {

            if($docxpagar->liquidacion_id){DocumentToPay::destroy($docxpagar->id);}

            if($docxpagar->ordentrabajo_id){WorkOrder::estado_eliminar_facturacion(Session::get('usuario'), $docxpagar->ordentrabajo_id);}
    
            if($docxpagar->cotizacion_id){Quotation::estado_eliminar_facturacion(Session::get('usuario'), $docxpagar->cotizacion_id);}
            
            DB::commit();
            

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['errors' => array('detalle' => $e->getMessage() )],422);
        }


    }

    protected function anular_documentos_electronicos($docxpagar){

        if ($this->lcpe == 1 && entre_intervalo($this->cservcpe, "1", "3")){
            
            if(substr($docxpagar->seriedoc, 0, 1) == "F"){

                return response()->json(['errors' => array('detalle' => 'Para anular este documento utilice la opción Comunicación de Baja' )],422);

            }else{

                $doccoumicacion_detalle = DocComunicacionDetalle::anular_facturacion($docxpagar_id);

                if($doccoumicacion_detalle){

                    return response()->json(['errors' => array('detalle' => 'El documento ya fue informado a Sunat' )],422);

                }

            }


        }

    }

    protected function anular_documentos_aplicados($docxpagar){

        $llAnula = Pempresa::get_parametro('VTA_ANUBCO')->valor; // Anular Mov.Caja al anular Venta (sólo contados)

        $movctate = $this->movctacteBillingController->obtener_movctacte();

        foreach($movctate as $m){

            if($m->id != $m->options->origen_id && $m->options->origen_id != $docxpagar->detraccion_id){

                $condicionpago = PaymentCondition::find($docxpagar->condicionpago_id); 
                
                if($condicionpago){


                    if($docxpagar->total != 0 && $condicionpago->dias > 0){

                        if($llAnula == 0){

                            return response()->json(['errors' => array('detalle' => "El documento tiene aplicaciones. Se requiere anular sus aplicaciones previamente" )],422);

                        }

                    }


                }
                

            }


        } // FIN FOREACH

        $referencia = Reference::verificar_eliminar_facturacion($docxpagar->id);
        if($referencia){
            return response()->json(['errors' => array('detalle' => "El documento tiene aplicaciones. Se requiere anular sus aplicaciones previamente" )],422);
        }

    }


    protected function setanulacpe($docxpagar){

        if($docxpagar->estado == "ANULADO"){

            $docxpagar->setanulacpe($docxpagar->id, Session::get('usuario'));

        }

    }

    protected function eliminar_detraccion_por_tipoafecta(Request $request){

        return $this->detractionBillingController->validar_tipo_afecta_30($request);

    }
}
