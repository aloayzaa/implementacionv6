$("#btnmostrar").click(function () {
    var account = $("#account").val();
    var initialdate = $("#initialdate").val();
    var finishdate = $("#finishdate").val();

    tableAccountMovement.init(this.value + '?account=' + account + '&initialdate=' + initialdate + '&finishdate=' + finishdate);
});
