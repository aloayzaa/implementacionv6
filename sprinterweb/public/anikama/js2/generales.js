var id_table = 'dataTable';
$(document).ready(function () {
    var proceso = $("#proceso").val();

    if (proceso === 'crea') {
        //$("#btn_grabar").attr("disabled", true);
    } else if (proceso === 'edita') {
        /*$("#frm_generales :input").attr("disabled", true);
        $("#btn_editar").attr("disabled", true);*/
    }

    $('.select2').select2();  //Verificar si no hay conflicto
});

$("#btn_habilitar").click(function () {
    /*$("#frm_generales :input").attr("disabled", false);
    $("#btn_editar ").attr("disabled", false);*/

    //habilitar
    if($('#var').val() == 'lowcommunication' && $("#txtstr4").val() != ''){
        warning('warning', 'No tiene privilegios para realizar operaciónes', 'Información');
    }else{
        $(".nuevo2").css('display', 'block');
        $(".habilitar2").css('display', 'block');
        $(".editar2").css('display', 'block');
        $(".cancelar2").css('display', 'block');
        $(".nueva_linea2").css('display', 'block');
        $(".aprobar2").css('display', 'block');
        $(".confi2").css('display', 'block');
        $(".reset2").css('display', 'block');
        $(".eliminar2").css('display', 'block');
        $(".anular2").css('display', 'block');
    
        $(".nuevo").css('display', 'none');
        $(".habilitar").css('display', 'none');
        $(".editar").css('display', 'none');
        $(".cancelar").css('display', 'none');
        $(".nueva_linea").css('display', 'none');
        $(".aprobar").css('display', 'none');
        $(".confi").css('display', 'none');
        $(".reset").css('display', 'none');
        $(".eliminar").css('display', 'none');
        $(".anular").css('display', 'none');
    
        $('.identificador').removeClass('ocultar');
    }
    
});

$("#btn_eliminar").click(function () {
    var id = $("#id").val();
    eliminar_registro(id);
});
$("#btn_anular").click(function () {
    anula();
});
function anular(valor){
    var id = $("#id").val();
    var estado = $("#estado").val();
    if (valor == 1){
        anular_registro_mov_show(id)
    }else {
        anular_registro(id, estado);
    }
}

$("#btn_cancelar").click(function () {
    swal.fire({
        title: 'Los cambios se van a cancelar, ¿Está seguro?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si",
        cancelButtonText: "No",
    }).then(function (result) {
        if (result.value) {
            location.reload();
        }
    });
});

function redireccionar(route, proceso) {
    window.location.href = route + '?proceso=' + proceso + '&' + 'route=' + route;
}

function verificar() {
    var page = $("#page").val();
    if (page !== 'login' && page !== 'register') {
        $.ajax({
            type: "GET",
            url: "/Suscripcion/check_suscription",
            success: function (data) {
                if (data !== '') {
                    for (var i = 0; i < data.length; i++) {
                        error("error", data[i], "Alerta!");
                    }
                }
            }
        });
    }
}

function verificarPuntoVentaUpdate() {
    var page = $("#page").val();
    if (page !== 'login' && page !== 'register') {
        $.ajax({
            type: "GET",
            url: "/Punto-Venta/check/point",
            success: function (data) {
                if (data !== '') {
                    error("error", data.message, data.title);
                }
            }
        });
    }
}

function eliminar_detalle(id, instancia, variable, estado, item) {
    if (estado === 1) {
        func_eliminar('Activar', id, instancia, variable, estado, item);
    } else if (estado === 0) {
        func_eliminar('Anular', id, instancia, variable, estado, item);
    }
}

function func_eliminar(tipo, id, instancia, variable, estado, item) {

    swal.fire({
        title: '¿' + tipo + ' el registro?',
        text: "Se actualizará el registro" + id,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, actualizar.",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            var datos = $("#frm_generales").serialize() + '&id_tmp=' + id + '&instancia=' + instancia + '&estado=' + estado + '&item=' + item;
            var url = '/' + variable + '/borrar_item';

            if (variable === 'billing') {
                if (tipo === 'Anular') {
                    var urrl = '/' + variable + '/search/cart/delete';
                    $.post(urrl, datos, function (cart) {
                        delete_tr(id);
                        subtract(cart);
                    });
                }
            } else {
                $.post(url, datos, function (resultado) {
                    listar_detalle(resultado, variable);
                });
            }
        }
    });
}

