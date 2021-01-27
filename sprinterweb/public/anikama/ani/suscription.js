function precio(id) {
    variable = $("#var").val();
    token = $("#_token").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': token},
        type: "POST",
        url: "/" + variable + "/plan/" + id,
        data: $("#frm_generales").serialize(),
        success: function (data) {
            $("#importe").val('$'+data);
        }
    });
}


