/*$("#cbo_tipo_reporte").change(function () {
    valida_tipo_check();
});

function valida_tipo_check() {
    var tipo = $("#cbo_tipo_reporte").val();

    if (tipo === 'PC') {
        $('#chk_ver_solo_pendientes').prop('disabled', true);
        $('#chk_ver_solo_pendientes').prop("checked", false);
    } else if (tipo === 'PS') {
        $('#chk_ver_solo_pendientes').prop('disabled', false);
    }
}*/
$("#pending").click(function () {
    var balances = $("#balances").val();
    valida_check(balances)
});
$("#balances").change(function () {
    var balances = $(this).val();
    valida_check(balances);
});
function valida_check(valor){
    if (valor === 'CM' || valor == 'PM'){
        $('#pending').prop('checked', false)
    }
}
$(document).ready(function () {
    var id_table = $('table').prop('id');
    var table = $('#'+id_table);
    init_datatable(table);
});

$("#mostrar").click(function () {
    var table = $('#listCurrentAccounts');
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
                var balances = $("#balances").val();
                dato = defecto(data, balances);

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

function defecto(data, balances) {
    var dthead = '';
    var dtbody = '';
    var dtfooter = '';
    var dato = [];

    var cargomn = 0;
    var saldomn = 0;
    var abonomn = 0;
    var cargome = 0;
    var saldome = 0;
    var abonome = 0;

    dthead += ("<tr role='row'>");

    if (balances === 'CS' || balances === 'PS') {
        dthead += ("<th rowspan='2' colspan='1'>F.Proceso</th>");
        dthead += ("<th rowspan='1' colspan='5'>Documento</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='6'>Documento</th>");
    }
    if (balances === 'CS' || balances === 'PS') {
        dthead += ("<th rowspan='2' colspan='1'>Referencia</th>");
    }
    dthead += ("<th rowspan='1' colspan='3'>M.N.</th>");
    dthead += ("<th rowspan='1' colspan='3'>M.E.</th>");
    dthead += ("<th rowspan='2' colspan='1'>Glosa</th>");
    if (balances === 'CM' || balances === 'PM') {
        dthead += ("<th rowspan='2' colspan='1'>Cuenta</th>");
    }
    dthead += ("</tr>");

    dthead += ("<tr role='row'>");
    dthead += ("<th rowspan='1' colspan='1'>TD</th>");
    dthead += ("<th rowspan='1' colspan='1'>Número</th>");
    if (balances === 'CS' || balances === 'PS') {
        dthead += ("<th rowspan='1' colspan='1'>Fecha</th>");
        dthead += ("<th rowspan='1' colspan='1'>Vencimiento</th>");
    } else {
        dthead += ("<th rowspan='1' colspan='1'>F.Proceso</th>");
        dthead += ("<th rowspan='1' colspan='1'>F.Emisión</th>");
        dthead += ("<th rowspan='1' colspan='1'>Vencimiento</th>");
    }
    dthead += ("<th rowspan='1' colspan='1'>Mon</th>");
    dthead += ("<th rowspan='1' colspan='1'>Cargos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Abonos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Saldo</th>");
    dthead += ("<th rowspan='1' colspan='1'>Cargos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Abonos</th>");
    dthead += ("<th rowspan='1' colspan='1'>Saldo</th>");
    dthead += ("</tr>");

    if (data.recordsTotal > 0) {
        $.each(data.data, function (i) {
            if ($("#pending").prop('checked')){
                if(data.data[i].saldomn != 0 || data.data[i].saldome != 0){
                    dtbody += ("<tr>");
                    if (balances === 'CS' || balances === 'PS') {
                        dtbody += ("<td>" + data.data[i].fechaproceso + "</td>");
                    }
                    dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
                    dtbody += ("<td>" + data.data[i].documento + "</td>");
                    dtbody += ("<td>" + data.data[i].fechaproceso + "</td>");
                    if (balances === 'CM' || balances === 'PM') {
                        dtbody += ("<td>" + data.data[i].fechaproceso + "</td>");
                    }
                    dtbody += ("<td>" + data.data[i].vencimiento + "</td>");
                    dtbody += ("<td>" + data.data[i].moneda + "</td>");
                    if (balances === 'CS' || balances === 'PS') {
                        dtbody += ("<td>" + data.data[i].referencia + "</td>");
                    }
                    dtbody += ("<td>" + data.data[i].cargomn + "</td>");
                    dtbody += ("<td>" + data.data[i].abonomn + "</td>");
                    dtbody += ("<td>" + data.data[i].saldomn + "</td>");
                    dtbody += ("<td>" + data.data[i].cargome + "</td>");
                    dtbody += ("<td>" + data.data[i].abonome + "</td>");
                    dtbody += ("<td>" + data.data[i].saldome + "</td>");
                    dtbody += ("<td>" + data.data[i].glosa + "</td>");
                    if (balances === 'CM' || balances === 'PM') {
                        dtbody += ("<td>" + data.data[i].cuenta + "</td>");
                    }
                    dtbody += ("</tr>");

                    cargomn += parseFloat(data.data[i].cargomn);
                    saldomn += parseFloat(data.data[i].saldomn);
                    abonomn += parseFloat(data.data[i].abonomn);
                    cargome += parseFloat(data.data[i].cargome);
                    saldome += parseFloat(data.data[i].saldome);
                    abonome += parseFloat(data.data[i].abonome);
                }
            }else{
                dtbody += ("<tr>");
                if (balances === 'CS' || balances === 'PS') {
                    dtbody += ("<td>" + data.data[i].fechaproceso + "</td>");
                }
                dtbody += ("<td>" + data.data[i].tipodoc + "</td>");
                dtbody += ("<td>" + data.data[i].documento + "</td>");
                dtbody += ("<td>" + data.data[i].fechaproceso + "</td>");
                if (balances === 'CM' || balances === 'PM') {
                    dtbody += ("<td>" + data.data[i].fechaproceso + "</td>");
                }
                dtbody += ("<td>" + data.data[i].vencimiento + "</td>");
                dtbody += ("<td>" + data.data[i].moneda + "</td>");
                if (balances === 'CS' || balances === 'PS') {
                    dtbody += ("<td>" + data.data[i].referencia + "</td>");
                }
                dtbody += ("<td>" + data.data[i].cargomn + "</td>");
                dtbody += ("<td>" + data.data[i].abonomn + "</td>");
                dtbody += ("<td>" + data.data[i].saldomn + "</td>");
                dtbody += ("<td>" + data.data[i].cargome + "</td>");
                dtbody += ("<td>" + data.data[i].abonome + "</td>");
                dtbody += ("<td>" + data.data[i].saldome + "</td>");
                dtbody += ("<td>" + data.data[i].glosa + "</td>");
                if (balances === 'CM' || balances === 'PM') {
                    dtbody += ("<td>" + data.data[i].cuenta + "</td>");
                }
                dtbody += ("</tr>");

                cargomn += parseFloat(data.data[i].cargomn);
                saldomn += parseFloat(data.data[i].saldomn);
                abonomn += parseFloat(data.data[i].abonomn);
                cargome += parseFloat(data.data[i].cargome);
                saldome += parseFloat(data.data[i].saldome);
                abonome += parseFloat(data.data[i].abonome);
            }

        });
    }
    dtfooter += ("<tr>");

    if (balances == 'CM'){
        dtfooter += ("<th colspan='6' bgcolor='#f2b957'>Totales:</th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+cargomn.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+saldomn.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+cargome.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+abonome.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
    }else {
        dtfooter += ("<th colspan='7' bgcolor='#f2b957'>Totales:</th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+cargomn.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+abonomn.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+cargome.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'>"+abonome.toFixed(2)+"</th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
        dtfooter += ("<th bgcolor='#f2b957'></th>");
    }


    dtfooter += ("</tr>");

    dato[0] = dthead;
    dato[1] = dtbody;
    dato[2] = dtfooter;

    return dato;
}
/*
$('#mostrar').click(function () {
    var ruta = $('#ruta').val();
    var form = $('#frm_reporte').serialize();
    tabladinamica(ruta, form);
    /!*$.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: ruta,
        data: form,
        success: function (data) {
            console.log(data);
        },error: function (data) {
            mostrar_errores(data);
        }
    });*!/
});
function tabladinamica(ruta, datos) {
    $.ajax({
        type: "GET",
        url: ruta,
        dataType: "JSON",
        data: datos,
        success: function (data) {
            console.log(data);
            let registros = data.data;
            table(registros);
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
var table = function (data) {
    let body = '';
    let registros = Object.getOwnPropertyNames(data[0]);
    for (var j=0; j<data.length; j++){
        body += '<tr>';
        for (var i in registros){
            body += '<td>'+data[0][registros[i]]+'</td>';
        }
        body += '</tr>';
    }
    console.log(body);
    $('#'+$('table').attr('id')+' tbody').append(body);
    $('#'+$('table').attr('id')).DataTable();
};*/
