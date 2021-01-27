$('#btn_nueva_linea').click(function () {
    var validacion = $("#tradeName").val();
    if (validacion == ''){
        info('info', 'Ingrese la descripción para poder general el modelo');
    }else{
        var variable = $("#var").val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('#_token').val()},
            type: "get",
            url: "/" + variable + "/" + 'autogenerar_codigo',
            data: {id: $('#code_marca').val()},
            success: function (data) {
                limpia_modal();
                $('#marca_id').val($('#code_marca').val());
                $('#modelo_id').val(data);
                $('#modal_add').modal('show');
            },error: function (data) {
                mostrar_errores(data);
            }
        });

    }
});

function limpia_modal() {
    $("#marca_id").val('');
    $("#modelo_id").val('');
    $('#modelo_descripcion').val('');
    $('#modelo_nombrecomercial').val('');
}
function agregar_item() {
    tabla = $('#tabla_listado_modelos').DataTable();
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
$('#tabla_listado_modelos tbody').on('click', '#btn_editar_modelo', function () {
    tabla = $('#tabla_listado_modelos').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_modelo(data);
});
function editar_modelo(data){
    $('#modal_edit').modal('show');
    $("#modal_edit #marca_id").val(data.options.marca_id).trigger("change");
    $("#modal_edit #modelo_id").val(data.options.codigo).trigger("change");
    $("#modal_edit #modelo_descripcion").val(data.options.descripcion).trigger("change");
    $("#modal_edit #modelo_nombrecomercial").val(data.options.nombrecomercial);
    $("#modal_edit #row_id").val(data.rowId);
}
function update_item() {
    tabla = $('#tabla_listado_modelos').DataTable();
    var form = $('#modal_edit #form_detalle');
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'update_item',
        data: form.serialize(),
        success: function (data) {
            $("#row_id").val('');
            $('#modal_edit').modal('hide').data('bs.modal', null);
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('#tabla_listado_modelos tbody').on('click', '#btn_eliminar_modelo', function () {
    tabla_datos = $('#tabla_listado_modelos').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_modelo');
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
