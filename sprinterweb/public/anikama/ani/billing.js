$(document).ready(function(){
     
    let fecha = $('#fecha_proceso').val();

    if( $('#proceso').val() === 'crea'){

        validarFecha(fecha);        
        consultar_tipocambio(fecha);
        tipo_venta($("#tipo_venta").val());

    }
    if( $('#proceso').val() === 'edita'){

        tercero_direccion($("#tercero").val());

        let sunat_estado = $("#sunat_estado").val();
        if(sunat_estado !== ''){
            let sunat_codigo = $("#sunat_codigo").val();
            let sunat_descripcion = $("#sunat_descripcion").val();
            warning_sunat('warning', 'Respuesta Sunat, código: ' + sunat_codigo + ' descripción: ' + sunat_descripcion , 'Información')
        }
        
        let respuesta_contabiliza = $("#respuesta_contabiliza").val();
        if(respuesta_contabiliza !== ''){warning_sunat('warning', 'No se contabilizó: ' + respuesta_contabiliza)}

        let respuesta_centraliza = $("#respuesta_centraliza").val();
        if(respuesta_centraliza !== ''){warning_sunat('warning', 'No se centralizó: ' + respuesta_centraliza)}
        
    }

    llenarSelect('tercero', '/customers/buscar_tercero');
    listado_guias_remision.init();
    listado_cronologia_cpe.init();
    listado_hitorial_aplicaciones.init();

});
var variable = $("#var").val();

var table = $('#BillingDetails').DataTable({
        serverSide: true,
        ajax: '/billing/lista_detalles',
        destroy: true,
        scrollX: true,
        scrollCollapse: true,
        autoWidth: false,
        columnDefs: [
            {
                "targets": 0,
                "visible": false,
                "searchable": false
            }
        ],
        columns: [
            {data: 'id'},
            {
                "sName": "Index",
                "render": function (data, type, row, meta) {
                    return meta.row + 1; // númeración items
                },
                "orderable": "false",
            },
            {data: 'options.codigo', "className": "text-center",
                "render": function ( data, type, full, meta ) {
                return `<a onclick="producto(${full.options.id})">${data == null ? '' : data}</a>`;
                },
            },            
            {data: 'options.descripcion', "className": "text-center"},
            {data: 'options.umedida_codigo', "className": "text-center"},
            {
                data: function (row) {
                    console.log(row);
                    return serie_billing(row);
                },
            },
            {data: 'options.stock', "className": "text-center"},
            {
                data: function (row) {
                    return cantidad_billing(row);
                }
            },
            {
                data: function (row) {
                    return precio_billing(row);
                }
            },
            {
                data: function (row) {
                    return descuento_billing(row);
                },
            },
            {data: 'options.precio', "className": "text-center"},
            {data: 'options.isc_icbper', "className": "text-center"},
            {data: 'options.importe', "className": "text-center"},
            {data: 'options.referencia_codigo', "className": "text-center"},
            {data: 'options.cuenta_codigo', "className": "text-center"},
            {
                className: "text-center",
                'render': function (data, type, row, meta) {

                    return centro_costo_detalle(row);

                }
            },
            {
                className: "text-center",
                'render': function (data, type, row, meta) {

                    return tipo_afecta_detalle(row);

                }
            },
            {
                data: function (row) {
                    return "<select id='actividad"+row.options.id+"' name='actividad"+row.options.id+"' class='select2'><option value=''>-Seleccionar-</option></select>";
                },
                "orderable": "false",
                "className": "text-center"
            },
            {
                data: function (row) {
                    return "<select id='proyecto"+row.options.id+"' name='proyecto"+row.options.id+"' class='select2'><option value=''>-Seleccionar-</option></select>";
                },
                "orderable": "false",
                "className": "text-center"
            },
            {data: 'options.op', "className": "text-center"},
            {
                data: function (row) {
                    return '<a  id="btnDelete"  class="btn"><span style="color:red" class="fa fa-trash-o fa-2x"></a>'
                },
                "orderable": "false",
            },
        ],
        order: [[0, 'desc']],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
});

function documentos_referencia(){

    let fproceso = $("#fecha_proceso").val();
    $("#referencia_hasta").val(fproceso).attr("max",fproceso);

    limpiar_datatable(listado_cotizacion_detalle_facturacion);
    limpiar_datatable(listado_cotizacion_facturacion);

    $("#modalDocumentosReferencia").modal("show");
}

$("#referencia_hasta").blur(function(){

    let fproceso = $("#fecha_proceso").val();

    if($("#referencia_hasta").val() > fproceso){
        $("#referencia_hasta").val(fproceso).attr("max",fproceso);
    }
    

});

function mostar_referencias(){

    let referencia_tipo = $("#referencia_tipo").val();
    let referencia_nombre = $("#referencia_nombre").val();
    let referencia_serie = $("#referencia_serie").val();
    let referencia_numero = $("#referencia_numero").val();
    let referencia_desde = $("#referencia_desde").val();
    let referencia_hasta = $("#referencia_hasta").val();
    let tipo_venta = $("#tipo_venta").val();

    listado_cotizacion_facturacion.init("/" + variable + "/mostrar_referencias/" + "?referencia_tipo=" + referencia_tipo + "&referencia_nombre=" + referencia_nombre + "&referencia_serie=" + referencia_serie + "&referencia_numero=" + referencia_numero + "&referencia_desde=" + referencia_desde + "&referencia_hasta=" + referencia_hasta + "&tipo_venta=" + tipo_venta);

}

function chk_referencia_oc(id){

    let estado = $("#chk_referencia_oc"+id).prop('checked');
    let referencia_tipo = $("#referencia_tipo").val();

    listado_cotizacion_detalle_facturacion.init("/" + variable + "/mostrar_referencias_detalle/" + "?id=" + id + "&estado=" + estado + "&referencia_tipo=" + referencia_tipo);

}

