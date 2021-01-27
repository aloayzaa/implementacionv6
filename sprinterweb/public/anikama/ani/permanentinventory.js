$("#mostrar").click(function () {
    var initialdate = $("#initialdate").val();
    var finishdate = $("#finishdate").val();

    tablePermamentInventory.init(this.value + '?&initialdate=' + initialdate + '&finishdate=' + finishdate);
});

function generar_ple_inventario_permanente() {
    var tipo = $("#cbo_formato").val();
    var variable = $("#var").val();
    if (tipo === 0) {
        error("error", "Archivo no Disponible", "Error!");
        return false;
    }

    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_inventario_permante_ple",
        dataType: "json",
        data: $("#frm_generales").serialize(),
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
