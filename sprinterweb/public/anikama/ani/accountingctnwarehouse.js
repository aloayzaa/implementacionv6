$(document).ready(function () {
    llenarSelectCuentasByClass('cuentas', "/accountingplans/pcgs");
});

function guardar() {
    store();
}
function actualizar() {
    update();
}
