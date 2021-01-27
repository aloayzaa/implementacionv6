$("#mostrar").click(function () {
    var table = $('#listBenefitDeclaration');
    var thead = $('#listBenefitDeclaration thead');
    var tbody = $('#listBenefitDeclaration tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/benefitdeclaration/list",
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
                                text: 'TXT',
                                action: function () {
                                    generar_txt_programa_beneficios();
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
    if (type === 'T') {
        dthead += ("<th rowspan='2' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='2'>Valor</th>");
    } else if (type === 'F') {
        dthead += ("<th rowspan='2' colspan='1'>T. Compra</th>");
        dthead += ("<th rowspan='1' colspan='3'>Comprobante</th>");
        dthead += ("<th rowspan='1' colspan='3'>Declarante</th>");
        dthead += ("<th rowspan='1' colspan='1'>Medio</th>");
        dthead += ("<th rowspan='2' colspan='1'>Banco</th>");
        dthead += ("<th rowspan='2' colspan='1'>Operación</th>");
        dthead += ("<th rowspan='2' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='2' colspan='1'>Monto</th>");
    } else {
        dthead += ("<th rowspan='2' colspan='1'>T. Compra</th>");
        dthead += ("<th rowspan='1' colspan='4'>Comprobante</th>");
        dthead += ("<th rowspan='1' colspan='8'>Declarante</th>");
        dthead += ("<th rowspan='2' colspan='1'>Mon.</th>");
        dthead += ("<th rowspan='1' colspan='2'>Destino</th>");
        dthead += ("<th rowspan='2' colspan='1'>Base</th>");
        dthead += ("<th rowspan='2' colspan='1'>ISC</th>");
        dthead += ("<th rowspan='2' colspan='1'>I.G.V.</th>");
        dthead += ("<th rowspan='2' colspan='1'>Otros</th>");
        if (type === 'V') {
            dthead += ("<th rowspan='1' colspan='4'>Percepción</th>");
            dthead += ("<th rowspan='1' colspan='6'>Referencia</th>");
        } else {
            dthead += ("<th rowspan='1' colspan='3'>Detracción</th>");
            dthead += ("<th rowspan='2' colspan='1'>Retención</th>");
            dthead += ("<th rowspan='1' colspan='6'>Referencia</th>");
        }
    }
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    if (type === 'T') {
        dthead += ("<th rowspan='1' colspan='1'>Compra</th>");
        dthead += ("<th rowspan='1' colspan='1'>Venta</th>");
    } else if (type === 'F') {
        dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>T. Perso</th>");
        dthead += ("<th rowspan='1' colspan='1'>T. Docum</th>");
        dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
        dthead += ("<th rowspan='1' colspan='1'>Pago</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>T. Perso</th>");
        dthead += ("<th rowspan='1' colspan='1'>T. Docum</th>");
        dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
        dthead += ("<th rowspan='1' colspan='1'>Razón Social</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ap. Paterno</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ap. Materno</th>");
        dthead += ("<th rowspan='1' colspan='1'>Nombre1</th>");
        dthead += ("<th rowspan='1' colspan='1'>Nombre2</th>");
        dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Es</th>");

        if (type === 'V') {
            dthead += ("<th rowspan='1' colspan='1'>Tasa</th>");
            dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
            dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        } else {
            dthead += ("<th rowspan='1' colspan='1'>Código</th>");
            dthead += ("<th rowspan='1' colspan='1'>Constancia</th>");
        }

        dthead += ("<th rowspan='1' colspan='1'>T. Comprob</th>");
        dthead += ("<th rowspan='1' colspan='1'>Serie</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Base</th>");
        dthead += ("<th rowspan='1' colspan='1'>I.G.V.</th>");
    }

    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            if (type === 'T') {
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].t_compra + "</td>");
                dtbody += ("<td>" + data.data[i].t_venta + "</td>");
            } else if (type === 'F') {
                dtbody += ("<td>" + data.data[i].tcompra + "</td>");
                dtbody += ("<td>" + data.data[i].tcomprob + "</td>");
                dtbody += ("<td>" + data.data[i].serie + "</td>");
                dtbody += ("<td>" + data.data[i].numero + "</td>");
                dtbody += ("<td>" + data.data[i].tpersona + "</td>");
                dtbody += ("<td>" + data.data[i].tdocum + "</td>");
                dtbody += ("<td>" + data.data[i].documento + "</td>");
                dtbody += ("<td>" + data.data[i].mpago + "</td>");
                dtbody += ("<td>" + data.data[i].banco + "</td>");
                dtbody += ("<td>" + data.data[i].operacion + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].monto + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].tcompra + "</td>");
                dtbody += ("<td>" + data.data[i].tcomprob + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].serie + "</td>");
                dtbody += ("<td>" + data.data[i].numero + "</td>");
                dtbody += ("<td>" + data.data[i].tpersona + "</td>");
                dtbody += ("<td>" + data.data[i].tdocum + "</td>");
                dtbody += ("<td>" + data.data[i].documento + "</td>");
                dtbody += ("<td>" + data.data[i].r_social + "</td>");
                dtbody += ("<td>" + data.data[i].ap_paterno + "</td>");
                dtbody += ("<td>" + data.data[i].ap_materno + "</td>");
                dtbody += ("<td>" + data.data[i].nombre1 + "</td>");
                dtbody += ("<td>" + data.data[i].nombre2 + "</td>");
                dtbody += ("<td>" + data.data[i].tmoneda + "</td>");
                dtbody += ("<td>" + data.data[i].destino + "</td>");
                dtbody += ("<td>" + data.data[i].nrodestino + "</td>");
                dtbody += ("<td>" + data.data[i].base + "</td>");
                dtbody += ("<td>" + data.data[i].isc + "</td>");
                dtbody += ("<td>" + data.data[i].igv + "</td>");
                dtbody += ("<td>" + data.data[i].otros + "</td>");
                dtbody += ("<td>" + data.data[i].base + "</td>");
                if (type === 'V') {
                    dtbody += ("<td>" + data.data[i].es_percep + "</td>");
                    dtbody += ("<td>" + data.data[i].tasa + "</td>");
                    dtbody += ("<td>" + data.data[i].ser_percep + "</td>");
                    dtbody += ("<td>" + data.data[i].nro_percep + "</td>");
                } else {
                    dtbody += ("<td>" + data.data[i].es_detrac + "</td>");
                    dtbody += ("<td>" + data.data[i].cod_detrac + "</td>");
                    dtbody += ("<td>" + data.data[i].constancia + "</td>");
                    dtbody += ("<td>" + data.data[i].es_retenc + "</td>");
                }
                dtbody += ("<td>" + data.data[i].ref_tcomprob + "</td>");
                dtbody += ("<td>" + data.data[i].ref_serie + "</td>");
                dtbody += ("<td>" + data.data[i].ref_numero + "</td>");
                dtbody += ("<td>" + data.data[i].ref_fecha + "</td>");
                dtbody += ("<td>" + data.data[i].ref_base + "</td>");
                dtbody += ("<td>" + data.data[i].ref_igv + "</td>");
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

function generar_txt_programa_beneficios() {
    var variable = $('#var').val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_programa_beneficios",
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
