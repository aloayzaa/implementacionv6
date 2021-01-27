//----------List Users--------------
var tableListUsers = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listUser').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'usu_usuario', "className": "text-center"},
                        {data: 'usu_nombres', "className": "text-center"},
                        {data: 'usu_apellidos', "className": "text-center"},
                        {data: 'usu_fecha_registro', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.usu_estado === '1' || row.usu_estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Companies--------------
var tableListCompanies = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCompanies').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'id', "className": "text-center"},
                        {data: 'emp_ruc', "className": "text-center"},
                        {data: 'emp_razon_social', "className": "text-center"},
                        {data: 'emp_contacto', "className": "text-center"},
                        {data: 'emp_telefono', "className": "text-center"},
                        {data: 'emp_direccion', "className": "text-center"},
                        {data: 'sprinter', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.emp_estado === '1' || row.emp_estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Puntos de Venta--------------
var tableListPuntosVenta = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#table-puntos-venta').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'id', class: 'text-center'},
                        {data: 'descripcion', class: 'text-center'},
                        {data: 'sus_fechainicio', class: 'text-center'},
                        {data: 'sus_fechafin', class: 'text-center'},
                        {data: 'puntoVenta', class: 'text-center'},
                        {data: 'estado', class: 'text-center'},
                        {data: 'actions', class: 'text-center'}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Suscripciones--------------
var tableListSubscription = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSubscription').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'sus_codigo', class: 'text-center'},
                        {data: 'plan', class: 'text-center'},
                        {data: 'periodo', class: 'text-center'},
                        {data: 'comprobante', class: 'text-center'},
                        {data: 'sus_estado', class: 'text-center'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Companies--------------
var tableListCertificate = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCertificate').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'id', "className": "text-center", class: 'text-center'},
                        {data: 'emp_ruc', "className": "text-center", class: 'text-center'},
                        {data: 'emp_razon_social', "className": "text-center", class: 'text-center'},
                        {data: 'emp_estado', "className": "text-center", class: 'text-center'},
                        {data: 'tipo', "className": "text-center", class: 'text-center'},
                        {data: 'actions', "className": "text-center", class: 'text-center'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List products catalog --------------
var tableProductCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#productCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2}
                    ],
                    "columns": [
                        {data: 'codigo', class: 'text-center'},
                        {data: 'descripcion', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List families catalog --------------
var tableFamilyCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#familyCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', class: 'text-center'},
                        {data: 'descripcion', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List deductions catalog --------------
var tableDeductionCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#deductionCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Expense catalog --------------
var tableExpenseCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#expenseCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'codigo', class: "text-center"},
                        {data: 'descripcion', class: "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Purchase Types --------------
var tablePurchaseTypes = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPurchaseTypes').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List marks and models --------------
var tableMarksAndModels = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#marksAndModels').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List measurement unit --------------
var tableMeasurementUnit = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#measurementUnit').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List third catalog --------------
var tableCustomerCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#customerCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2}
                    ],
                    "columns": [
                        /*{
                               className: "text-center",
                               'render': function (data, type, row, meta) {
                                   return '<input type="checkbox" class="check_register" value="'+row.id+'">';
                               }
                           },*/
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-left"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Identification catalog --------------
var tableIdentificationCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#identificationCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-left"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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


//----------List Commercial catalog --------------
var tableCommercialCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#commercialCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-left"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Account catalog --------------
var tableAccountCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#accountCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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


//----------List Documents sales --------------
var tableAccountsDocumentsSales = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listAccountsDocumentsSales').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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


//----------List Billing --------------
var tableBilling = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableBilling').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'fechaproceso'},
                        {data: 'documento'},
                        {data: 'codigo'},
                        {data: 'tercero'},
                        {data: 'moneda', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function(row) {
                                return row.base + row.inafecto
                            }
                        },
                        {
                            class: "text-center",
                            data: function(row) {
                                return row.impuesto + row.impuesto4
                            }
                        },
                        {data: 'total'},
                        {data: 'puntoventa'},
                        {data: 'tipoventa'},
                        {data: 'glosa'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado.toUpperCase());
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Documents sales --------------
var tableAccountsTypesSales = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listAccountsTypesSales').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columnDefs": [
                        { "width": "1%", "targets": -1 },
                        { "width": "1%", "targets": -3 },
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
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

//----------List Payment Methods --------------
var tablePaymentMethods = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tablePaymentMethods').DataTable({
                    serverSide: true,
                    ajax: route,
                    rowId: 'id',
                    responsive: true,
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Type Sales --------------
var tableTypeSales = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableTypeSales').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columnDefs": [
                        { "width": "1%", "targets": -1 },
                        { "width": "1%", "targets": -2 },
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro('+row.id+')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro('+ row.id +', '+ row.estado +')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------Opening Receivable--------------
var tableOpeningReceivableCatalog = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#openingReceivableCatalog').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'document', "className": "text-center"},
                        {data: 'ruc', "className": "text-center"},
                        {data: 'razonsocial', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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



//----------Table Record Voided Document--------------
var tableRecordVoidedDocument = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableRecordVoidedDocument').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'document'},
                        {data: 'ruc'},
                        {data: 'razonsocial'},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        {data: 'actions', "className": "text-center"}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List billing --------------
var tableDailySummary = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#dailySummary').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'id'},
                        {data: 'seriedoc'},
                        {data: 'numerodoc'},
                        {data: 'razonsocial'},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'base'},
                        {data: 'impuesto'},
                        {data: 'total'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List billing --------------
var tableListNote = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listNote').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'id'},
                        {data: 'seriedoc'},
                        {data: 'numerodoc'},
                        {data: 'razonsocial'},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'actions', "className": "text-center"}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Accounting --------------
var tableListAccounting = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listAccounting').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'emp_descripcion', action: 'text-center'},
                        {data: 'emp_contacto', action: 'text-center'},
                        {data: 'emp_fecha_registro', action: 'text-center'},
                        {data: 'emp_telefono', action: 'text-center'},
                        {data: 'empresas', action: 'text-center'},
                        {data: 'usuarios', action: 'text-center'},
                        {data: 'suscripcion', action: 'text-center'},
                        {data: 'emp_estado', action: 'text-center'},
                        {data: 'actions', action: 'text-center'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------SuperAdmin - List Companies--------------
var tableListCompaniesSuperAdmin = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCompaniesSuperAdmin').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'emp_codigo', "className": "text-center"},
                        {data: 'emp_descripcion', "className": "text-center"},
                        {data: 'emp_contacto', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.emp_estado === '1' || row.emp_estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
                            }
                        },
                        {data: 'sprinter', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------SuperAdmin - List Users--------------
var tableListUsersSuperAdmin = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listUsersSuperAdmin').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'usu_correo', "className": "text-center"},
                        {data: 'usu_nombres', "className": "text-center"},
                        {data: 'usu_apellidos', "className": "text-center"},
                        {data: 'usu_fecha_registro', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.usu_estado === '1' || row.usu_estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Suscripciones--------------
var tableListSuscriptionsSuperAdmin = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSubscriptionsSuperAdmin').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'sus_codigo', "className": "text-center"},
                        {data: 'plan', "className": "text-center"},
                        {data: 'periodo', "className": "text-center"},
                        {data: 'sus_estado', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Roles--------------
var tableListRoles = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listRol').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'id', "className": "text-center"},
                        {data: 'rol_nombre', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.rol_estado === '1' || row.rol_estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Costs--------------
var tableListCosts = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCosts').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Costs--------------
var tableListCostActivities = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCostActivities').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'costohoramn', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Projects--------------
var tableListProjects = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listProjects').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Accounting Plans--------------
var tableListAccountingPlans = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listAccountingPlans').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-rigth"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Business Units--------------
var tableListBusinessUnits = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBusinessUnits').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1' || row.estado === '2') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List Subsidiaries--------------
var tableListSubsidiaries = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSubsidiaries').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'contacto'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Warehouses--------------
var tableListWarehouses = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listWarehouses').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Subdiaries--------------
var tableListSubdiaries = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSubdiaries').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'codsunat'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List CurrentAccounts--------------
/*var tableListCurrentAccounts = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCurrentAccounts').DataTable({
                    //scrollY: '51vh',
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'vencimiento', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'referencia', "className": "text-center"},
                        {data: 'cargomn', "className": "text-center"},
                        {data: 'abonomn', "className": "text-center"},
                        {data: 'saldomn', "className": "text-center"},
                        {data: 'cargome', "className": "text-center"},
                        {data: 'abonome', "className": "text-center"},
                        {data: 'saldome', "className": "text-center"},
                        {data: 'saldome', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'cuenta', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Cuenta Corriente - Proveedor",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();*/

/*//----------List BalancePayments--------------
var tableListBalancePayments = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBalancePayments').DataTable({
                    //scrollY: '51vh',
                    scrollX: true,
                    scrollCollapse: true,
                    //paging: false, //sirve para mostrar todos los registros
                    serverSide: true,
                    ajax: route,
                    //responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.tipodoc !== '07') {
                                    if (row.totalmn !== 0) {
                                        return row.totalmn;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.tipodoc === '07') {
                                    return (row.totalmn).toFixed(2);
                                } else {
                                    if (row.totalmn - row.saldosmn === 0) {
                                        return '';
                                    } else {
                                        return (row.totalmn - row.saldosmn).toFixed(2);
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldosmn === 0) {
                                    return '';
                                } else {
                                    return row.saldosmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.tipodoc !== '07') {
                                    if (row.totalme !== 0) {
                                        return row.totalme;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.tipodoc === '07') {
                                    return (row.totalme).toFixed(2);
                                } else {
                                    if (row.totalme - row.saldosme === 0) {
                                        return '';
                                    } else {
                                        return (row.totalme - row.saldosme).toFixed(2);
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldosme === 0) {
                                    return '';
                                } else {
                                    return row.saldosme;
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Saldos por Pagar",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();*/

//----------List ShoppingSummary--------------
/*var tableListShoppingSummary = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listShoppingSummary').DataTable({
                    //scrollY: '51vh',
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.basemn === '0') {
                                    return '0.00';
                                } else {
                                    return row.basemn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.igvmn === '0') {
                                    return '0.00';
                                } else {
                                    return row.igvmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.totalmn === '0') {
                                    return '0.00';
                                } else {
                                    return row.totalmn;
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Resumen de Compras",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ]
                });
            });
        }
    };
}();*/

//----------List ShoppingSummary--------------
/*var tableListShoppingDetail = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listShoppingDetail').DataTable({
                    //scrollY: '51vh',
                    scrollX: true,
                    scrollCollapse: true,
                    //paging: false, //sirve para mostrar todos los registros
                    serverSide: true,
                    ajax: route,
                    "columns": [
                        {data: 'sucursal_cod', "className": "text-center"},
                        {data: 'nroregistro', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'cuenta_cod', "className": "text-center"},
                        {data: 'cuenta_dsc', "className": "text-center"},
                        {data: 'familia_cod', "className": "text-center"},
                        {data: 'familia_dsc', "className": "text-center"},
                        {data: 'basemn', "className": "text-center"},
                        {data: 'igvmn', "className": "text-center"},
                        {data: 'importemn', "className": "text-center"},
                        {data: 'baseme', "className": "text-center"},
                        {data: 'igvme', "className": "text-center"},
                        {data: 'importeme', "className": "text-center"},
                        {data: 'tcambio', "className": "text-center"},
                        {data: 'oc_numero', "className": "text-center"},
                        {data: 'oc_fecha', "className": "text-center"},
                        {data: 'proveedor_cod', "className": "text-center"},
                        {data: 'proveedor_dsc', "className": "text-center"},
                        {data: 'ccosto_cod', "className": "text-center"},
                        {data: 'ccosto_dsc', "className": "text-center"},
                        {data: 'act_cod', "className": "text-center"},
                        {data: 'act_dsc', "className": "text-center"},
                        {data: 'pry_cod', "className": "text-center"},
                        {data: 'pry_dsc', "className": "text-center"},
                        {data: 'cta_cargo', "className": "text-center"},
                        {data: 'cta_abono', "className": "text-center"},
                        {data: 'usuario', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Detalle de Compras",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ]
                });
            });
        }
    };
}();*/

//----------List Service Orders--------------
var tableListServiceOrders = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listServiceOrders').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'numope', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'base', "className": "text-center"},
                        {data: 'inafecto', "className": "text-center"},
                        {data: 'impuesto', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List ProvisionsToPay--------------
var tableListProvisionToPay = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listProvisionsToPay').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    columnDefs: [
                        {width: 130, targets: 0},
                        {width: 100, targets: 1},
                        {width: 120, targets: 2},
                        {width: 100, targets: 3},
                        {width: 100, targets: 4},
                    ],
                    "columns": [
                        {data: 'numope', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'codigo_tercero', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'num', "className": "text-center"},
                        {data: 'impuesto', "className": "text-center"},
                        {data: 'impuesto2', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado.toUpperCase());
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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


//----------List  Settlement to  expenses--------------
var tableListSettlementexpenses = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSettlementexpenses').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'codigo_tercero', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        // {data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List CreditDebitNotes--------------
var tableListCreditDebitNotes = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCreditDebitNotes').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'codigo_tercero', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'num', "className": "text-center"},
                        {data: 'impuesto', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado === '1') {
                                    return '<span class="label label-success">Activo</span>';
                                } else {
                                    return '<span class="label label-danger">Desactivado</span>';
                                }
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

//----------List References--------------
var tableListReferences = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listReferences').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    retrieve: true,
                    destroy: true,
                    "columns": [
                        {data: 'select', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List References Details--------------
var tableListReferencesDetails = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listReferencesDetails').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'select', "className": "text-center"},
                        {data: 'aplicar', "className": "text-center"},
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'importe', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Balances Receivable--------------
/*
var tableListBalancesReceivable = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listShoppingDetail').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    //paging: false, //sirve para mostrar todos los registros
                    serverSide: true,
                    ajax: route,
                    //responsive: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'totalmn', "className": "text-center"},
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.totalmn - row.saldosmn === 0) {
                                    return '';
                                } else {
                                    return (row.totalmn - row.saldosmn).toFixed(2);
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldosmn === 0) {
                                    return '';
                                } else {
                                    return row.saldosmn;
                                }
                            },
                        },
                        {data: 'totalme', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.totalme - row.saldosme === 0) {
                                    return '';
                                } else {
                                    return (row.totalme - row.saldosme).toFixed(2);
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldosme === 0) {
                                    return '';
                                } else {
                                    return row.saldosme;
                                }
                            },
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
*/

//----------List Sales Summary--------------
var tableListSalesSummary = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSalesSummary').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    //paging: false, //sirve para mostrar todos los registros
                    serverSide: true,
                    ajax: route,
                    //responsive: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'vventamn', "className": "text-center"},
                        {data: 'igvmn', "className": "text-center"},
                        {data: 'servmn', "className": "text-center"},
                        {data: 'totalmn', "className": "text-center"},
                        {data: 'costomn', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return row.vventamn - row.costomn;
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                return ((row.vventamn - row.costomn) / row.costomn) * 100;
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                return ((row.vventamn - row.costomn) / row.vventamn) * 100;
                            },
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

//----------List Sales Details--------------
var tableListSalesDetails = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSalesDetails').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    "columns": [
                        {data: 'sucursal_cod', "className": "text-center"},
                        {data: 'sucursal_dsc', "className": "text-center"},
                        {data: 'ptovta_cod', "className": "text-center"},
                        {data: 'ptovta_dsc', "className": "text-center"},
                        {data: 'tipovta_cod', "className": "text-center"},
                        {data: 'tipovta_dsc', "className": "text-center"},
                        {data: 'tipoigv_cod', "className": "text-center"},
                        {data: 'tipoigv_dsc', "className": "text-center"},
                        {data: 'certamen_dsc', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'tcambio', "className": "text-center"},
                        {data: 'guiarem', "className": "text-center"},
                        {data: 'cliente_cod', "className": "text-center"},
                        {data: 'cliente_dsc', "className": "text-center"},
                        {data: 'vventamn', "className": "text-center"},
                        {data: 'igvmn', "className": "text-center"},
                        {data: 'servmn', "className": "text-center"},
                        {data: 'totalmn', "className": "text-center"},
                        {data: 'costomn', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'S/') {
                                    if (row.totalmn - row.costomn !== 0) {
                                        return row.totalmn - row.costomn;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'S/') {
                                    if (row.vventamn !== 0) {
                                        return ((row.vventamn - row.costomn) / row.vventamn) * 100;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'S/') {
                                    if (row.costomn !== 0) {
                                        return ((row.vventamn - row.costomn) / row.costomn) * 100;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.vventame !== 0) {
                                        return vventame;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.igvme !== 0) {
                                        return igvme;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.servme !== 0) {
                                        return servme;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.totalme !== 0) {
                                        return totalme;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.costome !== 0) {
                                        return costome;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.totalme - row.costome !== 0) {
                                        return row.totalme - row.costome;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.vventame !== 0) {
                                        return ((row.vventame - row.costome) / row.vventame) * 100;
                                    }
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda !== 'S/') {
                                    if (row.costome !== 0) {
                                        return ((row.vventame - row.costome) / row.costome) * 100;
                                    }
                                }
                            },
                        },
                        {data: 'vendedor_cod', "className": "text-center"},
                        {data: 'vendedor_cod', "className": "text-center"},
                        {data: 'vendedor_dsc', "className": "text-center"},
                        {data: 'condicion_cod', "className": "text-center"},
                        {data: 'condicion_dsc', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Banks Opening --------------
var tableBanksOpening = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBanksOpening').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'descripcion_banco', "className": "text-center"},
                        {data: 'descripcion_ctactebanco', "className": "text-center"},
                        {data: 'nrocheque', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'codigo_tercero', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        {data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Cash Movement --------------
var tableCashMovement = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashMovement').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    columnDefs: [{
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return ceros_izquierda(5, parseInt(row.numero));
                        }
                    }],
                    "columns": [
                        {data: 'numero', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'descripcion_banco', "className": "text-center"},
                        {data: 'descripcion_ctactebanco', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'ingreso', "className": "text-center"},
                        {data: 'egreso', "className": "text-center"},
                        {data: 'tcambio', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado.toUpperCase());
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Cash Movement Detail --------------
var tableCashMovementDetail = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashMovementDetail').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'numero', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'descripcion_banco', "className": "text-center"},
                        {data: 'descripcion_ctactebanco', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'tcambio', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Bank Movement --------------
var tableBankMovement = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBankMovement').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    destroy: true,
                    columnDefs: [{
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return ceros_izquierda(5, parseInt(row.numero));
                        }
                    }],
                    "columns": [
                        {data: 'numero', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'descripcion_banco', "className": "text-center"},
                        {data: 'descripcion_ctactebanco', "className": "text-center"},
                        {data: 'nrocheque', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'ingreso', "className": "text-center"},
                        {data: 'egreso', "className": "text-center"},
                        {data: 'tcambio', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado.toUpperCase());
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Cash Movement Reference --------------
var tableCashMovementReference = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashMovementReference').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    info: false,
                    "columnDefs": [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        },
                        {
                            "width": "25%",
                            "targets": 8
                        }
                    ],
                    "columns": [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.options.id + '" name="chkItem' + row.options.id + '" id="chkItem' + row.options.id + '" onchange="aplicar(' + row.options.id + ', \'' + row.options.documento + '\' ,' + row.options.saldomn + ',' + row.options.saldome + ',' + row.options.cuenta_id  + ', \''+row.options.cuenta_codigo + ' | ' +row.options.cuenta_desc +'\''  +')">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.options.id + '" name="chkItem' + row.options.id + '" id="chkItem' + row.options.id + '" onchange="aplicar(' + row.options.id + ', \'' + row.options.documento + '\' ,' + row.options.saldomn + ',' + row.options.saldome + ',' + row.options.cuenta_id + ', \''+row.options.cuenta_codigo + ' | ' +row.options.cuenta_desc +'\''  +')">';
                                }
                            }
                        },
                        {
                            data: 'options.aplicar',
                            className: "text-center",
                            "render": function (data, type, row) {
                                if (row.options.elegido == 0) {
                                    return '<input readonly id="item' + row.id + '" name="item' + row.id + '" type="number" step="0.1" value="' + parseFloat(data).toFixed(2) + '"' +
                                        'onblur="valor_item('+ row.id + ',' + row.options.saldomn +' , '+ row.options.saldome +')" class="form-control text-center" style="width: 80px" >';
                                } else if (row.options.elegido == 1) {
                                    return '<input id="item' + row.id + '" name="item' + row.id + '" type="number" step="0.1" value="' + parseFloat(data).toFixed(2) + '"' +
                                        'onblur="valor_item('+ row.id + ',' + row.options.saldomn +' , '+ row.options.saldome +')" class="form-control text-center" style="width: 80px">';
                                }
                            }
                        },
                        {data: 'options.documento', "className": "text-center"},
                        {data: 'options.fecha', "className": "text-center"},
                        {data: 'options.vencimiento', "className": "text-center"},
                        {data: 'options.moneda', "className": "text-center"},
                        {data: 'options.saldomn', "className": "text-center"},
                        {data: 'options.saldome', "className": "text-center"},
                        {data: 'options.glosa', "className": "text-center"},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Bank Movement Reference --------------
var tableTransfers = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listTransfers').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'numero', "className": "text-center"},
                        {data: 'ctactebancoe', "className": "text-center"},
                        {data: 'monedae', "className": "text-center"},
                        {data: 'totale', "className": "text-center"},
                        {data: 'ctactebancoi', "className": "text-center"},
                        {data: 'monedai', "className": "text-center"},
                        {data: 'totali', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        //{data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Other Provisions --------------
var tableOtherProvisions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listOtherProvisions').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'codigo_tercero', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        //{data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Cash Bank Book --------------
var tableListCashBankBook = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashBankBook').DataTable({
                    serverSide: true,
                    scrollX: true,
                    scrollCollapse: true,
                    ajax: route,
                    //responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'suc_dsc', "className": "text-center"},
                        {data: 'ctacte', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'voucher', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'nrotran', "className": "text-center"},
                        {data: 'nrocheque', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'Soles') {
                                    return row.ingresomn;
                                } else {
                                    return row.ingreso;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'Soles') {
                                    return row.egresomn;
                                } else {
                                    return row.egreso;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'Soles') {
                                    return row.saldomn;
                                } else {
                                    return row.saldo;
                                }
                            },
                        },
                        {data: 'tcambio', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Consolidated Bank --------------
var tableListConsolidatedBank = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listConsolidatedBank').DataTable({
                    serverSide: true,
                    ajax: route,
                    scrollX: true,
                    scrollCollapse: true,
                    //responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'banco', "className": "text-center"},
                        {data: 'ctactebanco', "className": "text-center"},
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'saldoi', "className": "text-center"},
                        {data: 'ingreso', "className": "text-center"},
                        {data: 'egreso', "className": "text-center"},
                        {data: 'saldof', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Issued Checks--------------
var tableListIssuedChecks = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listIssuedChecks').DataTable({
                    serverSide: true,
                    ajax: route,
                    scrollX: true,
                    scrollCollapse: true,
                    //responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'banco', "className": "text-center"},
                        {data: 'ctactebanco', "className": "text-center"},
                        {data: 'voucher', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'nrocheque', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'importemn', "className": "text-center"},
                        {data: 'importeme', "className": "text-center"},
                        {data: 'codigo', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Operation Type--------------
var tableOperationType = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listOperationType').DataTable({
                    serverSide: true,
                    ajax: route,
                    rowId: 'id',
                    pageLength: 50,
                    responsive: true,
                    destroy: true,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', "className": "text-left"},
                        {data: 'descripcion', "className": "text-left"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Bank--------------
var tableBank = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBank').DataTable({
                    serverSide: true,
                    ajax: route,
                    rowId: 'id',
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Payment Way--------------
var tablePaymentType = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPaymentType').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Opening Seat / List Daily Seat / List Adjustment Exchange / Closing Seat--------------
var tableDailySeat = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDailySeat').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'nota_id',
                    "columns": [
                        {data: 'descripcion_subdiario', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return row.codigo_periodo + '-' + row.numero;
                            }
                        },
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'tipocambio', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'totalmn', "className": "text-center"},
                        {data: 'totalme', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.estado.toLowerCase() === 'activo') {
                                    return '<span class="label label-success">Activo</span>';
                                } else if (row.estado.toLowerCase() === 'anulado') {
                                    return '<span class="label label-danger">Anulado</span>';
                                }
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

//----------List Open Close Seat--------------
var tableOpenClosePeriods = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listOpenClosePeriods').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'compras', "className": "text-center"},
                        {data: 'ventas', "className": "text-center"},
                        {data: 'almacen', "className": "text-center"},
                        {data: 'tesoreria', "className": "text-center"},
                        {data: 'contabilidad', "className": "text-center"},
                        {data: 'planillas', "className": "text-center"},
                        {data: 'gestiontributaria', "className": "text-center"},
                        {data: 'activos', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Account Movement--------------
var tableAccountMovement = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listAccountMovement').DataTable({
                    serverSide: true,
                    ajax: route,
                    scrollX: true,
                    scrollCollapse: true,
                    destroy: true,
                    "columns": [
                        {data: 'fecha', "className": "text-center"},
                        {data: 'voucher', "className": "text-center"},
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'cco_codigo', "className": "text-center"},
                        {data: 'cco_nombre', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'cargomn', "className": "text-center"},
                        {data: 'abonomn', "className": "text-center"},
                        {data: 'cargome', "className": "text-center"},
                        {data: 'abonome', "className": "text-center"},
                        {data: 'op', "className": "text-center"},
                        {data: 'tercero_cod', "className": "text-center"},
                        {data: 'tercero_dsc', "className": "text-center"},
                        {data: 'ref_docum', "className": "text-center"},
                        {data: 'ref_fecha', "className": "text-center"},
                        {data: 'cta_cargo', "className": "text-center"},
                        {data: 'cta_abono', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Daily Book--------------
var tableDailyBook = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDailyBook').DataTable({
                    serverSide: true,
                    scrollX: true,
                    scrollCollapse: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'subdiario', "className": "text-center"},
                        {data: 'periodo', "className": "text-center"},
                        {data: 'operacion', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'cuo', "className": "text-center"},
                        {data: 'cuo_item', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'ref_fecha', "className": "text-center"},
                        {data: 'ref_libro', "className": "text-center"},
                        {data: 'ref_numero', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return (row.ref_tipodoc + row.ref_seriedoc + row.ref_numerodoc);
                            }
                        },
                        {data: 'cargomn', "className": "text-center"},
                        {data: 'abonomn', "className": "text-center"},
                        {data: 'ccosto', "className": "text-center"},
                        {data: 'ccosto_dsc', "className": "text-center"},
                        {data: 'op', "className": "text-center"},
                        {data: 'op_fecha', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE Normal',
                            action: function () {
                                generar_ple_normal_diario();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Ledger--------------
var tableLedger = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listLedger').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'operacion', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'cargomn', "className": "text-center"},
                        {data: 'abonomn', "className": "text-center"},
                        {data: 'ccosto', "className": "text-center"},
                        {data: 'ccosto_dsc', "className": "text-center"},
                        {data: 'op', "className": "text-center"},
                        {data: 'op_fecha', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE Normal',
                            action: function () {
                                generar_ple_normal_mayor();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Detail Movement Account--------------
var tableDetailMovementCash = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDetailMovementCash').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'ctacte', "className": "text-center"},
                        {data: 'periodo', "className": "text-center"},
                        {data: 'numero', "className": "text-center"},
                        {data: 'voucher_id', "className": "text-center"},
                        {data: 'voucher_item', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'cuenta_dsc', "className": "text-center"},
                        {data: 'ingreso', "className": "text-center"},
                        {data: 'egreso', "className": "text-center"},
                        {data: 'dolares', "className": "text-center"},
                        {data: 'ref_tipodoc', "className": "text-center"},
                        {data: 'ref_seriedoc', "className": "text-center"},
                        {data: 'ref_numerodoc', "className": "text-center"},
                        {data: 'ref_fechaven', "className": "text-center"},
                        {data: 'ref_libro', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE 5.0',
                            action: function () {
                                generar_ple_normal_movimiento_efectivo();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Detail Movement Account--------------
var tableDetailMovementAccount = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDetailMovementAccount').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'banco_dsc', "className": "text-center"},
                        {data: 'ctacte', "className": "text-center"},
                        {data: 'periodo', "className": "text-center"},
                        {data: 'numero', "className": "text-center"},
                        {data: 'voucher_id', "className": "text-center"},
                        {data: 'voucher_item', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'mediopago', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'nrodoc', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {data: 'transaccion', "className": "text-center"},
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'cuenta_dsc', "className": "text-center"},
                        {data: 'ingreso', "className": "text-center"},
                        {data: 'egreso', "className": "text-center"},
                        {data: 'dolares', "className": "text-center"},
                        {data: 'ref_tipodoc', "className": "text-center"},
                        {data: 'ref_seriedoc', "className": "text-center"},
                        {data: 'ref_numerodoc', "className": "text-center"},
                        {data: 'ref_fechaven', "className": "text-center"},
                        {data: 'ref_libro', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE 5.0',
                            action: function () {
                                generar_ple_normal_movimiento_ctacte();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Cash Banks--------------
var tableCashBanks = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashBanks').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'denominacion', "className": "text-center"},
                        {data: 'banco', "className": "text-center"},
                        {data: 'nrocuenta', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.moneda === 'PEN SOLES') {
                                    return row.cargomn;
                                } else {
                                    return row.cargome;
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.moneda === 'PEN SOLES') {
                                    return row.abonomn;
                                } else {
                                    return row.abonome;
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE Normal',
                            action: function () {
                                generar_ple_caja_bancos();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Accounting Current Account--------------
var tableCurrentAccountReport = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashBanks').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'cuenta', "className": "text-center"},
                        {data: 'tipodocide', "className": "text-center"},
                        {data: 'nrodocide', "className": "text-center"},
                        {data: 'razonsocial', "className": "text-center"},
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fechadoc', "className": "text-center"},
                        {data: 'vencimiento', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'saldomn', "className": "text-center"},
                        {data: 'saldome', "className": "text-center"},
                        {data: 'cargomn', "className": "text-center"},
                        {data: 'cargome', "className": "text-center"},
                        {data: 'difermn', "className": "text-center"},
                        {data: 'diferme', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE Normal',
                            action: function () {
                                generar_ple_cuenta_corriente();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Checking Balance--------------
var tableCheckingBalance = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCashBanks').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'iniciald', "className": "text-center"},
                        {data: 'inicialh', "className": "text-center"},
                        {data: 'movimd', "className": "text-center"},
                        {data: 'movimh', "className": "text-center"},
                        {data: 'finald', "className": "text-center"},
                        {data: 'finalh', "className": "text-center"},
                        {data: 'balanced', "className": "text-center"},
                        {data: 'balanceh', "className": "text-center"},
                        {data: 'naturald', "className": "text-center"},
                        {data: 'naturalh', "className": "text-center"},
                        {data: 'funciond', "className": "text-center"},
                        {data: 'funcionh', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE',
                            action: function () {
                                generar_ple_balance_comprobacion();
                            }
                        },
                        {
                            text: 'PDT 0684',
                            action: function () {
                                generar_pdt_balance_comprobacion();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Financial State--------------
var tableFinancialState = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listFinancialState').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Balances Account--------------
var tableBalancesAccount = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBalancesAccount').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'cargomn', "className": "text-center"},
                        {data: 'abonomn', "className": "text-center"},
                        {data: 'saldomn', "className": "text-center"},
                        {data: 'acumn', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Seat Validation--------------
var tableSeatValidation = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBalancesAccount').DataTable({
                    scrollX: true,
                    scrollCollapse: true,
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "columns": [
                        {data: 'tipo', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'voucher', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'cargo', "className": "text-center"},
                        {data: 'abono', "className": "text-center"},
                        {data: 'mensaje', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Taxes--------------
var tableTaxes = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listTaxes').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        {data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//----------List Categories--------------
var tableCategories = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCategories').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'codsunat', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        // {data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Categories--------------
var tableAssets = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listAssets').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                        // {data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//----------List Periods --------------
var tablePeriods = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPeriods').DataTable({
                    serverSide: true,
                    ajax: route,
                    rowId: 'id',
                    pageLength: 50,
                    responsive: true,
                    destroy: true,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
                            }
                        },
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Periods --------------
var tableExchangeRate = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listExchangeRate').DataTable({
                    serverSide: true,
                    ajax: route,
                    rowId: 'id',
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 't_compra', "className": "text-center"},
                        {data: 't_venta', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
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

//----------List No Habidos --------------
var tableNoHabidos = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listNoHabidos').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Tax Excluded--------------
var tableTaxExcluded = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listTaxExcluded').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Tax Excluded--------------
var tableWithholdingDocuments = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listWithholdingDocuments').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {
                            class: "text-center",
                            data: function (row) {
                                return row.codigo_documento + ' ' + row.seriedoc + '-' + row.numerodoc;
                            },
                        },
                        {data: 'fechaproceso', "className": "text-center"},
                        {data: 'codigo_tercero', "className": "text-center"},
                        {data: 'descripcion_tercero', "className": "text-center"},
                        {data: 'simbolo_moneda', "className": "text-center"},
                        {data: 'total', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'actions', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Withholding Reference--------------
var tableWithholdingReference = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listWithholdingDocumentsReference').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'elegido', "className": "text-center"},
                        {data: 'aplicar', "className": "text-center"},
                        {data: 'documento', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'vencimiento', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'saldomn', "className": "text-center"},
                        {data: 'saldome', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List tableMovementType --------------
var tableMovementType = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableMovementType').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List tableAccountingCentralizationWarehouse --------------
var tableAccountingCentralizationWarehouse = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableAccountingCentralizationWarehouse').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength:50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {
                            data: 'codigo',
                            class: "text-center",
                        },
                        {
                            data: 'descripcion'
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado)
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List tableOrderToWarehouse --------------
var tableOrderToWarehouse = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableOrderToWarehouse').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'pedido_id',
                    pageLength: 50,
                    columnDefs: [
                        {width: 140, targets: 5},
                        {width: 140, targets: 6},
                    ],
                    "columns": [
                        {data: 'descripcion_almacen', class: "text-center",},
                        {data: 'numero', class: "text-center",},
                        {data: 'fecha', class: "text-center",},
                        {data: 'descripcion_tercero', class: "text-center",},
                        {data: 'descripcion_movimiento', class: "text-center",},
                        {data: 'orden_trabajo', class: "text-center",},
                        {data: 'glosa', class: "text-center",},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado_pedido);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.pedido_id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.pedido_id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
                            }
                        },
                    ],
                    order: [[1, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List tableSaleOrder--------------
var tableSaleOrder = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableSaleOrder').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    columnDefs: [
                        {width: 140, targets: 5},
                        {width: 140, targets: 6},
                    ],
                    "columns": [
                        {data: 'pto_venta', class: "text-center",},
                        {data: 'fecha', class: "text-center",},
                        {data: 'numero', class: "text-center",},
                        {data: 'codigo_tercero', class: "text-center",},
                        {data: 'descripcion_tercero', class: "text-center",},
                        {data: 'moneda', class: "text-center",},
                        {data: 'importe', class: "text-center",},
                        {data: 'tipoventa', class: "text-center",},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado_pedido);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
                            }
                        },
                    ],
                    order: [[1, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List tableSummaryBallots--------------
var tableSummaryBallots = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableSummaryBallots').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,

                    "columns": [
                        {data: 'numero', class: "text-center",},
                        {data: 'fecha', class: "text-center",},
                        {data: 'motivo', class: "text-center",},
                        {data: 'estado', class: "text-center",},

                    ],
                    order: [[1, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List tableIncomeToWarehouse --------------
var tableIncomeToWarehouse = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableIncomeToWarehouse').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'ingreso_id',
                    pageLength: 50,
                    columnDefs: [
                        {width: 120, targets: 2},
                        {width: 140, targets: 4},
                    ],
                    "columns": [
                        {data: 'descripcion_almacen', class: "text-center"},
                        {data: 'numero', class: "text-center"},
                        {data: 'fecha', class: "text-center"},
                        {data: 'descripcion_tercero', class: "text-center"},
                        {data: 'descripcion_movimiento', class: "text-center"},
                        {data: 'simbolo_moneda', class: "text-center"},
                        {data: 'totalmn', class: "text-center"},
                        {data: 'totalme', class: "text-center"},
                        {data: 'glosa', class: "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado_ingreso);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.ingreso_id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.ingreso_id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
                            }
                        },
                    ],
                    order: [[1, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List tableExitToWarehouse --------------
var tableExitToWarehouse = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableExitToWarehouse').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'ingreso_id',
                    pageLength: 50,
                    "columns": [
                        {data: 'descripcion_almacen'},
                        {data: 'numero'},
                        {data: 'fecha'},
                        {data: 'descripcion_tercero'},
                        {data: 'descripcion_movimiento'},
                        {data: 'simbolo_moneda'},
                        {data: 'totalmn'},
                        {data: 'totalme'},
                        {data: 'glosa'},
                        {
                            class: "text-center",
                            data: function (row) {
                                switch (row.estado_ingreso.toLowerCase()) {
                                    case 'activo':
                                        return '<span class="label label-info">Activo</span>';
                                    case 'aprobado':
                                        return '<span class="label label-success">Aprobado</span>';
                                    case 'anulado':
                                        return '<span class="label label-danger">Anulado</span>';
                                    case 'facturado':
                                        return '<span class="label label-success">Facturado</span>';
                                        break;
                                    default:
                                        return '<span class="label label-danger">Error</span>'
                                }
                            }
                        },
                    ],
                    order: [[1, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List var tableInventorySettingsWarehouse = function () { --------------
var tableInventorySettingsWarehouse = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableInventorySettingsWarehouse').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    "columns": [
                        {data: 'descripcion_almacen'},
                        {data: 'numero'},
                        {data: 'fecha'},
                        {data: 'descripcion_sucursal'},
                        {data: 'descripcion_almacen'},
                        {data: 'descripcion_tercero'},
                        {data: 'glosa'},
                        {data: 'estado_ingreso', "className": "text-center"},

                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List var tableProductKardex = function () { --------------
var tableProductKardex = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableProductKardex').DataTable({
                    destroy: true,
                    serverSide: true,
                    scrollX: true,
                    scrollCollapse: true,
                    //paging: false,
                    dom: 'Bfrtip',
                    ajax: route,
                    //responsive: true,
                    columns: [
                        {data: 'almacen', name: 'almacen'},
                        {
                            class: "alinear_centro",
                            orderable: false,
                            data: function (row) {
                                if (row.id != null) {
                                    if (row.ventana == 'MOV_INGRESOALMACEN') {
                                        return '<label class="text-info"><a onclick="navega(' + "'" + 'ingresos-almacen/' + row.id + '/ver' + "'" + ');">' + row.documento + '</a></label>';
                                    } else {
                                        return '<label class="text-info"><a href="{{ route(\'show.exittowarehouse\', [row.id]) }}">' + row.documento + '</a></label>';
                                    }
                                } else {
                                    return '<label>' + row.codigo + '</label>';
                                }
                            },
                        },
                        {
                            data: function (row) {
                                if (row.fechaproceso != null) {
                                    return row.fechaproceso;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'referencia', name: 'referencia'},
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresos == 0) {
                                    return '';
                                } else {
                                    return row.ingresos;
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.salidas == 0) {
                                    return '';
                                } else {
                                    return row.salidas;
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.stock == 0) {
                                    return '';
                                } else {
                                    /*saldo=saldo+row.stock;
                                    return formatea_todo_moneda(saldo,3)*/
                                    return row.stock;
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresomn != 0) {
                                    return row.ingresomn / row.ingresos
                                } else if (row.ingresomn == 0 && row.salidamn == 0) {
                                    return '';
                                } else {
                                    return row.salidamn / row.salidas;
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresomn == 0) {
                                    return '';
                                } else {
                                    return row.ingresomn;
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.salidamn == 0) {
                                    return '';
                                } else {
                                    return row.salidamn
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresomn - row.salidamn == 0) {
                                    return '';
                                } else {
                                    return row.ingresomn - row.salidamn
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresome != 0) {
                                    return row.ingresome / row.ingresos
                                } else if (row.ingresome == 0 && row.salidame == 0) {
                                    return '';
                                    //alert('hola');
                                } else {
                                    return row.salidame / row.salidas
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresome == 0) {
                                    return '';
                                } else {
                                    return row.ingresome
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.salidame == 0) {
                                    return '';
                                } else {
                                    return row.salidame
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.ingresome - row.salidame == 0) {
                                    return '';
                                } else {
                                    return row.ingresome - row.salidame
                                }
                            },
                        },
                        {data: 'tipomov', name: 'tipomov'},
                        {data: 'glosa', name: 'glosa'},
                        {data: 'ter_codigo', name: 'ter_codigo'},
                        {data: 'ter_nombre', name: 'ter_nombre'},
                        {data: 'centrocosto', name: 'centrocosto'}

                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List Purchases--------------
var tableListPurchases = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPurchases').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'periodo', "className": "text-center"},
                        {
                            data: function (row) {
                                if (row.numero !== '') {
                                    return row.numero;
                                } else {
                                    return '';
                                }

                            }
                        },
                        {data: 'voucher_id', "className": "text-center"},
                        {data: 'voucher_item', "className": "text-center"},
                        {
                            data: function (row) {
                                if (row.fechadoc !== null) {
                                    return row.fechadoc;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                if (row.vencimiento !== null) {
                                    return row.vencimiento;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'seriedoc', "className": "text-center"},
                        {data: 'numerodoc', "className": "text-center"},
                        {data: 'numerodocexp', "className": "text-center"},
                        {data: 'nrodocide', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.baseexpmn === 0) {
                                    return '';
                                } else {
                                    return row.baseexpmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.igvexpmn === 0) {
                                    return '';
                                } else {
                                    return row.igvexpmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.basemixmn === 0) {
                                    return '';
                                } else {
                                    return row.basemixmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.igvmixmn === 0) {
                                    return '';
                                } else {
                                    return row.igvmixmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.baseexomn === 0) {
                                    return '';
                                } else {
                                    return row.baseexomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.igvexomn === 0) {
                                    return '';
                                } else {
                                    return row.igvexomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.basenogmn === 0) {
                                    return '';
                                } else {
                                    return row.basenogmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.iscmn === 0) {
                                    return '';
                                } else {
                                    return row.iscmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.otrosmn === 0) {
                                    return '';
                                } else {
                                    return row.otrosmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.totalmn === 0) {
                                    return '';
                                } else {
                                    return row.totalmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.totalme === 0) {
                                    return '';
                                } else {
                                    return row.totalme;
                                }
                            },
                        },
                        {data: 'nrodocdet', classname: 'text-center'},
                        {
                            data: function (row) {
                                if (row.fechadocdet !== null) {
                                    return row.fechadocdet;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'cmoneda', classname: 'text-center'},
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.tcambio === 0) {
                                    return '';
                                } else {
                                    return row.tcambio;
                                }
                            },
                        },
                        {
                            data: function (row) {
                                if (row.fecharef !== null) {
                                    return row.fecharef;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'tipodocref', classname: 'text-center'},
                        {data: 'serieref', classname: 'text-center'},
                        {data: 'numeroref', classname: 'text-center'},
                        {data: 'tipobien', classname: 'text-center'},
                        {data: 'contrato', classname: 'text-center'},
                        {data: 'error1', classname: 'text-center'},
                        {data: 'error2', classname: 'text-center'},
                        {data: 'error3', classname: 'text-center'},
                        {data: 'error4', classname: 'text-center'},
                        {data: 'mpago', classname: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.liquidacion_id === 0) {
                                    return '';
                                } else {
                                    return row.liquidacion_id;
                                }
                            },
                        },
                        {data: 'glosa', classname: 'text-center'},
                        {data: 'voucher', classname: 'text-center'},
                        {data: 'usuario', classname: 'text-center'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE Normal',
                            action: function () {
                                generar_ple_normal_compras();
                            }
                        },
                        {
                            text: 'PLE Simplificado',
                            action: function () {
                                generar_ple_simplificado();
                            }
                        },
                        {
                            text: 'PLE No Domiciliado',
                            action: function () {
                                generar_ple_no_domiciliados();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Sales--------------
var tableListSales = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSales').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'periodo', "className": "text-center"},
                        {
                            data: function (row) {
                                if (row.numero !== '') {
                                    return row.numero;
                                } else {
                                    return '';
                                }

                            }
                        },
                        {data: 'voucher_id', "className": "text-center"},
                        {data: 'voucher_item', "className": "text-center"},
                        {
                            data: function (row) {
                                if (row.fechadoc !== null) {
                                    return row.fechadoc;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                if (row.vencimiento !== null) {
                                    return row.vencimiento;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'seriedoc', "className": "text-center"},
                        {data: 'numerodoc', "className": "text-center"},
                        {data: 'nrodocide', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.exportamn === 0) {
                                    return '';
                                } else {
                                    return row.exportamn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.basemn === 0) {
                                    return '';
                                } else {
                                    return row.basemn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.dsctobasemn === 0) {
                                    return '';
                                } else {
                                    return row.dsctobasemn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.exoneramn === 0) {
                                    return '';
                                } else {
                                    return row.exoneramn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.inafectomn === 0) {
                                    return '';
                                } else {
                                    return row.inafectomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.igvmn === 0) {
                                    return '';
                                } else {
                                    return row.igvmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.dsctoigvmn === 0) {
                                    return '';
                                } else {
                                    return row.dsctoigvmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.iscmn === 0) {
                                    return '';
                                } else {
                                    return row.iscmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.otrosmn === 0) {
                                    return '';
                                } else {
                                    return row.otrosmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.totalmn === 0) {
                                    return '';
                                } else {
                                    return row.totalmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.totalme === 0) {
                                    return '';
                                } else {
                                    return row.totalme;
                                }
                            },
                        },
                        {data: 'cmoneda', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.tcambio === 0) {
                                    return '';
                                } else {
                                    return row.tcambio;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.fecharef !== null) {
                                    return row.fecharef;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'tipodocref', "className": "text-center"},
                        {data: 'serieref', "className": "text-center"},
                        {data: 'numeroref', "className": "text-center"},
                        {data: 'contrato', "className": "text-center"},
                        {data: 'error1', "className": "text-center"},
                        {data: 'mpago', "className": "text-center"},
                        {data: 'numeroret', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.importeret === 0) {
                                    return '';
                                } else {
                                    return row.importeret;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.referencial === 0) {
                                    return '';
                                } else {
                                    return row.referencial;
                                }
                            },
                        },
                        {data: 'glosa', "className": "text-center"},
                        {data: 'voucher', "className": "text-center"},
                        {data: 'usuario', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE 5.0 Normal',
                            action: function () {
                                generar_ple_ventas(1);
                            }
                        },
                        {
                            text: 'PLE 5.0 Simplificado',
                            action: function () {
                                generar_ple_ventas(2);
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Withholding Book--------------
var tableWithholdingBook = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listWithholdingBook').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'periodo', "className": "text-center"},
                        {
                            data: function (row) {
                                if (row.numero !== null) {
                                    return row.numero;
                                } else {
                                    return '';
                                }

                            }
                        },
                        {
                            data: function (row) {
                                if (row.fechadoc !== null) {
                                    return row.fechadoc;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                if (row.vencimiento !== null) {
                                    return row.vencimiento;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'seriedoc', "className": "text-center"},
                        {data: 'numerodoc', "className": "text-center"},
                        {
                            data: function (row) {
                                if (row.fpago !== null) {
                                    return row.fpago;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'tipodocide', "className": "text-center"},
                        {data: 'nrodocide', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.brutomn === 0) {
                                    return '';
                                } else {
                                    return row.brutomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.rentamn === 0) {
                                    return '';
                                } else {
                                    return row.rentamn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.pensionmn === 0) {
                                    return '';
                                } else {
                                    return row.pensionmn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.netomn === 0) {
                                    return '';
                                } else {
                                    return row.netomn;
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        }
                    ],
                });
            });
        }
    };
}();

//----------List Permament Inventory--------------
var tablePermamentInventory = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPermanentInventory').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'alm_cod', "className": "text-center"},
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {data: 'lote', "className": "text-center"},
                        {data: 'umedida', "className": "text-center"},
                        {data: 'tipo', "className": "text-center"},
                        {data: 'tipo_dsc', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'tipodoc', "className": "text-center"},
                        {data: 'seriedoc', "className": "text-center"},
                        {data: 'numerodoc', "className": "text-center"},
                        {data: 'operacion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldo === 0) {
                                    return '';
                                } else {
                                    return row.saldo;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.ingresos === 0) {
                                    return '';
                                } else {
                                    return row.ingresos;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.ingresos > 0) {
                                    if (row.preciomn === 0) {
                                        return '';
                                    } else {
                                        return row.preciomn;
                                    }
                                } else {
                                    return '';
                                }

                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.ingresomn === 0) {
                                    return '';
                                } else {
                                    return row.ingresomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.salidas === 0) {
                                    return '';
                                } else {
                                    return row.salidas;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.peso === 0) {
                                    return '';
                                } else {
                                    return row.peso;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.salidas > 0) {
                                    if (row.preciomn === 0) {
                                        return '';
                                    } else {
                                        return row.preciomn;
                                    }
                                } else {
                                    return '';
                                }

                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.salidamn === 0) {
                                    return '';
                                } else {
                                    return row.salidamn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldomn === 0) {
                                    return '';
                                } else {
                                    return row.saldomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldomn === 0) {
                                    return '';
                                } else {
                                    return row.saldomn;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.saldomn === 0) {
                                    return '';
                                } else {
                                    return row.saldomn;
                                }
                            },
                        },
                        {data: 'voucher', class: "text-center"},

                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PLE',
                            action: function () {
                                generar_ple_inventario_permanente();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Operation Customer--------------
var tableOperationCustomer = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listOPerationCustomer').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'contador', class: 'text-center'},
                        {data: 'd_tipodoc', class: 'text-center'},
                        {data: 'd_numdoc', class: 'text-center'},
                        {data: 'periodo', class: 'text-center'},
                        {data: 'tipo_per', class: 'text-center'},
                        {data: 'tipo_doc', class: 'text-center'},
                        {data: 'num_doc', class: 'text-center'},
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.nimporte === 0) {
                                    return '';
                                } else {
                                    return row.nimporte;
                                }
                            },
                        },
                        {data: 'ap_pater', name: 'ap_pater'},
                        {data: 'ap_mater', name: 'ap_mater'},
                        {data: 'nombre1', name: 'nombre1'},
                        {data: 'nombre2', name: 'nombre2'},
                        {data: 'razon_soc', name: 'razon_soc'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'TXT',
                            action: function () {
                                generar_txt_operaciones_terceros();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Benefit Declaration--------------
var tableBenefitDeclaration = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listBenefitDeclaration').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'tcompra', class: 'text-center'},
                        {data: 'tcomprob', class: 'text-center'},
                        {
                            data: function (row) {
                                if (row.fecha !== null) {
                                    return row.fecha;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'serie', class: 'text-center'},
                        {data: 'numero', class: 'text-center'},
                        {data: 'tpersona', class: 'text-center'},
                        {data: 'tdocum', class: 'text-center'},
                        {data: 'documento', class: 'text-center'},
                        {data: 'r_social', class: 'text-center'},
                        {data: 'ap_paterno', class: 'text-center'},
                        {data: 'ap_materno', class: 'text-center'},
                        {data: 'nombre1', class: 'text-center'},
                        {data: 'nombre2', class: 'text-center'},
                        {data: 'tmoneda', class: 'text-center'},
                        {data: 'destino', class: 'text-center'},
                        {data: 'nrodestino', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.base === 0) {
                                    return '';
                                } else {
                                    return row.base;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.isc === 0) {
                                    return '';
                                } else {
                                    return row.isc;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.igv === 0) {
                                    return '';
                                } else {
                                    return row.igv;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.otros === 0) {
                                    return '';
                                } else {
                                    return row.otros;
                                }
                            },
                        },
                        {data: 'es_percep', class: 'text-center'},
                        {data: 'tasa', class: 'text-center'},
                        {data: 'ser_percep', class: 'text-center'},
                        {data: 'nro_percep', class: 'text-center'},
                        {data: 'ref_tcomprob', class: 'text-center'},
                        {data: 'ref_serie', class: 'text-center'},
                        {data: 'ref_numero', class: 'text-center'},
                        {
                            data: function (row) {
                                if (row.ref_fecha !== null) {
                                    return row.ref_fecha;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.ref_base === 0) {
                                    return '';
                                } else {
                                    return row.ref_base;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.ref_igv === 0) {
                                    return '';
                                } else {
                                    return row.ref_igv;
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'TXT',
                            action: function () {
                                generar_txt_programa_beneficios();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Monthly Income Tax-----------------
var tableMonthlyIncomeTax = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listMonthlyIncomeTax').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'ruc', name: 'text-center'},
                        {data: 'apaterno', name: 'text-center'},
                        {data: 'amaterno', name: 'text-center'},
                        {data: 'nombre', name: 'text-center'},
                        {data: 'serie', name: 'text-center'},
                        {data: 'numero', name: 'text-center'},
                        {
                            data: function (row) {
                                if (row.fecha !== null) {
                                    return row.fecha;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.retencion === 0) {
                                    return '';
                                } else {
                                    return row.retencion;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.ies === 0) {
                                    return '';
                                } else {
                                    return row.ies;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.monto === 0) {
                                    return '';
                                } else {
                                    return row.monto;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.renta === 0) {
                                    return '';
                                } else {
                                    return row.renta;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.pension === 0) {
                                    return '';
                                } else {
                                    return row.pension;
                                }
                            },
                        },
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.neto === 0) {
                                    return '';
                                } else {
                                    return row.neto;
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PDT 621',
                            action: function () {
                                generar_pdt_igv_renta_mensual();
                            }
                        },
                    ],
                });
            });
        }
    };
}();

//----------List Retention Agents-----------------
var tableRetentionAgents = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listRetentionAgents').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'nrodocide', name: 'nrodocide'},
                        {data: 'rsocial', name: 'rsocial'},
                        {data: 'apaterno', name: 'apaterno'},
                        {data: 'amaterno', name: 'amaterno'},
                        {data: 'nombres', name: 'nombres'},
                        {data: 'ret_numero', name: 'ret_numero'},
                        {
                            data: function (row) {
                                if (row.ret_fecha != null) {
                                    return fecha_a_espaniol(row.ret_fecha);
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.retencion == 0) {
                                    return '';
                                } else {
                                    return formatea_todo_moneda(row.retencion, 2);
                                }
                            },
                        },
                        {data: 'tipodoc', name: 'tipodoc'},
                        {data: 'numerodoc', name: 'numerodoc'},
                        {
                            data: function (row) {
                                if (row.fechadoc != null) {
                                    return fecha_a_espaniol(row.fechadoc);
                                } else {
                                    return '';
                                }
                            }
                        },
                        {data: 'moneda', name: 'moneda'},
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.total == 0) {
                                    return '';
                                } else {
                                    return formatea_todo_moneda(row.total, 2);
                                }
                            },
                        },
                        {
                            class: "alinear_derecha",
                            data: function (row) {
                                if (row.totalmn == 0) {
                                    return '';
                                } else {
                                    return formatea_todo_moneda(row.totalmn, 2);
                                }
                            },
                        },
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            footer: true
                        },
                        {
                            extend: 'csv',
                            footer: true
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: "Sprinter Web - Libro Inventario Permanente",
                            footer: true,
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 6;
                                doc.styles.tableHeader.fontSize = 6;
                                doc.styles.tableFooter.fontSize = 6;
                            }
                        },
                        {
                            text: 'PDT 621',
                            action: function () {
                                generar_pdt_agente_retencion_cuarta();
                            }
                        },
                    ],
                });
            });
        }
    };
}();
//----------List Partidas Arancelarias--------------
var tableTariffItems = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listTariffItems').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', class: 'text-center'},
                        {data: 'descripcion', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Grupo de Productos-------------------
var tableProductoGroup = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listProductGroup').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', class: 'text-center'},
                        {data: 'descripcion', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//------------- List Clasificació terceros--------------
var tableThirdClass = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listThirdClass').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'codsunat'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------- lista Vendedores -----------------
var tableSeller = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listSeller').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        { "width": "1%", "targets": -1 },
                        { "width": "1%", "targets": -2 },
                    ],
                    "columns": [

                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro('+row.id+')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro('+ row.id +','+ row.estado +')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Condiciones de Pago--------------
var tablePaymentCondition = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPaymentCondition').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', class: 'text-center'},
                        {data: 'descripcion', class: 'text-center'},
                        {data: 'codsunat', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List tipo de transaccion--------------
var tableTransactionTypes = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listTransactionTypes').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'codsunat'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Tipo Via--------------
var tableRoadTypes = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listRoadTypes').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'codsunat'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Tipo Zona--------------
var tableZoneType = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listZoneType').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'codsunat'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Tipo Rubro--------------
var tableTypeHeading = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listTypeHeading').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {data: 'codsunat'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Tipo Categorias CTA CTE--------------
var tableCategoriesCtaCte = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCategoriesCtaCte').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo'},
                        {data: 'descripcion'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Ordenes de Compra--------------
var tablePurchaseOrder = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listPurchaseOrder').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    pageLength: 50,
                    columnDefs: [{
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return ceros_izquierda(3, parseInt(row.serie)) + " - " + ceros_izquierda(5, parseInt(row.numero));
                        }
                    }],
                    columns: [
                        {data: 'serie', class: 'text-center'},
                        {data: 'fechaproceso', class: 'text-center'},
                        {data: 'tercero_descripcion', class: 'text-center'},
                        {data: 'moneda_simbolo', class: 'text-center'},
                        {data: 'base', class: 'text-center'},
                        {data: 'inafecto', class: 'text-center'},
                        {data: 'impuesto', class: 'text-center'},
                        {data: 'total', class: 'text-center'},
                        {data: 'glosa', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {data: 'almacen_descripcion', class: 'text-center'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
var tableCustormerQuote = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ListCustomerQuote').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    rowId: 'id',
                    pageLength: 50,
                    columnDefs: [{
                        "targets": 0,
                        "render": function (data, type, row, meta) {
                            return ceros_izquierda(3, parseInt(row.serie)) + " - " + ceros_izquierda(5, parseInt(row.numero));
                        }
                    }],
                    columns: [
                        {data: 'serie', class: 'text-center'},
                        {data: 'fechaproceso', class: 'text-center'},
                        {data: 'tercero_descripcion', class: 'text-center'},
                        {data: 'moneda_simbolo', class: 'text-center'},
                        {data: 'base', class: 'text-center'},
                        {data: 'inafecto', class: 'text-center'},
                        {data: 'impuesto', class: 'text-center'},
                        {data: 'total', class: 'text-center'},
                        {data: 'glosa', class: 'text-center'},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {data: 'almacen_descripcion', class: 'text-center'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro_mov(' + row.id + ')"><span class="flaticon-menu-delete-file"></span></a>';
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
//----------List Menu Options--------------
var tableMenuOptions = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listMenuOptions').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'descripcion', class: "text-center"},
                        {data: 'consulta', class: "text-center"},
                        {data: 'crea', class: "text-center"},
                        {data: 'edita', class: "text-center"},
                        {data: 'anula', class: "text-center"},
                        {data: 'borra', class: "text-center"},
                        {data: 'imprime', class: "text-center"},
                        {data: 'aprueba', class: "text-center"},
                        {data: 'precio', class: "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List Currencies--------------
var tableListCurrencies = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCurrencies').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
                    "columns": [
                        {data: 'codigo', "className": "text-center"},
                        {data: 'descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Eliminar registro" onclick="eliminar_registro(' + row.id + ')"><span class="flaticon-menu-garbage-1"></span></a>';
                            }
                        },
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
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

//----------List Work Order--------------
var tableListWordOrder = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('.listWorkOrder').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    destroy: true,
                    "columns": [
                        {data: 'sel', className: "text-center"},
                        {data: 'nromanual', className: "text-center"},
                        {data: 'fechaproceso', className: "text-center"},
                        {data: 'tercero_descripcion', className: "text-center"},
                        {data: 'glosa', className: "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//------------list PurchaseOrderPedidoAlmacen----------
var tablePurcharseOrderWarehouse = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#cab_documento').DataTable({
                    serverSide: true,
                    ajax: route,
                    //scrollX: true,
                    //scrollCollapse: true,
                    destroy: true,
                    columns: [
                        {
                            'render': function (data, type, row, meta) {
                                return '<input type="checkbox" name="cab_doc_id' + row.id + '" id="cab_doc_id' + row.id + '" class="cab_doc_id" value="' + row.id + '" onclick="obtener_detalles(this.value)">';
                            }
                        },
                        {data: 'documento', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'nombre', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//-----------list PurchaseOrderPedidoAlmacenDetail----------
var tablePurcharseOrderWarehouseDetail = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#det_documento').DataTable({
                    serverSide: true,
                    ajax: route,
                    //scrollX: true,
                    //scrollCollapse: true,
                    destroy: true,
                    columnDefs: [
                        {
                            "targets": 1,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    "columns": [
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido === 0) {
                                    return '<input type="checkbox" name="iditem" id="iditem' + row.options.id.concat(row.options.item) + '" value="' + row.options.id + "|" + row.options.item + '" onclick="pedido_detalles_catidades(' + row.options.id.concat(row.options.item) + ')">';
                                } else {
                                    return '<input type="checkbox" name="iditem" id="iditem' + row.options.id.concat(row.options.item) + '" value="' + row.options.id + "|" + row.options.item + '" checked onclick="pedido_detalles_cantidades(' + row.options.id.concat(row.options.item) + ')">';
                                }

                            }
                        },
                        {data: 'options.id', className: "text-center"},
                        {data: 'options.documento', className: "bold-col-dt"},
                        {
                            data: 'options.pedido', className: "text-center",
                            "render": function (data, type, row, meta) {
                                if (row.options.elegido === 0) {
                                    return ' <input type="text" class="form-control text-center" style="width: 90px" name="detalle_pedido" id="detalle_pedido' + row.options.id.concat(row.options.item) + '" value="' + data + '" onblur="detallepedido(' + row.options.id.concat(row.options.item) + ',' + row.options.cantidad + ')" readonly>';
                                } else {
                                    return ' <input type="text" class="form-control text-center" style="width: 90px" name="detalle_pedido" id="detalle_pedido' + row.options.id.concat(row.options.item) + '" value="' + data + '" onblur="detallepedido(' + row.options.id.concat(row.options.item) + ',' + row.options.cantidad + ')">';
                                }
                            }
                        },
                        {data: 'options.prd_cod', "className": "text-center"},
                        {data: 'options.ume_cod', "className": "text-center"},
                        {data: 'options.prd_dsc', "className": "text-center"},
                        {
                            data: 'options.cantidad', "className": "text-center",
                            "render": function (data, type, row, meta) {
                                return '<div id="cantidad' + row.options.id.concat(row.options.item) + '">' + data + '</div>';
                            }
                        },
                        {data: 'options.ccosto', "className": "text-center"},
                    ],
                    order: [[1, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//----------------------pedidos almacen orden compra
var TableWarehousePurchaseOrder = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#pedidos_de_ordencompra').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'fecha', "className": "text-center"},
                        {
                            data: 'documento', "className": "text-center",
                            "render": function (data, type, full, meta) {
                                return `<a onclick="pedido(${full.id})">${data == null ? '' : data}</a>`;
                            },
                        },
                        {data: 'glosa', "className": "text-center"}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//---------------------ingreso almacen orden compran
var depositOrderPurchase = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ingreso_almacen').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'fecha', "className": "text-center"},
                        {data: 'refgremision', "className": "text-center"},
                        {data: 'totalmn', "className": "text-center"},
                        {data: 'totalme', "className": "text-center"},
                        {data: 'referencia', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//------------------------provisiones orden compra
var provisionesOrdenCompra = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#provisiones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'fecha', "className": "text-center"},
                        {data: 'referencia', "className": "text-center"},
                        {data: 'moneda_nacional', "className": "text-center"},
                        {data: 'moneda_extranjera', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//------------------------productos ubicacion almacen
var ProductUbicacionAlmacen = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_ubicacion_almacen').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.ubicacion', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_ubicacionalmacen" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_ubicacionalmacen" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------ DetalleUnidaddeMedida
var DetalleUnidadMedida = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_unidad_medida').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.factor', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_detalleunidadmedida" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_detalleunidadmedida" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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

//------------------------ DetalleEntidadesBancarias
var DetalleEntidadBancaria = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_docbanco').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.cuenta', "className": "text-center"},
                        {data: 'options.moneda_id', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.nrocheque', "className": "text-center"},


                        {data: 'options.', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_entidadbancaria" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_entidadbancaria" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------productos datos NPK
var ProductDatosNPK = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_datos_npk').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.conc', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_npk" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_npk" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//--------------------------- vendedores || comisiones marca -----------
var SellBrandCommissions = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla-comisiones-marca').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.desde', "className": "text-center"},
                        {data: 'options.hasta', "className": "text-center"},
                        {data: 'options.meta', "className": "text-center"},
                        {data: 'options.comision', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_marca_comision" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_marca_comision" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------terceros contacto
var TerceroContacto = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_contactos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.nombre', "className": "text-center"},
                        {data: 'options.nrodocidentidad', "className": "text-center"},
                        {data: 'options.cargo', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.options.cpe === 'on') {
                                    return '<input checked type="checkbox" id="chk_cpe" name="chk_cpe" disabled>';
                                } else {
                                    return '<input type="checkbox" id="chk_cpe" name="chk_cpe" disabled>';
                                }
                            }
                        },
                        {data: 'options.email', "className": "text-center"},
                        {data: 'options.telefono', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_contacto" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_contacto" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------terceros cuentas bancarias
var TerceroCuentaBancarias = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_cuentas_bancarias').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.banco_codigo', "className": "text-center"},
                        {data: 'options.cuenta', "className": "text-center"},
                        {data: 'options.moneda_descripcion', "className": "text-center"},
                        {data: 'options.tipocuenta_descripcion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.options.defecto === 'on') {
                                    return '<input checked type="checkbox" id="chk_defecto" name="chk_defecto" disabled>';
                                } else {
                                    return '<input type="checkbox" id="chk_defecto" name="chk_defecto" disabled>';
                                }
                            }
                        },
                        {data: 'options.cci', "className": "text-center"},
                        {data: 'options.swift', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_tercero_cuenta" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_tercero_cuenta" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------terceros marcas
var TerceroMarca = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_tercero_marca').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.marca_codigo', "className": "text-center"},
                        {data: 'options.marca_descripcion', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_tercero_marca" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_tercero_marca" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------terceros rubro
var TerceroRubro = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_tercero_rubro').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.rubro_codigo', "className": "text-center"},
                        {data: 'options.rubro_descripcion', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_tercero_rubro" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_tercero_rubro" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------terceros dirección
var TerceroDireccion = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_tercero_direccion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    "scrollY": "200px",
                    columns: [
                        {data: 'options.via_nombre', "className": "text-left"},
                        {data: 'options.ubigeo_codigo', "className": "text-center"},
                        {data: 'options.ubigeo_completo', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_tercero_direccion" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_tercero_direccion" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//------------------------terceros empresa
var TerceroEmpresa = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_tercero_empresa').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.ruc', "className": "text-left"},
                        {data: 'options.razonsocial', "className": "text-center"},
                        {data: 'options.direccion', "className": "text-center"},
                        {
                            class: "text-center",
                            data: function (row) {
                                if (row.options.tipo === 'N') {
                                    return '<label>Ninguno</label>';
                                } else if (row.options.tipo === 'P') {
                                    return '<label>Padre</label>';
                                } else if (row.options.tipo === 'M') {
                                    return '<label>Madre</label>';
                                } else if (row.options.tipo === 'T') {
                                    return '<label>Tutor</label>';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_editar_tercero_empresa" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_tercero_empresa" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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

//--------------------------- Terceros || Categorias Cta Cte -----------
var TypesAssignedDocuments = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCategoriesCtaCteDetail').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_documentos_asignados" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_documentos_asignados" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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
//----------------------------MESTROS || PRODUCTOS || MARCAS Y MODELOS -----------------------------------
var BrandModels = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tabla_listado_modelos').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.nombrecomercial', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_modelo" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_modelo" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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

//--------------------------- Gestion de compas || transaccion || ordenes de servicio -----------
var ServiceOrdersDetailDocuments = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDetailServiceOrders').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollY: 190,
                    scrollX: true,
                    columns: [
                        {
                            "sName": "Index",
                            "render": function (data, type, row, meta) {
                                return meta.row + 1; // This contains the row index
                            },
                            "orderable": "false",
                        },
                        {data: 'options.producto_codigo', "className": "text-center"},
                        {data: 'options.producto_descripcion', "className": "text-center"},
                        {data: 'options.umedida_codigo', "className": "text-center"},
                        {data: 'options.cantidad', "className": "text-center"},
                        {data: 'options.precio1', "className": "text-center"},
                        {data: 'options.importe', "className": "text-center"},
                        {data: 'options.op_codigo', "className": "text-center"},
                        {data: 'options.ccosto_codigo', "className": "text-center"},
                        {data: 'options.ccosto_descripcion', "className": "text-center"},
                        {data: 'options.glosa', "className": "text-center"},
                        {data: 'options.proyecto_codigo', "className": "text-center"},
                        {data: 'options.proyecto_descripcion', "className": "text-center"},
                        {data: 'options.actividad_codigo', "className": "text-center"},
                        {data: 'options.actividad_descripcion', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_editar_detalle_documento" class="btn"><span class="fa fa-edit bt-edit-ag"></span></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_detalle_documento" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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

var ListProvisions = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#lista_proviciones').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    paging: false,
                    bFilter: false,
                    ordering: false,
                    columns: [
                        {data: 'fecha', "className": "text-center"},
                        {data: 'referencia', "className": "text-center"},
                        {data: 'glosa', "className": "text-center"},
                        {data: 'importemn', "className": "text-center"},
                        {data: 'importeme', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();


//----------List Usuarios Privilegios--------------
var tableUserPrivileges = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listUserPrivileges').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    destroy: true,
                    pageLength: 50,
                    rowId: 'id',
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2},
                    ],
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

//----------list adjustmentexchange----------------------
var tableOpeningSeat = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listOpeningSeat').DataTable({
                    serverSide: true,
                    ajax: route,
                    rowId: 'id',
                    responsive: true,
                    destroy: true,
                    "columns": [
                        {data: 'subdiario'},
                        {data: 'voucher', "className": "text-center"},
                        {data: 'fecha', "className": "text-center"},
                        {data: 'moneda', "className": "text-center"},
                        {data: 'tcambio', "className": "text-center"},
                        {data: 'glosa'},
                        {data: 'totalmn', "className": "text-center"},
                        {data: 'totalme', "className": "text-center"},
                        {data: 'estado', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------list adjustmentexchange detail----------------------
var tableOpeningSeatDetail = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listOpeningSeatDetail').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollY: 190,
                    scrollX: true,
                    rowId: 'parent_id',
                    "columns": [
                        {data: 'options.item', "className": "text-center"},
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.nombre'},
                        {data: 'options.docrefer', "className": "text-center"},
                        {data: 'options.cuenta', "className": "text-center"},
                        {data: 'options.cargomn', "className": "text-center"},
                        {data: 'options.abonomn', "className": "text-center"},
                        {data: 'options.cargome', "className": "text-center"},
                        {data: 'options.abonome', "className": "text-center"},
                        {data: 'options.glosa'},
                        {data: 'options.cco_cod', "className": "text-center"},
                        {data: 'options.cco_dsc'},
                        {data: 'options.act_cod', "className": "text-center"},
                        {data: 'options.act_dsc'},
                        {data: 'options.pry_cod', "className": "text-center"},
                        {data: 'options.pry_dsc'},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//----------List sellingpoints --------------
var tableSellingPoints = function () {
    'use strict';

    return  {
        init : function (route){
            $(document).ready(function () {
               $("#listSellingPoints").DataTable({
                   serverSide: true,
                   ajax: route,
                   responsive: true,
                   rowId: 'id',
                   destroy: true,
                   columns : [
                       {data : 'codigo', 'className' : 'text-center'},
                       {data : 'descripcion', 'className' : 'text-center'},
                       {
                           class : "text-center",
                           data : function (row){
                               return estado_color(row.estado);
                           }
                       },
                       {
                           className: "text-center",
                           'render' : function (data, type, row, meta) {
                               return '<a title="Eliminar registro" onclick="eliminar_registro('+ row.id +')"><span class="flaticon-menu-garbage-1"></span></a>'
                           }
                       },
                       {
                           className: "text-center",
                           'render': function (data, type, row, meta) {
                               return '<a title="Anular registro" onclick="anular_registro(' + row.id + ',' + row.estado + ')"><span class="flaticon-menu-delete-file"></span></a>';
                           }
                       },
                   ]
               })
            });
        }
    }
}();

//--------------------------- Gestion de Ventas || transaccion || punto de venta -----------
var PointOfSaleDetail = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDetailPointOfSale').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    "searching": false,
                    "paging": false,
                    pageLength: 50,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'id'},
                        {
                            "sName": "Index",
                            "render": function (data, type, row, meta) {
                                return meta.row + 1; // númeración items
                            },
                            "orderable": "false",
                        },
                        {data: 'options.codigo', "className": "text-left"},
                        {data: 'options.descripcion', "className": "text-left"},
                        {data: 'options.umedida_codigo', "className": "text-center"},
                        //{data: 'options.serie', "className": "text-center"},
                        {
                            data: function (row) {
                                return serie_pointofsale(row);
                            },
                        },
                        {data: 'options.stock', "className": "text-center"},
                        //{data: 'options.cantidad', "className": "text-center"},
                        {
                            data: function (row) {
                                return cantidad_pointofsale(row);
                            }
                        },
                        //{data: 'options.preciolista', "className": "text-center"},
                        {
                            data: function (row) {
                                return precio_pointofsale(row);
                            }
                        },
                        //{data: 'options.descuento', "className": "text-center"},
                        {
                            data: function (row) {
                                return descuento_pointofsale(row);
                            },
                        },
                        {data: 'options.precio', "className": "text-center"},
                        {data: 'options.importe', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_detalle_venta" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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

var PointOfSaleCobranza = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listCobranzaPointOfsale').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    bFilter: false,
                    paging: false,
                    columns: [
                        {
                            data: function (row) {
                                return '<input type="hidden" name="id_fpco" id="id_fpco" class="id_fpco'+row.options.id+'" value="'+row.options.id+'">'+row.options.fp
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        //{data: 'options.fp', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {
                            data: function (row) {
                                return '<input type="text" id="referencia" name="referencia" class="form-control" value="'+row.options.ref+'">';
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {
                            data: function (row) {
                                return '<input type="text" class="form-control importe_cobranza'+row.options.id+'" name="importe_cobranza" value="'+parseFloat(row.options.importe).toFixed(2)+'" id="importe_cobranza" onblur="importe('+row.options.id+')">';
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        /*{data: 'options.importe', "className": "text-center"},*/
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var listProductPointOfSale = function () {
    'use strict';
    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listProductPointOfSale').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollX: true,
                    scrollCollapse: true,
                    columns: [
                        {
                            data: function (row) {
                                return '<input type="checkbox" name="detalle_modal'+row.options.id+'" id="detalle_modal'+row.options.id+'" onclick="existe_stock('+ row.options.id +')"  value="'+row.options.id+'">'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        /*{
                            data: function (row) {
                                return '<input type="hidden" id="codigo" name="codigo" class="form-control" value="'+row.options.codigo+'" readonly>'+row.options.codigo;
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },*/
                        {data: 'options.codigo'},
                        {data: 'options.descripcion'},
                        {data: 'options.umedida', "className": "text-center"},
                        //{data: 'options.serie', "className": "text-center"},
                        {
                            data: function (row) {
                                return serie_modal(row);
                            }
                        },
                        {data: 'options.stock', "className": "text-center"},
                        {
                            data: function (row) {
                                return cantidad_modal(row);
                            }
                        },
                        //{data: 'options.cantidad', "className": "text-center"},
                        //{data: 'options.preciolista', "className": "text-center"},
                        {
                            data: function (row) {
                                return precio_modal(row);
                            }
                        },
                        {
                            data: function (row) {
                                return descuento_modal(row);
                                //return '<input type="number" id="descuento_modal'+row.options.id+'" name="descuento_modal'+row.options.id+'" class="form-control width-75" value="" onblur="descuento_billing_modal('+row.options.id+')" disabled>';
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {data: 'options.caracteristicas'}
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//Facturación productos
var billingProducts = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listProductDetailBilling').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollX: true,
                    scrollCollapse: true,
                    searching: false,
                    columns: [
                        {
                            data: function (row) {
                                return '<input type="checkbox" name="detalle_modal'+row.options.id+'" id="detalle_modal'+row.options.id+'" onclick="existe_stock_billing('+ row.options.id +')"  value="'+row.options.id+'">'
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {data: 'options.codigo', "className": "text-center"},
                        {data: 'options.descripcion', "className": "text-center"},
                        {data: 'options.umedida', "className": "text-center"},
                        {
                            data: function (row) {
                                return serie_billing_modal(row);
                            }
                        },
                        {data: 'options.stock', "className": "text-center"},
                        {
                            data: function (row) {
                                return cantidad_billing_modal(row);
                            }
                        },
                        {
                            data: function (row) {
                                return precio_billing_modal(row);
                            }
                        },
                        {
                            data: function (row) {
                                return descuento_billing_modal(row);
                                //return '<input type="number" id="descuento_modal'+row.options.id+'" name="descuento_modal'+row.options.id+'" class="form-control width-75" value="" onblur="descuento_billing_modal('+row.options.id+')" disabled>';
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {data: 'options.ubicacion', "className": "text-center"},
                        {data: 'options.caracteristicas', "className": "text-center"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//----------Listado notas de crédito y débito --------------
var listado_notacreditodebito_cabecera = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listado_notacreditodebito_cabecera').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    pageLength: 5,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_creditodebito' + row.id + '" id="chk_creditodebito' + row.id + '" onchange="buscar_creditodebito_detalles(' + row.id + ')">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_creditodebito' + row.id + '" id="chk_creditodebito' + row.id + '" onchange="buscar_creditodebito_detalles(' + row.id +')">';
                                }
                            }
                        },
                        {data: 'name', "className": "text-center"},
                        {data: 'options.fecha', "className": "text-center"},
                        {data: 'options.nombre', "className": "text-center"},
                        {data: 'options.glosa', "className": "text-center"},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//----------Listado notas de crédito y débito detalle --------------
var listado_notacreditodebito_detalle = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listado_notacreditodebito_detalle').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    pageLength: 5,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_creditodebito_detalle' + row.id + '" id="chk_creditodebito_detalle' + row.id + '" onclick="aplicar_notacreditodebito_detalle(' + row.id + ')">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_creditodebito_detalle' + row.id + '" id="chk_creditodebito_detalle' + row.id + '" onclick="aplicar_notacreditodebito_detalle(' + row.id + ')">';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                return '<input type="text" id="notacreditodebito_aplicar'+row.id+'" name="notacreditodebito_aplicar'+row.id+'" value="' +  parseFloat(row.options.cantidad).toFixed(6) + '" class="form-control text-right width-75" onblur="notacreditodebito_cantidad(' + row.id +  ', ' + row.options.cantidad + ')">';
                            }
                        },
                        {data: 'options.prd_cod', "className": "text-center"},
                        {data: 'options.prd_dsc', "className": "text-center"},
                        {data: 'options.ume_cod', "className": "text-center"},
                        {data: 'options.cantidad', "className": "text-center"},
                        {data: 'options.lote', "className": "text-center"},
                        {data: 'options.serie', "className": "text-center"}
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//----------List lowcommunication --------------
var tableLowCommunication = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#lowcommunication').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                    ],
                    "columns": [
                        {data: 'numero', "className": "text-center"},
                        {data: 'fechaproceso', "className": "text-left"},
                        {data: 'glosa', "className": "text-left"},
                        {
                            class: "text-center",
                            data: function (row) {
                                return estado_color(row.estado);
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
///////////////su detalle
var tableLowCommunicationDetails = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listDetailLowCommunication').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    rowId: 'id',
                    pageLength: 50,
                    "columnDefs": [
                        {"width": "1%", "targets": -1},
                        {"width": "1%", "targets": -2}
                    ],
                    "columns": [
                        {data: 'options.item', "className": "text-center"},
                        {data: 'options.codigo', "className": "text-left"},
                        {data: 'options.descripcion', "className": "text-left"},
                        {data: 'options.docrefer', "className": "text-left"},
                        {data: 'options.moneda', "className": "text-left"},
                        {data: 'options.total', "className": "text-left"},
                        {
                            data: function (row) {
                                return '<a id="btn_eliminar_comunicacion" class="btn"><span class="fa fa-trash-o bt-delete-ag"></a>'
                            },
                            "orderable": "false",
                            "className": "text-center"
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

//su referencia
var tableLowCommunicationReferences = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#documentosxcobrar').DataTable({
                    serverSide: true,
                    ajax: route,
                    responsive: true,
                    pageLength: 10,
                    order: [[0, 'asc']],
                    "columns": [
                        //{data: 'options.elegido', "className": "text-center"},
                        {
                            data: function (row) {
                                if(row.options.elegido == 0) {
                                    return '<input type="checkbox" class="check" name="check'+row.id+'" id="check'+row.id+'" onclick="ocheck_valid('+row.id+')" value="'+row.id+'">'
                                }else{
                                    return '<input type="checkbox" class="check" checked name="check'+row.id+'" id="check'+row.id+'" onclick="ocheck_valid('+row.id+')" value="'+row.id+'">'
                                }
                            },
                            "orderable": "false",
                            "className": "text-center"
                        },
                        {data: 'options.aplicar', "className": "text-right"},
                        {data: 'options.documento', "className": "text-left"},
                        {data: 'options.fecha', "className": "text-left"},
                        {data: 'options.vencimiento', "className": "text-left"},
                        {data: 'options.moneda', "className": "text-left"},
                        {data: 'options.saldomn', "className": "text-right"},
                        {data: 'options.saldome', "className": "text-right"},
                        {data: ('options.lntercero' == 0) ? 'options.razonsocial' : 'options.glosa', "className": "text-left"},
                    ],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();
//---------- Listado ingreso almacen referencia cabecera en facturación (billing) --------------
var ingresoalmacen_referencia_cabecera = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ingresoalmacen_referencia_cabecera').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    pageLength: 5,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_ingresoalmacen_cab' + row.id + '" id="chk_ingresoalmacen_cab' + row.id + '" onchange="buscar_ingresoalmacen_detalles(' + row.id + ')">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_ingresoalmacen_cab' + row.id + '" id="chk_ingresoalmacen_cab' + row.id + '" onchange="buscar_ingresoalmacen_detalles(' + row.id +')">';
                                }
                            }
                        },
                        {data: 'options.documento', "className": "text-center"},
                        {data: 'options.fecha', "className": "text-center"},
                        {data: 'options.nombre', "className": "text-center"},
                        {data: 'options.glosa', "className": "text-center"},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado ingreso almacen referencia detalle en facturación (billing) --------------
var ingresoalmacen_referencia_detalle = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#ingresoalmacen_referencia_detalle').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.name + '" name="chk_ingresoalmacen_det' + row.name + '" id="chk_ingresoalmacen_det' + row.name + '" onchange="aplicar_ingresoalmacen_detalle(' + row.name + ')">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.name + '" name="chk_ingresoalmacen_det' + row.name + '" id="chk_ingresoalmacen_det' + row.name + '" onchange="aplicar_ingresoalmacen_detalle(' + row.name +')">';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                return '<input type="text" id="salidaalmacen_recibir'+row.name+'" name="salidaalmacen_recibir'+row.name+'" value="' +  parseFloat(row.options.recibir).toFixed(6) + '" class="form-control text-right width-75" onblur="salidaalmacen_recibir(' + row.name +  ', ' + row.options.recibir + ')">';
                            }
                        },
                        {data: 'options.documento', "className": "text-center, bolded"},
                        {data: 'options.prd_cod', "className": "text-center"},
                        {data: 'options.prd_dsc', "className": "text-center"},
                        {data: 'options.ume_cod', "className": "text-center"},
                        {data: 'options.cantidad', "className": "text-center"},
                        {data: 'options.atendido', "className": "text-center"},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado historial guía de remisión (billing) --------------

var listado_guias_remision = function () {
    'use strict';

    return {
        init: function () {
            $(document).ready(function () {
                $('#listado_guias_remision').DataTable({
                    serverSide: true,
                    ajax: '/billing/listado_tmpguias',
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    searching: false,
                    columns: [
                        {data: 'options.fecha', "className": "text-center"},
                        {
                            data: function (row) {
                                //return row.options.numerodoc + "-" + row.options.seriedoc;
                                return '<a onclick="tmpguias(' + row.id + ', \'' + row.options.ventana + '\')">' +  row.options.numerodoc + "-" + row.options.seriedoc + '</a>';
                            }
                        },                      
                        {data: 'options.glosa', "className": "text-center"}
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado cronologia cpe (billing) --------------

var listado_cronologia_cpe = function () {
    'use strict';

    return {
        init: function () {
            $(document).ready(function () {
                $('#listado_cronologia_cpe').DataTable({
                    serverSide: true,
                    ajax: '/billing/listado_cronologia_cpe',
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    searching: false,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'item', "className": "text-center"},
                        {data: 'fecha', "className": ""},
                        {data: 'glosa', "className": ""}
                    ],
                    order: [[0, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado movctacte historial aplicaciones(billing) --------------

var listado_hitorial_aplicaciones = function () {
    'use strict';

    return {
        init: function () {
            $(document).ready(function () {
                $('#listado_hitorial_aplicaciones').DataTable({
                    serverSide: true,
                    ajax: '/billing/listado_hitorial_aplicaciones',
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    searching: false,
                    columns: [
                        {data: 'options.fechaproceso', "className": ""},
                        {
                            data: function (row) {                  
                                return '<a onclick="historial_aplicaciones(' + row.options.origen_id + ', \'' + row.options.ventanaorigen + '\')"> ' + row.options.documento + '</a>';
                            }
                        },                           
                        {data: 'options.saldomn', "className": ""},
                        {data: 'options.saldome', "className": ""},
                        {data: 'options.glosa', "className": ""}
                    ],
                    order: [[0, 'asc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado documentos aplicar historial(billing) --------------

var listado_documentos_aplicar = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listado_documentos_aplicar').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_documento_aplicar' + row.id + '" id="chk_documento_aplicar' + row.id + '" onchange="documento_aplicar_seleccionado(' + row.id + ')">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_documento_aplicar' + row.id + '" id="chk_documento_aplicar' + row.id + '" onchange="documento_aplicar_seleccionado(' + row.id +')">';
                                }
                            }
                        },
                        {
                            data: function (row) {
                                if (row.options.elegido == 0) {
                                    return '<input type="text" id="documento_aplicar'+row.id+'" name="documento_aplicar'+row.id+'" value="' +  parseFloat(row.options.aplicar).toFixed(2) + '" class="form-control text-right width-75" onblur="cambiar_saldo_aplicar_documentos(' + row.id +  ', ' + row.options.aplicar + ')" disabled>';
                                } else if (row.options.elegido == 1) {
                                    return '<input type="text" id="documento_aplicar'+row.id+'" name="documento_aplicar'+row.id+'" value="' +  parseFloat(row.options.aplicar).toFixed(2) + '" class="form-control text-right width-75" onblur="cambiar_saldo_aplicar_documentos(' + row.id +  ', ' + row.options.aplicar + ')">';
                                }
                            }
                        },
                        {data: 'options.documento', "className": ""},
                        {data: 'options.fecha', "className": ""},
                        {data: 'options.vencimiento', "className": ""},
                        {data: 'options.moneda', "className": ""},
                        {data: 'options.saldomn', "className": ""},
                        {data: 'options.saldome', "className": ""},
                        {data: 'options.glosa', "className": "text-center"},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado cotización en facturación(billing) --------------
var listado_cotizacion_facturacion = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listado_cotizacion_facturacion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_referencia_oc' + row.id + '" id="chk_referencia_oc' + row.id + '" onchange="chk_referencia_oc(' + row.id + ')">';
                                } else if (row.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_referencia_oc' + row.id + '" id="chk_referencia_oc' + row.id + '" onchange="chk_referencia_oc(' + row.id +')">';
                                }
                            }
                        },
                        {data: 'documento', "className": ""},
                        {data: 'fecha', "className": ""},
                        {data: 'nombre', "className": ""},
                        {data: 'glosa', "className": ""},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

//---------- Listado cotización detalle en facturación(billing) --------------
var listado_cotizacion_detalle_facturacion = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#listado_cotizacion_detalle_facturacion').DataTable({
                    serverSide: true,
                    ajax: route,
                    destroy: true,
                    scrollY: '200px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: false,
                    columnDefs: [
                        {
                            "targets": 0,
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    columns: [
                        {data: 'options.elegido'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.options.elegido == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_referencia_detalle_oc' + row.id + '" id="chk_referencia_detalle_oc' + row.id + '">';
                                } else if (row.options.elegido == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_referencia_detalle_oc' + row.id + '" id="chk_referencia_detalle_oc' + row.id + '">';
                                }
                            }
                        },
                        {data: 'options.prd_cod', "className": ""},
                        {data: 'options.prd_dsc', "className": ""},
                        {data: 'options.ume_cod', "className": ""},
                        {data: 'options.aplicar', "className": ""},
                    ],
                    order: [[0, 'desc']],
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();

var tableSendingCpe = function () {
    'use strict';

    return {
        init: function (route) {
            $(document).ready(function () {
                $('#tableSendingCpe').DataTable({
                    serverSide: true,
                    ajax: route,
                    scrollY: '280px',
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    bDestroy: true,
                    paging: false,
                    /*preDrawCallback: function() {
                        abre_loading();
                    },
                    initComplete: function() {
                        cierra_loading();
                    },*/
                    rowId: 'id',
                    columns: [
                        {
                            "sName": "Index",
                            "render": function (data, type, row, meta) {
                                return meta.row + 1; // This contains the row index
                            },
                            "orderable": "false",
                        },
                        {data: 'fechaproceso'},
                        {data: 'tipodoc'},
                        {data: 'seriedoc'},
                        {data: 'numerodoc'},
                        {data: 'codigo', "className": "text-center"},
                        {data: 'nombre'},
                        {data: 'moneda'},
                        {data: 'total'},
                        {data: 'estado'},
                        {data: 'fecha_envio'},
                        {data: 'fecha_recep'},
                        {data: 'respuesta_dsc'},
                        {data: 'cpe_servidor'},
                        {
                            className: "text-center",
                            'render': function (data, type, row, meta) {
                                if (row.flag == 0) {
                                    return '<input type="checkbox" value="' + row.id + '" name="chk_aplicar' + row.id + '" id="chk_aplicar' + row.id + '">';
                                } else if (row.flag == 1) {
                                    return '<input checked type="checkbox" value="' + row.id + '" name="chk_aplicar' + row.id + '" id="chk_aplicar' + row.id + '">';   
                                }                                
                            }
                        },   
                    ],
                    footerCallback: function ( row, data, start, end, display ) {
                        var api = this.api(), data;
             
                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };
             
                        // Total over all pages
                        var total = api
                            .column( 8 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
             
                        // Total over this page
                        var pageTotal = api
                            .column( 8, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
             
                        // Update footer
                        $( api.column( 8 ).footer() ).html(
                            'S/. '+ pageTotal.toFixed(2)
                        );
                    },                    
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                });
            });
        }
    };
}();