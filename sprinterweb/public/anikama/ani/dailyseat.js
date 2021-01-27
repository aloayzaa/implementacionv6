var table =  $('#table-detail-dailyseat').DataTable({
    serverSide: true,
    ajax: '/AsientoDiario/listar_carrito',
    //  responsive: true,
    destroy: true,
    scrollX: true,
    "scrollY":        "380px",
    "scrollCollapse": true,
    "paging":         false,
    "searching":         false,
    columnDefs: [
        {
            "targets": 1,
            "visible": false,
            "searchable": false
        },
    ],
    "columns": [
        {
            "sName": "Index",
            "render": function (data, type, row, meta) {
                return meta.row+1; // This contains the row index
            },
            "orderable": "false",
            "className": "text-center",
        },
        {data: 'options.item'},

        {data: 'options.cuenta', "className": "text-center",
            "render": function ( data, type, full, meta ) {
                return `<a onclick="cuenta(${full.options.cuenta_id})">${data == null ? '' : data}</a>`;
            },
        },
        {data: 'options.CCosto_codigo', "className": "text-center"},
        {data: 'options.CCosto_desc', "className": "text-center"},

        {data: 'options.cargomn', "className": "text-center",
            "render": function (data) {
                return data == 0.00 ? '' : data;
            },
        },
        {data: 'options.abonomn', "className": "text-center",
            "render": function (data) {
                return data == 0.00 ? '' : data;
            },
        },
        {data: 'options.cargome', "className": "text-center",
            "render": function (data) {
                return data == 0.00 ? '' : data;
            },
        },
        {data: 'options.abonome', "className": "text-center",
            "render": function (data) {
                return data == 0.00 ? '' : data;
            },
        },

        {data: 'options.glosa', "className": "text-center"},
        {data: 'options.codigo', "className": "text-center"},
        {data: 'options.nombre', "className": "text-center"},
        {data: 'options.codigo_ref',
            "render": function ( data, type, full, meta ) {
                return `<a onclick="asiento_referencia('${full.options.tablareferencia}', '${full.options.referencia_id}')">${data == null ? '' : data}</a>`;
            },
        },

        {data: 'options.producto_codigo', "className": "text-center"},
        {data: 'options.producto_desc', "className": "text-center"},

    ],
    order: [[ 1, 'asc' ]],

    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // Total over all pages
        cargomn = api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        abonomn = api
            .column( 6 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );


        cargome = api
            .column( 7 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        abonome = api
            .column( 8 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );


        // Update footer
        $( api.column( 2 ).footer() ).html(
            'Total M.N. : '+ redondea(cargomn, 2) + ' - ' + redondea(abonomn, 2)
        );
        $( api.column( 5 ).footer() ).html(
            'Total M.E. : '+ redondea(cargome,2) + ' - ' + redondea(abonome,2)
        );
    },
    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
});