function delete_tr(id) {
    $("#" + id).remove();
}

//REVISA CONEXION DE INTERNET
function checkNetConnection() {
    $.ajaxSetup({async: false});
    re = "";
    r = Math.round(Math.random() * 10000);
    $.get("/", {subins: r}, function (d) {
        re = true;
    }).error(function () {
        re = false;
    });

    if (re === false) {
        alert('No hay conexión de internet');
    }
}

function info(type, message, title) {

    toastr[type](message, title);

    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}

function success(type, message, title) {

    toastr[type](message, title);

    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}

function warning(type, message, title) {

    toastr[type](message, title);

    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}

function warning_sunat(type, message, title) {

    toastr[type](message, title);

    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "80000",
        "hideDuration": "10000",
        "timeOut": "10000",
        "extendedTimeOut": "80000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}


function error(type, message, title) {

    toastr[type](message, title);

    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}


function redondea(numero, decimales) {
    var original = parseFloat(numero);
    return original.toFixed(decimales);
}

function emailbill(id, variable) {
    var token = $("#_token").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "POST",
        url: "/" + variable + "/emailbill/" + id,
        success: function (data) {
            if (data !== 'error') {
                success("success", "Correo Enviado..", "Exito!");
            } else {
                error("error", "Error al enviar correo..", "Error!");
            }
        }
    });
}

function cancelbill(id) {

    var token = $("#_token").val();
    var variable = $('#variable').val();

    swal.fire({
        title: '¿Anulará la factura?',
        text: "Anulará la factura con ID " + id + "",
        type: 'warning',
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, anular",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': token},
                type: "POST",
                url: "/" + variable + "/comunicacion_baja/" + id,
                success: function (data) {
                    if (data !== 'error') {
                        success("success", "Factura anulada...", "Exito!");
                    } else {
                        error("error", "Error al anular...", "Error!");
                    }
                    $(".table").DataTable().ajax.reload();
                }
            });
        }
    });
}

$('#btnEjecutaReporte').click(function () {
    ejecuta_reporte();
});

function ejecuta_reporte() {
    var variable = $("#var").val();
    var frmreporte = $("#frm_reporte");

    $.ajax({
        type: "GET",
        url: "/" + variable + "/list",
        dataType: "JSON",
        data: frmreporte.serialize(),
        success: function () {
            $(".table").DataTable().ajax.reload();
        }
    });
}

function ceros_izquierda(tam, num) {
    if (num.toString().length < tam) {
        return ceros_izquierda(tam, "0" + num)
    } else {
        return num;
    }
}


function calcular_vencimiento(condpago, fecha) {
    $.ajax({
        type: "GET",
        url: "/paymentcondition/obtenerdiascondicionpago",
        dataType: "JSON",
        data: '&condpago=' + condpago + '&fecha=' + fecha,
        success: function (data) {
            if (data) {
                $("#expdatea").val(data.date);
            }
        }
    });
}

//TOTALIZA IMPORTE DEL DETALLE
function totalizar() {
    var frmGenerales = $("#frm_generales");
    var variable = $("#var").val();
    var incl = checkbox('chktax');

    if ($("#igv").val() === null) {
        var igv = '3';
    } else {
        var igv = $("#igv").val();
    }

    if ($("#rent").val() === null) {
        var rent = '3';
    } else {
        var rent = $("#rent").val();
    }
    $.ajax({
        type: "POST",
        url: "/" + variable + "/totalizar",
        dataType: "JSON",
        data: frmGenerales.serialize(),
        success: function (data) {
            if (igv !== '3' && rent === '3') {
                $("#base").val(data.toFixed(2));
                $("#inactive").val('0.00');
            } else if (igv === '3' && rent !== '3') {
                $("#base").val('0.00');
                $("#inactive").val(data.toFixed(2));
            } else if (igv === '3' && rent === '3') {
                $("#base").val('0.00');
                $("#inactive").val(data.toFixed(2));
            } else {
                $("#base").val(data.toFixed(2));
            }
        },
        complete: function () {
            if (incl === 0) {
                if (igv !== '3' || igv === '3' && rent === '3') {
                    calcula_impuesto();
                } else if (rent !== '3' || rent === '3' && igv === '3') {
                    calcula_impuesto_renta();
                }
            } else if (incl === 1) {
                calcula_impuesto_incluido();
            }

        }
    });
}

