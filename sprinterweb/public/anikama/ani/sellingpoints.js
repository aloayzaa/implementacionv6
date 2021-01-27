$(document).ready(function () {
    $( "#txt_codigo" ).focus();
});
var variable = $("#var").val();
var table = $('#listAssociatedDocuments').DataTable({
    serverSide: true,
    ajax: '/sellingpoints/documentos_asociados',
    destroy: true,
    scrollX: true,
    "columns": [
        {
            "sName": "Index",
            "render": function (data, type, row, meta) {
                return meta.row + 1; // This contains the row index
            },
            "orderable": "false",
        },
        {data: 'options.documentocom_codigo'},
        {data: 'options.documentocom_descripcion'},
        {data: 'options.serie'},
        {data: 'options.lineas'},
        {
            data: function (row) {
                return '<a id="btnEdit" class="btn"><span class="fa fa-edit fa-2x"></span></a>'
            },
            "orderable": "false",
        },
        {
            data: function (row) {
                return '<a  id="btnDelete"  class="btn"><span style="color:red" class="fa fa-trash-o fa-2x"></a>'
            },
            "orderable": "false",
        },
    ],
   // order: [[1, 'asc']],
    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});
$('#btn_nueva_linea').click(function () {
    limpiar_modal();
    $("#btn_agregar_detalle").text("Agregar Detalle");
    $('#myModalDocumentAssociated').modal('show');
});
function acciones(ruta){
    let form = $("#form-add-detail").serialize();
    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "POST",
        url: "/" + variable + "/" + ruta,
        data: form + "&id=" + $("#id").val(),
        success: function (data) {
            $('#myModalDocumentAssociated').modal('hide');
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}
function eliminar_detalle(data){
    $.ajax({
        headers: {'X-CSRF-Token': $('#_token').val()},
        type: "POST",
        url: "/" + variable + "/eliminar_documentos_asociados",
        data: {rowId: data.rowId, id: data.options.id},
        success: function (data) {
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}
function limpiar_modal(){
    document.getElementById('form-add-detail').reset();
    $("#rowId").val("");
    $("#cbo_documentocom").val("").trigger('change');
}
$('#listAssociatedDocuments tbody').on('click', '#btnEdit', function () {
        var data = table.row($(this).parents('tr')).data();
        editar(data);
    }
);
$('#listAssociatedDocuments tbody').on('click', '#btnDelete', function () {
        var data = table.row($(this).parents('tr')).data();
        eliminar_detalle(data);
    }
);
function editar(data){
    console.log(data);
    $("#rowId").val(data.rowId);
    $("#cbo_documentocom").val(data.options.documentocom_id).trigger("change");
    $("#txt_serie").val(data.options.serie);
    $("#txt_lineas").val(data.options.lineas);
    $("#pv_series_id").val(data.options.id);
    $("#btn_agregar_detalle").text("Editar Detalle");
    $("#myModalDocumentAssociated").modal("show");
}
function agregar_detalle(){
    let ruta = ($("#rowId").val() != '') ? 'editar_documentos_asociados' : 'agregar_documentos_asociados';
    acciones(ruta)
}
function guardar() {
    if(validarDetalles()){
        store()
    }
}
function actualizar() {
    if(validarDetalles()){
        update()
    }
}
//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(0);
}
