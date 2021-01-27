$('#btn_nueva_linea').click(function () {
    $('#myModalUmedida').modal('show');
    limpia_modal();
});
function limpia_modal() {
    $("#cbo_um").val('');
    $("#txt_factor").val('');
}

/*$(document).ready(function () {
    var variable = $("#var").val();
    $("#cbo_um").select2({
        placeholder: "Type to select a sponsor",
        minimumInputLength: 2,
        multiple: false,
        width: 400,
        ajax: {
            url: "/" + variable + "/unidadmedida",
            data: function(term) {
                return term;
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(obj) {
                        return {
                            id: obj.id,
                            text: obj.codigo +' | '+ obj.descripcion
                        };
                    })
                };
            }
        }
    });
});*/

function agregar_item() {
    tabla = $('#tabla_unidad_medida').DataTable();
    var form = $('#form_detalle'); //add-item
    var variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'agregar_item',
        data: form.serialize(),
        success: function (data) {
            $('#myModalUmedida').modal('hide').data('bs.modal', null);
            tabla.ajax.reload();
            if (data.warning){
                warning('warning', data.warning);
            }
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('#tabla_unidad_medida tbody').on('click', '#btn_eliminar_detalleunidadmedida', function () {
    tabla_datos = $('#tabla_unidad_medida').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_unidad_conversion');
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

$('#tabla_unidad_medida tbody').on('click', '#btn_editar_detalleunidadmedida', function () {
    tabla_unidadconversion = $('#tabla_unidad_medida').DataTable();
    var data = tabla_unidadconversion.row($(this).parents('tr')).data();
    editar_unidadconversion(data);
});
function editar_unidadconversion(data){
    $('#myModalUmedidaedit').modal('show');
    $("#myModalUmedidaedit #cbo_um").val(data.options.umedida_id).trigger("change");
    $("#myModalUmedidaedit #txt_factor").val(data.options.factor);
    $("#myModalUmedidaedit #row_id").val(data.rowId);
}

function update_item() {
    tabla = $('#tabla_unidad_medida').DataTable();
    var form = $('#myModalUmedidaedit #form_detalle');
    var variable = $("#var").val();
    var datos = form.serialize()+"&cbo_um="+$("#myModalUmedidaedit #cbo_um").val();
    //alert(datos);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'update_item',
        data: datos,
        success: function (data) {
            console.log(data);
            $("#row_id").val('');
            $('#myModalUmedidaedit').modal('hide').data('bs.modal', null);
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
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


