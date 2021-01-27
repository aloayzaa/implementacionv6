$(document).ready(function () {
    var id_table = $('table').prop('id');
    var table = $('#' + id_table);
    init_datatable(table);
});

$("#mostrar").click(function () {
    var table = $('#listShoppingSummary');
    var thead = $('.' + id_table + ' thead');
    var tbody = $('.' + id_table + ' tbody');
    var tfoot = $('.' + id_table + ' tfoot');

    var ruta = $('#ruta').val();
    var lcDesde = $("#initialdate").val();
    var lcHasta = $("#finishdate").val();
    let lctipo = $("#type").val();
    var check = '';
    if ($("#comportamiento").prop('checked')){
        check = 'on';
    }
    var lcPeriodo = 0;

    var dato = [];

    if ($("#comportamiento").prop('checked')) {
        lcPeriodo = 1;
    }

    $.ajax({
        url: ruta,
        type: "GET",
        data: {lcDesde: lcDesde, lcHasta: lcHasta, lctipo: lctipo, lctipogeneral: 'R', lcPeriodo: lcPeriodo, check: check},
        success: function (data) {
            console.table(data);
            /*if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                tbody.empty();
                tfoot.empty();
                var money = $("#money").val();
                dato = defecto(data, money);
                thead.html(dato[0]);
                tbody.html(dato[1]);
                tfoot.html(dato[2]);
                init_datatable(table);
            }*/
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});

function defecto(data, money) {
    var dthead = '';
    var dtbody = '';
    var dtfooter = '';
    var dato = [];

    var subtotal = 0;
    var igv = 0;
    var total = 0;

    var subtotal_t = 0;
    var igv_t = 0;
    var total_t = 0;
    var lcCampo = '';

    var codigo = '';

    if ($("#comportamiento").prop('checked')) {
        dthead += ("<tr role='row'>");
        if ($("#type").val() != 'A') {
            dthead += ("<th>Código</th>");
            dthead += ("<th>Descripción</th>");
        } else {
            dthead += ("<th>Código</th>");
            dthead += ("<th>Descripción</th>");
            dthead += ("<th>Familia</th>");
        }
        if (data[0].length > 0) {
            $.each(data[1], function (i) {
                switch (data[1][i].periodo.substring(4, 6)) {
                    case '01': lcCampo = 'ENE_' + data[1][i].periodo.substring(0, 4); break;
                    case '02': lcCampo = 'FEB_' + data[1][i].periodo.substring(0, 4); break;
                    case '03': lcCampo = 'MAR_' + data[1][i].periodo.substring(0, 4); break;
                    case '04': lcCampo = 'ABR_' + data[1][i].periodo.substring(0, 4); break;
                    case '05': lcCampo = 'MAY_' + data[1][i].periodo.substring(0, 4); break;
                    case '06': lcCampo = 'JUN_' + data[1][i].periodo.substring(0, 4); break;
                    case '07': lcCampo = 'JUL_' + data[1][i].periodo.substring(0, 4); break;
                    case '08': lcCampo = 'AGO_' + data[1][i].periodo.substring(0, 4); break;
                    case '09': lcCampo = 'SET_' + data[1][i].periodo.substring(0, 4); break;
                    case '10': lcCampo = 'OCT_' + data[1][i].periodo.substring(0, 4); break;
                    case '11': lcCampo = 'NOV_' + data[1][i].periodo.substring(0, 4); break;
                    case '12': lcCampo = 'DIC_' + data[1][i].periodo.substring(0, 4); break;
                }
                dthead += ("<th>" + lcCampo + "</th>");
            });
        }
        dthead += ("<th>Total</th>");
        dthead += ("</tr>");
        if (data[0].length > 0) {
            var data_total = data[0];
            $.each(data[0], function (i) {
                if (codigo !== data[0][i].codigo) {
                    //codigo = data[0][i].codigo;
                    dtbody += ("<tr>");
                    if ($("#type").val() != 'A') {
                        dtbody += ("<td>" + data[0][i].codigo + "</td>");
                        dtbody += ("<td>" + data[0][i].descripcion + "</td>");
                    } else {
                        dtbody += ("<td>" + data[0][i].codigo + "</td>");
                        dtbody += ("<td>" + data[0][i].descripcion + "</td>");
                        dtbody += ("<td>" + data[0][i].familia_dsc + "</td>");
                    }
                    if (data[1].length > 0) {
                        $.each(data[1], function (j) {
                            codigo = data[0][i].codigo;
                            if ($("#type").val() != 'A') {
                                if (money == 'N') {
                                    if (data_total[i].periodo === data[1][j].periodo) {
                                        subtotal = parseFloat(data_total[i].basemn);
                                        total = parseFloat(data_total[i].basemn);
                                    } else {
                                        if (codigo === data_total[i].codigo) {
                                            codigo = data[0][i].codigo;
                                            subtotal = parseFloat(data[0][j].basemn);
                                            total = parseFloat(data[0][j].basemn);
                                        } else {
                                            subtotal = parseFloat(0);
                                            total = parseFloat(0);
                                        }
                                    }

                                } else {
                                    if (data[0][i].periodo === data[1][j].periodo) {
                                        subtotal = parseFloat(data[0][i].baseme);
                                        total = parseFloat(data[0][i].basemn);
                                    } else {
                                        subtotal = parseFloat(0);
                                        total += parseFloat(0);
                                    }
                                }
                            } else {
                                if (money == 'N') {
                                    if (data[0][i].periodo === data[1][j].periodo) {
                                        subtotal = parseFloat(data[0][i].basemn);
                                        total = parseFloat(data[0][i].basemn);
                                    } else {
                                        subtotal = parseFloat(0);
                                        total += parseFloat(0);
                                    }

                                } else {
                                    if (data[0][i].periodo === data[1][j].periodo) {
                                        subtotal = parseFloat(data[0][i].baseme);
                                        total = parseFloat(data[0][i].basemn);
                                    } else {
                                        subtotal = parseFloat(0);
                                        total += parseFloat(0);
                                    }
                                }
                            }

                            dtbody += ("<td>" + subtotal.toFixed(2) + "</td>");
                        });
                    }
                    dtbody += ("<td>" + total.toFixed(2) + "</td>");
                    dtbody += ("</tr>");

                    //total_t = total.toFixed(2);
                }
            });
        }
        dtfooter += ("<tr>");
        if ($("#type").val() != 'A') {
            dtfooter += ("<th colspan='2' bgcolor='#f2b957'>Totales:</th>");
        } else {
            dtfooter += ("<th colspan='3' bgcolor='#f2b957'>Totales:</th>");
        }

        if (data[0].length > 0) {
            $.each(data[1], function (i) {
                if ($("#type").val() !== 'A') {
                    if (codigo === data[0][i].codigo) {
                        if (money == 'N') {
                            subtotal = parseFloat(data[0][i].basemn);
                        } else {
                            subtotal = parseFloat(data[0][i].baseme);
                        }
                    }
                } else {
                    if (codigo === data[0][i].codigo) {
                        if (money == 'N') {
                            subtotal = parseFloat(data[0][i].basemn);
                        } else {
                            subtotal = parseFloat(data[0][i].baseme);
                        }
                    }
                }
                dtfooter += ("<th bgcolor='#f2b957'>" + subtotal.toFixed(2) + "</th>");
                total_t += subtotal;
            });
            dtfooter += ("<th bgcolor='#f2b957'>" + total_t.toFixed(2) + "</th>");
            dtfooter += ("</tr>");

        }
        dato[0] = dthead;
        dato[1] = dtbody;
        dato[2] = dtfooter;

    } else {
        dthead += ("<tr role='row'>");
        if ($("#type").val() != 'A') {
            dthead += ("<th colspan='2'>" + $("#type option:selected").html() + "</th>");
        } else {
            dthead += ("<th colspan='3'>Producto/Cuenta</th>");
        }
        dthead += ("<th rowspan='2'>Sub Total</th>");
        dthead += ("<th rowspan='2'>I.G.V</th>");
        dthead += ("<th rowspan='2'>Total</th>");
        dthead += ("</tr>");
        dthead += ("<tr role='row'>");
        if ($("#type").val() != 'A') {
            dthead += ("<th colspan='1'>Código</th>");
            dthead += ("<th rowspan='1'>Descripción</th>");
        } else {
            dthead += ("<th colspan='1'>Código</th>");
            dthead += ("<th rowspan='1'>Descripción</th>");
            dthead += ("<th rowspan='1'>Familia</th>");
        }
        dthead += ("</tr>");
        if (data[0].length > 0) {
            $.each(data[0], function (i) {
                if (codigo !== data[0][i].codigo) {
                    //if ($("#type").val() != 'A') {
                    codigo = data[0][i].codigo;
                    if (money == 'N') {
                        subtotal = parseFloat(data[0][i].basemn);
                        igv = parseFloat(data[0][i].igvmn);
                        total = parseFloat(data[0][i].totalmn);
                    } else {
                        subtotal = parseFloat(data[0][i].baseme);
                        igv = parseFloat(data[0][i].igvme);
                        total = parseFloat(data[0][i].totalme);
                    }
                    //}
                }

                dtbody += ("<tr>");
                if ($("#type").val() != 'A') {
                    dtbody += ("<td>" + data[0][i].codigo + "</td>");
                    dtbody += ("<td>" + data[0][i].descripcion + "</td>");
                } else {
                    dtbody += ("<td>" + data[0][i].codigo + "</td>");
                    dtbody += ("<td>" + data[0][i].descripcion + "</td>");
                    dtbody += ("<td>" + data[0][i].familia_dsc + "</td>");
                }
                dtbody += ("<td>" + subtotal.toFixed(2) + "</td>");
                dtbody += ("<td>" + igv.toFixed(2) + "</td>");
                dtbody += ("<td>" + total.toFixed(2) + "</td>");
                dtbody += ("</tr>");

                subtotal_t += parseFloat(subtotal);
                igv_t += parseFloat(igv);
                total_t += parseFloat(total);
            });
        }
        dtfooter += ("<tr>");
        if ($("#type").val() != 'A') {
            dtfooter += ("<th colspan='2' bgcolor='#f2b957'>Totales:</th>");
        } else {
            dtfooter += ("<th colspan='3' bgcolor='#f2b957'>Totales:</th>");
        }
        dtfooter += ("<th bgcolor='#f2b957'>" + subtotal_t.toFixed(2) + "</th>");
        dtfooter += ("<th bgcolor='#f2b957'>" + igv_t.toFixed(2) + "</th>");
        dtfooter += ("<th bgcolor='#f2b957'>" + total_t.toFixed(2) + "</th>");
        dtfooter += ("</tr>");
        dato[0] = dthead;
        dato[1] = dtbody;
        dato[2] = dtfooter;
    }


    return dato;
}
