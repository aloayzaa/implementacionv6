$("#reporte").click(function () {
    var bank = $("#bank").val();
    var idate = $("#initialdate").val();
    var fdate = $("#finaldate").val();
    var currency = $("#currency").val();
    tableListConsolidatedBank.init(this.value + '?bank=' + bank + '&idate=' + idate + '&fdate=' + fdate + '&currency=' + currency);
});
