
$("#estransferencia").click(function () {
    if ($(this).prop('checked')) {
        // Selecciona cada input que tenga la clase .checar
        $('#esajuste').prop( "checked", false );
    } else {
        // Deselecciona cada input que tenga la clase .checar
        $('#esajuste').prop( "checked", false );
    }

});

$("#esajuste").click(function () {
    if ($(this).prop('checked')) {
        // Selecciona cada input que tenga la clase .checar
        $('#estransferencia').prop( "checked", false );
    } else {
        // Deselecciona cada input que tenga la clase .checar
        $('#estransferencia').prop( "checked", false );
    }
});

$("#pidedocumento").click(function () {
    if ($(this).prop('checked')) {
        // Selecciona cada input que tenga la clase .checar
        $('#origen').prop( "disabled", false );
    } else {
        // Deselecciona cada input que tenga la clase .checar
        $('#origen').prop( "disabled", true );
    }
});



function guardar() {
    store();
}
function actualizar() {
   update();
}
