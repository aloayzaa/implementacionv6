$('#checkretention').click(function () {
    valida_chk_retencion(this.id);
});

function valida_chk_retencion(id) {
    var value = checkbox(id);
    if (value === 1) {
        $("#retentionbase").attr('readonly', false);
        $("#calculationbase").attr('disabled', false);
        $("#retentiontype").attr('disabled', false);
        $("#retentionname").attr('disabled', false);
    } else {
        $("#retentionbase").attr('readonly', true);
        $("#calculationbase").attr('disabled', true);
        $("#retentiontype").attr('disabled', true);
        $("#retentionname").attr('disabled', true);
        $("#retentionbase").val('');
        $("#retentiontype").val(0).change();
        $("#retentionname").val(0).change();
        $("#calculationbase").val(0).change();
    }
}

$('#checkbase').change(function () {
    valida_chk_resta_base(this.id);
});

function valida_chk_resta_base(id) {
    var value = checkbox(id);
    if (value === 1) {
        $("#pension").attr('disabled', false);
    } else {
        $("#pension").val(0).change();
        $("#pension").attr('disabled', true);
    }
}
