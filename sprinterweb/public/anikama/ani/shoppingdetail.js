$(document).ready(function () {
    var id_table = $('table').prop('id');
    var table = $('#'+id_table);
    init_datatable(table);
});

$("#mostrar").click(function () {
    var table = $('#listShoppingDetail');
    var tbody = $('.'+ id_table+' tbody');
    var tfoot = $('.'+ id_table+' tfoot');

    var ruta = $('#ruta').val();
    var lctipo = '';
    var lcDesde = $("#initialdate").val();
    var lcHasta = $("#finishdate").val();
    if ($("#lctipo").prop('checked')){
        lctipo = 'M';
    }else{
        lctipo = 'D';
    }

    var dato = [];

    $.ajax({
        url: ruta,
        type: "GET",
        data: {lcDesde: lcDesde, lcHasta: lcHasta, lctipo: lctipo},
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                tbody.empty();
                tfoot.empty();
                dato = defecto(data);
                tbody.html(dato[0]);
                tfoot.html(dato[1]);
                init_datatable(table);
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});

function defecto(data) {
    var dtbody = '';
    var dtfooter = '';
    var dato = [];


    var compramn = 0;
    var igvmn = 0;
    var importemn = 0;
    var comprame = 0;
    var igvme = 0;
    var importeme = 0;

    if (data.length > 0) {
        $.each(data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data[i].sucursal_cod + "</td>");
            dtbody += ("<td>" + data[i].nroregistro + "</td>");
            dtbody += ("<td>" + data[i].documento + "</td>");
            dtbody += ("<td>" + data[i].fechadoc + "</td>");
            dtbody += ("<td>" + data[i].moneda + "</td>");
            dtbody += ("<td>" + data[i].cuenta_cod + "</td>");
            dtbody += ("<td>" + data[i].cuenta_dsc + "</td>");
            dtbody += ("<td>" + data[i].familia_cod + "</td>");
            dtbody += ("<td>" + data[i].familia_dsc + "</td>");
            dtbody += ("<td>" + parseFloat(data[i].basemn).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data[i].igvmn).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data[i].importemn).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data[i].baseme).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data[i].igvme).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data[i].importeme).toFixed(2) + "</td>");
            dtbody += ("<td>" + data[i].tcambio + "</td>");
            dtbody += ("<td>" + data[i].oc_numero + "</td>");
            dtbody += ("<td>" + data[i].oc_fecha + "</td>");
            dtbody += ("<td>" + data[i].proveedor_cod + "</td>");
            dtbody += ("<td>" + data[i].proveedor_dsc + "</td>");
            dtbody += ("<td>" + data[i].ccosto_cod + "</td>");
            dtbody += ("<td>" + data[i].ccosto_dsc + "</td>");
            dtbody += ("<td>" + data[i].act_cod + "</td>");
            dtbody += ("<td>" + data[i].act_dsc + "</td>");
            dtbody += ("<td>" + data[i].pry_cod + "</td>");
            dtbody += ("<td>" + data[i].pry_dsc + "</td>");
            dtbody += ("<td>" + data[i].cta_cargo + "</td>");
            dtbody += ("<td>" + data[i].cta_abono + "</td>");
            dtbody += ("<td>" + data[i].usuario + "</td>");
            dtbody += ("</tr>");

            compramn += parseFloat(data[i].basemn);
            igvmn += parseFloat(data[i].igvmn);
            importemn += parseFloat(data[i].importemn);

            comprame += parseFloat(data[i].baseme);
            igvme += parseFloat(data[i].igvme);
            importeme += parseFloat(data[i].importeme);
        });
    }
    dtfooter += ("<tr>");
    dtfooter += ("<th colspan='9' bgcolor='#f2b957'>Totales:</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+compramn.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+igvmn.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+importemn.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+comprame.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+igvme.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+importeme.toFixed(2)+"</th>");
    dtfooter += ("<th colspan='14' bgcolor='#f2b957'></th>");
    dtfooter += ("</tr>");

    dato[0] = dtbody;
    dato[1] = dtfooter;

    return dato;
}
