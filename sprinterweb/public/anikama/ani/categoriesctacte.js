$('#btn_nueva_linea').click(function () {
    $('#modal_add').modal('show');
});

function agregar_item() {
    tabla = $('#listCategoriesCtaCteDetail').DataTable();
    var form = $('#form_detalle'); //add-item
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'agregar_item',
        data: form.serialize(),
        success: function (data) {
            $('#modal_add').modal('hide').data('bs.modal', null);
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#listCategoriesCtaCteDetail tbody').on('click', '#btn_editar_documentos_asignados', function () {
    tabla_doc_asignados = $('#listCategoriesCtaCteDetail').DataTable();
    var data = tabla_doc_asignados.row($(this).parents('tr')).data();
    editar_doc_asignados(data);
});
function editar_doc_asignados(data){
    console.log(data);
    $('#modal_edit').modal('show');
    $("#modal_edit #modal_doc_asig_id").val(data.options.documento_id).trigger("change");
    $("#modal_edit #row_id").val(data.rowId);
}
function update_item() {

    tabla = $('#listCategoriesCtaCteDetail').DataTable();
    var form = $('#modal_edit #form_detalle');
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'update_item',
        data: form.serialize(),
        success: function (data) {
            console.log(data);
            $("#row_id").val('');
            $('#modal_edit').modal('hide').data('bs.modal', null);
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#listCategoriesCtaCteDetail tbody').on('click', '#btn_eliminar_documentos_asignados', function () {
    tabla_datos = $('#listCategoriesCtaCteDetail').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_documentos_asignados');
});
function destroy_item(data,tabla,ruta){

    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: {rowId: data.rowId,id: data.options.id},
        success: function (data) {
            tabla.ajax.reload();
            success('success', 'Detalle eliminado correctamente!', 'Información');
        },
        error: function (data) {
        }
    });
}
function guardar() {
    store()
}
function actualizar() {
    update()
}
function anula() {
    anular(0);
}

