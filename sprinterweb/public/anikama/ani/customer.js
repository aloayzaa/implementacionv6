$(document).ready(function () {
    $( "#code" ).focus();
    var variable = $('#var').val();
    select2('cbo_ubigeo','buscar_ubigeo');
    select2('cbo_pais','buscar_pais');
    select2('cbo_condicion_pago_cliente','buscar_condicion_pago');
    select2('cbo_vendedor_cobrador','buscar_vendedor_cobrador');
    select2('cbo_ciudad','buscar_sucursal');
    select2('cbo_condicion_pago_proveedor','buscar_condicion_pago');
    select2('marca_id_tercero_marca','buscar_marca_tercero');
    select2('ubigeo_id_tercero_direccion','buscar_ubigeo');
});

function tipo_persona(data){

    $("#txt_codigo_cliente_proveedor").val($('#code').val());
    $("#txt_codigo_otros_datos").val($('#code').val());
    limpiar_inputs_comun();
    success('success', 'Consulta realizada con éxito!', 'Información');
    
    var persona = data.persona;

    $("#cambiar_tipo").empty();
    $("#cbo_tipo").val(data.tipo_persona).trigger('change');
    $("#cbo_documento").val(data.documentoide_id).trigger('change');

    if (data.tipo_persona === 1){
        llenar_juridica(data,persona);
    }else if(data.tipo_persona === 2){
        llenar_persona_natural(data,persona);
    }else if(data.tipo_persona === 0){
        llenar_persona_otro();
    }

}
function llenar_persona_natural(data,persona){
    limpiar_inputs_natural();

    codigo = $("#code").val();

    if (codigo.length == 11){
        $("#txt_razonsocial_cliente_proveedor").val(persona['emp_descripcion']);
        $("#txt_razonsocial_otros_datos").val(persona['emp_descripcion']);
        var apellidos = persona['emp_descripcion'].split(' ',2);
        var apellido_paterno = apellidos[0];
        var apellido_materno = apellidos[1];
        var nombres = persona['emp_descripcion'].split(' ').slice(2).join(' ');
        var numero_documento = persona['emp_ruc'];
        var dni = 1;
        var numero_dni = persona['emp_ruc'].substring(2,10);
        $("#txt_estado").val(persona['emp_estado_con']);
        $("#txt_situacion").val(persona['emp_con_domicilio']);
        $("#txt_nombre_via").val(persona['emp_tipo_via'] + " "+ persona['emp_nombre_via'] + " "+ persona['emp_codigo_zona'] + " " + persona['emp_tipo_zona']);
        $("#txt_numero_via").val(persona['emp_numero']);
        $("#txt_interior_via").val(persona['emp_interior']);

        if (data.ubigeo['ubigeo_id'] !== null) {
            $("#cbo_ubigeo").append("<option value='"+data.ubigeo['ubigeo_id']+"' selected>"+ data.ubigeo['ubigeo_codigo'] + " | " + data.ubigeo['ubigeo_descripcion'] + "</option>");
        }

    }else if (codigo.length == 8){
        
        $("#txt_razonsocial_cliente_proveedor").val(persona.apellidoPaterno +' '+ persona.apellidoMaterno + ' '+ persona.nombres);
        $("#txt_razonsocial_otros_datos").val(persona.apellidoPaterno +' '+ persona.apellidoMaterno + ' '+ persona.nombres);
        var apellido_paterno = persona.apellidoPaterno;
        var apellido_materno = persona.apellidoMaterno;
        var nombres = persona.nombres;
        var numero_documento = persona.dni;
        var dni = data.documentoide_id;
        var numero_dni = persona.dni; 
    }
    $("#cambiar_tipo").html("<div class='col-md-4 col-xs-12'><label for='txt_apellido_paterno'>Ap.Paterno:</label><input type='text' name='txt_apellido_paterno' id='txt_apellido_paterno' class='form-control' value='" + apellido_paterno + "'></div>" +
        "<div class='col-md-4 col-xs-12'><label for='txt_apellido_materno'>Ap.Materno:</label><input type='text' name='txt_apellido_materno' id='txt_apellido_materno' class='form-control' value='" + apellido_materno + "'></div>" +
        "<div class='col-md-4 col-xs-12'><label for='txt_nombres'>Nombres:</label><input type='text' id='txt_nombres' name='txt_nombres' class='form-control' value='"+ nombres +"'></div>");
    $("#txt_documento").val(numero_documento);
    $("#cbo_dni").val(dni).trigger('change');
    $("#txt_dni").val(numero_dni);
}

