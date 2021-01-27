$("#btnmostrar").click(function () {
    var table = $('#listDailyBook');
    var thead = $('#listDailyBook thead');
    var tbody = $('#listDailyBook tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/dailybook/list",
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
                                    generar_ple_normal_diario();
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
    if (type !== 'S') {
        dthead += ("<th rowspan='1' colspan='4'>Documento</th>");
        dthead += ("<th rowspan='1' colspan='2'>CUO</th>");
        dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
        dthead += ("<th rowspan='2' colspan='1'>Cuenta</th>");
        dthead += ("<th rowspan='2' colspan='1'>Denominación</th>");
        dthead += ("<th rowspan='1' colspan='4'>Referencia</th>");
        dthead += ("<th rowspan='2' colspan='1'>Debe</th>");
        dthead += ("<th rowspan='2' colspan='1'>Haber</th>");
        dthead += ("<th rowspan='1' colspan='2'>Centro Costo</th>");
        dthead += ("<th rowspan='1' colspan='2'>Operación</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Glosa</th>");
        dthead += ("<th rowspan='1' colspan='1'>C10</th>");
        dthead += ("<th rowspan='1' colspan='1'>C12</th>");
        dthead += ("<th rowspan='1' colspan='1'>C16</th>");
        dthead += ("<th rowspan='1' colspan='1'>C20</th>");
        dthead += ("<th rowspan='1' colspan='1'>C21</th>");
        dthead += ("<th rowspan='1' colspan='1'>C33</th>");
        dthead += ("<th rowspan='1' colspan='1'>C38</th>");
        dthead += ("<th rowspan='1' colspan='1'>C39</th>");
        dthead += ("<th rowspan='1' colspan='1'>C4011</th>");
        dthead += ("<th rowspan='1' colspan='1'>C4017</th>");
        dthead += ("<th rowspan='1' colspan='1'>C402</th>");
        dthead += ("<th rowspan='1' colspan='1'>C42</th>");
        dthead += ("<th rowspan='1' colspan='1'>C46</th>");
        dthead += ("<th rowspan='1' colspan='1'>C50</th>");
        dthead += ("<th rowspan='1' colspan='1'>C58</th>");
        dthead += ("<th rowspan='1' colspan='1'>C59</th>");
        dthead += ("<th rowspan='1' colspan='1'>C60</th>");
        dthead += ("<th rowspan='1' colspan='1'>C61</th>");
        dthead += ("<th rowspan='1' colspan='1'>C62</th>");
        dthead += ("<th rowspan='1' colspan='1'>C63</th>");
        dthead += ("<th rowspan='1' colspan='1'>C65</th>");
        dthead += ("<th rowspan='1' colspan='1'>C66</th>");
        dthead += ("<th rowspan='1' colspan='1'>C67</th>");
        dthead += ("<th rowspan='1' colspan='1'>C68</th>");
        dthead += ("<th rowspan='1' colspan='1'>C69</th>");
        dthead += ("<th rowspan='1' colspan='1'>C96</th>");
        dthead += ("<th rowspan='1' colspan='1'>C97</th>");
        dthead += ("<th rowspan='1' colspan='1'>C70</th>");
        dthead += ("<th rowspan='1' colspan='1'>C75</th>");
        dthead += ("<th rowspan='1' colspan='1'>C77</th>");
        dthead += ("<th rowspan='1' colspan='1'>C79</th>");
    }
    dthead += ("</tr>");

    if (type !== 'S') {
        dthead += ("<tr role='row'>");
        dthead += ("<th rowspan='1' colspan='1'>Subdiario</th>");
        dthead += ("<th rowspan='1' colspan='1'>Periodo</th>");
        dthead += ("<th rowspan='1' colspan='1'>Operación</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>ID</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Libro</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
        dthead += ("<th rowspan='1' colspan='1'>Código</th>");
        dthead += ("<th rowspan='1' colspan='1'>Descripción</th>");
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("</tr>");
    }

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            if (type !== 'S') {
                dtbody += ("<td>" + data.data[i].subdiario + "</td>");
                dtbody += ("<td>" + data.data[i].periodo + "</td>");
                dtbody += ("<td>" + data.data[i].operacion + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].cuo + "</td>");
                dtbody += ("<td>" + data.data[i].cuo_item + "</td>");
                dtbody += ("<td>" + data.data[i].glosa + "</td>");
                dtbody += ("<td>" + data.data[i].cuenta + "</td>");
                dtbody += ("<td>" + data.data[i].descripcion + "</td>");
                dtbody += ("<td>" + data.data[i].ref_fecha + "</td>");
                dtbody += ("<td>" + data.data[i].ref_libro + "</td>");
                dtbody += ("<td>" + data.data[i].ref_numero + "</td>");
                var total = data.data[i].ref_tipodoc + data.data[i].ref_seriedoc + data.data[i].ref_numerodoc;
                dtbody += ("<td>" + total + "</td>");
                if (moneda === 'N') {
                    dtbody += ("<td>" + data.data[i].cargomn + "</td>");
                    dtbody += ("<td>" + data.data[i].abonomn + "</td>");
                } else {
                    dtbody += ("<td>" + data.data[i].cargome + "</td>");
                    dtbody += ("<td>" + data.data[i].abonome + "</td>");
                }

                dtbody += ("<td>" + data.data[i].ccosto + "</td>");
                dtbody += ("<td>" + data.data[i].ccosto_dsc + "</td>");
                dtbody += ("<td>" + data.data[i].op + "</td>");
                dtbody += ("<td>" + data.data[i].op_fecha + "</td>");
            } else {
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].glosa + "</td>");
                dtbody += ("<td>" + data.data[i].c10 + "</td>");
                dtbody += ("<td>" + data.data[i].c12 + "</td>");
                dtbody += ("<td>" + data.data[i].c16 + "</td>");
                dtbody += ("<td>" + data.data[i].c20 + "</td>");
                dtbody += ("<td>" + data.data[i].c21 + "</td>");
                dtbody += ("<td>" + data.data[i].c33 + "</td>");
                dtbody += ("<td>" + data.data[i].c38 + "</td>");
                dtbody += ("<td>" + data.data[i].c39 + "</td>");
                dtbody += ("<td>" + data.data[i].c4011 + "</td>");
                dtbody += ("<td>" + data.data[i].c4017 + "</td>");
                dtbody += ("<td>" + data.data[i].c402 + "</td>");
                dtbody += ("<td>" + data.data[i].c42 + "</td>");
                dtbody += ("<td>" + data.data[i].c46 + "</td>");
                dtbody += ("<td>" + data.data[i].c50 + "</td>");
                dtbody += ("<td>" + data.data[i].c58 + "</td>");
                dtbody += ("<td>" + data.data[i].c59 + "</td>");
                dtbody += ("<td>" + data.data[i].c60 + "</td>");
                dtbody += ("<td>" + data.data[i].c61 + "</td>");
                dtbody += ("<td>" + data.data[i].c62 + "</td>");
                dtbody += ("<td>" + data.data[i].c63 + "</td>");
                dtbody += ("<td>" + data.data[i].c65 + "</td>");
                dtbody += ("<td>" + data.data[i].c66 + "</td>");
                dtbody += ("<td>" + data.data[i].c67 + "</td>");
                dtbody += ("<td>" + data.data[i].c68 + "</td>");
                dtbody += ("<td>" + data.data[i].c69 + "</td>");
                dtbody += ("<td>" + data.data[i].c96 + "</td>");
                dtbody += ("<td>" + data.data[i].c97 + "</td>");
                dtbody += ("<td>" + data.data[i].c70 + "</td>");
                dtbody += ("<td>" + data.data[i].c75 + "</td>");
                dtbody += ("<td>" + data.data[i].c77 + "</td>");
                dtbody += ("<td>" + data.data[i].c79 + "</td>");
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
    //dato[2] = dtfooter;

    return dato;
}

function generar_ple_normal_diario() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_librodiario_plenormal",
        dataType: "json",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            ple_normal_diario_cuentas(variable);
            if (data.estado === 'ok') {
                success("success", "Exportacion exitosa", "Exito!");
                ventanaSecundaria("/consumer/" + data.archivo);
            } else {
                error("error", "Se presentaron errores al generar el archivo", "Error!");
            }
        }
    });
}

function ple_normal_diario_cuentas(variable) {
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_librodiario_plenormal_cuentas",
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
