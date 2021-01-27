$("#value").change(function () {
    var value = parseFloat(this.value);
    $("#value").val(value.toFixed(2));
});
function guardar() {
    store()
}
function actualizar() {
    update()
}

function anula() {
    anular(0);
}

