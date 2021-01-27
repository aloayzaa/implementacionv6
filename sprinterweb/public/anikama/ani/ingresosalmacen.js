$(document).ready(function () {
    validarFecha();
    fechaproceso = $('#txt_fecha').val();
    fechafactura = $('#txt_fechafactura').val();
    if( $('#proceso').val() == 'create'){
        fecha = fechafactura == '' ? fechaproceso : fechafactura;
        consultar_tipocambio(fecha);
    }
    llenarSelect('txt_tercero', "/customers/buscar_tercero");
    llenarSelect('add_producto_id', "/products/buscar_producto");
    llenarSelect('modal_edit_producto_id', "/products/buscar_producto");
});

$("#txt_fecha").blur(function(e) {   //No uso del crud2 por ser caso especial
    if($('#txt_fechafactura').val() == ''){
        consultar_tipocambio($('#txt_fecha').val());
    }else{
        consultar_tipocambio($('#txt_fechafactura').val());
    }
});

$("#txt_fechafactura").blur(function(e) {   //No uso del crud2 por ser caso especial
    consultar_tipocambio($('#txt_fechafactura').val());
});

var table =  $('#table-detail-ingreso').DataTable({
    serverSide: true,
    ajax: '/IngresosAlmacen/listar_carrito',
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
        {data: 'options.producto_codigo',
            "render": function ( data, type, full, meta ) {
                return `<a onclick="producto(${full.options.producto_id})">${data == null ? '' : data}</a>`;
            },
            "className": "text-center"},
        {data: 'options.producto_desc', "className": "text-center"},
        {data: 'options.um', "className": "text-center"},
        {data: 'options.lote', "className": "text-center"},
        {data: 'options.fechaDetalle', "className": "text-center"},
        {data: 'options.cantidad', "className": "text-center"},
        {data: 'options.precio', "className": "text-center"},
        {data: 'options.importe', "className": "text-center"},
        {data: 'options.CCosto_codigo', "className": "text-center"},///
        {data: 'options.CCosto_desc', "className": "text-center"},
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

        totalcant = api
            .column( 7 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
        $( api.column( 3 ).footer() ).html(
           'Total Doc: '+totaldoc.toFixed(2)
        );
        $( api.column( 2 ).footer() ).html(
            'Total Cantidad: '+totalcant.toFixed(2)
        );
    },
    order: [[ 1, 'asc' ]],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});

$('#table-detail-ingreso tbody').on( 'click', '#btnEdit', function () {
        var data = table.row( $(this).parents('tr') ).data();
        console.log(data)
        editar(data);
    }
);

$('#table-detail-ingreso tbody').on( 'click', '#btnDelete', function () {
        var data = table.row( $(this).parents('tr') ).data();
        destroy_item(data);
    }
);

$('#btn_nueva_linea').click(function () {
    //valida primero insertar fecha
    if($('#txt_referencia_id').val() != ''){
        warning('warning', 'Los detalles provienen de una orden', 'Advertencia');
        return false
    }
    if($('#txt_fecha').val() == ''){
        alert('Ingresa la fecha primero')
    }else{
        $("#modal_cantidad").val("1");
        $("#modal_precio").val("00");
        $("#modal_importe").val("00");
        $("#modal_um").val("");
        $("#modal_lote").val("");
        $("#modal_fechalote").val("");
        $("#modal_add select").val("").trigger("change");
        $('#modal_add').modal('show');
    }
});





$('#btn_asiento').click(function () {
    referencia = $('#asiento').val();
    asiento(referencia);
});


if($('#txt_referencia_id').val() == ''){   //ocultar span si no hay referencia
    $('#span-referencia').hide();
}

$('#span-referencia').click(function () {
    span = $('#txt_referencia_id').val();
    referencia(span);
});

