$("#customer").change(function () {
    tercero();
});

$('#total').change(function () {
    $("#total").val(redondea($("#total").val(), 2));
});

$("#series").change(function () {
    var serie = $("#series").val();
    $("#series").val(ceros_izquierda(5, serie));
    verificar_ultimo_numero();
});

$("#number").change(function () {
    var serie = $("#number").val();
    $("#number").val(ceros_izquierda(8, serie));
    verificar_numero_registrado();
});

$("#date").change(function () {
    var condpago = $("#paymentcondition").val();
    consultar_tipo_cambio(this.value);
    calcular_vencimiento(condpago, this.value);
});

$("#paymentway").change(function () {
    bank('bank');
    currency('currencypd');
});

function bank(id) {
    $("#" + id).find('option').not(':first').remove();
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/bank",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data) {
                $("#" + id).append('<option value="' + data.banco_id + '" selected>' + data.codigo + ' | ' + data.descripcion + '</option>');
                $("#bankcurrentaccount").val(data.id);
            }
        }
    });
}

$('#amount').change(function () {
    $("#amount").val(redondea($("#amount").val(), 2));
});

$('#total').change(function () {
    $("#total").val(redondea($("#total").val(), 2));
});
