$(document).ready(function () {
    validarFecha();
    var fecha = $('#txt_fecha').val();
    if( $('#proceso').val() == 'crea'){
        consultar_tipocambio(fecha);
    }
    if ($("#type").val() == "I"){table.init(columnas_ingreso());}else{table.init(columnas_egreso());}
    tipooperacion();
});
var variable = $("#var").val();
var token = $("#_token").val();
var table = function () {
    'use strict';

    return {
        init: function (columnas) {
            //  event.preventDefault();
            //$(document).ready(function () {
            $('#listBankCashMovementDetail').DataTable({
                serverSide: true,
                "ajax": {
                    "url": '/' + variable + '/listar_carrito',
                    "type": "POST",
                    data  : {_token: token}
                },
                //ajax: '/' + variable + '/listar_carrito',
                destroy: true,
                scrollX: true,
                columnDefs: columnas,
                //info: false,
                columns: [
                    {
                        "sName": "Index",
                        "render": function (data, type, row, meta) {
                            return meta.row + 1; // This contains the row index
                        },
                        "orderable": "false",
                    },
                    {data: 'options.item'},
                    {data: 'options.operacion_codigo'},
                    {data: 'options.operacion_descripcion'},
                    {data: 'options.tercero_codigo'},
                    {data: 'options.tercero_descripcion'},
                    {data: 'options.referencia_codigo'},
                    {data: 'options.importe'},
                    {data: 'options.percepcion'},
                    {data: 'options.retencion'},
                    {data: 'options.retencion_renta'},
                    {data: 'options.retencion_pension'},
                    {data: 'options.total'},
                    {data: 'options.cuenta_codigo'},
                    {data: 'options.glosa'},
                    {data: 'options.centrocosto_codigo'},
                    {data: 'options.centrocosto_descripcion'},
                    {data: 'options.actividad_codigo'},
                    {data: 'options.actividad_descripcion'},
                    {data: 'options.proyecto_codigo'},
                    {data: 'options.proyecto_desc'},
                    {
                        className: "text-center",
                        data: function (row) {
                            return '<a id="btnEdit" class="btn" onclick="editar_detalle('+'\''+ row.rowId +'\''+')"><span style="color:royalblue" class="fa fa-edit fa-2x"></span></a>';
                        },
                        "orderable": "false",
                    },
                    {
                        className: "text-center",
                        data: function (row) {
                            return '<a id="btnDelete" class="btn" onclick="eliminar_detalle('+'\''+ row.rowId +'\''+','+ row.options.item +')"><span style="color:red" class="fa fa-trash-o fa-2x"></a>';
                        },
                        "orderable": "false",
                    },
                ],
                //order: [[1, 'asc']],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
            });
            //});
        }
    };
}();

$('#btn_nueva_linea').click(function () {
    if(validar_modal() === false){return false;}

    limpia_modal();
    limpiar_referencia();

    $('#total_detalle_referencia').val(0);

    llenarSelect('customer', '/customers/buscar_tercero');
    llenarSelectCuentas('account', "/accountingplans/pcgs");
    $("#button_acction").text("Agregar Detalle");
    $('#modal_add').modal('show');
});

$('#btn_asiento').click(function () { //ver
    referencia = $('#asiento').val();
    asiento(referencia);
});

$("#bank").change(function () {
    getCuentasCorrientes();
});

$("#currentaccount").change(function () {
    if ($(this).val() != ""){
        let moneda_actual = $("#currency").val();
        let moneda_nueva = $("#currentaccount option:selected").data('moneda');
        if (moneda_actual == null){
            moneda();
        }else{
            tabla = $('#listBankCashMovementDetail').DataTable();
            let totalRecords = tabla.rows().count();
            if (moneda_actual != moneda_nueva && totalRecords > 0){eliminar_importe_detalle();}else{moneda();}
        }
    }
});