function agregar_item() {
    var importe = $('#modal_importe').val();
    var cantidad =  $('#modal_cantidad').val();
    $('#modal_precio').val(importe / cantidad);

    var formadddetail = $('#form-add-detail');
    var disabled = formadddetail.find(':input:disabled').removeAttr('disabled');
    form = formadddetail.serialize(); //add-item
    disabled.attr('disabled','disabled');

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#token').val(),
        },
        type: "POST",
        url:"/IngresosAlmacen/agregar_item",
        data: form,
        success: function (data) {
            $('#modal_add').modal('hide');
            table.ajax.reload();
            solicitarTotales();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function editar(data){
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


function update_item() {

    var importe = $('#modal_edit_importe').val();
    var cantidad =  $('#modal_edit_cantidad').val();
    $('#modal_edit_precio').val(importe / cantidad);

    var formeditdetail = $('#form-edit-item');
    var disabled = formeditdetail.find(':input:disabled').removeAttr('disabled');
    form = formeditdetail.serialize(); //add-item
    console.log(form)
    disabled.attr('disabled','disabled');

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#token').val(),
        },
        type: "POST",
        url:"/IngresosAlmacen/update_carrito",
        data: form,
        success: function (data) {
            console.log(data);
            $('#modal_edit_item').modal('hide');
            table.ajax.reload();
            solicitarTotales();
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
        url:"/IngresosAlmacen/eliminar_item",
        data: {rowId: data.rowId, item: data.options.item},
        success: function () {
            table.ajax.reload();
            solicitarTotales();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}


$(".referente-add").change(function(){
    id = $('.referente-add').val();
    almacen = $('#txt_almacen').val();
    fecha = $('#txt_fecha').val();

    if(id != null){
        solicitarData(id, almacen, fecha);
    }


});
$(".referente-edit").change(function(){
    id = $('.referente-edit').val();
    almacen = $('#txt_almacen').val();
    fecha = $('#txt_fecha').val();

    if(id != null){
        solicitarData(id, almacen, fecha);
    }

});

function solicitarData(id, almacen, fecha){
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url:"/PedidosAlmacen/dataset/" +id,
        data: {almacen: almacen, fecha: fecha},
        success: function (data) {
            console.log(data);
            $('.um').val(data.unidad_codigo);
            $('.stock').val(data.stock[0].stock);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function solicitarTotales(){
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url:"/IngresosAlmacen/totalizar",
        success: function (data) {
            $('#txt_total').val(data.total);
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

function add_orden(id) {
    tipomov =  $('#txt_movimiento').val();

    if(tipomov === ''){
        warning('warning', 'Seleccione un tipo de movimiento', 'Advertencia');
        return false;   //mucho if else
    }
    $('#modal_ref').modal('show');
    fechahasta = $('#txt_fecha').val();
    $('#referencia_hasta').val($('#txt_fecha').val());
    console.log(id, tipomov);
    mostrarDetalleReferencias('vaciar', id);
    mostrarReferencias(id, tipomov, fechahasta);
}

function mostrarReferencias(id, tipomov, fechahasta) {
    var documentos = $('#ref_documento').DataTable({
        serverSide: true,
        ajax: '/IngresosAlmacen/references?id='+id+'&tipomov='+ tipomov+'&fechahasta='+ fechahasta,
        destroy: true,
        scrollX: false,
        rowId: 'id',
        columnDefs: [
            {
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            },
            {
                "targets": 1,
                "visible": false,
                "searchable": false
            }
        ],
        "paging":   false,
        "ordering": true,
        "info":     false,
        "searching": true,

        "columns": [
            {
                'render': function (data, type, row, meta){
                    return "";
                }
            },
            {data: 'id', className: "text-center"},
            {data: 'documento', className: "text-center"},
            {data: 'estado', className: "text-center"},
            {data: 'fecha', className: "text-center"},
            {data: 'nombre', className: "text-center"},
            {data: 'glosa', className: "text-center"},

        ],
        order: [[ 1, 'asc' ]],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

}

$('#ref_documento tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
        mostrarDetalleReferencias('vaciar', id);
    }
    else {
        $('#ref_documento tr.selected').removeClass('selected');
        $(this).addClass('selected');
        id =  $('#ref_documento tr.selected').attr("id");
        mostrarDetalleReferencias('agregar', id);
    }
} );


function mostrarDetalleReferencias(accion, id) {
    var docuementos_detalles = $('#ref_docdetalles').DataTable({
        serverSide: true,
        ajax: '/IngresosAlmacen/references_detail/'+accion +'?id=' +id,
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
            style:    'multi',
            selector: 'input:checkbox'
        },
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        "columns": [
            {
                'render': function (data, type, row, meta){
                    return '<input type="checkbox" name="iditem" value="'+row.options.id +"|"+ row.options.item+'">';
                }
            },

            {data: 'options.id', className: "text-center"},
            {data: 'options.aplicar', className: "text-center",
                "render": function ( data, type, full, meta ) {
                    return '<input type="number" class="form-control text-center" style="width: 80px" id="aplicar" name="aplicar" ' +
                        'max="'+full.options.cantidad+'" value="'+ (full.options.cantidad - full.options.atendido)+'">';
                },
            },
            {data: 'options.prd_cod', className: "text-center"},
            {data: 'options.prd_dsc', className: "text-center"},
            {data: 'options.ume_cod', className: "text-center"},
            {data: 'options.cantidad', className: "text-center"},
            {
                data: 'options.serie', className: "text-center",
                'render': function (data, type, row, meta) {
                    return ''
                },
            },
            {data: 'options.atendido', className: "text-center"},
            {data: 'options.stock', className: "text-center",
                'render': function (data, type, row, meta){
                    return  '0'
                }
            },
        ],
        order: [[ 1, 'asc' ]],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

}


function insertar_referencia(){

    tabla = $('#ref_docdetalles').DataTable();
    let detalle_select = [];
    let det = {};

        tabla.rows().every(function (rowIdx, tableLoop, rowLoop){
            var data = this.node();
            if($(data).find('input').prop('checked')){
                det = {
                    id:   $(data).find('input').serializeArray()[0].value,
                    aplicar:   $(data).find('input').serializeArray()[1].value,
                };
                detalle_select.push(det);
            }
        });
    console.log(detalle_select) ;


    if(detalle_select == 0){
        alert('Seleccione una referencia')
    }else{
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "POST",
            url: "/IngresosAlmacen/addreferences",
            data: {items: detalle_select},
            success: function (data) {
                $('#modal_ref').modal('hide').data('bs.modal', null);
                console.log(data);
                $("#txt_tercero").append("<option value='"+data.tercero_id+"' selected>"+ data.tercero_codigo + " | " + data.tercero_desc + "</option>");
                $('#txt_almacen').val(data.almacen_id);
                $('#txt_almacen').trigger('change');
                $('#txt_moneda').val(data.moneda_id);
                $('#txt_moneda').trigger('change');
                $('#txt_glosa').val(data.glosa);
                $('#txt_referencia').val(data.documento);
                $('#txt_referencia_id').val(data.referencia_id);
                table.ajax.reload();
                let existencias = $('.existencias').DataTable();
                existencias.ajax.reload();
                solicitarTotales();
            },
            error: function (data) {
                mostrar_errores(data);
            }
        });
    }
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


 $('#table-references-ingreso').DataTable({
    serverSide: true,
    ajax: '/IngresosAlmacen/tabla_references',
    destroy: true,
    "paging":   false,
    "searching": false,
    "columns": [
        {
            "sName": "Index",
            "render": function (data, type, row, meta) {
                return meta.row+1; // This contains the row index
            },
            "orderable": "false",
        },
        {data: 'options.fechadoc', "className": "text-center"},
        {data: 'options.documento', "className": "text-center"},
        {data: 'options.moneda', "className": "text-center"},
        {data: 'options.glosa', "className": "text-center"},
        {data: 'price', "className": "text-center"},

    ],
    order: [[ 1, 'asc' ]],
    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});
