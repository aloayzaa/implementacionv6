$("#btnmostrar").click(function () {
    var table = $('#listCashBanks');
    var thead = $('#listCashBanks thead');
    var tbody = $('#listCashBanks tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/cashbanks/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var moneda = $("#currency").val();
                dato = defecto(data, moneda);

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
                                text: 'PLE Normal',
                                action: function () {
                                    generar_ple_caja_bancos();
                                }
                            },
                        ],
                    }
                );
            }
        }
    });
});

function defecto(data, moneda) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='2'>Cuenta</th>");
    dthead += ("<th rowspan='2' colspan='1'>Banco</th>");
    dthead += ("<th rowspan='2' colspan='1'>Nro. Cuenta</th>");
    dthead += ("<th rowspan='2' colspan='1'>Moneda</th>");
    dthead += ("<th rowspan='1' colspan='2'>Saldo</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Deudor</th>");
    dthead += ("<th rowspan='1' colspan='1'>Acreedor</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data.data[i].cuenta + "</td>");
            dtbody += ("<td>" + data.data[i].denominacion + "</td>");
            dtbody += ("<td>" + data.data[i].banco + "</td>");
            dtbody += ("<td>" + data.data[i].nrocuenta + "</td>");
            dtbody += ("<td>" + data.data[i].moneda + "</td>");

            if (moneda === 'O') {
                if (data.data[i].moneda === 'PEN SOLES') {
                    dtbody += ("<td>" + data.data[i].cargomn + "</td>");
                } else {
                    dtbody += ("<td>" + data.data[i].cargome + "</td>");
                }
                if (moneda === 'PEN SOLES') {
                    dtbody += ("<td>" + data.data[i].abonomn + "</td>");
                } else {
                    dtbody += ("<td>" + data.data[i].abonome + "</td>");
                }

            } else if (moneda === 'N') {
                dtbody += ("<td>" + data.data[i].cargomn + "</td>");
                dtbody += ("<td>" + data.data[i].abonomn + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].cargome + "</td>");
                dtbody += ("<td>" + data.data[i].abonome + "</td>");
            }

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

function generar_ple_caja_bancos() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_balance_caja_banco_ple",
        dataType: "json",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (data.estado === 'ok') {
                success("success", "Exportacion exitosa", "Exito!");
                ventanaSecundaria("/consumer/" + data.archivo);
            } else {
                error("error", "Se presentaron errores al generar el archivo", "Error!");
            }
        },
    });
}
