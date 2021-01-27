$("#mostrar").click(function () {
    var table = $('#listSales');
    var thead = $('#listSales thead');
    var tbody = $('#listSales tbody');
    //var tfoot = $('#listSales tfoot');
    var dato = [];

    $.ajax({
        url: "/sales/list",
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
                                text: 'PLE 5.0 Normal',
                                action: function () {
                                    generar_ple_ventas(1);
                                }
                            },
                            {
                                text: 'PLE 5.0 Simplificado',
                                action: function () {
                                    generar_ple_ventas(2);
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

    dthead += ("<th rowspan='1' colspan='2'>Correlativo</th>");
    dthead += ("<th rowspan='2' colspan='1'>CUO</th>");
    dthead += ("<th rowspan='2' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='5'>Documento</th>");
    dthead += ("<th rowspan='1' colspan='2'>Cliente</th>");
    dthead += ("<th rowspan='1' colspan='1'>Valor Fact.</th>");
    dthead += ("<th rowspan='1' colspan='2'>Base Imponible</th>");
    dthead += ("<th rowspan='1' colspan='2'>Operación</th>");
    dthead += ("<th rowspan='1' colspan='2'>I.G.V. / I.P.M.</th>");
    dthead += ("<th rowspan='2' colspan='1'>I.S.C.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Otros</th>");
    dthead += ("<th rowspan='2' colspan='1'>Total</th>");
    dthead += ("<th rowspan='2' colspan='1'>Total M.E.</th>");
    dthead += ("<th rowspan='2' colspan='1'>Mon</th>");
    dthead += ("<th rowspan='2' colspan='1'>T. Cambio</th>");
    dthead += ("<th rowspan='1' colspan='4'>Referencias</th>");
    dthead += ("<th rowspan='2' colspan='1'>Contrato</th>");
    dthead += ("<th rowspan='2' colspan='1'>Error 1</th>");
    dthead += ("<th rowspan='2' colspan='1'>M. Pago</th>");
    dthead += ("<th rowspan='1' colspan='2'>Retención</th>");
    dthead += ("<th rowspan='1' colspan='1'>Valor</th>");
    dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
    dthead += ("<th rowspan='2' colspan='1'>Nro. Asiento</th>");
    dthead += ("<th rowspan='2' colspan='1'>Usuario</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Periodo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>Vence</th>");
    dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>RUC</th>");
    dthead += ("<th rowspan='1' colspan='1'>Razón Social</th>");
    dthead += ("<th rowspan='1' colspan='1'>Exportación</th>");
    dthead += ("<th rowspan='1' colspan='1'>Op. Gravada</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descuento</th>");
    dthead += ("<th rowspan='1' colspan='1'>Exoneración</th>");
    dthead += ("<th rowspan='1' colspan='1'>Inafecta</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descuento</th>");
    dthead += ("<th rowspan='1' colspan='1'>Impuestos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Importe</th>");
    dthead += ("<th rowspan='1' colspan='1'>Referencia</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data.data[i].periodo + "</td>");
            dtbody += ("<td>" + data.data[i].numero + "</td>");
            dtbody += ("<td>" + data.data[i].voucher_id + "</td>");
            dtbody += ("<td>" + data.data[i].voucher_item + "</td>");
            dtbody += ("<td>" + data.data[i].fechadoc + "</td>");
            dtbody += ("<td>" + data.data[i].vencimiento + "</td>");
            dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
            dtbody += ("<td>" + data.data[i].seriedoc + "</td>");
            dtbody += ("<td>" + data.data[i].numerodoc + "</td>");
            dtbody += ("<td>" + data.data[i].nrodocide + "</td>");
            dtbody += ("<td>" + data.data[i].nombre + "</td>");

            if (moneda === 'N') {
                dtbody += ("<td>" + data.data[i].exportamn + "</td>");
                dtbody += ("<td>" + data.data[i].basemn + "</td>");
                dtbody += ("<td>" + data.data[i].dsctobasemn + "</td>");
                dtbody += ("<td>" + data.data[i].exoneramn + "</td>");
                dtbody += ("<td>" + data.data[i].inafectomn + "</td>");
                dtbody += ("<td>" + data.data[i].igvmn + "</td>");
                dtbody += ("<td>" + data.data[i].dsctoigvmn + "</td>");
                dtbody += ("<td>" + data.data[i].iscmn + "</td>");
                dtbody += ("<td>" + data.data[i].otrosmn + "</td>");
                dtbody += ("<td>" + data.data[i].totalmn + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].exportame + "</td>");
                dtbody += ("<td>" + data.data[i].baseme + "</td>");
                dtbody += ("<td>" + data.data[i].dsctobaseme + "</td>");
                dtbody += ("<td>" + data.data[i].exonerame + "</td>");
                dtbody += ("<td>" + data.data[i].inafectome + "</td>");
                dtbody += ("<td>" + data.data[i].igvme + "</td>");
                dtbody += ("<td>" + data.data[i].dsctoigvme + "</td>");
                dtbody += ("<td>" + data.data[i].iscme + "</td>");
                dtbody += ("<td>" + data.data[i].otrosme + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
            }

            dtbody += ("<td>" + data.data[i].cmoneda + "</td>");
            dtbody += ("<td>" + data.data[i].tcambio + "</td>");
            dtbody += ("<td>" + data.data[i].fecharef + "</td>");
            dtbody += ("<td>" + data.data[i].tipodocref + "</td>");
            dtbody += ("<td>" + data.data[i].serieref + "</td>");
            dtbody += ("<td>" + data.data[i].numeroref + "</td>");
            dtbody += ("<td>" + data.data[i].contrato + "</td>");
            dtbody += ("<td>" + data.data[i].error1 + "</td>");
            dtbody += ("<td>" + data.data[i].mpago + "</td>");
            dtbody += ("<td>" + data.data[i].numeroret + "</td>");
            dtbody += ("<td>" + data.data[i].importeret + "</td>");
            dtbody += ("<td>" + data.data[i].referencial + "</td>");
            dtbody += ("<td>" + data.data[i].glosa + "</td>");
            dtbody += ("<td>" + data.data[i].voucher + "</td>");
            dtbody += ("<td>" + data.data[i].usuario + "</td>");
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

function generar_ple_ventas(tipo) {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_ventas_ple",
        dataType: "json",
        data: $("#frm_reporte").serialize() + "?tipo=" + tipo,
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
