$("#reporte").click(function () {
    var bank = $("#bank").val();
    var idate = $("#initialdate").val();
    var fdate = $("#finaldate").val();
    tableListIssuedChecks.init(this.value + '?bank=' + bank + '&idate=' + idate + '&fdate=' + fdate);
});
