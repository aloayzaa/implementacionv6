<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//<!--===============================================================================================-->
//Routes login, register, recover password
Route::get('/', 'Auth\LoginController@showLoginForm')->name('enter');
Route::post('/login', 'Auth\LoginController@authenticated')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout'); //metodo de laravel modificado con conexion bd
Route::get('/register', 'Panel\UserController@create')->name('register');

Route::resource('users', 'Panel\UserController')->except(['edit', 'create']);
Route::get('verify/{token}', 'Panel\UserController@verify')->name('verify');
Route::get('verify_by_supervisor/{token}', 'Panel\UserController@verify_by_supervisor')->name('verify_admin');
Route::get('isAdmin', 'Panel\UserController@validarAdmin');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

Route::post('/companies', 'Panel\UserController@companies');
Route::post('/getperiods', 'Panel\UserController@periods');


//////////////CONSULTAS DNI Y RUC////////

Route::post('/consumer/consultar_ruc_contribuyente/{ruc}', 'ConsumerController@consultar_ruc_contribuyente');
Route::get('/consumer/consultar_dni/{dni}', 'ConsumerController@consultar_dni');
Route::get('/consumer/consulta_tcambio', 'ConsumerController@consulta_tcambio');
Route::get('/consumer/prueba', 'ConsumerController@tipocambio');
Route::get('/consumer/backup', 'ConsumerController@backup');

///////////////PANEL - CONTROLSUPERADMIN////////

Route::get('/accountingPeriod', 'HomeController@accountingPeriod')->name('accountingPeriod');
Route::get('/accountingPeriod/update', 'HomeController@accountingPeriodUpdate')->name('accountingPeriodUpdate');
Route::get('/salesPoint', 'HomeController@salesPoint')->name('salesPoint');
Route::get('/salesPoint/update', 'HomeController@salesPointUpdate')->name('salesPointUpdate');


Route::group(['middleware' => ['role:3']], function () {
    Route::prefix('superadmin')->group(function () {
        Route::get('/', 'Panel\ControlSuperAdminController@index')->name('superadmin');
        Route::get('/list', 'Panel\ControlSuperAdminController@list')->name('list.superadmin');
        Route::get('/indexcompanies/{id_estudio}', 'Panel\ControlSuperAdminController@indexcompanies')->name('index.companies');
        Route::get('/indexsuscriptions/{id_estudio}', 'Panel\ControlSuperAdminController@indexsuscriptions')->name('index.suscriptions');
        Route::get('/indexusers/{id_estudio}', 'Panel\ControlSuperAdminController@indexusers')->name('index.users');
        Route::get('/listcompaniessuperadmin/{id_estudio}', 'Panel\ControlSuperAdminController@listcompanies')->name('listCompaniesSuperAdmin');
        Route::get('/listsuscriptionssuperadmin/{id_estudio}', 'Panel\ControlSuperAdminController@listsuscriptions')->name('listSuscriptionsSuperAdmin');
        Route::get('/listuserssuperadmin/{id_estudio}', 'Panel\ControlSuperAdminController@listusers')->name('listUsersSuperAdmin');
        Route::get('/activar-anular/{id}', 'Panel\ControlSuperAdminController@activar_anular');
    });
});


Route::group(['middleware' => ['role:3,1']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

/////////////////CONEXION SPRINTER////////////////
    Route::get('/conexion/{empresa_id}', 'Panel\ConexionController@index')->name('conexion.panel');

/////////////////PANEL - EMPRESA/////////////////

    Route::prefix('empresas')->group(function () {
        Route::get('/', 'Panel\EmpresasController@index')->name('empresas');
        Route::get('/create/{estudio}', 'Panel\EmpresasController@create')->name('create.empresas');
        Route::post('/store', 'Panel\EmpresasController@store')->name('store.empresas');
        Route::get('/edit/{company}', 'Panel\EmpresasController@edit')->name('edit.empresas');
        Route::put('/{id}', 'Panel\EmpresasController@update');
        Route::get('/list', 'Panel\EmpresasController@list')->name('list.empresas');

        Route::get('/valida_ruc_no_repetido', 'Panel\EmpresasController@valida_ruc_no_repetido');
        Route::get('/agrega_cart_file', 'Panel\EmpresasController@agrega_cart_detalle_files');
        Route::get('/listar_detalle_file', 'Panel\EmpresasController@lista_file_detalle');
        Route::get('/ver_detalle_file', 'Panel\EmpresasController@ver_file_detalle');
        Route::get('/borrar_detalle_file', 'Panel\EmpresasController@borra_detalle_file');
        Route::get('/activar-anular/{id}', 'Panel\EmpresasController@activar_anular_empresa');
    });

    /////////////////PANEL - SUSCRIPCION/////////////////

    Route::prefix('subscriptions')->group(function () {
        Route::get('/', 'Panel\SuscripcionesController@index')->name('subscriptions');
        Route::get('/list', 'Panel\SuscripcionesController@list')->name('list.subscriptions');
        Route::get('/create', 'Panel\SuscripcionesController@create')->name('create.subscriptions');
        Route::post('/store', 'Panel\SuscripcionesController@store')->name('store.subscriptions');
        Route::get('/{id}/edit', 'Panel\SuscripcionesController@edit');
        Route::put('/{id}', 'Panel\SuscripcionesController@update');
        Route::post('/plan/{id}', 'Panel\SuscripcionesController@obtener_plan');

        Route::get('/check_suscription', 'Panel\SuscripcionesController@check_suscription');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', 'Panel\RolController@index')->name('roles');
        Route::get('/list', 'Panel\RolController@list')->name('list.roles');
        Route::get('/create', 'Panel\RolController@create')->name('create.roles');
        Route::post('/store', 'Panel\RolController@store')->name('store.roles');
        Route::get('/edit/{id}', 'Panel\RolController@edit')->name('edit.roles');
    });
});

