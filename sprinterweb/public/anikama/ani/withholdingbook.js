$("#mostrar").click(function () {
    var table = $('#listWithholdingBook');
    var thead = $('#listWithholdingBook thead');
    var tbody = $('#listWithholdingBook tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: this.value,
        type: "GET",
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
                            }
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

    dthead += ("<th rowspan='1' colspan='2'>Correlativo</th>");
    dthead += ("<th rowspan='1' colspan='5'>Documento</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='3'>Proveedor</th>");
    dthead += ("<th rowspan='1' colspan='4'>Monto Retribución</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Periodo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Vence</th>");
    dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Pago</th>");
    dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Nombre/Razón Social</th>");
    dthead += ("<th rowspan='1' colspan='1'>Bruto</th>");
    dthead += ("<th rowspan='1' colspan='1'>Ret. Renta</th>");
    dthead += ("<th rowspan='1' colspan='1'>Ret. Pensión</th>");
    dthead += ("<th rowspan='1' colspan='1'>Neto</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data.data[i].periodo + "</td>");
            dtbody += ("<td>" + data.data[i].numero + "</td>");
            dtbody += ("<td>" + data.data[i].fechadoc + "</td>");
            dtbody += ("<td>" + data.data[i].vencimiento + "</td>");
            dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
            dtbody += ("<td>" + data.data[i].seriedoc + "</td>");
            dtbody += ("<td>" + data.data[i].numerodoc + "</td>");
            dtbody += ("<td>" + data.data[i].fpago + "</td>");
            dtbody += ("<td>" + data.data[i].tipodocide + "</td>");
            dtbody += ("<td>" + data.data[i].nrodocide + "</td>");
            dtbody += ("<td>" + data.data[i].nombre + "</td>");

            if (moneda === 'N') {
                dtbody += ("<td>" + data.data[i].brutomn + "</td>");
                dtbody += ("<td>" + data.data[i].rentamn + "</td>");
                dtbody += ("<td>" + data.data[i].pensionmn + "</td>");
                dtbody += ("<td>" + data.data[i].netomn + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].brutome + "</td>");
                dtbody += ("<td>" + data.data[i].rentame + "</td>");
                dtbody += ("<td>" + data.data[i].pensionme + "</td>");
                dtbody += ("<td>" + data.data[i].netome + "</td>");
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
