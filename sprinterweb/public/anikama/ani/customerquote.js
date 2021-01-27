//carga metodos al inicalizar
$(document).ready(function () {
    validarFecha();
    var fecha_tc = $("#txt_fecha").val();
    $("#txt_fecha_entrega").val(fecha_tc);
    if ($('#proceso').val() == 'crea'){
        consultar_tipocambio(fecha_tc);
    }
    llenarSelect('tercero_id','/customers/buscar_tercero');
    llenarSelect('cbo_producto','/serviceorders/buscar_servicios');
    llenarSelect('cbo_producto_edit','/serviceorders/buscar_servicios');
    select2('txt_detraccion','buscar_detraccion');
    select2('cbo_placa','buscar_undtransporte');
});
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
            // $('#txt_total_in').val(data.sum_ingresoalmacen_n);
            // $('#txt_total_ie').val(data.sum_ingresoalmacen_e);
            $('#importe_nacional').val(data.sum_provision_n);
            $('#importe_extranjero').val(data.sum_provision_e);
        },
        error: function (data) {
        }
    });
}

$('#cbo_producto').on('select2:select', function (e) {
    var data = e.params.data;
    $("#txt_umedida").val(data.otros['umedida_codigo']);
    $("#modal_costounidad").val(data.otros['precio1']);
    $("#txt_umedida_id").val(data.otros['umedida_id']);
    $(".cantidad").focus();
});

$("#chktax").on('click', function () {
    if($("#igv").val() == 4){totalizar();}else{$("#chktax").prop("checked", false);}
});
$('#btn_nueva_linea').click(function () {
    /*var tipo_cambio = $("#changerate").val();
    var punto_emision = $("#pointsale").val();
    var razon_social = $("#customer").val();
    var moneda = $("#currency").val();
    var observacion = $("#comment").val();
    var valores = [tipo_cambio, punto_emision, razon_social, moneda, observacion];
    var mensajes = ['Tipo Cambio', 'Punto de Emisión', 'Razón Social', 'Moneda', 'Observación'];
    validar_datos(valores, mensajes);*/
    limpia_modal();
    $('#modal_add').modal('show');
});

/*
function validar_datos(dato, mensaje){
    var valor = false;
    console.log(dato);
    for (i = 0; i < dato.length; i++){
        if (dato[i] == '' || dato[i] == null){
            valor = true;
            mensaje_m = mensaje[i];
        }
    }
    if (valor){
        info('info', 'El campo '+mensaje_m+' es requerido', 'Información');
    }else{
        $('#modal_add').modal('show');
    }
}*/
function limpia_modal() {
    $("#txt_umedida").val('');
    $("#txt_umedida_id").val('');
    $("#cbo_producto").val('').trigger('change');
    $("#modal_cantidad").val('');
    $("#modal_costounidad").val('');
    $('#modal_importe').val('');
    $('#modal_centrocosto_id').val('').trigger('change');
    $('#modal_glosa').val('');
    $('#modal_op').val('');
    $('#modal_actividad_id').val('').trigger('change');
    $('#modal_proyecto_id').val('').trigger('change');
}

