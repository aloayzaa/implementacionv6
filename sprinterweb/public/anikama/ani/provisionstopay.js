
$(document).ready(function () {
    validarFecha();
    validarfechadoc();
    llenarSelect('txt_tercero', "/customers/buscar_tercero");
    llenarSelect('referencia_tercero', "/customers/buscar_tercero");
    llenarSelectCuentas('add_cuenta_id', "/accountingplans/pcgs");
    llenarSelectCuentas('edit_cuenta_id', "/accountingplans/pcgs");


    formapago = $('#txt_formapago').val();
    if( formapago != null){
        getbanco(formapago);
    }

    if($('#formapago_id').val() != 0){
        $('#div-formapago *').prop('disabled',true);
        $('#div-detraccion .noedit').prop('disabled',true);
    }
});

var table = $('#table-detail-provision').DataTable({
    serverSide: true,
    ajax: '/ProvisionesPorPagar/listar_carrito',
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
        {data: 'options.codigo_ref', "className": "text-center"},
        {data: 'options.cuenta_codigo',
            "render": function ( data, type, full, meta ) {
                return `<a onclick="cuenta(${full.options.cuenta_id})">${data == null ? '' : data}</a>`;
            },
            "className": "text-center"},
        {data: 'options.cuenta_desc', "className": "text-center"},
        {data: 'options.op_codigo', "className": "text-center"},
        {data: 'options.CCosto_codigo', "className": "text-center"},
        {data: 'options.CCosto_desc', "className": "text-center"},
        {data: 'options.importe', "className": "text-center"},///
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
            .column( 8 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );


        // Update footer
        $( api.column( 4 ).footer() ).html(
            totaldoc.toFixed(2)
    );

    },
    order: [[1, 'asc']],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});

$('#table-detail-provision tbody').on( 'click', '#btnEdit', function () {
        var data = table.row( $(this).parents('tr') ).data();
        editar(data);
    }
);

