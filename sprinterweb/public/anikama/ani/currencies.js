$("#typen").change(function () {
    var value = checkbox('typen');

    if (value === 1) {
        $("#typee").prop('checked', false);
        $("#typeo").prop('checked', false);
    }
});

$("#typee").change(function () {
    var value = checkbox('typee');

    if (value === 1) {
        $("#typen").prop('checked', false);
        $("#typeo").prop('checked', false);
    }
});

$("#typeo").change(function () {
    var value = checkbox('typeo');

    if (value === 1) {
        $("#typen").prop('checked', false);
        $("#typee").prop('checked', false);
    }
});
function guardar() {
    store()
}
function actualizar() {
    update()

}
