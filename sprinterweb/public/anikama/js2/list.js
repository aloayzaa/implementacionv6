$(document).ready(function () {
    response();
    $("#proceso").val('');
    $("#route").val('');
});

function response() {
    var proceso = $("#proceso").val();
    var route = $("#route").val();

    if (proceso === 'edita') {
        changeurl(route);
        success("success", "Actualizado exitosamente", "Exito");
    } else if (proceso === 'crea') {
        changeurl(route);
        success("success", "Registrado exitosamente", "Exito");
    }
}

function changeurl(route) {
    window.history.pushState("data", "Title", route);
}
