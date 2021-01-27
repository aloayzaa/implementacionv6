$("#mostrar").click(function () {
    var table = $('#listRetentionAgents');
    var thead = $('#listRetentionAgents thead');
    var tbody = $('#listRetentionAgents tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/retentionagents/list",
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
                                    generar_pdt_agente_retencion_cuarta();
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
    if (type === 'P') {
        dthead += ("<th rowspan='1' colspan='5'>Proveedor</th>");
        dthead += ("<th rowspan='1' colspan='3'>Comprobante Retención</th>");
        dthead += ("<th rowspan='1' colspan='6'>Documento Aplica</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='1'>Motivo</th>");
        dthead += ("<th rowspan='1' colspan='3'>Comprobante Retención</th>");
        dthead += ("<th rowspan='1' colspan='3'>Proveedor</th>");
        dthead += ("<th rowspan='1' colspan='4'>Datos de la Retención</th>");
        dthead += ("<th rowspan='1' colspan='6'>Documento que Aplica</th>");
        dthead += ("<th rowspan='1' colspan='4'>Del Pago</th>");
        dthead += ("<th rowspan='1' colspan='3'>Datos de la Retención</th>");
        dthead += ("<th rowspan='1' colspan='3'>Del Tipo de Cambio</th>");
    }

    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    if (type === 'P') {
        dthead += ("<th rowspan='1' colspan='1'>R.U.C.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Razón Social</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ap. Paterno</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ap. Materno</th>");
        dthead += ("<th rowspan='1' colspan='1'>Nombres</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Monto</th>");
        dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Moneda</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total M.N.</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='1'>Contin.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número Doc.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Raz. Social</th>");
        dthead += ("<th rowspan='1' colspan='1'>Regimen</th>");
        dthead += ("<th rowspan='1' colspan='1'>Tasa</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total Reten.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total Pagado</th>");
        dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total</th>");
        dthead += ("<th rowspan='1' colspan='1'>Moneda</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total</th>");
        dthead += ("<th rowspan='1' colspan='1'>Moneda</th>");
        dthead += ("<th rowspan='1' colspan='1'>Importe</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Total Pagar</th>");
        dthead += ("<th rowspan='1' colspan='1'>Moneda</th>");
        dthead += ("<th rowspan='1' colspan='1'>T. Cambio</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    }

    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            if (type === 'H') {
                dtbody += ("<td>" + data.data[i].nrodocide + "</td>");
                dtbody += ("<td>" + data.data[i].rsocial + "</td>");
                dtbody += ("<td>" + data.data[i].apaterno + "</td>");
                dtbody += ("<td>" + data.data[i].amaterno + "</td>");
                dtbody += ("<td>" + data.data[i].nombres + "</td>");
                dtbody += ("<td>" + data.data[i].ret_numero + "</td>");
                dtbody += ("<td>" + data.data[i].ret_fecha + "</td>");
                dtbody += ("<td>" + data.data[i].retencion + "</td>");
                dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
                dtbody += ("<td>" + data.data[i].numerodoc + "</td>");
                dtbody += ("<td>" + data.data[i].fechadoc + "</td>");
                dtbody += ("<td>" + data.data[i].moneda + "</td>");
                dtbody += ("<td>" + data.data[i].total + "</td>");
                dtbody += ("<td>" + data.data[i].totalmn + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].motivo + "</td>");
                dtbody += ("<td>" + data.data[i].serieret + "</td>");
                dtbody += ("<td>" + data.data[i].numeroret + "</td>");
                dtbody += ("<td>" + data.data[i].fecharet + "</td>");
                dtbody += ("<td>" + data.data[i].tipodocide + "</td>");
                dtbody += ("<td>" + data.data[i].nrodocide + "</td>");
                dtbody += ("<td>" + data.data[i].rsocial + "</td>");
                dtbody += ("<td>" + data.data[i].tre_regimen + "</td>");
                dtbody += ("<td>" + data.data[i].tre_tasa + "</td>");
                dtbody += ("<td>" + data.data[i].tre_retenido + "</td>");
                dtbody += ("<td>" + data.data[i].tre_pagado + "</td>");
                dtbody += ("<td>" + data.data[i].ref_tipodoc + "</td>");
                dtbody += ("<td>" + data.data[i].ref_seriedoc + "</td>");
                dtbody += ("<td>" + data.data[i].ref_numerodoc + "</td>");
                dtbody += ("<td>" + data.data[i].ref_fechadoc + "</td>");
                dtbody += ("<td>" + data.data[i].ref_total + "</td>");
                dtbody += ("<td>" + data.data[i].ref_moneda + "</td>");
                dtbody += ("<td>" + data.data[i].pag_fecha + "</td>");
                dtbody += ("<td>" + data.data[i].pag_numero + "</td>");
                dtbody += ("<td>" + data.data[i].pag_total + "</td>");
                dtbody += ("<td>" + data.data[i].pag_moneda + "</td>");
                dtbody += ("<td>" + data.data[i].ret_importe + "</td>");
                dtbody += ("<td>" + data.data[i].ret_fecha + "</td>");
                dtbody += ("<td>" + data.data[i].ret_pago + "</td>");
                dtbody += ("<td>" + data.data[i].mon_ext + "</td>");
                dtbody += ("<td>" + data.data[i].mon_tcambio + "</td>");
                dtbody += ("<td>" + data.data[i].mon_fecha + "</td>");
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

function generar_pdt_agente_retencion_cuarta() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_agentes_de_retencion_pdt",
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