function agregar_documentos_referencia(){

    let data = obtener_checks_documentos_referencia();
    let tipo_venta = $("#tipo_venta").val(); // sumar
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val(); // sumar
    let igv = $("#igv").val(); // sumar
    
    $.ajax({
        headers: { 'X-CSRF-Token': $('#_token').val() },
        type: "POST",
        url: "/" + variable + "/agregar_documentos_referencia",
        data: {data: JSON.stringify(data), tipo_venta: tipo_venta, tipo_afectacion_igv: tipo_afectacion_igv, igv: igv},
        success: function (data) {
            resultado_agregar_documentos_referencia(data);
            resultado_sumar_billing(data);
            $("#modalDocumentosReferencia").modal("hide");
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function resultado_agregar_documentos_referencia(data){
    
    if(data['tercero_id']){

        $("#tercero").empty().append("<option value='"+data['tercero_id']+"' selected>"+data['tercero_codigo']+ " | " + data['tercero_descripcion'] +"</option>");
        $("#doc_identidad").val(data['doc_identidad']);
        $("#direccion").empty().append("<option value='"+data["ubigeo_id"]+"'>"+data['direccion'])+"</option>";
        $("#vendedor").val(data['vendedor_id']).trigger("change");
        $("#condicion_pago").val(data['condicionpago_id']).trigger("change");
        $("#moneda").val(data['moneda_id']).trigger("change");
        $("glosa").val(data['glosa']);

        tercero_direccion(data['tercero_id']);

    }
    if(data['tmprefer']){
        
        data['tmprefer'].forEach(function(data){
            $("#referencia_op").empty().append("<option value='"+data['id']+"' data-ventana='" + data['ventana'] + "'>" + data['numero'] + "</option>");        
        });   

    }
     

}

function obtener_checks_documentos_referencia() {
    let tabla = $('#listado_cotizacion_detalle_facturacion').DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        let data = this.node();
        if ($(data).find('input').prop('checked')) {
            det = {
                'ids': $(data).find('input').serializeArray()[0],
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}


$('#tercero').on('select2:select', function (e) {
    let data = e.params.data;
    console.log(data);
    $("#doc_identidad").val(data.otros['codigo']);
    $("#direccion").empty().append("<option value='"+data.otros["ubigeo_id"]+"'>"+data.otros['direccion'])+"</option>";
    validar_tercero(data.otros);
    tercero_direccion(data.id);
    agregar_tercero_modales(data.otros);
});
function tercero_direccion(tercero_id){
    $.ajax({
        type: "GET",
        url: "/" + variable + "/tercero_direccion/"+tercero_id,
        dataType: "JSON",
        success: function (data) {
           data.forEach(function(data){
               $("#direccion").append("<option value='"+data.ubigeo_id+"'>"+ data.descripcion + "</option>");
           });
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}
function validar_tercero(data){ // clasic objeto padre: cntclieprov, objeto hijo: txtcodigo, método: Valid
    if ($("#condicion_pago").val() === ""){
        let condicioncobr_id = (data['condicioncobr_id'] !== null) ? data['condicioncobr_id'] : 1;
        $("#condicion_pago").val(condicioncobr_id).trigger("change");
    }

    if (data['vendedor_id'] !== null) {
        $("#vendedor").val(data['vendedor_id']).trigger("change").prop('readonly',true);
    }

}
$("#tipo_doc").change(function(){
    $.ajax({
        type: "GET",
        url: "/" + variable + "/validatipodoc",
        data: {tipo_doc: $(this).val(), fecha_doc: $("#fecha_proceso").val(), tercero: $("#tercero").val(), doc_identidad: $("#doc_identidad").val(), punto_venta: $("#punto_venta").val(), id: $("#id").val()},
        success: function (data) {
            if(data != ''){ //ver cbotipodoc método Interactivechange, Valid, Lostfocus
                validar_tipo_doc(data);
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});
function validar_tipo_doc(data){
    
    if (data['mensaje_documento_puntoventa'] !== ''){

        error('error', data['mensaje_documento_puntoventa'], "Error!");
        return false;

    }
    if (data['serie_doc'] !== ''){

        $("#serie_doc").val(data['serie_doc']);

    }
    if (data['tipo_nota_habilitar'] === false){

        $("#tipo_nota").prop("disabled", true).empty();
        $("#label_tipo_nota").text("Tipo Nota de Crédito:");
        $("#documento_credito_debito").val('');
        $("#aplicar_credito_debito").prop("checked", false);

    }else{

        $("#tipo_nota").prop("disabled", false);
        $("#label_tipo_nota").text(data['tipo_nota_label']);
        llenar_tipo_nota(data['tipo_nota_data']);

    }

    $("#numero_doc").val(data['numero_doc']);

    if  (data['chkalmacen_checked'] !== null) {

        $("#crear_kardex").prop("checked", data['chkalmacen_checked']);
        evaluar_click_crear_kardex();

    }

    if  (data['chkalmacen_habilitar'] !== null) {

        $("#crear_kardex").prop("disabled", !data['chkalmacen_habilitar']);

    }

    if (data['valida_fecha'] !== ''){

        $("#fecha_proceso").val("");
        error('error', data['valida_fecha'], "Error!");

    }
    if (data['cliente_codigo'] !== ''){

        $("#tercero").append("<option value='"+data['cliente_id']+"'>"+data['cliente_codigo']+ " | " + data['descripcion'] +"</option>");
        $("#doc_identidad").val(data['cliente_codigo']);

    }
    if (data['mensaje_documento_cliente'] !== ''){

        $("#tipo_doc").val("").trigger("change");
        error('error', data['mensaje_documento_cliente'], "Error!");

    }

    if (data['documentocom_codsunat'] === '07'){ // ver txtserierem, método When

        $("#serierem").prop("readonly",true).val('');
        $("#nrorem").prop("readonly",true).val('');

    }
}
function llenar_tipo_nota(data) {

    $("#tipo_nota").empty().append("<option value=''>-Seleccionar-</option>");
    data.forEach(function(data){
        $("#tipo_nota").append("<option value='"+data.id+"'>"+ data.codigo +" | "+ data.descripcion +"</option>");
    });

}
$("#condicion_pago").change(function(){

    id = $(this).val();
    fecha =  $('#fecha_proceso').val();

    if(id !== '' && fecha !== ''){
        $.ajax({
            type: "get",
            url: "/" + variable + "/condicion_pago",
            data: {id: id, fecha: fecha},
            success: function (data) {
                $('#vencimiento').val(data);
            },
        });
    }

});

$("#igv").change(function(){ // cboimpuesto valid
    let igv  = $("#igv option:selected");
    let tipocalculo = igv.data('tipocalculo');
    let valor = igv.data('valor');
    let base = $("#base").val();
    if (tipocalculo === 'P'){
        valor = valor / 100;
        impuesto = base * valor;
    } else {
        impuesto = valor;
    }
    $("#impuesto").val(parseFloat(impuesto).toFixed(2));
});
$("#percepcion").change(function(){ // cboimpuesto2 valid
    let percepcion  = $("#percepcion option:selected");
    let tipocalculo = percepcion.data('tipocalculo');
    var valor = percepcion.data('valor');
    let base = parseFloat($("#base").val());
    let inafecto = parseFloat($("#inafecto").val());
    let impuesto = parseFloat($("#impuesto").val());
    let descuento = parseFloat($("#descuento").val());
    let isc_icbper = parseFloat($("#isc_icbper").val());

    if (tipocalculo === 'P'){

        valor = (base + inafecto + impuesto + descuento) * (valor / 100);

    }

    $("#impuesto2").val(parseFloat(valor).toFixed(2));
    let total = base + inafecto + descuento + impuesto + isc_icbper;
    $("#total").val(parseFloat(total).toFixed(2));
});
$('#btn_nueva_linea').click(function () {

    if ($("#id").val() != 0){return false;}

    if(validar_modal() === false){return false;}

    limpiar_datatable(billingProducts);
    $('#modal_add').modal('show');
});
function validar_modal(){
    if ($("#punto_venta").val() === '') { warning('warning', 'Seleccionar punto de venta', 'Información'); return false}
    if ($("#tcambio").val() === '') { warning('warning', 'Ingrese un tipo de cambio', 'Información'); return false}
    if ($("#tipo_doc").val() === '') { warning('warning', 'Seleccione un tipo de documento', 'Información'); return false}
}
function bucar_producto(){
    let producto_codigo = $("#producto_codigo").val();
    let producto_descripcion = $("#producto_descripcion").val();
    let producto_presentacion = $("#producto_presentacion").val();
    let punto_venta = $("#punto_venta").val();
    let fecha_proceso = $("#fecha_proceso").val();
    let tipo_venta = $("#tipo_venta").val();
    if (valida_busqueda_producto(producto_codigo, producto_descripcion, producto_presentacion) === false){return false;}

    billingProducts.init('/billing/buscar_producto/'+ '?producto_codigo=' + producto_codigo + '&producto_descripcion=' + producto_descripcion + '&producto_presentacion=' + producto_presentacion + "&punto_venta=" + punto_venta + "&fecha_proceso=" + fecha_proceso + "&tipo_venta=" + tipo_venta);
}
function valida_busqueda_producto(codigo, descripcion, presentacion){
    let busqueda = codigo.trim() + descripcion.trim() + presentacion.trim(); // A menos un criterio de busqueda
    if (busqueda === ''){ error('error', 'Ingrese un criterio de busqueda', 'Error'); return false; }
}
$('#producto_codigo').keydown(function (event) {
    if(event.keyCode == 9){
        limpiar_inputs_busqueda_producto();
        bucar_producto();
    }
});
$('#producto_descripcion').keydown(function (e) {
    if(e.keyCode == 9) {
        limpiar_inputs_busqueda_producto();
        bucar_producto();
    }
});
$('#producto_presentacion').keydown(function (e) {
    if(e.keyCode == 9) {
        limpiar_inputs_busqueda_producto();
        bucar_producto();
    }
});
function limpiar_inputs_busqueda_producto(){
    let codigo = $('#producto_codigo').val();
    if (codigo !== ''){
        $("#producto_descripcion").val("");
        $("#producto_presentacion").val("");
    }
}
function agregar_productos(){
    let data = obtener_checks_seleccionados('listProductDetailBilling');
    let tipo_venta = $("#tipo_venta").val(); // validar la serie // sumar
    let punto_venta = $("#punto_venta").val(); // validar la serie
    let igv = $("#igv").val(); // sumar
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val(); // sumar
    let importe = $("#importe").val();
    let moneda = $("#moneda").val();
    let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_doc = $("#tipo_doc").val();
    let lote = '';
    let tcambio = $("#tcambio").val();
    let fecha_proceso = $("#fecha_proceso").val(); // validar serie
    $.ajax({
        headers: { 'X-CSRF-Token': $('#_token').val() },
        type: "POST",
        url: "/" + variable + "/agregar_productos",
        data: {data: JSON.stringify(data), tipo_venta: tipo_venta, igv: igv, tipo_afectacion_igv: tipo_afectacion_igv, importe: importe, moneda: moneda, crear_kardex: crear_kardex, tipo_doc: tipo_doc, lote: lote, tcambio: tcambio, punto_venta: punto_venta, fecha_proceso: fecha_proceso},
        success: function (data) {
            $("#modal_add").modal("hide");
            resultado_sumar_billing(data);
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function obtener_checks_seleccionados(id) {
    let tabla = $('#' + id).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        let data = this.node();
        if ($(data).find('input').prop('checked')) {
            det = {
                'ids': $(data).find('input').serializeArray()[0],
                'series': $(data).find('input').serializeArray()[1],
                'cantidades': $(data).find('input').serializeArray()[2],
                'precios': $(data).find('input').serializeArray()[3],
                'dsctos': $(data).find('input').serializeArray()[4],
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}

function limpia_modal(){
    $("#producto_codigo").val('');
    $("#producto_descripcion").val('');
    $("#producto_presentacion").val('');
}

$("#punto_venta").change(function(e){
    $.ajax({
        type: "GET",
        url: "/" + variable + "/valida_puntoventa",
        data : {tipo_doc: $("#tipo_doc").val(), punto_venta: $(this).val(), id: $("#id").val()},
        success: function (data) {
            console.log(data);
            if (data != '') {
                validar_tipo_doc_para_puntoventa(data);    
            }
            
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});
$("#tipo_venta").change(function (e) {    
    tipo_venta($(this).val());
});

function tipo_venta(tipo_venta){

    $.ajax({
        type: "GET",
        url: "/" + variable + "/valida_tipoventa",
        data : {tipo_afectacion_igv : $("#tipo_afectacion_igv").val(), tipo_venta : tipo_venta},
        success: function (data) {
            validar_tipo_venta(data);
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function validar_tipo_venta(data){

    if ( data['impuesto_id'] !== null ) {
        $("#igv").val(data['impuesto_id']).trigger('change');
    }

    if ( data['tipo_afectacion_igv'] !== null ) {
        $("#tipo_afectacion_igv").val(data['tipo_afectacion_igv']).trigger('change');
    }

    if ( data['crear_kardex_checked'] !== null ) {
        $("#crear_kardex").prop("checked", data['crear_kardex_checked']);
        evaluar_click_crear_kardex();
    }

    if ( data['glosa'] !== null ) {
        $("#glosa").val(data['glosa']);
    }

}

$("#tipo_afectacion_igv").change(function() { // clasic tipo_afectacion_igv, objeto txtcodigo, método: Valid
    let tipo_venta_esgratuito = $("#tipo_venta option:selected").data('esgratuito');
    let tipo_afectacion_igv_codigo = $("#tipo_afectacion_igv option:selected").data('codigo');

    if ( tipo_venta_esgratuito === 1 && !(entre_intervalo(tipo_afectacion_igv_codigo, 11, 16) || entre_intervalo(tipo_afectacion_igv_codigo, 31, 36) || tipo_afectacion_igv_codigo === '21') ){
        error('error', "El tipo de afectación  no es válido. Rectifique!", "Error!");
        $("#tipo_afectacion_igv").val("").trigger("change");
    }    

    // SI SOLO HAY UN ITEM EN DOCXAPGAR_DETALLE EN SU VALOR tipoafectaigv_id TOMARÍA EL ID tipo_afectacion_igv DEL DOCXPAGAR
    // SI EL CÓDIGO DE tipo_afectacion_igv >= 30 ELIMINAR LAS DETRACCIONES (crsdocxpagar_detraccion)

    eliminar_detraccion_por_tipoafecta();


});

function existe_stock_billing(id) {
    check_detalle_billing(id)
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/existe_stock",
        data:{id: id},
        success: function (data) { console.log(data);},
        error: function (data) {
            mostrar_errores(data);
            $("#cantidad_modal" + id).val(0);
            $("#detalle_modal" + id).prop('checked', false);
            check_detalle_billing(id);
        }
    });
}

function cantidad_billing_modal(data){
    switch (data.options.pideserie) {
        case 0:
            return '<input type="number" id="cantidad_modal'+ data.options.id +'" name="cantidad_modal'+  data.options.id +'" value="'+ data.options.cantidad +'" class="form-control text-right width-75" onblur="cantidad_billing_modal2(' + data.options.id +')" disabled>';
            break;
        case "1":
            return '<input type="number" id="cantidad_modal'+ data.options.id +'" name="cantidad_modal'+  data.options.id +'" class="form-control  text-right width-75" value="1" readonly onblur="cantidad_billing_modal2(' + data.options.id +')" disabled>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function cantidad_billing(data){
    if (data.options.referencia_id > 0){ return '<input type="number" id="cantidad'+data.id+'" name="cantidad'+data.id+'" value="'+ data.options.cantidad +'" class="form-control text-right width-75" readonly>'; }
    switch (data.options.pideserie) {
        case 0:
            return '<input type="number" id="cantidad'+data.id+'" name="cantidad'+data.id+'" value="'+ data.options.cantidad +'" class="form-control text-right width-75" onblur="cantidad_billing_modal2(' + data.options.id +', ' + data.id + ')">';
            break;
        case "1":
            return '<input type="number" id="cantidad'+data.id+'" name="cantidad'+data.id+'" class="form-control text-right width-75" value="1" readonly onblur="cantidad_billing_modal2(' + data.options.id +', ' + data.id + ')">';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function cantidad_billing_modal2(id = '', item = ''){

    if ($("#id").val() != 0){return false;}

    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle_billing(id) === false ) { return false; }
    let cantidad = $("#cantidad"+origen).val(); // input
    let tcambio = $("#tcambio").val();
    let moneda = $("#moneda").val();
    let tipo_doc = $("#tipo_doc").val();
    let descuento = $("#descuento"+origen).val(); // input
    let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#tipo_venta").val();
    let igv = $("#igv").val();
    let precio = $("#precio"+origen).val(); // preciolista - input
    let serie = $("#serie"+origen).val(); // input
    let lote = '';
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val();
    let importe = $("#importe").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_cantidad",
        data:{cantidad: cantidad, tcambio: tcambio, moneda: moneda, tipo_doc: tipo_doc, descuento: descuento, crear_kardex: crear_kardex, tipo_venta: tipo_venta, igv: igv, precio: precio, lote: lote, tipo_afectacion_igv: tipo_afectacion_igv, serie: serie, importe: importe, id: id, item: item},
        success: function (data) {
            console.log(data)
            if (data.cantidad) {
                $("#cantidad"+origen).val(data.cantidad);
            }
            resultado_sumar_billing(data);
        }, error: function (data) {
            mostrar_errores(data);
            $("#cantidad"+origen).val(data.responseJSON.cantidad);
        }
    });
}

function precio_billing_modal(data) {
    switch (data.options.editar_precio) {
        case "0":
        case "1":
            return '<input type="number" id="precio_modal' + data.options.id + '" name="precio_modal' + data.options.id + '" class="form-control text-right width-75" value="' + data.options.preciolista + '" onblur="precio_billing_modal2(' + data.options.id + ')" disabled>';
            break;
        case "2":
            return '<input type="number" id="precio_modal' + data.options.id + '" name="precio_modal' + data.options.id + '" class="form-control text-right width-75" value="' + data.options.preciolista + '" readonly onblur="precio_billing_modal2(' + data.options.id + ')" disabled>';
            break;
    }
}

function precio_billing(data){
    if (data.options.referencia_id > 0){ return '<input type="number" id="precio'+data.id+'" name="precio'+data.id+'" class="form-control text-right width-75" value="' + data.options.preciolista + '" readonly>'; }
    switch (parseInt(data.options.editar_precio)) {
        case 0:
        case 1:            
            return '<input type="number" id="precio'+data.id+'" name="precio'+data.id+'" class="form-control text-right width-75" value="' + data.options.preciolista + '" onblur="precio_billing_modal2(' + data.options.id + ', ' + data.id + ')">';
            break;
        case 2:
            return '<input type="number" id="precio'+data.id+'" name="precio'+data.id+'" class="form-control text-right width-75" value="' + data.options.preciolista + '" readonly onblur="precio_billing_modal2(' + data.options.id + ', ' + data.id +')">';
            break;
    }
}

function precio_billing_modal2(id, item = ''){

    if ($("#id").val() != 0){return false;}

    let origen = ( item !== '' ) ? item  : "_modal"+id;

    $("#precio"+origen).val(parseFloat($("#precio"+origen).val()).toFixed(6));

    if ( check_detalle_billing(id) === false ) { return false; }
    let cantidad = $("#cantidad"+origen).val(); // input
    let tcambio = $("#tcambio").val();
    let moneda = $("#moneda").val();
    let tipo_doc = $("#tipo_doc").val();
    let descuento = $("#descuento"+origen).val(); // input
    let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#tipo_venta").val();
    let igv = $("#igv").val();
    let precio = $("#precio"+origen).val(); // preciolista - input
    let serie = $("#serie"+origen).val(); // input
    let lote = '';
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val();
    let importe = $("#importe").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_precio",
        data:{cantidad: cantidad, tcambio: tcambio, moneda: moneda, tipo_doc: tipo_doc, descuento: descuento, crear_kardex: crear_kardex, tipo_venta: tipo_venta, igv: igv, precio: precio, lote: lote, tipo_afectacion_igv: tipo_afectacion_igv, serie: serie, importe: importe, id: id, item: item},
        success: function (data) {
            console.log(data)
            if (data.cantidad) {
                $("#cantidad"+origen).val(data.cantidad);
            }
            resultado_sumar_billing(data);
        }, error: function (data) {
            mostrar_errores(data);
            $("#precio"+origen).val(data.responseJSON.precio)
        }
    });
}

function descuento_billing_modal(data) {
    switch (parseInt(data.options.pdescuento)) {
        case 1:
            return '<input type="number" id="descuento_modal'+data.options.id+'" name="descuento_modal'+data.options.id+'" class="form-control width-75" value="" onblur="descuento_billing_modal2('+data.options.id+')" disabled>';
            break;
        case 0:
            return '<input type="number" id="descuento_modal'+data.options.id+'" name="descuento_modal'+data.options.id+'" class="form-control width-75" value="" onblur="descuento_billing_modal2('+data.options.id+')" disabled readonly>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function descuento_billing(data) {
    if (data.options.referencia_id > 0){ return '<input type="number" id="descuento'+data.id+'" name="descuento'+data.id+'" class="form-control width-75" value="' + data.options.descuento + '" readonly>';  }
    switch (parseInt(data.options.pdescuento)) {
        case 1:
            return '<input type="number" id="descuento'+data.id+'" name="descuento'+data.id+'" class="form-control width-75" value="' + data.options.descuento + '" onblur="descuento_billing_modal2(' + data.options.id + ', ' + data.id + ')">';
            break;
        case 0:
            return '<input type="number" id="descuento'+data.id+'" name="descuento'+data.id+'" class="form-control width-75" value="" onblur="descuento_billing_modal2(' + data.options.id + ', ' + data.id + ')" readonly>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function descuento_billing_modal2(id = '', item = ''){

    if ($("#id").val() != 0){return false;}

    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle_billing(id) === false ) { return false; }
    let cantidad = $("#cantidad"+origen).val(); // input
    let tcambio = $("#tcambio").val();
    let moneda = $("#moneda").val();
    let tipo_doc = $("#tipo_doc").val();
    let descuento = $("#descuento"+origen).val(); // input
    let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#tipo_venta").val();
    let igv = $("#igv").val();
    let precio = $("#precio"+origen).val(); // input
    let serie = $("#serie"+origen).val(); // input
    let lote = '';
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val();
    let importe = $("#importe").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_descuento",
        data:{cantidad: cantidad, tcambio: tcambio, moneda: moneda, tipo_doc: tipo_doc, descuento: descuento, crear_kardex: crear_kardex, tipo_venta: tipo_venta, igv: igv, precio: precio, lote: lote, tipo_afectacion_igv: tipo_afectacion_igv, serie: serie, importe: importe, id: id, item: item},
        success: function (data) {
            console.log(data)
            if (data.cantidad) {
                $("#cantidad"+origen).val(data.cantidad);
            }
            resultado_sumar_billing(data);
        }, error: function (data) {
            mostrar_errores(data);
            $("#descuento"+origen).val(data.responseJSON.descuento)
        }
    });
}

function serie_billing_modal(data){ // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    switch (parseInt(data.options.pideserie)) {
        case 0:
            return '<input type="text" id="serie_modal'+data.options.id+'" name="serie_modal'+data.options.id+'" value="' + data.options.serie + '" class="form-control  text-right width-75 serie" readonly disabled>';
            break;
        case 1:
            return '<input type="text" id="serie_modal'+data.options.id+'" name="serie_modal'+data.options.id+'" value="' + data.options.serie + '" class="form-control text-right width-75" onblur="serie_billing_modal2(' + data.id + ')" disabled>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function serie_billing(data){  // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    switch (parseInt(data.options.pideserie)) {
        case 0:
            return '<input type="text" id="serie'+data.id+'" name="serie'+data.id+'" value="' + data.options.serie + '" class="form-control text-right width-75" readonly>';
            break;
        case 1:
            return '<input type="text" id="serie'+data.id+'" name="serie'+data.id+'" value="' + data.options.serie + '" class="form-control text-right width-75" onblur="serie_billing_modal2(' + data.options.id + ', ' +  data.id + ')">';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function serie_billing_modal2(id = '', item = ''){ // se necesita ambos para obtener la serie

    if ($("#id").val() != 0){return false;}

    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle_billing(id) === false ) { return false; }
    let serie = $("#serie" + origen).val(); // input
    let punto_venta = $("#punto_venta").val();
    let fecha_proceso = $("#fecha_proceso").val();
    let producto_id = id;
    let tipo_venta = $("#tipo_venta").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/valida_serie/?serie=" + serie + "&punto_venta=" + punto_venta + "&fecha_proceso=" + fecha_proceso + "&producto_id=" + producto_id + "&tipo_venta=" + tipo_venta,
        success: function (data) {
            success('success', 'La serie es correcta!', 'Información');
        }, error: function (data) {
            mostrar_errores(data);
            $("#serie" + origen).val('');
        }
    });
}

function check_detalle_billing(id){
    let estado = !$("#detalle_modal"+id).prop('checked');
    $("#cantidad_modal"+id).prop('disabled', estado);
    $("#precio_modal"+id).prop('disabled', estado);
    $("#descuento_modal"+id).prop('disabled', estado);
    $("#serie_modal"+id).prop('disabled', estado);
    return $("#detalle_modal"+id).prop('checked');
}

function resultado_sumar_billing(data){

    if ( typeof data.ttotal !== 'undefined' ) {

        table.ajax.reload(); // actualizar docxpagar_detalle
        $("#base").val(data.tbase);
        $("#inafecto").val(data.tinafecto);
        $("#descuento").val(data.tdescuento);
        $("#isc_icbper").val(data.tisc_icbper);
        $("#impuesto").val(data.igv);
        $("#op_gratuita").val(data.tgratuito);
        $("#total").val(data.ttotal);
        $("#importe").val(data.importe);

    }

}

function validar_tipo_doc_para_puntoventa(){

    if (data['mensaje_documento_puntoventa'] !== ''){

        error('error', data['mensaje_documento_puntoventa'], "Error!");
        return false;

    }
    if (data['serie_doc'] !== ''){

        $("#serie_doc").val(data['serie_doc']);

    }
    if (data['tipo_nota_habilitar'] === false){

        $("#tipo_nota").prop("disabled", true).empty();
        $("#label_tipo_nota").text("Tipo Nota de Crédito:");

    }else{

        $("#tipo_nota").prop("disabled", false);
        $("#label_tipo_nota").text(data['tipo_nota_label']);
        llenar_tipo_nota(data['tipo_nota_data']);

    }

    $("#numero_doc").val(data['numero_doc']);

    if  (data['chkalmacen_checked'] !== null) {

        $("#crear_kardex").prop("checked", data['chkalmacen_checked']);

    }

    if  (data['chkalmacen_habilitar'] !== null) {

        $("#crear_kardex").prop("disabled", !data['chkalmacen_checked']);

    }

}

$('#BillingDetails tbody').on('click', '#btnDelete', function () {
    var data = table.row($(this).parents('tr')).data();
    eliminar_docxpagar_detalle(data);
});

function eliminar_docxpagar_detalle(data) {

    if ($("#id").val() != 0){return false;}

    let tipo_venta = $("#tipo_venta").val(); // sumar
    let igv = $("#igv").val(); // sumar
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val(); // sumar

    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "POST",
        url: "/" + variable + "/eliminar_docxpagar_detalle",
        data: {rowId: data.rowId, item: data.id, tipo_venta: tipo_venta, igv: igv, tipo_afectacion_igv},
        success: function (data) {
            resultado_sumar_billing(data);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });

}

$("#serie_doc").blur(function(){
    let tipo_doc = $("#tipo_doc").val();
    let tercero = $("#tercero").val();
    let fecha_proceso = $("#fecha_proceso").val();
    let id = $("#id").val();
    let numero_doc = $("#numero_doc").val();
    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_serie_documento",
        data: {serie: $(this).val(), tipo_doc: tipo_doc, tercero: tercero, fecha_proceso: fecha_proceso, id: id, numero_doc: numero_doc},
        success: function (data) {
            validar_serie_documento(data);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
})

function validar_serie_documento(data){

    if ( data['serie'] !== null ) {

        $("#serie_doc").val(data['serie']);

    }

    if ( data['numero'] !== null ) {

        $("#numero_doc").val(data['numero']);

    }

    if ( data["validadocumento"] !== null ) {

        error('error', data['validadocumento'], "Error!");

    }

    if  ( data['validafecha'] !== null ) {

        error('error', data['validafecha'], "Error!");
        $("#fecha_proceso").val("");

    }

}

$("#fecha_proceso").blur(function(e){

    validarFecha($(this).val());    

    let id = $("#id").val();
    let tipo_doc = $("#tipo_doc").val();
    let serie_doc = $("#serie_doc").val();
    let numero_doc = $("#numero_doc").val();

    let vencimiento = $("#vencimiento").val();// VALIDAR VENCIMIENTO

    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_fecha_documento",
        data: {id: id, tipo_doc: tipo_doc, serie_doc: serie_doc, numero_doc: numero_doc, vencimiento: vencimiento, fecha_proceso: $(this).val()},
        success: function (data) {
            
            if (data['vencimiento'] !== null){ $("#vencimiento").val(data['vencimiento']); }

        },
        error: function (data) {
            mostrar_errores(data)
            $("#fecha_proceso").val("");
        }
    });
});

$("#buscar_nota").click(function(){

    if(validar_buscar_nota() === false){return false;}

    limpiar_datatable(listado_notacreditodebito_cabecera);
    limpiar_datatable(listado_notacreditodebito_detalle);

    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/creditodebito_seleccionado",
        success: function (data) {
            console.log(data);
            if(data){
                $("#creditodebito_serie").val(data['serie']);
                $("#creditodebito_numero").val(data['numero']);
            }

            let fproceso = $("#fecha_proceso").val();
            $("#creditodebito_hasta").val(fproceso).attr("max",fproceso);
            $("#creditodebito_desde").attr("max",fproceso);

            $('#modal_debito_credito').modal('show');

        },
        error: function (data) {
            mostrar_errores(data)
        }
    });

});

function validar_buscar_nota(){

    if ($("#tipo_nota").val() === '' || $("#tipo_nota").val() === null) { warning('warning', 'Debe seleccionar una nota de crédtio o débito', 'Información'); return false}
    if ($("#tercero").val() === '' || $("#tercero").val() === null) { warning('warning', 'Debe seleccionar un cliente', 'Información'); return false}

}

$("#creditodebito_hasta").change(function(){
    let fproceso = $("#fecha_proceso").val();
    $("#creditodebito_hasta").val(fproceso).attr("max",fproceso);
});

function mostrar_credito_debito(){
    let tercero = $("#tercero").val();
    let moneda = $("#moneda").val();
    let desde = $("#creditodebito_desde").val();
    let hasta = $("#creditodebito_hasta").val();
    let serie = $("#creditodebito_serie").val();
    let numero = $("#creditodebito_numero").val();

    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/mostrar_credito_debito",
        data: {tercero: tercero, moneda: moneda, desde: desde, hasta: hasta, serie: serie, numero: numero},
        success: function (data) {
            listado_notacreditodebito_cabecera.init('/billing/listado_notacreditodebito_cabecera');
            listado_notacreditodebito_detalle.init('/billing/listado_notacreditodebito_detalle');
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });

}

function limpiar_datatable(datatable) {
    datatable.init('/billing/limpiar_datatable');
}

function buscar_creditodebito_detalles(documento_id){
    quitar_check_anterior(documento_id);

    let tercero = $("#tercero").val();
    let moneda = $("#moneda").val();
    let desde = $("#creditodebito_desde").val();
    let hasta = $("#creditodebito_hasta").val();
    let serie = $("#creditodebito_serie").val();
    let numero = $("#creditodebito_numero").val();

    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/mostrar_credito_debito_detalle",
        data: {tercero: tercero, moneda: moneda, desde: desde, hasta: hasta, serie: serie, numero: numero, documento_id: documento_id},
        success: function (data) {
            listado_notacreditodebito_detalle.init('/billing/listado_notacreditodebito_detalle');
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });

}

function quitar_check_anterior(documento_id){

    let tabla = $('#listado_notacreditodebito_cabecera').DataTable();
    tabla.rows().every(function () {
        let data = this.node();
        if ($(data).find('input').prop('checked', true)) { // selección anterior
            let input = $(data).find('input').serializeArray()[0]
            let id = input.value; // id selección anterior
            if (documento_id != id){ $("#chk_creditodebito" + id).prop('checked', false); } // solo check en la selección actual
        }
    });

}

function aplicar_notacreditodebito_detalle(item){

    let estado = !$("#chk_creditodebito_detalle"+item).prop('checked');
    $("#notacreditodebito_aplicar"+item).prop('disabled', estado);

    return $("#chk_creditodebito_detalle"+item).prop('checked');

}

function notacreditodebito_cantidad(item, cantidad){
    $("#notacreditodebito_aplicar"+item).val(parseFloat($("#notacreditodebito_aplicar"+item).val()).toFixed(6));
    if ( aplicar_notacreditodebito_detalle(item) === false ) { $("#notacreditodebito_aplicar"+item).val(parseFloat(cantidad).toFixed(6)); return false; }
    validar_cantidad_notacreditodebito_detalle(item, cantidad);
}

function validar_cantidad_notacreditodebito_detalle(item, cantidad){

    let cantidad_input = $("#notacreditodebito_aplicar"+item).val();
    if (cantidad < cantidad_input) { $("#notacreditodebito_aplicar"+item).val(parseFloat(cantidad).toFixed(6))  }

}

function agregar_credito_debito(){
    let data = obtener_checks_seleccionados_creditodebito("listado_notacreditodebito_detalle");
    let moneda = $("#moneda").val();
    let igv = $("#igv").val(); // valor impuesto / sumar
    let tipo_doc = $("#tipo_doc").val();
    let tipo_venta = $("#tipo_venta").val(); // sumar
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val(); // sumar
    let serie_doc = $("#serie_doc").val();
    let tcambio = $("#tcambio").val();

    $.ajax({
        headers: { 'X-CSRF-Token': $('#_token').val() },
        type: "POST",
        url: "/" + variable + "/agregar_creditodebito",
        data: {data: JSON.stringify(data), moneda: moneda, igv: igv, tipo_doc: tipo_doc, tipo_venta: tipo_venta, tipo_afectacion_igv: tipo_afectacion_igv, serie_doc: serie_doc, tcambio: tcambio},
        success: function (data) {
            console.log(data)
            $("#modal_debito_credito").modal("hide");
            resultado_sumar_billing(data);
            resultado_creditodebito(data); // no poner antes para no sobre escribir el importe y para que tome el afecta igv de nota de crédito débito(el datatable carga antes)
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function obtener_checks_seleccionados_creditodebito(id) {
    let tabla = $('#' + id).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        let data = this.node();
        if ($(data).find('input').prop('checked')) {
            det = {
                'ids': $(data).find('input').serializeArray()[0],
                'aplicar': $(data).find('input').serializeArray()[1],
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}


function resultado_creditodebito(data){

    if ( data['chkaplicar'] != null ){

        $("#aplicar_credito_debito").prop("checked", data['chkaplicar']);

    }

    if ( data['documento'] != null) {

        $("#documento_credito_debito").val(data['documento']);        

    }

    if ( data['glosa'] != null ) {

        $("#glosa").val(data['glosa']);

    }

    if( data['importe_creditodebito'] != null){

        $("#importe").val(parseFloat(data['importe_creditodebito']).toFixed(3));

    }

    if ( data['impuesto2_id'] != null) {

        $("#percepcion").val(data['impuesto2_id']).trigger("change");

    }

    if ( data['impuesto_id'] != null){
        
        $("#igv").val(data['impuesto_id']).trigger("change");

    }

    if ( data['serie'] != null) {

        $("#serie_doc").val(data['serie']);

    }

    if ( data['tipoafectaigv_id'] != null){

        $("#tipo_afectacion_igv").val(data['tipoafectaigv_id']).trigger("change");
    }


    if ( data['vendedor_id'] != null){

        $("#vendedor").val(data['vendedor_id']).trigger("change");

    }

}

function centro_costo_detalle(row){

    let selector = "<select id='centro_costo_detalle"+row.id+"' name='centro_costo_detalle"+row.id+"'>";
    selector += "<option value=''>-Seleccionar-</option>";

    let centro_costo = row.options.centro_costo_data;

    centro_costo.forEach(function(data){

        if ( data.id == row.options.centro_costo_id ){
            selector += "<option value='"+data.id+"' selected >" + data.codigo + " | " + data.descripcion + "</option>";
        }else{
            selector += "<option value='"+data.id+"'>" + data.codigo + " | " + data.descripcion + "</option>";
        }

    });

    selector += "</select>";

    return selector;
}

function tipo_afecta_detalle(row){
    
    let selector = "<select id='tipo_afecta_detalle"+row.id+"' name='tipo_afecta_detalle"+row.id+"'>";
    selector += "<option value=''>-Seleccionar-</option>";

    let tipo_afecta = row.options.tipoafectaigv_data;

    tipo_afecta.forEach(function(data){

        if (data.id == $("#tipo_afectacion_igv").val()){
            selector += "<option value='"+data.id+"' selected >" + data.codigo + " | " + data.descripcion + "</option>";
        }else{
            selector += "<option value='"+data.id+"'>" + data.codigo + " | " + data.descripcion + "</option>";
        }

    });

    selector += "</select>";

    return selector;

}

$("#aplicar_credito_debito").click(function(){
    let tipo_doc = $("#tipo_doc").val();
    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_aplicar_credito_debito",
        data: {tipo_doc: tipo_doc},
        success: function (data) {
            console.log(data);
            if (data === '0'){
                $("#aplicar_credito_debito").prop("checked", false);
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});


$("#crear_kardex").click(function(){
    if ($("#id").val() != 0){return false;}
    evaluar_click_crear_kardex();
});
 
function evaluar_click_crear_kardex(){
    
    let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_doc = $("#tipo_doc").val();
    
    if (crear_kardex === true){
        
        $("#ocompra").val("");
        $("#serierem").val("");
        $("#nrorem").val("");

        $.ajax({
            type: "get",
            url: "/" + variable + "/codigo_sunat_documentocom",
            data: {tipo_doc: tipo_doc},
            success: function (data) {
                console.log(data);
                if (data !== '07'){
                    $("#serierem").prop("readonly",false)
                    $("#nrorem").prop("readonly",false)
                }
            },
            error: function (data) {
                mostrar_errores(data);
            }
        });

    }else{
        $("#serierem").val("").prop("readonly",true);
        $("#nrorem").val("").prop("readonly",true);
    }

}
function ver_ingresoalmacen_referencia(event){                       
                           
    if( event.keyCode === 113) {
        
        if ($("#crear_kardex").prop('checked') === true){return false;}

        limpiar_datatable(ingresoalmacen_referencia_detalle);

        let tercero = $("#tercero").val();
        let fecha_proceso = $("#fecha_proceso").val();
    
        ingresoalmacen_referencia_cabecera.init('/billing/ingresoalmacen_referencia_cabecera/?tercero='+ tercero +'&fecha_proceso=' + fecha_proceso);        

        $("#modalIngresoalmacenReferencia").modal("show");

    }
}


function buscar_ingresoalmacen_detalles(documento_id){
    
    let chk_ingresoalmacen_cab = $("#chk_ingresoalmacen_cab" + documento_id).prop('checked');
    let tercero = $("#tercero").val();
    let fecha_proceso = $("#fecha_proceso").val();

    ingresoalmacen_referencia_detalle.init('/billing/ingresoalmacen_referencia_detalle/?tercero='+ tercero +'&fecha_proceso=' + fecha_proceso + '&documento_id=' + documento_id + '&chk_ingresoalmacen_cab=' + chk_ingresoalmacen_cab);

}

function aplicar_ingresoalmacen_detalle(item){

    let estado = !$("#chk_ingresoalmacen_det"+item).prop('checked');

    $("#salidaalmacen_recibir"+item).prop('disabled', estado);

    return $("#chk_creditodebito_detalle"+item).prop('checked');

}

function salidaalmacen_recibir(item, cantidad){

    $("#salidaalmacen_recibir"+item).val(parseFloat($("#salidaalmacen_recibir"+item).val()).toFixed(6));
    if ( aplicar_ingresoalmacen_detalle(item) === false ) { $("#salidaalmacen_recibir"+item).val(parseFloat(cantidad).toFixed(6)); return false; }
    validar_salidaalmacen_detalle(item, cantidad);    

}

function validar_salidaalmacen_detalle(item, cantidad){

    let cantidad_input = $("#salidaalmacen_recibir"+item).val();
    if (cantidad < cantidad_input) { $("#salidaalmacen_recibir"+item).val(parseFloat(cantidad).toFixed(6))  }

}

function agregar_salidaalmacen_referencia(){

    let tipo_venta = $("#tipo_venta").val(); // sumar - getctaventapdto
    let igv = $("#igv").val(); // sumar
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val(); // sumar

    let data = obtener_checks_seleccionados_creditodebito("ingresoalmacen_referencia_detalle");

    $.ajax({
        type: "get",
        url: "/" + variable + "/agregar_salidaalmacen_referencia",
        data: {tipo_venta: tipo_venta, igv: igv, tipo_afectacion_igv: tipo_afectacion_igv, data: JSON.stringify(data)},
        success: function (data) {

            resultado_sumar_billing(data);
            $("#modalIngresoalmacenReferencia").modal("hide");

            if (data['txtocompra']){

                $("#ocompra").val(data['txtocompra']);
                $("#serierem").val(data['txtserierem']);
                $("#nrorem").val(data['txtnrorem']);
                listado_guias_remision.init();

            }else{

                $("#ocompra").val("");
                $("#serierem").val("");
                $("#nrorem").val("");

            }
            

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

$("#serierem").blur(function(e){

    if ($("#id").val() != 0){return false;}

    let serierem = $(this).val();
    let nrorem = $("#nrorem").val() 
    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_serierem",
        data: {serierem: serierem, nrorem: nrorem},
        success: function (data) {
            if ( data !== null ){
                $("#serierem").val(data['serierem']);
                $("#nrorem").val(data['numerorem']);
            }else{
                $("#nrorem").val("");
            }
        },
        error: function (data) {
            mostrar_errores(data);
            $("#serierem").val("");
            $("#nrorem").val("");
        }
    });
});

$("#nrorem").blur(function(e){

    if ($("#id").val() != 0){return false;}

    let serierem = $("#serierem").val();
    let nrorem = $(this).val()
    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_nrorem",
        data: {serierem: serierem, nrorem: nrorem},
        success: function (data) {
            if ( data !== null ){
                $("#nrorem").val(data['numerorem']);
            } else {
                $("#serierem").val("");                              
            }
        },
        error: function (data) {
            mostrar_errores(data);
            $("#serierem").val("");
            $("#nrorem").val("");
        }
    });     

});


// FALTA VALIDAR MONEDA CUANDO VIENE POR REFERENCIA VER: cntmoneda1-txtcodigo, método Valid 

$("#valor_referencial").blur(function(e){

    if( $(this).val().length === 0 ){ $(this).val(""); return false;}
    
    $(this).val(parseFloat($(this).val()).toFixed(2)); 

    validar_detraccion();

    validar_detraccion_importe();

});

$("#numero_detraccion").blur(function(){validar_detraccion();})
$("#fecha_detraccion").blur(function(){validar_detraccion();})
$("#importe_detraccion").blur(function(){validar_detraccion();})

function validar_detraccion(){
    let tipo_afectacion_igv = $("#tipo_afectacion_igv").val();
    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_detraccion_por_tipoafecta",
        data: {tipo_afectacion_igv: tipo_afectacion_igv},
        success: function (data) {
            
            if ( data == 0 ){
                limpiar_detraccion();
            }
        },
        error: function (data) {
            mostrar_errores(data);
            $("#detraccion").val("").trigger('change');
        }
    });         
}

function limpiar_detraccion(){

    $("#valor_referencial").val(0);
    $("#numero_detraccion").val("");
    $("#fecha_detraccion").val("");
    $("#importe_detraccion").val(0);
    $("#detraccion").val("").trigger('change');      
    $("#factor_detraccion").val("");   

}

$("#fecha_recepcion").blur(function(e){
    
    if ($("#vendedor").val() == "" || $("#vendedor").val() == null){ $(this).val(""); return false; }

    if ($(this).val().length === 0){ return false;}
    let id = $("#condicion_pago").val()
    if(id !== '' && $(this).val() !== ''){
        $.ajax({
            type: "get",
            url: "/" + variable + "/condicion_pago",
            data: {id: id, fecha: $(this).val()},
            success: function (data) {
                $('#vencimiento').val(data);
            },
        });
    }

});

$("#formapago").change(function(e){
    
    let importe_detracion = $("#importe_detracion").val();
    let moneda = $("#moneda").val();
    let total = $("#total").val();
    let tcambio = $("#tcambio").val();
    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_formapago",
        data: {formapago: $(this).val(), importe_detracion: importe_detracion, moneda: moneda, total: total, tcambio: tcambio},
        success: function (data) {
            validar_formapago(data);
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });      

})

function validar_formapago(data){

    if ( data['txtpago_estado'] !== null ){

        if (data['txtpago_estado'] === 'bloquear'){

            $("#importefp").prop("readonly", true);

        }

        if (data['txtpago_estado'] === 'desbloquear'){

            $("#importefp").prop("readonly", false);

        }                

    }

    if (data["banco"]){

        $("#bancofp").append("<option value='"+data["banco"]["id"]+"' selected>"+ data["banco"]["codigo"] + " | " + data["banco"]["descripcion"] +"</option>")

    }

    if (data["moneda"]){

        $("#monedafp").append("<option value='"+data["moneda"]["id"]+"' selected>"+ data["moneda"]["codigo"] + " | " + data["moneda"]["descripcion"] +"</option>")

    }

    $("#importefp").val(data["txtpago"]);

}

$("#importefp").blur(function(e){

    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_importefp",
        data: {importefp: $(this).val()},
        success: function (data) {
            
            if(data){ $("#importefp").val(data); }

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });         

});

$("#nrochequefp").blur(function(e){

    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_nrochequefp",
        data: {nrochequefp: $(this).val()},
        success: function (data) {
            
            if(data === null){ $("#nrochequefp").val("").prop("readonly", true); }

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

});

$("#detraccion").change(function(e){

    if($("#detraccion").val() !== ""){validar_detraccion();} 
    
    if($("#detraccion").val() !== ""){validar_detraccion_importe();}

});


function validar_detraccion_importe(){

    if($("#detraccion").val() == ""){return false;}


    let moneda = $("#moneda").val(); 
    let total = $("#total").val();
    let tcambio = $("#tcambio").val();
    let tcmoneda = $("#tcmoneda").val();
    let valor_referencial = $("#valor_referencial").val();
    let detraccion = $("#detraccion").val();  

    $.ajax({
        type: "get",
        url: "/" + variable + "/validar_detraccion",
        data: {detraccion: detraccion, moneda: moneda, total: total, tcambio: tcambio, tcmoneda: tcmoneda, valor_referencial: valor_referencial},
        success: function (data) {
            
            if(data['total_detraccion']){

                $("#importe_detraccion").val(parseFloat(data['total_detraccion']).toFixed(2));

            } 

            $("#factor_detraccion").val(data['valor']);

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

$("#importe_detracion").blur(function(e){

    if( $(this).val().length === 0 ){ $(this).val(""); return false;}

    $("#importe_detracion").val(parseFloat($(this).val()).toFixed(2));

})

function agregar_tercero_modales(data){

    $("#historial_aplicacion_tercero").empty().append("<option value="+ data['id'] +">" + data['codigo'] + ' | ' + data['descripcion'] + "</option>")
    $("#referencia_nombre").val(data['descripcion']);
}

$("#historial_btn_aplicar").click(function(){

    if(validar_modal_aplicar() === false){return false;}


    llenarSelect('historial_aplicacion_tercero', '/customers/buscar_tercero');

    let historial_aplicacion_tercero = $("#historial_aplicacion_tercero").val();
    let fecha_proceso = $("#fecha_proceso").val();
    let tcambio = $("#tcambio").val();
    let moneda = $("#moneda").val();
    let total = $("#total").val();

    $("#total_documentos_aplicar").val("");
 

    $.ajax({
        type: "get",
        url: "/" + variable + "/listado_documentos_aplicar",
        data: {historial_aplicacion_tercero: historial_aplicacion_tercero, fecha_proceso: fecha_proceso, tcambio: tcambio, moneda: moneda, total: total},
        success: function (data) {

            if (data){ $("#total_documentos_aplicar").val(data['total_documentos']); }
            
            listado_documentos_aplicar.init('/billing/obtener_listado_documentos_aplicar');
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

    $("#modalHistorialAplicacion").modal("show");

});
$("#historial_btn_aplicar").dblclick(function(e){ e.preventDefault(); });


function validar_modal_aplicar(){

    if ($("#tercero").val() === '' || $("#tercero").val() === null) { warning('warning', 'Debe seleccionar un cliente', 'Información'); return false}
    if ($("#tcambio").val() === '' || $("#tcambio").val() == 0) { warning('warning', 'Se necesita el tipo de cambio', 'Información'); return false}
    if ($("#moneda").val() === '') { warning('warning', 'Ingrese un tipo de moneda', 'Información'); return false}

}

function documento_aplicar_seleccionado(id){

    let seleccionado = validar_documento_aplicar_seleccionado(id);

    let total = $("#total").val();
    let total_documentos_aplicar = $("#total_documentos_aplicar").val();
    
    $.ajax({
        type: "get",
        url: "/" + variable + "/documento_aplicar_seleccionado",
        data: {id: id, total: total, seleccionado: seleccionado, total_documentos_aplicar: total_documentos_aplicar},
        success: function (data) {

            $("#total_documentos_aplicar").val(data['total_documentos']);
            $("#documento_aplicar"+id).val(data['aplicar_documento']);

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });


}


function validar_documento_aplicar_seleccionado(id){

    let estado = !$("#chk_documento_aplicar"+id).prop('checked');
    $("#documento_aplicar"+id).prop('disabled', estado);

    return $("#chk_documento_aplicar"+id).prop('checked');

}

function cambiar_saldo_aplicar_documentos(id, aplicar){

    $("#documento_aplicar"+id).val(parseFloat($("#documento_aplicar"+id).val()).toFixed(2));

    /*let cantidad_input = $("#documento_aplicar"+id).val();
    if (aplicar < cantidad_input) {$("#documento_aplicar"+id).val(parseFloat(aplicar).toFixed(2))  }*/

    cantidad_input = $("#documento_aplicar"+id).val();

    let total = $("#total").val();

    $.ajax({
        type: "get",
        url: "/" + variable + "/cambiar_saldo_aplicar_documentos",
        data: {id: id, cantidad_input: cantidad_input, total: total},
        success: function (data) {

            $("#total_documentos_aplicar").val(data['total_documentos']);
            $("#documento_aplicar"+id).val(data['aplicar_documento']);

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function agregar_documentos_aplicar(){

    $.ajax({
        type: "get",
        url: "/" + variable + "/agregar_documentos_aplicar",
        success: function (data) {
            if(data){

                $("#historial_documento").val(data['documento']);
                $("#historial_importe").val(data['importe']);
                $("#chk_historial_aplicar").prop("checked", true);                

            }

            $("#modalHistorialAplicacion").modal("hide");

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function eliminar_detraccion_por_tipoafecta(){

    let tipo_afectacion_igv =  $("#tipo_afectacion_igv").val();

    $.ajax({
        type: "get",
        url: "/" + variable + "/eliminar_detraccion_por_tipoafecta",
        data: {tipo_afectacion_igv: tipo_afectacion_igv},
        success: function (data) {
            
            if ( data == 0 ){
                limpiar_detraccion();
            }

        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function guardar() {
    if(validarDetalles()){
        store('loading');
    }
}
function actualizar() {
    if(validarDetalles()){
        update();
    }
}

function anula() {
    anular(1);
}