function company_check(ruc) {
    var token = $("#_token").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "POST",
        url: "/company_check",
        dataType: "JSON",
        data: '&ruc=' + ruc,
        success: function (data) {
            if(data === 1){
                warning("warning", "La empresa ya esta registrada", "Advertencia");
                $("#btn_grabar").attr("disabled", true);
                $("#btn_grabar").fadeOut("slow");
            }else{
                $("#btn_grabar").removeAttr("disabled");
                $("#btn_grabar").fadeIn("slow");
            }
        }
    });
}
