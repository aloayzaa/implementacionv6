

function busca_ruc() {
    empresa =  $('#txt_empresa').val('');
    var ruc = $('#txt_ruc').val();
    var token = $("#_token").val();
    $.ajax({
        headers: {
           'X-CSRF-TOKEN': token,
        },
        type: "post",
        url: "/consumer/consultar_ruc_contribuyente/" + ruc,
        dataType: "json",
        success: function (res) {
            console.log(res)
            if (res.data.length > 0) {
                $("#txt_empresa").val(res.data[0].emp_descripcion);
                $("#txt_address").val(res.data[0].emp_tipo_via + " " + res.data[0].emp_nombre_via +" NRO "+  res.data[0].emp_numero +" INT"+ res.data[0].emp_interior +" URB."+ res.data[0].emp_tipo_zona);
                $("#txt_ubigeo").val(res.data[0].emp_ubigeo);
            }else if(res.message){
                error("error", "No Autorizado", "Services");
            } else {
                error("error", "Empresa no existe en la base de datos de SUNAT", "Error");
            }
        }
    });
}

function guardar() {
    store();
}
function actualizar() {
    update();
}
