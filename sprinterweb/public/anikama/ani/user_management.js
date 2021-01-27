//----------Load Users--------------
var loadUser = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#loaduser').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var masterproducts = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#maestrosproductos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var mastercustomer = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#maestrosterceros').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var mastercosts = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#maestroscostos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var masterothers = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#maestrosotros').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var shoppingconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#comprasconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var shoppingprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#comprasprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var shoppingreports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#comprasreportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var shoppingtransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#comprastransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var salesconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ventasconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var salesprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ventasprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var salesreports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ventasreportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var salestransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ventastransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var warehouseconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#logisticaconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var warehouseprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#logisticaprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var warehousereports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#logisticareportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var warehousetransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#logisticatransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var treasuryconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tesoreriaconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var treasuryprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tesoreriaprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var treasuryreports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tesoreriareportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var treasurytransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tesoreriatransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var accountingconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#contabilidadconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var accountingprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#contabilidadprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var accountingreports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#contabilidadreportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var accountingtransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#contabilidadtransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var specialaccounts = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#librocajabancos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var specialaccounts2 = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#inventariosybalances').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var tributaryconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tributosconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var tributaryprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tributosprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var tributaryreports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tributosreportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var tributarytransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tributostransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var activeconfigurations = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#activosconfiguracion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var activeprocess = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#activosprocesos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var activereports = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#activosreportes').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var activetransaccions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#activostransacciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var utilitarianoptions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#utilitarioopciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    rowId: 'id',
                    "columns": [
                        {
                            'render': function (data, type, row, meta) {
                                return '' + row.descripcion + '<input type="hidden" name="menu_id[' + row.id + ']" value="' + row.id + '" class="menu_id">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="consulta[' + row.id + ']" class="consulta' + row.id + ' consulta">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="crea[' + row.id + ']" class="crea' + row.id + ' crea">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="edita[' + row.id + ']" class="edita' + row.id + ' edita">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="anula[' + row.id + ']" class="anula' + row.id + ' anula">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="borra[' + row.id + ']" class="borra' + row.id + ' borra">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="imprime[' + row.id + ']" class="imprime' + row.id + ' imprime">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="aprueba[' + row.id + ']" class="aprueba' + row.id + ' aprueba">';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="precio[' + row.id + ']" class="precio' + row.id + ' precio">';
                            }
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

$('#configuracion').click(function () {
    var id = $("#id").val();
    var descripcion = $("#descripcion").val();
    procesar(id);
    $("#usuario_id").val(id);
    $("#p_usuario").val(descripcion);
    $('#modal_add').modal('show');
});
$("#cancelar").click(function () {
    location.reload();
});
$("#aceptar").click(function () {
    abre_loading();
    var usuario_id = $("#usuario_id").val();
    var _token = $("#_token").val();
    var proceso = $("#proceso").val();
    var variable = $("#var").val();
    var maestros = $('.maestros .menu_id, .maestros .crea, .maestros .edita, .maestros .consulta, .maestros .anula, .maestros .borra, .maestros .imprime, .maestros .aprueba, .maestros .precio').serialize();
    var compras = $('.compras .menu_id, .compras .crea, .compras .edita, .compras .consulta, .compras .anula, .compras .borra, .compras .imprime, .compras .aprueba, .compras .precio').serialize();
    var ventas = $('.ventas .menu_id, .ventas .crea, .ventas .edita, .ventas .consulta, .ventas .anula, .ventas .borra, .ventas .imprime, .ventas .aprueba, .ventas .precio').serialize();
    var logistica = $('.logistica .menu_id, .logistica .crea, .logistica .edita, .logistica .consulta, .logistica .anula, .logistica .borra, .logistica .imprime, .logistica .aprueba, .logistica .precio').serialize();
    var tesoreria = $('.tesoreria .menu_id, .tesoreria .crea, .tesoreria .edita, .tesoreria .consulta, .tesoreria .anula, .tesoreria .borra, .tesoreria .imprime, .tesoreria .aprueba, .tesoreria .precio').serialize();
    var contabilidad = $('.contabilidad .menu_id, .contabilidad .crea, .contabilidad .edita, .contabilidad .consulta, .contabilidad .anula, .contabilidad .borra, .contabilidad .imprime, .contabilidad .aprueba, .contabilidad .precio').serialize();
    var tributos = $('.tributos .menu_id, .tributos .crea, .tributos .edita, .tributos .consulta, .tributos .anula, .tributos .borra, .tributos .imprime, .tributos .aprueba, .tributos .precio').serialize();
    var activos = $('.activos .menu_id, .activos .crea, .activos .edita, .activos .consulta, .activos .anula, .activos .borra, .activos .imprime, .activos .aprueba, .activos .precio').serialize();
    var utilitarios = $('.utilitarios .menu_id, .utilitarios .crea, .utilitarios .edita, .utilitarios .consulta, .utilitarios .anula, .utilitarios .borra, .utilitarios .imprime, .utilitarios .aprueba, .utilitarios .precio').serialize();
    var array = [maestros,compras,ventas,logistica,tesoreria,contabilidad,tributos,activos,utilitarios];
    var mensaje = ['Mestros', 'Compras', 'Ventas', 'Almecenes', 'Tesoreria', 'Contabilidad', 'Tributaria', 'Activos', 'Utilitarios'];
    for (i= 0; i<array.length;i++){
        $.ajax({
            headers: {'X-CSRF-TOKEN': _token},
            type: "post",
            url: "/" + variable + "/" + 'update_privilegios',
            data: '_token='+_token+'&proceso='+proceso+ '&var='+variable+'&usuario_id='+usuario_id+"&"+array[i]+"&mensaje="+mensaje[i],
            success: function (data) {
                if (data.success){
                    success('success', data.success, 'Información');
                    cierra_loading();
                    $('#modal_add').modal('hide');
                }
            },
            error: function (data) {
                mostrar_errores(data);
            }
        });
    }
});

$("#cargar_usuarios").click(function () {
    $('#modal_cargar_usuario').modal('show');
});
$('#loaduser tbody').on('dblclick', 'tr', function () {
    procesar(this.id);
    $('#modal_cargar_usuario').modal('hide');
});

function procesar(id) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "get",
        url: "/user_management/loaddata",
        data: {id: id},
        success: function (data) {
            if (typeof  data.aconfiguracion[0] === 'undefined'){
                $(".consulta").prop("checked", false);
                $(".crea").prop("checked", false);
                $(".edita").prop("checked", false);
                $(".anula").prop("checked", false);
                $(".borra").prop("checked", false);
                $(".imprime").prop("checked", false);
                $(".aprueba").prop("checked", false);
                $(".precio").prop("checked", false);
            }
            else{
                //activos
                for (i=0;i<data.aconfiguracion.length;i++){
                    var result = data.aconfiguracion[i];
                    if (result.consulta == 1){$("#activosconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#activosconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#activosconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#activosconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#activosconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#activosconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#activosconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#activosconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#activosconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.aprocesos.length;i++){
                    var result = data.aprocesos[i];
                    if (result.consulta == 1){$("#activosprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#activosprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#activosprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#activosprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#activosprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#activosprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#activosprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#activosprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#activosprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.areportes.length;i++){
                    var result = data.areportes[i];
                    if (result.consulta == 1){$("#activosreportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#activosreportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#activosreportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#activosreportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#activosreportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#activosreportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#activosreportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#activosreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#activosreportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.atransacciones.length;i++){
                    var result = data.atransacciones[i];
                    if (result.consulta == 1){$("#activostransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#activostransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#activostransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#activostransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#activostransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#activostransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#activostransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#activostransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#activostransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //compras
                for (i=0;i<data.cconfiguracion.length;i++){
                    var result = data.cconfiguracion[i];
                    if (result.consulta == 1){$("#comprasconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#comprasconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#comprasconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#comprasconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#comprasconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#comprasconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#comprasconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#comprasconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#comprasconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.cprocesos.length;i++){
                    var result = data.cprocesos[i];
                    if (result.consulta == 1){$("#comprasprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#comprasprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#comprasprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#comprasprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#comprasprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#comprasprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#comprasprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#comprasprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#comprasprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.creportes.length;i++){
                    var result = data.creportes[i];
                    if (result.consulta == 1){$("#comprasreportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#comprasreportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#comprasreportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#comprasreportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#comprasreportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#comprasreportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#comprasreportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#comprasreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#comprasreportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.ctransacciones.length;i++){
                    var result = data.ctransacciones[i];
                    if (result.consulta == 1){$("#comprastransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#comprastransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#comprastransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#comprastransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#comprastransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#comprastransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#comprastransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#comprastransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#comprastransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //contabilidad
                for (i=0;i<data.coconfiguracion.length;i++){
                    var result = data.coconfiguracion[i];
                    if (result.consulta == 1){$("#contabilidadconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#contabilidadconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#contabilidadconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#contabilidadconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#contabilidadconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#contabilidadconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#contabilidadconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#contabilidadconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.coprocesos.length;i++){
                    var result = data.coprocesos[i];
                    if (result.consulta == 1){$("#contabilidadprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#contabilidadprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#contabilidadprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#contabilidadprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#contabilidadprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#contabilidadprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#contabilidadprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#contabilidadprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.coreportes.length;i++){
                    var result = data.coreportes[i];
                    if (result.consulta == 1){$("#contabilidadreportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#contabilidadreportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#contabilidadreportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#contabilidadreportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#contabilidadreportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#contabilidadreportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#contabilidadreportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#contabilidadreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadreportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.cotransacciones.length;i++){
                    var result = data.cotransacciones[i];
                    if (result.consulta == 1){$("#contabilidadtransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#contabilidadtransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#contabilidadtransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#contabilidadtransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#contabilidadtransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#contabilidadtransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#contabilidadtransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#contabilidadtransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#contabilidadtransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                /*contabilidad especiales*/
                for (i=0;i<data.creportesespecial.length;i++){
                    var result = data.creportesespecial[i];
                    if (result.consulta == 1){$("#librocajabancos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#librocajabancos .crea"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#librocajabancos .edita"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#librocajabancos .anula"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#librocajabancos .borra"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#librocajabancos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#librocajabancos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#librocajabancos .precio"+result.menu_id+"").prop("checked", true);}else{$("#librocajabancos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.creportesespecial2.length;i++){
                    var result = data.creportesespecial2[i];
                    if (result.consulta == 1){$("#inventariosybalances .consulta"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#inventariosybalances .crea"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#inventariosybalances .edita"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#inventariosybalances .anula"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#inventariosybalances .borra"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#inventariosybalances .imprime"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#inventariosybalances .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#inventariosybalances .precio"+result.menu_id+"").prop("checked", true);}else{$("#inventariosybalances .precio"+result.menu_id+"").prop("checked", false);}
                }
                /**/
                //logistica
                for (i=0;i<data.lconfiguracion.length;i++){
                    var result = data.lconfiguracion[i];
                    if (result.consulta == 1){$("#logisticaconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#logisticaconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#logisticaconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#logisticaconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#logisticaconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#logisticaconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#logisticaconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#logisticaconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#logisticaconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.lprocesos.length;i++){
                    var result = data.lprocesos[i];
                    if (result.consulta == 1){$("#logisticaprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#logisticaprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#logisticaprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#logisticaprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#logisticaprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#logisticaprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#logisticaprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#logisticaprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#logisticaprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.lreportes.length;i++){
                    var result = data.lreportes[i];
                    if (result.consulta == 1){$("#logisticareportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#logisticareportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#logisticareportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#logisticareportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#logisticareportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#logisticareportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#logisticareportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#logisticareportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#logisticareportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.ltransacciones.length;i++){
                    var result = data.ltransacciones[i];
                    if (result.consulta == 1){$("#logisticatransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#logisticatransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#logisticatransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#logisticatransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#logisticatransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#logisticatransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#logisticatransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#logisticatransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#logisticatransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //maestros
                for (i=0;i<data.mproductos.length;i++){
                    var result = data.mproductos[i];
                    if (result.consulta == 1){$("#maestrosproductos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#maestrosproductos .crea"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#maestrosproductos .edita"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#maestrosproductos .anula"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#maestrosproductos .borra"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#maestrosproductos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#maestrosproductos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#maestrosproductos .precio"+result.menu_id+"").prop("checked", true);}else{$("#maestrosproductos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.mcostos.length;i++){
                    var result = data.mcostos[i];
                    if (result.consulta == 1){$("#maestroscostos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#maestroscostos .crea"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#maestroscostos .edita"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#maestroscostos .anula"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#maestroscostos .borra"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#maestroscostos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#maestroscostos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#maestroscostos .precio"+result.menu_id+"").prop("checked", true);}else{$("#maestroscostos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.motros.length;i++){
                    var result = data.motros[i];
                    if (result.consulta == 1){$("#maestrosotros .consulta"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#maestrosotros .crea"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#maestrosotros .edita"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#maestrosotros .anula"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#maestrosotros .borra"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#maestrosotros .imprime"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#maestrosotros .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#maestrosotros .precio"+result.menu_id+"").prop("checked", true);}else{$("#maestrosotros .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.mterceros.length;i++){
                    var result = data.mterceros[i];
                    if (result.consulta == 1){$("#maestrosterceros .consulta"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#maestrosterceros .crea"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#maestrosterceros .edita"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#maestrosterceros .anula"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#maestrosterceros .borra"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#maestrosterceros .imprime"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#maestrosterceros .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#maestrosterceros .precio"+result.menu_id+"").prop("checked", true);}else{$("#maestrosterceros .precio"+result.menu_id+"").prop("checked", false);}
                }
                //tesoreria
                for (i=0;i<data.tconfiguracion.length;i++){
                    var result = data.tconfiguracion[i];
                    if (result.consulta == 1){$("#tesoreriaconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tesoreriaconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tesoreriaconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tesoreriaconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tesoreriaconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tesoreriaconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tesoreriaconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tesoreriaconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.tprocesos.length;i++){
                    var result = data.tprocesos[i];
                    if (result.consulta == 1){$("#tesoreriaprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tesoreriaprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tesoreriaprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tesoreriaprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tesoreriaprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tesoreriaprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tesoreriaprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tesoreriaprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriaprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.treportes.length;i++){
                    var result = data.treportes[i];
                    if (result.consulta == 1){$("#tesoreriareportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tesoreriareportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tesoreriareportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tesoreriareportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tesoreriareportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tesoreriareportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tesoreriareportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tesoreriareportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .precio"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tesoreriareportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriareportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.ttransacciones.length;i++){
                    var result = data.ttransacciones[i];
                    if (result.consulta == 1){$("#tesoreriatransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tesoreriatransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tesoreriatransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tesoreriatransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tesoreriatransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tesoreriatransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tesoreriatransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tesoreriatransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#tesoreriatransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //tributos
                for (i=0;i<data.triconfiguracion.length;i++){
                    var result = data.triconfiguracion[i];
                    if (result.consulta == 1){$("#tributosconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tributosconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tributosconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tributosconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tributosconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tributosconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tributosconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tributosconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#tributosconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.triprocesos.length;i++){
                    var result = data.triprocesos[i];
                    if (result.consulta == 1){$("#tributosprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tributosprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tributosprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tributosprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tributosprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tributosprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tributosprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tributosprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#tributosprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.trireportes.length;i++){
                    var result = data.trireportes[i];
                    if (result.consulta == 1){$("#tributosreportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tributosreportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tributosreportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tributosreportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tributosreportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tributosreportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tributosreportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tributosreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .precio"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tributosreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#tributosreportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.tritransacciones.length;i++){
                    var result = data.tritransacciones[i];
                    if (result.consulta == 1){$("#tributostransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#tributostransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#tributostransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#tributostransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#tributostransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#tributostransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#tributostransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#tributostransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#tributostransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //utilidad
                for (i=0;i<data.uopcion.length;i++){
                    var result = data.uopcion[i];
                    if (result.consulta == 1){$("#utilitarioopciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#utilitarioopciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#utilitarioopciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#utilitarioopciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#utilitarioopciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#utilitarioopciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#utilitarioopciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#utilitarioopciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#utilitarioopciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //ventas
                for (i=0;i<data.vconfiguracion.length;i++){
                    var result = data.vconfiguracion[i];
                    if (result.consulta == 1){$("#ventasconfiguracion .consulta"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#ventasconfiguracion .crea"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#ventasconfiguracion .edita"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#ventasconfiguracion .anula"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#ventasconfiguracion .borra"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#ventasconfiguracion .imprime"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#ventasconfiguracion .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#ventasconfiguracion .precio"+result.menu_id+"").prop("checked", true);}else{$("#ventasconfiguracion .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.vprocesos.length;i++){
                    var result = data.vprocesos[i];
                    if (result.consulta == 1){$("#ventasprocesos .consulta"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#ventasprocesos .crea"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#ventasprocesos .edita"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#ventasprocesos .anula"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#ventasprocesos .borra"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#ventasprocesos .imprime"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#ventasprocesos .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#ventasprocesos .precio"+result.menu_id+"").prop("checked", true);}else{$("#ventasprocesos .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.vreportes.length;i++){
                    var result = data.vreportes[i];
                    if (result.consulta == 1){$("#ventasreportes .consulta"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#ventasreportes .crea"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#ventasreportes .edita"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#ventasreportes .anula"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#ventasreportes .borra"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#ventasreportes .imprime"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#ventasreportes .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#ventasreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .precio"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#ventasreportes .precio"+result.menu_id+"").prop("checked", true);}else{$("#ventasreportes .precio"+result.menu_id+"").prop("checked", false);}
                }
                for (i=0;i<data.vtransacciones.length;i++){
                    var result = data.vtransacciones[i];
                    if (result.consulta == 1){$("#ventastransacciones .consulta"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .consulta"+result.menu_id+"").prop("checked", false);}
                    if (result.crea == 1){$("#ventastransacciones .crea"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .crea"+result.menu_id+"").prop("checked", false);}
                    if (result.edita == 1){$("#ventastransacciones .edita"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .edita"+result.menu_id+"").prop("checked", false);}
                    if (result.anula == 1){$("#ventastransacciones .anula"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .anula"+result.menu_id+"").prop("checked", false);}
                    if (result.borra == 1){$("#ventastransacciones .borra"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .borra"+result.menu_id+"").prop("checked", false);}
                    if (result.imprime == 1){$("#ventastransacciones .imprime"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .imprime"+result.menu_id+"").prop("checked", false);}
                    if (result.aprueba == 1){$("#ventastransacciones .aprueba"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .aprueba"+result.menu_id+"").prop("checked", false);}
                    if (result.precio == 1){$("#ventastransacciones .precio"+result.menu_id+"").prop("checked", true);}else{$("#ventastransacciones .precio"+result.menu_id+"").prop("checked", false);}
                }
                //planilla
                //transporte
            }
        }, error: function (data) {
            mostrar_errores(data);
        }
    });
}

function accion(variable) {
    if ($(variable).prop("checked")){
        $(variable).prop("checked", false)
    }else{
        $(variable).prop("checked", true)
    }
}

function guardar() {
    store();
}

function actualizar() {
    update();
    $("#loaduser").DataTable().ajax.reload();
}
