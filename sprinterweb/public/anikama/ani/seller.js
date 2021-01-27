$('#btn_nueva_linea').click(function () {
    $('#modal_add').modal('show');
    limpia_modal();
});

function limpia_modal() {
    $("#modal_marca_id").val('');
    $("#modal_hasta").val('');
    $('#modal_desde').val('');
    $('#modal_meta').val('');
    $('#modal_comision').val('');
}

$(".referente-add").change(function () {
    id = $('.referente-add').val();
    solicitarData(id);
});

$(".referente-adit").change(function () {
    id = $('.referente-adit').val();
    solicitarData(id);
});

function solicitarData(id) {
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/seller/dataset/" + id,
        data: { },
        success: function (data) {
            $('.des').val(data.descripcion);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function agregar_item() {
    tabla = $('#tabla-comisiones-marca').DataTable();
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

$('#tabla-comisiones-marca tbody').on('click', '#btn_editar_marca_comision', function () {
    tabla_comisionmarca = $('#tabla-comisiones-marca').DataTable();
    //console.log(tabla_comisionmarca);
    var data = tabla_comisionmarca.row($(this).parents('tr')).data();
    editar_comosionmarca(data);
});
function editar_comosionmarca(data){
    console.log(data);
    $('#modal_edit').modal('show');
    $("#modal_edit #modal_marca_id").val(data.options.marca_id).trigger("change");
    $("#modal_edit #modal_descripcion").val(data.options.descripcion).trigger("change");
    $("#modal_edit #modal_desde").val(data.options.desde);
    $("#modal_edit #modal_hasta").val(data.options.hasta);
    $("#modal_edit #modal_meta").val(data.options.meta);
    $("#modal_edit #modal_comision").val(data.options.comision);
    $("#modal_edit #row_id").val(data.rowId);
}
function update_item() {
    tabla = $('#tabla-comisiones-marca').DataTable();
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

$('#tabla-comisiones-marca tbody').on('click', '#btn_eliminar_marca_comision', function () {
    tabla_datos = $('#tabla-comisiones-marca').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_datos_comision');
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


