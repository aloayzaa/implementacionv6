$('#btn_grabar').click(function () {
    var attr = $(this).is("[disabled]");
    if (attr === false) {
        store();
    } else {
        warning('warning', 'Formulario incompleto.', 'Alerta!');
    }
});

$('#btn_editar').click(function () {
    var attr = $(this).is("[disabled]");

    if (attr === false) {
        update();
    } else {
        warning('warning', 'En modo lectura.', 'Alerta!');
    }
});

function store() {
    var variable = $("#var").val();
    var token = $("#_token").val();
    var route = $('#frm_generales').data('route');

    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "POST",
        url: "/" + variable + "/store",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data.estado === "ok") {
                redireccionar(route, data.proceso);
            } else {
                error("error", data.bd);
            }
        }, beforeSend: function () {
            $('body').append('<div class="m-loader--mg loader-page"><span class="text-white">CARGANDO</span></div>');
        }, complete: function () {
            $(".loader-page").remove();
        }
    });
}

function update() {
    var variable = $("#var").val();
    var token = $("#_token").val();
    var id = $("#id").val();
    var route = $('#frm_generales').data('route');

    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "PUT",
        url: "/" + variable + "/" + id,
        dataType: "JSON",
        data: $("#frm_generales").serialize(),

        success: function (data) {
            if (data.estado == "ok") {
                if (!$("#cart_table")) {
                    $(".table").DataTable().ajax.reload();
                }
                redireccionar(route, data.proceso);
            } else {
                error("error", data.bd, "Error");
            }
        }, beforeSend: function () {
            startLoading();
        }, complete: function () {
            endLoading();
        }
    });
}

$("#btn_actualizar").click(function () {
    var id = $("#id").val();
    var route = $('#frm_generales').data('route');
    var estado = $("#estado").val();
    actualizar(id, route, estado);
});

function actualizar(id, route, estado) {
    if (estado === '1' || estado === 'Activo') {
        var mensaje = 'Activará';
        var confirm = 'activar';
    } else if (estado === '0' || estado === 'Anulado') {
        var mensaje = 'Anulará';
        var confirm = 'anular';
    }

    swal.fire({
        title: '¿' + mensaje + ' el registro?',
        text: "Modificará el registro con id: " + id + "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, " + confirm + "",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            var variable = $('#var').val();
            $.ajax({
                url: "/" + variable + "/activar-anular/" + id,
                type: "GET",
                data: {estado: estado},
                success: function (data) {
                    if (data.estado === 'ok') {
                        redireccionar(route, 'edita');
                        success("success", data.mensaje, "Actualizado");
                    } else {
                        error("error", data.mensaje, "Error");
                    }
                }
            });
        }
    });
}

//!!!!!!!!!NO BORRAR!!!!!!!!!!!
function procesar_actualizar(id, estado) {
    swal.fire({
        title: '¿Actualizará el registro?',
        text: "Modificara el registro " + id + "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, actualizar",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            var variable = $('#var').val();
            $.ajax({
                url: "/" + variable + "/activar-anular/" + id,
                type: "GET",
                data: {estado: estado},
                success: function (data) {
                    if (data.estado === 'ok') {
                        $(".table").DataTable().ajax.reload();
                        success("success", data.mensaje, "Actualizado");
                    } else {
                        error("error", data.mensaje, "Error");
                    }
                }, beforeSend: function () {
                    startLoading();
                }, complete: function () {
                    endLoading();
                }
            });
        }
    });
}
