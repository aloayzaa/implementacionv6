function referencia_notacredito() {

    if ($("#customer").val() === null) {
        error('error', "Filtre un tercero", "Error!");
        $("#customer").focus();

        return false;
    }

    if (!$("#processdate").val()) {
        error('error', "Ingrese fecha documento", "Error!");
        return false;
    }

    $('#myModalNotaCredito').modal('show');

    var date = $("#processdate").val();

    $("#date_modal").val(date);
}

function filtrar_referencia(route) {
    var customer = $("#customer").val();
    var date_modal = $("#date_modal").val();

    tableListReferences.init(route + '?customer=' + customer + '&date_modal=' + date_modal);
}

function lista_detalle_referencia(route, id_docxpagar) {
    agrega_carro_docxpagar(id_docxpagar);
    var check = 'referencia' + id_docxpagar;

    if ($('#' + check).is(":checked")) {
        tableListReferencesDetails.init(route + '?id_docxpagar=' + id_docxpagar);
    } else {
        $("#listReferencesDetails tbody").empty();
    }
}

function agrega_carro_docxpagar(id_docxpagar) {
    var variable = $("#var").val();
    var frmNuevo = $("#frm_nuevo_docxpagar");
    $.ajax({
        type: "GET",
        url: "/" + variable + "/agregar_carro_nota_credito_referencia",
        dataType: "JSON",
        data: frmNuevo.serialize() + '&id_docxpagar=' + id_docxpagar,
    });
}

function preinserta_carro_retencion() {
    var aplicar = $('#apply').val();
    $('#base').val(aplicar);
    calcula_impuesto_referencia();
}

function calcula_impuesto_referencia() {
    var variable = $("#var").val();
    var valor = $('#base').val();
    var num = parseFloat(valor).toFixed(2);
    $('#base').val(num);
    procesa_importe_afect_referencia(variable);
    muestra_datos_referencia(variable);
}

function procesa_importe_afect_referencia(variable) {
    var frmGenerales = $("#frm_generales");
    $.ajax({
        type: "POST",
        url: "/" + variable + "/total_instancia_change",
        dataType: "JSON",
        data: frmGenerales.serialize(),
        success: function (data) {
            $('#igvtotal').val(redondea(data.impuesto, 2));
            $('#total').val(redondea(data.total, 2));
            $('#amount').val(redondea(data.total, 2));
        }
    });
}

function muestra_datos_referencia(variable) {
    var frmGenerales = $("#frm_generales");
    $.ajax({
        type: "GET",
        url: "/" + variable + "/obtener_datos_docxpagar",
        dataType: "JSON",
        data: frmGenerales.serialize(),
        success: function (data) {
            if (data.estado === 'ok') {
                $('#documentref').append('<option selected value="' + data.docxpagar_id + '">' + data.documento + '</option>');
                $('#applycheck').attr('checked', true);
            }
        }
    });
}

$('#customer').change(function () {
    var variable = $("#var").val();
    var frmGenerales = $("#frm_generales");

    $.ajax({
        type: "GET",
        url: "/" + variable + "/customerdata",
        dataType: "JSON",
        data: frmGenerales.serialize(),
        success: function (data) {
            $('#ruc').val(data.tercero.codigo);
            $('#address').val(data.tercero.via_nombre);

            var numero = $("#docnumber").val();
            var tercero_id = this.value;
            var serie = $("#docseries").val();
            var origen = 'P';
            var documento_id = $("#document").val();
            verificar_numero_registrado(tercero_id, serie, documento_id, origen, ceros_izquierda(8, numero));
        }
    });
});

$("#docseries").change(function () {
    var serie = this.value;
    $("#docseries").val(ceros_izquierda(5, serie.toUpperCase()));

    var numero = $("#docnumber").val();
    var tercero_id = $("#customer").val();
    var serie = $("#docseries").val();
    var origen = 'P';
    var documento_id = $("#document").val();
    verificar_numero_registrado(tercero_id, serie, documento_id, origen, ceros_izquierda(8, numero));
});

$("#docnumber").change(function () {
    var numero = this.value;
    $("#docnumber").val(ceros_izquierda(8, numero));

    var numero = $("#docnumber").val();
    var tercero_id = $("#customer").val();
    var serie = $("#docseries").val();
    var origen = 'P';
    var documento_id = $("#document").val();
    verificar_numero_registrado(tercero_id, serie, documento_id, origen, ceros_izquierda(8, numero));
});

$("#date").change(function () {
    var fecha = $("#date").val();
    consultar_tipo_cambio(fecha);
});

function verificar_numero_registrado(tercero, serie, documento, origen, numero) {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/verificardocumento",
        dataType: "JSON",
        data: "&tercero=" + tercero + "&serie=" + serie + "&documento=" + documento + "&origen=" + origen + "&numero=" + numero,
        success: function (data) {
            if (data != null) {
                error('error', 'El documento ya se encuentra registrado', 'Error');
            }
        }
    });
}

$('#igv').change(function () {
    procesa_importe_afect();
});

$('#base').change(function () {
    calcula_impuesto(this.value);
});

$('#inactive').change(function () {
    var valor = this.value;
    var num = parseFloat(valor).toFixed(2);
    $('#inactive').val(num);
    procesa_importe_afect();
});

function calcula_impuesto(value) {
    var num = parseFloat(value).toFixed(2);
    $('#base').val(num);
    procesa_importe_afect();
}

function procesa_importe_afect() {
    var variable = $("#var").val();
    var frmGenerales = $("#frm_generales");

    $.ajax({
        type: "POST",
        url: "/" + variable + "/total_instancia_change",
        dataType: "JSON",
        data: frmGenerales.serialize(),
        success: function (data) {
            $('#igvtotal').val(redondea(data.impuesto, 2));
            $('#total').val(redondea(data.total, 2));
        }
    });
}

$('#document').change(function () {
    var numero = $("#docnumber").val();
    var tercero_id = $("#customer").val();
    var serie = $("#docseries").val();
    var origen = 'P';
    var documento_id = $("#document").val();
    verificar_numero_registrado(tercero_id, serie, documento_id, origen, ceros_izquierda(8, numero));
});

$("#perception").change(function () {
    calcula_percepcion();
});

$("#perceptioncheck").change(function () {
    calcula_percepcion();
});