function getCuentasCorrientes(){
    $("#currentaccount").empty();
    ctacte();
}
function ctacte(){
    $.ajax({
        type: "GET",
        url: "/" + variable + "/ctacte?bank="+$("#bank").val(),
        dataType: "JSON",
        success: function (data) {
            $("#currentaccount").append('<option value="" >-Seleccionar-</option>');
            if (data) {
                data.forEach(function(data){
                    $("#currentaccount").append('<option value="' + data.id + '" data-moneda="' + data.moneda_id +'">' + data.codigo + ' | ' + data.descripcion + '</option>');
                });
            }
        }
    });
}
function eliminar_importe_detalle(){
    swal.fire({
        title: '!Advertencia',
        text: "Va a cambiar la moneda, deberá volver a llenar el importe del documento. Está seguro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "GET",
                url: "/" + variable + "/eliminar_importe",
                dataType: "JSON",
                success: function (data) {
                    if ($("#type").val() == 'E'){
                        table.init(columnas_egreso());
                    }else {
                        table.init(columnas_ingreso());
                    }
                    totales_pie_tabla(data);
                    moneda();
                }
            });
        }else if(result.dismiss == 'cancel') {
            $("#currentaccount").val("").trigger("change");
        }else if(result.dismiss == 'esc'){
            $("#currentaccount").val("").trigger("change");
        }
    });
}
function moneda(){
    $.ajax({
        type: "GET",
        url: "/" + variable + "/currency",
        dataType: "JSON",
        data: {currentaccount: $("#currentaccount").val() },
        success: function (data) {
            if (data) {
                $("#currency").append('<option value="' + data.id + '"  selected>' + data.codigo + ' | ' + data.descripcion + '</option>')
            }
        }
    });
}

$("#type").change(function (e) {
    let columnas = ($(this).val() == "E") ? columnas_egreso() : columnas_ingreso();
    table.init(columnas);
    tipooperacion();
});

$("#operation").change(function () {
    var pidedocumento = $("#operation option:selected").data('documento');
    if (pidedocumento === 1) {
        $("#customer").prop('disabled', false).val("").trigger('change');
        $(".buscar").prop('disabled', false);
    } else if (pidedocumento === 0) {
        $("#customer").prop('disabled', true).val("").trigger('change');
        $(".buscar").prop('disabled', true);

        limpiar_referencia();
    }
    $("#account").prop('disabled', false);
    $("#amount").prop('disabled', false);
});

function limpiar_referencia() {
    var route = '/bankmovement/reference';
    tableCashMovementReference.init(route + '?type=' + 'clean');
}

function limpia_modal() {

    var fproceso = $('#txt_fecha').val();
    $("#finaldate").val(fproceso).attr("max",fproceso);
    $("#finaldate_modal").val($("#txt_fecha").val());
    $("#amount").val('0.00').prop('disabled',false);
    $("#txt_cci").val("").prop('readonly',true);
    $("#total").val('0.00');
    $("#account").val('').trigger('change').prop('disabled',false);
    $("#cost").val("").trigger('change').prop('disabled',false);
    $("#activity").val("").trigger('change');
    $("#project").val("").trigger('change');
    $("#customer").val("").trigger("").prop('disabled', false);
    $("#operation").val("").trigger('change');
    $("#txt_percepcion").val("");
}

function tipooperacion() {
    $("#operation").empty();
    $.ajax({
        type: "GET",
        url: "/" + $("#var").val() + "/tipooperacion",
        dataType: "JSON",
        data: {type : $("#type").val()},
        success: function (data) {
            $("#operation").append("<option value='' selected>-Seleccione-</option>");
            if (data) {
                for (let i = 0; i < data.length; i++) {
                    $("#operation").append('<option value="' + data[i].id + '" data-documento="' + data[i].pidedocumento + '">' + data[i].codigo + ' | ' + data[i].descripcion + '</option>');
                }
            }

        }
    });
}

function referencetable() {
    let operation = $("#operation").val();
    if (operation == '') {
        error('error', "Seleccionar Operación", "Error!");
    }else{
        $('#total_detalle_referencia').val(0.00);
        tableCashMovementReference.init('/bankmovement/reference/' + '?operation=' + operation + '&customer=' + $("#customer").val() + '&finaldate=' + $("#finaldate").val() + '&type=' + $("#type").val() + '&id=' + $("#id").val() + '&changerate=' + $("#changerate").val());
        comprobar_datatable();
    }
}

function validar_modal(){
    if ($("#txt_fecha").val() === '') {error('warning', "Seleccionar fecha", "Error!"); return false;}
    if ($("#type").val() === null) {error('warning', "Seleccionar tipo", "Error!"); return false;}
    if ($("#bank").val() === null) {error('warning', "Seleccionar banco", "Error!"); return false;}
    if ($("#currentaccount").val() == '') {error('warning', "Seleccionar cuenta corriente", "Error!"); return false;}
    if ($("#changerate").val() == 0) {error('warning', "T.Cambio es obligatorio", "Error!"); return false;}
    if ($("#comment").val() == '') {error('warning', "La glosa es obligatorio", "Error!"); return false;}
}

