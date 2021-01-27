$("#processdatee").change(function () {
    tipo_cambio(this.value, 'changeratee');
});

$("#processdatei").change(function () {
    tipo_cambio(this.value, 'changeratei');
});

function tipo_cambio(fecha, id) {
    $.ajax({
        type: "GET",
        url: "/consumer/consulta_tcambio",
        dataType: "JSON",
        data: '&fecha=' + fecha,
        success: function (data) {
            if (data === null) {
                $("#" + id).val(0.000);
            } else {
                $("#" + id).val(parseFloat(data).toFixed(3));
            }
        }
    });
}

$("#banke").change(function () {
    ctacte('currentaccounti');
    bankcash("cashe", this.value);
});

$("#banki").change(function () {
    ctacte('currentaccounti');
    bankcash("cashi", this.value);
});

$("#currentaccounte").change(function () {
    currency('currencye');
});

$("#currentaccounti").change(function () {
    currency('currencyi');
});

$("#checkchequee").change(function () {
    var value = checkbox(this.id);
    if (value === 1) {
        $("#checke").attr('readonly', false);
        $("#namee").attr('readonly', false);
    } else {
        $("#checke").attr('readonly', true);
        $("#checke").val('');
        $("#namee").attr('readonly', true);
        $("#namee").val('');
    }
});

$("#deferredcheck").change(function () {
    var value = checkbox(this.id);
    if (value === 1) {
        $("#deferred").attr('readonly', false);
    } else {
        $("#deferred").attr('readonly', true);
        $("#deferred").val('');
    }
});

$("#amounte").change(function () {
    var total = $('#amounte').val();
    $('#amounte').val(parseFloat(total).toFixed(2));
});

$("#amounti").change(function () {
    var total = $('#amounti').val();
    $('#amounti').val(parseFloat(total).toFixed(2));
});

$("#commente").change(function () {
    var glosa = $("#commente").val();
    $("#commenti").val(glosa);
});
