$(document).ready(function () {
    if ($('#tax').val() == 2) {
        $('#igv').hide();
    }

    if ($('#proceso').val() == 'ver') {
        $(":input").attr('disabled', 'disabled');
        $('#btn_editar').hide();
    } else if ($('#proceso').val() == 'crea') {
        $('#changerate').val(consultar_tipo_cambio($('#dateInitial').val()));
    }

});


$("#frm_generales").validate({
    rules: {
        date: {
            required: true
        },
        // customer: {
        //     required: true,
        // },
        // ruc: {
        //     required: true,
        // },
        series: {
            required: true,
        },
        numberOfSeries: {
            required: true
        },
        // address: {
        //     required: true,
        // },
        dateInitial: {
            required: true,
        },
        glosa: {
            required: true
        },
        dateEnd: {
            required: true,
        },
        changerate: {
            required: true,
        },
        currency: {
            required: true,
        },
        total: {
            required: true
        },
        tax: {
            required: true,
        },
        igv: {
            required: true,
        }
    },
    messages: {
        date: {
            required: "El campo día es requerido.",
        },
        // customer: {
        //     required: "Este campo es requerido.",
        // },
        // ruc: {
        //     required: "Este campo es requerido.",
        // },
        series: {
            required: "Requerido.",
        },
        numberOfSeries: {
            required: "Requerido.",
        },
        // address: {
        //     required: "Este campo es requerido.",
        // },
        dateInitial: {
            required: "Este campo es requerido.",
        },
        glosa: {
            required: "Este campo es requerido.",
        },
        dateEnd: {
            required: "Este campo es requerido.",
        },
        changerate: {
            required: "Este campo es requerido.",
        },
        currency: {
            required: "Este campo es requerido.",
        },
        total: {
            required: "Este campo es requerido.",
        },
        tax: {
            required: "Este campo es requerido.",
        },
        igv: {
            required: "Este campo es requerido.",
        }
    }
});


$('#customer').on('change', function () {
    var customer = $(this).val();
    var route = $(this).data('route');

    $.ajax({
        url: route,
        type: 'get',
        data: '&customer=' + customer,
        success: function (data) {
            if (data.estado === 'si') {
                process(data.third);
            } else if (data.estado === 'no') {
                warning('warning', 'Tercero no se encuentra registrado', 'Error');
            } else {
                // $('#name').val('');
                // $('#address').val('');
                // $('#document').val('0');
                // $("#document").prop('disabled', true);
                // $('#btn').hide();
                // $("#divAddress").show();
            }
        }
    });
});


function process(data) {
    if (typeof data['dni'] === 'undefined') {
        $('#ruc').val(data[0].emp_ruc);
        $("#address").val(data['domicilio']);
    } else {
        $("#name").val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);
        $("#document").val(4).change();
        $("#document").prop('disabled', false);
        $("#address").val(puntoventa.direccion);
        $("#address").removeAttr("readonly", "readonly");
    }
}

$('#document').on('change', function () {
    if ($('#proceso').val() == 'crea') {
        $('#numberOfSeries').val('');
    }
});

$('#dateInitial').on('change', function () {
    var date = $(this).val();
    var dateEnd = $("#dateEnd");

    consultar_tipo_cambio(date);

    dateEnd.attr("value", date);
    dateEnd.attr("min", date);
});

$('#tax').on('change', function () {
    var tax = $(this).val();

    if (tax == 1) {
        $('#igv').show();
    } else {
        $('#igv').hide();
    }
});

$('#glosa').on('focus', function () {
    $(this).val('');
});

$('#series').on('change', function () {
    $(this).val(ceros_izquierda(4, $(this).val()));
    $('#numberOfSeries').removeAttr('readonly')
});

$('#numberOfSeries').on('change', function () {
    $(this).val(ceros_izquierda(8, $(this).val()));

    var route = $(this).data('route');
    var number = $(this).val();
    var serie = $('#series').val();
    var document = $('#document').val();

    $.ajax({
        url: route,
        type: 'get',
        data: '&document=' + document + '&serie=' + serie + '&number=' + number,
        success: function (data) {
            if (data.resp == 1) {
                error("error", data.message);
                $('#numberOfSeries').val('');
            }
        }
    });
});

$('#total').on('change', function () {
    // var tax = $('#tax').val();
    var total = $(this).val();
    var route = $(this).data('route');

    // if (tax == 1) {
    $.ajax({
        url: route,
        type: 'get',
        data: '&total=' + total,
        success: function (data) {
            console.log(data);
            $('#igv').val(data);
        }
    });
    // }
});

function guardar() {
    store()
}
function actualizar() {
    update()
}

//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(1);
}