//CALCULA IGV
function calcula_impuesto() {
    var frmgenerales = $("#frm_generales");
    var incl = checkbox('chktax');

    if ($("#igv").val() === '') {
        var igv = '3';
    } else {
        var igv = $("#igv").val();
    }

    $.ajax({
        type: "GET",
        url: "/taxes/calcularigv",
        dataType: "JSON",
        data: frmgenerales.serialize(),
        success: function (data) {
            var num = parseFloat(data.resultado).toFixed(2);
            //if (incl === 0) {
            if (igv === '3') {
                $("#igvtotal").val('0.00');
            } else {
                $("#igvtotal").val(num);
                $("#rentimport").val('0.00');
            }
            //}
        },
        complete: function () {
            calcular_total();// calculo(no tocar)
        }
    });
}

//CALCULA IGV
function calcula_impuesto_incluido() {
    var frmgenerales = $("#frm_generales");
    var igv = $("#igv").val();
    var incl = checkbox('chktax');
    $.ajax({
        type: "GET",
        url: "/taxes/calcularconigv",
        dataType: "JSON",
        data: frmgenerales.serialize(),
        success: function (data) {
            var num = parseFloat(data.resultado).toFixed(2);
            var tot = parseFloat(data.total).toFixed(2);
            if (igv === '3') {
                $("#igvtotal").val('0.00');
            } else {
                $("#base").val(tot);
                $("#igvtotal").val(num);
                $("#rentimport").val('0.00');
            }
        },
        complete: function () {
            calcular_total();
        }
    });
}

//CALCULA RENT
function calcula_impuesto_renta() {
    var frmgenerales = $("#frm_generales");

    if ($("#rent").val() === '') {
        var rent = '3';
    } else {
        var rent = $("#rent").val();
    }

    $.ajax({
        type: "GET",
        url: "/taxes/calcularrent",
        dataType: "JSON",
        data: frmgenerales.serialize(),
        success: function (data) {
            var num = parseFloat(data.resultado).toFixed(2);
            if (rent === '3') {
                $("#rentimport").val('0.00');
            } else {
                $("#rentimport").val(num);
                $("#base").val('0.00');
                $("#igvtotal").val('0.00');
            }
        },
        complete: function () {
            calcular_total();//calculo (no tocar)
        }
    });
}

//CALCULAR PERCEPCION CON Y SIN SELECT PERCEPCION
function calcula_percepcion() {
    var cbo_percepcion = $("#perception").val();
    var frmNuevo = $("#frm_generales");

    $.ajax({
        type: "GET",
        url: "/taxes/calcularigv",
        dataType: "JSON",
        data: frmNuevo.serialize() + '&igv=' + cbo_percepcion,
        success: function (data) {
            if (data) {
                var num = parseFloat(data.resultado).toFixed(2);
                $("#perceptiontotal").val(num);
            }
        },
        complete: function () {
            calcular_total();
        }
    });
}

//CALCULAR EL TOTAL CON TODOS LOS IMPUESTOS
function calcular_total() {
    if ($("#base").val()) {
        var afecto = parseFloat($("#base").val());
    } else {
        var afecto = parseFloat('0');
    }

    if ($("#igvtotal").val()) {
        var igv = parseFloat($("#igvtotal").val());
    } else {
        var igv = parseFloat('0');
    }

    if ($("#inactive").val()) {
        var inafecto = parseFloat($("#inactive").val());
    } else {
        var inafecto = parseFloat('0');
    }

    if ($("#perceptiontotal").val()) {
        var percepcion = parseFloat($("#perceptiontotal").val());
    } else {
        var percepcion = parseFloat('0');
    }

    if ($("#rentimport").val()) {
        var renta = parseFloat($("#rentimport").val());
    } else {
        var renta = parseFloat('0');
    }

    var total = afecto + inafecto + igv + percepcion - renta;
    $('#total').val(total.toFixed(2));
}

