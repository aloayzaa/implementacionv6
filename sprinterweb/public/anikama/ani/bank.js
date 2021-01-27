$(document).ready(function () {
});

// function abrir_modal() {
//     $('#myModalEntidadBancaria').modal('show');
//     limpia_modal();
// }

$('#btn_nueva_linea').click(function () {
    $('#myModalEntidadBancaria').modal('show');
    limpia_modal();
});
// function limpia_modal() {
//     $("#code").val('');
//     $("#account").val(0);
//     $("#currency").find('option').not(':first').remove();
//     $("#check").val('');
// }
$('#tabla_docbanco tbody').on('click', '#btn_eliminar_entidadbancaria', function () {
    tabla_datos = $('#tabla_docbanco').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_detalle_general');
});

$("#account").change(function () {
    currency("currency");
});
$("#myModalEntidadBancaria").click(function(){
    $("#myModalAlmacen").find("input").val("");
    $("#myModalAlmacen select").val("0").trigger("change");
    $('#myModalAlmacen').modal('show');
});
$('#tabla_docbanco tbody').on('click', '#btn_editar_entidadbancaria', function () {
    tabla_datos = $('#tabla_docbanco').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    editar_entidadbancaria(data);
});

function editar_entidadbancaria(data){

    $('#myModalEntidadBancaria').modal('show');
    $("#id_ctactebanco").val(data.options.id).trigger("change");
    // $("#cbo_ubicacion_almacen").val(data.options.almacen_id).trigger("change");
    $("#code").val(data.options.codigo);
    $("#row_id").val(data.rowId);
}

function enviar_detalle_ctactebanco(){
    if($("#row_id").val() != ''){
        detalle_entidad_bank('editar_entidad_banco');
    }else{
        detalle_entidad_bank('agregar_entidad_banco');
    }
}
function editar_ubicacionalmacen(data){

    $('#myModalAlmacen').modal('show');
    $("#productoubicacion_id").val(data.options.id).trigger("change");
    $("#cbo_ubicacion_almacen").val(data.options.almacen_id).trigger("change");
    $("#txt_ubicacion_almacen").val(data.options.ubicacion);
    $("#row_id").val(data.rowId);
}
function detalle_entidad_bank(ruta){
    tabla_entidadbank= $('#tabla_docbanco').DataTable();
    var variable = $("#var").val();
    var frm = $("#frm_entidad_bank");

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize(),
        success: function (data) {
            $("#row_id").val('');
            $('#myModalEntidadBancaria').modal('hide');
            tabla_entidadbank.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });

}
//

//
// function agregar() {
//     var variable = $("#var").val();
//
//     if ($("#code").val().length > 20) {
//         error('error', "Código con máximo 20 caracteres", "Error");
//         return false;
//     }
//     if ($("#account").val().trim() === 0) {
//         error('error', "Seleccione una cuenta válida", "Error!");
//         return false;
//     }
//     if ($("#currency").val().trim() === 0) {
//         error('error', "Seleccione una moneda válida", "Error!");
//         return false;
//     }
//     if ($("#check").val().length > 11) {
//         error('error', "Número de cheque con máximo 11 caracteres", 'Error!');
//         return false;
//     }
//
//     var datos = $("#frm_generales").serialize();
//     var url = "/" + variable + '/agregar';
//     $.post(url, datos, function (resultado) {
//         listar_detalle(resultado, variable);
//         $('#myModalEntidadBancaria').modal('hide');
//     });
//
// }

function ver_detalle(id) {
    var variable = $('#var').val();
    $.ajax({
        type: "POST",
        url: '/' + variable + '/ver_detalle',
        dataType: "JSON",
        data: $("#frm_generales").serialize() + '&id=' + id,
        success: function (data) {
            if (data.estado === "ok") {
                $('#myModalEntidadBancaria').modal('show');
                $('#id_ctactebanco').val(data.id_ctactebanco);
                $('#id_cart').val(id);
                $("#estado_modal").val(data.state);
                $("#code").val(data.codigo);
                $("#account").val(data.id_cuenta);
                $("#currency").append('<option value="' + data.id_moneda + '" selected>' + data.cod_moneda + ' | ' + data.descripcion_moneda + '</option>');
                $("#check").val(data.numero_cheque);
            } else {
                error('error', data.estado, 'Error!');
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

// function edita_detalle() {
//     var variable = $('#var').val();
//     $.ajax({
//         type: "POST",
//         url: '/' + variable + '/editar_detalle',
//         dataType: "JSON",
//         data: $("#frm_generales").serialize(),
//         success: function (data) {
//             if (data.estado === "ok") {
//                 $('#myModalEntidadBancaria').modal('hide');
//                 listar_detalle(data.instancia, variable);
//             } else {
//                 $('#myModalEntidadBancaria').modal('hide');
//                 listar_detalle(data.instancia, variable);
//             }
//         }
//     });
// }
