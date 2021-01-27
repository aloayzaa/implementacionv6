$("#mostrar").click(function () {
    var table = $('#listMonthlyIncomeTax');
    var thead = $('#listMonthlyIncomeTax thead');
    var tbody = $('#listMonthlyIncomeTax tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/monthlyincometax/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var type = $("#type").val();
                dato = defecto(data, type);

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
                                text: 'PDT 621',
                                action: function () {
                                    generar_pdt_igv_renta_mensual();
                                }
                            },
                        ],
                    }
                );
            }
        }
    });
});

function defecto(data, type) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    if (type === 'H') {
        dthead += ("<th rowspan='2' colspan='1'>R.U.C.</th>");
        dthead += ("<th rowspan='2' colspan='1'>Ap. Paterno</th>");
        dthead += ("<th rowspan='2' colspan='1'>Ap. Materno</th>");
        dthead += ("<th rowspan='2' colspan='1'>Nombres</th>");
        dthead += ("<th rowspan='1' colspan='3'>Valor</th>");
        dthead += ("<th rowspan='2' colspan='1'>Ret.</th>");
        dthead += ("<th rowspan='2' colspan='1'>IES</th>");
        dthead += ("<th rowspan='1' colspan='4'>Valor</th>");
    } else if (type === 'R') {
        dthead += ("<th rowspan='1' colspan='4'>Comprobante</th>");
        dthead += ("<th rowspan='1' colspan='7'>Declarante</th>");
    } else if (type === 'P') {
        dthead += ("<th rowspan='2' colspan='1'>R.U.C.</th>");
        dthead += ("<th rowspan='2' colspan='1'>Razón Social</th>");
        dthead += ("<th rowspan='1' colspan='4'>Comprobante Percepción</th>");
        dthead += ("<th rowspan='1' colspan='4'>Documento Origen</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total</th>");
        dthead += ("<th rowspan='2' colspan='1'>Tipo</th>");
    } else {
        dthead += ("<th rowspan='2' colspan='1'>R.U.C.</th>");
        dthead += ("<th rowspan='2' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='2' colspan='1'>Número</th>");
        dthead += ("<th rowspan='2' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='2' colspan='1'>Retención</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total</th>");
        dthead += ("<th rowspan='2' colspan='1'>Nombre</th>");
    }
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    if (type === 'H') {
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Bruto</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ret. Renta</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ret. Pensión</th>");
        dthead += ("<th rowspan='1' colspan='1'>Neto</th>");
    } else if (type === 'R') {
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Doc. Identidad</th>");
        dthead += ("<th rowspan='1' colspan='1'>Cliente</th>");
        dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Moneda</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total M.E.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Retención</th>");
    } else if (type === 'P') {
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Percepción</th>");
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Moneda</th>");
        dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
    }

    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            if (type === 'H') {
                dtbody += ("<td>" + data.data[i].ruc + "</td>");
                dtbody += ("<td>" + data.data[i].apaterno + "</td>");
                dtbody += ("<td>" + data.data[i].amaterno + "</td>");
                dtbody += ("<td>" + data.data[i].nombre + "</td>");
                dtbody += ("<td>" + data.data[i].serie + "</td>");
                dtbody += ("<td>" + data.data[i].numero + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].retencion + "</td>");
                dtbody += ("<td>" + data.data[i].ies + "</td>");
                dtbody += ("<td>" + data.data[i].monto + "</td>");
                dtbody += ("<td>" + data.data[i].renta + "</td>");
                dtbody += ("<td>" + data.data[i].pension + "</td>");
                dtbody += ("<td>" + data.data[i].neto + "</td>");
            } else if (type === 'R') {
                dtbody += ("<td>" + data.data[i].ret_numero + "</td>");
                dtbody += ("<td>" + data.data[i].ret_fecha + "</td>");
                dtbody += ("<td>" + data.data[i].nrodocide + "</td>");
                dtbody += ("<td>" + data.data[i].nombre + "</td>");
                dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
                dtbody += ("<td>" + data.data[i].numerodoc + "</td>");
                dtbody += ("<td>" + data.data[i].fechadoc + "</td>");
                dtbody += ("<td>" + data.data[i].moneda + "</td>");
                dtbody += ("<td>" + data.data[i].total + "</td>");
                dtbody += ("<td>" + data.data[i].totalmn + "</td>");
                dtbody += ("<td>" + data.data[i].retencion + "</td>");
            } else if (type === 'P') {
                dtbody += ("<td>" + data.data[i].ruc + "</td>");
                dtbody += ("<td>" + data.data[i].nombre + "</td>");
                dtbody += ("<td>" + data.data[i].serie + "</td>");
                dtbody += ("<td>" + data.data[i].numero + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].percepcion + "</td>");
                dtbody += ("<td>" + data.data[i].seriedoc + "</td>");
                dtbody += ("<td>" + data.data[i].numerodoc + "</td>");
                dtbody += ("<td>" + data.data[i].fechadoc + "</td>");
                dtbody += ("<td>" + data.data[i].moneda + "</td>");
                dtbody += ("<td>" + data.data[i].total + "</td>");
                dtbody += ("<td>" + data.data[i].tipo + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].ruc + "</td>");
                dtbody += ("<td>" + data.data[i].serie + "</td>");
                dtbody += ("<td>" + data.data[i].numero + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].retencion + "</td>");
                dtbody += ("<td>" + data.data[i].total + "</td>");
                dtbody += ("<td>" + data.data[i].nombre + "</td>");
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
    //dato[2] = dtfooter

    return dato;
}

function generar_pdt_igv_renta_mensual() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_igv_renta_mensual_pdt621",
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