function llenar_juridica(data,persona){
    limpiar_inputs_juridica();

    $("#txt_razonsocial_cliente_proveedor").val(persona['emp_descripcion']);
    $("#txt_razonsocial_otros_datos").val(persona['emp_descripcion']);
    $("#cambiar_tipo").html("<div class='col-md-4 col-xs-12'><label for='name'>Razón social / Nombre completo:</label><input type='text' name='descripcion_tercero' id='descripcion_tercero' class='form-control' value='" + persona['emp_descripcion'] + "'></div>");
    $("#txt_estado").val(persona['emp_estado_con']);
    $("#txt_situacion").val(persona['emp_con_domicilio']);
    $("#txt_nombre_via").val(persona['emp_tipo_via'] + " "+ persona['emp_nombre_via'] + " "+ persona['emp_codigo_zona'] + " " + persona['emp_tipo_zona']);
    $("#txt_numero_via").val(persona['emp_numero']);
    $("#txt_interior_via").val(persona['emp_interior']);
    $("#cbo_ubigeo").append("<option value='"+data.ubigeo['ubigeo_id']+"' selected>"+ data.ubigeo['ubigeo_codigo'] + " | " + data.ubigeo['ubigeo_descripcion'] + "</option>");
    $("#txt_documento").val(persona['emp_ruc']);
}
function llenar_persona_otro(){
    $("#txt_documento").val($("#code").val());
}
$("#modal_contactos").click(function(){
    $("#nombre_tercero_contacto").val('');
    $("#nrodocidentidad_tercero_contacto").val('');
    $("#cargo_tercero_contacto").val('');
    $("#myModalContacto #cpe_tercero_contacto").prop("checked", false);
    $("#email_tercero_contacto").val('');
    $("#telefono_tercero_contacto").val('');
    $("#row_id_contacto").val('');
    $('#myModalContacto').modal('show');
});
$('#tabla_contactos tbody').on('click', '#btn_editar_contacto', function () {
    tabla = $('#tabla_contactos').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_contactos(data);
});
function editar_contactos(data){
    $('#myModalContacto').modal('show');
    $("#nombre_tercero_contacto").val(data.options.nombre);
    $("#nrodocidentidad_tercero_contacto").val(data.options.nrodocidentidad);
    $("#cargo_tercero_contacto").val(data.options.cargo);
    data.options.cpe == 'on' ? $("#myModalContacto #cpe_tercero_contacto").prop("checked", true) : $("#myModalContacto #cpe_tercero_contacto").prop("checked", false);
    $("#email_tercero_contacto").val(data.options.email);
    $("#telefono_tercero_contacto").val(data.options.telefono);
    $("#row_id_contacto").val(data.rowId);
}
function enviar_contactos(){
    if($("#row_id_contacto").val() != ''){
        contactos_env('editar_contactos');
    }else{
        contactos_env('agregar_contactos');
    }
}
function contactos_env(ruta){
    tabla = $('#tabla_contactos').DataTable();
    var variable = $("#var").val();
    var frm = $("#frm_contacto");

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize()+"&id="+$("#id").val(),
        success: function (data) {
            $("#row_id_contacto").val('');
            $('#myModalContacto').modal('hide');
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#tabla_contactos tbody').on('click', '#btn_eliminar_contacto', function () {
    tabla = $('#tabla_contactos').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    eliminar_item(data.rowId, data.options.item, tabla,'tercero_contacto');
});
$("#modal_cuentas_bancarias").click(function(){
    $('#myModalCuentasBancarias').modal('show');
    $("#banco_id_tercero_cuenta").val('').trigger('change');
    $("#cuenta_tercero_cuenta").val('');
    $("#moneda_id_tercero_cuenta").val('').trigger('change');
    $("#tipocuenta_tercero_cuenta").val('').trigger('change');
    $("#myModalCuentasBancarias #defecto_tercero_cuenta").prop("checked", false);
    $("#cci_tercero_cuenta").val('');
    $("#swift_tercero_cuenta").val('');
    $("#row_id_cuentasbancarias").val('');
});
function enviar_cuentas_bancarias(){
    if($("#row_id_cuentasbancarias").val() != ''){
        cuentas_bancarias_env('editar_cuentas_bancarias');
    }else{
        cuentas_bancarias_env('agregar_cuentas_bancarias');
    }
}
function cuentas_bancarias_env(ruta){
    tabla = $('#tabla_cuentas_bancarias').DataTable();
    var variable = $("#var").val();
    var frm = $("#frm_cuentas_bancarias");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize()+"&id="+$("#id").val(),
        success: function (data) {
            $("#row_id_cuentasbancarias").val('');
            $('#myModalCuentasBancarias').modal('hide');
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#tabla_cuentas_bancarias tbody').on('click', '#btn_editar_tercero_cuenta', function () {
    tabla = $('#tabla_cuentas_bancarias').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_tercero_cuenta(data);
});
function editar_tercero_cuenta(data){
    $('#myModalCuentasBancarias').modal('show');
    $("#banco_id_tercero_cuenta").val(data.options.banco_id).trigger('change');
    $("#cuenta_tercero_cuenta").val(data.options.cuenta);
    $("#moneda_id_tercero_cuenta").val(data.options.moneda_id).trigger('change');
    $("#tipocuenta_tercero_cuenta").val(data.options.tipocuenta).trigger('change');
    data.options.defecto == 'on' ? $("#myModalCuentasBancarias #defecto_tercero_cuenta").prop("checked", true) : $("#myModalCuentasBancarias #defecto_tercero_cuenta").prop("checked", false);
    $("#cci_tercero_cuenta").val(data.options.cci);
    $("#swift_tercero_cuenta").val(data.options.swift);
    $("#row_id_cuentasbancarias").val(data.rowId);
}
$('#tabla_cuentas_bancarias tbody').on('click', '#btn_eliminar_tercero_cuenta', function () {
    tabla = $('#tabla_cuentas_bancarias').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    eliminar_item(data.rowId, data.options.item, tabla,'tercero_cuenta');
});
$("#modal_marcas_provee").click(function(){
    $('#myModalMarca').modal('show');
    $("#marca_id_tercero_marca").val('').trigger('change');
});
function enviar_marcas(){
    if($("#row_id_marca").val() != ''){
        marcas_env('editar_tercero_marca');
    }else{
        marcas_env('agregar_tercero_marca');
    }
}
function marcas_env(ruta){
    tabla = $('#tabla_tercero_marca').DataTable();
    var variable = $("#var").val();
    var frm = $("#frm_marcas");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize()+"&id="+$("#id").val(),
        success: function (data) {
            $("#row_id_marca").val('');
            $('#myModalMarca').modal('hide');
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#tabla_tercero_marca tbody').on('click', '#btn_editar_tercero_marca', function () {
    tabla = $('#tabla_tercero_marca').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_tercero_marca(data);
});
function editar_tercero_marca(data){
    $('#myModalMarca').modal('show');
    $("#marca_id_tercero_marca").append("<option value='"+data.options.marca_id+"' selected>"+ data.options.marca_codigo + " | " + data.options.marca_descripcion + "</option>");
    $("#row_id_marca").val(data.rowId);
}
$('#tabla_tercero_marca tbody').on('click', '#btn_eliminar_tercero_marca', function () {
    tabla = $('#tabla_tercero_marca').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    eliminar_item(data.rowId, data.options.item, tabla,'tercero_marca');
});
$("#modal_rubros").click(function(){
    $('#myModalRubros').modal('show');
    $("#tiporubro_id_tercero_rubro").val('').trigger('change');
});
function enviar_tercero_rubro(){
    if($("#row_id_rubro").val() != ''){
        rubro_env('editar_tercero_rubro');
    }else{
        rubro_env('agregar_tercero_rubro');
    }
}
function rubro_env(ruta){
    tabla = $('#tabla_tercero_rubro').DataTable();
    var variable = $("#var").val();
    var frm = $("#frm_rubros");
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize()+"&id="+$("#id").val(),
        success: function (data) {
            $("#row_id_rubro").val('');
            $('#myModalRubros').modal('hide');
            tabla.ajax.reload();
        },error: function (data) {
            mostrar_errores(data);
        }
    });
}
$('#tabla_tercero_rubro tbody').on('click', '#btn_editar_tercero_rubro', function () {
    tabla = $('#tabla_tercero_rubro').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_tercero_rubro(data);
});
function editar_tercero_rubro(data){
    $('#myModalRubros').modal('show');
    $("#tiporubro_id_tercero_rubro").val(data.options.tiporubro_id).trigger('change');
    $("#row_id_rubro").val(data.rowId);
}
$('#tabla_tercero_rubro tbody').on('click', '#btn_eliminar_tercero_rubro', function () {
    tabla = $('#tabla_tercero_rubro').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    eliminar_item(data.rowId, data.options.item, tabla,'tercero_rubro');
});
$("#modal_tercero_direccion").click(function(){
    $('#myModal_tercero_direccion').modal('show');
    $('#row_id_tercero_direccion').val('');
    $("#ubigeo_id_tercero_direccion").val('').trigger('change');
    $("#via_nombre_tercero_direccion").val('');
});
function enviar_tercero_direccion(){
    listado = $('#tabla_tercero_direccion').DataTable();
    if($("#row_id_tercero_direccion").val() != ''){
        enviar_carrito('editar_tercero_direccion', listado, 'tercero_direccion');
    }else{
        enviar_carrito('agregar_tercero_direccion', listado, 'tercero_direccion');
    }
}
$('#tabla_tercero_direccion tbody').on('click', '#btn_editar_tercero_direccion', function () {
    tabla = $('#tabla_tercero_direccion').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_tercero_direccion(data);
});
function editar_tercero_direccion(data){
    $('#myModal_tercero_direccion').modal('show');
    $("#ubigeo_id_tercero_direccion").append("<option value='"+data.options.ubigeo_id+"' selected>"+ data.options.ubigeo_codigo + " | " + data.options.ubigeo_completo + "</option>");
    $("#via_nombre_tercero_direccion").val(data.options.via_nombre);
    $("#row_id_tercero_direccion").val(data.rowId);
}
$('#tabla_tercero_direccion tbody').on('click', '#btn_eliminar_tercero_direccion', function () {
    tabla = $('#tabla_tercero_direccion').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    eliminar_item(data.rowId, data.options.item, tabla,'tercero_direccion');
});
$("#modal_tercero_empresa").click(function(){
    $('#myModal_tercero_empresa').modal('show');
    $('#row_id_tercero_empresa').val('');
    $('#ruc_tercero_empresa').val('');
    $('#razonsocial_tercero_empresa').val('');
    $('#direccion_tercero_empresa').val('');
    $("#tipo_tercero_empresa").val('N').trigger('change');
});
function enviar_tercero_empresa(){
    listado = $('#tabla_tercero_empresa').DataTable();
    if($("#row_id_tercero_empresa").val() != ''){
        enviar_carrito('editar_tercero_empresa', listado, 'tercero_empresa');
    }else{
        enviar_carrito('agregar_tercero_empresa', listado, 'tercero_empresa');
    }
}
$('#tabla_tercero_empresa tbody').on('click', '#btn_editar_tercero_empresa', function () {
    tabla = $('#tabla_tercero_empresa').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    editar_tercero_empresa(data);
});
function editar_tercero_empresa(data){
    $('#myModal_tercero_empresa').modal('show');
    $("#ruc_tercero_empresa").val(data.options.ruc);
    $("#razonsocial_tercero_empresa").val(data.options.razonsocial);
    $("#direccion_tercero_empresa").val(data.options.direccion);
    $("#tipo_tercero_empresa").val(data.options.tipo).trigger('change');
    $("#row_id_tercero_empresa").val(data.rowId);
}
$('#tabla_tercero_empresa tbody').on('click', '#btn_eliminar_tercero_empresa', function () {
    tabla = $('#tabla_tercero_empresa').DataTable();
    var data = tabla.row($(this).parents('tr')).data();
    eliminar_item(data.rowId, data.options.item, tabla,'tercero_empresa');
});