Route::group(['middleware' => ['auth']], function () {
//////////////HOME////////
    Route::get('/consumer/{archivo}', 'ConsumerController@download');
    Route::get('/sprinter', 'HomeController@sprinter')->name('sprinter');

    Route::prefix('products')->group(function () {
        Route::get('/', 'ProductController@index')->name('products');
        Route::get('/list', 'ProductController@list')->name('list.products');
        Route::get('/create', 'ProductController@create')->name('create.products');
        Route::post('/store', 'ProductController@store')->name('store.products');
        Route::post('/eliminar', 'ProductController@t_eliminar_registro');
        Route::post('/anular','ProductController@t_anular_registro');
        Route::get('/edit/{product}', 'ProductController@edit')->name('edit.products');
        Route::post('/{id}', 'ProductController@update')->name('update.products');
        Route::get('/{id}/show', 'ProductController@show')->name('show.products');
        Route::get('/activar-anular/{id}', 'ProductController@activar_anular_producto');
        Route::get('/buscar_modelo', 'ProductController@buscar_modelo');
        Route::post('/filtrar_producto_sunat', 'ProductController@filtrar_producto_sunat');
        Route::get('/list_ubicacion_almacen', 'ProductController@list_ubicacion_almacen')->name('list_ubicacion_almacen.products');
        Route::get('/list_datos_npk', 'ProductController@list_datos_npk')->name('list_datos_npk.products');
        Route::get('/agregar_ubicacion_almacen', 'ProductController@agregar_ubicacion_almacen');
        Route::get('/editar_ubicacion_almacen', 'ProductController@editar_ubicacion_almacen');
        Route::get('/eliminar_ubicacion_almacen', 'ProductController@eliminar_ubicacion_almacen');
        Route::get('/buscar_codigo_sunat', 'ProductController@buscar_codigo_sunat');
        Route::get('/agregar_datos_npk', 'ProductController@agregar_datos_npk');
        Route::get('/editar_datos_npk', 'ProductController@editar_datos_npk');
        Route::get('/eliminar_datos_npk', 'ProductController@eliminar_datos_npk');
        Route::get('/buscar_partida_arancelaria', 'ProductController@buscar_partida_arancelaria');
        Route::get('/obtener_codigo_producto', 'ProductController@obtener_codigo_producto');
        Route::get('/buscar_um', 'ProductController@buscar_um');
        Route::get('/buscar_marca', 'ProductController@buscar_marca');
        Route::get('/buscar_grupoproducto', 'ProductController@buscar_grupoproducto');
        Route::get('/buscar_color', 'ProductController@buscar_color');
        Route::get('/buscar_producto', 'ProductController@buscar_producto');
        Route::get('/tipom', 'ProductController@getProductsTypeM');
        Route::get('/umedidas_producto', 'ProductController@umedidas_producto');
    });

    Route::prefix('families')->group(function () {
        Route::get('/', 'FamilyController@index')->name('families');
        Route::get('/list', 'FamilyController@list')->name('list.families');
        Route::get('/create', 'FamilyController@create')->name('create.families');
        Route::post('/store', 'FamilyController@store')->name('store.families');
        Route::put('/{id}', 'FamilyController@update')->name('update.families');
        Route::get('/{id}/edit', 'FamilyController@edit')->name('edit.families');
        Route::get('/producto_sunat', 'FamilyController@producto_sunat');
        Route::get('/estado', 'FamilyController@t_aprobar')->name('estado.families');
        Route::post('/eliminar', 'FamilyController@t_eliminar_registro');
        Route::post('/anular','FamilyController@t_anular_registro');
    });

    Route::prefix('trademarks')->group(function () {
        Route::get('/', 'MarcaController@index')->name('trademarks');
        Route::get('/list', 'MarcaController@list')->name('list.trademarks');
        Route::get('/create', 'MarcaController@create')->name('create.trademarks');
        Route::post('/store', 'MarcaController@store')->name('store.trademarks');
        Route::get('/{id}/edit', 'MarcaController@edit')->name('edit.trademarks');
        Route::put('/{id}', 'MarcaController@update')->name('update.trademarks');
        Route::get('/activar-anular/{id}', 'MarcaController@activar_anular_marca');
        Route::get('/lista_modelo', 'MarcaController@lista_modelo')->name('lista_modelo.trademarks');
        Route::get('/autogenerar_codigo', 'MarcaController@autogenerar_codigo')->name('autogenerar_codigo.trademarks');
        Route::get('/agregar_item', 'MarcaController@agregar_item')->name('agregaritem.trademarks');
        Route::get('/update_item', 'MarcaController@update_item')->name('update_item.trademarks');
        Route::get('/eliminar_modelo', 'MarcaController@eliminar_modelo');
        Route::post('/eliminar', 'MarcaController@t_eliminar_registro');
        Route::post('/anular','MarcaController@t_anular_registro');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', 'CustomerController@index')->name('customers');
        Route::get('/list', 'CustomerController@list')->name('list.customers');
        Route::get('/create', 'CustomerController@create')->name('create.customers');
        Route::post('/valida_codigo', 'CustomerController@valida_codigo');
        Route::post('/listar_detalle', 'CustomerController@listar_detalle');
        Route::post('/locales_anexos/{ruc}/{instancia}', 'CustomerController@locales_anexos');
        Route::post('/store', 'CustomerController@store')->name('store.customers');
        Route::get('/{id}/edit', 'CustomerController@edit')->name('edit.customers');
        Route::put('/{id}', 'CustomerController@update')->name('update.customers');
        Route::get('/activar-anular/{id}', 'CustomerController@activar_anular_producto');
        Route::post('/obtiene_ubigeo', 'CustomerController@obtiene_ubigeo');
        Route::post('/obtiene_pais', 'CustomerController@obtiene_pais');
        Route::post('/ver_detalle_sucursal', 'CustomerController@ver_detalle_sucursal');
        Route::post('/editar_detalle_sucursal', 'CustomerController@editar_detalle_sucursal');
        Route::post('/borrar_item', 'CustomerController@borrar_item');
        Route::get('/buscar_ubigeo', 'CustomerController@buscar_ubigeo');
        Route::get('/buscar_pais', 'CustomerController@buscar_pais');
        Route::get('/list_contactos', 'CustomerController@list_datos_npk')->name('list_contactos.customers');
        Route::get('/agregar_contactos', 'CustomerController@agregar_contactos');
        Route::get('/editar_contactos', 'CustomerController@editar_contactos');
        Route::get('/buscar_condicion_pago','CustomerController@buscar_condicion_pago');
        Route::get('/buscar_vendedor_cobrador','CustomerController@buscar_vendedor_cobrador');
        Route::get('/buscar_sucursal','CustomerController@buscar_sucursal');
        Route::get('/list_cuentas_bancarias','CustomerController@list_cuentas_bancarias')->name('list_cuentas_bancarias.customers');
        Route::get('/agregar_cuentas_bancarias','CustomerController@agregar_cuentas_bancarias');
        Route::get('/editar_cuentas_bancarias','CustomerController@editar_cuentas_bancarias');
        Route::get('/buscar_marca_tercero','CustomerController@buscar_marca_tercero');
        Route::get('/list_tercero_marca','CustomerController@list_tercero_marca')->name('list_tercero_marca.customers');
        Route::get('/agregar_tercero_marca','CustomerController@agregar_tercero_marca');
        Route::get('/editar_tercero_marca','CustomerController@editar_tercero_marca');
        Route::get('/list_tercero_rubro','CustomerController@list_tercero_rubro')->name('list_tercero_rubro.customers');
        Route::get('/agregar_tercero_rubro','CustomerController@agregar_tercero_rubro');
        Route::get('/editar_tercero_rubro','CustomerController@editar_tercero_rubro');
        Route::get('/eliminar_carrito','CustomerController@eliminar_carrito');
        Route::get('/list_tercero_direccion','CustomerController@list_tercero_direccion')->name('list_tercero_direccion.customers');
        Route::get('/agregar_tercero_direccion','CustomerController@agregar_tercero_direccion');
        Route::get('/editar_tercero_direccion','CustomerController@editar_tercero_direccion');
        Route::get('/list_tercero_empresa','CustomerController@list_tercero_empresa')->name('list_tercero_empresa.customers');
        Route::get('/agregar_tercero_empresa','CustomerController@agregar_tercero_empresa');
        Route::get('/editar_tercero_empresa','CustomerController@editar_tercero_empresa');
        Route::post('/eliminar', 'CustomerController@t_eliminar_registro');
        Route::post('/anular','CustomerController@t_anular_registro');
        Route::get('/buscar_tercero','CustomerController@buscar_tercero');
    });

    Route::prefix('identifications')->group(function () {
        Route::get('/', 'IdentificationController@index')->name('identifications');
        Route::get('/list', 'IdentificationController@list')->name('list.identifications');
        Route::get('/create', 'IdentificationController@create')->name('create.identifications');
        Route::post('/store', 'IdentificationController@store')->name('store.identifications');
        Route::get('/{id}/edit', 'IdentificationController@edit')->name('edit.identifications');
        Route::put('/{id}', 'IdentificationController@update')->name('update.identifications');
        Route::get('/estado', 'IdentificationController@t_aprobar')->name('estado.identifications');
    });

    Route::prefix('commercials')->group(function () {
        Route::get('/', 'CommercialController@index')->name('commercials');
        Route::get('/list', 'CommercialController@list')->name('list.commercials');
        Route::get('/{id}/edit', 'CommercialController@edit')->name('edit.commercials');
        Route::put('/{id}', 'CommercialController@update')->name('update.commercials');
        Route::get('/create', 'CommercialController@create')->name('create.commercials');
        Route::post('/store', 'CommercialController@store')->name('store.commercials');
    });

    Route::prefix('measurements')->group(function () {
        Route::get('/', 'MeasurementController@index')->name('measurements');
        Route::get('/list', 'MeasurementController@list')->name('list.measurements');
        Route::get('/create', 'MeasurementController@create')->name('create.measurements');
        Route::get('/list_unidadmedida', 'MeasurementController@list_unidadmedida')->name('list_unidadmedida.measurements');
        Route::post('/store', 'MeasurementController@store')->name('store.measurements');
        Route::get('/{id}/edit', 'MeasurementController@edit')->name('edit.measurements');
        Route::put('/{id}', 'MeasurementController@update')->name('update.measurements');
        Route::get('/agregar_item', 'MeasurementController@agregar_item')->name('agregaritem.measurements');
        Route::get('/eliminar_unidad_conversion', 'MeasurementController@eliminar_unidad_conversion');
        Route::get('/update_item', 'MeasurementController@update_item')->name('update_item.MeasurementController');
        Route::post('/eliminar', 'MeasurementController@t_eliminar_registro');
        Route::post('/anular','MeasurementController@t_anular_registro');

        Route::get('/unidad_medida/{id}', 'MeasurementController@unidad_medida');


    });

    Route::prefix('costs')->group(function () {
        Route::get('/', 'CostsController@index')->name('costs');
        Route::get('/list', 'CostsController@list')->name('list.costs');
        Route::get('/create', 'CostsController@create')->name('create.costs');
        Route::post('/store', 'CostsController@store')->name('store.costs');
        Route::get('/{id}/edit', 'CostsController@edit')->name('edit.costs');
        Route::put('/{id}', 'CostsController@update')->name('update.costs');;
        Route::get('/activar-anular/{id}', 'CostsController@activar');
    });

    Route::prefix('costactivities')->group(function () {
        Route::get('/', 'CostActivitiesController@index')->name('costactivities');
        Route::get('/list', 'CostActivitiesController@list')->name('list.costactivities');
        Route::get('/create', 'CostActivitiesController@create')->name('create.costactivities');
        Route::post('/store', 'CostActivitiesController@store')->name('store.costactivities');
        Route::get('/{id}/edit', 'CostActivitiesController@edit')->name('edit.costactivities');
        Route::put('/{id}', 'CostActivitiesController@update')->name('update.costactivities');
        Route::get('/activar-anular/{id}', 'CostActivitiesController@activar');
    });

    Route::prefix('accountingplans')->group(function () {
        Route::get('/', 'AccountingPlanController@index')->name('accountingplans');
        Route::get('/list', 'AccountingPlanController@list')->name('list.accountingplans');
        Route::get('/create', 'AccountingPlanController@create')->name('create.accountingplans');
        Route::post('/store', 'AccountingPlanController@store')->name('store.accountingplans');
        Route::get('/{id}/edit', 'AccountingPlanController@edit')->name('edit.accountingplans');
        Route::put('/{id}', 'AccountingPlanController@update')->name('update.accountingplans');;
        Route::post('/eliminar', 'AccountingPlanController@t_eliminar_registro');
        Route::post('/anular','AccountingPlanController@t_anular_registro');
        Route::get('/pcgs','AccountingPlanController@pcgs');
    });

    Route::prefix('projects')->group(function () {
        Route::get('/', 'ProjectController@index')->name('projects');
        Route::get('/list', 'ProjectController@list')->name('list.projects');
        Route::get('/create', 'ProjectController@create')->name('create.projects');
        Route::post('/store', 'ProjectController@store')->name('store.projects');
        Route::get('/{id}/edit', 'ProjectController@edit')->name('edit.projects');
        Route::put('/{id}', 'ProjectController@update');
        Route::get('/activar-anular/{id}', 'ProjectController@activar');
    });

    Route::prefix('businessunits')->group(function () {
        Route::get('/', 'BusinessUnitsController@index')->name('businessunits');
        Route::get('/list', 'BusinessUnitsController@list')->name('list.businessunits');
        Route::get('/create', 'BusinessUnitsController@create')->name('create.businessunits');
        Route::post('/store', 'BusinessUnitsController@store')->name('store.businessunits');
        Route::get('/{id}/edit', 'BusinessUnitsController@edit')->name('edit.businessunits');
        Route::put('/{id}', 'BusinessUnitsController@update');
        Route::get('/activar-anular/{id}', 'BusinessUnitsController@activar');
    });

    Route::prefix('subsidiaries')->group(function () {
        Route::get('/', 'SubsidiariesController@index')->name('subsidiaries');
        Route::get('/list', 'SubsidiariesController@list')->name('list.subsidiaries');
        Route::get('/create', 'SubsidiariesController@create')->name('create.subsidiaries');
        Route::post('/store', 'SubsidiariesController@store')->name('store.subsidiaries');
        Route::get('/{id}/edit', 'SubsidiariesController@edit')->name('edit.subsidiaries');
        Route::put('/{id}', 'SubsidiariesController@update')->name('update.subsidiaries');
        Route::get('/activar-anular/{id}', 'SubsidiariesController@activar');
        Route::post('/eliminar', 'SubsidiariesController@t_eliminar_registro');
        Route::post('/anular','SubsidiariesController@t_anular_registro');
    });

    Route::prefix('currencies')->group(function () {
        Route::get('/', 'CurrencyController@index')->name('currencies');
        Route::get('/list', 'CurrencyController@list')->name('list.currencies');
        Route::get('/create', 'CurrencyController@create')->name('create.currencies');
        Route::post('/store', 'CurrencyController@store')->name('store.currencies');
        Route::get('/{id}/edit', 'CurrencyController@edit')->name('edit.currencies');
        Route::put('/{id}', 'CurrencyController@update')->name('update.currencies');;
        Route::get('/activar-anular/{id}', 'CurrencyController@activar');
        Route::post('/eliminar', 'CurrencyController@t_eliminar_registro');
        Route::post('/anular','CurrencyController@t_anular_registro');
    });

    Route::prefix('warehouses')->group(function () {
        Route::get('/', 'WarehousesController@index')->name('warehouses');
        Route::get('/list', 'WarehousesController@list')->name('list.warehouses');
        Route::get('/create', 'WarehousesController@create')->name('create.warehouses');
        Route::post('/store', 'WarehousesController@store')->name('store.warehouses');
        Route::get('/{id}/edit', 'WarehousesController@edit')->name('edit.warehouses');
        Route::put('/{id}', 'WarehousesController@update')->name('update.warehouses');
        Route::get('/activar-anular/{id}', 'WarehousesController@activar');
        Route::post('/eliminar', 'WarehousesController@t_eliminar_registro');
        Route::post('/anular','WarehousesController@t_anular_registro');
    });

    Route::prefix('subdiaries')->group(function () {
        Route::get('/', 'SubdiariesController@index')->name('subdiaries');
        Route::get('/list', 'SubdiariesController@list')->name('list.subdiaries');
        Route::get('/create', 'SubdiariesController@create')->name('create.subdiaries');
        Route::post('/store', 'SubdiariesController@store')->name('store.subdiaries');
        Route::get('/{id}/edit', 'SubdiariesController@edit')->name('edit.subdiaries');
        Route::put('/{id}', 'SubdiariesController@update')->name('update.subdiaries');
        Route::get('/activar-anular/{id}', 'SubdiariesController@activar');
        Route::post('/eliminar', 'SubdiariesController@t_eliminar_registro');
        Route::post('/anular','SubdiariesController@t_anular_registro');
    });

    Route::prefix('deductions')->group(function () {
        Route::get('/', 'DeductionController@index')->name('deductions');
        Route::get('/list', 'DeductionController@list')->name('list.deductions');
        Route::get('/create', 'DeductionController@create')->name('create.deductions');
        Route::post('/store', 'DeductionController@store')->name('store.deductions');
        Route::get('/{id}/edit', 'DeductionController@edit')->name('edit.deductions');
        Route::put('/{id}', 'DeductionController@update')->name('update.deductions');
        Route::post('/eliminar', 'DeductionController@t_eliminar_registro');
        Route::post('/anular','DeductionController@t_anular_registro');
    });

    Route::prefix('accounts')->group(function () {
        Route::get('/', 'DocumentAccountController@index')->name('accounts');
        Route::get('/list', 'DocumentAccountController@list')->name('list.accounts');
        Route::get('/{id}/edit', 'DocumentAccountController@edit')->name('edit.accounts');
        Route::put('/{id}', 'DocumentAccountController@update')->name('update.accounts');
        Route::get('/buscar_accounts', 'DocumentAccountController@buscar_accounts');
    });

    Route::prefix('currentaccounts')->group(function () {
        Route::get('/', 'CurrentAccountsController@index')->name('currentaccounts');
        Route::get('/list', 'CurrentAccountsController@list')->name('list.currentaccounts');
    });

    Route::prefix('salescurrentaccounts')->group(function () {
        Route::get('/', 'SalesCurrentAccountsController@index')->name('salescurrentaccounts');
        Route::get('/list', 'SalesCurrentAccountsController@list')->name('list.salescurrentaccounts');
    });

    Route::prefix('balancepayments')->group(function () {
        Route::get('/', 'BalancePaymentsController@index')->name('balancepayments');
        Route::get('/list', 'BalancePaymentsController@list')->name('list.balancepayments');
        Route::get('/consultar_detalle', 'BalancePaymentsController@consultar_detalle')->name('consultar_detalle.balancepayments');
    });

    Route::prefix('shoppingsummary')->group(function () {
        Route::get('/', 'ShoppingSummaryController@index')->name('shoppingsummary');
        Route::get('/rptcompras', 'ShoppingSummaryController@rptcompras')->name('rptcompras.shoppingsummary');
        Route::get('/periodos', 'ShoppingSummaryController@periodos')->name('periodos.shoppingsummary');
    });

    Route::prefix('shoppingdetail')->group(function () {
        Route::get('/', 'ShoppingDetailController@index')->name('shoppingdetail');
        Route::get('/rptcompras', 'ShoppingDetailController@rptcompras')->name('rptcompras.shoppingdetail');
    });

    Route::prefix('ProvisionesPorPagar')->group(function () {
        Route::get('/', 'ProvisionsToPayController@index')->name('provisionstopay');
        Route::get('/list', 'ProvisionsToPayController@list')->name('list.provisionstopay');
        Route::get('/create', 'ProvisionsToPayController@create')->name('create.provisionstopay');
        Route::post('/store', 'ProvisionsToPayController@store')->name('store.provisionstopay');
        Route::get('/{id}/edit', 'ProvisionsToPayController@edit')->name('edit.provisionstopay');
        Route::put('/update', 'ProvisionsToPayController@update')->name('update.provisionstopay');
        Route::post('/comprobar_estado','ProvisionsToPayController@t_comprobar_estado');
        Route::post('/eliminar', 'ProvisionsToPayController@t_eliminar_registro');
        Route::post('/anular','ProvisionsToPayController@t_anular_registro');

        Route::get('/activar-anular/{id}', 'ProvisionsToPayController@activar_anular_provisionstopay');
        Route::get('/rucproveedor', 'ProvisionsToPayController@rucproveedor');
        Route::post('/listar_detalle', 'ProvisionsToPayController@listar_detalle');
        Route::get('/verificardocumento', 'ProvisionsToPayController@verifica_documento_registrado');
        Route::get('/calculadetraccion', 'ProvisionsToPayController@calcula_detraccion');
        Route::get('/cuenta_medida', 'ProvisionsToPayController@cuenta_medida');
        Route::post('/agregar_detalle_provision', 'ProvisionsToPayController@agregar_detalle_provision');
        Route::post('/edita_detalle_provision', 'ProvisionsToPayController@edita_detalle_provision');
        Route::post('/ver_detalle_provision', 'ProvisionsToPayController@ver_detalle_provision');
        Route::post('/borrar_item', 'ProvisionsToPayController@borrar_detalle_provision');
        Route::post('/totalizar_provision', 'ProvisionsToPayController@totalizar');

        Route::get('/listar_carrito', 'ProvisionsToPayController@listar_carrito')->name('listarcarrito.provisionstopay');
        Route::post('/update_carrito', 'ProvisionsToPayController@update_carrito')->name('updatecarrito.provisionstopay');
        Route::post('/eliminar_item', 'ProvisionsToPayController@eliminar_item')->name('eliminaritem.provisionstopay');

        Route::get('/condicionpago', 'ProvisionsToPayController@condicionpago')->name('condicionpago.provisionstopay');

        Route::get('/ordenescompra/{id}', 'ProvisionsToPayController@getordenescompra')->name('ordenescompra.provisionstopay');
        Route::get('/ordendetail/{id}', 'ProvisionsToPayController@ordendetail')->name('ordendetail.provisionstopay');
        Route::post('/addordencompra', 'ProvisionsToPayController@insertar_ordencompra')->name('insertar_ordencompra.provisionstopay');

        Route::get('/references/{id}', 'ProvisionsToPayController@references')->name('references.provisionstopay');
        Route::post('/addreferences', 'ProvisionsToPayController@insertar_referencia')->name('insertar_referencia.provisionstopay');

        Route::post('/importes', 'ProvisionsToPayController@aplicar_importes')->name('aplicar_importes.provisionstopay');

        Route::get('/ctactebanco', 'ProvisionsToPayController@ctactebanco');
        Route::get('/getporcentaje', 'ProvisionsToPayController@getporcentaje');
        Route::get('/getdetraccion', 'ProvisionsToPayController@getdetraccion');
        Route::get('/historial/{id}', 'ProvisionsToPayController@historial');

        Route::post('/agregar_cuenta', 'ProvisionsToPayController@agregar_cuenta');

    });

    Route::prefix('creditdebitnotes')->group(function () {
        Route::get('/', 'CreditDebitNotesController@index')->name('creditdebitnotes');
        Route::get('/list', 'CreditDebitNotesController@list')->name('list.creditdebitnotes');
        Route::get('/create', 'CreditDebitNotesController@create')->name('create.creditdebitnotes');
        Route::post('/store', 'CreditDebitNotesController@store')->name('store.creditdebitnotes');
        Route::get('/{id}/edit', 'CreditDebitNotesController@edit')->name('edit.creditdebitnotes');
        Route::put('/{id}', 'CreditDebitNotesController@update');
        Route::get('/activar-anular/{id}', 'CreditDebitNotesController@activar_anular_creditdebitnote');
        Route::get('/listreferences', 'CreditDebitNotesController@obtener_referencia_ncd')->name('list.references');
        Route::get('/listreferencesdetails', 'CreditDebitNotesController@obtener_referenciadetalle_ncd')->name('list.referencesdetails');
        Route::get('/customerdata', 'CreditDebitNotesController@customerdata');
        Route::get('/verificardocumento', 'CreditDebitNotesController@verifica_documento_registrado');
        Route::post('/total_instancia_change', 'CreditDebitNotesController@total_instancia_change');
        Route::get('/obtener_datos_docxpagar', 'CreditDebitNotesController@obtener_datos_docxpagar');
        Route::get('/agregar_carro_nota_credito_referencia', 'CreditDebitNotesController@agregar_carro_nota_credito_referencia');

    });

    Route::prefix('purchasetypes')->group(function () {
        Route::get('/', 'PurchaseTypesController@index')->name('purchasetypes');
        Route::get('/list', 'PurchaseTypesController@list')->name('list.purchasetypes');
        Route::get('/create', 'PurchaseTypesController@create')->name('create.purchasetypes');
        Route::post('/store', 'PurchaseTypesController@store')->name('store.purchasetypes');;
        Route::get('/{id}/edit', 'PurchaseTypesController@edit')->name('edit.purchasetypes');
        Route::put('/{id}', 'PurchaseTypesController@update')->name('update.purchasetypes');;
        Route::get('/activar-anular/{id}', 'PurchaseTypesController@activar');
        Route::post('/eliminar', 'PurchaseTypesController@t_eliminar_registro');
        Route::post('/anular','PurchaseTypesController@t_anular_registro');
    });

    Route::prefix('expenses')->group(function () {
        Route::get('/', 'ExpenseController@index')->name('expenses');
        Route::get('/list', 'ExpenseController@list')->name('list.expenses');
        Route::get('/create', 'ExpenseController@create')->name('create.expenses');
        Route::post('/store', 'ExpenseController@store');
        Route::get('/{id}/edit', 'ExpenseController@edit')->name('edit.expenses');
        Route::put('/{id}', 'ExpenseController@update');
    });

    Route::prefix('openingToPay')->group(function () {
        Route::get('/', 'OpeningToPayController@index')->name('openingToPay');
        Route::get('/list', 'OpeningToPayController@list')->name('list.openingToPay');
        Route::get('/create', 'OpeningToPayController@create')->name('create.openingToPay');
        Route::post('/store', 'OpeningToPayController@store');
        Route::get('/{id}/edit', 'OpeningToPayController@edit')->name('edit.openingToPay');
        Route::put('/{id}', 'OpeningToPayController@update');
        Route::get('/{id}/show', 'OpeningToPayController@show')->name('show.openingToPay');
        Route::get('/customer/validate', 'OpeningToPayController@validateCustomer')->name('validate.openingToPay');
        Route::get('/calculate/igv', 'OpeningToPayController@calculateIgv')->name('calculateIgv.openingToPay');
        Route::get('/numberSerie', 'OpeningToPayController@numberSerie')->name('number.openingToPay');
        Route::get('/activar-anular/{id}', 'OpeningToPayController@activeCancel');
    });

    Route::prefix('taxes')->group(function () {
        Route::get('/', 'TaxesController@index')->name('taxes');
        Route::get('/list', 'TaxesController@list')->name('list.taxes');
        Route::get('/create', 'TaxesController@create')->name('create.taxes');
        Route::post('/store', 'TaxesController@store');
        Route::get('/{id}/edit', 'TaxesController@edit')->name('edit.taxes');
        Route::put('/{id}', 'TaxesController@update');
        Route::get('/activar-anular/{id}', 'TaxesController@activate');
        Route::get('/calcularigv', 'TaxesController@calcularigv');
        Route::get('/calcularconigv', 'TaxesController@calcularconigv');
        Route::get('/calcularrent', 'TaxesController@calcularrent');

    });

    Route::prefix('typeSales')->group(function () {
        Route::get('/', 'TypeSaleController@index')->name('typeSales');
        Route::get('/list', 'TypeSaleController@list')->name('list.typeSales');
        Route::get('/create', 'TypeSaleController@create')->name('create.typeSales');
        Route::post('/store', 'TypeSaleController@store')->name('store.typeSales');
        Route::get('/{id}/edit', 'TypeSaleController@edit')->name('edit.typeSales');
        Route::put('/{id}', 'TypeSaleController@update')->name('update.typeSales');
        //Route::get('/{id}/show', 'TypeSaleController@show')->name('show.typeSales');
        //Route::get('/activar-anular/{id}', 'TypeSaleController@activeCancel');
        Route::post('/eliminar', 'TypeSaleController@t_eliminar_registro');
        Route::post('/anular','TypeSaleController@t_anular_registro');
    });

    Route::prefix('accountsDocumentsSales')->group(function () {
        Route::get('/', 'DocumentAccountSaleController@index')->name('accountsDocumentsSales');
        Route::get('/list', 'DocumentAccountSaleController@list')->name('list.accountsDocumentsSales');
        Route::get('/{id}/edit', 'DocumentAccountSaleController@edit')->name('edit.accountsDocumentsSales');
        Route::put('/{id}', 'DocumentAccountSaleController@update');
        Route::get('/buscar_accounts', 'DocumentAccountSaleController@buscar_accounts');
    });

    Route::prefix('accountsTypesSales')->group(function () {
        Route::get('/', 'AccountsTypesSalesController@index')->name('accountsTypesSales');
        Route::get('/list', 'AccountsTypesSalesController@list')->name('list.accountsTypesSales');
        Route::get('/{id}/edit', 'AccountsTypesSalesController@edit')->name('edit.accountsTypesSales');
        Route::put('/{id}', 'AccountsTypesSalesController@update')->name('update.accountsTypesSales');
        Route::get('/buscar_accounts', 'AccountsTypesSalesController@buscar_accounts');
    });

    Route::prefix('paymentMethods')->group(function () {
        Route::get('/', 'PaymentMethodsController@index')->name('paymentMethods');
        Route::get('/list', 'PaymentMethodsController@list')->name('list.paymentMethods');
        Route::get('/create', 'PaymentMethodsController@create')->name('create.paymentMethods');
        Route::post('/store', 'PaymentMethodsController@store')->name('store.paymentMethods');
        Route::get('/edit/{id}', 'PaymentMethodsController@edit')->name('edit.paymentMethods');
        Route::put('/update/{id}', 'PaymentMethodsController@update')->name('update.paymentMethods');
        Route::post('/eliminar', 'PaymentMethodsController@t_eliminar_registro');
        Route::post('/anular','PaymentMethodsController@t_anular_registro');
    });

    Route::prefix('openingReceivable')->group(function () {
        Route::get('/', 'OpeningReceivableController@index')->name('openingReceivable');
        Route::get('/list', 'OpeningReceivableController@list')->name('list.openingReceivable');
        Route::get('/create', 'OpeningReceivableController@create')->name('create.openingReceivable');
        Route::post('/store', 'OpeningReceivableController@store')->name('store.openingReceivable');
        Route::get('/{id}/edit', 'OpeningReceivableController@edit')->name('edit.openingReceivable');
        Route::put('/{id}', 'OpeningReceivableController@update');
        Route::get('/{id}/show', 'OpeningReceivableController@show')->name('show.openingReceivable');
        Route::get('/customer/validate', 'OpeningReceivableController@validateCustomer')->name('validate.openingReceivable');
        Route::get('/calculate/igv', 'OpeningReceivableController@calculateIgv')->name('calculateIgv.openingReceivable');
        Route::get('/numberSerie', 'OpeningReceivableController@numberSerie')->name('number.openingReceivable');
        Route::get('/activar-anular/{id}', 'OpeningReceivableController@activeCancel');
    });

    Route::prefix('recordVoidedDocument')->group(function () {
        Route::get('/', 'RecordVoidedDocumentController@index')->name('recordVoidedDocument');
        Route::get('/list', 'RecordVoidedDocumentController@list')->name('list.recordVoidedDocument');
        Route::get('/create', 'RecordVoidedDocumentController@create')->name('create.recordVoidedDocument');
        Route::post('/store', 'RecordVoidedDocumentController@store');
//        Route::get('/{id}/edit', 'RecordVoidedDocumentController@edit')->name('edit.recordVoidedDocument');
//        Route::put('/{id}', 'RecordVoidedDocumentController@update');
//        Route::get('/{id}/show', 'RecordVoidedDocumentController@show')->name('show.recordVoidedDocument');
//        Route::get('/customer/validate', 'RecordVoidedDocumentController@validateCustomer')->name('validate.recordVoidedDocument');
//        Route::get('/calculate/igv', 'RecordVoidedDocumentController@calculateIgv')->name('calculateIgv.recordVoidedDocument');
//        Route::get('/numberSerie', 'RecordVoidedDocumentController@numberSerie')->name('number.recordVoidedDocument');
        Route::get('/activar-anular/{id}', 'RecordVoidedDocumentController@activeCancel');
    });

    Route::prefix('billing')->group(function () {
        Route::get('/', 'BillingController@index')->name('billing');//ok
        Route::get('/list', 'BillingController@list')->name('list.billing');//ok
        Route::get('/create', 'BillingController@create')->name('create.billing');//ok
        Route::post('/store', 'BillingController@store')->name('store.billing'); //ok
        Route::get('/{id}/edit', 'BillingController@edit')->name('edit.billing'); //ok
        Route::put('/{id}', 'BillingController@update')->name('update.billing'); //ok
        Route::get('/mostrar_referencias', 'BillingController@mostrar_referencias'); //ok
        Route::get('/mostrar_referencias_detalle', 'BillingController@mostrar_referencias_detalle'); //ok
        Route::post('/agregar_documentos_referencia', 'BillingController@agregar_documentos_referencia'); //ok
        Route::get('/tercero_direccion/{tercero_id}', 'BillingController@tercero_direccion');//ok
        Route::get('/validatipodoc', 'BillingController@validatipodoc');//ok
        Route::get('/lista_detalles', 'BillingController@lista_detalles');//ok
        Route::get('/buscar_producto', 'BillingController@buscar_producto');//ok
        Route::get('/existe_stock/', 'BillingController@existe_stock');//ok
        Route::get('/valida_serie', 'BillingController@valida_serie');//ok
        Route::get('/validar_cantidad', 'BillingController@validar_cantidad');//ok
        Route::post('/agregar_productos', 'BillingController@agregar_productos'); //ok
        Route::get('/valida_puntoventa', 'BillingController@valida_puntoventa'); // ok
        Route::get('/valida_tipoventa', 'BillingController@valida_tipoventa'); // ok
        Route::get('/validar_precio', 'BillingController@validar_precio'); // ok
        Route::get('/validar_descuento', 'BillingController@validar_descuento'); // ok
        Route::post('/eliminar_docxpagar_detalle', 'BillingController@eliminar_docxpagar_detalle');// ok
        Route::get('/validar_serie_documento', 'BillingController@validar_serie_documento');// ok
        Route::get('/validar_fecha_documento', 'BillingController@validar_fecha_documento');// ok
        Route::get('/condicion_pago', 'BillingController@condicion_pago');// ok
        Route::get('/mostrar_credito_debito', 'BillingController@mostrar_credito_debito');// ok
        Route::get('/listado_notacreditodebito_cabecera', 'BillingController@listado_notacreditodebito_cabecera');// ok
        Route::get('limpiar_datatable', 'BillingController@limpiar_datatable');// ok
        Route::get('/mostrar_credito_debito_detalle', 'BillingController@mostrar_credito_debito_detalle');// ok
        Route::get('/listado_notacreditodebito_detalle', 'BillingController@listado_notacreditodebito_detalle');// ok
        Route::post('/agregar_creditodebito', 'BillingController@agregar_creditodebito');// ok
        Route::get('/creditodebito_seleccionado', 'BillingController@creditodebito_seleccionado');// ok
        Route::get('/validar_aplicar_credito_debito', 'BillingController@validar_aplicar_credito_debito');// ok
        Route::get('/codigo_sunat_documentocom', 'BillingController@codigo_sunat_documentocom');// ok
        Route::get('/validar_serierem', 'BillingController@validar_serierem');// ok
        Route::get('/validar_nrorem', 'BillingController@validar_nrorem');// ok
        Route::get('/ingresoalmacen_referencia_cabecera', 'BillingController@ingresoalmacen_referencia_cabecera');// ok
        Route::get('/ingresoalmacen_referencia_detalle', 'BillingController@ingresoalmacen_referencia_detalle');// ok
        Route::get('/agregar_salidaalmacen_referencia', 'BillingController@agregar_salidaalmacen_referencia');// ok
        Route::get('/validar_detraccion_por_tipoafecta', 'BillingController@validar_detraccion_por_tipoafecta');// ok
        Route::get('/validar_formapago', 'BillingController@validar_formapago');// ok
        Route::get('/validar_importefp', 'BillingController@validar_importefp');// ok
        Route::get('/validar_nrochequefp', 'BillingController@validar_nrochequefp');// ok
        Route::get('/listado_tmpguias', 'BillingController@listado_tmpguias');// ok
        Route::get('/listado_cronologia_cpe', 'BillingController@listado_cronologia_cpe');// ok
        Route::get('/listado_hitorial_aplicaciones', 'BillingController@listado_hitorial_aplicaciones');// ok
        Route::get('/validar_detraccion', 'BillingController@validar_detraccion');// ok
        Route::get('/listado_documentos_aplicar', 'BillingController@listado_documentos_aplicar');// ok
        Route::get('/obtener_listado_documentos_aplicar', 'BillingController@obtener_listado_documentos_aplicar');// ok
        Route::get('/documento_aplicar_seleccionado', 'BillingController@documento_aplicar_seleccionado');// ok
        Route::get('/cambiar_saldo_aplicar_documentos', 'BillingController@cambiar_saldo_aplicar_documentos');// ok
        Route::get('/agregar_documentos_aplicar', 'BillingController@agregar_documentos_aplicar');// ok
        Route::get('/eliminar_detraccion_por_tipoafecta', 'BillingController@eliminar_detraccion_por_tipoafecta');// ok
        Route::post('/eliminar', 'BillingController@eliminar');
        Route::post('/comprobar_estado','BillingController@comprobar_estado');
        Route::post('/anular','BillingController@anular');

    });

    Route::prefix('periods')->group(function () {
        Route::get('/', 'PeriodController@index')->name('periods');
        Route::get('/list', 'PeriodController@list')->name('list.periods');
        Route::get('/create', 'PeriodController@create')->name('create.periods');
        Route::post('/store', 'PeriodController@store')->name('store.periods');
        Route::get('/{id}/edit', 'PeriodController@edit')->name('edit.periods');
        Route::put('/{id}', 'PeriodController@update')->name('update.periods');
        Route::get('/perioddate', 'PeriodController@period');
        Route::get('/getfechas', 'PeriodController@getfechas');
        Route::post('/eliminar', 'PeriodController@t_eliminar_registro');
        Route::post('/anular','PeriodController@t_anular_registro');
    });

    Route::prefix('balancesreceivable')->group(function () {
        Route::get('/', 'BalancesReceivableController@index')->name('balancesreceivable');
        Route::get('/list', 'BalancesReceivableController@list')->name('list.balancesreceivable');
    });

    Route::prefix('salessummary')->group(function () {
        Route::get('/', 'SalesSummaryController@index')->name('salessummary');
        Route::get('/list', 'SalesSummaryController@list')->name('list.salessummary');
    });

    Route::prefix('salesdetails')->group(function () {
        Route::get('/', 'SalesDetailsController@index')->name('salesdetails');
        Route::get('/list', 'SalesDetailsController@list')->name('list.salesdetails');
        Route::get('/filtro', 'SalesDetailsController@filtro');
    });

    Route::prefix('banksopening')->group(function () {
        Route::get('/', 'BanksOpeningController@index')->name('banksopening');
        Route::get('/list', 'BanksOpeningController@list')->name('list.banksopening');
        Route::get('/create', 'BanksOpeningController@create')->name('create.banksopening');
        Route::post('/store', 'BanksOpeningController@store');
        Route::get('/{id}/edit', 'BanksOpeningController@edit')->name('edit.banksopening');
        Route::put('/{id}', 'BanksOpeningController@update');
        Route::get('/activar-anular/{id}', 'BanksOpeningController@activar');
        Route::get('/ctacte', 'BanksOpeningController@ctacte');
        Route::get('/currency', 'BanksOpeningController@currency');
    });

    Route::prefix('cashmovement')->group(function () {
        Route::post('/eliminar', 'CashMovementController@t_eliminar_registro');
        Route::post('/comprobar_estado','CashMovementController@t_comprobar_estado');
        Route::post('/anular','CashMovementController@t_anular_registro');
        Route::get('/', 'CashMovementController@index')->name('cashmovement');
        Route::get('/list', 'CashMovementController@list')->name('list.cashmovement');
        Route::post('/listar_carrito', 'CashMovementController@listar_carrito');
        Route::get('/create', 'CashMovementController@create')->name('create.cashmovement');
        Route::post('/store', 'CashMovementController@store')->name('store.cashmovement');
        Route::get('/{id}/edit', 'CashMovementController@edit')->name('edit.cashmovement');
        Route::put('/{id}', 'CashMovementController@update')->name('update.cashmovement');
        Route::get('/activar-anular/{id}', 'CashMovementController@activar');
        Route::get('/ctacte', 'CashMovementController@ctacte');
        Route::get('/currency', 'CashMovementController@currency');
        Route::get('/tipooperacion', 'CashMovementController@tipooperacion');
        Route::get('/list/reference', 'CashMovementController@reference')->name('list.reference.cashmovement');
        Route::post('/agregar_item', 'CashMovementController@agregar_item');
        Route::post('/eliminar_item', 'CashMovementController@eliminar_item')->name('eliminaritem.cashmovement');
        Route::post('/totalizar', 'CashMovementController@totalizar');
        Route::get('/pcgs', 'CashMovementController@pcgs');
        Route::get('/editar_detalle', 'CashMovementController@editar_detalle');
        Route::get('/reference_edit', 'CashMovementController@reference_edit');
        Route::get('/eliminar_importe', 'CashMovementController@eliminar_importe');
    });

    Route::prefix('bankmovement')->group(function () {
        Route::get('/estado', 'BankMovementController@t_aprobar')->name('estado.bankmovement');
        Route::post('/eliminar', 'BankMovementController@t_eliminar_registro');
        Route::post('/comprobar_estado','BankMovementController@t_comprobar_estado');
        Route::post('/anular','BankMovementController@t_anular_registro');
        Route::get('/', 'BankMovementController@index')->name('bankmovement');
        Route::get('/list', 'BankMovementController@list')->name('list.bankmovement');
        Route::post('/listar_carrito', 'BankMovementController@listar_carrito');
        Route::get('/create', 'BankMovementController@create')->name('create.bankmovement');
        Route::post('/store', 'BankMovementController@store')->name('store.bankmovement');
        Route::get('/{id}/edit', 'BankMovementController@edit')->name('edit.bankmovement');
        Route::put('/{id}', 'BankMovementController@update')->name('update.bankmovement');
        Route::get('/activar-anular/{id}', 'CashMovementController@activar');
        Route::get('/ctacte', 'CashMovementController@ctacte');
        Route::get('/currency', 'CashMovementController@currency');
        Route::get('/tipooperacion', 'CashMovementController@tipooperacion');
        Route::get('/reference', 'BankMovementController@reference');
        Route::post('/agregar_item', 'BankMovementController@agregar_item');
        Route::post('/eliminar_item', 'BankMovementController@eliminar_item');
        Route::get('/pcgs', 'CashMovementController@pcgs');
        Route::get('/editar_detalle', 'BankMovementController@editar_detalle');
        Route::get('/reference_edit', 'BankMovementController@reference_edit');
        Route::get('/eliminar_importe', 'BankMovementController@eliminar_importe');
    });

    Route::prefix('transfers')->group(function () {
        Route::get('/', 'BankDocumentController@index')->name('transfers');
        Route::get('/list', 'BankDocumentController@list')->name('list.transfers');
        Route::get('/create', 'BankDocumentController@create')->name('create.transfers');
        Route::post('/store', 'BankDocumentController@store')->name('store.transfers');
        Route::get('/{id}/edit', 'BankDocumentController@edit')->name('edit.transfers');
        Route::put('/{id}', 'BankDocumentController@update');
        Route::get('/ctacte', 'BankDocumentController@ctacte');
        Route::get('/bankcash', 'BankDocumentController@bankcash');
        Route::get('/currency', 'BankDocumentController@currency');
        Route::get('/activar-anular/{id}', 'BankDocumentController@activar');
    });

    Route::prefix('otherprovisions')->group(function () {
        Route::get('/', 'OtherProvisionsController@index')->name('otherprovisions');
        Route::get('/list', 'OtherProvisionsController@list')->name('list.otherprovisions');
        Route::get('/create', 'OtherProvisionsController@create')->name('create.otherprovisions');
        Route::post('/store', 'OtherProvisionsController@store')->name('store.otherprovisions');
        Route::get('/{id}/edit', 'OtherProvisionsController@edit')->name('edit.otherprovisions');
        Route::put('/{id}', 'OtherProvisionsController@update');
        Route::get('/tercero', 'OtherProvisionsController@tercero');
        Route::get('/ultimodocumento', 'OtherProvisionsController@verifica_documento_registrado');
        Route::get('/bank', 'OtherProvisionsController@bank');
        Route::get('/currency', 'OtherProvisionsController@currency');
        Route::get('/activar-anular/{id}', 'OtherProvisionsController@activar');
    });

    Route::prefix('cashbankbook')->group(function () {
        Route::get('/', 'CashBankBookController@index')->name('cashbankbook');
        Route::get('/list', 'CashBankBookController@list')->name('list.cashbankbook');
        Route::get('/ctacte', 'CashBankBookController@ctacte');
    });

    Route::prefix('consolidatedbank')->group(function () {
        Route::get('/', 'ConsolidatedBankController@index')->name('consolidatedbank');
        Route::get('/list', 'ConsolidatedBankController@list')->name('list.consolidatedbank');
        Route::get('/ctacte', 'ConsolidatedBankController@ctacte');
    });

    Route::prefix('issuedchecks')->group(function () {
        Route::get('/', 'IssuedChecksController@index')->name('issuedchecks');
        Route::get('/list', 'IssuedChecksController@list')->name('list.issuedchecks');
    });

    Route::prefix('operationtype')->group(function () {
        Route::get('/', 'OperationTypeController@index')->name('operationtype');
        Route::get('/list', 'OperationTypeController@list')->name('list.operationtype');
        Route::get('/create', 'OperationTypeController@create')->name('create.operationtype');
        Route::get('/edit/{id}', 'OperationTypeController@edit')->name('edit.operationtype');
        Route::post('/store', 'OperationTypeController@store')->name('store.operationtype');
        Route::put('/update/{id}', 'OperationTypeController@update')->name('update.operationtype');
        Route::post('/eliminar', 'OperationTypeController@t_eliminar_registro');
        Route::post('/anular','OperationTypeController@t_anular_registro');
    });

    Route::prefix('paymenttype')->group(function () {
        Route::get('/', 'PaymentTypeController@index')->name('paymenttype');
        Route::get('/list', 'PaymentTypeController@list')->name('list.paymenttype');
        Route::get('/create', 'PaymentTypeController@create')->name('create.paymenttype');
        Route::get('/edit/{id}', 'PaymentTypeController@edit')->name('edit.paymenttype');
        Route::post('/store', 'PaymentTypeController@store')->name('store.paymenttype');
        Route::put('/update/{id}', 'PaymentTypeController@update')->name('update.paymenttype');
        Route::post('/eliminar', 'PaymentTypeController@t_eliminar_registro');
        Route::post('/anular','PaymentTypeController@t_anular_registro');
    });

    Route::prefix('bank')->group(function () {
        Route::get('/', 'BankController@index')->name('bank');
        Route::get('/list', 'BankController@list')->name('list.bank');
        Route::get('/create', 'BankController@create')->name('create.bank');
        Route::post('/store', 'BankController@store')->name('store.bank');
        Route::get('/{id}/edit', 'BankController@edit')->name('edit.bank');
        Route::get('/list_docbanco', 'BankController@list_docbanco')->name('list_docbanco.bank');
        Route::put('/{id}', 'BankController@update')->name('update.bank');
//        Route::post('/listar_detalle', 'BankController@listar_detalle');
        Route::get('/currency', 'BankController@currency');
        Route::get('/agregar_entidad_banco', 'BankController@agregar_entidad_banco');
        Route::post('/ver_detalle', 'BankController@ver_detalle');
        Route::post('/editar_detalle', 'BankController@editar_detalle');
        Route::post('/borrar_item', 'BankController@borrar_item');
        Route::post('/eliminar', 'BankController@t_eliminar_registro');
        Route::post('/anular','BankController@t_anular_registro');
    });

    Route::prefix('openingseat')->group(function () {
        Route::get('/', 'OpeningSeatController@index')->name('openingseat');
        Route::get('/list', 'OpeningSeatController@list')->name('list.openingseat');
        Route::get('/create', 'OpeningSeatController@create')->name('create.openingseat');
        Route::post('/store', 'OpeningSeatController@store')->name('store.openingseat');
        Route::get('/{id}/edit', 'OpeningSeatController@edit')->name('edit.openingseat');
        Route::put('/{id}', 'OpeningSeatController@update');
        Route::post('/listar_detalle', 'OpeningSeatController@listar_detalle');
        Route::post('/agregar', 'OpeningSeatController@agregar');
        Route::post('/totalizar', 'OpeningSeatController@totalizar');
        Route::post('/ver_detalle', 'OpeningSeatController@ver_detalle');
        Route::post('/borrar_item', 'OpeningSeatController@borrar_item');
        Route::post('/editar_detalle', 'OpeningSeatController@editar_detalle');
    });

    Route::prefix('AsientoDiario')->group(function () {
        Route::get('/', 'DailySeatController@index')->name('dailyseat');
        Route::get('/list', 'DailySeatController@list')->name('list.dailyseat');
        Route::get('/create', 'DailySeatController@create')->name('create.dailyseat');
        Route::get('/show/{id}', 'DailySeatController@show')->name('show.dailyseat');

        Route::get('/edit/{id}', 'DailySeatController@edit')->name('edit.dailyseat');
        Route::get('/listar_carrito', 'DailySeatController@listar_carrito')->name('listarcarrito.dailyseat');

        Route::post('/listar_detalle', 'DailySeatController@listar_detalle');
        Route::post('/totalizar', 'DailySeatController@totalizar');
        Route::post('/agregar', 'DailySeatController@agregar');
        Route::post('/editar_detalle', 'DailySeatController@editar_detalle');
        Route::get('/prueba', 'DailySeatController@prueba');
        Route::post('/store', 'DailySeatController@store')->name('store.dailyseat');
    });

    Route::prefix('adjustmentexchange')->group(function () {
        Route::get('/', 'AdjustmentExchangeController@index')->name('adjustmentexchange');
        Route::get('/list', 'AdjustmentExchangeController@list')->name('list.adjustmentexchange');
        Route::get('/create', 'AdjustmentExchangeController@create')->name('create.adjustmentexchange');
        Route::post('/store', 'AdjustmentExchangeController@store')->name('store.adjustmentexchange');
        Route::get('/{id}/edit', 'AdjustmentExchangeController@edit')->name('edit.adjustmentexchange');
        Route::put('/{id}', 'AdjustmentExchangeController@update')->name('update.adjustmentexchange');
        Route::post('/listar_detalle', 'AdjustmentExchangeController@listar_detalle');
        Route::post('/adjustment', 'AdjustmentExchangeController@adjustment');
        Route::get('/activar-anular/{id}', 'AdjustmentExchangeController@activar');

        Route::get('/listdetail', 'AdjustmentExchangeController@listdetail')->name('listdetail.adjustmentexchange');
    });

    Route::prefix('closingseat')->group(function () {
        Route::get('/', 'ClosingSeatController@index')->name('closingseat');
        Route::get('/list', 'ClosingSeatController@list')->name('list.closingseat');
        Route::get('/create', 'ClosingSeatController@create')->name('create.closingseat');
        Route::post('/agregar', 'ClosingSeatController@agregar');
        Route::post('/totalizar', 'ClosingSeatController@totalizar');
        Route::post('/store', 'ClosingSeatController@store');
        Route::get('/{id}/edit', 'ClosingSeatController@edit')->name('edit.closingseat');
        Route::put('/{id}', 'ClosingSeatController@update');
        Route::post('/listar_detalle', 'ClosingSeatController@listar_detalle');
        Route::get('/activar-anular/{id}', 'ClosingSeatController@activar');
    });

    Route::prefix('opencloseperiods')->group(function () {
        Route::get('/', 'OpenClosePeriodsController@index')->name('opencloseperiods');
        Route::get('/list', 'OpenClosePeriodsController@list')->name('list.opencloseperiods');
        Route::post('/abrir_cerrar_periodo', 'OpenClosePeriodsController@abrir_cerrar_periodo');
    });

    Route::prefix('accountingcentralization')->group(function () {
        Route::get('/', 'AccountingCentralizationController@index')->name('accountingcentralization');
        Route::post('/centraliza_modulo', 'AccountingCentralizationController@centraliza_modulo');
    });

    Route::prefix('accountmovement')->group(function () {
        Route::get('/', 'AccountMovementController@index')->name('accountmovement');
        Route::get('/list', 'AccountMovementController@list')->name('list.accountmovement');
    });

    Route::prefix('dailybook')->group(function () {
        Route::get('/', 'DailyBookController@index')->name('dailybook');
        Route::get('/list', 'DailyBookController@list')->name('list.dailybook');
    });

    Route::prefix('ledger')->group(function () {
        Route::get('/', 'LedgerController@index')->name('ledger');
        Route::get('/list', 'LedgerController@list')->name('list.ledger');
    });

    Route::prefix('detailmovementcash')->group(function () {
        Route::get('/', 'DetailMovementCashController@index')->name('detailmovementcash');
        Route::get('/list', 'DetailMovementCashController@list')->name('list.detailmovementcash');
    });

    Route::prefix('detailmovementaccount')->group(function () {
        Route::get('/', 'DetailMovementAccountController@index')->name('detailmovementaccount');
        Route::get('/list', 'DetailMovementAccountController@list')->name('list.detailmovementaccount');
    });

    Route::prefix('cashbanks')->group(function () {
        Route::get('/', 'CashBanksController@index')->name('cashbanks');
        Route::get('/list', 'CashBanksController@list')->name('list.cashbanks');
    });

    Route::prefix('currentaccountreport')->group(function () {
        Route::get('/', 'CurrentAccountReportController@index')->name('currentaccountreport');
        Route::get('/list', 'CurrentAccountReportController@list')->name('list.currentaccountreport');
        Route::get('/reporte_balance_cuentas_corrientes_ple', 'CurrentAccountReportController@reporte_balance_cuentas_corrientes_ple');
    });

    Route::prefix('checkingbalance')->group(function () {
        Route::get('/', 'CheckingBalanceController@index')->name('checkingbalance');
        Route::get('/list', 'CheckingBalanceController@list')->name('list.checkingbalance');
        Route::get('/reporte_balance_comprobacion_ple', 'CheckingBalanceController@reporte_balance_comprobacion_ple');
        Route::get('/reporte_balance_comprobacion_pdt', 'CheckingBalanceController@reporte_balance_comprobacion_pdt');
    });

    Route::prefix('financialstate')->group(function () {
        Route::get('/', 'FinancialStateController@index')->name('financialstate');
        Route::get('/list', 'FinancialStateController@list')->name('list.financialstate');
    });

    Route::prefix('balancesaccount')->group(function () {
        Route::get('/', 'BalancesAccountController@index')->name('balancesaccount');
        Route::get('/list', 'BalancesAccountController@list')->name('list.balancesaccount');
    });

    Route::prefix('seatvalidation')->group(function () {
        Route::get('/', 'SeatValidationController@index')->name('seatvalidation');
        Route::get('/list', 'SeatValidationController@list')->name('list.seatvalidation');
    });

    Route::prefix('exchangerate')->group(function () {
        Route::get('/', 'ExchangeRateController@index')->name('exchangerate');
        Route::get('/list', 'ExchangeRateController@list')->name('list.exchangerate');
        Route::get('/edit/{id}', 'ExchangeRateController@edit')->name('edit.exchangerate');
        Route::put('/{id}', 'ExchangeRateController@update')->name('update.exchangerate');
        Route::get('/tiposdecambio', 'ExchangeRateController@getTipoCambioSunat');
        Route::get('/consultar/{fecha}', 'ExchangeRateController@show');
    });

    Route::prefix('nohabidos')->group(function () {
        Route::get('/', 'NoHabidosController@index')->name('nohabidos');
        Route::get('/list', 'NoHabidosController@list')->name('list.nohabidos');
    });

    Route::prefix('taxexcluded')->group(function () {
        Route::get('/', 'NoHabidosController@index')->name('taxexcluded');
        Route::get('/list', 'NoHabidosController@list')->name('list.taxexcluded');
    });

    Route::prefix('withholdingdocuments')->group(function () {
        Route::get('/', 'WithholdingDocumentsController@index')->name('withholdingdocuments');
        Route::get('/list', 'WithholdingDocumentsController@list')->name('list.withholdingdocuments');
        Route::get('/listreference', 'WithholdingDocumentsController@listreference')->name('listreference.withholdingdocuments');
        Route::get('/create', 'WithholdingDocumentsController@create')->name('create.withholdingdocuments');
        Route::post('/listar_detalle', 'WithholdingDocumentsController@listar_detalle');
        Route::get('/rucproveedor', 'WithholdingDocumentsController@rucproveedor');
        Route::get('/ultimodocumento', 'WithholdingDocumentsController@ultimo_numero_serie');
        Route::post('/consulta_documento', 'WithholdingDocumentsController@consulta_documento');
        Route::get('/carro_retencion', 'WithholdingDocumentsController@carro_retencion');
        Route::post('/edita_detalle_retencion', 'WithholdingDocumentsController@edita_detalle_retencion');
    });

    Route::prefix('TiposMovimiento')->group(function () {
        Route::get('/', 'MovementTypeController@index')->name('movementtype');
        Route::get('/list', 'MovementTypeController@list')->name('list.movementtype');
        Route::get('/crear', 'MovementTypeController@create')->name('create.movementtype');
        Route::post('/store', 'MovementTypeController@store')->name('store.movementtype');
        Route::get('/edit/{id}', 'MovementTypeController@edit')->name('edit.movementtype');
        Route::put('/update/{id}', 'MovementTypeController@update')->name('update.movementtype');
        Route::post('/eliminar', 'MovementTypeController@t_eliminar_registro');
        Route::post('/anular','MovementTypeController@t_anular_registro');
    });

    Route::prefix('CentralizacionContable')->group(function () {
        Route::get('/', 'AccountingCentralizationWarehouseController@index')->name('accountingctnwarehouse');
        Route::get('/list', 'AccountingCentralizationWarehouseController@list')->name('list.accountingctnwarehouse');
        Route::get('/edit/{id}', 'AccountingCentralizationWarehouseController@edit')->name('edit.accountingctnwarehouse');
        Route::put('/store', 'AccountingCentralizationWarehouseController@store')->name('store.accountingctnwarehouse');
        Route::put('/update/{id}', 'AccountingCentralizationWarehouseController@update')->name('update.accountingctnwarehouse');

    });

    Route::prefix('PedidosAlmacen')->group(function () {
        Route::get('/', 'OrderToWarehouseController@index')->name('ordertowarehouse');
        Route::get('/list', 'OrderToWarehouseController@list')->name('list.ordertowarehouse');
        Route::get('/crear', 'OrderToWarehouseController@create')->name('create.ordertowarehouse');
        Route::get('/edit/{id}', 'OrderToWarehouseController@edit')->name('edit.ordertowarehouse');
        Route::post('/store', 'OrderToWarehouseController@store')->name('store.ordertowarehouse');
        Route::put('/update', 'OrderToWarehouseController@update')->name('update.ordertowarehouse');
        Route::get('/listar_carrito', 'OrderToWarehouseController@listar_carrito')->name('listarcarrito.ordertowarehouse');
        Route::post('/agregar_item', 'OrderToWarehouseController@agregar_item')->name('agregaritem.ordertowarehouse');
        Route::post('/update_carrito', 'OrderToWarehouseController@update_carrito')->name('updatecarrito.ordertowarehouse');
        Route::post('/eliminar_item', 'OrderToWarehouseController@eliminar_item')->name('eliminaritem.ordertowarehouse');
        Route::get('/dataset/{id}', 'OrderToWarehouseController@dataset')->name('dataset.ordertowarehouse');
        Route::get('/change_almacen/{id}', 'OrderToWarehouseController@change')->name('change.ordertowarehouse');
        Route::get('/getalmacenes/{id}', 'OrderToWarehouseController@getalmacenes')->name('getalmacenes.ordertowarehouse');

        Route::get('/estado', 'OrderToWarehouseController@t_aprobar')->name('estado.ordertowarehouse');
        Route::post('/comprobar_estado','OrderToWarehouseController@t_comprobar_estado');
        Route::post('/eliminar', 'OrderToWarehouseController@t_eliminar_registro');
        Route::post('/anular','OrderToWarehouseController@t_anular_registro');
    });

    Route::prefix('IngresosAlmacen')->group(function () {
        Route::get('/', 'IncomeToWarehouseController@index')->name('incometowarehouse');
        Route::get('/listar', 'IncomeToWarehouseController@list')->name('list.incometowarehouse');
        Route::get('/crear', 'IncomeToWarehouseController@create')->name('create.incometowarehouse');
        Route::get('/edit/{id}', 'IncomeToWarehouseController@edit')->name('edit.incometowarehouse');
        Route::post('/store', 'IncomeToWarehouseController@store')->name('store.incometowarehouse');
        Route::put('/update', 'IncomeToWarehouseController@update')->name('update.incometowarehouse');
        Route::get('/listar_carrito', 'IncomeToWarehouseController@listar_carrito')->name('listarcarrito.incometowarehouse');
        Route::post('/agregar_item', 'IncomeToWarehouseController@agregar_item')->name('agregaritem.incometowarehouse');
        Route::post('/update_carrito', 'IncomeToWarehouseController@update_carrito')->name('updatecarrito.incometowarehouse');
        Route::post('/eliminar_item', 'IncomeToWarehouseController@eliminar_item')->name('eliminaritem.incometowarehouse');
        Route::get('/totalizar', 'IncomeToWarehouseController@totalizar')->name('totals.incometowarehouse');
        Route::get('/provision/{id}', 'IncomeToWarehouseController@provision')->name('provision.incometowarehouse');
        Route::get('/provision_detalle/{id}', 'IncomeToWarehouseController@provision_detalle')->name('provisiondetalle.incometowarehouse');
        Route::get('/references', 'IncomeToWarehouseController@references')->name('references.incometowarehouse');
        Route::get('/references_detail/{accion}', 'IncomeToWarehouseController@references_detail')->name('referencesdetail.incometowarehouse');
        Route::post('/addreferences', 'IncomeToWarehouseController@insertar_referencia')->name('insertar_referencia.incometowarehouse');
        Route::get('/tabla_references', 'IncomeToWarehouseController@tabla_references')->name('tabla_references.incometowarehouse');

        Route::post('/comprobar_estado','IncomeToWarehouseController@t_comprobar_estado');
        Route::post('/eliminar', 'IncomeToWarehouseController@t_eliminar_registro');
        Route::post('/anular','IncomeToWarehouseController@t_anular_registro');

    });

    ////VENTAS

    Route::prefix('PedidosVenta')->group(function () {
        Route::get('/', 'SaleOrderController@index')->name('saleorder');
        Route::get('/list', 'SaleOrderController@list')->name('list.saleorder');
        Route::get('/crear', 'SaleOrderController@create')->name('create.saleorder');
        Route::get('/edit/{id}', 'SaleOrderController@edit')->name('edit.saleorder');
        Route::post('/store', 'SaleOrderController@store')->name('store.saleorder');
        Route::put('/update/{id}', 'SaleOrderController@update')->name('update.saleorder');
        Route::get('/listar_carrito', 'SaleOrderController@listar_carrito')->name('listarcarrito.saleorder');
        Route::post('/addproductos', 'SaleOrderController@insertar_productos')->name('insertar_productos.saleorder');
        Route::post('/update_carrito', 'SaleOrderController@update_carrito')->name('updatecarrito.saleorder');
        Route::post('/eliminar_item', 'SaleOrderController@eliminar_item')->name('eliminaritem.saleorder');
        Route::get('/dataset/{id}', 'SaleOrderController@dataset')->name('dataset.saleorder');
        Route::get('/change_almacen/{id}', 'SaleOrderController@change')->name('change.saleorder');
        Route::get('/getalmacenes/{id}', 'SaleOrderController@getalmacenes')->name('getalmacenes.saleorder');

        Route::get('/estado', 'SaleOrderController@t_aprobar')->name('estado.saleorder');
        Route::post('/comprobar_estado','SaleOrderController@t_comprobar_estado');
        Route::post('/eliminar', 'SaleOrderController@t_eliminar_registro');
        Route::post('/anular','SaleOrderController@t_anular_registro');

        Route::get('/product','SaleOrderController@productWithStock');
        Route::get('/product_edit','SaleOrderController@product_to_edit');
        Route::get('/validarPuntoVenta','SaleOrderController@puntoventa');

    });

    Route::prefix('FacturarPedido')->group(function () {
        Route::get('/', 'BillOrderController@index')->name('billorder');
        Route::get('/list', 'BillOrderController@list')->name('list.billorder');
        Route::get('/listar_detalle', 'BillOrderController@detail_list');
        Route::get('/caja', 'BillOrderController@caja')->name('caja.billorder');
        Route::get('/getdata/{id}', 'BillOrderController@getdata')->name('data.billorder');

        Route::post('/store', 'BillOrderController@store')->name('store.billorder');

        Route::get('/listar_carrito', 'BillOrderController@listar_carrito')->name('listarcarrito.billorder');
        Route::post('/addproductos', 'BillOrderController@insertar_productos')->name('insertar_productos.billorder');
        Route::post('/update_carrito', 'BillOrderController@update_carrito')->name('updatecarrito.billorder');
        Route::post('/eliminar_item', 'BillOrderController@eliminar_item')->name('eliminaritem.billorder');
        Route::get('/dataset/{id}', 'BillOrderController@dataset')->name('dataset.billorder');
        Route::get('/change_almacen/{id}', 'BillOrderController@change')->name('change.billorder');
        Route::get('/getalmacenes/{id}', 'BillOrderController@getalmacenes')->name('getalmacenes.billorder');


    });

    //VENTAS - PROCESOS

    Route::prefix('SummaryBallots')->group(function () {
        Route::get('/', 'SummaryBallotsController@index')->name('summaryballots');
        Route::get('/list', 'SummaryBallotsController@list')->name('list.summaryballots');
        Route::get('/create', 'SummaryBallotsController@create')->name('create.summaryballots');
        Route::get('/edit/{id}', 'SummaryBallotsController@edit')->name('edit.summaryballots');
        Route::get('/listar-carrito', 'SummaryBallotsController@listar_carrito')->name('listarcarrito.summaryballots');
        Route::get('/aplicadoc/{fechaproceso}', 'SummaryBallotsController@aplicadoc')->name('aplicadoc.summaryballots');
        Route::get('/resumen', 'SummaryBallotsController@getResumen')->name('getresumen.summaryballots');
        Route::post('/store', 'SummaryBallotsController@store')->name('store.summaryballots');






    });


    Route::prefix('SalidasAlmacen')->group(function () {
        Route::get('/', 'ExitToWarehouseController@index')->name('exittowarehouse');
        Route::get('/list', 'ExitToWarehouseController@list')->name('list.exittowarehouse');
        Route::get('/crear', 'ExitToWarehouseController@create')->name('create.exittowarehouse');
        Route::get('/detalle/{id}', 'ExitToWarehouseController@edit')->name('edit.exittowarehouse');
        Route::post('/store', 'ExitToWarehouseController@store')->name('store.exittowarehouse');
        Route::put('/update', 'ExitToWarehouseController@update')->name('update.exittowarehouse');
        Route::get('/listar_carrito', 'ExitToWarehouseController@listar_carrito')->name('listarcarrito.exittowarehouse');
        Route::post('/agregar_item', 'ExitToWarehouseController@agregar_item')->name('agregaritem.exittowarehouse');
        Route::post('/update_carrito', 'ExitToWarehouseController@update_carrito')->name('updatecarrito.exittowarehouse');
        Route::post('/eliminar_item', 'ExitToWarehouseController@eliminar_item')->name('eliminaritem.exittowarehouse');
    });

    Route::prefix('movementwarehouse')->group(function () {
        Route::get('/', 'MovementWarehouseController@index')->name('movementwarehouse');
        Route::post('/value', 'MovementWarehouseController@value')->name('value.movementwarehouse');
    });

    Route::prefix('inventorysettingswarehouse')->group(function () {
        Route::get('/', 'InventorySettingsWarehouseController@index')->name('inventorysettingswarehouse');
        Route::get('/list', 'InventorySettingsWarehouseController@list')->name('list.inventorysettingswarehouse');
    });

    Route::prefix('productkardex')->group(function () {
        Route::get('/', 'ProductKardexController@index')->name('productkardex');
        Route::get('/list', 'ProductKardexController@list')->name('list.productkardex');
    });

    Route::prefix('purchases')->group(function () {
        Route::get('/', 'PurchasesController@index')->name('purchases');
        Route::get('/list', 'PurchasesController@list')->name('list.purchases');
        Route::get('/reporte_compras_plenormal', 'PurchasesController@reporte_compras_plenormal');
        Route::get('/reporte_compras_plesimplificado', 'PurchasesController@reporte_compras_plesimplificado');
        Route::get('/reporte_compras_plenodomiciliado', 'PurchasesController@reporte_compras_plenodomiciliado');
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', 'SalesController@index')->name('sales');
        Route::get('/list', 'SalesController@list')->name('list.sales');
        Route::get('/reporte_ventas_ple', 'SalesController@reporte_ventas_ple');

    });

    Route::prefix('withholdingbook')->group(function () {
        Route::get('/', 'WithholdingBookController@index')->name('withholdingbook');
        Route::get('/list', 'WithholdingBookController@list')->name('list.withholdingbook');
    });

    Route::prefix('permanentinventory')->group(function () {
        Route::get('/', 'PermanentInventoryController@index')->name('permanentinventory');
        Route::get('/list', 'PermanentInventoryController@list')->name('list.permanentinventory');
        Route::get('/reporte_inventario_permante_ple', 'PermanentInventoryController@reporte_inventario_permante_ple');
    });

    Route::prefix('operationcustomer')->group(function () {
        Route::get('/', 'OperationCustomerController@index')->name('operationcustomer');
        Route::get('/list', 'OperationCustomerController@list')->name('list.operationcustomer');
        Route::get('/reporte_operaciones_por_terceros', 'OperationCustomerController@reporte_operaciones_por_terceros');
    });

    Route::prefix('benefitdeclaration')->group(function () {
        Route::get('/', 'BenefitDeclarationController@index')->name('benefitdeclaration');
        Route::get('/list', 'BenefitDeclarationController@list')->name('list.benefitdeclaration');
        Route::get('/reporte_programa_beneficios', 'BenefitDeclarationController@reporte_programa_beneficios');
    });

    Route::prefix('monthlyincometax')->group(function () {
        Route::get('/', 'MonthlyIncomeTaxController@index')->name('monthlyincometax');
        Route::get('/list', 'MonthlyIncomeTaxController@list')->name('list.monthlyincometax');
        Route::get('/reporte_igv_renta_mensual_pdt621', 'MonthlyIncomeTaxController@reporte_igv_renta_mensual_pdt621');
    });

    Route::prefix('retentionagents')->group(function () {
        Route::get('/', 'RetentionAgentsController@index')->name('retentionagents');
        Route::get('/list', 'RetentionAgentsController@list')->name('list.retentionagents');
        Route::get('/reporte_agentes_de_retencion_pdt', 'RetentionAgentsController@reporte_agentes_de_retencion_pdt');
    });


    Route::prefix('categories')->group(function () {
        Route::get('/', 'CategoriesController@index')->name('categories');
        Route::get('/list', 'CategoriesController@list')->name('list.categories');
        Route::get('/create', 'CategoriesController@create')->name('create.categories');
        Route::post('/store', 'CategoriesController@store')->name('store.categories');
        Route::get('/{id}/edit', 'CategoriesController@edit')->name('edit.categories');
        Route::put('/{id}', 'CategoriesController@update');

    });

    Route::prefix('assets')->group(function () {
        Route::get('/', 'AssetsController@index')->name('assets');
        Route::get('/list', 'AssetsController@list')->name('list.assets');
//        Route::get('/store', 'AssetsController@store')->name('store.assets');

    });

    Route::prefix('tariffitems')->group(function () {
        Route::get('/', 'TariffItemsController@index')->name('tariffitems');
        Route::get('/list', 'TariffItemsController@list')->name('list.tariffitems');
        Route::get('/create', 'TariffItemsController@create')->name('create.tariffitems');
        Route::post('/store', 'TariffItemsController@store')->name('store.tariffitems');
        Route::get('/{id}/edit', 'TariffItemsController@edit')->name('edit.tariffitems');
        Route::put('/{id}', 'TariffItemsController@update')->name('update.tariffitems');
        Route::post('/eliminar', 'TariffItemsController@t_eliminar_registro');
        Route::post('/anular','TariffItemsController@t_anular_registro');
    });

    Route::prefix('productgroups')->group(function () {
        Route::get('/', 'ProductGroupController@index')->name('productgroups');
        Route::get('/list', 'ProductGroupController@list')->name('list.productgroups');
        Route::get('/create', 'ProductGroupController@create')->name('create.productgroups');
        Route::post('/store', 'ProductGroupController@store')->name('store.productgroups');
        Route::get('/{id}/edit', 'ProductGroupController@edit')->name('edit.productgroups');
        Route::put('/{id}', 'ProductGroupController@update')->name('update.productgroups');
        Route::post('/eliminar', 'ProductGroupController@t_eliminar_registro');
        Route::post('/anular','ProductGroupController@t_anular_registro');
    });

    Route::prefix('thirdclass')->group(function () {
        Route::get('/', 'ThirdClassController@index')->name('thirdclass');
        Route::get('/list', 'ThirdClassController@list')->name('list.thirdclass');
        Route::get('/create', 'ThirdClassController@create')->name('create.thirdclass');
        Route::post('/store', 'ThirdClassController@store')->name('store.thirdclass');
        Route::get('/{id}/edit', 'ThirdClassController@edit')->name('edit.thirdclass');
        Route::put('/{id}', 'ThirdClassController@update')->name('update.thirdclass');
        Route::post('/eliminar', 'ThirdClassController@t_eliminar_registro');
        Route::post('/anular','ThirdClassController@t_anular_registro');
    });

    Route::prefix('seller')->group(function () {
        Route::get('/', 'SellerController@index')->name('seller');
        Route::get('/list', 'SellerController@list')->name('list.seller');
        Route::get('/create', 'SellerController@create')->name('create.seller');
        Route::post('/store', 'SellerController@store')->name('store.seller');
        Route::get('/{id}/edit', 'SellerController@edit')->name('edit.seller');
        Route::put('/{id}', 'SellerController@update')->name('update.seller');
        Route::get('/dataset/{id}', 'SellerController@dataset')->name('dataset.seller');
        Route::get('/agregar_item', 'SellerController@agregar_item')->name('agregaritem.seller');
        Route::get('/list_marca_comision', 'SellerController@list_marca_comision')->name('list_marca_comision.seller');
        Route::get('/update_item', 'SellerController@update_item')->name('update_item.seller');
        Route::get('/eliminar_datos_comision', 'SellerController@eliminar_datos_comision');
        Route::post('/eliminar', 'SellerController@t_eliminar_registro');
        Route::post('/anular','SellerController@t_anular_registro');
    });

    Route::prefix('paymentcondition')->group(function () {
        Route::get('/obtenerdiascondicionpago', 'PaymentConditionController@obtener_dias_condicionpago');
        Route::get('/', 'PaymentConditionController@index')->name('paymentcondition');
        Route::get('/list', 'PaymentConditionController@list')->name('list.paymentcondition');
        Route::get('/create', 'PaymentConditionController@create')->name('create.paymentcondition');
        Route::post('/store', 'PaymentConditionController@store')->name('store.paymentcondition');
        Route::get('/{id}/edit', 'PaymentConditionController@edit')->name('edit.paymentcondition');
        Route::put('/{id}', 'PaymentConditionController@update')->name('update.paymentcondition');
        Route::post('/eliminar', 'PaymentConditionController@t_eliminar_registro');
        Route::post('/anular','PaymentConditionController@t_anular_registro');
    });

    Route::prefix('transactiontypes')->group(function () {
        Route::get('/', 'TransactionTypesController@index')->name('transactiontypes');
        Route::get('/list', 'TransactionTypesController@list')->name('list.transactiontypes');
        Route::get('/create', 'TransactionTypesController@create')->name('create.transactiontypes');
        Route::post('/store', 'TransactionTypesController@store')->name('store.transactiontypes');
        Route::get('/{id}/edit', 'TransactionTypesController@edit')->name('edit.transactiontypes');
        Route::put('/{id}', 'TransactionTypesController@update')->name('update.transactiontypes');
        Route::post('/eliminar', 'TransactionTypesController@t_eliminar_registro');
        Route::post('/anular','TransactionTypesController@t_anular_registro');
    });

    Route::prefix('roadtypes')->group(function () {
        Route::get('/', 'RoadTypesController@index')->name('roadtypes');
        Route::get('/list', 'RoadTypesController@list')->name('list.roadtypes');
        Route::get('/create', 'RoadTypesController@create')->name('create.roadtypes');
        Route::post('/store', 'RoadTypesController@store')->name('store.roadtypes');
        Route::get('/{id}/edit', 'RoadTypesController@edit')->name('edit.roadtypes');
        Route::put('/{id}', 'RoadTypesController@update')->name('update.roadtypes');
        Route::post('/eliminar', 'RoadTypesController@t_eliminar_registro');
        Route::post('/anular','RoadTypesController@t_anular_registro');
    });

    Route::prefix('zonetype')->group(function () {
        Route::get('/', 'ZoneTypeController@index')->name('zonetype');
        Route::get('/list', 'ZoneTypeController@list')->name('list.zonetype');
        Route::get('/create', 'ZoneTypeController@create')->name('create.zonetype');
        Route::post('/store', 'ZoneTypeController@store')->name('store.zonetype');
        Route::get('/{id}/edit', 'ZoneTypeController@edit')->name('edit.zonetype');
        Route::put('/{id}', 'ZoneTypeController@update')->name('update.zonetype');
        Route::post('/eliminar', 'ZoneTypeController@t_eliminar_registro');
        Route::post('/anular','ZoneTypeController@t_anular_registro');
    });

    Route::prefix('typeheading')->group(function () {
        Route::get('/', 'TypeHeadingController@index')->name('typeheading');
        Route::get('/list', 'TypeHeadingController@list')->name('list.typeheading');
        Route::get('/create', 'TypeHeadingController@create')->name('create.typeheading');
        Route::post('/store', 'TypeHeadingController@store')->name('store.typeheading');
        Route::get('/{id}/edit', 'TypeHeadingController@edit')->name('edit.typeheading');
        Route::put('/{id}', 'TypeHeadingController@update')->name('update.typeheading');
        Route::put('/{id}', 'TypeHeadingController@update')->name('update.typeheading');
        Route::post('/eliminar', 'TypeHeadingController@t_eliminar_registro');
        Route::post('/anular','TypeHeadingController@t_anular_registro');
    });

    Route::prefix('categoriesctacte')->group(function () {
        Route::get('/', 'CategoriesCtaCteController@index')->name('categoriesctacte');
        Route::get('/list', 'CategoriesCtaCteController@list')->name('list.categoriesctacte');
        Route::get('/create', 'CategoriesCtaCteController@create')->name('create.categoriesctacte');
        Route::post('/store', 'CategoriesCtaCteController@store')->name('store.categoriesctacte');
        Route::get('/{id}/edit', 'CategoriesCtaCteController@edit')->name('edit.categoriesctacte');
        Route::put('/{id}', 'CategoriesCtaCteController@update')->name('update.categoriesctacte');
        Route::get('/list_docuemntos_asignados', 'CategoriesCtaCteController@list_docuemntos_asignados')->name('list_docuemntos_asignados.categoriesctacte');
        Route::get('/agregar_item', 'CategoriesCtaCteController@agregar_item')->name('agregaritem.categoriesctacte');
        Route::get('/update_item', 'CategoriesCtaCteController@update_item')->name('update_item.categoriesctacte');
        Route::get('/eliminar_documentos_asignados', 'CategoriesCtaCteController@eliminar_documentos_asignados');
        Route::post('/eliminar', 'CategoriesCtaCteController@t_eliminar_registro');
        Route::post('/anular','CategoriesCtaCteController@t_anular_registro');
    });

    Route::prefix('purchaseorder')->group(function () {
        Route::get('/', 'PurchaseOrderController@index')->name('purchaseorder');
        Route::get('/list', 'PurchaseOrderController@list')->name('list.purchaseorder');
        Route::get('/create', 'PurchaseOrderController@create')->name('create.purchaseorder');
        Route::post('/store', 'PurchaseOrderController@store')->name('store.purchaseorder');
        Route::put('/update', 'PurchaseOrderController@update')->name('update.purchaseorder');
        Route::get('/{id}/edit', 'PurchaseOrderController@edit')->name('edit.purchaseorder');
        //Route::put('/{id}', 'PurchaseOrderController@update')->name('update.purchaseorder');
        Route::post('/agregar_detalle_documento', 'PurchaseOrderController@agregar_detalle_documento');
        Route::get('/listar_detalle', 'PurchaseOrderController@listar_detalle');
        Route::post('/eliminar_detalle_documento', 'PurchaseOrderController@eliminar_detalle_documento');
        Route::post('/editar_detalle_documento', 'PurchaseOrderController@editar_detalle_documento');
        Route::get('/tipo_producto', 'PurchaseOrderController@tipo_producto');
        Route::post('/cambiar_impuesto', 'PurchaseOrderController@cambiar_impuesto');
        Route::post('/totalizar', 'PurchaseOrderController@totalizar');
        Route::post('/sumar', 'PurchaseOrderController@sumar');
        Route::post('cambiar_impuesto2', 'PurchaseOrderController@cambiar_impuesto2');
        Route::post('calcular', 'PurchaseOrderController@calcular');
        Route::post('volumen_cantidad_front', 'PurchaseOrderController@volumen_cantidad_front');
        Route::post('datos_adicionales', 'PurchaseOrderController@datos_adicionales');
        Route::get('/pedido_cabecera', 'PurchaseOrderController@pedido_cabecera')->name('pedido_cabecera.purchaseorder');
        Route::get('/pedido_detalle', 'PurchaseOrderController@pedido_detalle')->name('pedido_detalle.purchaseorder');
        //Route::get('/listar_pedido_cabecera','PurchaseOrderController@listar_pedido_cabecera');
        Route::post('/agregar_pedido_detalle', 'PurchaseOrderController@agregar_pedido_detalle');
        Route::post('/agregar_pedidos', 'PurchaseOrderController@agregar_pedidos');
        Route::get('/referencia_pedido_almacen', 'PurchaseOrderController@referencia_pedido_almacen')->name('referencia_pedido_almacen.purchaseorder');
        Route::post('contacto_depositar', 'PurchaseOrderController@contacto_depositar');
        Route::put('/cambiar_estado/{id}', 'PurchaseOrderController@cambiar_estado');
        Route::post('/agregar_pedido_detalle', 'PurchaseOrderController@agregar_pedido_detalle');
        Route::post('/agregar_pedidos', 'PurchaseOrderController@agregar_pedidos');
        Route::post('contacto_depositar', 'PurchaseOrderController@contacto_depositar');
        Route::put('/cambiar_estado/{id}', 'PurchaseOrderController@cambiar_estado');
        Route::get('/estado', 'PurchaseOrderController@t_aprobar')->name('estado.purchaseorder');
        Route::get('/ingreso_almacen', 'PurchaseOrderController@ingreso_almacen')->name('ingreso_almacen.purchaseorder');
        Route::get('/provisiones', 'PurchaseOrderController@provisiones')->name('provisiones.purchaseorder');
        Route::post('/sumar_footer_referencias', 'PurchaseOrderController@sumar_footer_referencias');
        Route::get('/archivar', 'PurchaseOrderController@archivar');
        Route::post('/eliminar', 'PurchaseOrderController@t_eliminar_registro');
        Route::post('/comprobar_estado','PurchaseOrderController@t_comprobar_estado');
        Route::post('/anular','PurchaseOrderController@t_anular_registro');
        //Route::get('/buscar_tercero','CustomerController@buscar_tercero');
        Route::get('/historial_precio','PurchaseOrderController@historial_precio');
    });

    Route::prefix('serviceorders')->group(function () {
        Route::get('/', 'ServiceOrdersController@index')->name('serviceorders');
        Route::get('/list', 'ServiceOrdersController@list')->name('list.serviceorders');
        Route::get('/create', 'ServiceOrdersController@create')->name('create.serviceorders');
        Route::post('/store', 'ServiceOrdersController@store')->name('store.serviceorders');
        Route::get('/{id}/edit', 'ServiceOrdersController@edit')->name('edit.serviceorders');
        Route::put('/{id}', 'ServiceOrdersController@update')->name('update.serviceorders');
        Route::post('/totalizar', 'ServiceOrdersController@totalizar');
        Route::get('/list_detalle_documento', 'ServiceOrdersController@list_detalle_documento')->name('list_detalle_documento.serviceorders');
        Route::get('/generar_numero_serie/{serie}', 'ServiceOrdersController@generar_numero_serie');
        Route::post('/impuesto', 'ServiceOrdersController@impuesto');
        Route::post('impuesto3', 'ServiceOrdersController@impuesto3');
        Route::post('/sumar', 'ServiceOrdersController@sumar');
        Route::get('/agregar_item', 'ServiceOrdersController@agregar_item')->name('agregar_item.serviceorders');
        Route::get('/update_item', 'ServiceOrdersController@update_item')->name('update_item.serviceorders');
        Route::get('/eliminar_datos', 'ServiceOrdersController@eliminar_datos');
        Route::get('/buscar_detraccion', 'ServiceOrdersController@buscar_detraccion');
        Route::post('/importe_adicional', 'ServiceOrdersController@importe_adicional');
        Route::get('/buscar_undtransporte', 'ServiceOrdersController@buscar_undtransporte');
        Route::get('/cargar_contactos/{id}', 'ServiceOrdersController@cargar_contactos');
        Route::get('/archivar', 'ServiceOrdersController@archivar');
        Route::post('/eliminar', 'ServiceOrdersController@t_eliminar_registro');
        Route::post('/comprobar_estado','ServiceOrdersController@t_comprobar_estado');
        Route::post('/anular','ServiceOrdersController@t_anular_registro');
        Route::get('/buscar_servicios', 'ServiceOrdersController@buscar_servicios');
        Route::get('/depositaren/{id}', 'ServiceOrdersController@depositaren');
        Route::get('/estado', 'ServiceOrdersController@t_aprobar')->name('estado.serviceorders');
        Route::get('/lista_provisiones', 'ServiceOrdersController@lista_provisiones')->name('lista_provisiones.serviceorders');
        Route::post('/sumar_footer_referencias', 'ServiceOrdersController@sumar_footer_referencias');
    });

    Route::prefix('settlementexpenses')->group(function () {
        Route::get('/', 'SettlementExpensesController@index')->name('settlementexpenses');
        Route::get('/list', 'SettlementExpensesController@list')->name('list.settlementexpenses');
        Route::get('/create', 'SettlementExpensesController@create')->name('create.settlementexpenses');
        Route::get('/rucproveedor', 'SettlementExpensesController@rucproveedor');
    });

    Route::prefix('configuration')->group(function () {
        Route::get('/generales', 'ConfigurationController@index')->name('generales.configuration');
        Route::get('/maestros', 'ConfigurationController@maestros')->name('maestros.configuration');
        Route::get('/compras', 'ConfigurationController@compras')->name('compras.configuration');
        Route::get('/ventas', 'ConfigurationController@ventas')->name('ventas.configuration');
        Route::get('/logistica', 'ConfigurationController@logistica')->name('logistica.configuration');
        Route::get('/tesoreria', 'ConfigurationController@tesoreria')->name('tesoreria.configuration');
        Route::get('/contable', 'ConfigurationController@contable')->name('contable.configuration');
        Route::get('/activos', 'ConfigurationController@activos')->name('activos.configuration');
        Route::get('/planilla', 'ConfigurationController@planilla')->name('planilla.configuration');
        Route::get('/cpe', 'ConfigurationController@cpe')->name('cpe.configuration');
        Route::get('/produccion', 'ConfigurationController@produccion')->name('produccion.configuration');
        Route::get('/transporte', 'ConfigurationController@transporte')->name('transporte.configuration');
        Route::get('/procesar_maestros', 'ConfigurationController@procesar_maestros')->name('procesar_maestros.configuration');
        Route::get('/procesar_generales', 'ConfigurationController@procesar_generales')->name('procesar_generales.configuration');
        Route::get('/procesar_compras', 'ConfigurationController@procesar_compras')->name('procesar_compras.configuration');
        Route::get('/procesar_logistica', 'ConfigurationController@procesar_logistica')->name('procesar_logistica.configuration');
        Route::get('/procesar_ventas', 'ConfigurationController@procesar_ventas')->name('procesar_ventas.configuration');
        Route::get('/procesar_tesoreria', 'ConfigurationController@procesar_tesoreria')->name('procesar_tesoreria.configuration');
        Route::get('/procesar_contable', 'ConfigurationController@procesar_contable')->name('procesar_contable.configuration');
        Route::get('/procesar_activos', 'ConfigurationController@procesar_activos')->name('procesar_activos.configuration');
        Route::get('/procesar_planilla', 'ConfigurationController@procesar_planilla')->name('procesar_planilla.configuration');
        Route::get('/procesar_cpe', 'ConfigurationController@procesar_cpe')->name('procesar_cpe.configuration');
        Route::get('/procesar_produccion', 'ConfigurationController@procesar_produccion')->name('procesar_produccion.configuration');
        Route::get('/buscar_tipomovimiento', 'ConfigurationController@buscar_tipomovimiento');
        Route::get('/buscar_taxes', 'ConfigurationController@buscar_taxes');
        Route::get('/buscar_commercial', 'ConfigurationController@buscar_commercial');
        Route::get('/buscar_pcg', 'ConfigurationController@buscar_pcg');
        Route::get('/buscar_ubigeo', 'CustomerController@buscar_ubigeo');
    });

    Route::prefix('user_management')->group(function () {
        Route::get('/', 'UserManagementController@index')->name('user_management');
        Route::get('/list', 'UserManagementController@list')->name('list.user_management');
        Route::get('/create', 'UserManagementController@create')->name('create.user_management');
        Route::post('/store', 'UserManagementController@store')->name('store.user_management');
        Route::get('/{id}/edit', 'UserManagementController@edit')->name('edit.user_management');
        Route::put('/{id}', 'UserManagementController@update')->name('update.user_management');
        Route::post('/update_privilegios', 'UserManagementController@update_privilegios')->name('update_privilegios.user_management');
        Route::get('/loaduser', 'UserManagementController@loaduser')->name('loaduser.user_management');
        Route::get('/maestrosproductos', 'UserManagementController@maestrosproductos')->name('maestrosproductos.user_management');
        Route::get('/maestrosterceros', 'UserManagementController@maestrosterceros')->name('maestrosterceros.user_management');
        Route::get('/maestroscostos', 'UserManagementController@maestroscostos')->name('maestroscostos.user_management');
        Route::get('/maestrosotros', 'UserManagementController@maestrosotros')->name('maestrosotros.user_management');
        Route::get('/comprasconfiguracion', 'UserManagementController@comprasconfiguracion')->name('comprasconfiguracion.user_management');
        Route::get('/comprastransacciones', 'UserManagementController@comprastransacciones')->name('comprastransacciones.user_management');
        Route::get('/comprasprocesos', 'UserManagementController@comprasprocesos')->name('comprasprocesos.user_management');
        Route::get('/comprasreportes', 'UserManagementController@comprasreportes')->name('comprasreportes.user_management');
        Route::get('/ventasconfiguracion', 'UserManagementController@ventasconfiguracion')->name('ventasconfiguracion.user_management');
        Route::get('/ventastransacciones', 'UserManagementController@ventastransacciones')->name('ventastransacciones.user_management');
        Route::get('/ventasprocesos', 'UserManagementController@ventasprocesos')->name('ventasprocesos.user_management');
        Route::get('/ventasreportes', 'UserManagementController@ventasreportes')->name('ventasreportes.user_management');
        Route::get('/logisticaconfiguracion', 'UserManagementController@logisticaconfiguracion')->name('logisticaconfiguracion.user_management');
        Route::get('/logisticatransacciones', 'UserManagementController@logisticatransacciones')->name('logisticatransacciones.user_management');
        Route::get('/logisticaprocesos', 'UserManagementController@logisticaprocesos')->name('logisticaprocesos.user_management');
        Route::get('/logisticareportes', 'UserManagementController@logisticareportes')->name('logisticareportes.user_management');
        Route::get('/tesoreriaconfiguracion', 'UserManagementController@tesoreriaconfiguracion')->name('tesoreriaconfiguracion.user_management');
        Route::get('/tesoreriatransacciones', 'UserManagementController@tesoreriatransacciones')->name('tesoreriatransacciones.user_management');
        Route::get('/tesoreriaprocesos', 'UserManagementController@tesoreriaprocesos')->name('tesoreriaprocesos.user_management');
        Route::get('/tesoreriareportes', 'UserManagementController@tesoreriareportes')->name('tesoreriareportes.user_management');
        Route::get('/contabilidadconfiguracion', 'UserManagementController@contabilidadconfiguracion')->name('contabilidadconfiguracion.user_management');
        Route::get('/contabilidadtransacciones', 'UserManagementController@contabilidadtransacciones')->name('contabilidadtransacciones.user_management');
        Route::get('/contabilidadprocesos', 'UserManagementController@contabilidadprocesos')->name('contabilidadprocesos.user_management');
        Route::get('/contabilidadreportes', 'UserManagementController@contabilidadreportes')->name('contabilidadreportes.user_management');
        Route::get('/especial', 'UserManagementController@especial')->name('especial.user_management');
        Route::get('/especial2', 'UserManagementController@especial2')->name('especial2.user_management');
        Route::get('/tributosconfiguracion', 'UserManagementController@tributosconfiguracion')->name('tributosconfiguracion.user_management');
        Route::get('/tributostransacciones', 'UserManagementController@tributostransacciones')->name('tributostransacciones.user_management');
        Route::get('/tributosprocesos', 'UserManagementController@tributosprocesos')->name('tributosprocesos.user_management');
        Route::get('/tributosreportes', 'UserManagementController@tributosreportes')->name('tributosreportes.user_management');
        Route::get('/activosconfiguracion', 'UserManagementController@activosconfiguracion')->name('activosconfiguracion.user_management');
        Route::get('/activostransacciones', 'UserManagementController@activostransacciones')->name('activostransacciones.user_management');
        Route::get('/activosprocesos', 'UserManagementController@activosprocesos')->name('activosprocesos.user_management');
        Route::get('/activosreportes', 'UserManagementController@activosreportes')->name('activosreportes.user_management');
        Route::get('/utilitarioopciones', 'UserManagementController@utilitarioopciones')->name('utilitarioopciones.user_management');

        Route::get('/loaddata', 'UserManagementController@loaddata')->name('loaddata.user_management');
    });

    Route::prefix('sellingpoints')->group(function() {
        Route::get('/', 'SellingPointsController@index')->name('sellingpoints');
        Route::get('list', 'SellingPointsController@list')->name('list.sellingpoints');
        Route::get('/create', 'SellingPointsController@create')->name('create.sellingpoints');
        Route::get('/documentos_asociados','SellingPointsController@documentos_asociados');
        Route::post('/store', 'SellingPointsController@store')->name('store.sellingpoints');
        Route::get('/{id}/edit', 'SellingPointsController@edit')->name('edit.sellingpoints');
        Route::post('/agregar_documentos_asociados', 'SellingPointsController@agregar_documentos_asociados');
        Route::post('/editar_documentos_asociados', 'SellingPointsController@editar_documentos_asociados');
        Route::post('/eliminar_documentos_asociados', 'SellingPointsController@eliminar_documentos_asociados');
        Route::put('/{id}', 'SellingPointsController@update')->name('update.sellingpoints');
        Route::post('/eliminar', 'SellingPointsController@t_eliminar_registro');
        Route::post('/anular','SellingPointsController@t_anular_registro');
    });

    Route::prefix('pointofsale')->group(function() {
        Route::get('/', 'PointOfSaleController@index')->name('pointofsale');
        Route::get('/list', 'PointOfSaleController@list')->name('list.pointofsale');
        Route::get('/list_detalle_venta', 'PointOfSaleController@list_detalle_venta')->name('list_detalle_venta.pointofsale');
        Route::get('/list_cobranza_venta', 'PointOfSaleController@list_cobranza_venta')->name('list_cobranza_venta.pointofsale');
        Route::get('/list_products_venta', 'PointOfSaleController@list_products_venta')->name('list_products_venta.pointofsale');
        Route::get('/eliminar_datos', 'PointOfSaleController@eliminar_datos');//ok
        Route::get('/buscar_producto', 'PointOfSaleController@buscar_producto');//ok
        Route::post('/valida_codigo', 'PointOfSaleController@valida_codigo');//ok
        Route::get('/totalizar', 'PointOfSaleController@totalizar');//ok
        Route::get('/cobrar', 'PointOfSaleController@btncobrar');//ok
        Route::get('/serie', 'PointOfSaleController@txtserie_valid');//ok
        Route::get('/documentocom', 'PointOfSaleController@interactiveChange');//ok
        Route::get('/vence', 'PointOfSaleController@txtcodigo_vencimiento');//ok
        Route::post('/store', 'PointOfSaleController@store')->name('store.pointofsale');//falta
        Route::get('/validar_detalle_puntoventa', 'PointOfSaleController@valida_contenido');//ok
        Route::get('/detalle_cobro', 'PointOfSaleController@detalle_cobro');//ok
        Route::get('/buscar_vendedores', 'PointOfSaleController@buscar_vendedores');//ok
        Route::get('/forma_pago_cobranza', 'PointOfSaleController@forma_pago');//ok
        ///modificando
        Route::get('/valida_serie', 'PointOfSaleController@valida_serie');//ok
        Route::get('/validar_cantidad', 'PointOfSaleController@validar_cantidad');//ok
        Route::get('/existe_stock/', 'PointOfSaleController@existe_stock');//ok
        Route::get('/validar_precio', 'PointOfSaleController@validar_precio'); // ok
        Route::get('/validar_descuento', 'PointOfSaleController@validar_descuento'); // ok
        Route::post('/agregar_productos', 'PointOfSaleController@agregar_productos'); //ok
        Route::get('/impuesto', 'PointOfSaleController@cboimpuesto');//ok
        Route::get('/impuesto2', 'PointOfSaleController@cboimpuesto2');//ok
    });
    Route::prefix('pricelist')->group(function() {
        Route::get('/', 'PriceListController@index')->name('pricelist');
        Route::get('/create', 'PriceListController@list')->name('create.pricelist');
        Route::get('/buscar_productos', 'PriceListController@buscar_producto');
        Route::post('/store', 'PriceListController@store')->name('store.pricelist');
        Route::get('/listar_detalle', 'PriceListController@listar_detalle');
        Route::get('/editar', 'PriceListController@editar');
    });
    Route::prefix('customerquote')->group(function() {
        Route::get('/', 'CustomerQuoteController@index')->name('customerquote');
        Route::get('/list', 'CustomerQuoteController@list')->name('list.customerquote');
        Route::get('/create', 'CustomerQuoteController@create')->name('create.customerquote');
        Route::post('/store', 'CustomerQuoteController@store')->name('store.customerquote');

    });

    Route::prefix('lowcommunication')->group(function() {
        Route::get('/', 'LowCommunicationController@index')->name('lowcommunication');
        Route::get('/list', 'LowCommunicationController@list')->name('list.lowcommunication');
        Route::get('/create', 'LowCommunicationController@create')->name('create.lowcommunication');
        Route::post('/store', 'LowCommunicationController@store')->name('store.lowcommunication');
        Route::get('/{id}/edit', 'LowCommunicationController@edit')->name('edit.lowcommunication');
        Route::put('/{id}', 'LowCommunicationController@update')->name('update.lowcommunication');
        Route::get('/listDeatilLowCommunication', 'LowCommunicationController@listDeatilLowCommunication')->name('listDeatilLowCommunication.lowcommunication');
        Route::get('/references', 'LowCommunicationController@references')->name('references.lowcommunication');
        Route::get('/aux_aplicacion', 'LowCommunicationController@aux_aplicacion');
        Route::get('/mostrar', 'LowCommunicationController@btnrefresh_click');
        Route::get('/check', 'LowCommunicationController@o_check1_valid');
        Route::get('/btnok', 'LowCommunicationController@btnok');
        Route::get('/todoscheck', 'LowCommunicationController@o_check_total_valid');
        Route::get('/estado', 'LowCommunicationController@t_aprobar')->name('estado.lowcommunication');
        Route::post('/procesar', 'LowCommunicationController@btncdr_click');
        Route::get('/eliminar_datos', 'LowCommunicationController@eliminar_datos');//ok
    });

    Route::prefix('sendingCpe')->group(function() {
        Route::get('/', 'SendingCpeController@index')->name('sendingCpe');
        Route::get('/limpiar_datatable', 'SendingCpeController@limpiar_datatable');
        Route::get('/mostrar', 'SendingCpeController@mostrar');
        Route::post('/procesar', 'SendingCpeController@procesar');
    });    
});
