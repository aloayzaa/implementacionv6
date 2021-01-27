$(document).ready(function () {
    validarFecha();
    consultar_tipocambio($('.tipocambio').val());
    select2('vendedor_id','buscar_vendedores');
});
$("#btn_nuevo_venta").click(function () {
    //habilitar
    $(".nuevo_venta2").css('display', 'block');
    $(".editar2").css('display', 'block');
    $(".nueva_linea2").css('display', 'block');

    $(".nuevo_venta").css('display', 'none');
    $(".editar").css('display', 'none');
    $(".nueva_linea").css('display', 'none');

    $('.identificador').removeClass('ocultar');
});

$('#btn_nueva_linea').click(function () {
    limpia_modal();
    let mensaje = validarFecha();
    let puntoventa_id = $("#salesPoint").val();
    if(mensaje){
        if (puntoventa_id === ''){
            success('success', 'No se ha seleccionado el punto de venta', 'Información');
        }else{
            var ruta_list_products = $("#ruta_list_products").val();
            listProductPointOfSale.init(ruta_list_products);
            $('#modal_add').modal('show');
        }
    }
});

function limpia_modal() {
    $("#producto_codigo").val('');
    $("#producto_descripcion").val('');
    $("#producto_presentacion").val('');
    $("#actualizar_detalle").val('');
}

function valida_serie(id) {
    var rutas = $("#var").val();
    var variable = $(".serie"+id).val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + rutas + "/valida_serie/"+variable,
        success: function (data) {
            if (data == ''){
                $(".serie"+id).val('');
            }
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('#listDetailPointOfSale tbody').on('click', '#btn_eliminar_detalle_venta', function () {
    tabla_datos = $('#listDetailPointOfSale').DataTable();
    var data = tabla_datos.row($(this).parents('tr')).data();
    destroy_item(data,tabla_datos,'eliminar_datos');
});
function destroy_item(data,tabla,ruta){
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: {rowId: data.rowId,item: data.options.item},
        success: function (data) {
            $("table").DataTable().ajax.reload();
            success('success', 'Detalle eliminado correctamente!', 'Información');
        },
        error: function (data) {
        }
    });
}

function bucar_producto(){
    let producto_codigo = $("#producto_codigo").val();
    let producto_descripcion = $("#producto_descripcion").val();
    let producto_presentacion = $("#producto_presentacion").val();
    let punto_venta = $("#txt_descripcionpv").val();
    let fecha_proceso = $("#txt_fecha").val();
    let tipo_venta = $("#txt_descripciontv").val();
    if (valida_busqueda_producto(producto_codigo, producto_descripcion, producto_presentacion) === false){return false;}

    listProductPointOfSale.init('/pointofsale/buscar_producto/'+ '?producto_codigo=' + producto_codigo + '&producto_descripcion=' + producto_descripcion + '&producto_presentacion=' + producto_presentacion + "&punto_venta=" + punto_venta + "&fecha_proceso=" + fecha_proceso + "&tipo_venta=" + tipo_venta);
}

function valida_busqueda_producto(codigo, descripcion, presentacion){
    let busqueda = codigo.trim() + descripcion.trim() + presentacion.trim(); // A menos un criterio de busqueda
    if (busqueda === ''){ error('error', 'Ingrese un criterio de busqueda', 'Error'); return false; }
}

