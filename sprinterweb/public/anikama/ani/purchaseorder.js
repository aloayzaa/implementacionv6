$(document).ready(function () {
    $("#cbo_unidad_negocio").focus();
    validarFecha();
    //$('#txt_fecha').val(new Date().toDateInputValue());
    $('#txt_hasta').val(new Date().toDateInputValue());
    $('#txt_fecha_entrega').val($('#txt_fecha').val());

    if($("#proceso").val() == 'crea'){
        var fecha_tc = $("#txt_fecha").val();
        consultar_tipocambio(fecha_tc);
    }

    if ($('#proceso').val() == 'edit'){

        var array = $("#cbo_razon_social option:selected").text();
        ruc = array.split('|');
        $("#txt_ruc").val(ruc[0]);
        volumen_cantidad_front('');

        var ordencompra_id = $("#id").val();
        var ruta_referencia_pedido_almacen = $("#ruta_pedidos_de_ordencompra").val();
        var ruta_ingreso_almacen = $("#tabla_ingreso_almacen").val();
        var ruta_provisiones = $("#tabla_provisiones").val();

        TableWarehousePurchaseOrder.init( ruta_referencia_pedido_almacen + '?ordencompra_id=' + ordencompra_id);
        depositOrderPurchase.init( ruta_ingreso_almacen + '?ordencompra_id=' + ordencompra_id);
        provisionesOrdenCompra.init( ruta_provisiones + '?ordencompra_id=' + ordencompra_id);
        sumar_footer(ordencompra_id);
    }
    llenarSelect("cbo_razon_social",'/customers/buscar_tercero');
});

Date.prototype.toDateInputValue = (function () {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0, 10);
});
/*
$("#txt_fecha").blur(function () {
    consultar_tipo_cambio($("#txt_fecha").val());
    $('#txt_fecha_entrega').val($('#txt_fecha').val());
});*/
$("#cbo_razon_social").change(function () {
    var array = $("#cbo_razon_social option:selected").text();
    ruc = array.split('|');
    $("#txt_ruc").val(ruc[0]);
    contacto_depositar($("#cbo_razon_social").val());

});
$("#cbo_moneda_orden").change(function () {
    $("#txt_tc_orden").val('0.0000');
});
$("#txt_serie").blur(function () {
    var serie = $("#txt_serie").val();
    if (serie.length < 5) {
        serieenv = ceros_izquierda(5, serie);
        $("#txt_serie").val(serieenv);
    }
});
$("#cbo_punto_emision").change(function () {
    var punto_emision = $("#cbo_punto_emision").val();
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/datos_adicionales",
        dataType: "json",
        data: {punto_emision: punto_emision},
        success: function (data) {
            $("#txt_lugar_entrega").val(data.direccionalmacen);

        }
    });

});
var table = $('#detalle_documento').DataTable({
    serverSide: true,
    ajax: '/purchaseorder/listar_detalle',
    destroy: true,
    scrollX: true,
    columnDefs: [
        {
            "targets": 1,
            "visible": false,
            "searchable": false
        },
    ],
    "columns": [
        {
            "sName": "Index",
            "render": function (data, type, row, meta) {
                return meta.row + 1; // This contains the row index
            },
            "orderable": "false",
        },
        {data: 'options.item'},
        {data: 'options.producto_codigo', "className": "text-center",
            "render": function ( data, type, full, meta ) {
                return `<a onclick="producto(${full.options.producto_id})">${data == null ? '' : data}</a>`;
            },
        },
        {data: 'options.producto_descripcion', "className": "text-center"},
        {data: 'options.umedida_codigo', "className": "text-center"},
        {class: "text-center",
            data: function (row) {
                return parseFloat(row.options.cantidad).toFixed(3);
            }
        },
        {class: "text-center",
            data: function (row) {
                return parseFloat(row.options.valor).toFixed(6);
            }
        },
        {data: 'options.descuento', "className": "text-center"},
        {class: "text-center",
            data: function (row) {
                return parseFloat(row.options.subtotal).toFixed(2);
            }
        },
        {data: 'options.volumen', "className": "text-center"},
        {data: 'options.glosa', "className": "text-center"},

        {
            data: function (row) {
                return '<a id="btnEdit" class="btn"><span class="fa fa-edit fa-2x"></span></a>'
            },
            "orderable": "false",
        },
        {
            data: function (row) {
                return '<a  id="btnDelete"  class="btn"><span style="color:red" class="fa fa-trash-o fa-2x"></a>'
            },
            "orderable": "false",
        },
    ],
    order: [[1, 'asc']],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});
