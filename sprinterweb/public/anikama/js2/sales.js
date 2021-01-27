//funciones generales de venta para el calculo del detalle principal importe, descuento, precio unitario ||
var grupo_ruta = $("#var").val();

function precio(data) {
    switch (data.precioventa) {
        case "1":
            let valor = data.precio1;
            if (data.dsctos) {
                valor = data.precio;
            }
            return cambiar_precio(data, valor);
            break;
        case "2":
            let valor1 = data.precio2;
            if (data.dsctos) {
                valor1 = data.precio;
            }
            return cambiar_precio(data, valor1);
            break;
        case "3":
            let valor2 = data.precio3;
            if (data.dsctos) {
                valor2 = data.precio;
            }
            return cambiar_precio(data, valor2);
            break;
        case "4":
            let valor3 = data.precio4;
            if (data.dsctos) {
                valor3 = data.precio;
            }
            return cambiar_precio(data, valor3);
            break;
        case "5":
            let valor4 = data.precio5;
            if (data.dsctos) {
                valor4 = data.precio;
            }
            return cambiar_precio(data, valor4);
            break;
        case "6":
            let valor5 = data.precio6;
            if (data.dsctos) {
                valor5 = data.precio;
            }
            return cambiar_precio(data, valor5);
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function cambiar_precio(data, precio) {
    switch (data.editar_precio) {
        case "0":
            return '<input type="number" id="precio" name="precio" class="form-control text-right width-75 precio' + data.id + '" value="' + parseFloat(precio).toFixed(2) + '" onblur="modificar_carrito(' + data.id + ', ' + data.contador +')">';
            break;
        case "1":
            return '<input type="number" id="precio" name="precio" class="form-control text-right width-75 precio' + data.id + '" value="' + parseFloat(precio).toFixed(2) + '" onblur="modificar_carrito(' + data.id + ', ' + data.contador +')">';
            break;
        case "2":
            return '<input type="number" id="precio" name="precio" class="form-control text-right width-75" value="' + parseFloat(precio).toFixed(2) + '" readonly>';
            break;
    }
}

function pideserie(data) {
    let valor = '';
    if (data.dsctos) {
        valor = data.serie;
    }
    switch (data.pideserie) {
        case 0:
            return '<input type="text" id="serie" name="serie" value="' + valor + '" class="form-control  text-right width-75 serie" readonly>';
            break;
        case "1":
            return '<input type="text" id="serie" name="serie" value="' + valor + '" class="form-control  text-right width-75 serie' + data.id + '" onblur="valida_serie(' + data.id + ')">';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function cantidad_pideserie(data) {

    let valor = 1;
    if (data.dsctos) {
        valor = data.cantidad;
    }
    switch (data.pideserie) {
        case 0:
            return '<input type="number" id="cantidad" name="cantidad" value="' + valor + '" class="form-control  text-right width-75 cantidad' + data.id + '" value="1" onblur="modificar_carrito('+data.id+')">';
            break;
        case "1":
            return '<input type="numer" id="cantidad" name="cantidad" class="form-control  text-right width-75 cantidad" value="' + valor + '" readonly>';
            break;
        default:
            return '<span class="label bg-red">ERROR</span>';
            break;
    }
}

function valida_descuento(id, contador) {
    let descuento = $(".descuento" + id).val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/valida_descuento/" + descuento,
        success: function (data) {
            if (data.success) {success('success', data.success, 'Información');}
            $(".descuento" + id).val(data.descuento);
            modificar_carrito(id, contador);
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

function valida_serie(id) {
    let serie = $(".serie" + id).val();
    let punto_venta = $("#punto_venta").val();
    let fecha_proceso = $("#fecha_proceso").val();
    let producto_id = id;
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/valida_serie/?serie=" + serie + "&punto_venta=" + punto_venta + "&fecha_proceso=" + fecha_proceso + "&producto_id=" + producto_id,
        success: function (data) {
            (data !== null) ? $(".serie" + id).val(data) : $(".serie" + id).val('');
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}
/*
function valida_precio(id) {
    //carrito principal
    let precio = $(".precio" + id).val();
    let descuento = $(".descuento" + id).val();
    let calculo_precio_dsct = parseFloat(precio).toFixed(6) - ((parseFloat(precio).toFixed(6) * parseFloat(descuento).toFixed(2)) / 100);
    let importe = parseFloat($(".cantidad" + id).val()).toFixed() * calculo_precio_dsct;
    $(".precio_dscto" + id).val(calculo_precio_dsct);
    $(".importe" + id).val(importe);
}

function valida_cantidad_stock(id, stock, contador) {
    let cantidad = $(".cantidad" + id).val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/valida_cantidad/" + cantidad + "/" + stock,
        success: function (data) {
            if (data.success) {
                success('success', data.success, 'Información');
                $(".checkbox" + id).prop('checked', false);
                cantidad = $(".cantidad" + id).val(data.cantidad);
            }
            if (grupo_ruta == 'pointofsale'){
                modificar_carrito(id, contador);
            }
            /!*if (grupo_ruta == 'pointofsale'){
                modificar_carrito(id);
            }*!/
            //carrito principal
            let precio = $(".precio_dscto" + id).val();
            let calculo = parseFloat(cantidad, 2).toFixed(2) * parseFloat(precio).toFixed(2);
            $(".importe" + id).val(calculo);
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}*/

function valida_stock(id, contador) {
    let cantidad = $(".cantidad"+id).val();
    let tventa = $('select[id=txt_descripciontv]').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/" + grupo_ruta + "/valida_stock",
        data:{producto_id: id, cantidad: cantidad, contador: contador, tventa: tventa},
        success: function (data) {
            if (data.success) {
                success('success', data.success, 'Información');
                $(".cantidad" + id).val(data.cantidad);
                $(".checkbox" + id).prop('checked', false);
            }
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

$('#producto_codigo').keyup(function (event) {
    if(event.keyCode == 9){
        limpiar_inputs_busqueda_producto();
        bucar_producto();
    }
});
$('#producto_descripcion').keyup(function (event) {
    if(event.keyCode == 9) {
        limpiar_inputs_busqueda_producto();
        bucar_producto();
    }
});

function limpiar_inputs_busqueda_producto(){
    let codigo = $('#producto_codigo').val();
    if (codigo !== ''){
        $("#producto_descripcion").val("");
        $("#producto_presentacion").val("");
    }
}
