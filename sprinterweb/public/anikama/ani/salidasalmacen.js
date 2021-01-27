
var table =  $('#table-detail').DataTable({
    serverSide: true,
    ajax: '/SalidasAlmacen/listar_carrito',
    //  responsive: true,
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
                return meta.row+1; // This contains the row index
            },
           "orderable": "false",
           "className": "text-center",
        },
        {data: 'options.item'},
        {data: 'options.producto_codigo', "className": "text-center"},
        {data: 'options.producto_desc', "className": "text-center"},
        {data: 'options.um', "className": "text-center"},
        {data: 'options.lote', "className": "text-center"},
        {data: 'options.fechaDetalle', "className": "text-center"},
        {data: 'options.stock', "className": "text-center"},
        {data: 'options.cantidad', "className": "text-center"},
        {data: 'options.costoUnitario', "className": "text-center"},
        {data: 'options.costoTotal', "className": "text-center"},
        {data: 'options.peso', "className": "text-center"},
        {data: 'options.CCosto_codigo', "className": "text-center"},

        {
            data: function (row) {
                return '<a id="btnEdit" class="btn btn-success"><span class="fa fa-edit"></span></a>'
            },
            "orderable": "false",
        },
        {
            data: function (row) {
                return '<button id="btnDelete" class="btn btn-danger"><span class="fa fa-trash"></button>'
            },
            "orderable": "false",
        },
    ],
    order: [[ 1, 'asc' ]],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});

$('#table-detail tbody').on( 'click', '#btnEdit', function () {
        var data = table.row( $(this).parents('tr') ).data();
        editar(data);
    }
);

$('#table-detail tbody').on( 'click', '#btnDelete', function () {
        var data = table.row( $(this).parents('tr') ).data();
        destroy_item(data);
    }
);

$('#btn_nueva_linea').click(function () {
    //valida primero insertar fecha
    if(  $('#txt_fecha').val() == ''){
        alert('Ingresa la fecha primero')
    }else{
        $('#modal_add').modal('show');
    }
});

function agregar_item() {
    form = $('#form-add-detail').serialize(); //add-item
    console.log(form)
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#token').val(),
        },
        type: "POST",
        url:"/SalidasAlmacen/agregar_item",
        data: form,
        success: function (data) {
            console.log(data);
            $('#modal_add').modal('hide');
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function editar(data){
    console.log(data);
    $('#myModal').modal('show');

    $('#row_id').val(data.rowId);
    $('#modal_edit_idcodigo').val(data.options.id_codigo);
    $('#modal_edit_idcodigo').trigger('change');
    $('#modal_um').val(data.options.um);
    $('#modal_stock').val(data.options.stock);
    $('#modal_cantidad').val(data.options.cantidad);
    $('#modal_costounitario').val(data.options.costoUnitario);
    $('#modal_lote').val(data.options.lote);
}

function update_item() {

    form = $('#form-edit-item').serialize();
    console.log(form)

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#token').val(),
        },
        type: "POST",
        url:"/SalidasAlmacen/update_carrito",
        data: form,
        success: function (data) {
            console.log(data);
            $('#myModal').modal('hide');
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function destroy_item(data) {
 //   $("#save").attr("disabled", true);

    form = $('#form-edit-detail').serialize();
    console.log(form)

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#token').val(),
        },
        type: "POST",
        url:"/SalidasAlmacen/eliminar_item",
        data: {rowId: data.rowId, item: data.options.item},
        success: function (data) {
            console.log(data);
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
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