function valida_numero(numero) {
    if (numero == null) {
        return 0.000
    } else {
        return numero;
    }
}

$("#btnUpdate").click(function () {
    var period = $("#accountingPeriod").val();
    var proceso = $("#proceso").val();

    $.ajax({
        type: "GET",
        url: "/periods/perioddate",
        dataType: "JSON",
        data: '&period=' + period,
        success: function (data) {
            window.location.reload();
            /*        $("#processdate").attr({"min": data.period.inicio, "max": data.period.final});
                    $("#date").attr({"min": data.period.inicio, "max": data.period.final});
                    $("#period").val(data.period.descripcion);

                    if (proceso !== 'crea' && proceso !== 'edita') {
                        $(".table").DataTable().ajax.reload();
                    }*/
        }
    });
});

function ctacte(id) {  //chekear esta funcion creo que está por las we
    $("#" + id).find('option').not(':first').remove();
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/ctacte",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data) {
                for (let i = 0; i < data.length; i++) {
                    $("#" + id).append('<option value="' + data[i].id + '">' + data[i].descripcion + '</option>')
                }
            }
        }
    });
}

function currency(id) {         //chekear esta funcion creo que está por las we
    $("#" + id).find('option').not(':first').remove();
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/currency",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data) {
                $("#" + id).append('<option value="' + data.id + '" selected>' + data.codigo + ' | ' + data.descripcion + '</option>')
            }
        }
    });
}

function checkbox(id) {
    if ($('#' + id).is(":checked")) {
        return 1;
    } else {
        return 0;
    }
}

function ruc_proveedor(id) {
    var variable = $("#var").val();
    $.ajax({
        url: '/' + variable + '/rucproveedor',
        type: 'GET',
        data: '&id=' + id,
        success: function (data) {
            $("#customerruc").val(data.codigo);
            $("#customeraddress").val(data.via_nombre);
        }
    });
}


function ruc_proveedor_tab(id) {
    var variable = $("#var").val();
    $.ajax({
        url: '/' + variable + '/rucproveedor',
        type: 'GET',
        data: '&id=' + id,
        success: function (data) {
            $("#txt_ruc").val(data.codigo);
            $("#txt_direccion").val(data.via_nombre);
        }
    });
}

function bankcash(input, bank) {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/bankcash",
        dataType: "JSON",
        data: '&bank=' + bank,
        success: function (data) {
            if (data) {
                $("#" + input).val(data.efectivo);
            }
        }
    });
}

function tercero() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/tercero",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data) {
                $("#ruc").val(data.codigo);
                $("#address").val(data.via_nombre);
            }
        }
    });
}

function verificar_ultimo_numero() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/ultimodocumento",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data === 1) {
                var numerodoc = ceros_izquierda(8, data);
            } else {
                var auxiliar = parseInt(data.numerodoc) + 1;
                var numerodoc = ceros_izquierda(8, auxiliar);
            }

            $("#number").val(numerodoc);
            if (variable !== 'withholdingdocuments') {
                verificar_numero_registrado();
            } else {
                genera_glosa(variable);
            }
        }
    });
}

function verificar_numero_registrado() {
    var variable = $("#var").val();
    $.ajax({
        type: "GET",
        url: "/" + variable + "/ultimodocumento",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            if (data !== null) {
                $("#number").val('');
                error("error", 'El documento ya esta registrado', "Error!");
                $("#number").focus();
            }
        }
    });
}

function genera_glosa(variable) {
    var serie = $('#series').val();
    var txt_numero_doc = $('#number').val();

    $.ajax({
        type: "POST",
        url: "/" + variable + "/consulta_documento",
        dataType: "JSON",
        data: $("#frm_generales").serialize(),
        success: function (data) {
            $('#comment').val(data.descripcion + '/' + serie + '-' + txt_numero_doc);
        }
    });
}

function ventanaSecundaria(URL) {
    window.open(URL);
}


function backButtonRefresh() {
    window.addEventListener("pageshow", function (event) {
        var historyTraversal = event.persisted ||
            (typeof window.performance != "undefined" &&
                window.performance.navigation.type === 2);
        if (historyTraversal) {
            //Handle page restore.
            window.location.reload();
        }
    });
}

