$(document).ready(function () {
    $('#changerate').val(consultar_tipo_cambio($('#date').val()));
});

$('#date').on('change', function () {
    consultar_tipo_cambio($(this).val());
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
