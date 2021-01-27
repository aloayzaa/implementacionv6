$("#btnmostrar").click(function () {
    var period = $("#period").val();
    var currency = $("#currency").val();
    tableCurrentAccountReport.init(this.value + '?period=' + period + '&currency=' + currency);
});

function generar_ple_cuenta_corriente() {
    var variable = $('#var').val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_balance_cuentas_corrientes_ple",
        dataType: "json",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (data.estado === 'ok') {
                success("success", "Exportacion exitosa", "Exito!");
                ventanaSecundaria("/consumer/" + data.archivo);
            } else {
                error("error", "Se presentaron errores al generar el archivo", "Error!");
            }
        }
    });
}
