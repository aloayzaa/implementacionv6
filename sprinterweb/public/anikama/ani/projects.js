$("#chkconfig").change(function () {
    var value = checkbox(this.id);

    if (value === 1) {
        $("#account").prop("disabled", false);
        $("#chargeaccount").prop("disabled", false);
        $("#paymentaccount").prop("disabled", false);
    } else if (value === 0) {
        $("#account").prop("disabled", true);
        $("#chargeaccount").prop("disabled", true);
        $("#paymentaccount").prop("disabled", true);
    }
});