$('#code').on( 'keydown',function(e) {
    if( e.keyCode === 9 || e.keyCode === 13) {
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "POST",
            url: "/" + $("#var").val() + "/valida_codigo",
            dataType: "JSON",
            data: {codigo: $("#code").val(), id: $("#id").val()},
            success: function (data) {
                if (data.estado === 'si') {
                    error('error', 'El código ya existe verifique', 'Alerta!');
                } else {
                    tipo_persona(data);
                    $('#tabla_tercero_direccion').DataTable().ajax.reload();
                }
            },
            beforeSend:function(){
                abre_loading();
            },
            complete:function(){
                cierra_loading();
            }
        });
    }
} );
function limpiar_inputs_juridica(){
    $("#descripcion_tercero").val('');
    $("#txt_nombre_comercial").val();
    //limpiar_inputs_comun();
}
function limpiar_inputs_natural(){
    $("#txt_apellido_paterno").val('');
    $("#txt_apellido_materno").val('');
    $("#txt_nombres").val();
    //limpiar_inputs_comun();
}
function limpiar_inputs_comun(){
    $("#cbo_documento").val('');
    $("#txt_documento").val('');
    $("#cbo_dni").val('');
    $("#txt_dni").val('');
    $("#cbo_via_tipo").val('');
    $("#txt_nombre_via").val('');
    $("#txt_numero_via").val('');
    $("#txt_interior_via").val('');
    $("#cbo_tipo_zona").val('');
    $("#txt_nombre_zona").val('');
    $("#cbo_ubigeo").val('');
    $("#txt_telefono1").val('');
    $("#txt_telefono2").val('');
    $("#txt_telefono3").val('');
    $("#txt_email").val('');
    $("#txt_web").val('');
}
function guardar() {
    store();
}
function actualizar() {
    update();
}
//movimientos el valor es 1 tablas comunes es 0
function anula() {
    anular(0);
}

