function table() {
    var table = $('#listSalesSummary');
    var thead = $('#listSalesSummary thead');
    var tbody = $('#listSalesSummary tbody');
    var tfoot = $('#listSalesSummary tfoot');

    var type = $("#type").val();
    var dato = [];

    $.ajax({
        url: "/salessummary/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                tfoot.empty();

                var monthly = checkbox('monthly');
                var costs = checkbox('costs');

                if (type === 'C') {
                    dato = cliente(data, monthly, costs);
                } else if (type === 'A') {
                    dato = producto(data, monthly, costs);
                } else if (type === 'B') {
                    dato = clienteproducto(data, monthly, costs);
                }

                thead.html(dato[0]);
                tbody.html(dato[1]);
                tfoot.html(dato[2]);
                table.DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                        }
                    }
                );
            }
        }
    });
}

function cliente(data, monthly, costs) {
    var dthead = '';
    var dtbody = '';
    var dtfooter = '';
    var dato = [];


    dthead += ("<tr role='row'>");
    if (monthly === 1) {
        dthead += ("<th rowspan='2' colspan='1'>Periodo</th>");
    }
    dthead += ("<th rowspan='1' colspan='2'>Cliente</th>");
    if (costs === 1) {
        dthead += ("<th rowspan='2' colspan='1'>C.Costo</th>");
    }
    dthead += ("<th rowspan='1' colspan='4'>Venta</th>");
    dthead += ("<th rowspan='2' colspan='1'>Costo(2)</th>");
    dthead += ("<th rowspan='2' colspan='1'>Utilidad(3)</th>");
    dthead += ("<th rowspan='1' colspan='1'>% Rent.</th>");
    dthead += ("<th rowspan='1' colspan='1'>% Marg. Com</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Valor(1)</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Servicio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            if (monthly === 1) {
                dtbody += ("<td>" + data[i].periodo + "</td>");
            }
            dtbody += ("<td>" + data[i].codigo + "</td>");
            dtbody += ("<td>" + data[i].descripcion + "</td>");
            if (costs === 1) {
                dtbody += ("<td>" + data[i].ccosto + "</td>");
            }
            dtbody += ("<td>" + data[i].vventamn + "</td>");
            dtbody += ("<td>" + data[i].igvmn + "</td>");
            dtbody += ("<td>" + data[i].servmn + "</td>");
            dtbody += ("<td>" + data[i].totalmn + "</td>");
            dtbody += ("<td>" + data[i].costomn + "</td>");
            dtbody += ("<td>" + (data[i].vventamn - data[i].costomn) + "</td>");
            dtbody += ("<td>" + ((data[i].vventamn - data[i].costomn) / data[i].costomn) * 100 + "</td>");
            dtbody += ("<td>" + ((data[i].vventamn - data[i].costomn) / data[i].vventamn) * 100 + "</td>");
            dtbody += ("</tr>");
        });
    }

    dtfooter += ("<tr>");
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
    dtfooter += ("</tr>");

    dato[0] = dthead;
    dato[1] = dtbody;
    dato[2] = dtfooter;

    return dato;
}

function producto(data, monthly, costs) {
    var dthead = '';
    var dtbody = '';
    var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    if (monthly === 1) {
        dthead += ("<th rowspan='2' colspan='1'>Periodo</th>");
    }
    dthead += ("<th rowspan='1' colspan='3'>Producto</th>");
    if (costs === 1) {
        dthead += ("<th rowspan='2' colspan='1'>C.Costo</th>");
    }
    dthead += ("<th rowspan='2' colspan='1'>Cantidad</th>");
    dthead += ("<th rowspan='1' colspan='4'>Venta</th>");
    dthead += ("<th rowspan='2' colspan='1'>Costo(2)</th>");
    dthead += ("<th rowspan='2' colspan='1'>Utilidad(3)</th>");
    dthead += ("<th rowspan='1' colspan='1'>% Rent.</th>");
    dthead += ("<th rowspan='1' colspan='1'>% Marg. Com</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Familia</th>");
    dthead += ("<th rowspan='1' colspan='1'>Valor(1)</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Servicio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            if (monthly === 1) {
                dtbody += ("<td>" + data[i].periodo + "</td>");
            }
            dtbody += ("<td>" + data[i].producto_cod + "</td>");
            dtbody += ("<td>" + data[i].producto_dsc + "</td>");
            dtbody += ("<td>" + data[i].familia_dsc + "</td>");
            if (costs === 1) {
                alert('hola');
                dtbody += ("<td>" + data[i].ccosto + "</td>");
            }
            dtbody += ("<td>" + data[i].cantidad + "</td>");
            dtbody += ("<td>" + data[i].vventamn + "</td>");
            dtbody += ("<td>" + data[i].igvmn + "</td>");
            dtbody += ("<td>" + data[i].servmn + "</td>");
            dtbody += ("<td>" + data[i].totalmn + "</td>");
            dtbody += ("<td>" + data[i].costomn + "</td>");
            dtbody += ("<td>" + (data[i].vventamn - data[i].costomn) + "</td>");
            dtbody += ("<td>" + ((data[i].vventamn - data[i].costomn) / data[i].costomn) * 100 + "</td>");
            dtbody += ("<td>" + ((data[i].vventamn - data[i].costomn) / data[i].vventamn) * 100 + "</td>");
            dtbody += ("</tr>");
        });
    }

    dtfooter += ("<tr>");
    dtfooter += ("<th bgcolor='#f2b957'>Totales:</th>");
    if (monthly === 1 && costs === 0 || monthly === 0 && costs === 1) {
        dtfooter += ("<th bgcolor='#f2b957' colspan='3'></th>");
    } else if (monthly === 1 && costs === 1) {
        dtfooter += ("<th bgcolor='#f2b957' colspan='4'></th>");
    } else {
        dtfooter += ("<th bgcolor='#f2b957' colspan='2'></th>");
    }
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("</tr>");

    dato[0] = dthead;
    dato[1] = dtbody;
    dato[2] = dtfooter;

    return dato;
}

