$(document).ready(function () {
    $("#mostrar").prop("disabled", false);
    $("#update_tcambio").prop("disabled", false); //validando que se cargue el documento completo
});


$("#mostrar").click(function () {
    var period = $("#period").val();
    var year = $("#year").val();
    tableExchangeRate.init(this.value + '?period=' + period + '&year=' + year);
});

$('#update_tcambio').click(function () {
    actualizar_tipos_cambio();
});

function actualizar_tipos_cambio() {
    $("#update_tcambio").prop("disabled", true);
    var variable = $("#var").val();

    $.ajax({
        type: "GET",
        url: "/" + variable + "/tiposdecambio",
        data: {
            period: $('#period').val(),
            year: $('#year').val(),
        },
        success: function (data) {
            console.log(data);
             $("#listExchangeRate").DataTable().ajax.reload();
             success("success", data.message, "Exito!");
        },
        error: function (data) {
            mostrar_errores(data);
        },
        complete: function () {
            $("#update_tcambio").prop("disabled", false);
        }
    });
}

function actualizar() {
    update()
}