$("#chk_incluye_impuestos").click(function () {
    totalizar();
});
$("#cbo_igv_tributos").change(function () {
    var codigo = $("#cbo_igv_tributos option:selected").data('codigo');
    if(typeof codigo === "undefined"){
        $("#cbo_percepcion_tributos").prop('disabled',true);
    }else if(codigo != '99'){
        $("#cbo_percepcion_tributos").prop('disabled',false);
    }else{
        $("#cbo_percepcion_tributos").prop('disabled',true);
    }
    totalizar_impuesto(1);
});

$("#cbo_percepcion_tributos").change(function () {
    totalizar_impuesto(3);
});

function totalizar_impuesto(accion) {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/totalizar",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_base").val(data.txt_base);
            $("#txt_inafecto").val(data.txt_inafecto);
            $("#txt_num1").val(data.txt_num1);
            ejecutar_accion(accion);
            //table.ajax.reload();
        }
    });
}

function totalizar() {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/totalizar",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_base").val(data.txt_base);
            $("#txt_inafecto").val(data.txt_inafecto);
            $("#txt_num1").val(data.txt_num1);
            ejecutar_accion(2);
            table.ajax.reload();
            //sumar();

        }
    });
}

function sumar() {
    var variable = $("#var").val();
    var form = $("#frm_generales");

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/sumar",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_total").val(data.txt_total);
            $("#txt_impuesto").val(data.txt_impuesto);
        }
    });
}

function ejecutar_accion(accion) {
    // 1 = cbo_igv_tributos
    // 2 = flujo habitual antes de sumar validar los combos
    switch (accion) {
        case 1:
            cbo_igv_tributos();
            break;
        case 2:
            impuesto();
            break;
        case 3:
            cbo_percepcion_tributos();
            break;
        default:
            break;
    }
}
function cbo_igv_tributos() {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/cambiar_impuesto",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_impuesto").val(data.txt_impuesto);
            totalizar();
        }
    });
}
function cbo_percepcion_tributos() {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/cambiar_impuesto2",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_impuesto2").val(data.txt_impuesto2);
            totalizar();
        }
    });
}

function impuesto() {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/cambiar_impuesto",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_impuesto").val(data.txt_impuesto);
            impuesto_two();
        }
    });
}

function impuesto_two() {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/cambiar_impuesto2",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_impuesto2").val(data.txt_impuesto2);
            sumar();
        }
    });
}
$('#btn_nueva_linea').click(function () {
    if (valida_modal() == false){return false;}
    $('#myModalDetalleDocumento').modal('show');
    limpia_modal();
    llenarSelect('cbo_producto','/purchaseorder/tipo_producto?tipoproducto='+$("#cbo_tipo_producto").val());
    var descuento_base = $("#txt_dscto_base").val();
    $("#txt_descuento").val(descuento_base);

});
$('#cbo_producto').on('select2:select', function (e) {
    var data = e.params.data;

    $("#txt_umedida").empty().prop('disabled',false);
    if(data.otros["ucompra_id"] != data.otros["umedida_id"]){
        $("#txt_umedida").append('<option value="'+data.otros["ucompra_id"]+'" selected>'+data.otros['ucompra_codigo'] + '</option><option value="'+data.otros["umedida_id"]+'">'+ data.otros['ukardex_codigo'] + '</option>');
    }else{
        $("#txt_umedida").append('<option value="'+data.otros["ucompra_id"]+'" selected>'+data.otros['ucompra_codigo'] + '</option>');
    }

    $("#txt_volumen").val(data.otros['volumen']);
    historial_precio(data.id,'');
});
$('#cbo_producto_editar').on('select2:select', function (e) {
    var data = e.params.data;

    $("#txt_umedida_editar").empty();
    if(data.otros["ucompra_id"] != data.otros["umedida_id"]){
        $("#txt_umedida_editar").append('<option value="'+data.otros["ucompra_id"]+'" selected>'+data.otros['ucompra_codigo'] + '</option><option value="'+data.otros["umedida_id"]+'">'+ data.otros['ukardex_codigo'] + '</option>');
    }else{
        $("#txt_umedida_editar").append('<option value="'+data.otros["ucompra_id"]+'" selected>'+data.otros['ucompra_codigo'] + '</option>');
    }

    $("#txt_volumen_editar").val(data.otros['volumen']);
    historial_precio(data.id,'_editar');
});
function limpia_modal() {
    $('#id_cart').val(0);
    $('#cbo_producto').val("").change();
    $('#txt_umedida').empty().prop('disabled',true);
    $("#txt_umedida_id").val("");
    $('#txt_cantidad').val("");
    $('#txt_costo_unitario').val("");
    $('#txt_descuento').val("");
    $('#txt_importe_producto').val("").prop('readonly',false);
    $('#txt_volumen').val("");
    $('#txt_glosa').val("");
    $('#txt_valor').val("");
    $('#txt_subtotal').val("");
    $('#txt_importe_p').val("");
}

