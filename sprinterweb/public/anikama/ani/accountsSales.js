$(document).ready(function(){
    var variable = $('#condiciones').val();
    var variable2 = $('#condiciones2').val();
    for (var i = 0; i < variable; i++){
        select_cuentas('N'+i,'buscar_accounts');
        select_cuentas('E'+i,'buscar_accounts');
    }
    for (var i = 0; i < variable2; i++){
        select_cuentas('N2'+i,'buscar_accounts');
        select_cuentas('E2'+i,'buscar_accounts');
    }
});
function select_cuentas(elemento,ruta){
    var variable = $("#var").val();
    $("#"+elemento).select2({
        placeholder: "-Seleccionar-",
        minimumInputLength: 2,
        multiple: false,
        //width: 400,
        ajax: {
            url: "/" + variable + "/"+ ruta,
            data: function(term) {
                return term;
            },
            processResults: function(data) {
                $("#"+elemento).val("").trigger('change');
                return {
                    results: $.map(data, function(obj) {
                        return {
                            id: obj.id,
                            text: obj.codigo +' | '+ obj.descripcion
                        };
                    })
                };
            }
        }
    });
}

function guardar() {
    store()
}
function actualizar() {
    update()
}

