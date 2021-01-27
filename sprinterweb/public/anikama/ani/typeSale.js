/*
$('#deferred').on('change', function () {
    var document = $('#document');
    var divDocument = $('#divDocument');
    if( $(this).prop('checked') ) {
        divDocument.show();
        document.prop('disabled', false);
    }else{
        document.prop('disabled', 'disabled');
        divDocument.hide();
    }
});
*/
$().ready(function () {
    if ($("#deferred").prop('checked')){
        $("#document").prop('disabled', false);
    }else{
        $("#document").prop('disabled', true);
    }
});
$("#deferred").on('change',function () {
    if ($(this).prop('checked')){
        $("#document").prop('disabled', false);
    }else{
        $("#document").prop('disabled', true);
    }
});


function guardar() {
    store()
}
function actualizar() {
    update()
}

