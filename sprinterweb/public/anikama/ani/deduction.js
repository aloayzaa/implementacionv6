$("#frm_generales").validate({
    ignore: "input[type='text']:hidden",
    rules: {
        txt_codigo_tercero: {
            required: true
        },
    }
});
