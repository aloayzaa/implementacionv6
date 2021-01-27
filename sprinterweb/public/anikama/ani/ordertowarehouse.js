$(document).ready(function () {
    fecha = $('#txt_fecha').val();
    if( $('#proceso').val() == 'create'){
        consultar_tipocambio(fecha);
    }
    validarFecha();
    llenarSelect('txt_tercero', "/customers/buscar_tercero");
    llenarSelect('modal_edit_producto_id', "/products/tipom");
});

var table = $('#table-detail-pedido').DataTable({
    serverSide: true,
    ajax: '/PedidosAlmacen/listar_carrito',
    destroy: true,
    scrollX: true,
    columnDefs: [
        {
            "targets": 1,
            "visible": false,
            "searchable": false
        },
    ],
    "columns": [
        {
            "sName": "Index",
            "render": function (data, type, row, meta) {
                return meta.row + 1; // This contains the row index
            },
            "orderable": "false",
            "className": "text-center",
        },
        {data: 'options.item'},
        {data: 'options.producto_codigo',
            "render": function ( data, type, full, meta ) {
                return `<a onclick="producto(${full.options.producto_id})">${data == null ? '' : data}</a>`;
            },
            "className": "text-center"},
        {data: 'options.producto_desc', "className": "text-center"},
        {data: 'options.um', "className": "text-center"},
        {data: 'options.lote', "className": "text-center"},
        {data: 'options.fechaDetalle', "className": "text-center"},
        {data: 'options.stock', "className": "text-center"},
        {data: 'options.cantidad', "className": "text-center"},
        {data: 'options.CCosto_codigo', "className": "text-center"},
        {data: 'options.CCosto_desc', "className": "text-center"},///
        {
            data: function (row) {
                return '<a id="btnEdit" class="btn"><span style="color:royalblue" class="fa fa-edit fa-2x"></span></a>'
            },
            "orderable": "false",
            "className": "text-center"
        },
        {
            data: function (row) {
                return '<a id="btnDelete" class="btn"><span style="color:red" class="fa fa-trash-o fa-2x"></a>'
            },
            "orderable": "false",
            "className": "text-center"
        },
    ],
    order: [[1, 'asc']],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});

$('#table-detail-pedido tbody').on('click', '#btnEdit', function () {
        var data = table.row($(this).parents('tr')).data();
        editar(data);
    }
);

$('#table-detail-pedido tbody').on('click', '#btnDelete', function () {
        var data = table.row($(this).parents('tr')).data();
        destroy_item(data);
    }
);

$('#btn_nueva_linea').click(function () {
    //valida primero insertar fecha
    if(  $('#txt_fecha').val() == ''){
        warning('warning', 'Ingresa la fecha', 'Advertencia');
        return false;
    }
    if(  $('#txt_movimiento').val() == ''){
        warning('warning', 'Ingresa el tipo de movimiento', 'Advertencia');
        return false;
    }

    $("#modal_cantidad").val("1");
    $("#modal_um").val("");
    $("#modal_lote").val("");
    $("#modal_fechalote").val("");
    $("#modal_add select").val("").trigger("change");
    $('#modal_add').modal('show');
    llenarSelect('add_producto_id', "/products/tipom?mov="+$('#txt_movimiento').val());

});


function agregar_item() {

    if (!$('#modal_cantidad').val()) {//Cero por defecto si esta vacio para que no falle la comparacion laravel
        $('#modal_cantidad').val(0);
    }

    var formadddetail = $('#form-add-detail');
    var disabled = formadddetail.find(':input:disabled').removeAttr('disabled');
    var form = formadddetail.serialize(); //add-item
    disabled.attr('disabled', 'disabled');

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/PedidosAlmacen/agregar_item",
        data: form,
        success: function (data) {
            $('#modal_add').modal('hide').data('bs.modal', null);
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function editar(data) {
    $('#modal_edit_item').modal('show');

    $('#modal_edit_row_id').val(data.rowId);

    $("#modal_edit_producto_id").append("<option value='"+data.options.producto_id+"' selected>"+ data.options.producto_codigo + " | " + data.options.producto_desc + "</option>");

    $('#modal_edit_um').val(data.options.um);
    $('#modal_edit_lote').val(data.options.lote);
    $('#modal_edit_stock').val(data.options.stock);
    $('#modal_edit_cantidad').val(data.options.cantidad);
    $('#modal_edit_centrocosto_id').val(data.options.CCosto_id);
    $('#modal_edit_centrocosto_id').trigger('change');
}

function update_item() {

    if (!$('#modal_edit_cantidad').val()) {   //Cero por defecto si esta vacio para que no falle la comparacion laravel
        $('#modal_edit_cantidad').val(0);
    }

    var formeditdetail = $('#form-edit-item');
    var disabled = formeditdetail.find(':input:disabled').removeAttr('disabled');
    var form = formeditdetail.serialize(); //add-item
    disabled.attr('disabled', 'disabled');

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/PedidosAlmacen/update_carrito",
        data: form,
        success: function (data) {
            $('#modal_edit_item').modal('hide');
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function destroy_item(data) {
   // form = $('#form-edit-detail').serialize();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/PedidosAlmacen/eliminar_item",
        data: {rowId: data.rowId, item: data.options.item},
        success: function () {
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

$(".referente-add").change(function () {
    id = $('.referente-add').val();
    almacen = $('#txt_almacen').val();
    fecha = $('#txt_fecha').val();
    if(id != null){
        solicitarData(id, almacen, fecha);
    }
});

$(".referente-edit").change(function () {
    id = $('.referente-edit').val();
    almacen = $('#txt_almacen').val();
    fecha = $('#txt_fecha').val();
    if(id != null){
        solicitarData(id, almacen, fecha);
    }
});

$("#txt_sucursal").change(function () {
    sucursal = $('#txt_sucursal').val();
    if(sucursal != ''){
        getalmacenes(sucursal);
    }else{
        getalmacenes(0); //para traear todos los almacenes de vuelta
    }
});

function solicitarData(id, almacen, fecha) {
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/PedidosAlmacen/dataset/" + id,
        data: {almacen: almacen, fecha: fecha},
        success: function (data) {
            $('.um').val(data.unidad_codigo);
            $('.stock').val(data.stock[0].stock);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function getalmacenes(id) {
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/PedidosAlmacen/getalmacenes/" + id,
        success: function (data) {
         console.log(data);
         if($("#txt_almacen").val() == ''){
             $("#txt_almacen").focus();
             $("#txt_almacen").empty().append('<option value="" selected>-- Seleccione una opción  -- </option>');
             for (var i = 0; i < data.length; i++) {
                 $('#txt_almacen').append('<option value="' + data[i].id + '">' + data[i].descripcion + '</option>');
             }
         }
        },
    });
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
