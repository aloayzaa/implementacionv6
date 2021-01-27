$(document).ready(function () {
    fecha = $('#txt_fecha').val();
    if( $('#proceso').val() == 'create'){
        consultar_tipocambio(fecha);
    }
    validarFecha();
    llenarSelect('txt_tercero', "/customers/buscar_tercero");
    validarPuntoVenta();
});

function validarPuntoVenta() {
    $.ajax({
        type: "get",
        url: "/PedidosVenta/validarPuntoVenta",
        success: function (data) {
            if(data.error){
                warning('warning', data.error, 'Advertencia');
            }
        },

    });
}

var table = $('#table-detail-saleorder').DataTable({
    serverSide: true,
    ajax: '/PedidosVenta/listar_carrito',
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
        {data: 'options.umedida', "className": "text-center"},
        {data: 'options.cantidad', "className": "text-center"},
        {data: 'options.precio', "className": "text-center"},
        {data: 'options.descuento', "className": "text-center"},
        {data: 'options.precio', "className": "text-center"},
        {data: 'options.importe', "className": "text-center"},
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
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // Total over all pages
        totaldoc = api
            .column( 9 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        subtotal = api
            .column( 6 )
            .data()
            .reduce( function (a, b, index ) {
                return intVal(a) + intVal(b) * intVal(api.column(5).data()[index])
            }, 0 );

        descuento = api
            .column( 7 )
            .data()
            .reduce( function (a, b, index) {
                return intVal(a) + (intVal(api.column(6).data()[index])  * intVal(b)/100) * intVal(api.column(5).data()[index]);
            }, 0 );

        // Update footer
        $( api.column( 2 ).footer() ).html(
            'Sub-total: '+subtotal.toFixed(2)
        );

        $( api.column( 3 ).footer() ).html(
            'Descuento: '+descuento.toFixed(2)
        );

        $( api.column( 4 ).footer() ).html(
            'Total Doc: '+totaldoc.toFixed(2)
        );


    },
    order: [[1, 'asc']],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});

$('#table-detail-saleorder tbody').on('click', '#btnEdit', function () {
        var data = table.row($(this).parents('tr')).data();
        console.log(data);
        editar(data);
    }
);

$('#table-detail-saleorder tbody').on('click', '#btnDelete', function () {
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

    if(  $('#salesPoint').val() == ''){
        warning('warning', 'Ingresa un punto de venta', 'Advertencia');
        return false;
    }


    $('#modal_add').modal('show');
    mostrarProductos('/PedidosVenta/product');

});


function editar(data){
    $('#modal_add').modal('show');
    mostrarProductos('/PedidosVenta/product_edit?row_id='+data.rowId);

    console.log(data.options.id_codigo);
    $('#modal_edit_item').modal('show');

    $('#modal_edit_row_id').val(data.rowId);

    $("#modal_edit_producto_id").append("<option value='"+data.options.producto_id+"' selected>"+ data.options.producto_codigo + " | " + data.options.producto_desc + "</option>");

    $('#modal_edit_um').val(data.options.um);
    $('#modal_edit_lote').val(data.options.lote);
    $('#modal_edit_stock').val(data.options.stock);
    $('#modal_edit_cantidad').val(data.options.cantidad);
    $('#modal_edit_importe').val(data.options.importe);
    $('#modal_edit_precio').val(data.options.precio);
    $('#modal_edit_centrocosto_id').val(data.options.CCosto_id);
    $('#modal_edit_centrocosto_id').trigger('change');

    if(data.options.editable != 1){
        console.log('editableee');
        $('#modal_edit_producto_id').prop('disabled', true);
        $('#modal_edit_cantidad').prop('disabled', true);
        $('#modal_edit_importe').prop('disabled', true);
        $('#modal_edit_centrocosto_id').prop('disabled', true);
    }


}



function insertar_productos(){

    detalle_select =  getProductschecked('table-add-pedido');
    console.log(detalle_select);

    if(detalle_select == 0){ //Chekear
        alert('Seleccione una referencia')
    }else{
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "POST",
            url: "/PedidosVenta/addproductos",
            data: {
                items: detalle_select,
            },
            success: function (data) {
                $('#modal_add').modal('hide').data('bs.modal', null);
                console.log(data);
                table.ajax.reload();
            },
            error: function (data) {
                mostrar_errores(data);
            }
        });
    }
}


function destroy_item(data) {
    //   $("#save").attr("disabled", true);

    form = $('#form-edit-detail').serialize();
    console.log(form)

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url:"/PedidosVenta/eliminar_item",
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
function mostrarProductos(route) {

    var productos = $('#table-add-pedido').DataTable({
        serverSide: true,
        ajax: route,
        destroy: true,
        scrollX: false,
        rowId: 'producto_id',
        columnDefs: [
            {
                "targets": 1,
                "visible": false,
                "searchable": false
            }
        ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        "paging":   false,
        "ordering": false,
        "info":     true,
        "searching": true,
        "columns": [
            {
                'render': function (data, type, row, meta){
                    return '<input type="checkbox"  value="' + row.id + '" name="chkItem' + row.id + '" id="chkItem' + row.id + '" value="'+row.id+'" >';
                }
            },
            {data: 'options.producto_id', className: "text-center"},
            {data: 'options.producto_codigo', "className": "text-center"},
            {data: 'options.producto_desc', "className": "text-center"},
            {data: 'options.umedida', "className": "text-center"},
            {data: 'options.stock', "className": "text-center"},
            {
                data: function (row) {
                    return '<input type="text" id="" name="cantidad" value="'+row.options.cantidad+'" class="form-control">';
                },
                "orderable": "false",
                "className": "text-center"
            },
            {
                data: function (row) {
                    var selector = '';
                    if (row.options.precioventa == 1){
                        selector =  'checked';
                    }
                    return '<input type="text" id="" name="precio" class="form-control" value="'+row.options.precio+'">'
                },
                "orderable": "false",
                "className": "text-center"
            },
            {
                data: function (row) {
                    return '<input type="text" id="" name="descuento" value="'+row.options.descuento+'" class="form-control">'
                },
                "orderable": "false",
                "className": "text-center"
            },
        ],

        order: [[ 1, 'asc' ]],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });


}

