$(document).ready(function () {
    typedata();
});

function typedata() {
    $("#account").find('option').not(':first').remove();
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/filtro",
        dataType: "JSON",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (data) {
                for (let i = 0; i < data.length; i++) {
                    $("#account").append('<option value="' + data[i].id + '">' + data[i].codigo + ' | ' + data[i].descripcion + '</option>')
                }
            }
        }
    });
}

function table() {
    var table = $('#listSalesDetails');
    var thead = $('#listSalesDetails thead');
    var tbody = $('#listSalesDetails tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/salesdetails/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var moneda = $("#currency").val();
                var rdtype = $("#rdtype:checked").val();

                dato = defecto(data, moneda, rdtype);

                thead.html(dato[0]);
                tbody.html(dato[1]);
                //tfoot.html(dato[2]);
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

function defecto(data, moneda, rdtype) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='2'>Sucursal</th>");
    dthead += ("<th rowspan='1' colspan='2'>Pto. Venta</th>");
    dthead += ("<th rowspan='1' colspan='2'>T. Venta</th>");
    dthead += ("<th rowspan='1' colspan='2'>T. Afectación I.G.V</th>");
    dthead += ("<th rowspan='2' colspan='1'>Certamen</th>");
    dthead += ("<th rowspan='1' colspan='4'>Documento</th>");
    dthead += ("<th rowspan='1' colspan='1'>Gui Rem.</th>");
    if (rdtype === '2') {
        dthead += ("<th rowspan='1' colspan='4'>Producto</th>");
        dthead += ("<th rowspan='1' colspan='2'>Familia</th>");
        dthead += ("<th rowspan='2' colspan='1'>Cantidad</th>");
    }
    dthead += ("<th rowspan='1' colspan='2'>Cliente</th>");
    if (rdtype === '2') {
        dthead += ("<th rowspan='1' colspan='2'>Otros datos</th>");
    }
    dthead += ("<th rowspan='1' colspan='4'>Moneda Nacional</th>");
    dthead += ("<th rowspan='2' colspan='1'>Costo</th>");
    dthead += ("<th rowspan='2' colspan='1'>Utilidad</th>");
    dthead += ("<th rowspan='2' colspan='1'>Margen Com. %</th>");
    dthead += ("<th rowspan='2' colspan='1'>Rentab. %</th>");
    dthead += ("<th rowspan='1' colspan='4'>Moneda Nacional</th>");
    dthead += ("<th rowspan='2' colspan='1'>Costo</th>");
    dthead += ("<th rowspan='2' colspan='1'>Utilidad</th>");
    dthead += ("<th rowspan='2' colspan='1'>Margen Com. %</th>");
    dthead += ("<th rowspan='2' colspan='1'>Rentab. %</th>");
    if (rdtype === '2') {
        dthead += ("<th rowspan='1' colspan='2'>Cuenta</th>");
    }
    dthead += ("<th rowspan='1' colspan='3'>Vendedor</th>");
    dthead += ("<th rowspan='1' colspan='2'>Cond. Pago</th>");
    dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='1' colspan='1'>Mon.</th>");
    dthead += ("<th rowspan='1' colspan='1'>T. Cambio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Sal. Almacén</th>");
    if (rdtype === '2') {
        dthead += ("<th rowspan='1' colspan='1'>Código</th>");
        dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
        dthead += ("<th rowspan='1' colspan='1'>U.M</th>");
        dthead += ("<th rowspan='1' colspan='1'>Marca</th>");
        dthead += ("<th rowspan='1' colspan='1'>Código</th>");
        dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Nombre/R. Social</th>");
    if (rdtype === '2') {
        dthead += ("<th rowspan='1' colspan='1'>Dirección</th>");
        dthead += ("<th rowspan='1' colspan='1'>Ubigeo</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>V. Venta</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Servicio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>V. Venta</th>");
    dthead += ("<th rowspan='1' colspan='1'>I.G.V</th>");
    dthead += ("<th rowspan='1' colspan='1'>Servicio</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    if (rdtype === '2') {
        dthead += ("<th rowspan='1' colspan='1'>Código</th>");
        dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Nombre</th>");
    dthead += ("<th rowspan='1' colspan='1'>Código</th>");
    dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data[i].sucursal_cod + "</td>");
            dtbody += ("<td>" + data[i].sucursal_dsc + "</td>");
            dtbody += ("<td>" + data[i].ptovta_cod + "</td>");
            dtbody += ("<td>" + data[i].ptovta_dsc + "</td>");
            dtbody += ("<td>" + data[i].tipovta_cod + "</td>");
            dtbody += ("<td>" + data[i].tipovta_dsc + "</td>");
            dtbody += ("<td>" + data[i].tipoigv_cod + "</td>");
            dtbody += ("<td>" + data[i].tipoigv_dsc + "</td>");
            dtbody += ("<td>" + data[i].certamen_dsc + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].fechadoc + "</td>");
            dtbody += ("<td>" + data[i].moneda + "</td>");
            dtbody += ("<td>" + data[i].tcambio + "</td>");
            dtbody += ("<td>" + data[i].guiarem + "</td>");
            if (rdtype === '2') {
                dtbody += ("<td>" + data[i].producto_cod + "</td>");
                dtbody += ("<td>" + data[i].producto_dsc + "</td>");
                dtbody += ("<td>" + data[i].ptovta_cod + "</td>");
                dtbody += ("<td>" + data[i].marca_dsc + "</td>");
                dtbody += ("<td>" + data[i].familia_cod + "</td>");
                dtbody += ("<td>" + data[i].familia_dsc + "</td>");
                dtbody += ("<td>" + data[i].tipoigv_cod + "</td>");
            }
            dtbody += ("<td>" + data[i].cliente_cod + "</td>");
            dtbody += ("<td>" + data[i].cliente_dsc + "</td>");
            if (rdtype === '2') {
                dtbody += ("<td>" + data[i].cliente_dir + "</td>");
                dtbody += ("<td>" + data[i].cliente_ubi + "</td>");
            }
            if (currency === 'O') {
                if (data[i].moneda === 'S/') {
                    if (data[i].vventamn !== '0') {
                        dtbody += ("<td>" + data[i].vventamn + "</td>");
                    }
                }
                if (data[i].moneda === 'S/') {
                    if (data[i].igvmn !== '0') {
                        dtbody += ("<td>" + data[i].igvmn + "</td>");
                    }
                }
                if (data[i].moneda === 'S/') {
                    if (data[i].servmn !== '0') {
                        dtbody += ("<td>" + data[i].servmn + "</td>");
                    }
                }
                if (data[i].moneda === 'S/') {
                    if (data[i].totalmn !== '0') {
                        dtbody += ("<td>" + data[i].totalmn + "</td>");
                    }
                }
                if (data[i].moneda === 'S/') {
                    if (data[i].costomn !== '0') {
                        dtbody += ("<td>" + data[i].costomn + "</td>");
                    }
                }
                if (data[i].moneda === 'S/') {
                    if (data[i].totalmn - data[i].costomn !== '0') {
                        dtbody += ("<td>" + data[i].totalmn - data[i].costomn + "</td>");
                    }
                }

            } else if (currency === 'A') {

            }
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");

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