$("#tercero_id").change(function () {
    var array = $("#tercero_id option:selected").text();
    ruc = array.split('|');
    $("#txt_customerruc").val(ruc[0]);
    cargar_contactos($(this).val());
    cargar_deposito($(this).val());

});
$("#txt_contacto").change(function () {
    var array = $("#txt_contacto option:selected").text();
    var dato = array.split('|');
    $("#solicitadopor").val(dato[1]);

});
function cargar_contactos(id){
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/cargar_contactos/"+id,
        success: function (data) {
            $("#txt_contacto").html('<option value="">-Seleccione-</option>');
            for (i=0; i< data.length; i++){
                var datos = data[i];
                $("#txt_contacto").append('<option value="'+datos.tercero_id+'">'+datos.nrodocidentidad+ ' | ' +datos.nombre+'</option>');
            }
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
function cargar_deposito(id){
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/depositaren/"+id,
        success: function (data) {
            $("#ctate").html('<option value="">-Seleccione-</option>');
            for (i=0; i< data.length; i++){
                var datos = data[i];
                $("#ctate").append('<option value="'+datos.codigo+'">'+datos.codigo+ ' ' + datos.simbolo+ ' ' +datos.cuenta+'</option>');
            }
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$("#pointsale").change(function () {
    var direccion = $("#pointsale option:selected").data('direccion');
    $("#txt_direccion").val(direccion);
});

function agregar_item() {
    var form_detail = $("#form_detalle");
    var form = form_detail.serialize();
    tabla = $('#listDetailServiceOrders').DataTable();
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'agregar_item',
        data: form,
        success: function (data) {
            totalizar();
            $('#modal_add').modal('hide').data('bs.modal', null);
            success('success', 'Detalle agregado correctamente!', 'Información');
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('#listDetailServiceOrders tbody').on('click', '#btn_editar_detalle_documento', function () {
    tabla = $('#listDetailServiceOrders').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_detalle(data);
});
function editar_detalle(data){
    console.log(data);
    $('#modal_edit').modal('show');
    $("#modal_edit #cbo_producto_edit").append('<option value="'+data.options.producto_id+'" selected>'+data.options.producto_codigo + ' | ' + data.options.producto_descripcion +'</option>');
    $("#modal_edit #txt_umedida").val(data.options.umedida_codigo);
    $("#modal_edit #txt_umedida_id").val(data.options.umedida_id);
    $("#modal_edit #modal_cantidad").val(data.options.cantidad);
    $("#modal_edit #modal_costounidad").val(data.options.precio1);
    $('#modal_edit #modal_importe').val(data.options.importe);
    $('#modal_edit #modal_op').val(data.options.op_id);
    $('#modal_edit #modal_centrocosto_id').val(data.options.ccosto_id);
    $('#modal_edit #modal_glosa').val(data.options.glosa);
    $('#modal_edit #modal_proyecto_id').val(data.options.proyecto_id);
    $('#modal_edit #modal_actividad_id').val(data.options.actividad_id);
    $("#modal_edit #row_id").val(data.rowId)
}
function update_item() {

    tabla = $('#listDetailServiceOrders').DataTable();
    var form = $('#modal_edit #form_detalle');
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'update_item',
        data: form.serialize(),
        success: function (data) {
            totalizar();
            $("#modal_edit #row_id").val('');
            $('#modal_edit').modal('hide').data('bs.modal', null);
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('#listDetailServiceOrders tbody').on('click', '#btn_eliminar_detalle_documento', function () {
    tabla_datos = $('#listDetailServiceOrders').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_datos');
});
function destroy_item(data,tabla,ruta){
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: {rowId: data.rowId,item: data.options.item},
        success: function (data) {
            totalizar();
            tabla.ajax.reload();
            success('success', 'Detalle eliminado correctamente!', 'Información');
        },
        error: function (data) {
        }
    });
}


$("#series_cotiza").blur(function () {
    var serie = $("#series_cotiza").val();
    if (serie.length < 5) {
        serieenv = ceros_izquierda(5, serie);
        $("#series_cotiza").val(serieenv);
    }
});

$("#currency").change(function () {
    $("#txt_tc").val('0.0000');
});
$("#txt_series").blur(function () {
    var serie = $("#txt_series").val();
    if (serie.length < 5) {
        serieenv = ceros_izquierda(5, serie);
        $("#txt_series").val(serieenv);
    }
});
///calculos
$("#igv").change(function () {
    if ($("#igv").val() == 4 ){$("#rent").val('').trigger('change');totalizar_impuesto(1); $("#afecto").val(1);}else{$("#afecto").val(0);}
    totalizar_impuesto(1);
});

$("#rent").change(function () {
    if ($("#rent").val() != '' && $("#rent").val() != 3){$("#igv").val('').trigger('change');}
    totalizar_impuesto(3);
    $("#afecto").val(0);
});
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
        data: form.serialize()+"&afecto="+$("#afecto").val(),
        success: function (data) {
            $("#txt_base").val(parseFloat(data.base).toFixed(2));
            $("#txt_inactive").val(parseFloat(data.inafecto).toFixed(2));
            ejecutar_accion(2);
            sumar();

        }
    });
}
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
            $("#txt_base").val(parseFloat(data.base).toFixed(2));
            $("#txt_inactive").val(parseFloat(data.inafecto).toFixed(2));
            ejecutar_accion(accion);
            //table.ajax.reload();
        }
    });
}
function ejecutar_accion(accion) {
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
        url: "/" + variable + "/impuesto",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_rentimport").val(parseFloat(data.impuesto3).toFixed(2));
            $("#txt_igvtotal").val(parseFloat(data.impuesto).toFixed(2));
            if(data.chk_incluye == 0) {$("#chktax").prop("checked", false);}
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
        url: "/" + variable + "/impuesto3",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_igvtotal").val(parseFloat(data.impuesto).toFixed(2));
            $("#txt_rentimport").val(parseFloat(data.impuesto3).toFixed(2));
            if(data.chk_incluye == 0) {$("#chktax").prop("checked", false);}
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
        url: "/" + variable + "/impuesto",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_rentimport").val(parseFloat(data.impuesto3).toFixed(2));
            $("#txt_igvtotal").val(parseFloat(data.impuesto).toFixed(2));
            if(data.chk_incluye == 0) {$("#chktax").prop("checked", false);}
            impuesto3();
        }
    });
}
//
function impuesto3() {
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/impuesto3",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            // $("#txt_igvtotal").val(parseFloat(data.impuesto).toFixed(2));
            $("#txt_rentimport").val(parseFloat(data.impuesto3).toFixed(2));
            sumar();
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
            console.log(data);
            ($("#igv").val() == 4) ? $("#txt_total").val(parseFloat(data.total).toFixed(2)) : $("#txt_total").val(parseFloat(data.impuesto).toFixed(2));
            if ($("#rent").val() != '' && $("#rent").val() != 3){$("#txt_total").val(parseFloat(data.total).toFixed(2))}
            comprobar_detraccion();
        }
    });
}
$("#modal_edit #modal_cantidad").blur(function () {
    var subtotal = 0;
    if ($("#modal_edit #modal_cantidad").val() > 0) {
        if ($("#modal_edit #modal_costounidad").val() > 0) {
            subtotal = $("#modal_edit #modal_costounidad").val() * $("#modal_edit #modal_cantidad").val();
            $("#modal_edit #modal_importe").val(subtotal);
            $("#modal_edit #txt_subtotal").val(subtotal);
        } else {
            var valor = subtotal / $("#modal_edit #modal_cantidad").val();
            $("#modal_edit #modal_costounidad").val(valor);
            $("#modal_edit #txt_valor").val(valor);

        }
        var lnSubtotal = subtotal;
        subtotal_(subtotal, lnSubtotal);
        calcular_valor();
    }
});

