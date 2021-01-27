$("#bank").change(function () {
    if (this.value !== '0') {
        $('#currentaccount option[value="0"]').remove();
        ctacte_reporte('currentaccount');
    } else if (this.value === '0') {
        $("#currentaccount").empty();
        $("#currentaccount").append('<option value="0">Todas las Cuentas</option>')
    }
});

function ctacte_reporte(id) {
    $("#" + id).empty();
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/ctacte",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data) {
                for (let i = 0; i < data.length; i++) {
                    $("#" + id).append('<option value="' + data[i].id + '">' + data[i].codigo + ' | ' + data[i].descripcion + '</option>')
                }
            }
        }
    });
}

function table() {
    var variable = $("#var").val();
    var table = $('#listCashBankBook');
    var thead = $('#listCashBankBook thead');
    var tbody = $('#listCashBankBook tbody');
    tbody.empty();
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/" + variable + "/list",
        type: "get",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var detallado = checkbox('detailed');
                dato = defecto(detallado);
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
                                title: "Sprinter Web - Libro Caja y Banco",
                                footer: true,
                                customize: function (doc) {
                                    doc.defaultStyle.fontSize = 6;
                                    doc.styles.tableHeader.fontSize = 6;
                                    doc.styles.tableFooter.fontSize = 6;
                                }
                            },
                        ],
                    }
                );
            }
        }
    });
}


function defecto(data) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='2' colspan='1'>Sucursal</th>");
    dthead += ("<th rowspan='2' colspan='1'>Cuenta Corriente</th>");
    dthead += ("<th rowspan='2' colspan='1'>Fecha</th>");
    dthead += ("<th rowspan='2' colspan='1'>Voucher</th>");
    if (data === 1) {
        dthead += ("<th rowspan='1' colspan='1'>Tipo</th>");
        dthead += ("<th rowspan='2' colspan='1'>Cuenta</th>");
        dthead += ("<th rowspan='2' colspan='1'>Centro Costo</th>");
        dthead += ("<th rowspan='1' colspan='2'>Comprobante Pago</th>");
    }
    dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='2' colspan='1'>Cheque</th>");
    dthead += ("<th rowspan='2' colspan='1'>Ingresos</th>");
    dthead += ("<th rowspan='2' colspan='1'>Egresos</th>");
    if (data === 1) {
        dthead += ("<th rowspan='2' colspan='1'>Saldo</th>");
    }
    dthead += ("<th rowspan='2' colspan='1'>T.C</th>");
    if (data === 1) {
        dthead += ("<th rowspan='2' colspan='1'>Razón Social</th>");
    } else {
        dthead += ("<th rowspan='2' colspan='1'>Cheque Girado</th>");
    }
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    if (data === 1) {
        dthead += ("<th rowspan='1' colspan='1'>Operación</th>");
        dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
        dthead += ("<th rowspan='1' colspan='1'>Nro. Interno</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Transacción</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data[i].suc_dsc + "</td>");
            dtbody += ("<td>" + data[i].ctacte + "</td>");
            dtbody += ("<td>" + data[i].fechaproceso + "</td>");
            dtbody += ("<td>" + data[i].voucher + "</td>");
            if (data === 1) {
                dtbody += ("<td>" + data[i].operacion + "</td>");
                dtbody += ("<td>" + data[i].cuenta + "</td>");
                dtbody += ("<td>" + data[i].ccosto + "</td>");
                dtbody += ("<td>" + data[i].docrefer + "</td>");
                dtbody += ("<td>" + data[i].documero + "</td>");
            }
            dtbody += ("<td>" + data[i].glosa + "</td>");
            dtbody += ("<td>" + data[i].nrotran + "</td>");
            dtbody += ("<td>" + data[i].nrocheque + "</td>");
            if (data[i].moneda === 'Soles') {
                dtbody += ("<td>" + data[i].ingresomn + "</td>");
                dtbody += ("<td>" + data[i].egresomn + "</td>");
                dtbody += ("<td>" + data[i].saldomn + "</td>");
            } else {
                dtbody += ("<td>" + data[i].ingreso + "</td>");
                dtbody += ("<td>" + data[i].egreso + "</td>");
                dtbody += ("<td>" + data[i].saldo + "</td>");
            }
            if (data === 1) {
                dtbody += ("<td>" + data[i].nombre + "</td>");
            }
            dtbody += ("<td>" + data[i].tcambio + "</td>");
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
