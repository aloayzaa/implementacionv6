$(document).ready(function () {
    var instancia = $("#instancia").val();
    var variable = $("#var").val();
    listar_detalle(instancia, variable);
    totalizar();
});

$("#processdate").change(function () {
    consultar_tipo_cambio(this.value);
});

function abrir_modal() {
    if ($("#changerate").val() !== '0.00') {
        $('#myModalCierreApertura').modal('show');
        limpia_modal();
    } else {
        error('error', "Ingrese una fecha", "Error!");
        $("#processdate").focus();
    }
}

function limpia_modal() {
    $("#account").val(0);
    $("#cost").val(0);
    $("#glosam").val('');
    $("#cargomn").val('0.00');
    $("#abonomn").val('0.00');
    $("#cargome").val('0.00');
    $("#abonome").val('0.00');
}

$("#cargomn").change(function () {
    var valor = $("#cargomn").val();
    var tcambio = $("#changerate").val();
    $("#cargomn").val(redondea(valor, 2));
    $("#cargome").val(redondea(valor / tcambio, 2));
});

$("#abonomn").change(function () {
    var valor = $("#abonomn").val();
    var tcambio = $("#changerate").val();
    $("#abonomn").val(redondea(valor, 2));
    $("#abonome").val(redondea(valor / tcambio, 2));
});

$("#cargome").change(function () {
    var valor = $("#cargome").val();
    var tcambio = $("#changerate").val();
    $("#cargome").val(redondea(valor, 2));
    $("#cargomn").val(redondea(valor * tcambio, 2));
});

$("#abonome").change(function () {
    var valor = $("#abonome").val();
    var tcambio = $("#changerate").val();
    $("#abonome").val(redondea(valor, 2));
    $("#abonomn").val(redondea(valor * tcambio, 2));
});

$("#btn_agregar_cart").click(function () {
    var valor = $("#txt_id_modelo_modal").val();
    if (valor === null) {
        agregar_detalle_cierre();
    } else {
        guardar_cambios_modal_cierre();
    }
});

function agregar_detalle_cierre() {
    if ($("#account").val() === null) {
        error("error", "Seleccione una Cuenta", "Error!");
        return false;
    }
    if ($("#cargomn").val() !== 0) {
        if ($("#cargome").val() === 0) {
            error("error", "Ingrese cargo M.E.", "Error!");
            return false;
        }
    }
    if ($("#cargome").val() !== 0) {
        if ($("#cargomn").val() === 0) {
            error("error", "Ingrese cargo M.N.", "Error!");
            return false;
        }
    }
    if ($("#abonomn").val() !== 0) {
        if ($("#abonome").val() === 0) {
            error("error", "Ingrese abono M.E.", "Error!");
            return false;
        }
    }
    if ($("#abonome").val() !== 0) {
        if ($("#abonomn").val() === 0) {
            error("error", "Ingrese abono M.N.", "Error!");
            return false;
        }
    }

    var datos = $("#frm_generales").serialize();
    var variable = $("#var").val();
    var url = "/" + variable + '/agregar';
    $.post(url, datos, function (resultado) {
        listar_detalle(resultado, proceso);
        totalizar(resultado);
        $('#myModalCierreApertura').modal('hide');
    });
}

function guardar_cambios_modal_cierre() {
    $('#myModalDetalleCierre').modal('hide');
    var variable = $("#var").val();
    $.ajax({
        type: "POST",
        url: "/" + variable + '/editar_detalle',
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data.estado === "ok") {
                listar_detalle(data.instancia, variable);
            } else {
                listar_detalle(data.instancia, 'openingseat');
            }
            totalizar();
        }
    });
}

function totalizar() {
    var variable = $("#var").val();
    $.ajax({
        type: "POST",
        url: "/" + variable + "/totalizar",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            $("#total_cargomn").val(redondea(data.totcargomn, 2));
            $("#total_abonomn").val(redondea(data.totabonomn, 2));
            $("#totalmn").val(redondea(data.totcargomn - data.totabonomn, 2));
            $("#total_cargome").val(redondea(data.totcargome, 2));
            $("#total_abonome").val(redondea(data.totabonome, 2));
            $("#totalme").val(redondea(data.totcargome - data.totabonome, 2));
        }
    });
}