function select2(elemento, ruta) {
    var variable = $("#var").val();
    $("#" + elemento).select2({
        placeholder: "-Seleccionar-",
        minimumInputLength: 2,
        multiple: false,
        //width: 400,
        ajax: {
            url: "/" + variable + "/" + ruta,
            data: function (term) {
                return term;
            },
            processResults: function (data) {
                $("#" + elemento).empty();
                return {
                    results: $.map(data, function (obj) {
                        return {
                            id: obj.id,
                            text: obj.codigo + ' | ' + obj.descripcion
                        };
                    })
                };
            }
        }
    });
}

function llenarSelect(select, route) {
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
                            id: obj.id,
                            text: obj.codigo + ' | ' + obj.descripcion,
                            otros: obj
                        };
                    })
                };
            }
        }
    });
}


function llenarSelectCuentas(select, route) {
    $("#" + select).select2({
        placeholder: "-- Seleccione una opción --",
        minimumInputLength: 2,
        multiple: false,
        //width: 400,
        ajax: {
            url: route,
            data: function (term) {
                return term;
            },
            processResults: function (data) {
                $("#" + select).empty();
                return {
                    results: $.map(data, function (obj) {
                        return {
                            id: obj.id,
                            text: obj.codigo + ' | ' + obj.descripcion,
                            otros: obj,
                            disabled: obj.es_titulo != 2 ? true : false
                        };
                    })
                };
            }
        }
    });
}

//No borrar
function llenarSelectCuentasByClass(select, route) {
    $("." + select).select2({
        placeholder: "-- Seleccione una opción --",
        minimumInputLength: 2,
        multiple: false,
        //width: 400,
        ajax: {
            url: route,
            data: function (term) {
                return term;
            },
            processResults: function (data) {
                $("#" + select).empty();
                return {
                    results: $.map(data, function (obj) {
                        return {
                            id: obj.id,
                            text: obj.codigo + ' | ' + obj.descripcion,
                            otros: obj,
                            disabled: obj.es_titulo != 2 ? true : false
                        };
                    })
                };
            }
        }
    });
}

$('#btn_aprobar').click(function () {
    estado();
});

function estado() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "GET",
        url: $("#ruta_estado").val(),
        dataType: "json",
        data: {id: $("#id").val()},
        success: function (data) {
            if (data.estado == 'ok') {
                $('#estadito').text(data.estado_tabla);
                success('success', 'El registro está ' + data.estado_tabla + '!!');
            }
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}


function filterFloat(evt, input) {
    // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
    var key = window.Event ? evt.which : evt.keyCode;
    var chark = String.fromCharCode(key);
    var tempValue = input.value + chark;
    if (key >= 48 && key <= 57) {
        if (filter(tempValue) === false) {
            return false;
        } else {
            return true;
        }
    } else {
        if (key == 8 || key == 13 || key == 46 || key == 0) {
            return true;
        } else {
            return false;
        }
    }
}

function filter(__val__) {
    var preg = /^([0-9]+\.?[0-9]{0,2})$/;
    if (preg.test(__val__) === true) {
        return true;
    } else {
        return false;
    }

}

function eliminar_item(rowId, item, tabla, insancia_eliminar) {
    var variable = $("#var").val();
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/" + variable + "/" + 'eliminar_carrito',
        data: {rowId: rowId, item: item, instancia_eliminar: insancia_eliminar},
        success: function (data) {
            tabla.ajax.reload();
            success('success', 'Detalle eliminado correctamente!', 'Información');
        },
        error: function (data) {
        }
    });
}

