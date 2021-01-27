$("#hourlycost").change(function () {
    var value = this.value;
    $("#hourlycost").val(parseFloat(value).toFixed(2));
});


function guardar() {
    store()
}
function actualizar() {
    update()

}


