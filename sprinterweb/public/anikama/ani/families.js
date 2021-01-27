$(document).ready(function () {
    var variable = $("#var").val();
    $("#cbo_pro_sunat").select2({
        placeholder: "Seleccionar...",
        minimumInputLength: 2,
        multiple: false,
        width: 400,
        ajax: {
            url: "/" + variable + "/producto_sunat",
            data: function(term) {
                return term;
            },
            processResults: function(data) {
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
});
function guardar() {
   store()
}
function actualizar() {
   update()

}

