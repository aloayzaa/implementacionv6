
$("#es_divisionaria").click(function () {
    if ($(this).prop('checked')) {
        $('#es_analisis').prop( "checked", false );
    } else {
        $('#es_analisis').prop( "checked", false );
    }

});

$("#es_analisis").click(function () {
    if ($(this).prop('checked')) {
        $('#es_divisionaria').prop( "checked", false );
    } else {
        $('#es_divisionaria').prop( "checked", false );
    }
});

$("#tipo_auxiliar").change(function () {

    if ($(this).val('T')) {

        $('#tipo_ajuste').val('D').trigger('change');

        if($('#dbalance').val() == "A"){
            $('#tipo_cambio').val('V').trigger('change');
        }else {
            $('#tipo_cambio').val('C').trigger('change');
        }

    }
});

function guardar() {
    store()
}
function actualizar() {
    update()
}