$("#modal_cantidad").blur(function () {
    var subtotal = 0;
    if ($("#modal_cantidad").val() > 0) {
        if ($("#modal_costounidad").val() > 0) {
            subtotal = $("#modal_costounidad").val() * $("#modal_cantidad").val();
            $("#modal_importe").val(parseFloat(subtotal).toFixed(2));
            $("#txt_subtotal").val(parseFloat(subtotal).toFixed(2));
        } else {
            var valor = subtotal / $("#modal_cantidad").val();
            $("#modal_costounidad").val(parseFloat(valor).toFixed(2));
            $("#txt_valor").val(parseFloat(valor).toFixed(2));

        }
        var lnSubtotal = subtotal;
        subtotal_(subtotal, lnSubtotal);
        calcular_valor();
    }
});

function subtotal_(subtotal, lnSubtotal) {
    subtotal = lnSubtotal;
    $("#txt_subtotal").val(parseFloat(subtotal).toFixed(2));
    $("#modal_importe").val(parseFloat(subtotal).toFixed(2));
    $("#modal_edit #txt_subtotal").val(parseFloat(subtotal).toFixed(2));
    $("#modal_edit #modal_importe").val(parseFloat(subtotal).toFixed(2));
}
function calcular_valor() {
    var lntotal = ($("#modal_edit #modal_importe").val() > 0 ? $("#modal_edit #modal_importe").val() : 0);
    var valor = lntotal / $("#modal_cantidad").val();
    $("#modal_edit #txt_valor").val(parseFloat(valor).toFixed(2));
}

