$("#behavior").change(function () {
    var value = checkbox("behavior");
    if (value === 1) {
        $("#view").attr('disabled', false);
    } else if (value === 0) {
        $("#view").attr('disabled', true);
        $("#view").attr('checked', true);
    }
});

$("#btnmostrar").click(function () {
    var table = $('#listFinancialState');
    var thead = $('#listFinancialState thead');
    var tbody = $('#listFinancialState tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/financialstate/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {

            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var mensual = checkbox("behavior");
                dato = defecto(data, mensual);
                thead.html(dato[0]);
                tbody.html(dato[1]);
                //tfoot.html(dato[2]);
                table.DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                        },
                        "dom": 'Blfrtip',
                        "buttons": [
                            {
                                extend: 'excelHtml5',
                                footer: true
                            },
                            {
                                extend: 'csv',
                                footer: true
                            },
                            {
                                extend: 'pdf',
                                orientation: 'landscape',
                                pageSize: 'LEGAL',
                                title: "Sprinter Web - Libro Inventario Permanente",
                                footer: true,
                                customize: function (doc) {
                                    doc.defaultStyle.fontSize = 6;
                                    doc.styles.tableHeader.fontSize = 6;
                                    doc.styles.tableFooter.fontSize = 6;
                                }
                            },
                            {
                                text: 'PLE',
                                action: function () {
                                    generar_ple_balance_comprobacion();
                                }
                            },
                            {
                                text: 'PDT 0684',
                                action: function () {
                                    generar_pdt_balance_comprobacion();
                                }
                            },
                        ],
                    }
                );
            }
        }
    });
});

function defecto(data, mensual) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    if (mensual === 1) {
        dthead += ("<th rowspan='1' colspan='1'>Inicial</th>");
        dthead += ("<th rowspan='1' colspan='1'>Enero</th>");
        dthead += ("<th rowspan='1' colspan='1'>Febrero</th>");
        dthead += ("<th rowspan='1' colspan='1'>Marzo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Abril</th>");
        dthead += ("<th rowspan='1' colspan='1'>Mayo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Junio</th>");
        dthead += ("<th rowspan='1' colspan='1'>Julio</th>");
        dthead += ("<th rowspan='1' colspan='1'>Agosto</th>");
        dthead += ("<th rowspan='1' colspan='1'>Septiembre</th>");
        dthead += ("<th rowspan='1' colspan='1'>Octubre</th>");
        dthead += ("<th rowspan='1' colspan='1'>Noviembre</th>");
        dthead += ("<th rowspan='1' colspan='1'>Diciembre</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Importe</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data.data[i].descripcion + "</td>");
            if (mensual === 1) {
                dtbody += ("<td>" + data.data[i].inicial + "</td>");
                dtbody += ("<td>" + data.data[i].enero + "</td>");
                dtbody += ("<td>" + data.data[i].febrero + "</td>");
                dtbody += ("<td>" + data.data[i].marzo + "</td>");
                dtbody += ("<td>" + data.data[i].abril + "</td>");
                dtbody += ("<td>" + data.data[i].mayo + "</td>");
                dtbody += ("<td>" + data.data[i].junio + "</td>");
                dtbody += ("<td>" + data.data[i].julio + "</td>");
                dtbody += ("<td>" + data.data[i].agosto + "</td>");
                dtbody += ("<td>" + data.data[i].setiembre + "</td>");
                dtbody += ("<td>" + data.data[i].octubre + "</td>");
                dtbody += ("<td>" + data.data[i].noviembre + "</td>");
                dtbody += ("<td>" + data.data[i].diciembre + "</td>");
            }
            dtbody += ("<td>" + data.data[i].total + "</td>");
            dtbody += ("</tr>");
        });
    }

    /*dtfooter += ("<tr>");
    dtfooter += ("<th bgcolor='#f2b957'>Totales:</th>");
    if (monthly === 1 && costs === 0 || monthly === 0 && costs === 1) {
        dtfooter += ("<th bgcolor='#f2b957' colspan='2'></th>");
    } else if (monthly === 1 && costs === 1) {
        dtfooter += ("<th bgcolor='#f2b957' colspan='3'></th>");
    } else {
        dtfooter += ("<th bgcolor='#f2b957'></th>");
    }

    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("</tr>");*/

    dato[0] = dthead;
    dato[1] = dtbody;
    //dato[2] = dtfooter;

    return dato;
}
