$(document).ready(function () {
    var instancia = $("#instancia").val();
    var variable = $("#var").val();
    listar_detalle(instancia, variable);
    totalizar(variable);
});

function abrir_modal() {
    if ($("#changerate").val() !== '0.00') {
        $('#myModalAsientoApertura').modal('show');
        limpia_modal();
    } else {
        error('error', "Ingrese una fecha", "Error!");
        $("#processdate").focus();
    }
}

function limpia_modal() {
    $("#account").val(0);
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

$("#processdate").change(function () {
    consultar_tipo_cambio(this.value);
});

$("#btn_agregar_cart").click(function () {
    var valor = $("#txt_id_modelo_modal").val();
    if (!valor) {
        agregar_detalle_apertura();
    } else {
        guardar_cambios_modal_apertura();
    }
});

function agregar_detalle_apertura() {
    if ($("#account").val() === 0) {
        error('error', "Seleccione una Cuenta", "Error!");
        return false;
    }
    if ($("#cargomn").val() !== 0) {
        if ($("#cargome").val() === 0) {
            error('error', "Ingrese cargo M.E.", "Error!");
            return false;
        }
    }
    if ($("#cargome").val() !== 0) {
        if ($("#cargomn").val() === 0) {
            error('error', "Ingrese cargo M.N.", 'Error!');
            return false;
        }
    }
    if ($("#abonomn").val() !== 0) {
        if ($("#abonome").val() == 0) {
            error('error', "Ingrese abono M.E.", "Error!");
            return false;
        }
    }
    if ($("#abonome").val() !== 0) {
        if ($("#abonomn").val() === 0) {
            error('error', "Ingrese abono M.N.", "Error!");
            return false;
        }
    }

    var variable = $("#var").val();
    var datos = $("#frm_generales").serialize();
    var url = "/" + variable + '/agregar';
    $.post(url, datos, function (resultado) {
        listar_detalle(resultado, variable);
        totalizar(variable);
        $('#myModalAsientoApertura').modal('hide');
    });
}

function totalizar(variable) {
    $.ajax({
        type: "POST",
        url: "/" + variable + "/totalizar",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            $("#tcargomn").val(redondea(data.totcargomn, 2));
            $("#tabonomn").val(redondea(data.totabonomn, 2));
            $("#totalmn").val(redondea(data.totcargomn - data.totabonomn, 2));
            $("#tcargome").val(redondea(data.totcargome, 2));
            $("#tabonome").val(redondea(data.totabonome, 2));
            $("#totalme").val(redondea(data.totcargome - data.totabonome, 2));
        }
    });
}

function ver_detalle(id, id_detalle, id_parent) {
    var variable = $("#var").val();
    $.ajax({
        type: "POST",
        url: '/' + variable + '/ver_detalle',
        dataType: "JSON",
        data: $("#frm_generales").serialize() + '&id=' + id + '&id_detalle=' + id_detalle + '&id_parent=' + id_parent,
        success: function (data) {
            if (data.estado === "ok") {
                $('#myModalAsientoApertura').modal('show');
                $("#account").val(data.cuenta_id);
                $("#cargomn").val(parseFloat(data.cargomn).toFixed(2));
                $("#cargome").val(parseFloat(data.cargome).toFixed(2));
                $("#abonomn").val(parseFloat(data.abonomn).toFixed(2));
                $("#abonome").val(parseFloat(data.abonome).toFixed(2));
                $("#txt_id_modelo_modal").val(data.id);
                $("#txt_auxiliar").val(id_detalle);
                $("#txt_aux_id").val(id_parent);
                $("#estado_modal").val(data.state);
            } else {
                error('error', data.estado, "Error");
            }
        }
    });
}

function guardar_cambios_modal_apertura() {
    $('#myModalAsientoApertura').modal('hide');
    var variable = $("#var").val();
    $.ajax({
        type: "POST",
        url: '/' + variable + '/editar_detalle',
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data.estado === "ok") {
                listar_detalle(data.instancia, variable);
            } else {
                listar_detalle(data.instancia, variable);
            }
            totalizar(variable);
        }
    });
}
