$(document).ready(function(){
    $("#txt_fecha").focus();
});
var grupo_ruta = $("#var").val();
$(document).ready(function () {
    validarFecha();
    consultar_tipocambio($('.tipocambio').val());
});

$('#btn_nueva_linea').click(function () {
    open_modal();
});

function open_modal(){
    let motivo = ($("#txtglosa").val() != '')? true : false;
    let mensaje = validarFecha();
    if(mensaje){
        if(motivo){
            limpia_modal();
            llenarSelect('tercero_id','/customers/buscar_tercero');
            $("#modal_add #tercero_id").focus();
            $('#modal_add').modal('show');
        }else{
            success('warning', 'Ingrese el Motivo de Baja', 'Información');
            $("#txtglosa").focus();
        }
    }
}

function limpia_modal() {
}

$("#tercero_id").change(function(){
    aux_aplicacion();
})

$("#txtdocreferencial").click(function(){
    aux_aplicacion();
});

function aux_aplicacion(){
    let moneda_id = $("#txt_descripcionmon").val();
    let tipo = $("#txt_descripcionmon option:selected").data('tipo');
    let txt_fecha = $("#txt_fecha").val();
    let tcambio = $("#tcambio").val();
    let glosa = $("#txtglosa").val();
    let tercero_id = $("#tercero_id").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/aux_aplicacion",
        data:{tercero_id: tercero_id, moneda_id: moneda_id, tipo: tipo, txt_fecha: txt_fecha, glosa: glosa, tcambio: tcambio},
        success: function (data) {
            llenarSelect('tercero_id_refe','/customers/buscar_tercero');
            $('#modal_referencia').modal('show');
            //
            $("#txtdocreferencial").focus();

            let id = $("#tercero_id").val();
            let descripcion = $("#tercero_id option:selected").text();
            //colocamos el tercero eleccionado en la referencia
            $("#tercero_id_refe").html('<option value="'+id+'">'+descripcion+'</option>');
            //Colocamos la fecha hasta
            $("#txthasta").val(data.txthasta);
            //otros valores
            $("#txtid").val(data.txtid);
            $("#txtoperacion").val(data.txtoperacion);
            $("#txtnum1").val(data.txtnum1);
            $("#ctipomon").val(data.ctipomon);
            $("#corigen").val(data.corigen);
            $("#txtcambio").val(data.txtcambio);
            $("#txttotal").val(data.txttotal);
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

$("#mostrar").click(function(){
    let tercero_id_refe = $("#tercero_id_refe").val();
    let corigen = $("#corigen").val();
    let txthasta = $("#txthasta").val();
    let txtid = $("#txtid").val();
    let txtoperacion = $("#txtoperacion").val();
    let tcambio = $("#txtcambio").val();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/mostrar",
        data:{tercero_id_refe: tercero_id_refe, corigen: corigen, txthasta: txthasta, txtid: txtid, txtoperacion: txtoperacion, tcambio: tcambio},
        success: function (data) {
            $("#txttotal").val(data.txttotal);
            $('#documentosxcobrar').DataTable().ajax.reload();
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
})

function ocheck_valid(id){
    var check = false;
    if($("#check"+id).prop('checked')){
        check = true;
    }
    let tipo = $("#txt_descripcionmon option:selected").data('tipo');
    let txtnum1 = $("#txtnum1").val();
    let txttotal = $("#txttotal").val();
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/check",
        data:{id: id, ctipomon: tipo, txtnum1: txtnum1, txttotal: txttotal, check: check},
        success: function (data) {
            $("#txttotal").val(data.txttotal);
            if($(".check").prop('checked')){
                $("#ckbtodos").prop('checked', true);
            }else{
                $("#ckbtodos").prop("checked", false);
            }
            $('#documentosxcobrar').DataTable().ajax.reload();
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

$("#btn_aceptar").click(function(){

    let data = obtener_checks_seleccionados('documentosxcobrar');

    let txtnum1 = $("#txtnum1").val();
    let txttotal = $("#txttotal").val();
    let txt_fecha = $("#txt_fecha").val();
    let tercero_id = $("#tercero_id").val();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/btnok",
        data:{data: JSON.stringify(data), txtnum1: txtnum1, txttotal: txttotal, txt_fecha: txt_fecha, tercero_id: tercero_id},
        success: function (data) {
            if(data.mensaje){
                warning('warning', data.mensaje, 'Información');
            }else{
                $('#modal_referencia').modal('hide');
                $('#modal_add').modal('hide');
                $('#listDetailLowCommunication').DataTable().ajax.reload();
            }
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
});

function obtener_checks_seleccionados(id) {
    let tabla = $('#' + id).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        let data = this.node();
        if ($(data).find('input').prop('checked')) {
            det = {
                'check_ids': $(data).find('input').serializeArray()[0],
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}


$('#txt_fecha').blur(function(){
    $("#txtglosa").focus();
});

/*$("#txtglosa").blur(function(){
    if($("#id").val() == ''){
        open_modal();
    }
});*/

$("#ckbtodos").click(function(){
    if($(this).prop('checked')){
        $(".check").prop('checked', true);
    }else{
        $(".check").prop("checked", false);
    }

    var check = false;
    if($(".check").prop('checked')){
        check = true;
    }

    let tipo = $("#txt_descripcionmon option:selected").data('tipo');

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/todoscheck",
        data:{ctipomon: tipo, check: check},
        success: function (data) {
            $("#txttotal").val(data.txttotal);
            $('#documentosxcobrar').DataTable().ajax.reload();
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
})

function guardar() {
    store()
}

$("#procesar_baja").click(function(){
    var form = $("#frm_generales");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "post",
        url: "/" + grupo_ruta + "/procesar",
        data: form.serialize(),
        success: function (data) {
            success('success', data.descripcion, 'Información');
            if(data.estado == 'ok'){
                window.location.replace(data.ruta);
            }
            
        }, error: function (data) {
            mostrar_errores(data);
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        }
    });
});

//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(1);
}

$('#listDetailLowCommunication tbody').on('click', '#btn_eliminar_comunicacion', function () {
    tabla_datos = $('#listDetailLowCommunication').DataTable();
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
            $("table").DataTable().ajax.reload();
            success('success', 'Detalle eliminado correctamente!', 'Información');
        },
        error: function (data) {
        }
    });
}