$('#detalle_documento tbody').on('click', '#btnEdit', function () {
        var data = table.row($(this).parents('tr')).data();
        editar(data);
    }
);

$('#detalle_documento tbody').on('click', '#btnDelete', function () {
        var data = table.row($(this).parents('tr')).data();
        destroy_itemm(data);
    }
);

//-------------------------------------------------------crear

function agregar_detalle_documento() {
var variable = $("#var").val();
var form_detail = $("#form-add-detail");
var form_general = $("#frm_generales");
var form = form_detail.serialize() + form_general.serialize();

$.ajax({
    headers: {
        'X-CSRF-Token': $('#_token').val(),
    },
    type: "POST",
    url: "/" + variable + "/agregar_detalle_documento",
    data: form,
    success: function (data) {
        $("#txt_total_volumen").val(data.total_volumen);
        $("#txt_total_cantidad").val(data.total_cantidad);
        totalizar();
        $('#myModalDetalleDocumento').modal('hide');
        table.ajax.reload();
        success('success', 'Detalle agregado correctamente!', 'Información');
    },
    error: function (data) {
        mostrar_errores(data)
    }
});
}

$("#txt_cantidad").blur(function () {
var subtotal = $("#txt_importe_producto").val();
if ($("#txt_cantidad").val() > 0) {
    if ($("#txt_costo_unitario").val() > 0) {

        subtotal = $("#txt_costo_unitario").val() * $("#txt_cantidad").val();
        $("#txt_importe_producto").val(subtotal);
        $("#txt_subtotal").val(subtotal);
    } else {

        var valor = subtotal / $("#txt_cantidad").val();
        $("#txt_costo_unitario").val(parseFloat(valor).toFixed(6));
        $("#txt_valor").val(valor);

    }
    subtotal_con_descuento(subtotal);
}
});
$("#txt_costo_unitario").blur(function () {
var lnprecio = $("#txt_costo_unitario").val();
$("#txt_valor").val(lnprecio);
if (lnprecio != 0){$("#txt_importe_producto").prop('readonly',true);}

if ($("#txt_cantidad").val() > 0) {
    var lnSubtotal = lnprecio * $("#txt_cantidad").val();
    subtotal_con_descuento(lnSubtotal);
}
});

$("#txt_descuento").blur(function () {
    var descuento = $("#txt_descuento").val();
    if ((descuento.indexOf("-") + 1) > 0) {
        error('error', "Carácter inválido", "Error!");
    }
    if ($("#txt_cantidad").val() > 0 && $("#txt_costo_unitario").val() > 0) {
        var lnSubtotal = $("#txt_costo_unitario").val() * $("#txt_cantidad").val();
        subtotal_con_descuento(lnSubtotal)

    }
});

