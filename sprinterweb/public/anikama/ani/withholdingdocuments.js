$(document).ready(function () {
    var instancia = $("#instancia").val();
    var variable = $("#var").val();
    listar_detalle(instancia, variable)
});

function abrir_modal(route) {
    $('#myModalDetalleRetencion').modal('show');
    tablereference(route);
}

$("#processdate").change(function () {
    $("#finishdate").val(this.value);
});

$("#btnmostrar").click(function () {
    tablereference(this.value);
});

$("#date").change(function () {
    consultar_tipo_cambio(this.value);
    var paymentcondition = $("#paymentcondition").val();
    calcular_vencimiento(paymentcondition, this.value);
});

function tablereference(route) {
    var finishdate = $("#finishdate").val();
    var customer = $("#customer").val();
    var type = $("#type").val();

    tableWithholdingReference.init(route + '?customer=' + customer + '&finishdate=' + finishdate + '&type=' + type);
}

$("#documenttype").change(function () {
    $("#series").val('00001');
    var serie = $("#series").val();
    var documento_id = $("#documenttype").val();
    var origen = $("#type").val();
    $("#series").val(ceros_izquierda(5, serie.toUpperCase()));
    verificar_ultimo_numero(serie.toUpperCase(), documento_id, origen);
});

$("#type").change(function () {
    var serie = $("#series").val();
    var documento_id = $("#documenttype").val();
    var origen = $("#type").val();
    $("#series").val(ceros_izquierda(5, serie.toUpperCase()));
    verificar_ultimo_numero(serie.toUpperCase(), documento_id, origen);
});

$("#series").change(function () {
    var series = $("#series").val();
    $("#series").val(ceros_izquierda(5, series.toUpperCase()));
    verificar_ultimo_numero();
});

$("#number").change(function () {
    var variable = $("#var").val();
    genera_glosa(variable);
    var numero = $("#number").val();
    $("#number").val(ceros_izquierda(8, numero));
});

function aplica_ref(sp_id, saldomn, saldome) {
    var table = $('#listWithholdingDocumentsReference').DataTable();
    var saldon = saldomn.toFixed(2);
    var saldoe = saldome.toFixed(2);
    var moneda = $("#currency").val();

    if ($('#chkAplica' + sp_id).is(':checked')) {
        if (moneda === '1') {
            $('#txt_aplica' + sp_id).val(saldon);
        } else {
            $('#txt_aplica' + sp_id).val(saldoe);
        }
        $("#txt_aplica" + sp_id).attr('readonly', false);
        chckaplica_data(table, sp_id);
    } else {
        $("#txt_aplica" + sp_id).attr('readonly', true);
        $('#txt_aplica' + sp_id).val('0.00');
    }
}

function chckaplica_data(table, sp_id) {
    $('#listWithholdingDocumentsReference tbody').on('click', '#chkAplica' + sp_id, function () {
        var chckaplica = checkbox('chkAplica' + sp_id);
        if (chckaplica === 1) {
            table.row($(this).parents('tr').addClass('selected'));
        } else {
            table.row($(this).parents('tr').removeClass('selected'));
        }
    });
}

$("#btn_agregar_cart").click(function () {
    var table = $('#listWithholdingDocumentsReference').DataTable();
    var datatable = JSON.stringify(table.rows('.selected').data().toArray());
    inserta_carro_retencion(datatable);
});

function format(id) {
    var value = $("#txt_aplica" + id).val();
    var aplicar = parseFloat(value).toFixed(2);
    $("#txt_aplica" + id).val(aplicar);
}

function inserta_carro_retencion(datatable) {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + '/carro_retencion',
        data: $("#frm_generales").serialize() + '&datatable=' + datatable,
        success: function (data) {
            listar_detalle(data, variable);
            $('#myModalDetalleRetencion').modal('hide');
        }
    });
}
