function abrir_cerrar_periodo(id, estado, modulo) {
    var variable = $("#var").val();
    $.ajax({
        type: "POST",
        url: "/" + variable + "/abrir_cerrar_periodo",
        dataType: "JSON",
        data: $("#frm_generales").serialize() + '&id=' + id + '&estado=' + estado + '&modulo=' + modulo,
        success: function (data) {
            if (data.estado === "ok") {
                $(".table").DataTable().ajax.reload();
            }
        }
    });
}

$("#btn_buscar_per").click(function () {
    var codigo = $("#codigo").val();
    tableOpenClosePeriods.init(this.value + '?codigo=' + codigo);
});