function subtotal_con_descuento(subtotal) {

    if ($("#txt_descuento").val() != '' || $("#txt_descuento").val() > 0) {
        var lc = '';
        var lcDscto = '';
        var descuento = $("#txt_descuento").val();
        descuento = descuento.replace(/ /g, "").trim();
        var longitud_descuento = descuento.length;
        for (var i = 0; i < longitud_descuento; i++) {
            lc = descuento.substr(i, 1);
            if (lc != '+') {
                lcDscto = lcDscto.concat(lc);
            }
            if (lc == '+' || i == (longitud_descuento - 1)) {
                var lnDscto = 1 - (Number(lcDscto) / 100);
                lcDscto = '';
                subtotal = subtotal * lnDscto;
            }
        }
    }

    $("#txt_subtotal").val(parseFloat(subtotal).toFixed(2));
    $("#txt_importe_producto").val(parseFloat(subtotal).toFixed(2));
}

$("#txt_cantidad").change(function () {
    var valor = $("#txt_cantidad").val();
    $("#txt_cantidad").val(parseFloat(valor).toFixed(3));
});
$("#txt_costo_unitario").change(function () {
    var valor = $("#txt_costo_unitario").val();
    $("#txt_costo_unitario").val(parseFloat(valor).toFixed(6));
});
$("#txt_volumen").change(function () {
    var valor = $("#txt_volumen").val();
    $("#txt_volumen").val(parseFloat(valor).toFixed(6));
});

//-------------------------------------------------------editar

function editar(data) {
    $('#myModalDetalleDocumentoEditar').modal('show');
    llenarSelect('cbo_producto_editar','/purchaseorder/tipo_producto?tipoproducto='+$("#cbo_tipo_producto").val());

    $('#modal_edit_row_id').val(data.rowId);
    $('#cbo_producto_editar').append('<option value= ' + data.options.producto_id +
      ' selected >' + data.options.producto_codigo + ' | ' + data.options.producto_descripcion +
        '</option>');
    $("#txt_umedida_editar").empty();
    $("#txt_umedida_editar").append('<option value="'+data.options.umedida_id+'" selected>'+data.options.umedida_codigo +'</option>');

    $('#txt_cantidad_editar').val(data.options.cantidad);
    $('#txt_costo_unitario_editar').val(parseFloat(data.options.valor).toFixed(6));
    $('#txt_descuento_editar').val(data.options.descuento);
    $('#txt_importe_producto_editar').val(data.options.subtotal).prop('readonly',false);
    $('#txt_volumen_editar').val(data.options.volumen);
    $('#txt_glosa_editar').val(data.options.glosa);
    $('#txt_valor_editar').val(data.options.valor);
    $('#txt_subtotal_editar').val(data.options.subtotal);
    if($('#txt_costo_unitario_editar').val() != 0 ){$('#txt_importe_producto_editar').prop('readonly',true);}
    umedida_producto(data.options.producto_id,data.options.umedida_id);
}

function editar_detalle_documento() {
var variable = $("#var").val();
var form_detail = $('#form-edit-item');
var form_general = $("#frm_generales");
var form = form_detail.serialize() + form_general.serialize();
$.ajax({
    headers: {
        'X-CSRF-Token': $('#_token').val(),
    },
    type: "POST",
    url: "/" + variable + "/editar_detalle_documento",
    data: form,
    success: function (data) {
        $("#txt_total_volumen").val(data.total_volumen);
        $("#txt_total_cantidad").val(data.total_cantidad);
        totalizar();
        $('#myModalDetalleDocumentoEditar').modal('hide');
        $('#detalle_documento').DataTable().ajax.reload();
        success('success', 'Detalle actualizado correctamente!', 'Información');
    },
    error: function (data) {
        mostrar_errores(data)
    }
});
}


