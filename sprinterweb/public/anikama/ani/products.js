$(document).ready(function () {
    var proceso = $("#proceso").val();
    if (proceso === 'edita') {
        //$("#frm_generales :input").attr("disabled", true);
    }

    $("#cbo_familia").select2();
    $("#model").select2({placeholder: "-Seleccionar-"});
    $("#cbo_tipo_carga").select2({placeholder: "-Seleccionar-"});

    select2_sunat_producto('sunat','/products/buscar_codigo_sunat');
    select2('cbo_partida_arancelaria','buscar_partida_arancelaria');
    select2('compra','buscar_um');
    select2('kardex','buscar_um');
    select2('mark','buscar_marca');
    select2('txt_grupo','buscar_grupoproducto');
    select2('color','buscar_color');
});

var variable = $("#var").val();

$("#cbo_familia").on('change', function () {

    let cbo_familia = $("#cbo_familia").val();
    let sunat = $("#sunat").val();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "GET",
        url: "/" + variable + "/obtener_codigo_producto",
        data: {cbo_familia : cbo_familia, sunat: sunat},
        success: function (data) {

            if (data !== null){
                
                if ( data['codigo_producto'] !== null){ $("#txt_codigo_producto").val(data['codigo_producto']); }
                if ( data['id_producto_sunat'] !== null){
                     $("#sunat").append("<option value='"+data['id_producto_sunat']+"' selected>"+ data['codigo_producto_sunat'] + " | " + data['descripcion_producto_sunat'] + "</option>");
                }
                
            }

        }
    });
});

$("#mark").change(function(){
    $("#model").empty();
    select2('model','buscar_modelo?marca_id='+this.value);
});
function float(id) {
    var valor = $("#" + id).val();
    $("#" + id).val(redondea(valor, 2));
}
$("#conversion").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#conversion").val(data);
});
$("#peso").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#peso").val(data);
});
$("#volumen").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#volumen").val(data);
});
$("#longitud").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#longitud").val(data);
});
$("#sminimo").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#sminimo").val(data);
});
$("#smaximo").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#smaximo").val(data);
});
$("#utilidad").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#utilidad").val(data);
});
$("#toleracion").change(function () {
    var valor = this.value;
    var data = parseFloat(valor).toFixed(3);
    $("#toleracion").val(data);
});
$("#modal_ubicacion_almacen").click(function(){
    $("#myModalAlmacen").find("input").val("");
    $("#myModalAlmacen select").val("0").trigger("change");
    $('#myModalAlmacen').modal('show');
});
$('#tabla_ubicacion_almacen tbody').on('click', '#btn_editar_ubicacionalmacen', function () {
    tabla_ubicacionalmacen = $('#tabla_ubicacion_almacen').DataTable();
    var data = tabla_ubicacionalmacen.row($(this).parents('tr')).data();
    editar_ubicacionalmacen(data);
});
function editar_ubicacionalmacen(data){

    $('#myModalAlmacen').modal('show');
    $("#productoubicacion_id").val(data.options.id).trigger("change");
    $("#cbo_ubicacion_almacen").val(data.options.almacen_id).trigger("change");
    $("#txt_ubicacion_almacen").val(data.options.ubicacion);
    $("#row_id").val(data.rowId);
}
function enviar_ubicacion_almacen(){
    if($("#row_id").val() != ''){
        ubicacion_almacen_env('editar_ubicacion_almacen');
    }else{
        ubicacion_almacen_env('agregar_ubicacion_almacen');
    }
}
function ubicacion_almacen_env(ruta){
    tabla_ubicacionalmacen = $('#tabla_ubicacion_almacen').DataTable();

    var frm = $("#frm_ubicacion_almacen");

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize(),
        success: function (data) {
            $("#row_id").val('');
            $('#myModalAlmacen').modal('hide');
            tabla_ubicacionalmacen.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });

}
$("#modal_datos_npk").click(function(){
    $("#myDatosNPK").find("input").val("");
    $("#myDatosNPK select").val("0").trigger("change");
    $('#myDatosNPK').modal('show');
});
$('#tabla_datos_npk tbody').on('click', '#btn_editar_npk', function () {
    tabla_datos_npk = $('#tabla_datos_npk').DataTable();
    var data = tabla_datos_npk.row($(this).parents('tr')).data();
    editar_datosnpk(data);
});
function editar_datosnpk(data){
    $('#myDatosNPK').modal('show');
    $("#productonpk_id").val(data.options.id);
    $("#cbo_productos_npk").val(data.options.nutriente_id).trigger("change");
    $("#txt_conc").val(data.options.conc);
    $("#row_id_npk").val(data.rowId);
}
function enviar_datos_npk(){
    if($("#row_id_npk").val() != ''){
        npk_env('editar_datos_npk');
    }else{
        npk_env('agregar_datos_npk');
    }
}
function npk_env(ruta){
    tabla_datos_npk = $('#tabla_datos_npk').DataTable();

    var frm = $("#frm_npk");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize(),
        success: function (data) {
            $("#row_id_npk").val('');
            $('#myDatosNPK').modal('hide');
            tabla_datos_npk.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#tabla_ubicacion_almacen tbody').on('click', '#btn_eliminar_ubicacionalmacen', function () {
        tabla_ubicacion_almacen = $('#tabla_ubicacion_almacen').DataTable();
        var data = tabla_ubicacion_almacen.row($(this).parents('tr')).data();
        destroy_item(data,tabla_ubicacion_almacen,'eliminar_ubicacion_almacen');
});
$('#tabla_datos_npk tbody').on('click', '#btn_eliminar_npk', function () {
    tabla_datos_npk = $('#tabla_datos_npk').DataTable();
    var data = tabla_datos_npk.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos_npk,'eliminar_datos_npk');
});
function destroy_item(data,tabla,ruta){

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: {rowId: data.rowId,id: data.options.id},
        success: function (data) {
            tabla.ajax.reload();
            success('success', 'Detalle eliminado correctamente!', 'Información');
        },
        error: function (data) {
        }
    });
}
function select2_sunat_producto(select, route){
    $("#"+select).select2({
        placeholder: "-- Seleccione una opción --",
        minimumInputLength: 2,
        multiple: false,
        //width: 400,
        ajax: {
            url: route,
            data: function(term) {
                return term;
            },
            processResults: function(data) {
                $("#"+select).empty();
                return {
                    results: $.map(data, function(obj) {
                        return {
                            id: obj.id,
                            text: obj.codigo +' | '+ obj.descripcion,
                            disabled: obj.codigo.length != 8 ? true :false
                        };
                    })
                };
            }
        }
    });
}
function guardar() {
    store()
}
function actualizar() {
    update_image()
}
