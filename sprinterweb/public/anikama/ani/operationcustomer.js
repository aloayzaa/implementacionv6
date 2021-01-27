$("#mostrar").click(function () {
    var table = $('#listOPerationCustomer');
    var thead = $('#listOPerationCustomer thead');
    var tbody = $('#listOPerationCustomer tbody');
    //var tfoot = $('#listSalesDetails tfoot');
    var dato = [];

    $.ajax({
        url: "/operationcustomer/list",
        type: "get",
        data: $("#frm_reporte").serialize(),
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                //tfoot.empty();

                var detailed = checkbox("detailed");
                dato = defecto(data, detailed);

                thead.html(dato[0]);
                tbody.html(dato[1]);
                //tfoot.html(dato[2]);
                table.DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
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
                                generar_txt_operaciones_terceros();
                            }
                        },
                    ],
                });
            }
        }
    });
});

function defecto(data, detailed) {
    var dthead = '';
    var dtbody = '';
    //var dtfooter = '';
    var dato = [];

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='2' colspan='1'>Contador</th>");
    dthead += ("<th rowspan='1' colspan='2'>Declarante</th>");
    dthead += ("<th rowspan='2' colspan='1'>Periodo</th>");
    dthead += ("<th rowspan='1' colspan='2'>Tipo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    dthead += ("<th rowspan='2' colspan='1'>Importe</th>");
    dthead += ("<th rowspan='1' colspan='2'>Apellidos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Primer</th>");
    dthead += ("<th rowspan='1' colspan='1'>Segundo</th>");
    dthead += ("<th rowspan='2' colspan='1'>Razón Social</th>");
    if (detailed === 1) {
        dthead += ("<th rowspan='1' colspan='4'>Razón Social</th>");
    }
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>T.D.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
    dthead += ("<th rowspan='1' colspan='1'>Pers</th>");
    dthead += ("<th rowspan='1' colspan='1'>Doc</th>");
    dthead += ("<th rowspan='1' colspan='1'>Documento</th>");
    dthead += ("<th rowspan='1' colspan='1'>Paterno</th>");
    dthead += ("<th rowspan='1' colspan='1'>Materno</th>");
    dthead += ("<th rowspan='1' colspan='1'>Nombre</th>");
    dthead += ("<th rowspan='1' colspan='1'>Nombre</th>");
    if (detailed === 1) {
        dthead += ("<th rowspan='1' colspan='1'>Número</th>");
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Importe</th>");
        dthead += ("<th rowspan='1' colspan='1'>Importe $</th>");
    }
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data.data[i].contador + "</td>");
            dtbody += ("<td>" + data.data[i].d_tipodoc + "</td>");
            dtbody += ("<td>" + data.data[i].d_numdoc + "</td>");
            dtbody += ("<td>" + data.data[i].periodo + "</td>");
            dtbody += ("<td>" + data.data[i].tipo_per + "</td>");
            dtbody += ("<td>" + data.data[i].tipo_doc + "</td>");
            dtbody += ("<td>" + data.data[i].num_doc + "</td>");
            dtbody += ("<td>" + data.data[i].nimporte + "</td>");
            dtbody += ("<td>" + data.data[i].ap_pater + "</td>");
            dtbody += ("<td>" + data.data[i].ap_mater + "</td>");
            dtbody += ("<td>" + data.data[i].nombre1 + "</td>");
            dtbody += ("<td>" + data.data[i].nombre2 + "</td>");
            dtbody += ("<td>" + data.data[i].razon_soc + "</td>");
            if (detailed === 1) {
                dtbody += ("<td>" + data.data[i].documento + "</td>");
                dtbody += ("<td>" + data.data[i].fecha + "</td>");
                dtbody += ("<td>" + data.data[i].total + "</td>");
                dtbody += ("<td>" + data.data[i].totalme + "</td>");
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

function generar_txt_operaciones_terceros() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/reporte_operaciones_por_terceros",
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