$("#txt_cantidad_editar").blur(function () {
var subtotal = $("#txt_importe_producto_editar").val();
if ($("#txt_cantidad_editar").val() > 0) {
    if ($("#txt_costo_unitario_editar").val() > 0) {

        subtotal = $("#txt_costo_unitario_editar").val() * $("#txt_cantidad_editar").val();
        $("#txt_importe_producto_editar").val(subtotal);
        $("#txt_subtotal_editar").val(subtotal);
    } else {

        var valor = subtotal / $("#txt_cantidad_editar").val();
        $("#txt_costo_unitario_editar").val(parseFloat(valor).toFixed(6));
        $("#txt_valor_editar").val(valor);

    }

    subtotal_con_descuento_editar(subtotal);
}
});
$("#txt_costo_unitario_editar").blur(function () {
    var lnprecio = $("#txt_costo_unitario_editar").val();
    $("#txt_valor_editar").val(lnprecio);
    if (lnprecio != 0) {$("#txt_importe_producto_editar").prop('readonly',true);}

    if ($("#txt_cantidad_editar").val() > 0) {
        var lnSubtotal = lnprecio * $("#txt_cantidad_editar").val();
        subtotal_con_descuento_editar(lnSubtotal);
    }
});

$("#txt_descuento_editar").blur(function () {
    var descuento = $("#txt_descuento_editar").val();
    if ((descuento.indexOf("-") + 1) > 0) {
        error('error', "Carácter inválido", "Error!");
    }
    if ($("#txt_cantidad_editar").val() > 0 && $("#txt_costo_unitario_editar").val() > 0) {
        var lnSubtotal = $("#txt_costo_unitario_editar").val() * $("#txt_cantidad_editar").val();
        subtotal_con_descuento_editar(lnSubtotal)

    }
});

function subtotal_con_descuento_editar(subtotal) {
    if ($("#txt_descuento_editar").val() != '' || $("#txt_descuento_editar").val() > 0) {
        var lc = '';
        var lcDscto = '';
        var descuento = $("#txt_descuento_editar").val();
        descuento = descuento.replace(/ /g, "").trim();
        var longitud_descuento = descuento.length;
        for (var i = 0; i < longitud_descuento; i++) {
            lc = descuento.substr(i, 1);
            if (lc != '+') {
                lcDscto = lcDscto.concat(lc);
            }
            if (lc == '+' || i == (longitud_descuento - 1)) {
                var lnDscto = 1 - (Number(lcDscto) / 100);
                lcDscto = '';
                subtotal = subtotal * lnDscto;
            }
        }
    }
    $("#txt_subtotal_editar").val(parseFloat(subtotal).toFixed(2));
    $("#txt_importe_producto_editar").val(parseFloat(subtotal).toFixed(2));
}

$("#txt_cantidad_editar").change(function () {
    var valor = $("#txt_cantidad_editar").val();
    $("#txt_cantidad_editar").val(parseFloat(valor).toFixed(3));
});
$("#txt_costo_unitario_editar").change(function () {
    var valor = $("#txt_costo_unitario_editar").val();
    $("#txt_costo_unitario_editar").val(parseFloat(valor).toFixed(6));
});
$("#txt_volumen_editar").change(function () {
    var valor = $("#txt_volumen_editar").val();
    $("#txt_volumen_editar").val(parseFloat(valor).toFixed(6));
});

//-------------------------------------------------------eliminar
function destroy_itemm(data) {
var variable = $("#var").val();

$.ajax({
    headers: {
        'X-CSRF-Token': $('#_token').val(),
    },
    type: "POST",
    url: "/" + variable + "/eliminar_detalle_documento",
    data: {rowId: data.rowId, item: data.options.item},
    success: function (data) {
        $("#txt_total_volumen").val(data.total_volumen);
        $("#txt_total_cantidad").val(data.total_cantidad);
        totalizar();
        table.ajax.reload();
        success('success', 'Detalle eliminado correctamente!', 'Información');
    },
    error: function (data) {
        mostrar_errores(data)
    }
});
}

