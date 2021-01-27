$(document).ready(function () {
    mostrarReferencias();
    mostrarDetalleReferencias('vaciar', 0);

});


$('#pagar').click(function () {
    getdata($('#tableSaleOrder tr.selected').attr("id"));
    $('#modal_pagar').modal('show');
    mostrarCaja();
    let fecha = '2020-02-10'; //falta dinamico
    consultar_tipocambio(fecha);
});

function getdata(id) {
console.log(id)
    $.ajax({
        type: "GET",
        url: "/FacturarPedido/getdata/"+id,
        dataType: "JSON",
        success: function (data) {
         console.log(data)
            $('#id').val(id)
            $('#txt_fecha').val(data.fecha)
            $('#txt_moneda').val(data.moneda)
            $('#txt_condicionpago').val(data.condicionpago)
            $('#txt_total').val(data.total)
            $('#txt_igv').val(data.igv)  //depende el producto tal ves
            $('#txt_valorventa').val(data.valorventa)
;        }
    });
}

function mostrarCaja() {
    $('#tabla-caja').DataTable({
        serverSide: true,
        ajax: '/FacturarPedido/caja',
        responsive: true,
        rowId: 'id',
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        "columns": [
            {data: 'pto_venta', class: "text-center",},
            {data: 'id', class: "text-center",},
            {data: 'descripcion', class: "text-center",},
            {data: 'importe', class: "text-center",},

        ],
        order: [[1, 'asc']],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}

function mostrarReferencias(){
    $('#tableSaleOrder').DataTable({
        serverSide: true,
        ajax: '/FacturarPedido/list',
        responsive: true,
        rowId: 'id',
        pageLength: 50,
        columnDefs: [
            {width: 140, targets: 5},
            {width: 140, targets: 6},
        ],
        "columns": [
            {data: 'pto_venta', class: "text-center",},
            {data: 'fecha', class: "text-center",},
            {data: 'numero', class: "text-center",},
            {data: 'codigo_tercero', class: "text-center",},
            {data: 'descripcion_tercero', class: "text-center",},
            {data: 'moneda', class: "text-center",},
            {data: 'importe', class: "text-center",},
            {data: 'tipoventa', class: "text-center",},
            {
                class: "text-center",
                data: function (row) {
                    return estado_color(row.estado_pedido);
                }
            },
            {
                className: "text-center",
                'render': function (data, type, row, meta) {
                    return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                }
            },
            {
                className: "text-center",
                'render': function (data, type, row, meta) {
                    return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
                }
            },
        ],
        order: [[1, 'asc']],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}


$('#tableSaleOrder tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
        mostrarDetalleReferencias();
        $('#pagar').prop('disabled', true)
    }
    else {
        $('#tableSaleOrder tr.selected').removeClass('selected');
        $(this).addClass('selected');
        let id =  $('#tableSaleOrder tr.selected').attr("id");
        mostrarDetalleReferencias(id);
        $('#pagar').prop('disabled', false)
    }
} );

function mostrarDetalleReferencias(id = 0) {
    var detalle_pedidos = $('#order-detail-ref').DataTable({
        serverSide: true,
        ajax: '/FacturarPedido/listar_detalle?id=' +id,
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
            style:    'multi',
            selector: 'input:checkbox'
        },
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        "columns": [
            {
                "sName": "Index",
                "render": function (data, type, row, meta) {
                    return meta.row + 1; // This contains the row index
                },
                "orderable": "false",
                "className": "text-center",
            },
            {data: 'item'},
            {data: 'codigo_producto',
                "render": function ( data, type, full, meta ) {
                    return `<a onclick="producto(${full.producto_id})">${data == null ? '' : data}</a>`;
                },
                "className": "text-center"},
            {data: 'descripcion_producto', "className": "text-center"},
            {data: 'codigo_umedida', "className": "text-center"},
            {data: 'cantidad', "className": "text-center"},
            {data: 'precio', "className": "text-center"},
            {data: 'descuento', "className": "text-center"},
            {data: 'importe', "className": "text-center"},
        ],
        order: [[ 1, 'asc' ]],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });

}


function pagar() {
    store();
}
