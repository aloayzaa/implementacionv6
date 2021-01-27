$(document).ready(function () {

});

$('#codigo').blur(function () {
    if($('#codigo').val() != ''){
        calcular_fechas();
    }
});

function calcular_fechas() {
    $.ajax({
        type: "GET",
        url: "/periods/getfechas",
        data: {
            codigo: $('#codigo').val(),
        },
        success: function (data) {
            $('#f_inicio').val(data.fecha_inicio);
            $('#f_final').val(data.fecha_final);
        },
        error: function (data) {
            mostrar_errores(data);
        },
        complete: function () {
            console.log('completado');
        }
    });
}

function guardar() {
    store();
}
function actualizar() {
    update();
}
