$('#btn_grabar').click(function () {
    var attr = $(this).is("[disabled]");
    if (attr === false) {
        guardar();
    }
});

$('#btn_editar').click(function () {  //deberia ser guardar tambien
    actualizar();
});

function validarFecha(fechaProceso = null) {
    
    fechaperiodo = $('#SelectedPeriod').text().toString().toLowerCase() ;  //necesario tenerlo en edit al menos como label
    
    txt_fecha = (fechaProceso) ? fechaProceso : $("#txt_fecha").val();
    fecha = txt_fecha.split("-");
    
    var event = new Date(Date.UTC(fecha[0], fecha[1]));
    var options = { year: 'numeric', month: 'long'};
    
    date = event.toLocaleDateString('es-ES', options);
    dateformted = date.replace(' de', '');

    var validate = fechaperiodo === dateformted;

    if(validate === false){
        warning('warning', 'La fecha no coincide con el periodo seleccionado', 'Advertencia!');
    }
    return validate;
}


function validarDetalles() {
    var totalRecords = table.page.info().recordsTotal;
    var validate = totalRecords != 0
    if(validate == false){
        warning('warning', 'Ingrese al menos un detalle', 'Advertencia')
    }
    return validate;
}

function store(loading = null) {
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
            } else {
                error('error', data.error, 'Error!');
            }
        },
        beforeSend:function(){
          if(loading !== null) {abre_loading();}
        },
        complete:function(){
          if(loading !== null) {cierra_loading();}  
        },     
        error: function (data) {
            mostrar_errores(data);
            $("#btn_grabar").attr("disabled", false);
        }
    });

}
function update() {
    var ruta = $("#ruta").val();
    var form = $("#frm_generales").serialize();

    $("#btn_grabar").attr("disabled", true);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "PUT",
        url: ruta,
        data: form,
        success: function (data) {
            if(data.actualizar){
                window.location.reload(); //para provisiones pago
            }
            if(data.success){
                success('success', data.success, 'Registro Actualizado!!');
                detalles = $('.actualizable').DataTable();
                detalles.ajax.reload();
                //habilitar
                $(".editar").css('display','block');
                $(".editar2").css('display','none');
                $(".nueva_linea2").css('display','none');
                $(".nueva_linea").css('display','block');
                $(".cancelar2").css('display','none');
                $(".cancelar").css('display','block');

                $(".habilitar").css('display','block');
                $(".habilitar2").css('display','none');
                $(".nuevo2").css('display','none');
                $(".nuevo").css('display','block');
                $(".aprobar2").css('display','none');
                $(".aprobar").css('display','block');
                $(".confi2").css('display','none');
                $(".confi").css('display','block');
                $(".reset2").css('display','none');
                $(".reset").css('display','block');

                $('.identificador').addClass('ocultar');
            }
            if(data.error){
                error('error', data.error, 'Error!!');
            }

        },
        error: function (data) {
            mostrar_errores(data);
            $("#btn_grabar").attr("disabled", false);
        }
    });

}
function update_image() {
    var ruta = $("#ruta").val();
    var form = new FormData($("#frm_generales")[0]);
    $("#btn_grabar").attr("disabled", true);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "POST",
        url: ruta,
        data: form,
        //dataType:"JSON",
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.ruta) {
                window.location.replace(data.ruta);
                $("#btn_grabar").attr("disabled", false);
            } else {
                error('error', data.mensaje, 'Error!');
            }
         },
        error: function (data) {
             mostrar_errores(data);
             $("#btn_grabar").attr("disabled", false);
         }
     });

}

/*function update() {   //No se usa para ingresos-pedidos-salidas
    var ruta = $("#ruta").val();
    var form = $("#frm_generales").serialize();


    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "PUT",
        url: ruta,
        data: form,

        success: function (data) {
            console.log(data);
            success('success', 'Se guardaron los cambios correctamenteeee' , 'Guardado!!');
            if(data.errors){
                error('error', data.errors , 'Error!!');
            }
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}*/

function consultar_tipocambio(fecha) {
    console.log(fecha)
    $.ajax({
        type: "GET",
        url: "/exchangerate/consultar/"+fecha,
        dataType: "JSON",
        success: function (data) {
            console.log('tipo de cambio' + data)
            $(".typechange").val(parseFloat(data.t_venta).toFixed(3));
            $(".typechangecompra").val(parseFloat(data.t_compra).toFixed(3));
        }
    });
}

$(".tipocambio").blur(function(e) {   //No siempre es txt_fecha por eso mejor clase separada
    consultar_tipocambio($('.tipocambio').val());
});

$("#txt_fecha").change(function(e) {
    validarFecha();
});

$('.numero').focus( function () {
    $(this).data('val', $(this).val());
    $(this).val('');
});

$('.numero').blur( function () {
    if($(this).val() === ''){
        $(this).val( $(this).data('val'))
    }
});

$('.twodecimal').keypress( function () {
    return filterFloat(event,this)
});

$(".almacencito").change(function () {
    id = $('#id').val();
    almacen = $('.almacencito').val();
    fecha = $('#txt_fecha').val();
    change_almacen(id, almacen, fecha);
});