$("#cobrar").click(function () {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/validar_detalle_puntoventa",
        data:{},
        success: function (data) {
            if(data.valida == true){
                warning('warning', data.mensaje, 'Información');
            }else{
                cobranza();
            }
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
});

function cobranza(){
    //document.onkeyup = KeyCheck;
    let tmoneda = $("#txt_descripcionmon").val();
    let condicionpago_id = $('select[id=txt_descripcioncp]').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/cobrar",
        data:{tmoneda: tmoneda, condicionpago_id: condicionpago_id},
        success: function (data) {
            if (data.success){
                confirmar(data.success);
            }else{
                pasar_tabs();
                ocultar();
            }
            $("#txtneto").val(parseFloat(data.txtneto).toFixed(2));
            $("table").DataTable().ajax.reload();
            totalizar();
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

function totalizar(){
    let txt_base = $("#txt_base").val();
    let txt_inafecto = $("#txt_inafecto").val();
    let txt_impuesto = $("#txt_impuesto").val();
    let txt_impuesto2 = $("#txt_impuesto2").val();
    let txt_descuento = $("#txt_descuento").val();
    let txtneto = $("#txtneto").val();
    let tcambio = $("#tcambio").val();
    let tmoneda = $("#txt_descripcionmon").val();
}

function ocultar(){
    $("#cobrar").addClass('ocultar');
    $("#ticket").addClass('ocultar');
}

function confirmar(data){
    swal.fire({
        title: data,
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "SI",
        cancelButtonText: "NO",
    }).then(function (result) {
        if(result.value){
            pasar_tabs();
            ocultar();
        }        
    });
}

function pasar_tabs(){
    $("#cbo_impuesto").addClass('ocultar');
    $("#cbo_impuesto2").addClass('ocultar');
    $('.cobrarpv').removeClass('ocultar');
    $('.detallepva').removeClass('active');
    $('.detallepv').addClass('ocultar');
    $('.cobrarpva').addClass('active');
}

$('#code').on( 'keydown',function(e) {
    let codigo = $("#code").val();
    if( e.keyCode === 9 || e.keyCode === 13) {
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "POST",
            url: "/" + $("#var").val() + "/valida_codigo",
            dataType: "JSON",
            data: {codigo: codigo, id: 0},
            success: function (data) {
                $("#ubigeo_id").val('');
                $('#direccion_id').html('');
                $("#cliente").html('<option value="'+data.customer.id+'">'+data.customer.descripcion+'</option>');
                $('#direccion_id').append('<option value="'+data.customer.via_nombre+'">'+data.customer.via_nombre+'</option>');
                $("#documento").val(codigo);
                $("#ubigeo_id").val(data.customer.ubigeo_id);
                
                for (var i=0; i < data.direcciones.length; i++){
                    var datos = data.direcciones[i];
                    $('#direccion_id').append('<option value="'+datos.via_nombre+'">'+datos.via_nombre+'</option>');
                }
                $("#tipopersona").val(data.customer.tipopersona);
                if(data.customer.tipopersona == "2"){
                    $("#cbotipodoc").val('4');
                }
                if(data.customer.tipopersona == '1'){
                    $("#cbotipodoc").val('2');
                }
                cbotipodoc();
            },
            beforeSend:function(){
                abre_loading();
            },
            complete:function(){
                cierra_loading();
            }
        });
    }
});

function cbotipodoc(){
    let puntoventa = $('select[id=txt_descripcionpv]').val();
    let cbotipodoc = $('select[id=cbotipodoc]').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/documentocom",
        data:{puntoventa: puntoventa, cbotipodoc: cbotipodoc},
        success: function (data) {
            if (data.success){
                success('success', data.success, 'Información');
                $("#txtserie").val('');
            }else{
                $("#txtserie").val(data);
            }
            serie_valid();
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

function serie_valid() {
    let puntoventa = $('select[id=txt_descripcionpv]').val();
    let cbotipodoc = $('select[id=cbotipodoc]').val();
    let serie = $('#txtserie').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/serie",
        data:{puntoventa: puntoventa, cbotipodoc: cbotipodoc, serie: serie},
        success: function (data) {
            if (data.success){
                success('success', data.success, 'Información');
                $("#txtserie").val('');
                $("#txtnumero").val('');

            }else{
                $("#txtserie").val(data.serie);
                $("#txtnumero").val(data.numero);
            }
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}


$("#txtnum1").blur(function(){
    let txtnum1 = $("#txtnum1").val();
    let txtneto = $("#txtneto").val();
    let txtnum2 = 0;
    if(txtnum1 > 0){
        txtnum2 = txtnum1 - txtneto;
    }
    $("#txtnum1").val(parseFloat(txtnum1).toFixed(2));
    $("#txtnum2").val(parseFloat(txtnum2).toFixed(2));
});

$("#cbo_impuesto").change(function(){
    //let tventa = $('select[id=txt_descripciontv]').val();
    //let cboimpuesto = $('select[id=cbo_impuesto]').val();
    //let total = $("#txt_total").val();
    //let base = $("#txt_base").val();

    let tipo_venta = $("#txt_descripciontv").val();
    let igv = $("#cbo_impuesto").val();
    let tipo_afectacion_igv = $("#cnttipoafectaigv").val();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/impuesto",
        data:{tipo_venta: tipo_venta, igv: igv, tipo_afectacion_igv: tipo_afectacion_igv},
        success: function (data) {
            //$("#txt_base").val(parseFloat(data.txtbase).toFixed(2));
            //$("#txt_impuesto").val(parseFloat(data.txtimpuesto).toFixed(2));
            //sumar(tventa, cboimpuesto);
            resultado_sumar(data);
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
});

$("#cbo_impuesto2").change(function(){
    let base = $('#txt_base').val();
    let inafecto = $('#txt_inafecto').val();
    let impuesto = $('#txt_impuesto').val();
    let descuento = $('#txt_descuento').val();
    let isc = $('#txt_isc').val();
    let cbo_impuesto2 = $('select[id=cbo_impuesto2]').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/impuesto2",
        data:{cbo_impuesto2: cbo_impuesto2, base: base, inafecto: inafecto, impuesto: impuesto, descuento: descuento, isc: isc},
        success: function (data) {
            $("#txt_impuesto2").val(parseFloat(data.txtimpuesto2).toFixed(2));
            $("#txt_total").val(parseFloat(data.txttotal).toFixed(2));
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
});

$("#txt_descripcioncp").change(function(){
    let condicionpago_id = $('select[id=txt_descripcioncp]').val();
    let fecha = $('#txt_fecha').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/vence",
        data:{condicionpago_id: condicionpago_id, fecha: fecha},
        success: function (data) {
            $("#txt_vence").val(data);
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
});


function guardar() {
    let razonzocial = $("#cliente option:selected").text();
    //let direccion = $("#direccion option:selected").text();
    $("#razonsocial").val(razonzocial);
    //$("#direccion").val(direccion);
    store()
}

 function detalle_cobro(){
    let forma_pago = $('select[id=forma_pago]').val();
    let resto = $("#valor_resto").val();
    if(forma_pago != ""){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('#_token').val()},
            type: "get",
            url: "/" + grupo_ruta + "/detalle_cobro",
            data:{forma_pago: forma_pago, resto: resto},
            success: function (data) {
                $("#forma_pago").modal('hide');
                $('#listCobranzaPointOfsale').DataTable().ajax.reload();
            }, error: function (data) {
                mostrar_errores(data);
            }
        });
    }else{
        warning('warning', 'Seleccione una forma de pago', 'Información');
    }
 }

$("#tipo_forma_pago").click(function(){
    $('#forma_pago').modal('show');
})

$('#remision1').blur(function(){
    let remision1 = $(this).val();
    if (remision1.length > 1 && remision1.length < 4) {
        remi = ceros_izquierda(4, remision1);
        $(this).val(remi);
    }
})

$("#cbotipodoc").change(function(){
    let combo = $(this).val();
    let tipopersona = $('#tipopersona').val();
    if(tipopersona == 2 && combo != "4"){
        warning('warning', 'Documento de Identidad no válido', 'Información');        
        $("#cbotipodoc").val('4');
    }
    cbotipodoc();

})

function importe(id){
    let grupo = $("#var").val();
    let data = obtener_checks_seleccionados_cobranza('listCobranzaPointOfsale');
    let neto = $("#txtneto").val();
    //let importe = $(".importe_cobranza"+id).val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "GET",
        url: "/" + grupo + "/forma_pago_cobranza",
        data: "data="+  JSON.stringify(data)+"&neto="+neto+"&importe="+importe,
        success: function (data) {
            //console.log(data);
            if(data.valor){
                $('#forma_pago').modal('show');
                $("#valor_resto").val(data.valor);
            }
            $('#listCobranzaPointOfsale').DataTable().ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function obtener_checks_seleccionados_cobranza(id) {
    var tabla = $('#' + id).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        var data = this.node();
        //if ($(data).find('input').prop('checked')) {
            det = {
                'ids': $(data).find('input').serializeArray()[0],
                'ref': $(data).find('input').serializeArray()[1],
                'importe': $(data).find('input').serializeArray()[2],
            };
            detalle_select.push(det);
        //}
    });
    return detalle_select;
}

function existe_stock(id) {
    check_detalle(id)
    let grupo = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo + "/existe_stock",
        data:{id: id},
        success: function (data) { console.log(data);},
        error: function (data) {
            mostrar_errores(data);
            $("#cantidad_modal" + id).val(0);
            $("#detalle_modal" + id).prop('checked', false);
            check_detalle(id);
        }
    });
}

function check_detalle(id){
    let estado = !$("#detalle_modal"+id).prop('checked'); 
    $("#cantidad_modal"+id).prop('disabled', estado);
    $("#precio_modal"+id).prop('disabled', estado);
    $("#descuento_modal"+id).prop('disabled', estado);
    $("#serie_modal"+id).prop('disabled', estado);
    return $("#detalle_modal"+id).prop('checked');
}

function serie_modal(data){ // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    switch (parseInt(data.options.pideserie)) {
        case 0:
            return '<input type="text" id="serie_modal'+data.options.id+'" name="serie_modal'+data.options.id+'" value="' + data.options.serie + '" class="form-control  text-right width-75 serie" readonly disabled>';
            break;
        case 1:
            return '<input type="text" id="serie_modal'+data.options.id+'" name="serie_modal'+data.options.id+'" value="' + data.options.serie + '" class="form-control text-right width-75" onblur="serie_modal2(' + data.id + ')" disabled>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function serie_modal2(id = '', item = ''){ // se necesita ambos para obtener la serie
    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle(id) === false ) { return false; }
    let serie = $("#serie" + origen).val(); // input
    let punto_venta = $("#txt_descripcionpv").val();
    let fecha_proceso = $("#txt_fecha").val();
    let producto_id = id;
    let tipo_venta = $("#txt_descripciontv").val();
    let variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/valida_serie/?serie=" + serie + "&punto_venta=" + punto_venta + "&fecha_proceso=" + fecha_proceso + "&producto_id=" + producto_id + "&tipo_venta=" + tipo_venta,
        success: function (data) {
            success('success', 'La serie es correcta!', 'Información');
        }, error: function (data) {
            mostrar_errores(data);
            $("#serie" + origen).val('');
        }
    });    
}