function clienteproducto(data, monthly, costs) {
    var dthead = '';
    var dtbody = '';
    var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    if (monthly === 1) {
        dthead += ("<th rowspan='2' colspan='1'>Periodo</th>");
    }
    dthead += ("<th rowspan='1' colspan='4'>Cliente/Producto</th>");
    if (costs === 1) {
        dthead += ("<th rowspan='2' colspan='1'>C.Costo</th>");
    }
    dthead += ("<th rowspan='2' colspan='1'>Cantidad</th>");
    dthead += ("<th rowspan='1' colspan='4'>Venta</th>");
    dthead += ("<th rowspan='2' colspan='1'>Costo(2)</th>");
    dthead += ("<th rowspan='2' colspan='1'>Utilidad(3)</th>");
    dthead += ("<th rowspan='1' colspan='1'>% Rent.</th>");
    dthead += ("<th rowspan='1' colspan='1'>% Marg. Com</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Producto</th>");
    dthead += ("<th rowspan='1' colspan='1'>Familia</th>");
    dthead += ("<th rowspan='1' colspan='1'>Valor(1)</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Servicio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            if (monthly === 1) {
                dtbody += ("<td>" + data[i].periodo + "</td>");
            }
            dtbody += ("<td>" + data[i].codigo + "</td>");
            dtbody += ("<td>" + data[i].descripcion + "</td>");
            dtbody += ("<td>" + data[i].producto_dsc + "</td>");
            dtbody += ("<td>" + data[i].familia_dsc + "</td>");
            if (costs === 1) {
                dtbody += ("<td>" + data[i].ccosto + "</td>");
            }
            dtbody += ("<td>" + data[i].cantidad + "</td>");
            dtbody += ("<td>" + data[i].vventamn + "</td>");
            dtbody += ("<td>" + data[i].igvmn + "</td>");
            dtbody += ("<td>" + data[i].servmn + "</td>");
            dtbody += ("<td>" + data[i].totalmn + "</td>");
            dtbody += ("<td>" + data[i].costomn + "</td>");
            dtbody += ("<td>" + (data[i].vventamn - data[i].costomn) + "</td>");
            dtbody += ("<td>" + ((data[i].vventamn - data[i].costomn) / data[i].costomn) * 100 + "</td>");
            dtbody += ("<td>" + ((data[i].vventamn - data[i].costomn) / data[i].vventamn) * 100 + "</td>");
            dtbody += ("</tr>");
        });
    }

    dtfooter += ("<tr>");
    dtfooter += ("<th bgcolor='#f2b957'>Totales:</th>");
    if (monthly === 1 && costs === 0 || monthly === 0 && costs === 1) {
        dtfooter += ("<th bgcolor='#f2b957' colspan='4'></th>");
    } else if (monthly === 1 && costs === 1) {
        dtfooter += ("<th bgcolor='#f2b957' colspan='5'></th>");
    } else {
        dtfooter += ("<th bgcolor='#f2b957' colspan='3'></th>");
    }
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("<th bgcolor='#f2b957'></th>");
    dtfooter += ("</tr>");

    dato[0] = dthead;
    dato[1] = dtbody;
    dato[2] = dtfooter;

    return dato;
}