function change_almacen(id, almacen, fecha) {
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "get",
        url: "/PedidosAlmacen/change_almacen/" + id,
        data: {almacen: almacen, fecha: fecha},
        success: function () {
            table.ajax.reload();
        },
        error: function (data) {
            mostrar_errores(data)
        }
    });
}

$("#btn_actualizar").click(function () {
    var id = $("#id").val();
    var route = $('#frm_generales').data('route');
    var estado = $("#estado").val();
    actualizar(id, route, estado);
});

/*function actualizar(id, route, estado) {
    if (estado === '1') {
        var mensaje = 'Activará';
        var confirm = 'activar';
    } else if (estado === '0') {
        var mensaje = 'Anulará';
        var confirm = 'anular';
    }

    swal.fire({
        title: '¿' + mensaje + ' el registro?',
        text: "Modificará el registro con id: " + id + "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, " + confirm + "",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            var variable = $('#var').val();
            $.ajax({
                url: "/" + variable + "/activar-anular/" + id,
                type: "GET",
                data: {estado: estado},
                success: function (data) {
                    if (data.estado === 'ok') {
                        redireccionar(route, 'edita');
                        success("success", data.mensaje, "Actualizado");
                    } else {
                        error("error", data.mensaje, "Error");
                    }
                }
            });
        }
    });
}

//!!!!!!!!!NO BORRAR!!!!!!!!!!!
function procesar_actualizar(id, estado) {
    swal.fire({
        title: '¿Actualizará el registro?',
        text: "Modificara el registro " + id + "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, actualizar",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            var variable = $('#var').val();
            $.ajax({
                url: "/" + variable + "/activar-anular/" + id,
                type: "GET",
                data: {estado: estado},
                success: function (data) {
                    if (data.estado === 'ok') {
                        $(".table").DataTable().ajax.reload();
                        success("success", data.mensaje, "Actualizado");
                    } else {
                        error("error", data.mensaje, "Error");
                    }
                }, beforeSend: function () {
                    startLoading();
                }, complete: function () {
                    endLoading();
                }
            });
        }
    });
}*/

function mostrar_errores(data) {
    json = data.responseJSON.errors;  //importante
    console.log(json);
    for (var clave in json) {
        // Controlando que json realmente tenga esa propiedad
        if (json.hasOwnProperty(clave)) {
            // Mostrando en pantalla la clave junto a su valor
            error('error', json[clave], 'Error!!');
        }
    }
}

function asiento_referencia(tabla_referencia, id) {
    switch (tabla_referencia) {
        case 'ingresoalmacen':
            provision(id);
            break;
        case 'docxpagar':
            history.back();
    }
}

function provision(documento) {
    if (documento == null) {
        alert('No existe provision')
    } else {
        $('#modal_provision').modal('show');
        mostrarDetalleProvision(documento);
        //tablita.init();
        $.ajax({
            headers: {
                'X-CSRF-Token': $('#_token').val(),
            },
            type: "get",
            url: "/IngresosAlmacen/provision/" + documento,
            success: function (data) {
                $('#provision_periodo').val(data.periodo);
                $('#provision_unegocio').val(data.unegocio);
                $('#provision_numero').val(data.numero);
                $('#provision_fecha').val(data.fechaproceso);
                $('#provision_sucursal').val(data.sucursal);
                $('#provision_transaccion').val(data.tipotran);
                $('#provision_tercero').val(data.tercero);
                $('#provision_doc').val(data.tipodoc);
                $('#provision_serie').val(data.seriedoc);
                $('#provision_numerodoc').val(data.numerodoc);
                $('#provision_tcambio').val(data.tcambio);
                $('#provision_fechadoc').val(data.fechadoc);
                $('#provision_moneda').val(data.moneda);
                $('#provision_vencimiento').val(data.vencimiento);
                $('#provision_glosa').val(data.glosa);
                $('#provision_inafecto').val(data.inafecto);
                $('#provision_total').val(data.total);
            },
        })
    }
}

function mostrarDetalleProvision(documento) {
    var tablita = $('#listprov-detail').DataTable({
        serverSide: true,
        ajax: '/IngresosAlmacen/provision_detalle/' + documento,
        destroy: true,
        scrollX: false,
        responsive: true,
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "columns": [
            {data: 'fechaproceso', className: "text-center"},
            {data: 'documento', className: "text-center"},
            {data: 'glosa', className: "text-center"},
            {data: 'saldomn', className: "text-center"},
            {data: 'saldome', className: "text-center"},
        ],
        order: [[1, 'asc']],
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
    });
}