function cantidad_modal(data){
    switch (data.options.pideserie) {
        case 0:
            return '<input type="number" id="cantidad_modal'+ data.options.id +'" name="cantidad_modal'+  data.options.id +'" value="'+ data.options.cantidad +'" class="form-control text-right width-75" value="" onblur="cantidad_modal2(' + data.options.id +')" disabled>';
            break;
        case "1":
            return '<input type="number" id="cantidad_modal'+ data.options.id +'" name="cantidad_modal'+  data.options.id +'" class="form-control  text-right width-75" value="1" readonly onblur="cantidad_modal2(' + data.options.id +')" disabled>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function cantidad_modal2(id = '', item = ''){
    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle(id) === false ) { return false; }
    let cantidad = $("#cantidad"+origen).val(); // input
    let tcambio = $("#tcambio").val();
    let moneda = $("#txt_descripcionmon").val();
    let tipo_doc = $("#cbotipodoc").val();
    let descuento = $("#descuento"+origen).val(); // input
    //let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#txt_descripciontv").val();
    let igv = $("#cbo_impuesto").val();
    let precio = $("#precio"+origen).val(); // preciolista - input
    let serie = $("#serie"+origen).val(); // input
    let lote = '';
    let tipo_afectacion_igv = $("#cnttipoafectaigv").val();
    let variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_cantidad",
        data:{cantidad: cantidad, tcambio: tcambio, moneda: moneda, tipo_doc: tipo_doc, descuento: descuento, tipo_venta: tipo_venta, igv: igv, precio: precio, lote: lote, tipo_afectacion_igv: tipo_afectacion_igv, serie: serie, id: id, item: item},
        success: function (data) {
            console.log(data)
            if (data.cantidad) {
                $("#cantidad"+origen).val(data.cantidad);
            }
            resultado_sumar(data);
        }, error: function (data) {
            mostrar_errores(data);
            $("#cantidad"+origen).val(data.responseJSON.cantidad);
        }
    });
}

