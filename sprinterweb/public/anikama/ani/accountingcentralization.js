$('#btn_procesar_centralizacion').click(function () {
    validar_fechas_periodo();
});

function validar_fechas_periodo() {
    var inicio = $("#txt_finicio").val();
    var fin = $("#txt_ffin").val();

    if (inicio === fin) {
        centralizacion_contable_modulo();
    } else {
        error("error", "La fecha de inicio no corresponde al mismo periodo que la fecha final.", 'Verifique!');
    }
}

function centralizacion_contable_modulo() {
    var variable = $("#var").val();
    $.ajax({
        type: "POST",
        url: "/" + variable + "/centraliza_modulo",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data[0].estado === 'ok') {
                success('success', data[0].mensaje, "Correcto!");
            } else {
                var aux = 1;
                $.each(data, function (i, item) {
                    var fila = '<tr><td>' + ceros_izquierda(3, aux) + '</td><td>' + obtener_fecha_actual() + '</td><td>' + item.mensaje + '</td></tr>';
                    $('#listAccountingCentralization').append(fila);
                    aux++;
                })
            }
        }
    });
}

function obtener_fecha_actual() {
    var d = new Date();

    var month = d.getMonth() + 1;
    var day = d.getDate();

    return d.getFullYear() + '/' +
        (('' + month).length < 2 ? '0' : '') + month + '/' +
        (('' + day).length < 2 ? '0' : '') + day;
}

$('#module').change(function () {
    var valor = $("#module").val();
    if (valor === '2') {
        $("#checkctacte").attr('disabled', false);
    } else {
        $("#checkctacte").attr('disabled', true);
    }
});