//----------------------------------------------------------------
function volumen_cantidad_front(data) {

var variable = $("#var").val();

$.ajax({
    headers: {
        'X-CSRF-Token': $('#_token').val(),
    },
    type: "POST",
    url: "/" + variable + "/volumen_cantidad_front",
    success: function (data) {
        $("#txt_total_volumen").val(data.total_volumen);
        $("#txt_total_cantidad").val(data.total_cantidad);
    },
    error: function (data) {
        mostrar_errores(data)
    }
});
}
//-----------------------------pedidos---------------------------------------
$("#btn_pedidos").click(function(){
    var fproceso = $("#txt_fecha").val();
    $("#txt_hasta").val(fproceso).attr("max",fproceso);
    $('#myModalPedidos').modal('show');
});
$("#btn_filtrar").click(function(){
    var fecha_hasta = $('#txt_hasta').val();
    var id = $('#id').val();
    tablePurcharseOrderWarehouse.init(this.value + '?fecha_hasta=' + fecha_hasta + '&id=' + id);
});
function obtener_detalles(id){
    var pedido_id = id;

    if($("#cab_doc_id"+id).prop('checked')){
        var accion = 'agregar';
        var ids_check_det = obtener_checks_seleccionados('det_documento');
    }else{
        var accion = 'borrar';
        var ids_cab =  obtener_checks_seleccionados('cab_documento');
        var ids_check_det = obtener_checks_seleccionados('det_documento');
    }

    var fecha_hasta = $('#txt_hasta').val();
    var ruta = $('#btn_detalles').val();

    tablePurcharseOrderWarehouseDetail.init( ruta + '?fecha_hasta=' + fecha_hasta + '&id=' + pedido_id + '&accion=' + accion + '&ids_cab=' + JSON.stringify(ids_cab) + "&ids_check_det=" + JSON.stringify(ids_check_det));

}
function obtener_checks_seleccionados(tabla){
    tabla = $('#'+tabla).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function (rowIdx, tableLoop, rowLoop){
       var data = this.node();
        if($(data).find('input').prop('checked')){
            det = {
                'ids': $(data).find('input').serializeArray()[0],
                'pedido': $(data).find('input').serializeArray()[1]
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}
function pedido_detalles_cantidades(idelemento){
    if($("#iditem"+idelemento).prop('checked')){
        $("#detalle_pedido"+idelemento).prop('readonly', false);
        $("#detalle_pedido"+idelemento).val($("#cantidad"+idelemento).text());
    }else{
        $("#detalle_pedido"+idelemento).val('0.00000');
        $("#detalle_pedido"+idelemento).prop('readonly', true);
    }
}
function agregar_pedidos(){
    var variable = $("#var").val();
    var data = obtener_checks_seleccionados('det_documento');
    var frm = $("#frm_generales")
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/agregar_pedidos",
        data: frm.serialize()+"&data="+JSON.stringify(data),
        success: function (data) {
            $("#txt_total_volumen").val(data.total_volumen);
            $("#txt_total_cantidad").val(data.total_cantidad);
            totalizar();
            $('#myModalPedidos').modal('hide');
            table.ajax.reload();
            success('success', 'Detalle agregado correctamente!', 'Información');
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}
$("#cbo_tipo_detraccion").change(function(){
    comprobar_detraccion();
});
$("#txt_valor_referencial").blur(function(){
    comprobar_detraccion();
});
function comprobar_detraccion(){
    var valor_detraccion = $("#cbo_tipo_detraccion option:selected").data('valor');
    $("#txt_porcentaje_tipo_detraccion").val(valor_detraccion);
    var total = $("#txt_total").val();
    var valor_referencial = $("#txt_valor_referencial").val();
    importe = (total > valor_referencial) ? (total * valor_detraccion)/100 : (valor_referencial * valor_detraccion)/100;
    $("#txt_importe").val(importe);
}
function contacto_depositar(tercero_id){
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/contacto_depositar",
        data:{tercero_id:tercero_id},
        success: function (data) {
            $("#cbo_contacto_otros_datos").empty();
            $("#cbo_depositar_otros_datos").empty();

            $.each(data.tercero_cuenta, function (key, registro) {
                $('#cbo_depositar_otros_datos').append('<option value="' + registro.banco_codigo + ' ' + registro.moneda_simbolo + ' ' + registro.cuenta +
                    '">' + registro.banco_codigo + ' ' + registro.moneda_simbolo + ' ' + registro.cuenta +
                    '</option>');
            });

            $.each(data.tercero_contacto, function (key, registro) {
                $('#cbo_contacto_otros_datos').append('<option value=' + registro.nrodocidentidad +
                    '>' + registro.nrodocidentidad + " | " + registro.nombre +
                    '</option>');
            });
        },
        error: function (data) {
        }
    });
}
function sumar_footer(id){
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/sumar_footer_referencias",
        data:{ordencompra_id:id},
        success: function (data) {
            $('#txt_total_in').val(data.sum_ingresoalmacen_n);
            $('#txt_total_ie').val(data.sum_ingresoalmacen_e);
            $('#txt_total_pn').val(data.sum_provision_n);
            $('#txt_total_pe').val(data.sum_provision_e);
        },
        error: function (data) {
        }
    });
}
function archivar(){
    var respuesta = confirm('El cambio o proceso solicitado va a iniciarse. Está usted seguro?');
    if(respuesta){
        archivar_env();
    }
}
function archivar_env(){
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "GET",
        url: "/" + variable + "/archivar",
        data:{id:$("#id").val()},
        success: function (data) {
            $('#estadito').text(data.estado_tabla);
        },
        error: function (data) {
        }
    });
}
$("#txt_importe_producto").blur(function(){
    if ($("#txt_costo_unitario").val() != 0){return false;}
    if($("#txt_cantidad").val() > 0 ){
        $("#txt_costo_unitario").val(parseFloat($("#txt_importe_producto").val() / $("#txt_cantidad").val()).toFixed(6));
        $("#txt_valor").val(parseFloat($("#txt_importe_producto").val() / $("#txt_cantidad").val()).toFixed(6));
    }
    $("#txt_subtotal").val($("#txt_importe_producto").val());
    subtotal_con_descuento($("#txt_subtotal").val());
});
$("#txt_importe_producto_editar").blur(function(){
    if ($("#txt_costo_unitario_editar").val() != 0){return false;}
    if ($("#txt_cantidad_editar").val() > 0){
        $("#txt_costo_unitario_editar").val(parseFloat($("#txt_importe_producto_editar").val() / $("#txt_cantidad_editar").val()).toFixed(6));
        $("#txt_valor_editar").val(parseFloat($("#txt_importe_producto_editar").val() / $("#txt_cantidad_editar").val()).toFixed(6));
    }
    $("#txt_subtotal_editar").val($("#txt_importe_producto_editar").val());
    subtotal_con_descuento_editar($("#txt_subtotal_editar").val());
});
function guardar() {
    if(validarDetalles()){
        store()
    }
}
function actualizar() {
    if(validarDetalles()){
        update()
        //window.location.reload();
    }
}
function historial_precio(producto_id, accion){
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "GET",
        url: "/" + $("#var").val() + "/historial_precio",
        data:{producto_id: producto_id, moneda_id: $("#cbo_moneda_orden").val()},
        success: function (data) {
            console.log(data);
            if(data != 0){$("#txt_importe_producto"+accion).prop('readonly',true).val('');}else{$("#txt_importe_producto"+accion).prop('readonly',false).val('');}
            $("#txt_costo_unitario"+accion).val(parseFloat(data).toFixed(6));
            $("#txt_valor"+accion).val(parseFloat(data).toFixed(6));
        },
        error: function (data) {
        }
    });
}
function umedida_producto(producto_id, umedida_id){
    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val(),},
        type: "GET",
        url: "/products/umedidas_producto",
        data:{producto_id: producto_id},
        success: function (data) {
            if(umedida_id != data.ucompra_id){
                $("#txt_umedida_editar").append('<option value="'+data.ucompra_id+'">'+ data.ucompra_codigo + '</option>');
            }else if(umedida_id != data.ukardex_id){
                $("#txt_umedida_editar").append('<option value="'+data.ukardex_id+'">'+ data.ukardex_codigo + '</option>');
            }
        },
        error: function (data) {}
    });
}
function detallepedido(id, valor_maximo){
    if($("#detalle_pedido"+id).val() > valor_maximo){warning('warning','Esta requiriendo más de lo solicitado','¡Advertencia!');}
    $("#detalle_pedido"+id).val(parseFloat($("#detalle_pedido"+id).val()).toFixed(6));
}
function valida_modal(){
    if($("#cbo_moneda_orden").val() == null || $("#cbo_moneda_orden").val() == ''){error('warning', "Seleccione el tipo de moneda", "Error!"); return false;}
}

function anula() {
    anular(1);
}


