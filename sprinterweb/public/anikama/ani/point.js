$(document).ready(function () {
});

function update_table() {
    $("#table-puntos-venta").DataTable().ajax.reload();
}

function limpiar_crear() {
    $("#frm_generales")[0].reset();
}

function limpiar_editar() {
    $("#frm_generales")[0].reset();
}

function modal(id, name) {
    $('#exampleModalEditar').modal('show');
    $("#id").val(id);
    $("#namep").val(name);
}

function btn(){
    error('error', 'Por favor active su punto de venta', 'Punto de venta desactivado');
}
