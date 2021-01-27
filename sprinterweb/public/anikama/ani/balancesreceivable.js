$(document).ready(function () {
    var id_table = $('table').prop('id');
    var table = $('#'+id_table);
    init_datatable(table);

});

$("#mostrar").click(function () {
    var table = $('#listBalanceReceivable');
    var thead = $('.'+id_table+' thead');
    var tbody = $('.'+ id_table+' tbody');
    var tfoot = $('.'+ id_table+' tfoot');

    var ruta = $('#ruta').val();
    var form = $('#frm_reporte').serialize();

    var dato = [];

    $.ajax({
        url: ruta,
        type: "GET",
        data: form,
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                table.DataTable().destroy();
                thead.empty();
                tbody.empty();
                tfoot.empty();
                var check = ($("#detalle").prop('checked'));
                var origen = $("#origen").val();
                dato = defecto(data, check, origen);

                thead.html(dato[0]);
                tbody.html(dato[1]);
                tfoot.html(dato[2]);
                init_datatable(table);
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});

function defecto(data, check, origen) {
    var dthead = '';
    var dtbody = '';
    var dtfooter = '';
    var dato = [];

    var totalmn = 0;
    var ctamn = 0;
    var saldomn = 0;
    var totalme = 0;
    var ctame = 0;
    var saldome = 0;

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='2'>Código</th>");
    dthead += ("<th rowspan='2'>Nombre o Razón Social</th>");
    if (check) {
        dthead += ("<th rowspan='2'>Sucursal</th>");
        dthead += ("<th rowspan='2'>Punto Venta</th>");
        dthead += ("<th colspan='6'>Documento</th>");
    }
    dthead += ("<th colspan='3'>Nuevos Soles</th>");
    dthead += ("<th colspan='3'>Dolares Americanos</th>");
    if (check) {
        dthead += ("<th rowspan='2'>Condición</th>");
        dthead += ("<th rowspan='2'>Vendedor</th>");
        dthead += ("<th colspan='1'>Ciudad</th>");
        dthead += ("<th rowspan='2'>Glosa</th>");
        dthead += ("<th colspan='2'>Dirección</th>");
    }
    dthead += ("</tr>");

    if (check) {
        dthead += ("<th colspan='1'>TD</th>");
        dthead += ("<th colspan='1'>Número</th>");
        dthead += ("<th colspan='1'>Fecha</th>");
        dthead += ("<th colspan='1'>Vencimiento</th>");
        dthead += ("<th colspan='1'>Días</th>");
        dthead += ("<th colspan='1'>Mon</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>A Cta.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Saldo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>A Cte.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Saldo</th>");
    if (check){
        dthead += ("<th rowspan='1' colspan='1'>Cobranza</th>");
        dthead += ("<th rowspan='1' colspan='1'>Dirección</th>");
        dthead += ("<th rowspan='1' colspan='1'>Provincia</th>");
    }
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            dtbody += ("<tr>");
            dtbody += ("<td>" + data.data[i].codigo + "</td>");
            dtbody += ("<td>" + data.data[i].descripcion + "</td>");
            if (check) {
                dtbody += ("<td>" + data.data[i].suc_dsc + "</td>");
                dtbody += ("<td>" + data.data[i].ptoventa + "</td>");
                dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
                dtbody += ("<td>" + data.data[i].documento + "</td>");
                dtbody += ("<td>" + data.data[i].fechadoc + "</td>");
                dtbody += ("<td>" + data.data[i].vencimiento + "</td>");
                dtbody += ("<td>" + data.data[i].vencimiento + "</td>");
                dtbody += ("<td>" + data.data[i].moneda + "</td>");
            }
            dtbody += ("<td>" + parseFloat(data.data[i].totalmn).toFixed(2) + "</td>");
            if (origen == 'PS'){
                dtbody += ("<td>" + (parseFloat(data.data[i].totalmn) - parseFloat(data.data[i].saldomn)).toFixed(2) + "</td>");
                dtbody += ("<td>" + parseFloat(data.data[i].saldomn).toFixed(2) + "</td>");
            }else{
                dtbody += ("<td>" + (parseFloat(data.data[i].totalmn) - parseFloat(data.data[i].saldo1mn)).toFixed(2) + "</td>");
                dtbody += ("<td>" + parseFloat(data.data[i].saldo1mn).toFixed(2) + "</td>");
            }
            dtbody += ("<td>" + parseFloat(data.data[i].totalme).toFixed(2) + "</td>");
            if (origen == 'PS'){
                dtbody += ("<td>" + (parseFloat(data.data[i].totalme) - parseFloat(data.data[i].saldome)).toFixed(2) + "</td>");
                dtbody += ("<td>" + parseFloat(data.data[i].saldome).toFixed(2) + "</td>");
            }else{
                dtbody += ("<td>" + (parseFloat(data.data[i].totalme) - parseFloat(data.data[i].saldo1me)).toFixed(2) + "</td>");
                dtbody += ("<td>" + parseFloat(data.data[i].saldo1me).toFixed(2) + "</td>");
            }
            if (check) {
                dtbody += ("<td>" + data.data[i].condicion + "</td>");
                dtbody += ("<td>" + data.data[i].vendedor + "</td>");
                dtbody += ("<td>" + data.data[i].glosa + "</td>");
                dtbody += ("<td>" + data.data[i].glosa + "</td>");
                dtbody += ("<td>" + data.data[i].direccion + "</td>");
                dtbody += ("<td>" + data.data[i].ubigeo + "</td>");
            }
            dtbody += ("</tr>");

            totalmn += parseFloat(data.data[i].totalmn);
            if (origen == 'PS'){
                ctamn += parseFloat(data.data[i].totalmn) - parseFloat(data.data[i].saldomn);
                saldomn += parseFloat(data.data[i].saldomn);
            }else{
                ctamn += parseFloat(data.data[i].totalmn) - parseFloat(data.data[i].saldo1mn);
                saldomn += parseFloat(data.data[i].saldo1mn);
            }
            totalme += parseFloat(data.data[i].totalme);
            if (origen == 'PS'){
                ctame += parseFloat(data.data[i].totalme) - parseFloat(data.data[i].saldome);
                saldome += parseFloat(data.data[i].saldome);
            }else{
                ctame += parseFloat(data.data[i].totalme) - parseFloat(data.data[i].saldo1me);
                saldome += parseFloat(data.data[i].saldo1me);
            }

        });
    }
    dtfooter += ("<tr>");

    if (check){
        dtfooter += ("<th colspan='10' bgcolor='#f2b957'>Totales:</th>");
    }else {
        dtfooter += ("<th colspan='2' bgcolor='#f2b957'>Totales:</th>");
    }
    dtfooter += ("<th colspan='1' bgcolor='#f2b957'>"+totalmn.toFixed(2)+"</th>");
    dtfooter += ("<th colspan='1' bgcolor='#f2b957'>"+ctamn.toFixed(2)+"</th>");
    dtfooter += ("<th colspan='1' bgcolor='#f2b957'>"+saldomn.toFixed(2)+"</th>");
    dtfooter += ("<th colspan='1' bgcolor='#f2b957'>"+totalme.toFixed(2)+"</th>");
    dtfooter += ("<th colspan='1' bgcolor='#f2b957'>"+ctame.toFixed(2)+"</th>");
    dtfooter += ("<th colspan='1' bgcolor='#f2b957'>"+saldome.toFixed(2)+"</th>");
    if (check){
        dtfooter += ("<th colspan='6' bgcolor='#f2b957'></th>");

    }

    dtfooter += ("</tr>");

    dato[0] = dthead;
    dato[1] = dtbody;
    dato[2] = dtfooter;

    return dato;
}
