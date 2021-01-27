<?php

use App\Menu\Entities\Menu;
use App\Privileges\Entities\Privileges;
use Illuminate\Support\Facades\Session;

if (!function_exists('menu1')) {
    /**
     * Get providers menu
     *
     * @return string
     */
    ///menus de navegacion

    function procesar()
    {
        $admin = Session::get('usuario');
        //Maestros
        $rutas_productos = array('products', 'families', 'trademarks', 'measurements', 'tariffitems', 'productgroups');
        $rutas_terceros = array('customers', 'thirdclass', 'seller', 'paymentcondition', 'identifications', 'commercials', 'transactiontypes', 'roadtypes', 'zonetype', 'typeheading', 'categoriesctacte', null);
        $rutas_costos = array('costs', 'costactivities', 'accountingplans', 'projects', 'businessunits');
        $rutas_otros = array('subsidiaries', 'warehouses', 'currencies', 'subdiaries');

        //gestion_compras
        $rutas_configuracion_c = array('deductions', 'accounts', 'purchasetypes');
        $rutas_procesos_c = array(null, null);
        $rutas_transaccion_c = array('openingToPay', 'serviceorders', 'purchaseorder', 'provisionstopay', 'creditdebitnotes', 'settlementexpenses', null);
        $rutas_reportes_c = array('currentaccounts', 'balancepayments', 'shoppingsummary', 'shoppingdetail', null, null, null, null, null, null, null, null);

        //gestion ventas
        $rutas_configuracion_v = array('typeSales','sellingpoints','pricelist', 'accountsDocumentsSales', 'accountsTypesSales', 'paymentMethods', null, null, null, null);
        $rutas_transaccion_v = array('openingReceivable', null, 'recordVoidedDocument', null, 'billing', null, null, 'pointofsale');
        $rutas_procesos_v = array(null, 'lowcommunication', null, 'sendingCpe', null  , null, null);
        $rutas_reportes_v = array('salescurrentaccounts', 'balancesreceivable', 'salessummary', 'salesdetails', null, null, null, null, null, null, null, null);

        //almacenes
        $rutas_configuracion_a = array('movementtype', 'accountingctnwarehouse', null);
        $rutas_transaccion_a = array('ordertowarehouse', 'incometowarehouse', 'exittowarehouse', null, null, null, null, null, null);
        $rutas_procesos_a = array('movementwarehouse', 'inventorysettingswarehouse', null, null);
        $rutas_reportes_a = array('productkardex', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

        //tesoreria
        $rutas_configuracion_t = array('operationtype', 'bank', 'paymenttype', null, null);
        $rutas_transaccion_t = array('banksopening', 'cashmovement', 'bankmovement', 'transfers', 'otherprovisions', null, null, null, null);
        $rutas_procesos_t = array(null, null, null, null, null);
        $rutas_reportes_t = array('cashbankbook', 'consolidatedbank', 'issuedchecks', null, null, null, null, null, null, null, null, null, null, null);

        //tributaria
        $rutas_configuracion_tr = array('taxes', 'periods', 'exchangerate', 'nohabidos', 'taxexcluded');
        $rutas_transaccion_tr = array('withholdingdocuments', null);
        $rutas_procesos_tr = array('operationcustomer', 'benefitdeclaration', 'monthlyincometax', 'retentionagents', null, null, null, null, null);
        $rutas_reportes_tr = array('purchases', 'sales', 'withholdingbook', 'permanentinventory', null, null, null);

        //activos
        $rutas_configuracion_ac = array('categories', 'assets');
        $rutas_transaccion_ac = array(null, null, null, null, null);
        $rutas_procesos_ac = array(null);
        $rutas_reportes_ac = array(null, null, null, null, null);

        //archivos

        //contabilidad
        $rutas_configuracion_con = array(null);
        $rutas_transaccion_con = array('openingseat', 'dailyseat', 'adjustmentexchange', 'closingseat', null);
        $rutas_procesos_con = array('opencloseperiods', 'accountingcentralization', null);
        $rutas_reportes_con = array('accountmovement', 'dailybook', 'ledger', null, null, 'balancesaccount', 'seatvalidation', null, null, null, null);

        $item = array(
            'Dashboard' => [
                'text' => __('Inicio'),
                'route' => 'home',
                'icon' => 'home',
                'permission' => 1,
                'subMenu' => [],

            ],

            'SuperAdmin' => [
                'text' => __('Super Administrador'),
                'route' => 'superadmin',
                'icon' => 'people',
                'permission' => 1,
                'subMenu' => [],

            ],

            'Companies' => [
                'text' => __('Empresas'),
                'route' => 'empresas',
                'icon' => 'domain',
                'permission' => 2,
                'subMenu' => [],

            ],

            'Users' => [
                'text' => __('Usuarios'),
                'route' => 'users',
                'icon' => 'account_circle',
                'permission' => 3,
                'subMenu' => [],

            ],

            'Subscription' => [
                'text' => __('Suscripción'),
                'route' => 'subscriptions',
                'icon' => 'assignment_turned_in',
                'permission' => 4,
                'subMenu' => [],

            ],

            /*'Roles' => [
                'text' => __('Roles'),
                'route' => 'roles',
                'icon' => 'transfer_within_a_station',
                'permission' => 5,
                'subMenu' => [],

            ],*/);
        if ($admin == 'ADMINISTRADOR') {
            $item += ['Master' => [
                'text' => __('Maestros'),
                'route' => null,
                'icon' => 'flaticon-menu-interface',
                'permission' => 9,
                'subMenu' => [
                    'Product' => [
                        'text' => __('Productos '),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Product catalog' => [
                                'text' => __('Catálogo de productos ✔'),
                                'route' => 'products',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Families' => [
                                'text' => __('Familia ✔'),
                                'route' => 'families',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Mark and models' => [
                                'text' => __('Marcas y modelos ✓'),
                                'route' => 'trademarks',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Units measurement' => [
                                'text' => __('Unidades de medida ✓'),
                                'route' => 'measurements',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Tariff Items' => [
                                'text' => __('Partidas Arancelarias ✔'),
                                'route' => 'tariffitems',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Product Group' => [
                                'text' => __('Grupo de Productos'),
                                'route' => 'productgroups',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Third catalog' => [
                        'text' => __('Terceros'),
                        'route' => null,
                        'icon' => '',
                        'permission' => 10,
                        'subMenu' => [
                            'Customer catalog' => [
                                'text' => __('Catálogo de terceros ✔'),
                                'route' => 'customers',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'third classification' => [
                                'text' => __('Clasificación ✔'),
                                'route' => 'thirdclass',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'seller' => [
                                'text' => __('Vendedores ✔'),
                                'route' => 'seller',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Payment Conditions' => [
                                'text' => __('Condiciones de Pago ✔'),
                                'route' => 'paymentcondition',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Identification document' => [
                                'text' => __('Documentos de identidad ✔'),
                                'route' => 'identifications',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Commercial document' => [
                                'text' => __('Documentos comerciales ✔'),
                                'route' => 'commercials',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'transaction types' => [
                                'text' => __('Tipos de transacción ✓'),
                                'route' => 'transactiontypes',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'road types' => [
                                'text' => __('Tipos de via ✓'),
                                'route' => 'roadtypes',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'zone type' => [
                                'text' => __('Tipo de zona ✓'),
                                'route' => 'zonetype',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'type heading' => [
                                'text' => __('Rubros ✓'),
                                'route' => 'typeheading',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'categories ctacte' => [
                                'text' => __('Categorías Cta.Cte ✓'),
                                'route' => 'categoriesctacte',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Costs' => [
                        'text' => __('Costos'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Centro de costos' => [
                                'text' => __('Centro de costos ✓'),
                                'route' => 'costs',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Actividades de Costo' => [
                                'text' => __('Actividades de Costo'),
                                'route' => 'costactivities',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Plan de cuentas' => [
                                'text' => __('Plan de cuentas ✓'),
                                'route' => 'accountingplans',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Proyectos' => [
                                'text' => __('Proyectos ✓'),
                                'route' => 'projects',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Unidades de Negocio' => [
                                'text' => __('Unidades de Negocio ✓'),
                                'route' => 'businessunits',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Others' => [
                        'text' => __('Otros'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Sucursales' => [
                                'text' => __('Sucursales ✓'),
                                'route' => 'subsidiaries',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Almacenes' => [
                                'text' => __('Almacenes ✔'),
                                'route' => 'warehouses',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Monedas' => [
                                'text' => __('Monedas ✔'),
                                'route' => 'currencies',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Subdiarios' => [
                                'text' => __('Subdiarios ✔'),
                                'route' => 'subdiaries',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                ],

            ],];
            $item += ['Shopping' => [
                'text' => __('Gestión de compras'),
                'route' => null,
                'icon' => 'flaticon-menu-commerce',
                'permission' => 11,
                'subMenu' => [
                    'Settings' => [
                        'text' => __('Configuración'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Detraction' => [
                                'text' => __('Tipos detración ✓'),
                                'route' => 'deductions',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Account document' => [
                                'text' => __('Cuentas por documentos compras ✓'),
                                'route' => 'accounts',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Tipos de Compra' => [
                                'text' => __('Tipos de Compra ✓'),
                                'route' => 'purchasetypes',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Opening to pay' => [
                                'text' => __('Apertura por pagar'),
                                'route' => 'openingToPay',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Órdenes de Servicio' => [
                                'text' => __('Órdenes de Servicio ✔'),
                                'route' => 'serviceorders',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'purchase order' => [
                                'text' => __('Ordenes de Compra ✔'),
                                'route' => 'purchaseorder',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Provisions payable' => [
                                'text' => __('Provisiones por pagar ✔'),
                                'route' => 'provisionstopay',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Credit and debit notes' => [
                                'text' => __('Notas de créditos / débitos'),
                                'route' => 'creditdebitnotes',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Settlement of expenses' => [
                                'text' => __('Liquidación de Gastos'),
                                'route' => 'settlementexpenses',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Reports' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Cuenta Corriente - Proveedor' => [
                                'text' => __('Cuenta Corriente - Proveedor ✔'),
                                'route' => 'currentaccounts',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Saldos por Pagar' => [
                                'text' => __('Saldos por Pagar ✔'),
                                'route' => 'balancepayments',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Resumen de Compras' => [
                                'text' => __('Resumen de Compras'),
                                'route' => 'shoppingsummary',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Detalle de Compras' => [
                                'text' => __('Detalle de Compras ✔'),
                                'route' => 'shoppingdetail',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                ],
            ],];
            $item += ['Sales' => [
                'text' => __('Gestión de ventas'),
                'route' => null,
                'icon' => 'flaticon-menu-commerce-1',
                'permission' => 12,
                'subMenu' => [
                    'Settings' => [
                        'text' => __('Configuración'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Type sales' => [
                                'text' => __('Tipo de venta ✔'),
                                'route' => 'typeSales',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Points of sale' => [
                                'text' => __('Punto de venta'),
                                'route' => 'sellingpoints',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Price list' => [
                                'text' => __('Lista de Precios'),
                                'route' => 'pricelist',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Documents sales' => [
                                'text' => __('Cuentas por documentos ventas ✔'),
                                'route' => 'accountsDocumentsSales',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Accounts by type of sales' => [
                                'text' => __('Cuentas por tipo de venta ✔'),
                                'route' => 'accountsTypesSales',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Payment Methods' => [
                                'text' => __('Formas de pago ✓'),
                                'route' => 'paymentMethods',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Opening receivable' => [
                                'text' => __('Apertura por cobrar'),
                                'route' => 'openingReceivable',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Customer Quote' => [
                                'text' => __('Cotización Cliente'),
                                'route' =>'customerquote' ,
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Record voided documents' => [
                                'text' => __('Documentos anulados'),
                                'route' => 'recordVoidedDocument',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Sales Orders' => [
                                'text' => __('Pedidos de Venta'),
                                'route' => 'saleorder',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Billings' => [
                                'text' => __('Facturación'),
                                'route' => 'billing',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Billing Order' => [
                                'text' => __('Facturar Pedido'),
                                'route' => 'billorder',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Point of Sales' => [
                                'text' => __('Punto de Venta'),
                                'route' => 'pointofsale',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Procesos' => [
                        'text' => __('Procesos'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'LowCommunication' => [
                                'text' => __('Comunicación de Baja'),
                                'route' => 'lowcommunication',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'SummaryBallotsController' => [
                                'text' => __('Resumen Boletas Electrónicas'),
                                'route' => 'summaryballots',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'SendingCpe' => [
                                'text' => __('Envio CPE'),
                                'route' => 'sendingCpe',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],                            
                        ],
                    ],
                    'Reports' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Cuenta Corriente - Clientes' => [
                                'text' => __('Cuenta Corriente - Cliente'),
                                'route' => 'salescurrentaccounts',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Saldos por Cobrar' => [
                                'text' => __('Saldos por Cobrar'),
                                'route' => 'balancesreceivable',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Resumen de Ventas' => [
                                'text' => __('Resumen de Ventas'),
                                'route' => 'salessummary',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Detalle de Ventas' => [
                                'text' => __('Detalle de Ventas'),
                                'route' => 'salesdetails',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                ],
            ],];
            $item += ['Warehouse' => [
                'text' => __('Almacenes'),
                'route' => null,
                'icon' => 'flaticon-menu-buildings',
                'permission' => 13,
                'subMenu' => [
                    'Settings' => [
                        'text' => __('Configuración'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Tipos de Movement' => [
                                'text' => __('Tipos de Movimiento ✓'),
                                'route' => 'movementtype',
                                'icon' => '',
                                'permission' => '',
                            ],
                            'Centralización Contable' => [
                                'text' => __('Centralización Contable'),
                                'route' => 'accountingctnwarehouse',
                                'icon' => '',
                                'permission' => '',
                            ]
                        ],
                    ],
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Pedidos a Almacén' => [
                                'text' => __('Pedidos al Almacén ✓'),
                                'route' => 'ordertowarehouse',
                                'icon' => '',
                                'permission' => '',
                            ],
                            'Ingresos a Almacén' => [
                                'text' => __('Ingresos a Almacén ✓'),
                                'route' => 'incometowarehouse',
                                'icon' => '',
                                'permission' => '',
                            ],
                            'Salidas a Almacén' => [
                                'text' => __('Salidas de Almacén'),
                                'route' => 'exittowarehouse',
                                'icon' => '',
                                'permission' => '',
                            ]

                        ],
                    ],
                    'Process' => [
                        'text' => __('Procesos'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Valorizar Movimientos Almacén' => [
                                'text' => __('Valorizar Movimientos Almacén'),
                                'route' => 'movementwarehouse',
                                'icon' => '',
                                'permission' => '',
                            ],
                            'Ajustes Diferencia de Inventario' => [
                                'text' => __('Ajustes Diferencia de Inventario'),
                                'route' => 'inventorysettingswarehouse',
                                'icon' => '',
                                'permission' => '',
                            ]
                        ],
                    ],
                    'Reports' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Kardex de Producto' => [
                                'text' => __('Kardex de Producto'),
                                'route' => 'productkardex',
                                'icon' => '',
                                'permission' => '',
                            ],
                            'Stock por Almacén' => [
                                'text' => __('Stock por Almacén'),
                                'route' => null,
                                'icon' => '',
                                'permission' => '',
                            ],
                            'Detalle Movement de Almacén' => [
                                'text' => __('Detalle Movement de Almacén'),
                                'route' => null,
                                'icon' => '',
                                'permission' => '',
                            ]
                        ],
                    ],
                ],
            ],];
            $item += ['Treasury' => [
                'text' => __('Tesorería'),
                'route' => null,
                'icon' => 'flaticon-menu-money',
                'permission' => 14,
                'subMenu' => [
                    'Settings' => [
                        'text' => __('Configuración'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Tipo de Operación' => [
                                'text' => __('Tipos de Operación ✓'),
                                'route' => 'operationtype',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Entidades Bancarias' => [
                                'text' => __('Entidades Bancarias'),
                                'route' => 'bank',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Medios de Pago' => [
                                'text' => __('Medios de Pago'),
                                'route' => 'paymenttype',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Apertura de Bancos' => [
                                'text' => __('Apertura de Bancos'),
                                'route' => 'banksopening',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Movement de Caja' => [
                                'text' => __('Movimientos de Caja'),
                                'route' => 'cashmovement',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Movement de Banco' => [
                                'text' => __('Movimientos de Bancos'),
                                'route' => 'bankmovement',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Transferencias' => [
                                'text' => __('Transferencias'),
                                'route' => 'transfers',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Otras Provisiones' => [
                                'text' => __('Otras Provisiones'),
                                'route' => 'otherprovisions',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Reports' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Libro Caja y Banco' => [
                                'text' => __('Libro Caja y Banco'),
                                'route' => 'cashbankbook',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Consolidado de Bancos' => [
                                'text' => __('Consolidado de Bancos'),
                                'route' => 'consolidatedbank',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Relación de Cheques Emitidos' => [
                                'text' => __('Relación de Cheques Emitidos'),
                                'route' => 'issuedchecks',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                ],
            ],];
            $item += ['Accounting' => [
                'text' => __('Contabilidad'),
                'route' => null,
                'icon' => 'flaticon-menu-business',
                'permission' => 15,
                'subMenu' => [
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Asiento de Apertura' => [
                                'text' => __('Asiento de Apertura'),
                                'route' => 'openingseat',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Asiento Diario' => [
                                'text' => __('Asiento de Diario ✓'),
                                'route' => 'dailyseat',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Ajustes por Diferencia de Cambio' => [
                                'text' => __('Ajustes por Diferencia de Cambio'),
                                'route' => 'adjustmentexchange',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Asiento Cierre' => [
                                'text' => __('Asiento Cierre'),
                                'route' => 'closingseat',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Procesos' => [
                        'text' => __('Procesos'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Abrir/Cerrar Periodos' => [
                                'text' => __('Abrir/Cerrar Periodos'),
                                'route' => 'opencloseperiods',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Centralización Contable por Módulo' => [
                                'text' => __('Centralización Contable por Módulo'),
                                'route' => 'accountingcentralization',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Reportes' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Movement por Cuenta' => [
                                'text' => __('Movement por Cuenta'),
                                'route' => 'accountmovement',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Libro Diario' => [
                                'text' => __('Libro Diario'),
                                'route' => 'dailybook',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Libro Mayor' => [
                                'text' => __('Libro Mayor'),
                                'route' => 'ledger',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Libro Caja y Banco' => [
                                'text' => __('Libro Caja y Banco'),
                                'route' => null,
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [
                                    'Detalle de Movimientos del Efectivo' => [
                                        'text' => __('Detalle de Movimientos del Efectivo'),
                                        'route' => 'detailmovementcash',
                                        'icon' => '',
                                        'permission' => '',
                                        'subMenu' => [],
                                    ],
                                    'Detalle de Movimientos de la Cuenta Corriente' => [
                                        'text' => __('Detalle de Movimientos de la Cuenta Corriente'),
                                        'route' => 'detailmovementaccount',
                                        'icon' => '',
                                        'permission' => '',
                                        'subMenu' => [],
                                    ],
                                ],
                            ],
                            'Inventario y Balances' => [
                                'text' => __('Inventario y Balances'),
                                'route' => null,
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [
                                    'Cajas y Bancos' => [
                                        'text' => __('Cajas y Bancos'),
                                        'route' => 'cashbanks',
                                        'icon' => '',
                                        'permission' => '',
                                        'subMenu' => [],
                                    ],
                                    'Cuentas Corrientes' => [
                                        'text' => __('Cuentas Corrientes'),
                                        'route' => 'currentaccountreport',
                                        'icon' => '',
                                        'permission' => '',
                                        'subMenu' => [],
                                    ],
                                    'Balance de Comprobación' => [
                                        'text' => __('Balance de Comprobación'),
                                        'route' => 'checkingbalance',
                                        'icon' => '',
                                        'permission' => '',
                                        'subMenu' => [],
                                    ],
                                    'State Financieros - Anual' => [
                                        'text' => __('State Financieros - Anual'),
                                        'route' => 'financialstate',
                                        'icon' => '',
                                        'permission' => '',
                                        'subMenu' => [],
                                    ],
                                ],
                            ],
                            'Saldos por Cuenta' => [
                                'text' => __('Saldos por Cuenta'),
                                'route' => 'balancesaccount',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Validación de Asientos' => [
                                'text' => __('Validación de Asientos'),
                                'route' => 'seatvalidation',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                ],
            ],];
            $item += ['Tributary' => [
                'text' => __('Gestión tributaria'),
                'route' => null,
                'icon' => 'flaticon-menu-greek',
                'permission' => 16,
                'subMenu' => [
                    'Settings' => [
                        'text' => __('Configuración'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Impuestos' => [
                                'text' => __('Impuestos'),
                                'route' => 'taxes',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Periodos' => [
                                'text' => __('Periodos'),
                                'route' => 'periods',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Tipo de Cambio' => [
                                'text' => __('Tipo de Cambio'),
                                'route' => 'exchangerate',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'No Habidos' => [
                                'text' => __('No Habidos'),
                                'route' => 'nohabidos',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Contribuyentes Renuncia Exoneración I.G.V.' => [
                                'text' => __('Contribuyentes Renuncia Exoneración I.G.V.'),
                                'route' => 'taxexcluded',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Comprobantes de Retención' => [
                                'text' => __('Comprobantes de Retención'),
                                'route' => 'withholdingdocuments',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Process' => [
                        'text' => __('Procesos'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'PDT-Operaciones con Terceros' => [
                                'text' => __('PDT-Operaciones con Terceros'),
                                'route' => 'operationcustomer',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'PDB-Programa de Declaración de Beneficios' => [
                                'text' => __('PDB-Programa de Declaración de Beneficios'),
                                'route' => 'benefitdeclaration',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'PDT 621 - IGV Renta Mensual' => [
                                'text' => __('PDT 621 - IGV Renta Mensual'),
                                'route' => 'monthlyincometax',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'PDT 626 - Agentes de Retención' => [
                                'text' => __('PDT 626 - Agentes de Retención'),
                                'route' => 'retentionagents',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Reports' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Compras' => [
                                'text' => __('Compras'),
                                'route' => 'purchases',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Ventas' => [
                                'text' => __('Ventas'),
                                'route' => 'sales',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Libro de Retenciones Renta 4ta' => [
                                'text' => __('Libro de Retenciones Renta 4ta'),
                                'route' => 'withholdingbook',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Inventario Permanente' => [
                                'text' => __('Inventario Permanente'),
                                'route' => 'permanentinventory',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                ],
            ],];
            $item += ['Active' => [
                'text' => __('Activos'),
                'route' => null,
                'icon' => 'flaticon-menu-holidays',
                'permission' => 17,
                'subMenu' => [
                    'Settings' => [
                        'text' => __('Configuración'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [
                            'Categorias' => [
                                'text' => __('Categorias'),
                                'route' => 'categories',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                            'Activos' => [
                                'text' => __('Activos'),
                                'route' => 'assets',
                                'icon' => '',
                                'permission' => '',
                                'subMenu' => [],
                            ],
                        ],
                    ],
                    'Transaction' => [
                        'text' => __('Transacción'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [],
                    ],
                    'Reports' => [
                        'text' => __('Reportes'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [],
                    ],
                ],
            ],];
        }
        else {
            $maestro = cargar_modulos('01', $admin);
            if (count($maestro) == 0) {
                $item += [];
            }
            else {
                $padre = array();
                //maestros
                for ($i = 0; $i < count($maestro[0]); $i++) {
                    if ($maestro[0][$i] == 0 && $maestro[1][$i] == 0 && $maestro[2][$i] == 0 && $maestro[3][$i] == 0 && $maestro[4][$i] == 0 && $maestro[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_productos = condiciones('01.01', $admin);
                        $hijos_productos = datos($hijos_productos, $rutas_productos, []);
                        $hijos_catalogos = condiciones('01.02', $admin);
                        $hijos_catalogos = datos($hijos_catalogos, $rutas_terceros, []);
                        $hijos_costos = condiciones('01.03', $admin);
                        $hijos_costos = datos($hijos_costos, $rutas_costos, []);
                        $hijos_otros = condiciones('01.04', $admin);
                        $hijos_otros = datos($hijos_otros, $rutas_otros, []);
                        $padre = cargar_padres('01');
                        $terminar_proceso = datos_padre($padre, [$hijos_productos, $hijos_catalogos, $hijos_costos, $hijos_otros]);
                        $item += ['Master' => [
                            'text' => __('Maestros'),
                            'route' => null,
                            'icon' => 'flaticon-menu-interface',
                            'permission' => 9,
                            'subMenu' => $terminar_proceso,

                        ],];
                    }
                }

                //compras
                $shopping = cargar_modulos('04', $admin);
                for ($i = 0; $i < count($shopping[0]); $i++) {
                    if ($shopping[0][$i] == 0 && $shopping[1][$i] == 0 && $shopping[2][$i] == 0 && $shopping[3][$i] == 0 && $shopping[4][$i] == 0 && $shopping[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('04.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_c, []);
                        $hijos_transaccion = condiciones('04.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_c, []);
                        $hijos_procesos = condiciones('04.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_c, []);
                        $hijos_reportes = condiciones('04.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_c, []);
                        $padre = cargar_padres('04');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Shopping' => [
                            'text' => __('Gestión de compras'),
                            'route' => null,
                            'icon' => 'flaticon-menu-commerce',
                            'permission' => 11,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }

                //ventas
                $sales = cargar_modulos('03', $admin);
                for ($i = 0; $i < count($sales[0]); $i++) {
                    if ($sales[0][$i] == 0 && $sales[1][$i] == 0 && $sales[2][$i] == 0 && $sales[3][$i] == 0 && $sales[4][$i] == 0 && $sales[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('03.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_v, []);
                        $hijos_transaccion = condiciones('03.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_v, []);
                        $hijos_procesos = condiciones('03.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_v, []);
                        $hijos_reportes = condiciones('03.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_v, []);
                        $padre = cargar_padres('03');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Sales' => [
                            'text' => __('Gestión de ventas'),
                            'route' => null,
                            'icon' => 'flaticon-menu-commerce-1',
                            'permission' => 12,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }

                //almacenes
                $warehouse = cargar_modulos('02', $admin);
                for ($i = 0; $i < count($warehouse[0]); $i++) {
                    if ($warehouse[0][$i] == 0 && $warehouse[1][$i] == 0 && $warehouse[2][$i] == 0 && $warehouse[3][$i] == 0 && $warehouse[4][$i] == 0 && $warehouse[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('02.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_a, []);
                        $hijos_transaccion = condiciones('02.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_a, []);
                        $hijos_procesos = condiciones('02.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_a, []);
                        $hijos_reportes = condiciones('02.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_a, []);
                        $padre = cargar_padres('02');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Warehouse' => [
                            'text' => __('Almacenes'),
                            'route' => null,
                            'icon' => 'flaticon-menu-buildings',
                            'permission' => 13,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }

                //tesoreria
                $treasury = cargar_modulos('05', $admin);
                for ($i = 0; $i < count($treasury[0]); $i++) {
                    if ($treasury[0][$i] == 0 && $treasury[1][$i] == 0 && $treasury[2][$i] == 0 && $treasury[3][$i] == 0 && $treasury[4][$i] == 0 && $treasury[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('05.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_t, []);
                        $hijos_transaccion = condiciones('05.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_t, []);
                        $hijos_procesos = condiciones('05.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_t, []);
                        $hijos_reportes = condiciones('05.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_t, []);
                        $padre = cargar_padres('05');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Treasury' => [
                            'text' => __('Tesorería'),
                            'route' => null,
                            'icon' => 'flaticon-menu-money',
                            'permission' => 14,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }

                //contabilidad
                $accounting = cargar_modulos('06', $admin);
                for ($i = 0; $i < count($accounting[0]); $i++) {
                    if ($accounting[0][$i] == 0 && $accounting[1][$i] == 0 && $accounting[2][$i] == 0 && $accounting[3][$i] == 0 && $accounting[4][$i] == 0 && $accounting[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('06.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_con, []);
                        $hijos_transaccion = condiciones('06.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_con, []);
                        $hijos_procesos = condiciones('06.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_con, []);
                        $hijos_reportes = condiciones('06.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_con, ['06.04.04', '06.04.05']);
                        $padre = cargar_padres('06');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Accounting' => [
                            'text' => __('Contabilidad'),
                            'route' => null,
                            'icon' => 'flaticon-menu-business',
                            'permission' => 15,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }

                //tributos
                $tributary = cargar_modulos('13', $admin);
                for ($i = 0; $i < count($tributary[0]); $i++) {
                    if ($tributary[0][$i] == 0 && $tributary[1][$i] == 0 && $tributary[2][$i] == 0 && $tributary[3][$i] == 0 && $tributary[4][$i] == 0 && $tributary[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('13.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_tr, []);
                        $hijos_transaccion = condiciones('13.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_tr, []);
                        $hijos_procesos = condiciones('13.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_tr, []);
                        $hijos_reportes = condiciones('13.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_tr, []);
                        $padre = cargar_padres('13');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Tributary' => [
                            'text' => __('Gestión tributaria'),
                            'route' => null,
                            'icon' => 'flaticon-menu-greek',
                            'permission' => 16,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }

                //activos
                $active = cargar_modulos('07', $admin);
                for ($i = 0; $i < count($active[0]); $i++) {
                    if ($active[0][$i] == 0 && $active[1][$i] == 0 && $active[2][$i] == 0 && $active[3][$i] == 0 && $active[4][$i] == 0 && $active[5][$i] == 0) {
                        $item += [];
                    } else {
                        $hijos_configuracion = condiciones('07.01', $admin);
                        $hijos_configuracion = datos($hijos_configuracion, $rutas_configuracion_ac, []);
                        $hijos_transaccion = condiciones('07.02', $admin);
                        $hijos_transaccion = datos($hijos_transaccion, $rutas_transaccion_ac, []);
                        $hijos_procesos = condiciones('07.03', $admin);
                        $hijos_procesos = datos($hijos_procesos, $rutas_procesos_ac, []);
                        $hijos_reportes = condiciones('07.04', $admin);
                        $hijos_reportes = datos($hijos_reportes, $rutas_reportes_ac, []);
                        $padre = cargar_padres('07');
                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
                        $item += ['Active' => [
                            'text' => __('Activos'),
                            'route' => null,
                            'icon' => 'flaticon-menu-holidays',
                            'permission' => 17,
                            'subMenu' => $terminar_proceso,
                        ],];
                    }
                }
            }
        }
        /*$item += [
            'Archive' => [
                'text' => __('Gestión de archivos'),
                'route' => null,
                'icon' => 'flaticon-menu-upload',
                'permission' => 14,
                'subMenu' => [
                    'PLE' => [
                        'text' => __('Transformación archivo PLE'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [],
                    ],
                    'Load' => [
                        'text' => __('Carga archivo'),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [],
                    ],
                ],
            ],
        ];*/
        return $item;
    }

    function datos($hijos, $ruta, $condicion)
    {
        $admin = Session::get('usuario');
        $submenu = array();
        $hija = array();
        $variable = array();
        if (count($condicion) > 0) {
            if ($condicion[0] == '06.04.04') {
                $especial = especial('06.04.04', $admin);
                $especial1 = datos_especiales($especial);
            }
            if ($condicion[1] == '06.04.05') {
                $especial = especial('06.04.05', $admin);
                $especial2 = datos_especiales($especial);
            }
            if (count($especial1) == 0 && count($especial2) == 0) {
                unset($hijos[0][3]);
                unset($hijos[1][3]);
                unset($hijos[2][3]);
                unset($hijos[3][3]);
                unset($hijos[4][3]);
                unset($hijos[5][3]);
                unset($hijos[6][3]);
                unset($hijos[0][4]);
                unset($hijos[1][4]);
                unset($hijos[2][4]);
                unset($hijos[3][4]);
                unset($hijos[4][4]);
                unset($hijos[5][4]);
                unset($hijos[6][4]);
                $hijos1 = array_values($hijos[0]);
                $hijos2 = array_values($hijos[1]);
                $hijos3 = array_values($hijos[2]);
                $hijos4 = array_values($hijos[3]);
                $hijos5 = array_values($hijos[4]);
                $hijos6 = array_values($hijos[5]);
                $hijos7 = array_values($hijos[6]);
                $hijos = [$hijos1, $hijos2, $hijos3, $hijos4, $hijos5, $hijos6, $hijos7];
                unset($ruta[3]);
                unset($ruta[4]);
                $ruta = array_values($ruta);
                $hija2 = [];
                $hija3 = [];
            } else {
                if (count($especial1) == 0) {
                    $hija2 = [];
                    unset($hijos[0][3]);
                    unset($hijos[1][3]);
                    unset($hijos[2][3]);
                    unset($hijos[3][3]);
                    unset($hijos[4][3]);
                    unset($hijos[5][3]);
                    unset($hijos[6][3]);
                    $hijos1 = array_values($hijos[0]);
                    $hijos2 = array_values($hijos[1]);
                    $hijos3 = array_values($hijos[2]);
                    $hijos4 = array_values($hijos[3]);
                    $hijos5 = array_values($hijos[4]);
                    $hijos6 = array_values($hijos[5]);
                    $hijos7 = array_values($hijos[6]);
                    $hijos = [$hijos1, $hijos2, $hijos3, $hijos4, $hijos5, $hijos6, $hijos7];
                    unset($ruta[3]);
                    $ruta = array_values($ruta);
                } else {
                    $hija2 = $especial1;
                }
                if (count($especial2) == 0) {
                    $hija3 = [];
                    unset($hijos[0][4]);
                    unset($hijos[1][4]);
                    unset($hijos[2][4]);
                    unset($hijos[3][4]);
                    unset($hijos[4][4]);
                    unset($hijos[5][4]);
                    unset($hijos[6][4]);
                    $hijos1 = array_values($hijos[0]);
                    $hijos2 = array_values($hijos[1]);
                    $hijos3 = array_values($hijos[2]);
                    $hijos4 = array_values($hijos[3]);
                    $hijos5 = array_values($hijos[4]);
                    $hijos6 = array_values($hijos[5]);
                    $hijos7 = array_values($hijos[6]);
                    $hijos = [$hijos1, $hijos2, $hijos3, $hijos4, $hijos5, $hijos6, $hijos7];
                    unset($ruta[4]);
                    $ruta = array_values($ruta);
                } else {
                    $hija3 = $especial2;
                }
            }
        }
        for ($i = 0; $i < count($hijos[0]); $i++) {
            $hija = [];
            if ($hijos[0][$i] == 'Libro Caja Bancos') {
                $hija = $hija2;
            }
            if ($hijos[0][$i] == 'Inventarios y Balances') {
                $hija = $hija3;
            }
            if ('ADMINISTRADOR' == $admin) {
                $submenu += $variable += [$hijos[0][$i] => [
                    'text' => __($hijos[0][$i]),
                    'route' => $ruta[$i],
                    'icon' => '',
                    'permission' => '',
                    'subMenu' => $hija,
                ],];;
            } else {
                if ($hijos[1][$i] == 0 && $hijos[2][$i] == 0 && $hijos[3][$i] == 0 && $hijos[4][$i] == 0 && $hijos[5][$i] == 0 && $hijos[6][$i] == 0) {
                    $submenu += [];
                } else {
                    $submenu += $variable += [$hijos[0][$i] => [
                        'text' => __($hijos[0][$i]),
                        'route' => $ruta[$i],
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => $hija,
                    ],];
                }
            }
        }
        return $submenu;
    }

    function datos_padre($padres, $hijos)
    {
        $admin = Session::get('usuario');
        $submenu = array();
        for ($i = 0; $i < count($padres); $i++) {
            if ('ADMINISTRADOR' == $admin) {
                $submenu += [$padres[$i]['descripcion'] => [
                    'text' => __($padres[$i]['descripcion']),
                    'route' => null,
                    'icon' => '',
                    'permission' => '',
                    'subMenu' => $hijos[$i],
                ],];
            } else {
                if (count($hijos[$i]) == 0) {
                    $submenu += [];
                } else {
                    $submenu += [$padres[$i]['descripcion'] => [
                        'text' => __($padres[$i]['descripcion']),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => $hijos[$i],
                    ],];
                }
            }
        }
        return $submenu;
    }

    function datos_especiales($datos){
        $admin = Session::get('usuario');
        $submenu = array();
        for ($i = 0; $i < count($datos); $i++) {
            if ('ADMINISTRADOR' == $admin) {
                $submenu += [$datos[$i]['descripcion'] => [
                    'text' => __($datos[$i]['descripcion']),
                    'route' => null,
                    'icon' => '',
                    'permission' => '',
                    'subMenu' => [],
                ],];
            }
            else {
                if ($datos[$i]['crea'] == 0 && $datos[$i]['edita'] == 0 && $datos[$i]['anula'] == 0 && $datos[$i]['borra'] == 0 && $datos[$i]['consulta'] == 0 && $datos[$i]['imprime'] == 0) {
                    $submenu += [];
                }
                else {
                    $submenu += [$datos[$i]['descripcion'] => [
                        'text' => __($datos[$i]['descripcion']),
                        'route' => null,
                        'icon' => '',
                        'permission' => '',
                        'subMenu' => [],
                    ],];
                }
            }
        }
        return $submenu;
    }

    function condiciones($variable, $usuario)
    {
        $consulta = Privileges::condiciones($variable, $usuario);
        foreach ($consulta as $item => $value) {
            $descripcion[$item] = $value->descripcion;
            $crea[$item] = $value->crea;
            $edita[$item] = $value->edita;
            $anula[$item] = $value->anula;
            $borra[$item] = $value->borra;
            $consultas[$item] = $value->consulta;
            $imprime[$item] = $value->imprime;
        }
        $consult = [$descripcion, $crea, $edita, $anula, $borra, $consultas, $imprime];
        return $consult;
    }

    function cargar_padres($parametro)
    {
        $extra = Privileges::cargar_padre($parametro);
        return $extra;
    }

    function cargar_modulos($parametro, $usuario)
    {
        $admin = Session::get('usuario');
        $extra = Privileges::modulos($parametro, $usuario);
        if (count($extra) == 0) {
            $variable = [];
        } else {
            foreach ($extra as $item => $value) {
                $descripcion[$item] = $value->descripcion;
                $crea[$item] = $value->crea;
                $edita[$item] = $value->edita;
                $anula[$item] = $value->anula;
                $borra[$item] = $value->borra;
                $consulta[$item] = $value->consulta;
                $imprime[$item] = $value->imprime;
            }
            $variable = array($descripcion, $crea, $edita, $anula, $borra, $consulta, $imprime);
        }
        return $variable;
    }

    function especial($parametro, $usuario)
    {
        $consulta = Menu::submodulos3($parametro, $usuario);
        return $consulta;
    }

    function menu()
    {
        $items = procesar();
        return json_decode(json_encode($items));
    }

//    function procesar(){
//        global $admin;
//
//        $item = array(
//            'Dashboard' => [
//                'text' => __('Inicio'),
//                'route' => 'home',
//                'icon' => 'home',
//                'permission' => 1,
//                'subMenu' => [],
//
//            ],
//
//            'SuperAdmin' => [
//                'text' => __('Super Administrador'),
//                'route' => 'superadmin',
//                'icon' => 'people',
//                'permission' => 1,
//                'subMenu' => [],
//
//            ],
//
//            'Companies' => [
//                'text' => __('Empresas'),
//                'route' => 'empresas',
//                'icon' => 'domain',
//                'permission' => 2,
//                'subMenu' => [],
//
//            ],
//
//            'Users' => [
//                'text' => __('Usuarios'),
//                'route' => 'users',
//                'icon' => 'account_circle',
//                'permission' => 3,
//                'subMenu' => [],
//
//            ],
//
//            'Subscription' => [
//                'text' => __('Suscripción'),
//                'route' => 'subscriptions',
//                'icon' => 'assignment_turned_in',
//                'permission' => 4,
//                'subMenu' => [],
//
//            ],
//
//            /*'Roles' => [
//                'text' => __('Roles'),
//                'route' => 'roles',
//                'icon' => 'transfer_within_a_station',
//                'permission' => 5,
//                'subMenu' => [],
//
//            ],*/);
//        if ($admin == 'ADMINISTRADOR'){
//            $item += ['Master' => [
//                'text' => __('Maestros'),
//                'route' => null,
//                'icon' => 'flaticon-menu-interface',
//                'permission' => 9,
//                'subMenu' => [
//                    'Product' => [
//                        'text' => __('Productos '),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Product catalog' => [
//                                'text' => __('Catálogo de productos ✔'),
//                                'route' => 'products',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Families' => [
//                                'text' => __('Familia ✔'),
//                                'route' => 'families',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Mark and models' => [
//                                'text' => __('Marcas y modelos ✓'),
//                                'route' => 'trademarks',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Units measurement' => [
//                                'text' => __('Unidades de medida ✓'),
//                                'route' => 'measurements',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Tariff Items' => [
//                                'text' => __('Partidas Arancelarias ✔'),
//                                'route' => 'tariffitems',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Product Group' => [
//                                'text' => __('Grupo de Productos'),
//                                'route' => 'productgroups',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Third catalog' => [
//                        'text' => __('Terceros'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => 10,
//                        'subMenu' => [
//                            'Customer catalog' => [
//                                'text' => __('Catálogo de terceros ✔'),
//                                'route' => 'customers',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'third classification' => [
//                                'text' => __('Clasificación ✔'),
//                                'route' => 'thirdclass',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'seller' => [
//                                'text' => __('Vendedores ✔'),
//                                'route' => 'seller',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Payment Conditions' => [
//                                'text' => __('Condiciones de Pago ✔'),
//                                'route' => 'paymentcondition',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Identification document' => [
//                                'text' => __('Documentos de identidad ✔'),
//                                'route' => 'identifications',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Commercial document' => [
//                                'text' => __('Documentos comerciales ✔'),
//                                'route' => 'commercials',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'transaction types' => [
//                                'text' => __('Tipos de transacción ✓'),
//                                'route' => 'transactiontypes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'road types' => [
//                                'text' => __('Tipos de via ✓'),
//                                'route' => 'roadtypes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'zone type' => [
//                                'text' => __('Tipo de zona ✓'),
//                                'route' => 'zonetype',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'type heading' => [
//                                'text' => __('Rubros ✓'),
//                                'route' => 'typeheading',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'categories ctacte' => [
//                                'text' => __('Categorías Cta.Cte ✓'),
//                                'route' => 'categoriesctacte',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Costs' => [
//                        'text' => __('Costos'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Centro de costos' => [
//                                'text' => __('Centro de costos'),
//                                'route' => 'costs',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Actividades de Costo' => [
//                                'text' => __('Actividades de Costo'),
//                                'route' => 'costactivities',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Plan de cuentas' => [
//                                'text' => __('Plan de cuentas'),
//                                'route' => 'accountingplans',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Proyectos' => [
//                                'text' => __('Proyectos'),
//                                'route' => 'projects',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Unidades de Negocio' => [
//                                'text' => __('Unidades de Negocio'),
//                                'route' => 'businessunits',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Others' => [
//                        'text' => __('Otros'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Sucursales' => [
//                                'text' => __('Sucursales ✓'),
//                                'route' => 'subsidiaries',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Almacenes' => [
//                                'text' => __('Almacenes ✔'),
//                                'route' => 'warehouses',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Monedas' => [
//                                'text' => __('Monedas ✔'),
//                                'route' => 'currencies',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Subdiarios' => [
//                                'text' => __('Subdiarios ✔'),
//                                'route' => 'subdiaries',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                ],
//
//            ],];
//            $item += ['Shopping' => [
//                'text' => __('Gestión de compras'),
//                'route' => null,
//                'icon' => 'flaticon-menu-commerce',
//                'permission' => 11,
//                'subMenu' => [
//                    'Settings' => [
//                        'text' => __('Configuración'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Detraction' => [
//                                'text' => __('Tipos detración ✓'),
//                                'route' => 'deductions',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Account document' => [
//                                'text' => __('Cuentas por documentos compras ✓'),
//                                'route' => 'accounts',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Tipos de Compra' => [
//                                'text' => __('Tipos de Compra ✓'),
//                                'route' => 'purchasetypes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Opening to pay' => [
//                                'text' => __('Apertura por pagar'),
//                                'route' => 'openingToPay',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Órdenes de Servicio' => [
//                                'text' => __('Órdenes de Servicio ✔'),
//                                'route' => 'serviceorders',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'purchase order' => [
//                                'text' => __('Ordenes de Compra ✔'),
//                                'route' => 'purchaseorder',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Provisions payable' => [
//                                'text' => __('Provisiones por pagar'),
//                                'route' => 'provisionstopay',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Credit and debit notes' => [
//                                'text' => __('Notas de créditos / débitos'),
//                                'route' => 'creditdebitnotes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Settlement of expenses' => [
//                                'text' => __('Liquidación de Gastos'),
//                                'route' => 'settlementexpenses',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Reports' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Cuenta Corriente - Proveedor' => [
//                                'text' => __('Cuenta Corriente - Proveedor'),
//                                'route' => 'currentaccounts',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Saldos por Pagar' => [
//                                'text' => __('Saldos por Pagar'),
//                                'route' => 'balancepayments',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Resumen de Compras' => [
//                                'text' => __('Resumen de Compras'),
//                                'route' => 'shoppingsummary',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Detalle de Compras' => [
//                                'text' => __('Detalle de Compras'),
//                                'route' => 'shoppingdetail',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                ],
//            ],];
//            $item += ['Sales' => [
//                'text' => __('Gestión de ventas'),
//                'route' => null,
//                'icon' => 'flaticon-menu-commerce-1',
//                'permission' => 12,
//                'subMenu' => [
//                    'Settings' => [
//                        'text' => __('Configuración'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Type sales' => [
//                                'text' => __('Tipo de venta'),
//                                'route' => 'typeSales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Documents sales' => [
//                                'text' => __('Cuentas por documentos ventas'),
//                                'route' => 'accountsDocumentsSales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Accounts by type of sales' => [
//                                'text' => __('Cuentas por tipo de venta'),
//                                'route' => 'accountsTypesSales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Payment Methods' => [
//                                'text' => __('Formas de pago'),
//                                'route' => 'paymentMethods',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Opening receivable' => [
//                                'text' => __('Apertura por cobrar'),
//                                'route' => 'openingReceivable',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Record voided documents' => [
//                                'text' => __('Documentos anulados'),
//                                'route' => 'recordVoidedDocument',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Billings' => [
//                                'text' => __('Facturación'),
//                                'route' => 'billing',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Reports' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Cuenta Corriente - Clientes' => [
//                                'text' => __('Cuenta Corriente - Cliente'),
//                                'route' => 'salescurrentaccounts',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Saldos por Cobrar' => [
//                                'text' => __('Saldos por Cobrar'),
//                                'route' => 'balancesreceivable',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Resumen de Ventas' => [
//                                'text' => __('Resumen de Ventas'),
//                                'route' => 'salessummary',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Detalle de Ventas' => [
//                                'text' => __('Detalle de Ventas'),
//                                'route' => 'salesdetails',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                ],
//            ],];
//            $item += ['Warehouse' => [
//                'text' => __('Almacenes'),
//                'route' => null,
//                'icon' => 'flaticon-menu-buildings',
//                'permission' => 13,
//                'subMenu' => [
//                    'Settings' => [
//                        'text' => __('Configuración'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Tipos de Movement' => [
//                                'text' => __('Tipos de Movement'),
//                                'route' => 'movementtype',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            'Centralización Contable' => [
//                                'text' => __('Centralización Contable'),
//                                'route' => 'accountingctnwarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ]
//                        ],
//                    ],
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Pedidos a Almacén' => [
//                                'text' => __('Pedidos al Almacén ✓'),
//                                'route' => 'ordertowarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            'Ingresos a Almacén' => [
//                                'text' => __('Ingresos a Almacén ✓'),
//                                'route' => 'incometowarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            'Salidas a Almacén' => [
//                                'text' => __('Salidas de Almacén'),
//                                'route' => 'exittowarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ]
//                        ],
//                    ],
//                    'Process' => [
//                        'text' => __('Procesos'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Valorizar Movimientos Almacén' => [
//                                'text' => __('Valorizar Movimientos Almacén'),
//                                'route' => 'movementwarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            'Ajustes Diferencia de Inventario' => [
//                                'text' => __('Ajustes Diferencia de Inventario'),
//                                'route' => 'inventorysettingswarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ]
//                        ],
//                    ],
//                    'Reports' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Kardex de Producto' => [
//                                'text' => __('Kardex de Producto'),
//                                'route' => 'productkardex',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            'Stock por Almacén' => [
//                                'text' => __('Stock por Almacén'),
//                                'route' => null,
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            'Detalle Movement de Almacén' => [
//                                'text' => __('Detalle Movement de Almacén'),
//                                'route' => null,
//                                'icon' => '',
//                                'permission' => '',
//                            ]
//                        ],
//                    ],
//                ],
//            ],];
//            $item += ['Treasury' => [
//                'text' => __('Tesorería'),
//                'route' => null,
//                'icon' => 'flaticon-menu-money',
//                'permission' => 14,
//                'subMenu' => [
//                    'Settings' => [
//                        'text' => __('Configuración'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Tipo de Operación' => [
//                                'text' => __('Tipo de Operación'),
//                                'route' => 'operationtype',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Entidades Bancarias' => [
//                                'text' => __('Entidades Bancarias'),
//                                'route' => 'bank',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Medios de Pago' => [
//                                'text' => __('Medios de Pago'),
//                                'route' => 'paymenttype',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Apertura de Bancos' => [
//                                'text' => __('Apertura de Bancos'),
//                                'route' => 'banksopening',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Movement de Caja' => [
//                                'text' => __('Movement de Caja'),
//                                'route' => 'cashmovement',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Movement de Banco' => [
//                                'text' => __('Movimientos de Bancos'),
//                                'route' => 'bankmovement',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Transferencias' => [
//                                'text' => __('Transferencias'),
//                                'route' => 'transfers',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Otras Provisiones' => [
//                                'text' => __('Otras Provisiones'),
//                                'route' => 'otherprovisions',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Reports' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Libro Caja y Banco' => [
//                                'text' => __('Libro Caja y Banco'),
//                                'route' => 'cashbankbook',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Consolidado de Bancos' => [
//                                'text' => __('Consolidado de Bancos'),
//                                'route' => 'consolidatedbank',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Relación de Cheques Emitidos' => [
//                                'text' => __('Relación de Cheques Emitidos'),
//                                'route' => 'issuedchecks',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                ],
//            ],];
//            $item += ['Accounting' => [
//                'text' => __('Contabilidad'),
//                'route' => null,
//                'icon' => 'flaticon-menu-business',
//                'permission' => 15,
//                'subMenu' => [
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Asiento de Apertura' => [
//                                'text' => __('Asiento de Apertura'),
//                                'route' => 'openingseat',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Asiento Diario' => [
//                                'text' => __('Asiento de Diario ✓'),
//                                'route' => 'dailyseat',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Ajustes por Diferencia de Cambio' => [
//                                'text' => __('Ajustes por Diferencia de Cambio'),
//                                'route' => 'adjustmentexchange',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Asiento Cierre' => [
//                                'text' => __('Asiento Cierre'),
//                                'route' => 'closingseat',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Procesos' => [
//                        'text' => __('Procesos'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Abrir/Cerrar Periodos' => [
//                                'text' => __('Abrir/Cerrar Periodos'),
//                                'route' => 'opencloseperiods',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Centralización Contable por Módulo' => [
//                                'text' => __('Centralización Contable por Módulo'),
//                                'route' => 'accountingcentralization',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Reportes' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Movement por Cuenta' => [
//                                'text' => __('Movement por Cuenta'),
//                                'route' => 'accountmovement',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Libro Diario' => [
//                                'text' => __('Libro Diario'),
//                                'route' => 'dailybook',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Libro Mayor' => [
//                                'text' => __('Libro Mayor'),
//                                'route' => 'ledger',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Libro Caja y Banco' => [
//                                'text' => __('Libro Caja y Banco'),
//                                'route' => null,
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [
//                                    'Detalle de Movimientos del Efectivo' => [
//                                        'text' => __('Detalle de Movimientos del Efectivo'),
//                                        'route' => 'detailmovementcash',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    'Detalle de Movimientos de la Cuenta Corriente' => [
//                                        'text' => __('Detalle de Movimientos de la Cuenta Corriente'),
//                                        'route' => 'detailmovementaccount',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                ],
//                            ],
//                            'Inventario y Balances' => [
//                                'text' => __('Inventario y Balances'),
//                                'route' => null,
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [
//                                    'Cajas y Bancos' => [
//                                        'text' => __('Cajas y Bancos'),
//                                        'route' => 'cashbanks',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    'Cuentas Corrientes' => [
//                                        'text' => __('Cuentas Corrientes'),
//                                        'route' => 'currentaccountreport',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    'Balance de Comprobación' => [
//                                        'text' => __('Balance de Comprobación'),
//                                        'route' => 'checkingbalance',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    'State Financieros - Anual' => [
//                                        'text' => __('State Financieros - Anual'),
//                                        'route' => 'financialstate',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                ],
//                            ],
//                            'Saldos por Cuenta' => [
//                                'text' => __('Saldos por Cuenta'),
//                                'route' => 'balancesaccount',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Validación de Asientos' => [
//                                'text' => __('Validación de Asientos'),
//                                'route' => 'seatvalidation',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                ],
//            ],];
//            $item += ['Tributary' => [
//                'text' => __('Gestión tributaria'),
//                'route' => null,
//                'icon' => 'flaticon-menu-greek',
//                'permission' => 16,
//                'subMenu' => [
//                    'Settings' => [
//                        'text' => __('Configuración'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Impuestos' => [
//                                'text' => __('Impuestos'),
//                                'route' => 'taxes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Periodos' => [
//                                'text' => __('Periodos'),
//                                'route' => 'periods',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Tipo de Cambio' => [
//                                'text' => __('Tipo de Cambio'),
//                                'route' => 'exchangerate',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'No Habidos' => [
//                                'text' => __('No Habidos'),
//                                'route' => 'nohabidos',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Contribuyentes Renuncia Exoneración I.G.V.' => [
//                                'text' => __('Contribuyentes Renuncia Exoneración I.G.V.'),
//                                'route' => 'taxexcluded',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Comprobantes de Retención' => [
//                                'text' => __('Comprobantes de Retención'),
//                                'route' => 'withholdingdocuments',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Process' => [
//                        'text' => __('Procesos'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'PDT-Operaciones con Terceros' => [
//                                'text' => __('PDT-Operaciones con Terceros'),
//                                'route' => 'operationcustomer',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'PDB-Programa de Declaración de Beneficios' => [
//                                'text' => __('PDB-Programa de Declaración de Beneficios'),
//                                'route' => 'benefitdeclaration',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'PDT 621 - IGV Renta Mensual' => [
//                                'text' => __('PDT 621 - IGV Renta Mensual'),
//                                'route' => 'monthlyincometax',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'PDT 626 - Agentes de Retención' => [
//                                'text' => __('PDT 626 - Agentes de Retención'),
//                                'route' => 'retentionagents',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Reports' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Compras' => [
//                                'text' => __('Compras'),
//                                'route' => 'purchases',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Ventas' => [
//                                'text' => __('Ventas'),
//                                'route' => 'sales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Libro de Retenciones Renta 4ta' => [
//                                'text' => __('Libro de Retenciones Renta 4ta'),
//                                'route' => 'withholdingbook',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Inventario Permanente' => [
//                                'text' => __('Inventario Permanente'),
//                                'route' => 'permanentinventory',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                ],
//            ],];
//            $item += ['Active' => [
//                'text' => __('Activos'),
//                'route' => null,
//                'icon' => 'flaticon-menu-holidays',
//                'permission' => 17,
//                'subMenu' => [
//                    'Settings' => [
//                        'text' => __('Configuración'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [
//                            'Categorias' => [
//                                'text' => __('Categorias'),
//                                'route' => 'categories',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            'Activos' => [
//                                'text' => __('Activos'),
//                                'route' => 'assets',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                        ],
//                    ],
//                    'Transaction' => [
//                        'text' => __('Transacción'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [],
//                    ],
//                    'Reports' => [
//                        'text' => __('Reportes'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [],
//                    ],
//                ],
//            ],];
//        }
//        else {
//            $maestro = cargar_modulos('01', $admin);
//            if (count($maestro) == 0){
//                $item += [];
//            }
//            else {
//                $padre = array();
//                //maestros
//                for ($i = 0; $i < count($maestro[0]); $i++) {
//                    if ($maestro[0][$i] == 0 && $maestro[1][$i] == 0 && $maestro[2][$i] == 0 && $maestro[3][$i] == 0 && $maestro[4][$i] == 0 && $maestro[5][$i] == 0) {
//                        $item += [];
//                    }
//                    else {
//                        $hijos_productos = condiciones('01.01', $admin, 6);
//                        $array_productos = [
//                            0 => [
//                                'text' => __('Catálogo de productos ✔'),
//                                'route' => 'products',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Familia ✔'),
//                                'route' => 'families',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Marcas y modelos ✓'),
//                                'route' => 'trademarks',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Unidades de medida ✓'),
//                                'route' => 'measurements',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            4 => [
//                                'text' => __('Partidas Arancelarias ✔'),
//                                'route' => 'tariffitems',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            5 => [
//                                'text' => __('Grupo de Productos'),
//                                'route' => 'productgroups',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_productos = datos($hijos_productos, $array_productos);
//                        $hijos_catalogos = condiciones('01.02',$admin, 11);
//                        $array_catalogos = [
//                            0 => [
//                                'text' => __('Catálogo de terceros ✔'),
//                                'route' => 'customers',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Clasificación ✔'),
//                                'route' => 'thirdclass',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Vendedores ✔'),
//                                'route' => 'seller',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Condiciones de Pago ✔'),
//                                'route' => 'paymentcondition',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            4 => [
//                                'text' => __('Documentos de identidad ✔'),
//                                'route' => 'identifications',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            5 => [
//                                'text' => __('Documentos comerciales ✔'),
//                                'route' => 'commercials',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            6 => [
//                                'text' => __('Tipos de transacción ✓'),
//                                'route' => 'transactiontypes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            7 => [
//                                'text' => __('Tipos de via ✓'),
//                                'route' => 'roadtypes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            8 => [
//                                'text' => __('Tipo de zona ✓'),
//                                'route' => 'zonetype',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            9 => [
//                                'text' => __('Rubros ✓'),
//                                'route' => 'typeheading',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            10 => [
//                                'text' => __('Categorías Cta.Cte ✓'),
//                                'route' => 'categoriesctacte',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_catalogos = datos($hijos_catalogos, $array_catalogos);
//                        $hijos_costos = condiciones('01.03',$admin, 5);
//                        $array_costos = [
//                            0 => [
//                                'text' => __('Centro de costos'),
//                                'route' => 'costs',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Actividades de Costo'),
//                                'route' => 'costactivities',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Plan de cuentas'),
//                                'route' => 'accountingplans',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Proyectos'),
//                                'route' => 'projects',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            4 => [
//                                'text' => __('Unidades de Negocio'),
//                                'route' => 'businessunits',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_costos = datos($hijos_costos, $array_costos);
//                        $hijos_otros = condiciones('01.04',$admin, 4);
//                        $array_otros = [
//                            0 => [
//                                'text' => __('Sucursales ✓'),
//                                'route' => 'subsidiaries',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Almacenes ✔'),
//                                'route' => 'warehouses',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Monedas ✔'),
//                                'route' => 'currencies',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Subdiarios ✔'),
//                                'route' => 'subdiaries',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_otros = datos($hijos_otros, $array_otros);
//                        $padre = cargar_padres('01', '');
//                        $terminar_proceso = datos_padre($padre, [$hijos_productos,$hijos_catalogos,$hijos_costos,$hijos_otros]);
//                        $item += ['Master' => [
//                            'text' => __('Maestros'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-interface',
//                            'permission' => 9,
//                            'subMenu' => $terminar_proceso,
//
//                        ],];
//                    }
//                }
//
//                //compras
//                $shopping = cargar_modulos('04', $admin);
//                for ($i = 0; $i < count($shopping[0]); $i++) {
//                    if ($shopping[0][$i] == 0 && $shopping[1][$i] == 0 && $shopping[2][$i] == 0 && $shopping[3][$i] == 0 && $shopping[4][$i] == 0 && $shopping[5][$i] == 0) {
//                        $item += [];
//                    } else {
//                        $hijos_configuracion = condiciones('04.01', $admin, 3);
//                        $array_configuracion = [
//                            0 => [
//                                'text' => __('Tipos detración ✓'),
//                                'route' => 'deductions',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Cuentas por documentos compras ✓'),
//                                'route' => 'accounts',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Tipos de Compra ✓'),
//                                'route' => 'purchasetypes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);
//                        $hijos_transaccion = condiciones('04.02', $admin, 5);
//                        $array_transaccion = [
//                                0 => [
//                                    'text' => __('Apertura por pagar'),
//                                    'route' => 'openingToPay',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                1 => [
//                                    'text' => __('Órdenes de Servicio ✔'),
//                                    'route' => 'serviceorders',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                2 => [
//                                    'text' => __('Ordenes de Compra ✔'),
//                                    'route' => 'purchaseorder',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                3 => [
//                                    'text' => __('Provisiones por pagar'),
//                                    'route' => 'provisionstopay',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                4 => [
//                                    'text' => __('Notas de créditos / débitos'),
//                                    'route' => 'creditdebitnotes',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                5 => [
//                                    'text' => __('Liquidación de Gastos'),
//                                    'route' => 'settlementexpenses',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
///*                        $hijos_procesos = condiciones('04.03', $admin, 0);
//                        $array_procesos = [];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);*/
//                        $hijos_reportes = condiciones('04.04', $admin, 4);
//                        $array_reportes = [
//                                0 => [
//                                    'text' => __('Cuenta Corriente - Proveedor'),
//                                    'route' => 'currentaccounts',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                1 => [
//                                    'text' => __('Saldos por Pagar'),
//                                    'route' => 'balancepayments',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                2 => [
//                                    'text' => __('Resumen de Compras'),
//                                    'route' => 'shoppingsummary',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                3 => [
//                                    'text' => __('Detalle de Compras'),
//                                    'route' => 'shoppingdetail',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],];
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('04', 'Procesos');
//                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, /*$hijos_procesos,*/ $hijos_reportes]);
//                        $item += ['Shopping' => [
//                            'text' => __('Gestión de compras'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-commerce',
//                            'permission' => 11,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//
//                //ventas
//                $sales = cargar_modulos('03', $admin);
//                for ($i = 0; $i < count($sales[0]); $i++) {
//                    if ($sales[0][$i] == 0 && $sales[1][$i] == 0 && $sales[2][$i] == 0 && $sales[3][$i] == 0 && $sales[4][$i] == 0 && $sales[5][$i] == 0) {
//                        $item += [];
//                    } else {
//                        $hijos_configuracion = condiciones('03.01', $admin, 4);
//                        $array_configuracion = [
//                            0 => [
//                                'text' => __('Tipo de venta'),
//                                'route' => 'typeSales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Cuentas por documentos ventas'),
//                                'route' => 'accountsDocumentsSales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Cuentas por tipo de venta'),
//                                'route' => 'accountsTypesSales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Formas de pago'),
//                                'route' => 'paymentMethods',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);
//                        $hijos_transaccion = condiciones('03.02', $admin, 3);
//                        $array_transaccion = [
//                                0 => [
//                                    'text' => __('Apertura por cobrar'),
//                                    'route' => 'openingReceivable',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                1 => [
//                                    'text' => __('Documentos anulados'),
//                                    'route' => 'recordVoidedDocument',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                2 => [
//                                    'text' => __('Facturación'),
//                                    'route' => 'billing',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
///*                        $hijos_procesos = condiciones('03.03', $admin, 0);
//                        $array_procesos = [];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);*/
//                        $hijos_reportes = condiciones('03.04', $admin, 4);
//                        $array_reportes = [
//                            0 => [
//                                'text' => __('Cuenta Corriente - Cliente'),
//                                'route' => 'salescurrentaccounts',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Saldos por Cobrar'),
//                                'route' => 'balancesreceivable',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Resumen de Ventas'),
//                                'route' => 'salessummary',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Detalle de Ventas'),
//                                'route' => 'salesdetails',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('03','Procesos');
//                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, /*$hijos_procesos,*/ $hijos_reportes]);
//                        $item += ['Sales' => [
//                            'text' => __('Gestión de ventas'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-commerce-1',
//                            'permission' => 12,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//
//                //almacenes
//                $warehouse = cargar_modulos('02', $admin);
//                for ($i = 0; $i < count($warehouse[0]); $i++) {
//                    if ($warehouse[0][$i] == 0 && $warehouse[1][$i] == 0 && $warehouse[2][$i] == 0 && $warehouse[3][$i] == 0 && $warehouse[4][$i] == 0 && $warehouse[5][$i] == 0) {
//                        $item += [];
//                    } else {
//                        $hijos_configuracion = condiciones('02.01', $admin, 2);
//                        $array_configuracion = [
//                            0 => [
//                                'text' => __('Tipos de Movement'),
//                                'route' => 'movementtype',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            1 => [
//                                'text' => __('Centralización Contable'),
//                                'route' => 'accountingctnwarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ]];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);
//                        $hijos_transaccion = condiciones('02.02', $admin,3);
//                        $array_transaccion = [
//                            0 => [
//                                'text' => __('Pedidos al Almacén ✓'),
//                                'route' => 'ordertowarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            1 => [
//                                'text' => __('Ingresos a Almacén ✓'),
//                                'route' => 'incometowarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            2 => [
//                                'text' => __('Salidas de Almacén'),
//                                'route' => 'exittowarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ]];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
//                        $hijos_procesos = condiciones('02.03', $admin,2);
//                        $array_procesos = [
//                            0 => [
//                                'text' => __('Valorizar Movimientos Almacén'),
//                                'route' => 'movementwarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            1 => [
//                                'text' => __('Ajustes Diferencia de Inventario'),
//                                'route' => 'inventorysettingswarehouse',
//                                'icon' => '',
//                                'permission' => '',
//                            ]];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);
//                        $hijos_reportes = condiciones('02.04', $admin, 3);
//                        $array_reportes = [
//                            0 => [
//                                'text' => __('Kardex de Producto'),
//                                'route' => 'productkardex',
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            1 => [
//                                'text' => __('Stock por Almacén'),
//                                'route' => null,
//                                'icon' => '',
//                                'permission' => '',
//                            ],
//                            2 => [
//                                'text' => __('Detalle Movement de Almacén'),
//                                'route' => null,
//                                'icon' => '',
//                                'permission' => '',
//                            ]];
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('02', '');
//                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
//                        $item += ['Warehouse' => [
//                            'text' => __('Almacenes'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-buildings',
//                            'permission' => 13,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//
//                //tesoreria
//                $treasury = cargar_modulos('05', $admin);
//                for ($i = 0; $i < count($treasury[0]); $i++) {
//                    if ($treasury[0][$i] == 0 && $treasury[1][$i] == 0 && $treasury[2][$i] == 0 && $treasury[3][$i] == 0 && $treasury[4][$i] == 0 && $treasury[5][$i] == 0) {
//                        $item += [];
//                    } else {
//                        $hijos_configuracion = condiciones('05.01', $admin, 3);
//                        $array_configuracion = [
//                            0 => [
//                                'text' => __('Tipo de Operación'),
//                                'route' => 'operationtype',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Entidades Bancarias'),
//                                'route' => 'bank',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Medios de Pago'),
//                                'route' => 'paymenttype',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);
//                        $hijos_transaccion = condiciones('05.02', $admin, 5);
//                        $array_transaccion = [
//                            0 => [
//                                'text' => __('Apertura de Bancos'),
//                                'route' => 'banksopening',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Movement de Caja'),
//                                'route' => 'cashmovement',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Movimientos de Bancos'),
//                                'route' => 'bankmovement',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Transferencias'),
//                                'route' => 'transfers',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            4 => [
//                                'text' => __('Otras Provisiones'),
//                                'route' => 'otherprovisions',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
///*                        $hijos_procesos = condiciones('05.03', $admin, 0);
//                        $array_procesos = [];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);*/
//                        $hijos_reportes = condiciones('05.04', $admin ,3);
//                        $array_reportes = [
//                            0 => [
//                                'text' => __('Libro Caja y Banco'),
//                                'route' => 'cashbankbook',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Consolidado de Bancos'),
//                                'route' => 'consolidatedbank',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Relación de Cheques Emitidos'),
//                                'route' => 'issuedchecks',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('05', 'Procesos');
//                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, /*$hijos_procesos,*/ $hijos_reportes]);
//                        $item += ['Treasury' => [
//                            'text' => __('Tesorería'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-money',
//                            'permission' => 14,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//
//                //contabilidad
//                $accounting = cargar_modulos('06', $admin);
//                for ($i = 0; $i < count($accounting[0]); $i++) {
//                    if ($accounting[0][$i] == 0 && $accounting[1][$i] == 0 && $accounting[2][$i] == 0 && $accounting[3][$i] == 0 && $accounting[4][$i] == 0 && $accounting[5][$i] == 0) {
//                        $item += [];
//                    } else {
///*                        $hijos_configuracion = condiciones('06.01', $admin, 0);
//                        $array_configuracion = [];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);*/
//                        $hijos_transaccion = condiciones('06.02', $admin, 4);
//                        $array_transaccion = [
//                            0 => [
//                                'text' => __('Asiento de Apertura'),
//                                'route' => 'openingseat',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Asiento de Diario ✓'),
//                                'route' => 'dailyseat',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Ajustes por Diferencia de Cambio'),
//                                'route' => 'adjustmentexchange',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Asiento Cierre'),
//                                'route' => 'closingseat',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
//                        $hijos_procesos = condiciones('06.03', $admin, 2);
//                        $array_procesos = [
//                            1 => [
//                                'text' => __('Abrir/Cerrar Periodos'),
//                                'route' => 'opencloseperiods',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            0 => [
//                                'text' => __('Centralización Contable por Módulo'),
//                                'route' => 'accountingcentralization',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);
//                        //reportes
//                        $especial = especial('06.04.04', $admin, 2);
//                        $array_especial = [
//                            0 => [
//                                'text' => __('Detalle de Movimientos del Efectivo'),
//                                'route' => 'detailmovementcash',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Detalle de Movimientos de la Cuenta Corriente'),
//                                'route' => 'detailmovementaccount',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $especial_hijo = datos_especial($especial, $array_especial);
//                        $especial2 = especial('06.04.05', $admin, 4);
//                        $array_especial2 = [
//                            0 => [
//                                'text' => __('Cajas y Bancos'),
//                                'route' => 'cashbanks',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Cuentas Corrientes'),
//                                'route' => 'currentaccountreport',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Balance de Comprobación'),
//                                'route' => 'checkingbalance',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('State Financieros - Anual'),
//                                'route' => 'financialstate',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $especial_hijo2 = datos_especial($especial2, $array_especial2);
//                        if (count($especial_hijo) == 0 && count($especial_hijo2) == 0){
//                            $hijos_reportes = condiciones('06.04', $admin, 4);
//                            $array_reportes = [
//                                0 => [
//                                    'text' => __('Movement por Cuenta'),
//                                    'route' => 'accountmovement',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                1 => [
//                                    'text' => __('Libro Diario'),
//                                    'route' => 'dailybook',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                2 => [
//                                    'text' => __('Libro Mayor'),
//                                    'route' => 'ledger',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                3 => [
//                                    'text' => __('Saldos por Cuenta'),
//                                    'route' => 'balancesaccount',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],
//                                4 => [
//                                    'text' => __('Validación de Asientos'),
//                                    'route' => 'seatvalidation',
//                                    'icon' => '',
//                                    'permission' => '',
//                                    'subMenu' => [],
//                                ],];
//                        }else{
//                            if (count($especial_hijo) == 0){
//                                $hijos_reportes = condiciones('06.04', $admin, 5);
//                                $array_reportes = [
//                                    0 => [
//                                        'text' => __('Movement por Cuenta'),
//                                        'route' => 'accountmovement',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    1 => [
//                                        'text' => __('Libro Diario'),
//                                        'route' => 'dailybook',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    2 => [
//                                        'text' => __('Libro Mayor'),
//                                        'route' => 'ledger',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    3 => $especial_hijo2,
//                                    4 => [
//                                        'text' => __('Saldos por Cuenta'),
//                                        'route' => 'balancesaccount',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],
//                                    5 => [
//                                        'text' => __('Validación de Asientos'),
//                                        'route' => 'seatvalidation',
//                                        'icon' => '',
//                                        'permission' => '',
//                                        'subMenu' => [],
//                                    ],];
//                            }
//                            else{
//                                if (count($especial_hijo2) == 0){
//                                    $hijos_reportes = condiciones('06.04', $admin, 5);
//                                    $array_reportes = [
//                                        0 => [
//                                            'text' => __('Movement por Cuenta'),
//                                            'route' => 'accountmovement',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        1 => [
//                                            'text' => __('Libro Diario'),
//                                            'route' => 'dailybook',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        2 => [
//                                            'text' => __('Libro Mayor'),
//                                            'route' => 'ledger',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        3 => $especial_hijo,
//                                        4 => [
//                                            'text' => __('Saldos por Cuenta'),
//                                            'route' => 'balancesaccount',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        5 => [
//                                            'text' => __('Validación de Asientos'),
//                                            'route' => 'seatvalidation',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],];
//                                }
//                                else{
//                                    $hijos_reportes = condiciones('06.04', $admin, 6);
//                                    $array_reportes = [
//                                        0 => [
//                                            'text' => __('Movement por Cuenta'),
//                                            'route' => 'accountmovement',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        1 => [
//                                            'text' => __('Libro Diario'),
//                                            'route' => 'dailybook',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        2 => [
//                                            'text' => __('Libro Mayor'),
//                                            'route' => 'ledger',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        3 => $especial_hijo,
//                                        4 => $especial_hijo2,
//                                        5 => [
//                                            'text' => __('Saldos por Cuenta'),
//                                            'route' => 'balancesaccount',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],
//                                        6 => [
//                                            'text' => __('Validación de Asientos'),
//                                            'route' => 'seatvalidation',
//                                            'icon' => '',
//                                            'permission' => '',
//                                            'subMenu' => [],
//                                        ],];
//                                }
//                            }
//                        }
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('06', 'Configuración');
//                        $terminar_proceso = datos_padre($padre, [/*$hijos_configuracion, */$hijos_transaccion, $hijos_procesos, $hijos_reportes]);
//                        $item += ['Accounting' => [
//                            'text' => __('Contabilidad'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-business',
//                            'permission' => 15,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//
//                //tributos
//                $tributary = cargar_modulos('13', $admin);
//                for ($i = 0; $i < count($tributary[0]); $i++) {
//                    if ($tributary[0][$i] == 0 && $tributary[1][$i] == 0 && $tributary[2][$i] == 0 && $tributary[3][$i] == 0 && $tributary[4][$i] == 0 && $tributary[5][$i] == 0) {
//                        $item += [];
//                    } else {
//                        $hijos_configuracion = condiciones('13.01', $admin, 5);
//                        $array_configuracion = [
//                            0 => [
//                                'text' => __('Impuestos'),
//                                'route' => 'taxes',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Periodos'),
//                                'route' => 'periods',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Tipo de Cambio'),
//                                'route' => 'exchangerate',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('No Habidos'),
//                                'route' => 'nohabidos',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            4 => [
//                                'text' => __('Contribuyentes Renuncia Exoneración I.G.V.'),
//                                'route' => 'taxexcluded',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);
//                        $hijos_transaccion = condiciones('13.02', $admin,1);
//                        $array_transaccion = [
//                            0 => [
//                                'text' => __('Comprobantes de Retención'),
//                                'route' => 'withholdingdocuments',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
//                        $hijos_procesos = condiciones('13.03', $admin, 4);
//                        $array_procesos = [
//                            0 => [
//                                'text' => __('PDT-Operaciones con Terceros'),
//                                'route' => 'operationcustomer',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('PDB-Programa de Declaración de Beneficios'),
//                                'route' => 'benefitdeclaration',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('PDT 621 - IGV Renta Mensual'),
//                                'route' => 'monthlyincometax',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('PDT 626 - Agentes de Retención'),
//                                'route' => 'retentionagents',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);
//                        $hijos_reportes = condiciones('13.04', $admin, 4);
//                        $array_reportes = [
//                            0 => [
//                                'text' => __('Compras'),
//                                'route' => 'purchases',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Ventas'),
//                                'route' => 'sales',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            2 => [
//                                'text' => __('Libro de Retenciones Renta 4ta'),
//                                'route' => 'withholdingbook',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            3 => [
//                                'text' => __('Inventario Permanente'),
//                                'route' => 'permanentinventory',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('13', '');
//                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, $hijos_procesos, $hijos_reportes]);
//                        $item += ['Tributary' => [
//                            'text' => __('Gestión tributaria'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-greek',
//                            'permission' => 16,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//
//                //activos
//                $active = cargar_modulos('07', $admin);
//                for ($i = 0; $i < count($active[0]); $i++) {
//                    if ($active[0][$i] == 0 && $active[1][$i] == 0 && $active[2][$i] == 0 && $active[3][$i] == 0 && $active[4][$i] == 0 && $active[5][$i] == 0) {
//                        $item += [];
//                    } else {
//                        $hijos_configuracion = condiciones('07.01', $admin, 2);
//                        $array_configuracion = [
//                            0 => [
//                                'text' => __('Categorias'),
//                                'route' => 'categories',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],
//                            1 => [
//                                'text' => __('Activos'),
//                                'route' => 'assets',
//                                'icon' => '',
//                                'permission' => '',
//                                'subMenu' => [],
//                            ],];
//                        $hijos_configuracion = datos($hijos_configuracion,$array_configuracion);
//                        $hijos_transaccion = condiciones('07.02', $admin, 0);
//                        $array_transaccion = [];
//                        $hijos_transaccion = datos($hijos_transaccion,$array_transaccion);
///*                        $hijos_procesos = condiciones('07.03', $admin, 0);
//                        $array_procesos = [];
//                        $hijos_procesos = datos($hijos_procesos,$array_procesos);*/
//                        $hijos_reportes = condiciones('07.04', $admin, 0);
//                        $array_reportes = [];
//                        $hijos_reportes = datos($hijos_reportes,$array_reportes);
//                        $padre = cargar_padres('07', 'Procesos');
//                        $terminar_proceso = datos_padre($padre, [$hijos_configuracion, $hijos_transaccion, /*$hijos_procesos, */$hijos_reportes]);
//                        $item += ['Active' => [
//                            'text' => __('Activos'),
//                            'route' => null,
//                            'icon' => 'flaticon-menu-holidays',
//                            'permission' => 17,
//                            'subMenu' => $terminar_proceso,
//                        ],];
//                    }
//                }
//            }
//        }
//        $item += [
//            'Archive' => [
//                'text' => __('Gestión de archivos'),
//                'route' => null,
//                'icon' => 'flaticon-menu-upload',
//                'permission' => 14,
//                'subMenu' => [
//                    'PLE' => [
//                        'text' => __('Transformación archivo PLE'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [],
//                    ],
//                    'Load' => [
//                        'text' => __('Carga archivo'),
//                        'route' => null,
//                        'icon' => '',
//                        'permission' => '',
//                        'subMenu' => [],
//                    ],
//                ],
//            ],
//        ];
//        return $item;
//    }
//    function datos($hijos, $contenido){
//        global $admin;
//        $submenu = array();
//        for ($i=0;$i<count($hijos);$i++){
//            $variable = [$hijos[$i]['descripcion'] => $contenido[$i],];
//            if ('ADMINISTRADOR' == $admin){$submenu += $variable;}else{
//                if ($hijos[$i]['crea'] == 0 && $hijos[$i]['edita'] == 0 && $hijos[$i]['anula'] == 0 && $hijos[$i]['borra'] == 0 && $hijos[$i]['consulta'] == 0 && $hijos[$i]['imprime'] == 0){
//                    $submenu += [];
//                }
//                else{
//                    $submenu += $variable;
//                }
//            }
//        }
//        return $submenu;
//    }
//    function datos_especial($hijos, $contenido){
//        global $admin;
//        $submenu = array();
//        $padre = array();
//        for ($i=0;$i<count($hijos);$i++){
//            $variable = [$hijos[$i]['descripcion'] => $contenido[$i],];
//            if ('ADMINISTRADOR' == $admin){$submenu += $variable;}else{
//                if ($hijos[$i]['crea'] == 0 && $hijos[$i]['edita'] == 0 && $hijos[$i]['anula'] == 0 && $hijos[$i]['borra'] == 0 && $hijos[$i]['consulta'] == 0 && $hijos[$i]['imprime'] == 0){
//                    $submenu += [];
//                }
//                else{
//                    $submenu += $variable;
//                }
//            }
//        }
//        if (count($submenu) == 0){
//            $padre = [];
//        }
//        else {
//            if ($hijos[0]['descripcion'] == 'Detalle Movimientos del Efectivo' || $hijos[0]['descripcion'] == "Detalle Movimientos de la Cta.Cte.") {
//                $padre = [
//                    'text' => __('Libro Caja y Banco'),
//                    'route' => null,
//                    'icon' => '',
//                    'permission' => '',
//                    'subMenu' => $submenu,
//                ];
//            }else{
//                $padre = [
//                    'text' => __('Inventario y Balances'),
//                    'route' => null,
//                    'icon' => '',
//                    'permission' => '',
//                    'subMenu' => $submenu,
//                    ];
//            }
//        }
//        return $padre;
//    }
//    function datos_padre($padres, $hijos)
//    {
//        global $admin;
//        $submenu = array();
//        for ($i = 0; $i < count($padres); $i++) {
//            $variable = [$padres[$i]['descripcion'] => [
//                'text' => __($padres[$i]['descripcion']),
//                'route' => null,
//                'icon' => '',
//                'permission' => '',
//                'subMenu' => $hijos[$i],
//            ],];
//            if ('ADMINISTRADOR' == $admin) {
//                $submenu += $variable;
//            }
//            else {
//                if (count($hijos[$i]) == 0) {
//                    $submenu += [];
//                }
//                else {
//                    $submenu += $variable;
//                }
//            }
//        }
//        return $submenu;
//    }
//    function condiciones($variable, $usuario, $limit){
//        $consulta = Privileges::condiciones($variable, $usuario, $limit);
//        return $consulta;
//    }
//    function especial ($parametro, $usuario, $limit){
//        $consulta = Privileges::especial($parametro, $usuario, $limit);
//        return $consulta;
//    }
//    function cargar_padres($parametro, $condicion){
//        $extra = Privileges::cargar_padre($parametro, $condicion);
//        return $extra;
//    }
//    function cargar_modulos($parametro, $usuario){
//        global $admin;
//        /*if ($admin == 'ADMINISTADOR'){
//            $extra = Privileges::administrador2($parametro);
//        }else{*/
//        $extra = Privileges::modulos($parametro, $usuario);
//        /*}*/
//        if (count($extra) == 0){
//            $variable = [];
//        }
//        else {
//            foreach ($extra as $item => $value) {
//                $descripcion[$item] = $value->descripcion;
//                $crea[$item] = $value->crea;
//                $edita[$item] = $value->edita;
//                $anula[$item] = $value->anula;
//                $borra[$item] = $value->borra;
//                $consulta[$item] = $value->consulta;
//                $imprime[$item] = $value->imprime;
//            }
//            $variable = array($descripcion, $crea, $edita, $anula, $borra, $consulta, $imprime);
//        }
//        return $variable;
//    }
//    function menu()
//    {
//        $items = procesar();
//        return json_decode(json_encode($items));
//    }


//    todos lo privilegios
    /*    function menu2()
        {
            $consulta = Menu::admin();
            foreach ($consulta as $item => $value){
                $codigo[$item] = explode('.', $value->codigo);
                $descripcion[$item] = $value->descripcion;
                $modulo[$item] = $value->modulo;
            }
            $item = array();
            for ($i= 0;$i<count($codigo);$i++){
                switch ($codigo[$i][0]){
                    case '01':
                        $extra = consutar_data('01');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-interface',
                            'subMenu' => $a
                        ],];break;
                    case '02':
                        $extra = consutar_data('02');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-buildings',
                            'subMenu' => $a
                        ],];break;
                    case '03':
                        $extra = consutar_data('03');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-commerce-1',
                            'subMenu' => $a
                        ],];break;
                    case '04':
                        $extra = consutar_data('04');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-commerce',
                            'subMenu' => $a
                        ],];break;
                    case '05':
                        $extra = consutar_data('05');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-money',
                            'subMenu' => $a
                        ],];break;
                    case '06':
                        $extra = consutar_data('06');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-business',
                            'subMenu' => $a
                        ],];break;
                    case '07':
                        $extra = consutar_data('07');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-holidays',
                            'subMenu' => $a
                        ],];break;
                    case '08':
                        $extra = consutar_data('08');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-interface',
                            'subMenu' => $a
                        ],];break;
                    case '12':
                        $extra = consutar_data('12');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-interface',
                            'subMenu' => $a
                        ],];break;
                    case '13':
                        $extra = consutar_data('13');
                        $a = procesar_data($extra,$modulo[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-greek',
                            'subMenu' => $a
                        ],];break;
                    case '99':
                        $extra = consutar_data('99');
                        $a = procesar_data($extra,$modulo[$i]);
                        dd($descripcion[$i]);
                        $item += [$descripcion[$i] => [
                            'text' => __($descripcion[$i]),
                            'url' =>"#",
                            'icon' => 'flaticon-menu-interface',
                            'subMenu' => $a
                        ],];break;
                }
            }
            return json_decode(json_encode($item));
        }*/
    function menu2()
    {
        $item = array();
        $item += ['Master' => [
            'text' => __('Maestros'),
            'url' => '#',
            'icon' => 'flaticon-menu-interface',
            'subMenu' => [
                'Product' => [
                    'text' => __('Productos '),
                    'url' => "#MaestrosProductos",
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Third catalog' => [
                    'text' => __('Terceros'),
                    'url' => '#MaestrosTerceros',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Costs' => [
                    'text' => __('Costos'),
                    'url' => "#MaestrosCostos",
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Others' => [
                    'text' => __('Otros'),
                    'url' => '#MaestrosOtros',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],

        ],];
        $item += ['Shopping' => [
            'text' => __('Gestión de compras'),
            'url' => '#',
            'icon' => 'flaticon-menu-commerce',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#ComprasConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#ComprasTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Procesos'),
                    'url' => '#ComprasProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#ComprasReportes',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        $item += ['Sales' => [
            'text' => __('Gestión de ventas'),
            'url' => '#',
            'icon' => 'flaticon-menu-commerce-1',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#VentasConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#VentasTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Transacción'),
                    'url' => '#VentasProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#VentasReportes',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        $item += ['Warehouse' => [
            'text' => __('Almacenes'),
            'url' => '#',
            'icon' => 'flaticon-menu-buildings',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#LogisticaConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#LogisticaTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Procesos'),
                    'url' => '#LogisticaProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#LogisticaReportes',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        $item += ['Treasury' => [
            'text' => __('Tesorería'),
            'url' => '#',
            'icon' => 'flaticon-menu-money',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#TesoreriaConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#TesoreriaTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Procesos'),
                    'url' => '#TesoreriaProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#TesoreriaReportes',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        $item += ['Accounting' => [
            'text' => __('Contabilidad'),
            'url' => '#',
            'icon' => 'flaticon-menu-business',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#ContabilidadConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#ContabilidadTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Procesos'),
                    'url' => '#ContabilidadProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#ContabilidadReportes',
                    'icon' => '',
                    'subMenu' => [
                        'Libro Caja y Banco' => [
                            'text' => __('Libro Caja y Banco'),
                            'url' => '#LibroCajaBancos',
                            'icon' => '',
                            'subMenu' => [],
                        ],
                        'Inventario y Balances' => [
                            'text' => __('Inventario y Balances'),
                            'url' => '#InventariosyBalances',
                            'icon' => '',
                            'subMenu' => [],
                        ],
                    ],
                ],
            ],
        ],];
        $item += ['Tributary' => [
            'text' => __('Gestión tributaria'),
            'url' => '#',
            'icon' => 'flaticon-menu-greek',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#TributosConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#TributosTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Procesos'),
                    'url' => '#TributosProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#TributosReportes',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        $item += ['Active' => [
            'text' => __('Activos'),
            'url' => '#',
            'icon' => 'flaticon-menu-holidays',
            'subMenu' => [
                'Settings' => [
                    'text' => __('Configuración'),
                    'url' => '#ActivosConfiguracion',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Transaction' => [
                    'text' => __('Transacción'),
                    'url' => '#ActivosTransacciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Process' => [
                    'text' => __('Procesos'),
                    'url' => '#ActivosProcesos',
                    'icon' => '',
                    'subMenu' => [],
                ],
                'Reports' => [
                    'text' => __('Reportes'),
                    'url' => '#ActivosReportes',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        $item += ['Utilitarios' => [
            'text' => __('Utilitarios'),
            'url' => '#',
            'icon' => 'flaticon-menu-holidays',
            'subMenu' => [
                'Options' => [
                    'text' => __('Opciones'),
                    'url' => '#UtilitarioOpciones',
                    'icon' => '',
                    'subMenu' => [],
                ],
            ],
        ],];
        return json_decode(json_encode($item));
    }
    /*    function procesar_data($extra,$modulo){
            $a = array();
            for ($j=0;$j<count($extra);$j++){
                $sub = eliminar_acentos($modulo.$extra[$j]);
                $a += [$extra[$j] => [
                    'text' => __($extra[$j]),
                    'url' =>str_replace(' ','',"#".$sub),
                    'icon' => '',
                ],];
            }
            return $a;
        }
        function consutar_data($variable){
            $data = Menu::admin_condicion($variable);
            foreach ($data as $item => $value) {
                $descripcion[$item] = $value->descripcion;
            }
            return $descripcion;
        }*/
}