function agregar_item() {
    let grupo = $("#var").val();
    let data = obtener_checks_seleccionados('listCashMovementReference');
    let form = $('#form-add-detail').serialize();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + grupo + "/agregar_item",
        data: form + "&data=" + JSON.stringify(data) + "&comment=" + $("#comment").val() + "&parent_id=" + $("#id").val() + "&txt_fecha=" + $("#txt_fecha").val() + "&currency=" + $("#currency").val() + "&changerate=" + $("#changerate").val() + '&type=' + $("#type").val(),
        success: function (data) {
            $('#modal_add').modal('hide').data('bs.modal', null);
            if ($("#type").val() == 'E'){
                table.init(columnas_egreso());
            }else {
                table.init(columnas_ingreso());
            }
            totales_pie_tabla(data);
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function editar(data) {
    //console.log(data);
    $("#button_acction").text("Editar Detalle");
    $('#modal_add').modal('show');
    limpia_modal();


    $("#operation").val(data.options.operacion_id).trigger('change'); // si está después del tercero, el mismo cambiará a su valor por defecto no tomará el append
    //En caso cambie el tipo
    if ($("#operation").val() == null){$("#operation").append("<option value='"+data.options.operacion_id+"' data-documento='"+data.options.pidedocumento+"' selected>"+data.options.operacion_codigo+" | "+data.options.operacion_descripcion+"</option>");}

    llenarSelectCuentas('account', "/accountingplans/pcgs");
    llenarSelect('customer', '/customers/buscar_tercero');

    if(data.options.tercero_id != null){
        $("#txt_cci").val(data.options.cci).prop('readonly', false);
        $("#customer").append("<option value='"+data.options.tercero_id+"' selected>" + data.options.tercero_codigo + " | " + data.options.tercero_descripcion + "</option>");
    }else{
        $("#txt_cci").val("").prop('readonly', true);
    }
    if(data.options.referencia_id != null){
        tableCashMovementReference.init('/bankmovement/reference_edit');
        $("#amount").prop('disabled', true);
        $("#account").prop('disabled', true);
        $("#cost").prop('disabled', true);
    }else{
        $("#amount").val(data.options.importe);
        $("#total").val(data.options.total);
        $("#account").append("<option value='"+data.options.cuenta_id+"' selected>" + data.options.cuenta_codigo + " | " + data.options.cuenta_descripcion +"<option>");
        if (data.options.pide_ccosto != 0){
            if(data.options.centrocosto_id != null){ $("#cost").prop('disabled',false).append("<option value='"+data.options.centrocosto_id+"' selected>" + data.options.centrocosto_codigo + " | " + data.options.centrocosto_descripcion + "<option>");}
        }else{
            $("#cost").val("").trigger('change').prop('disabled',true);
        }
    }

    $("#txt_percepcion").val(data.options.percepcion);
    if(data.options.actividad_id != null){ $("#activity").val(data.options.actividad_id).trigger('change');}
    if(data.options.proyecto_id != null){ $("#project").val(data.options.proyecto_id).trigger('change');}
    $("#rowId").val(data.rowId);
}

function eliminar_detalle(rowId, item) {
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/" + variable + "/eliminar_item",
        data: {rowId: rowId, item: item},
        success: function (data) {
            if ($("#type").val() == 'E'){
                table.init(columnas_egreso());
            }else {
                table.init(columnas_ingreso());
            }
            totales_pie_tabla(data);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function aplicar(id, documento, saldomn, saldome, cuenta, cuenta_codigo, cuenta_desc) {
    var value = checkbox('chkItem' + id);
    if (value === 1) {
        var moneda = $("#currency").val();
        var saldo = (moneda === '1') ? saldomn : saldome;

        $("#item" + id).val(parseFloat(saldo).toFixed(2)).prop('readonly', false);
        var total = obtener_checks_seleccionados('listCashMovementReference');
        cheks_seleccionados(total);
        total_referencia(total);
    } else {
        $("#item" + id).val("0.00").prop('readonly', true);
        var total = obtener_checks_seleccionados('listCashMovementReference');
        cheks_seleccionados(total);
        total_referencia(total);
    }
}

function obtener_checks_seleccionados(id) {
    var tabla = $('#' + id).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        var data = this.node();
        if ($(data).find('input').prop('checked')) {
            det = {
                'ids': $(data).find('input').serializeArray()[0],
                'aplicar': $(data).find('input').serializeArray()[1],
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}


$("#chkcheque").change(function () {
    if (checkbox('chkcheque') === 1) {
        $("#cheque").prop("readonly", false).val('');
        $("#chkdiffer").prop("disabled", false).prop("checked", false);
        $("#girado").prop("readonly",false).val('');
    } else {
        $("#cheque").prop("readonly", true).val('');
        $("#chkdiffer").prop("disabled", true).prop("checked", false);
        $("#girado").prop("readonly", true).val('');
        $("#differ").prop("readonly", true).val('');
    }
});

$("#chkdiffer").change(function () {
    if (checkbox('chkdiffer') === 1) {
        $("#differ").prop("readonly", false).val('');
    } else {
        $("#differ").prop("readonly", true).val('');
    }
});
function valor_item(id, saldomn, saldome) {
    var moneda = $("#currency").val();
    var valor_maximo = moneda === '1' ? saldomn : saldome;
    var valor_item = $("#item" + id).val();
    valor_item  =  (valor_item > valor_maximo) ? valor_maximo : valor_item;
    $("#item" + id).val(valor_item);
    var total = obtener_checks_seleccionados('listCashMovementReference');
    total_referencia(total);
}
$('#account').on('select2:select', function (e) {
    var data = e.params.data;
    limpiar_referencia();
    //(data.otros["tipo_auxiliar"] == "T") ? $("#customer").prop('disabled',false).val('').trigger('change') : $("#customer").prop('disabled',true).val('').trigger('change'); // el tipo de operación tiene prioridad
    //(data.otros["pide_ccosto"] != 0) ? $("#cost").prop('disabled',false).val('').trigger('change') : $("#cost").prop('disabled',true).val('').trigger('change');
});

function cheks_seleccionados(total){
    if(total.length > 0){
        $("#amount").prop("disabled",true).val("0.00");
        $("#account").prop("disabled",true).val("").trigger('change');
        $("#cost").prop("disabled",true).val("").trigger("change");
    }else{
        $("#amount").prop("disabled",false);
        $("#account").prop("disabled",false);
        $("#cost").prop("disabled",false);
    }
    if(total.length > 1){ //Más de una referencia, no agregar lo siguiente:
        $("#activity").prop('disabled', true).val("").trigger("change");
        $("#project").prop('disabled', true).val("").trigger("change");
        $("#txt_percepcion").prop('disabled', true).val("0.00");
    }else{
        $("#activity").prop('disabled', false);
        $("#project").prop('disabled', false);
        $("#txt_percepcion").prop('disabled', false);
    }
}
function comprobar_datatable(){
    $('#listCashMovementReference').DataTable().on("draw", function(){
        let total = obtener_checks_seleccionados('listCashMovementReference');
        cheks_seleccionados(total);
    });
}

function columnas_ingreso(){
    let columnas = [
        {
            "targets": [ 0 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 9 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 10 ],
            "visible": false,
            "searchable": false
        },
        {
            "targets": [ 11 ],
            "visible": false,
            "searchable": false
        }
    ];
    return columnas;
}
function columnas_egreso(){
    let columnas = [{
        "targets": 0,
        "visible": false,
        "searchable": false
    }];
    if ($("#lagenteret").val() == 0){
        columnas = [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 9 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 10 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 11 ],
                "visible": false,
                "searchable": false
            }
        ];
    }
    return columnas;
}


function totales_pie_tabla(data){
    $("#txt_retencion").val(data.retencion);
    $("#txt_retencion_renta").val(data.retencion_renta);
    $("#txt_retencion_pension").val(data.retencion_pension);
    $("#txt_descuento").val(data.descuento);
    $("#txt_total_detalle").val(data.total);
}
function editar_detalle(rowId){
    let url = "/" + variable + "/editar_detalle/?rowId="+rowId+'&txt_fecha='+$("#txt_fecha").val()+'&changerate='+ $("#changerate").val();
    fetch(url)
        .then((response) => response.json())
        .then((response) => editar(response))
        .catch(error => console.error(error));
}
$("#amount").blur(function(){
    $("#amount").val(parseFloat($("#amount").val()).toFixed(2));
});
$("#txt_percepcion").blur(function(){
    $("#txt_percepcion").val(parseFloat($("#txt_percepcion").val()).toFixed(2));
});
function guardar() {
    if (validarDetalles2()) {
        store();
    }
}
function actualizar() {
    if (validarDetalles2()) {
        update()
    }
}
function validarDetalles2(){
    let moneda = $("#currency").val();
    let moneda_cuenta = $("#currentaccount option:selected").data('moneda');
    if (moneda != moneda_cuenta){error('error', 'No coincide el tipo de moneda', 'Error!');return false;}

    let tabla = $('#listBankCashMovementDetail').DataTable();
    let respuesta = (tabla.data().count() == 0) ? false : true;
    if(respuesta == false){warning('warning', 'Ingrese al menos un detalle', 'Advertencia');}
    return respuesta;
}
//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(1);
}