$('#table-detail-provision tbody').on( 'click', '#btnDelete', function () {
        var data = table.row( $(this).parents('tr') ).data();
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

$('#span-orden').click(function () {
    span = $('#txt_ordencompra_id').val();
    orden(span);
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
    $('#modal_referencia').modal('show');

    $("#referencia_tercero").append("<option value='"+ $('#txt_tercero option:selected').val() +"' selected>"+ $('#txt_tercero option:selected').text()  + "</option>");
    mostrarReferencias('limpiar');
}

function gettcambio(){
    return $.ajax({
        type: "GET",
        url: "/exchangerate/consultar/"+$('#txt_fecha').val(),
    });
}

function limpiar_selector(name){
    $("#"+name).append("<option value = 0 selected>"+ '--Seleccione una opción--' + "</option>");
}


function add_orden(id) {
    $('#modal_ordencompra').modal('show');

    fechahasta = $('#txt_fecha').val();
    console.log(fechahasta);
    mostrarDetalleOrdenCompra(id, 'vaciar');
    mostrarOrdenesCompra(0, fechahasta);
}

function editar(data){
console.log(data)
    if(data.options.esnuevo == 1){
        $('#modal_edit_cuenta').modal('show');

        $('#modal_edit_row_id').val(data.rowId);
        $("#edit_cuenta_id").append("<option value='"+data.options.cuenta_id+"' selected>"+ data.options.cuenta_codigo + " | " + data.options.cuenta_desc + "</option>");
        $('#modal_edit_centrocosto_id').val(data.options.CCosto_id);
        $('#modal_edit_centrocosto_id').trigger('change');
        $('#modal_edit_op').val(data.options.op);
        $('#modal_edit_importe').val(data.options.importe);
    }else{
        warning('warning', 'Los detalles provienen de una orden de compra', 'Advertencia')
    }

}

function update_item() {

    form = $('#form-edit-item').serialize(); //add-item

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#token').val(),
        },
        type: "POST",
        url:"/ProvisionesPorPagar/update_carrito",
        data: form,
        success: function (data) {
            console.log(data);
            $('#modal_edit_cuenta').modal('hide');
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
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url:"/ProvisionesPorPagar/eliminar_item",
        data: {rowId: data.rowId, item: data.options.item},
        success: function () {
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}


function mostrarReferencias(accion = null) {

    tercero = $('#referencia_tercero').val();
    fechaproceso = $('#txt_fecha').val();

    console.log(tercero)

    var tablita = $('#list-references').DataTable({
        serverSide: true,
        ajax: '/ProvisionesPorPagar/references/'+tercero+'?fechaproceso=' +fechaproceso+'&accion=' +accion,
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

function mostrarOrdenesCompra(id, fechaproceso) {
    var tablaorden = $('#ref_ordencompra').DataTable({
        serverSide: true,
        ajax: '/ProvisionesPorPagar/ordenescompra/'+id+'?fechahasta=' +fechaproceso,
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


$('#ref_ordencompra tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
        mostrarDetalleOrdenCompra(id, 'vaciar');
    }
    else {
        $('#ref_ordencompra tr.selected').removeClass('selected');
        $(this).addClass('selected');
        id =  $('#ref_ordencompra tr.selected').attr("id");
        console.log(id)
        mostrarDetalleOrdenCompra(id, 'agregar');
    }
} );


function  mostrarDetalleOrdenCompra(id, tipo){
    var orden_detalles = $('#ref_ordencompradetail').DataTable({
        serverSide: true,
        ajax: '/ProvisionesPorPagar/ordendetail/'+id+'?accion=' +tipo,
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
                    return '<input type="checkbox" name="iditem" value="'+row.options.id +"|"+ row.name+'">';
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

};
$('#txt_fecha').change(function(e) {
    validarfechadoc();
});

function validarfechadoc(){
    $('#txt_fechadoc').attr(
        "max", $('#txt_fecha').val(),
    );
}

$('#txt_fechadoc').change(function(e) {
    calcular_condicionpago();
});

$('#txt_condicionpago').change(function(e) {
    calcular_condicionpago();
});

function calcular_condicionpago() {
    id = $('#txt_condicionpago').val();
    fecha =  $('#txt_fechadoc').val();

    if(id !== '' && fecha !== ''){
        $.ajax({
            type: "get",
            url:"/ProvisionesPorPagar/condicionpago",
            data: {id: id , fecha: fecha},
            success: function (data) {
                $('#txt_vencimiento').val(data);
            },
        });
    }
}

function insertar_ordendecompra() {
    detalle_select =  getchecked('ref_ordencompradetail');
    if(detalle_select == 0){
        alert('Seleccione una Orden')  //diferente
    }else{
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "POST",
            url: "/ProvisionesPorPagar/addordencompra", //diferente
            data: {items: detalle_select},
            success: function (data) {
                $('#modal_ordencompra').modal('hide').data('bs.modal', null);
                console.log(data);
                $("#txt_tercero").append("<option value='"+data.tercero_id+"' selected>"+ data.tercero_codigo + " | " + data.tercero_desc + "</option>");

                $('#txt_sucursal').val(data.sucursal_id);
                $('#txt_sucursal').trigger('change');
                $('#txt_almacen').val(data.almacen_id);
                $('#txt_almacen').trigger('change');
                $('#txt_condicionpago').val(data.condicionpago_id);
                $('#txt_condicionpago').trigger('change');
                $('#txt_moneda').val(data.moneda_id);
                $('#txt_moneda').trigger('change');
                $('#txt_glosa').val(data.glosa);

                $('#txt_igv_id').val(data.impuesto_id);
                $('#txt_igv_id').trigger('change');
                $('#txt_percepcion_id').val(data.impuesto2_id);
                $('#txt_percepcion_id').trigger('change');

                $('#txt_base').val(data.base);
                $('#txt_inafecto').val(data.inafecto);
                $('#txt_igv').val(data.impuesto);
                $('#txt_percepcion').val(data.impuesto2);
                $('#txt_total').val(data.total);

                $('#txt_ordencompra').val(data.orden_codigo);
                $('#txt_ordencompra_id').val(data.orden_id);

                table.ajax.reload();

                calcular_importes();
            },
            error: function (data) {
                mostrar_errores(data);
            }
        });
    }}

function insertar_referencia(){

    detalle_select =  getchecked('list-references');

    if(detalle_select == 0 && $('#add_cuenta_id').val() == '' ){ //Chekear
        alert('Seleccione una referencia')
    }else{
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "POST",
            url: "/ProvisionesPorPagar/addreferences",
            data: {
                items: detalle_select,
                cuenta_id: $('#add_cuenta_id').val(),
                centrocosto_id: $('#add_centrocosto_id').val(),
                importe: $('#modal_importe').val(),
            },
            success: function (data) {
                $('#modal_referencia').modal('hide').data('bs.modal', null);
                console.log(data);
                table.ajax.reload();
            },
            error: function (data) {
                mostrar_errores(data);
            }
        });
    }
}

$('#btn_asiento').click(function () {
    referencia = $('#asiento').val();
    asiento(referencia);
});


$( "#txt_adquisicion" ).change(function() {
    select = $( "#txt_adquisicion" ).val()
    if(select == 4){
        $('#txt_percepcion_id').val("");
        $('#txt_percepcion_id').trigger('change');
        $('#txt_percepcion_id').attr('disabled', true);

        $('#txt_base').attr('readonly', true);
        $('#txt_base').val('0.00');

       if($('#txt_igv_id').val() != 3){
           $('#txt_igv_id').val(3);
           $('#txt_igv_id').trigger('change');
        }

    }else{
        $('#txt_percepcion_id').attr('disabled', false);
        $('#txt_base').attr('readonly', false);
        $('#txt_igv_id').val("");
        $('#txt_igv_id').trigger('change');
    }
    console.log(select);
});

$('#txt_igv_id').change(function() {
    select = $( "#txt_igv_id" ).val();
    if(select == 3){
       $('#txt_adquisicion').val(4);
       $('#txt_adquisicion').trigger('change');
    }
    calcular_importes();
});

$('#txt_percepcion_id').change(function() {
    calcular_importes();
});

$('#txt_renta_id').change(function() {
    $('#txt_adquisicion').val(4);
    $('#txt_adquisicion').trigger('change');
    calcular_importes();
});

$('#txt_base').blur(function () {
    calcular_importes();
});
$('#txt_inafecto').blur(function () {
    calcular_importes();
});

$('#check_impuesto').change(function() {
    calcular_importes();
});

$('#txt_igv').change(function() {
    base =  parseFloat($('#txt_base').val());
    inafecto =  parseFloat($('#txt_inafecto').val());
    igv =  parseFloat($('#txt_igv').val());
    perc =  parseFloat($('#txt_percepcion').val());
    renta =  parseFloat($('#txt_renta').val());
    check = $('#check_impuesto').prop('checked') ? 'on' : 'off';

    total =  base + inafecto + igv + (check == 'on' ? perc : 0) - renta;

    $('#txt_total').val(total.toFixed(2));
});


function calcular_importes(){
    base = $('#txt_base').val();
    inafecto = $('#txt_inafecto').val();

    igv = $('#txt_igv_id').val();
    percepcion = $('#txt_percepcion_id').val();
    renta = $('#txt_renta_id').val();
    check = $('#check_impuesto').prop('checked') ? 'on' : 'off';

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "POST",
        url: "/ProvisionesPorPagar/importes",
        data: {
            base: base,
            inafecto: inafecto,
            igv: igv,
            percepcion: percepcion,
            renta: renta,
            check: check,
        },
        success: function (data) {
            console.log(data);
            $('#txt_igv').val(data.igv);
            $('#txt_percepcion').val(data.percepcion);
            $('#txt_renta').val(data.renta);
            $('#txt_total').val(data.total);
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('.desbloqueable').dblclick( function () {  //solo si aún no se paga
    $(this).removeAttr("readonly")
});

$("#txt_seriedoc").change(function () {
    $('#txt_seriedoc').val(ceros_izquierda(5, $('#txt_seriedoc').val()));
    verificarDocumentoRegistrado();
});

$("#txt_numerodoc").change(function () {
    $('#txt_numerodoc').val(ceros_izquierda(8, $('#txt_numerodoc').val()));
    verificarDocumentoRegistrado();
});

function verificarDocumentoRegistrado() {

    var serie = $("#txt_seriedoc").val();
    var numero = $("#txt_numerodoc").val();
    var tercero = $("#txt_tercero").val();
    var documento = $("#txt_tipodoc").val();

    $.ajax({
        type: "GET",
        url: "/ProvisionesPorPagar/verificardocumento",
        dataType: "JSON",
        data: {tercero: tercero, serie: serie, documento: documento, origen: 'P',  numero: numero},
        success: function (data) {
            if (data.documento) {
                error('error', data.mensaje, 'Error');
            }
        }
    });
}


$("#txt_formapago").change(function () {
    formapago = $('#txt_formapago').val();
    getbanco(formapago);
    $('#importepago').val( $('#txt_total').val() - $('#total_detra').val() );
});

$('#importepago').blur(function () {
    validar_importe();
});

function validar_importe() {
    if( parseInt($('#importepago').val()) > parseInt($('#txt_total').val() )){
        $('#importepago').val($('#txt_total').val())
    }
}
function getbanco(id) {
    $.ajax({
        type: "GET",
        url: "/ProvisionesPorPagar/ctactebanco",
        dataType: "JSON",
        data: {formapago: id},
        success: function (data) {
            console.log(data)
            $('#cta_banco').append('<option selected value="' + data.ctactebanco.banco_id + '">' + data.ctactebanco.codigo + ' | ' + data.ctactebanco.descripcion + '</option>');
            $('#moneda_pago').append('<option selected value="' + data.currency.id + '">' + data.currency.codigo + ' | ' + data.currency.descripcion + '</option>');
         //   recalcular(data.currency.id);
        },
    });
}

function getporcentaje(id) {
    $.ajax({
        type: "GET",
        url: "/ProvisionesPorPagar/getporcentaje",
        dataType: "JSON",
        data: {tipodetraccion: id},
        success: function (data) {
            console.log(data)
            $('#detra_porcentaje').val(data.valor);
            $('#detra_numero').val('F-');
            getImporteDetraccion(data.valor);
        },
    });
}

function getImporteDetraccion(porcentaje) {

   total = $('#txt_total').val();
   valor_ref = $('#refvalue').val();

    $.ajax({
        type: "GET",
        url: "/ProvisionesPorPagar/getdetraccion",
        dataType: "JSON",
        data: {
            total: total,
            valor_ref: valor_ref,
            porcentaje: porcentaje,
        },
        success: function (data) {
            $('#total_detra').val(   data.toFixed(2));
        },
    });
}

$("#tipodetraccion").change(function () {
    tipo = $('#tipodetraccion').val();
    $('#detra_fecha').val( $('#txt_fechadoc').val() );
    $('#detra_fecha').attr(
        "min", $('#txt_fechadoc').val(),
    );
    getporcentaje(tipo);
});



var historial = $('#table-provision-historial').DataTable({
    serverSide: true,
    ajax: '/ProvisionesPorPagar/historial/'+ $('#id').val(),
    destroy: true,
    "paging":   false,
    "ordering": false,
    "info":     false,
    "searching": true,
    "columns": [
        {
            "sName": "Index",
            "render": function (data, type, row, meta) {
                return meta.row + 1; // This contains the row index
            },
            "orderable": "false",
            "className": "text-center",
        },
        {data: 'fechaproceso', "className": "text-center"},
        {data: 'documento', "className": "text-center"},
        {data: 'glosa', "className": "text-center"},
        {data: 'saldomn', "className": "text-center"},
        {data: 'saldome', "className": "text-center"},
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
        saldomn = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        saldome = api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );


        // Update footer
        $( api.column( 4 ).footer() ).html(
            saldomn.toFixed(2)
        );
        $( api.column( 5 ).footer() ).html(
            saldome.toFixed(2)
        );

    },
    order: [[1, 'asc']],

    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});



function guardar() {
    sub_total = $( table.column( 4 ).footer() ).html();
    base = $('#txt_base').val();
    inafecto = $('#txt_inafecto').val();

    baseint = parseFloat(base);
    inafectoint = parseFloat(inafecto);

//validar importe
    if(validarDetalles()){
        if(sub_total !== (baseint + inafectoint).toFixed(2)){
            warning('warning', 'La base no coincide con la del detalle', 'Advertencia')
        }else{
            validar_importe();
            store();
        }
    }
}
function actualizar() {

    sub_total = $( table.column( 4 ).footer() ).html();
    base = $('#txt_base').val();
    inafecto = $('#txt_inafecto').val();

    baseint = parseFloat(base);
    inafectoint = parseFloat(inafecto);

    if(validarDetalles()){
        if(sub_total !== (baseint + inafectoint).toFixed(2)){
            warning('warning', 'La base no coincide con la del detalle', 'Advertencia')
        }else{
            validar_importe();
            update();
            detalles = $('.historial').DataTable();
            detalles.ajax.reload();
        }
    }
}


function eventcheck(id, saldomn, saldome, cuenta) {
    var value = checkbox('chkItem' + id);

    detalle_select =  getchecked('list-references');

    if(detalle_select == 0){
        $("#add_cuenta_id").prop("disabled", false);
        $("#add_cuenta_id").append("<option value='"+0+"' selected disabled>"+ '--Seleccione una opción--' + "</option>");
        $("#add_centrocosto_id").prop("disabled", false);
        $("#modal_importe").prop("disabled", false);
    }

    if (value === 1) {
        console.log('jeje')
        var moneda = $("#txt_moneda").val();

        saldo = moneda === '1' ? saldomn : saldome;

        $("#add_cuenta_id").prop("disabled", true);
        $("#add_cuenta_id").empty();
        $("#add_cuenta_id").append("<option selected value ="+cuenta+" >"+ cuenta +"</option>");

        $("#modal_importe").prop("disabled", true);
        $("#modal_importe").val(parseFloat(saldo).toFixed(2));
        $("#item" + id).val(parseFloat(saldo).toFixed(2)).prop('readonly', false);

        $("#add_centrocosto_id").prop("disabled", true);

    }else{
        $("#item" + id).val("0.00").prop('readonly', true);
      //  $("#amount").val('0.00');

    }
}

function valor_item(id, saldomn, saldome) {

    var moneda = $("#txt_moneda").val();
    valor_maximo = moneda === '1' ? saldomn : saldome;

    if ($("#item" + id).val() > valor_maximo) {$("#item" + id).val(valor_maximo);}
    $("#modal_importe").val($("#item" + id).val());
}



























function ver_detalle_factura(rowid) {
    limpia_modal();
    $('#tipo_modal').val('editarModal');
    var variable = $("#var").val();
    var frmGenerales = $("#frm_generales");
    $.ajax({
        type: "POST",
        url: "/" + variable + '/ver_detalle_provision',
        dataType: "JSON",
        data: frmGenerales.serialize() + '&rowid=' + rowid,
        success: function (data) {
            if (data.estado === "ok") {
                $('#myModalDetalleProductosProvision').modal('show');
                $('#myModalDetalleProductosProvision').modal('show');
                $("#products").val(data.producto_id).change();
                $("#costcenterdt").val(data.ccosto_id).change();
                $('#quantity').val(data.cantidad);
                $("#unitprice").val(data.prec_unit);
                $("#dttotal").val(data.impt_det);
                $('#id_cart').val(data.rowid);
                $('#item').val(data.item);
                $('#parent_id').val(data.parent_id);
                $("#estado_modal").val(data.state);
            } else {
                error("error", data.estado, "Error!");
            }
        }
    });
}

