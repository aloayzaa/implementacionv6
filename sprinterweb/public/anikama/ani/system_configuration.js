$(document).ready(function () {
    llenarSelectubigeo('cbo_ubigeo','buscar_ubigeo');
});

function llenarSelectubigeo(select, route) {
    $("#" + select).select2({
        placeholder: "-- Seleccione una opción --",
        minimumInputLength: 2,
        language: {
            inputTooShort: function () {
                return 'Ingresa mínimo 2 caracteres';
            },
            noResults: function () {
                return "No se encontró resultados";
            }
        },
        multiple: false,
        //width: 400,
        ajax: {
            url: route,
            data: function (term) {
                return term;
            },
            processResults: function (data) {
                //    $("#"+select).empty();
                return {
                    results: $.map(data, function (obj) {
                        return {
                            id: obj.codigo,
                            text: obj.codigo + ' | ' + obj.descripcion,
                            otros: obj
                        };
                    })
                };
            }
        }
    });
}

$('#btnGuardarConfiguracionmaestros').click(function(){
    if($("#frm_conf_maestros").valid()){
        registrar_maestros_conf();
    }
});

$('#btnGuardarConfiguraciongenerales').click(function(){
    if($("#frm_conf_generales").valid()){
        registrar_generales_conf();
    }
});

$('#btnGuardarConfiguracioncompras').click(function(){
    if($("#frm_conf_compras").valid()){
        registrar_compras_conf();
    }
});

$('#btnGuardarConfiguracionventas').click(function(){
    if($("#frm_conf_ventas").valid()){
        registrar_ventas_conf();
    }
});

$('#btnGuardarConfiguracionlogistica').click(function(){
    if($("#frm_conf_logistica").valid()){
        registrar_logistica_conf();
    }
});

$('#btnGuardarConfiguraciontesoreria').click(function(){
    if($("#frm_conf_tesoreria").valid()){
        registrar_tesoreria_conf();
    }
});

$('#btnGuardarConfiguracioncontabilidad').click(function(){
    if($("#frm_conf_contabilidad").valid()){
        registrar_contabilidad_conf();
    }
});

$('#btnGuardarConfiguracionactivos').click(function(){
    if($("#frm_conf_activos").valid()){
        registrar_activos_conf();
    }
});

$('#btnGuardarConfiguracionplanilla').click(function(){
    if($("#frm_conf_planillas").valid()){
        registrar_planilla_conf();
    }
});

$('#btnGuardarConfiguracioncpe').click(function(){
    if($("#frm_conf_cpe").valid()){
        registrar_cpe_conf();
    }
});

$('#btnGuardarConfiguracionproduccion').click(function(){
    if($("#frm_conf_produccion").valid()){
        registrar_produccion_conf();
    }
});


function registrar_maestros_conf()
{
    var variable = $("#var").val();
    var frm_conf_maestros=$("#frm_conf_maestros");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_maestros',
        data: frm_conf_maestros.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function registrar_generales_conf()
{
    var variable = $("#var").val();
    var frm_conf_generales=$("#frm_conf_generales");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_generales',
        data: frm_conf_generales.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}
function registrar_compras_conf()
{
    var variable = $("#var").val();
    var frm_conf_compras=$("#frm_conf_compras");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_compras',
        data: frm_conf_compras.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function registrar_ventas_conf()
{
    var variable = $("#var").val();
    var frm_conf_ventas=$("#frm_conf_ventas");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_ventas',
        data: frm_conf_ventas.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

function registrar_logistica_conf()
{
    var variable = $("#var").val();
    var frm_conf_logistica=$("#frm_conf_logistica");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_logistica',
        data: frm_conf_logistica.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}
function registrar_tesoreria_conf()
{
    var variable = $("#var").val();
    var frm_conf_tesoreria=$("#frm_conf_tesoreria");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_tesoreria',
        data: frm_conf_tesoreria.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}
function registrar_contabilidad_conf()
{
    var variable = $("#var").val();
    var frm_conf_contabilidad=$("#frm_conf_contabilidad");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_contable',
        data: frm_conf_contabilidad.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
function registrar_activos_conf()
{
    var variable = $("#var").val();
    var frm_conf_activos=$("#frm_conf_activos");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_activos',
        data: frm_conf_activos.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

function registrar_planilla_conf()
{
    var variable = $("#var").val();
    var frm_conf_planillas=$("#frm_conf_planillas");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_planilla',
        data: frm_conf_planillas.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

function registrar_cpe_conf()
{
    var variable = $("#var").val();
    var frm_conf_cpe=$("#frm_conf_cpe");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_cpe',
        data: frm_conf_cpe.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}

function registrar_produccion_conf()
{
    var variable = $("#var").val();
    var frm_conf_produccion=$("#frm_conf_produccion");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + 'procesar_produccion',
        data: frm_conf_produccion.serialize(),
        success: function (data) {
            if(data.mensaje){
                success('success', data.mensaje, 'Información');
            }
        },
        beforeSend:function(){
            abre_loading();
        },
        complete:function(){
            cierra_loading();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
function recargar()
{
    location.reload(true);
}


