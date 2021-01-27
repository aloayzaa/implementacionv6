$("#frm_generales").validate({
    ignore: "input[type='text']:hidden",
    rules: {
        code: {
            required: true
        },
    }
});
