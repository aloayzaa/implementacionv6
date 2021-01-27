$(document).ready(function () {
    var instancia = $("#instancia").val();
    var variable = $("#var").val();
/*    listar_detalle(instancia, variable);*/
    var fecha_tc = $("#txt_fecha").val();

        consultar_tipocambio(fecha_tc);
});

function guardar() {
    store()
}
function actualizar() {
    update()
}

$('#btn_procesar').click(function () {
    var attr = $(this).is("[disabled]");
    if (attr === false) {
        if ($('#proceso').val() == 'crea') {
            store();
        }else{
            update();
        }
    } else {
        warning('warning', 'Formulario incompleto.', 'Alerta!');
    }
});