function getchecked(table_name) {
    tabla = $('#'+table_name).DataTable();
    let detalle_select = [];
    let det = {};

    tabla.rows().every(function (){
        var data = this.node();
        if($(data).find('input').prop('checked')){
            det = {
                id:   $(data).find('input').serializeArray()[0].value,
                aplicar:   $(data).find('input').serializeArray()[1].value,
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}

function getProductschecked(table_name) {
    tabla = $('#'+table_name).DataTable();
    let detalle_select = [];
    let det = {};

    tabla.rows().every(function (){
        var data = this.node();
        if($(data).find('input').prop('checked')){
            det = {
                id:   $(data).find('input').serializeArray()[0].value,
                cantidad:   $(data).find('input').serializeArray()[1].value,
                precio:   $(data).find('input').serializeArray()[2].value,
                descuento:   $(data).find('input').serializeArray()[3].value,
            };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}

/*eliminación multiple
$("#btn_eliminar").click(function(){
    var ids = $(".check_register:checked").map(function(){
        return $(this).val();
    }).get();//.join(' ');
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "post",
        url: "/" + $("#var").val() + "/eliminar",
        data: {ids: ids},
        success: function (data) {
            if(data.success){
                listado = $('table').DataTable();
                listado.ajax.reload();
                success('success', data.success, 'Registro Eliminado!!');
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
});*/
/*
$("#check_all").change(function(){
    $(".check_register").prop("checked", $(this).prop("checked"));
});*/
/*------------------*/
/*eliminar registro*/
function eliminar_registro(id){
    swal.fire({
        title: '¿Eliminará el registro?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('#_token').val(),
                },
                type: "post",
                url: "/" + $("#var").val() + "/eliminar",
                data: {id: id},
                success: function (data) {
                    if(data.success){
                        success('success', data.success, 'Registro Eliminado!!');
                        if ($("#proceso").val() == 'edita'){
                            window.location.href = '/'+$("#var").val();
                        }else{
                            listado = $('table').DataTable();
                            listado.ajax.reload();
                        }
                    }
                },
                error: function (data) {
                    mostrar_errores(data);
                }
            });
        }
    });
}
function anular_registro(id, estado){
    var mensaje = '';
    if(estado == 0){
        mensaje = "El registro está anulado. Desea activarlo?";
    }else{
        mensaje = "Este registro será ANULADO. Está seguro?";
    }
    swal.fire({
        title: mensaje,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('#_token').val(),
                },
                type: "post",
                url: "/" + $("#var").val() + "/anular",
                data: {id: id},
                success: function (data) {
                    if(data.success){
                        listado = $('table').DataTable();
                        listado.ajax.reload();
                        success('success', data.success, 'Estado actualizado!!');
                        if ($("#proceso").val() == 'edita'){
                            location.reload();
                        }
                    }
                },
                error: function (data) {
                    mostrar_errores(data);
                }
            });
        }
    });
}
function anular_registro_mov(id){
    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "post",
        url: "/" + $("#var").val() + "/comprobar_estado",
        data: {id: id},
        success: function (data) {
            if(data.success){
                anular_registro_mov_enviar(id, data.success);
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function anular_registro_mov_enviar(id, mensaje){
    Swal.fire({
        title: mensaje + ". Motivo:",
        type: 'warning',
        input: 'text',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
        inputValidator: (value) => {
            if (!value) {
                return 'El motivo es obligatorio';
            }
        }
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('#_token').val(),
                },
                type: "post",
                url: "/" + $("#var").val() + "/anular",
                data: {id: id, motivo: result.value},
                success: function (data) {
                    if(data.success){
                        listado = $('table').DataTable();
                        listado.ajax.reload();
                        success('success', data.success, 'Estado actualizado!!');
                    }
                },
                error: function (data) {
                    mostrar_errores(data);
                }
            });
        }
    });
    /*.then(function (result) {
        if (result.value) {
            const motivo = JSON.stringify(result.value);
            if(motivo.length === 0){
                error('error', "Ingrese el motivo", 'Error!');
                return;
            }else{
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('#_token').val(),
                    },
                    type: "post",
                    url: "/" + $("#var").val() + "/anular",
                    data: {id: id, motivo: motivo},
                    success: function (data) {
                        if(data.success){
                            listado = $('table').DataTable();
                            listado.ajax.reload();
                            success('success', data.success, 'Estado actualizado!!');
                        }
                    },
                    error: function (data) {
                        mostrar_errores(data);
                    }
                });
            }
        }
    });*/
}

function anular_registro_mov_show(id){

    $.ajax({
        headers: {
            'X-CSRF-Token': $('#_token').val(),
        },
        type: "post",
        url: "/" + $("#var").val() + "/comprobar_estado",
        data: {id: id},
        success: function (data) {
            if(data.success){
                anular_registro_mov_enviar_show(id, data.success);
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });

}

function anular_registro_mov_enviar_show(id, mensaje){
    Swal.fire({
        title: mensaje + ". Motivo:",
        type: 'warning',
        input: 'text',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
        inputValidator: (value) => {
            if (!value) {
                return 'El motivo es obligatorio';
            }
        }
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('#_token').val(),
                },
                type: "post",
                url: "/" + $("#var").val() + "/anular",
                data: {id: id, motivo: result.value},
                success: function (data) {
                    if(data.success){
                        success('success', data.success, 'Estado actualizado!!');
                        refresh();
                    }
                },
                error: function (data) {
                    mostrar_errores(data);
                }
            });
        }
    });
}

function refresh() {
    setTimeout('document.location.reload()', 5000);
}
