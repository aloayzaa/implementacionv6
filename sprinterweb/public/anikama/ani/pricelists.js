//carga metodos al inicalizar
$(document).ready(function () {

    llenarSelect('cbo_producto','/pricelist/buscar_productos');
    // llenarSelect('cbo_producto_edit','/serviceorders/buscar_servicios');

});

//
// $('#cbo_producto').on('select2:select', function (e) {
//     var data = e.params.data;
//     $("#txt_umedida").val(data.otros['umedida_codigo']);
//     $("#modal_costounidad").val(data.otros['precio1']);
//     $("#txt_umedida_id").val(data.otros['umedida_id']);
//     $(".cantidad").focus();
// });
$('#cbo_producto').on('select2:select', function (e) {
    var data = e.params.data;
    if (data.otros['umedida_id'] > 0){
        $("#un_medida").append("<option value='"+data.otros['umedida_id']+"' selected>" + data.otros['umedida_codigo'] + " | " + data.otros['umedida_descripcion'] + "</option>");
    }

});

function guardar() {
    var ruta = $("#ruta").val();
    var form = new FormData($("#frm_generales")[0]);
    $("#btn_grabar").attr("disabled", true);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "POST",
        url: ruta,
        data: form,
        dataType:"JSON",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.ruta) {
                window.location.replace(data.ruta);
                table.ajax.reload();
            } else {
                error('error', data.error, 'Error!');
            }
        },
        error: function (data) {
            mostrar_errores(data);
            $("#btn_grabar").attr("disabled", false);
        }
    });
}
function actualizar() {
    update()
}

//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(1);
}
var variable = $("#var").val();
var table = $('#listprecio').DataTable({
    serverSide: true,
    ajax: '/pricelist/listar_detalle',
    destroy: true,
    scrollX: true,
    columnDefs: [
        {
            "targets": 0,
            "visible": false,
            "searchable": false
        },
    ],
    "columns": [
        {data: 'id'},
        {data: 'suc_cod'},
        {data: 'suc_dsc'},
        {data: 'codigo'},
        {data: 'descripcion'},
        {data: 'ume_cod'},
        {data: 'precio'},
        {data: 'precio2'},
        {data: 'precio3'},
        {data: 'precio4'},
        {data: 'precio5'},
        {data: 'precio6'},
        {data: 'marca_cod'},

        {
            data: function (row) {
                return '<a id="btnEdit" class="btn"><span class="fa fa-edit fa-2x"></span></a>'
            },
            "orderable": "false",
        },
        {
            data: function (row) {
                return '<a  id="btnDelete"  class="btn"><span style="color:red" class="fa fa-trash-o fa-2x"></a>'
            },
            "orderable": "false",
        },
    ],
    order: [[1, 'asc']],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});
$('#listprecio tbody').on('click', '#btnEdit', function () {
        var data = table.row($(this).parents('tr')).data();
        console.log(data['id']);
        editar(data['id']);
    }
);

$('#listprecio tbody').on('click', '#btnDelete', function () {
        var data = table.row($(this).parents('tr')).data();
        destroy_itemm(data);
    }
);
function editar(id){
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "GET",
        url: "/" + variable + "/editar",
        data:{id:id},
        success: function (data) {
            console.log(data);
            $("#id").val(data['id']);
            $("#txt_sucursal").val(data['sucursal_id']).trigger("change");
            $("#cbo_producto").append("<option value='"+data['producto_id']+"' selected>"+data['producto_codigo']+ " | " + data['producto_descripcion'] +"</option")
            $("#txt_precio2").val(data['precio2']);
            $("#txt_precio1").val(data['precio']);
            $("#txt_precio3").val(data['precio3']);
            $("#txt_precio4").val(data['precio4']);
            $("#txt_precio5").val(data['precio5']);
            $("#txt_precio6").val(data['precio6']);
            $("#currency").val(data['moneda_id']).trigger("change");
           // $("#").val(data['umedida_id']).trigger("change")



        },
        error: function (data) {
        }
    });
}
