$("#btnmostrar").click(function () {
    var period = $("#period").val();
    tableDetailMovementAccount.init(this.value + '?period=' + period);
});

function generar_ple_normal_movimiento_ctacte() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_movimiento_ctacte_ple",
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
