$('#submitBlock').click(function () {
    var date = $('#single_cal4').val();
    var route = $(this).data('route');
    var value = $(this).data('value');

    $.ajax({
        url: route,
        type: 'get',
        data: '&date=' + date + '&value=' + value,
        success: function (data) {
            if (data === 'ok') {
                success('success', "Enviado");
            } else {
                error('error', "Error al enviar");
            }
        }
    });
});

$('#single_cal4').on('change', function () {
    var date = $(this).val();
    var route = $(this).data('route');
    var table = $('#dailySummary');
    var btn = $('#submitBlock');
    var tbody = $('#dailySummary tbody');
    var $data = '';

    $.ajax({
        url: route,
        type: "get",
        data: '&date=' + date,
        success: function (data) {

            if (!$.isEmptyObject(data)) {

                var $subtotal = 0;
                var $igv = 0;
                var $total = 0;

                btn.show();
                table.DataTable().destroy();
                tbody.empty();

                $.each(data, function (i) {
                    $data += ("<tr>");
                    $data += ("<td>" + data[i].id + "</td>");
                    $data += ("<td>" + data[i].seriedoc + "</td>");
                    $data += ("<td>" + data[i].numerodoc + "</td>");
                    $data += ("<td>" + data[i].razonsocial + "</td>");
                    $data += ("<td>" + data[i].fechaproceso + "</td>");
                    $data += ("<td>" + data[i].base + "</td>");
                    $data += ("<td>" + data[i].impuesto + "</td>");
                    $data += ("<td>" + data[i].total + "</td>");
                    $data += ("</tr>");

                    $subtotal += parseFloat(data[i].base);
                    $igv += parseFloat(data[i].impuesto);
                    $total += parseFloat(data[i].total);
                });

                var datas = [];

                datas.push(data.length);
                datas.push($subtotal.toFixed(2));
                datas.push($igv.toFixed(2));
                datas.push($total.toFixed(2));

                tbody.html($data);
                table.DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                        }
                    }
                );
                totalDaily(datas);
            } else {

                var datas = [];

                datas.push(0);
                datas.push(0);
                datas.push(0);
                datas.push(0);

                tbody.empty();
                $data += ("<tr>");
                $data += ("<td colspan='8' class='text-center'>Ningún dato disponible en esta tabla</td>");
                $data += ("</tr>");
                tbody.html($data);
                btn.hide();
                totalDaily(datas)
            }
        }
    });
});

function totalDaily(datas) {
    var tbody = $('#totalDaily tbody');
    var $data = '';

    tbody.empty();

    $data += ("<tr>");
    $data += ("<td>" + datas[0] + "</td>");
    $data += ("<td>" + datas[1] + "</td>");
    $data += ("<td>" + datas[2] + "</td>");
    $data += ("<td>" + datas[3] + "</td>");
    $data += ("</tr>");

    tbody.html($data);
}
