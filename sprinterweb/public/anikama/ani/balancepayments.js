/*
$("#mostrar").click(function () {
    var date = $("#date").val();
    tableListBalancePayments.init(this.value + '?date=' + date);
});
*/
// $("#pending").click(function () {
//     var balances = $("#balances").val();
//     valida_check(balances)
// });
// $("#balances").change(function () {
//     var balances = $(this).val();
//     valida_check(balances);
// });
// function valida_check(valor){
//     if (valor === 'PC'){
//         $('#pending').prop('checked', false)
//     }
// }
$(document).ready(function () {
    var id_table = $('table').prop('id');
    var table = $('#'+id_table);
    init_datatable(table);

});

$("#mostrar").click(function () {
    var table = $('#listBalancePayments');
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
                var moneda = $("#moneda").val();
                dato = defecto(data, check, moneda);

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

function defecto(data, check, moneda) {
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
        dthead += ("<th colspan='2'>Sucursal</th>");
        dthead += ("<th colspan='5'>Documento</th>");
    }
    dthead += ("<th colspan='3'>Nuevos Soles</th>");
    dthead += ("<th colspan='3'>Dolares Americanos</th>");
    if (check) {
        dthead += ("<th rowspan='2'>Glosa</th>");
    }
    dthead += ("</tr>");

    if (check) {
        dthead += ("<th colspan='1'>Cod.</th>");
        dthead += ("<th colspan='1'>Descripción</th>");
        dthead += ("<th colspan='1'>TD</th>");
        dthead += ("<th colspan='1'>Número</th>");
        dthead += ("<th colspan='1'>Fecha</th>");
        dthead += ("<th colspan='1'>Vencimiento</th>");
        dthead += ("<th colspan='1'>Mon</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>A Cta.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Saldo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Total</th>");
    dthead += ("<th rowspan='1' colspan='1'>A Cte.</th>");
    dthead += ("<th rowspan='1' colspan='1'>Saldo</th>");
    dthead += ("</tr>");
    console.log(data);
    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            if ($('#sucursal').val() != '' && $("#categoria").val() != ""){
                $.ajax({
                    url: '/balancepayments/consultar_detalle',
                    type: "GET",
                    data: {id: $("#categoria").val()},
                    success: function (datas) {
                        for (var j=0; j<datas.length;j++){
                            if ($('#sucursal').val() === data.data[i].sucursal_id && datas[j].codigo === data.data[i].tipodoc){
                                var result = procesar(data.data[i], moneda, check);
                                dtbody += result[0];
                                totalmn += result[1];
                                ctamn += result[2];
                                saldomn += result[3];
                                totalme += result[4];
                                ctame += result[5];
                                saldome += result[6];
                            }
                        }
                    }
                });
            }else{
                if($('#sucursal').val() != ''){
                    if ($('#sucursal').val() === data.data[i].sucursal_id){
                        var result = procesar(data.data[i], moneda, check);
                        dtbody += result[0];
                        totalmn += result[1];
                        ctamn += result[2];
                        saldomn += result[3];
                        totalme += result[4];
                        ctame += result[5];
                        saldome += result[6];
                    }
                }else{
                    if ($("#categoria").val() != "") {
                        $.ajax({
                            url: '/balancepayments/consultar_detalle',
                            type: "GET",
                            data: {id: $("#categoria").val()},
                            success: function (datas) {
                                for (var j = 0; j < datas.length; j++) {
                                    if (datas[j].codigo === data.data[i].tipodoc) {
                                        var result = procesar(data.data[i], moneda, check);
                                        dtbody += result[0];
                                        totalmn += result[1];
                                        ctamn += result[2];
                                        saldomn += result[3];
                                        totalme += result[4];
                                        ctame += result[5];
                                        saldome += result[6];
                                    }
                                }
                            }
                        });
                    }else{
                        var result = procesar(data.data[i], moneda, check);
                        dtbody += result[0];
                        totalmn += result[1];
                        ctamn += result[2];
                        saldomn += result[3];
                        totalme += result[4];
                        ctame += result[5];
                        saldome += result[6];
                    }
                }
            }
        });
    }
    dtfooter += ("<tr>");

    if (check){
        dtfooter += ("<th colspan='9' bgcolor='#f2b957'>Totales:</th>");

    }else {
        dtfooter += ("<th colspan='2' bgcolor='#f2b957'>Totales:</th>");
    }
    dtfooter += ("<th bgcolor='#f2b957'>"+totalmn.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+ctamn.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+saldomn.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+totalme.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+ctame.toFixed(2)+"</th>");
    dtfooter += ("<th bgcolor='#f2b957'>"+saldome.toFixed(2)+"</th>");
    if (check){
        dtfooter += ("<th colspan='1' bgcolor='#f2b957'></th>");

    }

    dtfooter += ("</tr>");

    dato[0] = dthead;
    dato[1] = dtbody;
    dato[2] = dtfooter;

    return dato;
}
function procesar(data, moneda, check) {
    var totalmn = 0;
    var ctamn = 0;
    var saldomn = 0;
    var totalme = 0;
    var ctame = 0;
    var saldome = 0;
    var dtbody = '';
    dtbody += ("<tr>");
    dtbody += ("<td>" + data.codigo + "</td>");
    dtbody += ("<td>" + data.descripcion + "</td>");
    if (check) {
        dtbody += ("<td>" + data.suc_cod + "</td>");
        dtbody += ("<td>" + data.suc_dsc + "</td>");
        dtbody += ("<td>" + data.tipodoc + "</td>");
        dtbody += ("<td>" + data.documento + "</td>");
        dtbody += ("<td>" + data.fechadoc + "</td>");
        dtbody += ("<td>" + data.vencimiento + "</td>");
        dtbody += ("<td>" + data.moneda + "</td>");
    }
    if (moneda == 'O'){
        if (data.moneda_id != 1){
            dtbody += ("<td>" + 0 + "</td>");
            dtbody += ("<td>" + 0 + "</td>");
            dtbody += ("<td>" + 0 + "</td>");
            dtbody += ("<td>" + parseFloat(data.totalme).toFixed(2) + "</td>");
            dtbody += ("<td>" + (parseFloat(data.totalme) - parseFloat(data.saldome)).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data.saldome).toFixed(2) + "</td>");
        }
        if (data.moneda_id == 1){
            dtbody += ("<td>" + parseFloat(data.totalmn).toFixed(2) + "</td>");
            dtbody += ("<td>" + (parseFloat(data.totalmn) - parseFloat(data.saldomn)).toFixed(2) + "</td>");
            dtbody += ("<td>" + parseFloat(data.saldomn).toFixed(2) + "</td>");
            dtbody += ("<td>" + 0 + "</td>");
            dtbody += ("<td>" + 0 + "</td>");
            dtbody += ("<td>" + 0 + "</td>");
        }
    }else{
        dtbody += ("<td>" + parseFloat(data.totalmn).toFixed(2) + "</td>");
        dtbody += ("<td>" + (parseFloat(data.totalmn) - parseFloat(data.saldomn)).toFixed(2) + "</td>");
        dtbody += ("<td>" + parseFloat(data.saldomn).toFixed(2) + "</td>");
        dtbody += ("<td>" + parseFloat(data.totalme).toFixed(2) + "</td>");
        dtbody += ("<td>" + (parseFloat(data.totalme) - parseFloat(data.saldome)).toFixed(2) + "</td>");
        dtbody += ("<td>" + parseFloat(data.saldome).toFixed(2) + "</td>");
    }
    if (check) {
        dtbody += ("<td>" + data.glosa + "</td>");
    }
    dtbody += ("</tr>");

    if (moneda == 'O'){
        if (data.moneda_id != 1){
            totalmn += 0;
            ctamn += 0;
            saldomn += 0;
            totalme += parseFloat(data.totalme);
            ctame += parseFloat(data.totalme) - parseFloat(data.saldome);
            saldome += parseFloat(data.saldome);
        }
        if (data.moneda_id == 1){
            totalmn += parseFloat(data.totalmn);
            ctamn += parseFloat(data.totalmn) - parseFloat(data.saldomn);
            saldomn += parseFloat(data.saldomn);
            totalme += 0;
            ctame += 0;
            saldome += 0;
        }
    }else{
        totalmn += parseFloat(data.totalmn);
        ctamn += parseFloat(data.totalmn) - parseFloat(data.saldomn);
        saldomn += parseFloat(data.saldomn);
        totalme += parseFloat(data.totalme);
        ctame += parseFloat(data.totalme) - parseFloat(data.saldome);
        saldome += parseFloat(data.saldome);
    }
    return [dtbody, totalmn, ctamn, saldomn, totalme, ctame, saldome];
}
