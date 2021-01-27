$("#bank").change(function () {
    ctacte('currentaccount');
});

$("#currentaccount").change(function () {
    currency('currency');
});

$("#processdate").change(function () {
    consultar_tipo_cambio(this.value);
});

$("#total").change(function () {
    calcular(this.value);
});

$("#check").change(function () {
    habilitar('check');
});

function habilitar(id) {
    var value = checkbox(id);
    if (value === 1) {
        $("#checktxt").attr('readonly', false);
    } else {
        $("#checktxt").attr('readonly', true);
        $("#checktxt").val('');
    }
}

function calcular(total) {
    var tipo_moneda = $("#currency").val();
    var changerate = $("#changerate").val();
    if (tipo_moneda === '1') {
        var monedanac = total / changerate;
        $('#totalme').val(parseFloat(monedanac).toFixed(2));
        $('#totalmn').val(parseFloat(total).toFixed(2));
        $('#total').val($('#totalmn').val());
    } else if (tipo_moneda === '2') {
        var monedaex = total * changerate;
        $('#totalmn').val(parseFloat(monedaex).toFixed(2));
        $('#totalme').val(parseFloat(total).toFixed(2));
        $('#total').val($('#totalme').val());
    }
}
