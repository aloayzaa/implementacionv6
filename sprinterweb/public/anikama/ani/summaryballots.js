$(document).ready(function () {
    fecha = $('#txt_fecha').val();
    if( $('#proceso').val() == 'create'){
        consultar_tipocambio(fecha);
    }
    validarFecha();
    llenarSelect('referencia_tercero', "/customers/buscar_tercero");
});

var table = $('#table-detail-summaryballots').DataTable({
    serverSide: true,
    ajax: '/SummaryBallots/listar-carrito',
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
        {data: 'options.id'},
        {data: 'options.codigo', "className": "text-center"},
        {data: 'options.descripcion', "className": "text-center"},
        {data: 'options.docrefer', "className": "text-center"},
        {data: 'options.codigo', "className": "text-center"},
        {data: 'options.moneda', "className": "text-center"},
        {data: 'options.total', "className": "text-center"},
        {data: 'options.estado', "className": "text-center"},

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

$('#table-detail-summaryballots tbody').on('click', '#btnEdit', function () {
        var data = table.row($(this).parents('tr')).data();
        console.log(data)
        editar(data);
    }
);

$('#table-detail-summaryballots tbody').on('click', '#btnDelete', function () {
        var data = table.row($(this).parents('tr')).data();
        destroy_item(data);
    }
);

$('#btn_nueva_linea').click(function () {
    //valida primero insertar fecha

    if(  $('#txt_fecha').val() == ''){
        alert('Ingresa la fecha primero')
    }else{
        referencia();
    }

});

async function referencia() {
    tercero = $('#txt_tercero').val();
    moneda = $('#txt_moneda').val();
    tcambio = $('#txt_tcambio').val();

    if(tercero == ''){
        warning('warning', 'Ingrese una razón social', 'Advertencia')
        return false
    }
    if(moneda == ''){
        warning('warning', 'Ingrese una moneda', 'Advertencia')
        return false
    }

    if(tcambio == 0){
        warning('warning', 'Ingrese un tipo de cambio', 'Advertencia')
        return false
    }

    var tcambio = await gettcambio();

    if(tcambio.t_venta < 1 ){
        warning('warning', 'No existe tcambio para esa fecha de registro', 'Advertencia')
        return false
    }

    $("#add_cuenta_id").prop("disabled", false);
    $("#add_cuenta_id").append("<option value='"+0+"' selected disabled>"+ '--Seleccione una opción--' + "</option>");
    $("#add_centrocosto_id").prop("disabled", false);
    $("#modal_importe").prop("disabled", false);
    $('#modal_add').modal('show');

    mostrarReferencias('limpiar');
}

function gettcambio() {
    return $.ajax({
        type: "GET",
        url: "/exchangerate/consultar/" + $('#txt_fecha').val(),
    });
}

function limpiar_selector(name){
    $("#"+name).append("<option value = 0 selected>"+ '--Seleccione una opción--' + "</option>");
}


function mostrarReferencias(accion = null) {

    tercero = $('#referencia_tercero').val();
    fechaproceso = $('#txt_fecha').val();

    console.log(tercero)

    var tablita = $('#list-references').DataTable({
        serverSide: true,
        ajax: '/SummaryBallots/aplicadoc/'+fechaproceso+'?tercero='+tercero+'&accion=' +accion,
        destroy: true,
        scrollX: false,
        rowId: 'id',
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
        "info":     false,
        "searching": false,
        "columns": [
            {
                'render': function (data, type, row, meta){
                    if(row.options.elegido == 0) {
                        return '<input type="checkbox" value="' + row.options.id + '" name="chkItem' + row.options.id + '" id="chkItem' + row.options.id + '" value="'+row.id+'" onchange="eventcheck(' + row.options.id + ',' + row.options.saldomn + ',' + row.options.saldome + ', \''+row.options.cuenta_cod + ' | ' +row.options.cuenta_desc +'\''  +')">';
                    } else if (row.options.elegido == 1) {
                        return '<input type="checkbox" checked value="' + row.options.id + '"  name="chkItem' + row.options.id + '" id="chkItem' + row.options.id + '"  value="'+row.id+'" onchange="eventcheck(' + row.options.id + ',' + row.options.saldomn + ', ' + row.options.saldome + ', \'' + row.options.cuenta_cod +' | ' + row.options.cuenta_desc +'\''  + ')">';
                    }
                }
            },
            {data: 'options.id', className: "text-center"},
            {data: 'options.aplicar', className: "text-center",
                /*  "render": function ( data, type, full, meta ) {
                      return '<input type="number" class="form-control text-center" style="width: 80px" id="aplicar" name="aplicar" ' +
                          'max="'+full.options.saldomn+'" value="'+ (full.options.saldomn)+'">';
                  },
  */
                "render": function (data, type, row) {
                    if (row.options.elegido == 0) {
                        return '<input readonly id="item' + row.id + '" name="item' + row.id + '" type="number" step="0.1" value="' + parseFloat(data).toFixed(2) + '"' +
                            'onblur="valor_item('+ row.id + ',' + row.options.saldomn +' , '+ row.options.saldome +')">';
                    } else if (row.options.elegido == 1) {
                        return '<input id="item' + row.id + '" name="item' + row.id + '" type="number" step="0.1" value="' + parseFloat(data).toFixed(2) + '"' +
                            'onblur="valor_item('+ row.id + ',' + row.options.saldomn +' , '+ row.options.saldome +')">';
                    }
                }
            },
            {data: 'options.documento', className: "text-center"},
            {data: 'options.fechaproceso', className: "text-center"},
            {data: 'options.vencimiento', className: "text-center"},
            {data: 'options.moneda', className: "text-center"},
            {data: 'options.saldomn', className: "text-center"},
            {data: 'options.saldome', className: "text-center"},
            {data: 'options.glosa', className: "text-center"},
        ],
        order: [[ 1, 'asc' ]],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

}




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
    console.log(data)
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

function getResumen(route) {

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: route,
        success: function (data) {
            console.log(data);
            table.ajax.reload();
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
