$(document).ready(function () {
    var instancia = $("#instancia").val();
    var variable = $("#var").val();
    listar_detalle(instancia, variable);
});

$("#frm_generales").validate({
    rules: {
        email: {
            required: true
        },
        lastname: {
            required: true,
        },
        name: {
            required: true,
        },
        phone: {
            required: true,
        }
    },
    messages: {
        email: {
            required: "Campo obligatorio",
        },
        password: {
            required: "Campo obligatorio",
        }
    }
});

function consultar_correo(variable, id_email) {
    var email = $("#" + id_email).val();
    var token = $("#_token").val();
    $.ajax({
        type: "POST",
        url: "/" + variable + "/consultar_correo",
        dataType: "JSON",
        data: '&email=' + email + '&_token=' + token,
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                error("error", "Usuario ya existe", "Error");
                $("#btn_grabar").attr("disabled", true);
            } else {
                $('#btn_grabar').removeAttr('disabled');
            }
        }
    });
}

function agregar_item(instancia, variable) {
    var frm_generales = $("#frm_generales");
    if ($("#rol").val() == null) {
        error('error', 'Ingrese un rol', "Alerta!");
        $("#rol").focus();
        return false;
    }

    if ($("#company").val() == null) {
        error('error', 'Ingrese una empresa', "Alerta!");
        $("#company").focus();
        return false;
    }

    var proceso = $("#tipo_proceso").val();
    var datos = frm_generales.serialize() + '&instancia=' + instancia + '&proceso=' + proceso;
    var url = '/' + variable + '/agregar';
    $.post(url, datos, function (instancia) {
        listar_detalle(instancia, variable);
    });
}

$("#menu").change(function () {
    var route = $("#routelistoptions").val();
    var empresa = $("#company").val();
    var id = $("#id").val();

    tableMenuOptions.init(route + '?menu=' + this.value + '&empresa=' + empresa + '&id=' + id);
});

function activaropcion(id, tipo) {
    var variable = $("#var").val();
    var value = checkbox(id + tipo);
    $.ajax({
        type: "GET",
        url: "/" + variable + "/activaropcion",
        dataType: "JSON",
        data: '&id=' + id + '&tipo=' + tipo + '&value=' + value,
        success: function (data) {

        }
    });
}