function precio_modal(data) {
    switch (data.options.editar_precio) {
        case "0":
        case "1":
            return '<input type="number" id="precio_modal' + data.options.id + '" name="precio_modal' + data.options.id + '" class="form-control text-right width-75" value="' + data.options.preciolista + '" onblur="precio_modal2(' + data.options.id + ')" disabled>';
            break;
        case "2":
            return '<input type="number" id="precio_modal' + data.options.id + '" name="precio_modal' + data.options.id + '" class="form-control text-right width-75" value="' + data.options.preciolista + '" readonly onblur="precio_modal2(' + data.options.id + ')" disabled>';
            break;
    }
}

function precio_modal2(id, item = ''){
    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle(id) === false ) { return false; }
    let cantidad = $("#cantidad"+origen).val(); // input
    let tcambio = $("#tcambio").val();
    let moneda = $("#txt_descripcionmon").val();
    let tipo_doc = $("#cbotipodoc").val();
    let descuento = $("#descuento"+origen).val(); // input
    //let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#txt_descripciontv").val();
    let igv = $("#cbo_impuesto").val();
    let precio = $("#precio"+origen).val(); // preciolista - input
    let serie = $("#serie"+origen).val(); // input
    let lote = '';
    let tipo_afectacion_igv = $("#cnttipoafectaigv").val();
    let variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_precio",
        data:{cantidad: cantidad, tcambio: tcambio, moneda: moneda, tipo_doc: tipo_doc, descuento: descuento, tipo_venta: tipo_venta, igv: igv, precio: precio, lote: lote, tipo_afectacion_igv: tipo_afectacion_igv, serie: serie, id: id, item: item},
        success: function (data) {
            console.log(data)
            if (data.cantidad) {
                $("#cantidad"+origen).val(data.cantidad);
            }
            resultado_sumar(data);
        }, error: function (data) {
            mostrar_errores(data);
            $("#precio"+origen).val(data.responseJSON.precio)
        }
    });
}