function enviar_carrito(ruta, listado, tabla) {
    var variable = $("#var").val();
    var frm = $("#frm_" + tabla);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + variable + "/" + ruta,
        data: frm.serialize() + "&id=" + $("#id").val(),
        success: function (data) {
            $("#row_id_" + tabla).val('');
            $("#myModal_" + tabla).modal('hide');
            listado.ajax.reload();
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

/*loading*/
function abre_loading(mensaje) {
    //eliminamos si existe un div ya bloqueando
    cierra_loading();

    //si no enviamos mensaje se pondra este por defecto
    var mensaje = "Su solicitud está siendo procesada"/*linea agregada, en caso se envie el parametro mensaje borrar esta linea*/
    if (mensaje === undefined) mensaje = "Su solicitud está siendo procesada<br>Espere por favor";

    //centrar imagen gif
    var height = 350;//El div del titulo, para que se vea mas arriba (H)
    var ancho = 0;
    var alto = 0;

    //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
    if (window.innerWidth == undefined) ancho = window.screen.width;
    else ancho = window.innerWidth;
    if (window.innerHeight == undefined) alto = window.screen.height;
    else alto = window.innerHeight;

    //operación necesaria para centrar el div que muestra el mensaje
    var heightdivsito = alto / 2 - parseInt(height) / 2;//Se utiliza en el margen superior, para centrar

    //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
    imgCentro = "<div id='letra' style='text-align:center;height:" + alto + "px;'>" +
        "<div style='color:#FFF;margin-top:" + heightdivsito + "px; font-size:18px;font-weight:bold'>" +
        "<div class='lds-css ng-scope'>" +
        "<div style='width:100%;height:100%' class='lds-facebook'>" +
        "<img src='/../img/loading.svg' alt='Cargando...'>" +
        "<br>" +
        mensaje +
        "<div>" +
        "</div>" +
        "</div>" +
        "</div>";

    //creamos el div que bloquea grande
    div = document.createElement("div");
    div.id = "WindowLoad";
    div.style.width = 100 + "%";
    div.style.height = 100 + "%";
    $("body").append(div);

    //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
    input = document.createElement("input");
    input.id = "focusInput";
    input.type = "text";

    //asignamos el div que bloquea
    $("#WindowLoad").append(input);

    //asignamos el foco y ocultamos el input text
    $("#focusInput").focus();
    $("#focusInput").hide();

    //centramos el div del texto
    $("#WindowLoad").html(imgCentro);
}

function cierra_loading() {
    // eliminamos el div que bloquea pantalla
    $("#WindowLoad").remove();

}

/*-------------------*/
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { // corrige la deformación del datatable en los tabs
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust()
        .responsive.recalc();
});

function estado_color(estado) {
    switch ((estado.length > 1) ? estado.toUpperCase() : parseInt(estado)) {
        case 'APROBADO':
            return '<span class="label" style="color: black; background: yellow">APROBADO</span>';
            break;
        case 'CERRADO':
            return '<span class="label" style="color:white;background: gray">CERRADO</span>';
            break;
        case 'ACTIVO':
            return '<span class="label bg-blue">ACTIVO</span>';
            break;
        case 'AT.TOTAL':
            return '<span class="label bg-orange">AT.TOTAL</span>';
            break;
        case 'AT.PARCIAL':
            return '<span class="label bg-orange">AT.PARCIAL</span>';
            break;
        case 'FACTURADO':
            return '<span class="label bg-blue">FACTURADO</span>';
            break;
        case 'FT.PARCIAL':
            return '<span class="label bg-blue">FT.PARCIAL</span>';
            break;
        case 'ANULADO':
            return '<span class="label bg-red">ANULADO</span>';
            break;
        case 'ARCHIVADO':
            return '<span class="label" style="color:white;background: gray">ARCHIVADO</span>';
            break;
        case 1:
            return '<span class="label label-success">ACTIVO</span>';
            break;
        case 0:
            return '<span class="label label-danger">ANULADO</span>';
            break;
        case 'ACEPTADO':
            return '<span class="label bg-blue">ACEPTADO</span>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function total_referencia(data_total) {
    let total = 0;
    data_total.forEach(function (data_total) {
        total += parseFloat(data_total.aplicar.value);
    });
    $('#total_detalle_referencia').val(total.toFixed(2));
}

function init_datatable(table) {
    table.DataTable({
            "scrollX": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "dom": 'Blfrtip',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    footer: true
                },
                {
                    extend: 'csv',
                    footer: true
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: "Sprinter Web - Detalle de Compra",
                    footer: true,
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 6;
                        doc.styles.tableHeader.fontSize = 6;
                        doc.styles.tableFooter.fontSize = 6;
                    }
                },
            ],
        }
    );
}
function entre_intervalo( valor, minimo, maximo ){
    if( parseInt(valor) < parseInt(minimo) ) { return false; }
    if( parseInt(valor) > parseInt(maximo) ) { return false; }
    return true;
}
