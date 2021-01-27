$("#btnmostrar").click(function () {
    var initialdate = $("#initialdate").val();
    var finishdate = $("#finishdate").val();

    tableSeatValidation.init(this.value + '?initialdate=' + initialdate + '&finishdate=' + finishdate);
});