function descuento_modal(data) {
    switch (parseInt(data.options.pdescuento)) {
        case 1:
            return '<input type="number" id="descuento_modal'+data.options.id+'" name="descuento_modal'+data.options.id+'" class="form-control width-75" value="" onblur="descuento_modal2('+data.options.id+')" disabled>';
        case 0:
            return '<input type="number" id="descuento_modal'+data.options.id+'" name="descuento_modal'+data.options.id+'" class="form-control width-75" value="" onblur="descuento_modal2('+data.options.id+')" disabled readonly>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function descuento_modal2(id = '', item = ''){
    let origen = ( item !== '' ) ? item  : "_modal"+id;
    if ( check_detalle(id) === false ) { return false; }
    let cantidad = $("#cantidad"+origen).val(); // input
    let tcambio = $("#tcambio").val();
    let moneda = $("#txt_descripcionmon").val();
    let tipo_doc = $("#cbotipodoc").val();
    let descuento = $("#descuento"+origen).val(); // input
    //let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#txt_descripciontv").val();
    let igv = $("#cbo_impuesto").val();
    let precio = $("#precio"+origen).val(); // preciolista - input
    let serie = $("#serie"+origen).val(); // input
    let lote = '';
    let tipo_afectacion_igv = $("#cnttipoafectaigv").val();
    let variable = $("#var").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/validar_descuento",
        data:{cantidad: cantidad, tcambio: tcambio, moneda: moneda, tipo_doc: tipo_doc, descuento: descuento, tipo_venta: tipo_venta, igv: igv, precio: precio, lote: lote, tipo_afectacion_igv: tipo_afectacion_igv, serie: serie, id: id, item: item},
        success: function (data) {
            console.log(data)
            if (data.cantidad) {
                $("#cantidad"+origen).val(data.cantidad);
            }            
            resultado_sumar(data);
        }, error: function (data) {
            mostrar_errores(data);
            $("#descuento"+origen).val(data.responseJSON.descuento)
        }
    });    
}