$("#modal_costounidad").blur(function () {
    var subtotal = 0;
    var lnprecio = ($("#modal_costounidad").val() > 0 ? $("#modal_costounidad").val() : 0);
    if (lnprecio < 0) {
        $("#modal_costounidad").val(0);
    }
    if ($("#modal_cantidad").val() > 0) {
        var lnSubtotal = lnprecio * $("#modal_cantidad").val();
        subtotal_(subtotal, lnSubtotal)
        calcular_valor()
    }
});

$("#modal_edit #modal_costounidad").blur(function () {
    var subtotal = 0;
    var lnprecio = ($("#modal_edit #modal_costounidad").val() > 0 ? $("#modal_edit #modal_costounidad").val() : 0);
    if (lnprecio < 0) {
        $("#modal_edit #modal_costounidad").val(0);
    }
    if ($("#modal_edit #modal_cantidad").val() > 0) {
        var lnSubtotal = lnprecio * $("#modal_edit #modal_cantidad").val();
        subtotal_(subtotal, lnSubtotal)
        calcular_valor()
    }
});
$("#txt_detraccion").change(function(){
    comprobar_detraccion();
});
$("#txt_valor_referencial").blur(function(){
    comprobar_detraccion();
});
function comprobar_detraccion(){
    var variable = $("#var").val();
    var form = $("#frm_generales");
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/importe_adicional",
        dataType: "json",
        data: form.serialize(),
        success: function (data) {
            $("#txt_importe").val(parseFloat(data.importe).toFixed(2))
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

$("#modal_cantidad").change(function () {
    var valor = $("#modal_cantidad").val();
    $("#modal_cantidad").val(parseFloat(valor).toFixed(3));
});
$("#modal_costounidad").change(function () {
    var valor = $("#modal_costounidad").val();
    $("#modal_costounidad").val(parseFloat(valor).toFixed(6));
    if ($("#modal_costounidad").val() == '' || $("#modal_costounidad").val() == 0){
        $(".importe").prop('readonly', false);
    }else{
        $(".importe").prop('readonly', true);
    }
});
$("#modal_importe").change(function () {
    var valor = $("#modal_importe").val();
    $("#modal_importe").val(parseFloat(valor).toFixed(2));
    var valor2 = $("#modal_cantidad").val();
    var precio_unitario = valor / valor2;
    $("#modal_costounidad").val(parseFloat(precio_unitario).toFixed(6));
    if ($("#modal_costounidad").val() == '' || $("#modal_costounidad").val() == 0){
        $(".importe").prop('readonly', false);
    }else{
        $(".importe").prop('readonly', true);
    }
});

$("#modal_edit #modal_cantidad").change(function () {
    var valor = $("#modal_edit #modal_cantidad").val();
    $("#modal_edit #modal_cantidad").val(parseFloat(valor).toFixed(3));
});
$("#modal_edit #modal_costounidad").change(function () {
    var valor = $("#modal_edit #modal_costounidad").val();
    $("#modal_edit #modal_costounidad").val(parseFloat(valor).toFixed(6));
    if ($("#modal_edit #modal_costounidad").val() == '' || $("#modal_edit #modal_costounidad").val() == 0){
        $(".importe").prop('readonly', false);
    }else{
        $(".importe").prop('readonly', true);
    }
});
$("#modal_edit #modal_importe").change(function () {
    var valor = $("#modal_edit #modal_importe").val();
    $("#modal_edit #modal_importe").val(parseFloat(valor).toFixed(2));
    var valor2 = $("#modal_edit #modal_cantidad").val();
    var precio_unitario = valor / valor2;
    $("#modal_edit #modal_costounidad").val(parseFloat(precio_unitario).toFixed(6));
    if ($("#modal_edit #modal_costounidad").val() == '' || $("#modal_edit #modal_costounidad").val() == 0){
        $(".importe").prop('readonly', false);
    }else{
        $(".importe").prop('readonly', true);
    }
});


function guardar() {
    store()
}
function actualizar() {
    update()
}

//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(1);
}



