
async function email_check(){
    cleanSelector('periodo', 'Seleccione un Periodo');
    cleanSelector('empresa', 'Seleccione una Empresa');
    usuario = $("#usu_correo").val();
    user = await isAdmin(usuario);

    if(user == 1){
        $( "#empresa" ).replaceWith("<select id='empresa' class='form-control' name='empresa'><option value='' selected>Seleccione una Empresa </option></select>");
        getEmpresas(usuario);
    }else{
        $( "#empresa" ).replaceWith("<input id='empresa' type='text' class='form-control' placeholder='Ingrese el Ruc de la Empresa' name='empresa' />");
    }
}

$("#periodo").focus(function () {
    empresa = $("#empresa").val();
    getperiodos(empresa)
});


function isAdmin(usuario) {
   return $.ajax({
        type: "get",
        url: "/isAdmin",
        dataType: "JSON",
        data: {email: usuario},
    });
}


function getEmpresas(usuario) {
    var token = $("#_token").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "POST",
        url: "/companies",
        dataType: "JSON",
        data: {usu_correo: usuario},
        success: function (data) {
            if (data) {
            //    $("#empresa").focus();
                for (var i = 0; i < data.length; i++) {
                    $('#empresa').append('<option value="' + data[i].ruc + '">' + data[i].name + '</option>');
                }
            }
        }
    });
}


function getperiodos(ruc) {
    var token = $("#_token").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "post",
        url: "/getperiods",
        dataType: "JSON",
        data: {ruc: ruc},
        success: function (data) {
            if (data) {
                $('#periodo').empty();
                for (var i = 0; i < data.length; i++) {
                    $('#periodo').append('<option value="' + data[i].id + '">' + data[i].descripcion + '</option>');
                }
            }
        },
        error: function (data) {
            mostrar_errores(data);
            $('#empresa').focus();
        }
    });
}

function login() {
    var ruta = $("#ruta").val();
    var form = $("#form_login").serialize();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").val()},
        type: "POST",
        url: ruta,
        data: form,
        success: function (data) {
            if(data.ruta){
                window.location.replace(data.ruta);
            }
            else{
                error('error',data.error, 'Error!');
            }
        },
        error: function (data) {
            mostrar_errores(data);
        }
    });
}

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

function cleanSelector(selector, mensaje) {
    $("#"+selector).empty().append('<option disabled selected>-- '+mensaje+' --</option>');
}
