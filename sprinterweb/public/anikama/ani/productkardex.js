$("#btn_showData").click(function (e) {
    e.preventDefault();
    var desde = $("#txt_desde").val();
    var hasta = $("#txt_hasta").val();
    var product_id = $("#product_id").val();
    var form = $("#form_kardex").serialize();

    console.log(form)
    tableProductKardex.init('productkardex/list' + "?product_id=" + product_id + "&txt_desde=" + desde + "&txt_hasta=" +hasta);
});



