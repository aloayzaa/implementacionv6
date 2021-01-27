$("#mostrar").click(function () {
    var table = $('#listPurchases');
    var thead = $('#listPurchases thead');
    var tbody = $('#listPurchases tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/purchases/list",
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
                                    generar_ple_normal_compras();
                                }
                            },
                            {
                                text: 'PLE Simplificado',
                                action: function () {
                                    generar_ple_simplificado();
                                }
                            },
                            {
                                text: 'PLE No Domiciliado',
                                action: function () {
                                    generar_ple_no_domiciliados();
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
    dthead += ("<th rowspan='1' colspan='6'>Documento</th>");
    dthead += ("<th rowspan='1' colspan='2'>Proveedor</th>");
    dthead += ("<th rowspan='1' colspan='2'>Adq. Exportación</th>");
    dthead += ("<th rowspan='1' colspan='2'>Adq. Mixtos</th>");
    dthead += ("<th rowspan='1' colspan='2'>Adq. Exoneración</th>");
    dthead += ("<th rowspan='1' colspan='1'>Adq. No</th>");
    dthead += ("<th rowspan='2' colspan='1'>I.S.C.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Otros</th>");
    dthead += ("<th rowspan='2' colspan='1'>Total</th>");
    dthead += ("<th rowspan='2' colspan='1'>Total M.E.</th>");
    dthead += ("<th rowspan='1' colspan='2'>Detracción</th>");
    dthead += ("<th rowspan='2' colspan='1'>Moneda</th>");
    dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
    dthead += ("<th rowspan='1' colspan='4'>Referencia</th>");
    dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
    dthead += ("<th rowspan='2' colspan='1'>Contrato</th>");
    dthead += ("<th rowspan='1' colspan='4'>Tipo Error</th>");
    dthead += ("<th rowspan='1' colspan='1'>Medio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Id</th>");
    dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
    dthead += ("<th rowspan='2' colspan='1'>Nro. Asiento</th>");
    dthead += ("<th rowspan='2' colspan='1'>Usuario</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Periodo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>Vence</th>");
    dthead += ("<th rowspan='1' colspan='1'>TD</th>");
    dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número DUA</th>");
    dthead += ("<th rowspan='1' colspan='1'>RUC</th>");
    dthead += ("<th rowspan='1' colspan='1'>Razón Social</th>");
    dthead += ("<th rowspan='1' colspan='1'>Base</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Base</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Base</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Gravadas</th>");
    dthead += ("<th rowspan='1' colspan='1'>Imptos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>Cambio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>TD</th>");
    dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Bien</th>");
    dthead += ("<th rowspan='1' colspan='1'>:1</th>");
    dthead += ("<th rowspan='1' colspan='1'>:2</th>");
    dthead += ("<th rowspan='1' colspan='1'>:3</th>");
    dthead += ("<th rowspan='1' colspan='1'>:4</th>");
    dthead += ("<th rowspan='1' colspan='1'>Pago</th>");
    dthead += ("<th rowspan='1' colspan='1'>Liq</th>");
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
            dtbody += ("<td>" + data.data[i].numerodocexp + "</td>");
            dtbody += ("<td>" + data.data[i].nrodocide + "</td>");
            dtbody += ("<td>" + data.data[i].nombre + "</td>");

            if (moneda === 'N') {
                dtbody += ("<td>" + data.data[i].baseexpmn + "</td>");
                dtbody += ("<td>" + data.data[i].igvexpmn + "</td>");
                dtbody += ("<td>" + data.data[i].basemixmn + "</td>");
                dtbody += ("<td>" + data.data[i].igvmixmn + "</td>");
                dtbody += ("<td>" + data.data[i].baseexomn + "</td>");
                dtbody += ("<td>" + data.data[i].igvexomn + "</td>");
                dtbody += ("<td>" + data.data[i].basenogmn + "</td>");
                dtbody += ("<td>" + data.data[i].iscmn + "</td>");
                dtbody += ("<td>" + data.data[i].otrosmn + "</td>");
                dtbody += ("<td>" + data.data[i].totalmn + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].baseexpme + "</td>");
                dtbody += ("<td>" + data.data[i].igvexpme + "</td>");
                dtbody += ("<td>" + data.data[i].basemixme + "</td>");
                dtbody += ("<td>" + data.data[i].igvmixme + "</td>");
                dtbody += ("<td>" + data.data[i].baseexome + "</td>");
                dtbody += ("<td>" + data.data[i].igvexome + "</td>");
                dtbody += ("<td>" + data.data[i].basenogme + "</td>");
                dtbody += ("<td>" + data.data[i].iscme + "</td>");
                dtbody += ("<td>" + data.data[i].otrosme + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
            }

            dtbody += ("<td>" + data.data[i].nrodocdet + "</td>");
            dtbody += ("<td>" + data.data[i].fechadocdet + "</td>");
            dtbody += ("<td>" + data.data[i].cmoneda + "</td>");
            dtbody += ("<td>" + data.data[i].tcambio + "</td>");
            dtbody += ("<td>" + data.data[i].fecharef + "</td>");
            dtbody += ("<td>" + data.data[i].tipodocref + "</td>");
            dtbody += ("<td>" + data.data[i].serieref + "</td>");
            dtbody += ("<td>" + data.data[i].numeroref + "</td>");
            dtbody += ("<td>" + data.data[i].tipobien + "</td>");
            dtbody += ("<td>" + data.data[i].contrato + "</td>");
            dtbody += ("<td>" + data.data[i].error1 + "</td>");
            dtbody += ("<td>" + data.data[i].error2 + "</td>");
            dtbody += ("<td>" + data.data[i].error3 + "</td>");
            dtbody += ("<td>" + data.data[i].error4 + "</td>");
            dtbody += ("<td>" + data.data[i].mpago + "</td>");
            dtbody += ("<td>" + data.data[i].liquidacion_id + "</td>");
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

function generar_ple_normal_compras() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_compras_plenormal",
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

function generar_ple_simplificado() {
    var variable = $("#var").val();

    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_compras_plesimplificado",
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

function generar_ple_no_domiciliados() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_compras_plenodomiciliado",
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
