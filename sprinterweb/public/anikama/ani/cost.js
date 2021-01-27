$("#height").change(function () {
    var value = this.value;
    $("#height").val(parseFloat(value).toFixed(2));

    var width = $("#width").val();

    var total = value * width;

    $("#area").val(total.toFixed(2));
});

$("#width").change(function () {
    var value = this.value;
    $("#width").val(parseFloat(value).toFixed(2));

    var height = $("#height").val();

    var total = value * height;

    $("#area").val(total.toFixed(2));
});


function guardar() {
    store()
}
function actualizar() {
    update()

}

