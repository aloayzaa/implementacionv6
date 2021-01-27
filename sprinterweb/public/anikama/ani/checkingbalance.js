$("#btnmostrar").click(function () {
    var initialdate = $("#initialdate").val();
    var finaldate = $("#finaldate").val();
    var currency = $("#currency").val();
    var digits = $("#digits").val();
    var checkclosing = checkbox('checkclosing');
    tableCheckingBalance.init(this.value + '?initialdate=' + initialdate + '&finaldate=' + finaldate + '&currency=' + currency + '&digits=' + digits + '&checkclosing=' + checkclosing);
});

function generar_ple_balance_comprobacion() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_balance_comprobacion_ple",
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

function generar_pdt_balance_comprobacion() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_balance_comprobacion_pdt",
        dataType: "json",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (data.estado === 'ok') {
                success("Exportacion exitosa");
                ventanaSecundaria("/consumer/" + data.archivo);
            } else {
                error("error", "Se presentaron errores al generar el archivo", "Error!");
            }
        }
    });
}
