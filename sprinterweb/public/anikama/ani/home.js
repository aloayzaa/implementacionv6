var accountingPeriod = function () {
    'use strict';

    return {
        init: function (route, currentPeriod) {
            $(document).ready(function () {
                var period = $('#accountingPeriod');

                //console.log(currentPeriod)
                $.ajax({
                    url: route,
                    type: 'get',
                    success: function (data) {
                        period.empty();
                        period.append('<option value="" disabled>-Seleccione-</option>');
                        for (var i = 0; i < data.length; i++) {
                            if (currentPeriod == data[i].descripcion) {
                                period.append('<option value="' + data[i].id + '"  selected>' + data[i].descripcion + '</option>');
                            } else {
                                period.append('<option value="' + data[i].id + '">' + data[i].descripcion + '</option>');
                            }
                        }
                    }
                });
            });
        }
    };
}();

var salesPoint = function () {
    'use strict';

    return {
        init: function (route, currentPoint) {
            $(document).ready(function () {
                var point = $('#salesPoint');
                var point2 = $('#txt_descripcionpv');

                //console.log(currentPeriod)
                $.ajax({
                    url: route,
                    type: 'get',
                    success: function (data) {
                        point.empty();
                        point2.empty();
                        point.append('<option value="">-NINGUNO-</option>');
                        point2.append('<option value="">-NINGUNO-</option>');
                        for (var i = 0; i < data.length; i++) {
                            if (currentPoint == data[i].descripcion) {
                                point.append('<option value="' + data[i].id + '"  selected>' + data[i].descripcion + '</option>');
                                point2.append('<option value="' + data[i].id + '"  selected>' + data[i].descripcion + '</option>');
                            } else {
                                point.append('<option value="' + data[i].id + '">' + data[i].descripcion + '</option>');
                                point2.append('<option value="' + data[i].id + '">' + data[i].descripcion + '</option>');
                            }
                        }
                    }
                });
            });
        }
    };
}();

$('#btnUpdate').on('click', function () {
    var route = $(this).data('route');
    var period = $('#accountingPeriod').val();

    $.ajax({
        url: route,
        type: 'get',
        data: '&period=' + period,
        success: function (data) {
            $('#modalPeriod').modal('hide');
            $('#titlePeriod').html(data);
        }
    });
});

$('#btnUpdatePoint').on('click', function () {
    var route = $(this).data('route');
    var point = $('#salesPoint').val();
    var date = $('#datePoint').val();
    $.ajax({
        url: route,
        type: 'get',
        data: '&point=' + point+'&date='+date,
        success: function (data) {
            $('#modalPoint').modal('hide');
            $('#titlePoint').html(data);
            window.location.reload();
        }
    });
});


var graphValuesMonth = function () {
    'use strict';

    return {
        init: function (route) {

            $.ajax({
                url: route,
                type: 'get',
                success: function (data) {
                    google.charts.load('current', {'packages': ['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Mes', 'Soles'],
                            ['Mes', 1],
                            ['Mes', 1],
                            ['Mes', 1],
                        ]);

                        var options = {
                            title: 'Ventas mensuales',
                            // hAxis: {title: 'Year', titleTextStyle: {color: '#333'}},
                            vAxis: {minValue: 0}
                        };

                        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                    }
                }
            });
        }
    };
}();
