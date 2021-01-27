$("#btnmostrar").click(function () {
    var table = $('#listLedger');
    var thead = $('#listLedger thead');
    var tbody = $('#listLedger tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/ledger/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var moneda = $("#currency").val();
                var type = $("#type").val();

                dato = defecto(data, moneda, type);

                thead.html(dato[0]);
                //tbody.html(dato[1]);
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
                                    generar_ple_normal_mayor();
                                }
                            },
                        ],
                    }
                );
            }
        }
    });
});

function defecto(data, moneda, type) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    if (type === 'A') {
        dthead += ("<th rowspan='2' colspan='1'>Cuenta</th>");
        dthead += ("<th rowspan='2' colspan='1'>Descripción</th>");
    }

    dthead += ("<th rowspan='2' colspan='1'>Operación</th>");
    dthead += ("<th rowspan='2' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
    dthead += ("<th rowspan='2' colspan='1'>Debe</th>");
    dthead += ("<th rowspan='2' colspan='1'>Haber</th>");
    dthead += ("<th rowspan='1' colspan='2'>Centro Costo</th>");
    dthead += ("<th rowspan='1' colspan='2'>Operación</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            if (type === 'A') {
                dtbody += ("<td>" + data[i].cuenta + "</td>");
                dtbody += ("<td>" + data[i].descripcion + "</td>");
            }
            dtbody += ("<td>" + data[i].operacion + "</td>");
            dtbody += ("<td>" + data[i].fecha + "</td>");
            dtbody += ("<td>" + data[i].glosa + "</td>");

            if (moneda === 'N') {
                dtbody += ("<td>" + data[i].cargomn + "</td>");
                dtbody += ("<td>" + data[i].abonomn + "</td>");
            } else {
                dtbody += ("<td>" + data[i].cargome + "</td>");
                dtbody += ("<td>" + data[i].abonome + "</td>");
            }

            dtbody += ("<td>" + data[i].ccosto + "</td>");
            dtbody += ("<td>" + data[i].ccosto_dsc + "</td>");
            dtbody += ("<td>" + data[i].op + "</td>");
            dtbody += ("<td>" + data[i].op_fecha + "</td>");
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

function generar_ple_normal_mayor() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_libromayor_plenormal",
        dataType: "json",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (data.estado === 'ok') {
                success("success", "Exportacion exitosa", "Exito!");
                ventanaSecundaria("/consumer/" + data.archivo);
            } else {
                error("error", "Se presentaron errores al generar el archivo", "Error!");
            }
        }
    });
}
