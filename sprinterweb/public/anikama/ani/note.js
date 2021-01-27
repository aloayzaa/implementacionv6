$('#note').on('change', function () {
    var note = $(this).val();
    var route = $(this).data('route');
    var typeNote = $('#typeNote');

    $.ajax({
        url: route,
        type: 'get',
        data: '&note=' + note,
        success: function (data) {
            clear();
            typeNote.empty();
            typeNote.append('<option selected disabled>-Seleccione-</option>');
            for (var i = 0; i < data.length; i++) {
                typeNote.append('<option value="' + data[i].descripcion + '">' + data[i].descripcion + '</option>');
            }
        }
    });
});

$('#typeNote').on('change', function () {
    $('#divBtn').show();
    if ($('#note').val() == 8) {
        $('#apply').show();
    }
});

$('#single_cal4').on('change', function () {
    var route = $(this).data('route');
    var date = $('#single_cal4').val();
    var table = $('#dailySummary');
    var tbody = $('#dailySummary tbody');
    var $data = '';

    $.ajax({
        url: route,
        type: 'get',
        data: '&date=' + date,
        success: function (data) {
            $('#saveChangeBtn').prop('disabled', true);
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                tbody.empty();
                $.each(data, function (i) {
                    $data += ("<tr>");
                    // $data += ("<td>" + data[i].id + "</td>");
                    $data += ("<td>" + data[i].seriedoc + "</td>");
                    $data += ("<td>" + data[i].numerodoc + "</td>");
                    $data += ("<td>" + data[i].razonsocial + "</td>");
                    /* $data += ("<td>" + data[i].fechaproceso + "</td>");
                     $data += ("<td>" + data[i].base + "</td>");
                     $data += ("<td>" + data[i].impuesto + "</td>");
                     $data += ("<td>" + data[i].total + "</td>");*/
                    $data += ("<td><a class='icon-button dropdownn'  onclick='ticket(this.id)' id='" + data[i].id + "'><i class='fa fa-angle-double-down'></i><span></span></a></td>");
                    $data += ("</tr>");
                });
                tbody.html($data);
                table.DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    }
                });
            } else {
                tbody.empty();
                // $data += ("<tr>");
                $data += ("<td colspan='4' class='text-center'>Ningún dato disponible en esta tabla</td>");
                // $data += ("</tr>");
                tbody.html($data);
                $('#details').html('');
            }
        }
    });
});

function ticket(id) {
    var route = $('#dailySummary').data('route');

    $.ajax({
        url: route,
        type: 'get',
        data: '&ticket=' + id,
        success: function (answer) {
            listDetails(answer[0]);
        }
    });
}

function listDetails(data) {
    var route = $('#details').data('route');
    var token = $('#details').data('token');

    $.ajax({
        url: route,
        type: 'post',
        data: '&data=' + JSON.stringify(data) + '&proceso=' + '1' + '&instancia=' + 'details' + '&variable=' + 'DAS' + '&escaner=' + '4' + '&_token=' + token,
        success: function (answer) {
            $('#details').html(answer);
            $('#saveChangeBtn').removeAttr("disabled");
        }
    });
}

$('#saveChangeBtn').on('click', function () {
    var checked = $("input[type=checkbox]:checked").last();
    var parent = checked.data('parent');
    var parentRoute = checked.data('parent-route');

    fillGeneralData(parent, parentRoute);
    fillSerieNumber();
    fillDetailData();

    $('#billing').show();
    clearModal();
    $('#exampleModal').modal('toggle');
});


function fillGeneralData(parent, route) {
    var note = $("#note").val();

    $.ajax({
        url: route,
        type: 'get',
        data: '&parent=' + parent,
        success: function (answer) {
            if (!$.isEmptyObject(answer.billing)) {
                // $('#date').val(answer.billing.fechaproceso);
                $('#billingId').val(answer.billing.id);
                $('#serieBilling').val(answer.billing.seriedoc + '-' + answer.billing.numerodoc);
                $('#trader').val(answer.user.usu_nombres + ' ' + answer.user.usu_apellidos);
                $('#typeSale').val(answer.typeSale.descripcion);
                $('#client').val(answer.billing.ruc);
                $('#name').val(answer.billing.razonsocial);
                $('#address').val(answer.billing.direccion);
                $('#serieTicket').val(answer.billing.seriedoc);
                $('#numberTicket').val(answer.billing.numerodoc);
                $('#currency').val(answer.currency.descripcion);
                $('#document').val(answer.document.descripcion);
                fillSerieNumber(answer.document.id, note);
            }
        }
    });
}

function fillSerieNumber(document, note) {
    var token = $("#details").data('token');

    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "POST",
        url: "/Facturacion/serie/numero",
        data: '&document=' + document + '&note=' + note,
        success: function (data) {
            $('#serie').val(data.serie);
            $('#number').val(data.numerodoc);
        }
    });
}

function fillDetailData() {

    var checked = $("input[type=checkbox]:checked");
    var $data = '';
    var total = 0;
    var table = $('#tableDetails');
    var tbody = $('#tableDetails tbody');

    table.DataTable().destroy();
    tbody.empty();

    checked.each(function () {
        if (!$.isEmptyObject($(this).data('product'))) {
            $data += ("<tr>");
            $data += ("<td>" + $(this).data('product') + "</td>");
            $data += ("<td>" + $(this).data('description') + "</td>");
            $data += ("<td>" + $(this).data('units') + "</td>");
            $data += ("<td>" + $(this).data('quantity') + "</td>");
            $data += ("<td>" + $(this).data('price') + "</td>");
            $data += ("<td>" + $(this).data('amount') + "</td>");
            $data += ("</tr>");

            total += parseFloat($(this).data('amount'));
        }
    });

    subtotal = total / 1.18;
    igv = total - subtotal;

    $('#subtotal').val(subtotal.toFixed(2));
    $('#igv').val(igv.toFixed(2));
    $('#total').val(total.toFixed(2));

    $(tbody).html($data);
    table.DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        }
    );
}

function clear() {
    $('#divBtn').hide();
    $('#apply').hide();

    // $('#date').val('');
    $('#trader').val('');
    $('#typeSale').val('');
    $('#client').val('');
    $('#name').val('');
    $('#address').val('');
    $('#currency').val('');
    $('#document').val('');
    $('#serie').val('');
    $('#number').val('');
    $('#subtotal').val('');
    $('#igv').val('');
    $('#total').val('');

    $('#billing ').hide();
}

function clearModal() {
    $('#single_cal4').change();
    $('#details').html('');
    tbody = $('#dailySummary tbody');
    tbody.empty();
    $data = '';
    // $data += ("<tr>");
    $data += ("<td colspan='8' class='text-center'>Ningún dato disponible en esta tabla</td>");
    // $data += ("</tr>");
    tbody.html($data);
}