function agregar_producto(){
    let data = obtener_checks_seleccionados('listProductPointOfSale');

    let moneda = $("#txt_descripcionmon").val();
    let tipo_doc = $("#cbotipodoc").val();
    //let crear_kardex = $("#crear_kardex").prop('checked');
    let tipo_venta = $("#txt_descripciontv").val(); // validar la serie // sumar
    let igv = $("#cbo_impuesto").val()
    let lote = '';
    let tipo_afectacion_igv = $("#cnttipoafectaigv").val();
    let variable = $("#var").val();
    let punto_venta = $("#txt_descripcionpv").val(); // validar la serie
    //let importe = $("#importe").val();
    let tcambio = $("#tcambio").val();
    let fecha_proceso = $("#fecha_proceso").val(); // validar serie
    //let variable = $("#var").val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('#_token').val() },
        type: "POST",
        url: "/" + variable + "/agregar_productos",
        data: {data: JSON.stringify(data), tipo_venta: tipo_venta, igv: igv, tipo_afectacion_igv: tipo_afectacion_igv, moneda: moneda, tipo_doc: tipo_doc, lote: lote, tcambio: tcambio, punto_venta: punto_venta, fecha_proceso: fecha_proceso},
        success: function (data) {
            $("#modal_add").modal("hide");
            resultado_sumar(data);
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function obtener_checks_seleccionados(id) {
    let tabla = $('#' + id).DataTable();
    let detalle_select = [];
    let det = {};
    tabla.rows().every(function () {
        let data = this.node();
        if ($(data).find('input').prop('checked')) {
            det = {
                'ids': $(data).find('input').serializeArray()[0],
                'series': $(data).find('input').serializeArray()[1],
                'cantidades': $(data).find('input').serializeArray()[2],
                'precios': $(data).find('input').serializeArray()[3],
                'dsctos': $(data).find('input').serializeArray()[4],
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}

function resultado_sumar(data){

    if ( typeof data.ttotal !== 'undefined' ) {

        $('#listDetailPointOfSale').DataTable().ajax.reload(); // actualizar docxpagar_detalle
        $("#txt_base").val(parseFloat(data.tbase).toFixed(2));
        $("#txt_inafecto").val(parseFloat(data.tinafecto).toFixed(2));
        $("#txt_descuento").val(parseFloat(data.tdescuento).toFixed(2));
        $("#txt_isc").val(parseFloat(data.tisc_icbper).toFixed(2));
        $("#txt_impuesto").val(parseFloat(data.igv).toFixed(2));
        $("#txt_gratuito").val(parseFloat(data.tgratuito).toFixed(2));
        $("#txt_total").val(parseFloat(data.ttotal).toFixed(2));
        //$("#importe").val(data.importe);

    }
    
}

/*$("#cbo_impuesto").change(function(){ // cboimpuesto valid
    let igv  = $("#cbo_impuesto option:selected");
    let tipocalculo = igv.data('tipocalculo');
    let valor = igv.data('valor');
    let total = $("#txt_total").val();
    let base = $("#txt_base").val();
    if (tipocalculo === 'P'){
        valor = valor / 100;
        impuesto = base * valor;
    } else {
        impuesto = valor;
    }
    total = total - impuesto;
    $("#txt_impuesto").val(parseFloat(impuesto).toFixed(2));
    $("#txt_base").val(parseFloat(total).toFixed(2));
});*/

/*$("#cbo_impuesto2").change(function(){ // cboimpuesto2 valid
    let percepcion  = $("#cbo_impuesto2 option:selected");
    let tipocalculo = percepcion.data('tipocalculo');
    let valor = percepcion.data('valor');
    let base = $("#txt_base").val();
    let inafecto = $("#txt_inafecto").val();
    let impuesto = $("#txt_impuesto").val();
    let descuento = $("#txt_descuento").val();
    let isc_icbper = $("#txt_isc").val();
    if (tipocalculo === 'P'){
        //console.log((parseFloat(base).toFixed(2) + parseFloat(inafecto).toFixed(2) + parseFloat(impuesto).toFixed(2) + parseFloat(descuento).toFixed(2)));
        valor = base + inafecto + impuesto + descuento * ( parseFloat(valor).toFixed(2) / 100);
    }
    $("#txt_impuesto2").val(parseFloat(valor).toFixed(2));
    let total = base + inafecto + descuento + impuesto + isc_icbper;
    $("#txt_total").val(parseFloat(total).toFixed(2));
});*/


function serie_pointofsale(data){  // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    console.log(data);
    switch (parseInt(data.options.pideserie)) {
        case 0:
            return '<input type="text" id="serie'+data.id+'" name="serie'+data.id+'" value="' + data.options.serie + '" class="form-control text-right width-75" readonly>';
            break;
        case 1:
            return '<input type="text" id="serie'+data.id+'" name="serie'+data.id+'" value="' + data.options.serie + '" class="form-control text-right width-75" onblur="serie_modal2(' + data.options.id + ', ' +  data.id + ')">';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }    
}

function cantidad_pointofsale(data){
    switch (data.options.pideserie) {
        case 0:
            return '<input type="number" id="cantidad'+data.id+'" name="cantidad'+data.id+'" value="'+ data.options.cantidad +'" class="form-control text-right width-75" value="" onblur="cantidad_modal2(' + data.options.id +', ' + data.id + ')">';
            break;
        case "1":
            return '<input type="number" id="cantidad'+data.id+'" name="cantidad'+data.id+'" class="form-control text-right width-75" value="1" readonly onblur="cantidad_modal2(' + data.options.id +', ' + data.id + ')">';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function precio_pointofsale(data){
    switch (data.options.editar_precio) {
        case "0":
        case "1":
            return '<input type="number" id="precio'+data.id+'" name="precio'+data.id+'" class="form-control text-right width-75" value="' + data.options.preciolista + '" onblur="precio_modal2(' + data.options.id + ', ' + data.id + ')">';
            break;
        case "2":
            return '<input type="number" id="precio'+data.id+'" name="precio'+data.id+'" class="form-control text-right width-75" value="' + data.options.preciolista + '" readonly onblur="precio_modal2(' + data.options.id + ', ' + data.id +')">';
            break;
    }
}


function descuento_pointofsale(data) {
    switch (parseInt(data.options.pdescuento)) {
        case 1:
            return '<input type="number" id="descuento'+data.id+'" name="descuento'+data.id+'" class="form-control width-75" value="' + data.options.descuento + '" onblur="descuento_modal2(' + data.options.id + ', ' + data.id + ')">';
            break;
        case 0:
            return '<input type="number" id="descuento'+data.id+'" name="descuento'+data.id+'" class="form-control width-75" value="" onblur="descuento_modal2(' + data.options.id + ', ' + data.id + ')" readonly>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}