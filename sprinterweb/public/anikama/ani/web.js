$(document).ready(function () {
    var url = window.location.href;
    var ruc = url.substring(30);
    rucsession(ruc);
});

function rucsession(ruc){
    $.ajax({
        type: "get",
        url: "/web/rucsession",
        data: '&ruc=' + ruc,
        success: function (data) {
            //NOBORRAR
            // console.log(data);
        }
    });
}

$('#family').on('change', function () {
    var family = $(this).val();
    var route = $(this).data('route');
    var mark = $('#mark');
    var model = $('#model');
    var ruc = $('#ruc').val();

    $.ajax({
        type: "get",
        url: route,
        data: '&family=' + family + '&ruc=' + ruc,
        success: function (data) {
            if (!$.isEmptyObject(data)) {
                $('#listProduct').html(data.products);
                mark.prop("disabled", false);
                mark.empty();
                model.empty();
                if (!$.isEmptyObject(data.marks)) {
                    mark.append('<option value="" selected disabled>-Seleccione-</option>');
                    for (var i = 0; i < data.marks.length; i++) {
                        mark.append('<option value="' + data.marks[i].id + '">' + data.marks[i].descripcion + '</option>');
                    }
                }
            } else {
                $('#listProduct').html('<div class="col-lg-12"> No existen productos asociados </div><br><br><br><br></div><br><br><br><br></div><br><br><br><br></div><br><br><br><br><br> ');
            }
        }
    });
});

$('#mark').on('change', function () {
    var mark = $(this).val();
    var route = $(this).data('route');
    var family = $('#family').val();
    var model = $('#model');
    var ruc = $('#ruc').val();

    $.ajax({
        type: "get",
        url: route,
        data: '&mark=' + mark + '&family=' + family + '&ruc=' + ruc,
        success: function (data) {
            if (!$.isEmptyObject(data.products)) {
                $('#listProduct').html(data.products);
                model.prop("disabled", false);
                model.empty();
                model.append('<option value="" selected disabled>-Seleccione-</option>');
                for (var i = 0; i < data.models.length; i++) {
                    model.append('<option value="' + data.models[i].id + '">' + data.models[i].descripcion + '</option>');
                }
            } else {
                $('#listProduct').html('<div class="col-lg-12"> No existen productos asociados </div><br><br><br><br></div><br><br><br><br></div><br><br><br><br></div><br><br><br><br><br> ');
            }
        }
    });
});

$('#model').on('change', function () {
    var model = $(this).val();
    var route = $(this).data('route');
    var family = $('#family').val();
    var mark = $('#mark').val();
    var ruc = $('#ruc').val();

    $.ajax({
        type: "get",
        url: route,
        data: '&model=' + model + '&mark=' + mark + '&family=' + family + '&ruc=' + ruc,
        success: function (data) {
            if (!$.isEmptyObject(data.products)) {
                $('#listProduct').html(data.products);
            } else {
                $('#listProduct').html('<div class="col-lg-12"> No existen productos asociados </div><br><br><br><br></div><br><br><br><br></div><br><br><br><br></div><br><br><br><br><br> ');
            }
        }
    });
});
