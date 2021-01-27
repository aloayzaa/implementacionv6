-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-01-2020 a las 09:09:18
-- Versión del servidor: 5.5.64-MariaDB
-- Versión de PHP: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `panel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivo`
--

CREATE TABLE `archivo` (
  `id` int(11) NOT NULL,
  `arc_codigo` varchar(20) NOT NULL,
  `arc_descripcion` varchar(200) NOT NULL,
  `arc_observaciones` varchar(200) DEFAULT NULL,
  `arc_estado` smallint(1) DEFAULT NULL,
  `arc_usuario` varchar(20) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `arc_ruta` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificado`
--

CREATE TABLE `certificado` (
  `id` int(11) NOT NULL,
  `usuario_sunat` varchar(45) NOT NULL,
  `password_sunat` varchar(45) NOT NULL,
  `password` varchar(250) NOT NULL,
  `tipo` varchar(250) NOT NULL,
  `p12` varchar(250) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conexion`
--

CREATE TABLE `conexion` (
  `id` int(11) NOT NULL,
  `con_codigo` varchar(20) NOT NULL,
  `con_descripcion` varchar(200) NOT NULL,
  `con_estado` smallint(1) NOT NULL,
  `con_usuario` varchar(20) NOT NULL,
  `con_password` varchar(200) DEFAULT NULL,
  `con_bd` varchar(20) DEFAULT NULL,
  `con_puerto` char(5) DEFAULT NULL,
  `emp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupon`
--

CREATE TABLE `cupon` (
  `id` int(10) NOT NULL,
  `cup_codigo` varchar(200) DEFAULT NULL,
  `cup_nombres` varchar(150) DEFAULT NULL,
  `cup_apellidos` varchar(150) DEFAULT NULL,
  `cup_telefono` char(20) DEFAULT NULL,
  `cup_ocupacion` varchar(100) DEFAULT NULL,
  `cup_fecha_registro` date DEFAULT NULL,
  `cup_estado` int(10) DEFAULT NULL,
  `cup_nro_inicial` int(11) DEFAULT NULL,
  `cup_nro_actual` int(11) DEFAULT NULL,
  `cup_porcentaje_dcto` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cupon`
--

CREATE TABLE `detalle_cupon` (
  `id` int(11) NOT NULL,
  `detc_fecha_uso` date DEFAULT NULL,
  `detc_estado` int(11) DEFAULT NULL,
  `detc_monto` decimal(10,2) DEFAULT NULL,
  `cup_id` int(10) NOT NULL,
  `est_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `emp_codigo` varchar(20) NOT NULL,
  `emp_descripcion` varchar(200) NOT NULL,
  `emp_ruc` varchar(20) NOT NULL,
  `emp_contacto` varchar(200) DEFAULT NULL,
  `emp_direccion` varchar(200) DEFAULT NULL,
  `emp_telefono` varchar(15) DEFAULT NULL,
  `emp_observaciones` varchar(200) DEFAULT NULL,
  `emp_estado` smallint(1) DEFAULT '1',
  `emp_usuario` varchar(20) DEFAULT NULL,
  `tem_id` int(11) DEFAULT NULL,
  `est_id` int(11) DEFAULT NULL,
  `emp_color` varchar(45) DEFAULT NULL,
  `emp_razon_social` varchar(200) DEFAULT NULL,
  `emp_fav` smallint(1) NOT NULL DEFAULT '0',
  `emp_fecha_registro` date DEFAULT NULL,
  `ver_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `emp_codigo`, `emp_descripcion`, `emp_ruc`, `emp_contacto`, `emp_direccion`, `emp_telefono`, `emp_observaciones`, `emp_estado`, `emp_usuario`, `tem_id`, `est_id`, `emp_color`, `emp_razon_social`, `emp_fav`, `emp_fecha_registro`, `ver_id`) VALUES
(1, '20482699821', 'INVERSIONES Y MULTISERVICIOS GONZALES S.A.C.', '20482699821', 'demo demo', 'AV. FEDERICO VILLARREAL-1317A NRO . SEC. SANTA JULIA I', '123456789', '', 1, '1', NULL, NULL, NULL, 'INVERSIONES Y MULTISERVICIOS GONZALES S.A.C.', 0, '2019-02-26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudio`
--

CREATE TABLE `estudio` (
  `id` int(11) NOT NULL,
  `est_codigo` varchar(20) DEFAULT NULL,
  `est_descripcion` varchar(200) DEFAULT NULL,
  `est_ruc` varchar(20) DEFAULT NULL,
  `est_usuario` varchar(20) DEFAULT NULL,
  `est_estado` smallint(1) DEFAULT NULL,
  `est_detalle` text,
  `est_telefono` varchar(15) DEFAULT NULL,
  `est_encargado` varchar(200) DEFAULT NULL,
  `ver_id` int(11) DEFAULT NULL,
  `est_fecha_registro` date DEFAULT NULL,
  `est_cantidad` int(11) NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma_pago`
--

CREATE TABLE `forma_pago` (
  `id` int(11) NOT NULL,
  `fpa_codigo` varchar(20) NOT NULL,
  `fpa_descripcion` varchar(200) NOT NULL,
  `fpa_estado` smallint(1) NOT NULL,
  `fpa_usuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `forma_pago`
--

INSERT INTO `forma_pago` (`id`, `fpa_codigo`, `fpa_descripcion`, `fpa_estado`, `fpa_usuario`) VALUES
(1, 'DEP', 'DEPOSITO EN AGENTE', 1, ''),
(2, 'DEP', 'TRANSFERENCIA', 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `modulo` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `codigo` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `descripcion` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `comando` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ventana` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `estado` decimal(1,0) NOT NULL DEFAULT '1',
  `usuario` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `modulo`, `codigo`, `descripcion`, `comando`, `ventana`, `estado`, `usuario`) VALUES
(1, 'Maestros', '01', 'Maestros', '', '', '1', ''),
(2, 'Maestros', '01.01', 'Productos', '', '', '1', ''),
(3, 'Logistica', '02.04.06', 'Consolidado de Movimientos', 'do form forms\\rpt_consolidadomovalmacen', 'rpt_consolidadomovalmacen', '0', ''),
(4, 'Logistica', '02.04.01', 'Kardex de Producto', 'do form forms\\rpt_kardex', 'rpt_kardex', '1', ''),
(5, 'Logistica', '02.04.03', 'Stock por Almacen', 'do form forms\\rpt_stockalmacen', 'rpt_stockalmacen', '1', ''),
(6, 'Logistica', '02.04.07', 'Documentos de Provisión', 'do form forms\\rpt_provisionalmacen', 'rpt_provisionalmacen', '0', ''),
(7, 'Utilitario', '99', 'Utilitarios', '', '', '1', ''),
(8, 'Utilitario', '99.01', 'Opciones', '', '', '1', ''),
(9, 'Utilitario', '99.01.01', 'Configurar Sistema', 'do form forms\\tab_pempresa', 'tab_pempresa', '1', 'FHARO'),
(10, 'Utilitario', '99.01.02', 'Editor de Menús', 'do form forms\\menus', 'menus', '1', 'FHARO'),
(11, 'Logistica', '02.01.02', 'Centralización Contable', 'do form forms\\tab_almacen_contab', 'tab_almacen_contab', '1', ''),
(12, 'Logistica', '02.04.04', 'Resumen Movimientos Almacén', 'do form forms\\rpt_movalmacenresumen', 'rpt_movalmacenresumen', '0', ''),
(13, 'Utilitario', '99.01.03', 'Gestión de Usuario', 'do form forms\\tab_usuario', 'tab_usuario', '1', ''),
(14, 'Contabilidad', '06', 'Contabilidad', '', '', '1', 'ADMINISTRADOR'),
(15, 'Contabilidad', '06.01', 'Configuración', '', '', '1', 'ADMINISTRADOR'),
(16, 'Ventas', '03.01.03', 'Lista de Precios', 'do form forms\\tab_listaprecio', 'tab_listaprecio', '0', ''),
(17, 'Tesoreria', '05', 'Tesorería', '', '', '1', ''),
(18, 'Tributos', '13.01.01', 'Impuestos', 'do form forms\\tab_impuesto', 'tab_impuesto', '1', ''),
(19, 'Ventas', '03.01.01', 'Tipos de Venta', 'do form forms\\tab_tipoventa', 'tab_tipoventa', '1', ''),
(20, 'Maestros', '01.02.06', 'Tipos Documento Comercial', 'do form forms\\tab_documentocom', 'tab_documentocom', '1', ''),
(21, 'Maestros', '01.01.06', 'Colores', 'do frmtabla in prgs\\procedimientos with \'color\', \'Colores\'', 'tab_color', '0', ''),
(22, 'Ventas', '03.02.05', 'Facturación', 'do form forms\\mov_facturaventa', 'mov_facturaventa', '1', ''),
(23, 'Ventas', '03.02.04', 'Pedidos de Venta', 'do form forms\\mov_pedidoptovta', 'mov_pedidoptovta', '0', 'ADMINISTRADOR'),
(25, 'Logistica', '02.02.01', 'Pedido de Servicio', 'do form forms\\mov_pedidoservicio', 'mov_pedidoservicio', '0', 'ADMINISTRADOR'),
(26, 'Ventas', '03.02.01', 'Apertura por Cobrar', 'do form forms\\mov_aperturaxcobrar', 'mov_aperturaxcobrar', '1', ''),
(27, 'Ventas', '03.02', 'Transacciones', '', '', '1', ''),
(28, 'Tesoreria', '05.02.06', 'Letras', 'do form forms\\mov_notacontable_letra', 'mov_notacontable_letra', '0', ''),
(29, 'Ventas', '03.04.02', 'Saldos por Cobrar', 'do form forms\\rpt_saldosxcobrar', 'rpt_saldosxcobrar', '1', ''),
(30, 'Ventas', '03.04.01', 'Cuenta Corriente - Cliente', 'do form forms\\rpt_ctactexcobrar', 'rpt_ctactexcobrar', '1', ''),
(31, 'Ventas', '03.04.03', 'Saldos por Cobrar - Rangos', 'do form forms\\rpt_saldosxcobrar_rangos', 'rpt_saldosxcobrar_rangos', '0', ''),
(32, 'Tributos', '13.04.02', 'Registro de Ventas', 'do form forms\\rpt_ventasregistro', 'rpt_ventasregistro', '1', ''),
(33, 'Contabilidad', '06.03.01', 'Abrir / Cerrar Periodos', 'do form forms\\aux_periodos with \'CON\'', 'prc_periodos', '1', 'ADMINISTRADOR'),
(35, 'Compras', '04.02.01', 'Apertura por Pagar', 'do form forms\\mov_aperturaxpagar', 'mov_aperturaxpagar', '1', ''),
(36, 'Maestros', '01.02', 'Terceros', '', '', '1', ''),
(37, 'Compras', '04.02.04', 'Provisiones por Pagar', 'do form forms\\mov_docxpagar', 'mov_docxpagar', '1', ''),
(38, 'Compras', '04.01.02', 'Cuentas por Documento', 'do form forms\\tab_cxp_contab', 'tab_cxp_contab', '1', ''),
(39, 'Maestros', '01.02.07', 'Tipos de Transacción', 'do frmtabla in prgs\\procedimientos with \'tipotransaccion\', \'Tipo de Transacción\'', 'tab_tipotransaccion', '0', ''),
(40, 'Compras', '04.04.01', 'Cuenta Corriente - Proveedor', 'do form forms\\rpt_ctactexpagar', 'rpt_ctactexpagar', '1', ''),
(41, 'Compras', '04.04.03', 'Saldos por Pagar - Rangos', 'do form forms\\rpt_saldosxpagar_rangos', 'rpt_saldosxpagar_rangos', '0', ''),
(42, 'Maestros', '01.03.02', 'Actividades de Costo', 'do form forms\\tab_actividad', 'tab_actividad', '0', ''),
(43, 'Maestros', '01.03.04', 'Proyectos', 'do form forms\\tab_proyecto', 'tab_proyecto', '0', ''),
(44, 'Ventas', '03.04.04', 'Resumen de Ventas', 'do form forms\\rpt_ventasresumen', 'rpt_ventasresumen', '1', ''),
(45, 'Ventas', '03', 'Gestión de Ventas', '', '', '1', ''),
(46, 'Maestros', '01.03.03', 'Plan de Cuentas', 'do form forms\\tab_pcg', 'tab_pcg', '1', ''),
(47, 'Maestros', '01.04', 'Otros', '', '', '1', ''),
(48, 'Maestros', '01.03.01', 'Centros de Costo', 'do form forms\\tab_centrocosto', 'tab_centrocosto', '1', ''),
(49, 'Maestros', '01.02.04', 'Condiciones de Pago', 'do frmtabla in prgs\\procedimientos with \'condicionpago\', \'Condiciones de Pago\'', 'tab_condicionpago', '0', ''),
(50, 'Tributos', '13.01.03', 'Tipos de Cambio', 'do form forms\\tab_tcambio', 'tab_tcambio', '1', ''),
(51, 'Contabilidad', '06.02', 'Transacciones', '', '', '1', 'ADMINISTRADOR'),
(52, 'Tesoreria', '05.02.02', 'Movimientos de Caja', 'do form forms\\mov_caja', 'mov_caja', '1', ''),
(53, 'Tesoreria', '05.02.04', 'Transferencias', 'do form forms\\mov_transferenciabanco', 'mov_transferenciabanco', '1', ''),
(54, 'Tesoreria', '05.02.07', 'Conciliación Bancaria', 'do form forms\\mov_conciliacion', 'mov_conciliacion', '0', ''),
(55, 'Utilitario', '99.01.04', 'Aprobar Documentos', 'do form forms\\aux_aprobacion', 'aux_aprobacion', '1', ''),
(56, 'Contabilidad', '06.04', 'Reportes', '', '', '1', 'ADMINISTRADOR'),
(57, 'Tesoreria', '05.04.01', 'Libro Caja y Bancos', 'do form forms\\rpt_librobanco', 'rpt_librobanco', '1', ''),
(58, 'Contabilidad', '06.04.04.02', 'Detalle Movimientos de la Cta.Cte.', 'do form forms\\rpt_movimiento_ctacte', 'rpt_movimiento_ctacte', '1', 'ADMINISTRADOR'),
(59, 'Contabilidad', '06.04.04.01', 'Detalle Movimientos del Efectivo', 'do form forms\\rpt_movimiento_efectivo', 'rpt_movimiento_efectivo', '1', 'ADMINISTRADOR'),
(60, 'Tesoreria', '05.04.03', 'Relacion de Cheques Emitidos', 'do form forms\\rpt_cheques_emitidos', 'rpt_cheques_emitidos', '1', ''),
(61, 'Compras', '04.02.02', 'Ordenes de Compra', 'do form forms\\mov_ordencompra', 'mov_ordencompra', '0', ''),
(62, 'Contabilidad', '06.03', 'Procesos', '', '', '1', 'ADMINISTRADOR'),
(63, 'Maestros', '01.04.06', 'SubDiarios', 'do form forms\\tab_subdiario', 'tab_subdiario', '0', ''),
(64, 'Logistica', '02.02', 'Transacciones', '', '', '1', ''),
(65, 'Contabilidad', '06.01.02', 'Estados Financieros', 'do form forms\\tab_eeff', 'tab_eeff', '1', 'ADMINISTRADOR'),
(66, 'Tributos', '13.01.02', 'Periodos', 'do form forms\\tab_periodo', 'tab_periodo', '1', ''),
(67, 'Ventas', '03.01.05', 'Cuentas por Tipo de Venta', 'do form forms\\tab_tipoventa_contab', 'tab_tipoventa_contab', '1', ''),
(68, 'Logistica', '02.03', 'Procesos', '', '', '1', ''),
(70, 'Contabilidad', '06.02.02', 'Asientos de Diario Automatico', 'do form forms\\mov_notacontable', 'mov_notacontable', '1', 'ADMINISTRADOR'),
(71, 'Contabilidad', '06.02.01', 'Asiento de Apertura', 'do form forms\\mov_aperturacontable', 'mov_aperturacontable', '1', 'ADMINISTRADOR'),
(72, 'Ventas', '03.01', 'Configuración', '', '', '1', ''),
(73, 'Contabilidad', '06.04.05.04', 'Balance de Comprobación', 'do form forms\\rpt_balancecomprobacion', 'rpt_balancecomprobacion', '1', 'ADMINISTRADOR'),
(74, 'Contabilidad', '06.04.02', 'Libro Diario', 'do form forms\\rpt_librodiario', 'rpt_librodiario', '1', 'ADMINISTRADOR'),
(75, 'Contabilidad', '06.04.03', 'Libro Mayor', 'do form forms\\rpt_libromayor', 'rpt_libromayor', '1', 'ADMINISTRADOR'),
(76, 'Tributos', '13.04.03', 'Libro de Retenciones Renta 4ta', 'do form forms\\rpt_comprasretencion', 'rpt_comprasretencion', '1', 'ADMINISTRADOR'),
(77, 'Contabilidad', '06.04.04', 'Libro Caja Bancos', '', '', '1', 'ADMINISTRADOR'),
(79, 'Contabilidad', '06.04.01', 'Movimiento por Cuenta', 'do form forms\\rpt_movimientoxcuenta', 'rpt_movimientoxcuenta', '1', 'ADMINISTRADOR'),
(80, 'Planillas', '08.02.09.02', 'Planilla Vacaciones', 'do form forms\\mov_planillavac', 'mov_planillavac', '1', ''),
(81, 'Ventas', '03.01.02', 'Puntos de Venta', 'do form forms\\tab_puntoventa', 'tab_puntoventa', '0', ''),
(82, 'Ventas', '03.04.05', 'Detalle de Ventas', 'do form forms\\rpt_ventasdetalle', 'rpt_ventasdetalle', '1', ''),
(83, 'Tesoreria', '05.04.04', 'Saldos Conciliados', 'do form forms\\rpt_conciliacion', 'rpt_conciliacion', '0', ''),
(84, 'Tesoreria', '05.02.03', 'Movimientos de Bancos', 'do form forms\\mov_banco', 'mov_banco', '1', ''),
(85, 'Tesoreria', '05.01.04', 'Medios de Pago', 'do frmtabla in prgs\\procedimientos with \'mediopago\', \'Medios de Pago\'', 'tab_mediopago', '1', ''),
(86, 'Tesoreria', '05.01.01', 'Tipos de Operación', 'do form forms\\tab_tipooperacion', 'tab_tipooperacion', '1', ''),
(87, 'Tesoreria', '05.01', 'Configuración', '', '', '1', ''),
(88, 'Tesoreria', '05.01.02', 'Entidades Bancarias', 'do form forms\\tab_banco', 'tab_banco', '1', ''),
(89, 'Contabilidad', '06.04.05.02', 'Cuentas Corrientes', 'do form forms\\rpt_ib12', 'rpt_ib12', '0', 'ADMINISTRADOR'),
(90, 'Contabilidad', '06.04.05.01', 'Caja y Bancos', 'do form forms\\rpt_ib10', 'rpt_ib10', '0', ''),
(92, 'Logistica', '02', 'Logistica y Almacenes', '', '', '1', 'ADMINISTRADOR'),
(93, 'Maestros', '01.02.05', 'Tipos Documento Identidad', 'do frmtabla in prgs\\procedimientos with \'documentoide\', \'Documentos de Identidad\'', 'tab_documentoide', '0', ''),
(94, 'Compras', '04.02.03', 'Orden de Servicio', 'do form forms\\mov_ordenservicio', 'mov_ordenservicio', '0', ''),
(95, 'Ventas', '03.01.04', 'Cuentas por Documento', 'do form forms\\tab_cxc_contab', 'tab_cxc_contab', '1', ''),
(96, 'Contabilidad', '06.04.05.05', 'Estado de Flujo del Efectivo', 'do form forms\\rpt_flujoefectivo', 'rpt_flujoefectivo', '0', 'ADMINISTRADOR'),
(97, 'Contabilidad', '06.03.03', 'Centralización Contable por Módulo', 'do form forms\\prc_centralizar', 'prc_centralizar', '1', ''),
(100, 'Tributos', '13.03.02', 'PDB - Programa de Declaración de Beneficios', 'do form forms\\rpt_pdb', 'rpt_pdb', '1', 'ADMINISTRADOR'),
(101, 'Tributos', '13.03.01', 'PDT - Operaciones con Terceros', 'do form forms\\rpt_daot', 'rpt_daot', '1', 'ADMINISTRADOR'),
(102, 'Logistica', '02.04', 'Reportes', '', '', '1', ''),
(103, 'Maestros', '01.04.05', 'Monedas', 'do form forms\\tab_moneda', 'tab_moneda', '0', ''),
(104, 'Maestros', '01.03', 'Costos', '', '', '1', ''),
(105, 'Maestros', '01.01.01', 'Catálogo de Productos', 'do form forms\\tab_producto', 'tab_producto', '1', ''),
(106, 'Maestros', '01.02.01', 'Catálogo de Terceros', 'do form forms\\tab_tercero', 'tab_tercero', '1', ''),
(107, 'Maestros', '01.01.05', 'Unidades de Medida', 'do form forms\\tab_umedida', 'tab_umedida', '0', ''),
(108, 'Logistica', '02.03.02', 'Valorizar Movimientos de Almacenes', 'do form forms\\prc_contabalm', 'prc_contabalm', '1', ''),
(110, 'Compras', '04.04.05', 'Listado de Provisiones', 'do form forms\\rpt_comprasdetalle', 'rpt_comprasdetalle', '0', 'ADMINISTRADOR'),
(111, 'Contabilidad', '06.04.05', 'Inventarios y Balances', '', '', '1', ''),
(112, 'Contabilidad', '06.02.04', 'Ajustes por Diferencia de Cambio', 'do form forms\\mov_notacontable_ajuste', 'mov_notacontable_ajuste', '1', ''),
(114, 'Logistica', '02.01', 'Configuración', '', '', '1', ''),
(117, 'Maestros', '01.02.02', 'Clasificación', 'do frmtabla in prgs\\procedimientos with \'clasetercero\', \'Clasificación Terceros\'', 'tab_clasetercero', '0', ''),
(118, 'Maestros', '01.01.02', 'Familias', 'do frmtabla in prgs\\procedimientos with \'familiapdto\', \'Familias de Productos\'', 'tab_familiapdto', '0', ''),
(119, 'Logistica', '02.03.01', 'Abrir/Cerrar Periodos', 'do form forms\\aux_periodos with \'ALM\'', 'aux_periodos', '0', ''),
(120, 'Tesoreria', '05.04.02', 'Consolidado de Bancos', 'do form forms\\rpt_consolidadobancos', 'rpt_consolidadobancos', '1', ''),
(122, 'Compras', '04.04.02', 'Saldos por Pagar', 'do form forms\\rpt_saldosxpagar', 'rpt_saldosxpagar', '1', ''),
(123, 'Planillas', '08.02.09.03', 'Planilla Gratificaciones', 'do form forms\\mov_planillagrati', 'mov_planillagrati', '1', ''),
(124, 'Tesoreria', '05.02.01', 'Apertura de Bancos', 'do form forms\\mov_aperturabanco', 'mov_aperturabanco', '1', ''),
(125, 'Contabilidad', '06.04.05.03', 'Mercaderías', 'do form forms\\rpt_ib20', 'rpt_ib20', '0', ''),
(127, 'Compras', '04', 'Gestión de Compras', '', '', '1', ''),
(128, 'Compras', '04.01', 'Configuración', '', '', '1', ''),
(129, 'Tesoreria', '05.02', 'Transacciones', '', '', '1', ''),
(130, 'Tesoreria', '05.03', 'Procesos', '', '', '1', ''),
(131, 'Tesoreria', '05.04', 'Reportes', '', '', '1', 'ADMINISTRADOR'),
(132, 'Compras', '04.03', 'Procesos', '', '', '1', ''),
(133, 'Planillas', '08.01.01', 'Tablas', '', '', '1', ''),
(136, 'Tesoreria', '05.03.01', 'Abrir/Cerrar Periodo', 'do form forms\\aux_periodos with \'TES\'', 'prc_periodos', '1', ''),
(137, 'Compras', '04.04', 'Reportes', '', '', '1', ''),
(138, 'Compras', '04.02', 'Transacciones', '', '', '1', ''),
(139, 'Compras', '04.04.04', 'Resumen de Compras', 'do form forms\\rpt_comprasresumen', 'rpt_comprasresumen', '1', ''),
(140, 'Tributos', '13.04.01', 'Registro de Compras', 'do form forms\\rpt_comprasregistro', 'rpt_comprasregistro', '1', ''),
(141, 'Compras', '04.04.06', 'Liquidaciones de Gastos', 'do form forms\\rpt_liquidaciongasto', 'rpt_liquidaciongasto', '0', ''),
(142, 'Planillas', '08.02.09.04', 'Planilla CTS', 'do form forms\\mov_planillacts', 'mov_planillacts', '1', ''),
(144, 'Compras', '04.01.01', 'Tipos de Detracción', 'do frmtabla in prgs\\procedimientos with \'tipodetraccion\', \'Tipo Detracción\'', 'tab_tipodetraccion', '1', ''),
(145, 'Compras', '04.03.01', 'Abrir / Cerrar Periodos', 'do form forms\\aux_periodos with \'CXP\'', 'rpt_prc_periodos', '1', ''),
(146, 'Compras', '04.01.03', 'Tipos de Gasto', 'do form forms\\tab_tipocompra', 'tab_tipocompra', '1', ''),
(148, 'Maestros', '01.04.02', 'Almacenes', 'do form forms\\tab_almacen', 'tab_almacen', '1', ''),
(149, 'Contabilidad', '06.04.07', 'Detalle de Gastos', 'do form forms\\rpt_gastosdetalle', 'rpt_gastosdetalle', '0', ''),
(151, 'Contabilidad', '06.04.05.07', 'Estado Financiero - Anual', 'do form forms\\rpt_eeff', 'rpt_eeff', '1', ''),
(152, 'Contabilidad', '06.04.05.06', 'Estado de Cambios Patrimonio Neto', 'do form forms\\rpt_patrimonioneto', 'rpt_patrimonioneto', '0', 'ADMINISTRADOR'),
(153, 'Contabilidad', '06.04.05.08', 'Estado Financiero - Comparativo', 'do form forms\\rpt_eeffcomparativo', 'rpt_eeffcomparativo', '0', 'ADMINISTRADOR'),
(154, 'Tributos', '13.04.05', 'Activo Fijo', 'do form forms\\rpt_libroactivo', 'rpt_libroactivo', '0', 'ADMINISTRADOR'),
(155, 'Tributos', '13.03.05', 'PDT 621 - IGV Renta Mensual', 'do form forms\\rpt_pdt621', 'rpt_pdt621', '1', ''),
(156, 'Tributos', '13.02.02', 'Comprobantes de Percepción', 'do form forms\\mov_percepcion', 'mov_percepcion', '1', ''),
(158, 'Logistica', '02.01.01', 'Tipos de Movimiento', 'do form forms\\tab_movimientotipo', 'tab_movimientotipo', '0', ''),
(159, 'Tributos', '13', 'Gestión Tributaria', '', '', '1', 'ADMINISTRADOR'),
(160, 'Maestros', '01.02.03', 'Vendedores', 'do form forms\\tab_vendedor', 'tab_vendedor', '0', ''),
(161, 'Maestros', '01.04.01', 'Sucursales', 'do form forms\\tab_sucursal', 'tab_sucursal', '1', ''),
(162, 'Maestros', '01.01.04', 'Fichas, Recetas o Plantillas', 'do form forms\\tab_producto_ficha', 'tab_producto_ficha', '0', 'LPACHECO'),
(163, 'Logistica', '02.02.03', 'Ingresos a Almacén', 'do form forms\\mov_ingresoalmacen', 'mov_ingresoalmacen', '1', ''),
(164, 'Logistica', '02.02.04', 'Salidas de Almacén', 'do form forms\\mov_salidaalmacen', 'mov_salidaalmacen', '1', ''),
(165, 'Logistica', '02.02.02', 'Pedidos al Almacén', 'do form forms\\mov_pedidoalmacen', 'mov_pedidoalmacen', '0', ''),
(166, 'Compras', '04.02.06', 'Liquidación de Gastos', 'do form forms\\mov_liquidaciongasto', 'mov_liquidaciongasto', '0', ''),
(167, 'Logistica', '02.02.05', 'Transferencias', 'do form forms\\mov_transferenciaalmacen', 'mov_transferenciaalmacen', '0', ''),
(168, 'Logistica', '02.02.06', 'Partes de Transformación', 'do form forms\\mov_transformalmacen', 'mov_transformalmacen', '0', 'LPACHECO'),
(169, 'Maestros', '01.02.08', 'Tipos de Vía', 'do frmtabla in prgs\\procedimientos with \'tipovia\', \'Tipo de Vía\'', 'tab_tipovia', '0', ''),
(170, 'Ventas', '03.02.06', 'Registro Documentos Anulados', 'do form forms\\anuladocumento', 'anuladocumento', '1', 'ADMINISTRADOR'),
(171, 'Tributos', '13.01', 'Configuración', '', '', '1', ''),
(172, 'Ventas', '03.03.01', 'Abrir / Cerrar Periodos', 'do form forms\\aux_periodos with \'CXC\'', 'prc_periodos', '1', ''),
(173, 'Ventas', '03.04', 'Reportes', '', '', '1', ''),
(174, 'Ventas', '03.03', 'Procesos', '', '', '1', ''),
(186, 'Planillas', '08', 'Planillas', '', '', '1', ''),
(187, 'Planillas', '08.01', 'Configuración', '', '', '1', ''),
(188, 'Planillas', '08.02', 'Transacciones', '', '', '1', ''),
(189, 'Planillas', '08.04', 'Reportes', '', '', '1', ''),
(190, 'Planillas', '08.03', 'Procesos', '', '', '1', ''),
(198, 'Planillas', '08.02.01', 'Control Asistencia', 'do form forms\\control_asistencia_clinica', 'control_asistencia_clinica', '1', 'ADMINISTRADOR'),
(210, 'Contabilidad', '06.02.05', 'Asiento de Diario Manual', 'do form forms\\mov_notacontable_aplic', 'mov_notacontable_aplic', '1', 'ADMINISTRADOR'),
(212, 'Tributos', '13.02', 'Transacciones', '', '', '1', ''),
(226, 'Maestros', '01.01.03', 'Marcas y Modelos', 'do form forms\\tab_marca', 'tab_marca', '1', ''),
(227, 'Planillas', '08.01.01.01', 'Tipos de Planilla', 'do form forms\\tab_tipoplanilla', 'tab_tipoplanilla', '1', ''),
(228, 'Planillas', '08.01.02', 'Gestión de Personal', 'do form forms\\tab_tercero_personal', 'tab_tercero_personal', '1', ''),
(229, 'Planillas', '08.01.01.03', 'Cargos de Personal', 'do frmtabla in prgs\\procedimientos with \'tipocargo\', \'Cargos\'', 'tab_tipocargo', '1', ''),
(230, 'Planillas', '08.01.04', 'Conceptos de Remuneración', 'do form forms\\tab_concepto', 'tab_concepto', '1', ''),
(231, 'Planillas', '08.01.01.02', 'Tipos de Trabajador', 'do frmtabla in prgs\\procedimientos with \'tipotrabajador\', \'Tipo de Trabajador\'', 'tab_tipotrabajador', '1', ''),
(232, 'Planillas', '08.02.08', 'Registro de Tareo', 'do form forms\\mov_doctareo', 'mov_doctareo', '1', ''),
(233, 'Planillas', '08.02.09.01', 'Planilla Remuneraciones', 'do form forms\\mov_planilla', 'mov_planilla', '1', ''),
(234, 'Planillas', '08.04.01', 'Boletas de Pago', 'do form forms\\rpt_planillaboleta', 'rpt_planillaboleta', '1', 'ADMINISTRADOR'),
(235, 'Planillas', '08.04.02', 'Libro Planillas', 'do form forms\\rpt_planillalibro', 'rpt_planillalibro', '1', 'ADMINISTRADOR'),
(266, 'Compras', '04.02.05', 'Notas de Crédito', 'do form forms\\mov_ncreditocompra', 'mov_ncreditocompra', '1', ''),
(272, 'Tributos', '13.04', 'Reportes', '', '', '1', ''),
(273, 'Tributos', '13.03', 'Procesos', '', '', '1', ''),
(274, 'Planillas', '08.01.01.04', 'Nacionalidades', 'do frmtabla in prgs\\procedimientos with \'nacionalidad\', \'Nacionalidades\'', 'tab_nacionalidad', '1', ''),
(275, 'Planillas', '08.01.01.05', 'Ocupaciones', 'do frmtabla in prgs\\procedimientos with \'ocupacion\', \'Ocupaciones\'', 'tab_ocupacion', '1', ''),
(276, 'Planillas', '08.01.01.06', 'Tipos de Contrato', 'do frmtabla in prgs\\procedimientos with \'tipocontrato\', \'Tipos de Contrato\'', 'tab_tipocontrato', '1', ''),
(277, 'Planillas ', '08.01.01.07', 'Tipos de Pensión', 'do frmtabla in prgs\\procedimientos with \'tipopension\', \'Tipo de Pension\'', 'tab_tipopension', '1', ''),
(278, 'Planillas', '08.01.01.09', 'Situación EPS', 'do frmtabla in prgs\\procedimientos with \'situacioneps\', \'Situación EPS\'', 'tab_situacioneps', '1', ''),
(279, 'Planillas', '08.01.01.10', 'EPS', 'do form forms\\tab_eps', 'tab_eps', '1', ''),
(280, 'Maestros', '01.02.09', 'Tipo de Zona', 'do frmtabla in prgs\\procedimientos with \'tipozona\', \'Tipo de Zona\'', 'tab_tipozona', '0', ''),
(281, 'Planillas', '08.01.01.08', 'Tipos de Régimen Pensionario', 'do form forms\\tab_tiporegpension', 'tab_tiporegpension', '1', 'ADMINISTRADOR'),
(282, 'Planillas', '08.01.01.11', 'Nivel Educativo', 'do frmtabla in prgs\\procedimientos with \'niveleducativo\', \'Nivel Educativo\'', 'tab_niveleducativo', '1', ''),
(284, 'Tesoreria', '05.02.05', 'Anticipos y Entregas Rendir', 'do form forms\\mov_generaxpagar', 'mov_generaxpagar', '0', ''),
(288, 'Ventas', '03.04.06', 'Liquidación de Ventas', 'do form forms\\rpt_ventasptoventa', 'rpt_ventasptoventa', '0', 'ADMINISTRADOR'),
(290, 'Planillas', '08.01.03', 'Horarios', 'do form forms\\tab_horario', 'tab_horario', '1', ''),
(291, 'Planillas', '08.02.02', 'Partes de Asistencia', 'do form forms\\mov_asistencia', 'mov_asistencia', '1', ''),
(312, 'Compras', '04.04.07', 'Relación de Entregas y/o Anticipos', 'do form forms\\rpt_anticipoentrega', 'rpt_anticipoentrega', '0', ''),
(313, 'Planillas', '08.01.01.13', 'Variables de Planilla', 'do form forms\\tab_variable', 'tab_variable', '1', ''),
(314, 'Planillas', '08.02.03', 'Descansos Médicos', 'do form forms\\mov_dscmedico', 'mov_dscmedico', '1', ''),
(315, 'Planillas', '08.02.05', 'Retenciones Judiciales', 'do form forms\\mov_retjudicial', 'mov_retjudicial', '1', ''),
(316, 'Planillas', '08.01.01.12', 'Tipos Suspensión Laboral', 'do frmtabla in prgs\\procedimientos with \'tiposusplaboral\', \'Tipo de Suspensión Laboral\'', 'tab_tiposusplaboral', '1', ''),
(318, 'Maestros', '01.02.10', 'Rubros', 'do frmtabla in prgs\\procedimientos with \'tiporubro\', \'Tipo de Rubro\'', 'tab_tiporubro', '0', ''),
(319, 'Planillas', '08.02.06', 'Préstamos y Adelantos', 'do form forms\\mov_prestamo', 'mov_prestamo', '1', 'ADMINISTRADOR'),
(320, 'Tributos', '13.02.01', 'Comprobantes de Retención', 'do form forms\\mov_retencion', 'mov_retencion', '1', ''),
(321, 'Planillas', '08.03.01', 'Generar Adelanto', 'do form forms\\prc_adelanto', 'prc_adelanto', '1', ''),
(333, 'Contabilidad', '06.04.08', 'Validación de Asientos', 'do form forms\\rpt_validaasiento', 'rpt_validaasiento', '1', 'ADMINISTRADOR'),
(344, 'Logistica', '02.04.08', 'Consumo por Centro de Costo', 'do form forms\\rpt_consumoalmacen', 'rpt_consumoalmacen', '0', ''),
(355, 'Logistica', '02.04.02', 'Libro Kardex', 'do form forms\\rpt_libroinvpermanente', 'rpt_libroinvpermanente', '1', 'ADMINISTRADOR'),
(360, 'Planillas', '08.03.02', 'Foto', 'do form forms\\prc_capturafoto', 'prc_capturafoto', '1', ''),
(364, 'Ventas', '03.04.07', 'Ventas vs Compras', 'do form forms\\rpt_ventasvscompras', 'rpt_ventasvscompras', '0', 'ADMINISTRADOR'),
(379, 'Compras', '04.04.08', 'Seguimiento Ordenes de Compra', 'do form forms\\rpt_comprasestado', 'rpt_comprasestado', '0', 'ADMINISTRADOR'),
(380, 'Ventas', '03.01.06', 'Formas de Pago', 'do form forms\\tab_formapago', 'tab_formapago', '1', ''),
(387, 'Planillas', '08.01.05', 'Asignar Personal a Supervidor', 'do form forms\\aux_asignapersonal', 'aux_asignapersonal', '1', 'ADMINISTRADOR'),
(388, 'Logistica', '02.03.05', 'Imprimir Códigos de Barra', 'do form forms\\prc_printcodbar', 'prc_printcodbar', '0', 'ADMINISTRADOR'),
(389, 'Planillas', '08.02.04', 'Permisos', 'do form forms\\mov_dscpermiso', 'mov_dscpermiso', '1', ''),
(390, 'Planillas', '08.02.07', 'Autorizar Hora Extra', 'do form forms\\mov_authextra', 'mov_authextra', '1', 'ADMINISTRADOR'),
(391, 'Planillas', '08.04.03.01', 'Asistencia - Marcación reloj', 'do form forms\\rpt_asistencia', 'rpt_asistencia', '1', 'ADMINISTRADOR'),
(392, 'Planillas', '08.04.03.02', 'Asistencia - Detalle horas', 'do form forms\\rpt_asistenciadetalle', 'rpt_asistenciadetalle', '1', 'ADMINISTRADOR'),
(399, 'Compras', '04.04.09', 'Pareto Compras', 'do form forms\\rpt_paretocompras', 'rpt_paretocompras', '0', ''),
(403, 'Planillas', '08.04.03.03', 'Asistencia por Día', 'do form forms\\rpt_asistenciaxdia', 'rpt_asistenciaxdia', '1', 'ADMINISTRADOR'),
(404, 'Planillas', '08.04.03.04', 'Permisos / Descansos', 'do form forms\\rpt_planillapermiso', 'rpt_planillapermiso', '1', 'ADMINISTRADOR'),
(405, 'Planillas', '08.01.07', 'Cuentas por Concepto', 'do form forms\\tab_planilla_contab', 'tab_planilla_contab', '1', 'ADMINISTRADOR'),
(406, 'Contabilidad', '06.02.06', 'Asiento de Cierre', 'do form forms\\mov_notacontable_cierre', 'mov_notacontable_cierre', '1', 'ADMINISTRADOR'),
(409, 'Logistica', '02.02.07', 'Control de Inventario', 'do form forms\\mov_inventario', 'mov_inventario', '0', 'ADMINISTRADOR'),
(410, 'Tributos', '13.03.03', 'PDT - Planilla Electrónica', 'do form forms\\rpt_planillaelectronica', 'rpt_planillaelectronica', '1', 'ADMINISTRADOR'),
(411, 'Planillas', '08.04.06', 'Adelantos / Préstamos', 'do form forms\\rpt_planillaadelanto', 'rpt_planillaadelanto', '1', 'ADMINISTRADOR'),
(413, 'Planillas', '08.04.05', 'Padrón Personal', 'do form forms\\rpt_padronpersonal', 'rpt_padronpersonal', '1', 'ADMINISTRADOR'),
(414, 'Planillas', '08.03.03', 'Abrir / Cerrar Periodos', 'do form forms\\aux_periodos with \'PLA\'', 'aux_periodos', '1', 'ADMINISTRADOR'),
(415, 'Planillas', '08.03.04', 'Actualizar Costo de Tareo', 'do form forms\\prc_actcostotareo', 'prc_actcostotareo', '1', 'ADMINISTRADOR'),
(416, 'Planillas', '08.04.07', 'Reporte de Tareo', 'do form forms\\rpt_tareo', 'rpt_tareo', '1', 'ADMINISTRADOR'),
(417, 'Planillas', '08.01.01.15', 'Tipo Fin Periodo', 'do frmtabla in prgs\\procedimientos with \'tipofinperiodo\', \'Tipo Fin Periodo\'', 'tab_tipofinperiodo', '1', 'ADMINISTRADOR'),
(419, 'Planillas', '08.03.05', 'Contabilizar Planillas', 'do form forms\\prc_contabpla', 'prc_contabpla', '1', 'ADMINISTRADOR'),
(420, 'Planillas', '08.04.03.05', 'Autorización H.Extras', 'do form forms\\rpt_authextra', 'rpt_authextra', '1', 'ADMINISTRADOR'),
(422, 'Logistica', '02.04.05', 'Detalle Movimientos Almacén', 'do form forms\\rpt_movalmacenxgrupo', 'rpt_movalmacenxgrupo', '0', 'ADMINISTRADOR'),
(424, 'Tributos', '13.04.06', 'Inventario Permanente', 'do form forms\\rpt_libroinvpermanente', 'rpt_libroinvpermanente', '1', 'ADMINISTRADOR'),
(426, 'Maestros', '01.02.11', 'Categorías Cta.Cte.', 'do form forms\\tab_categctacte', 'tab_categctacte', '0', 'ADMINISTRADOR'),
(428, 'Logistica', '02.02.08', 'Guias de Remisión', 'do form forms\\mov_guiaremision', 'mov_guiaremision', '0', 'ADMINISTRADOR'),
(429, 'Planillas', '08.02.09', 'Planillas', '', '', '1', 'ADMINISTRADOR'),
(432, 'Logistica', '02.04.09', 'Control Inventario', 'do form forms\\rpt_controlinventario', 'rpt_controlinventario', '0', 'ADMINISTRADOR'),
(434, 'Planillas', '08.04.03', 'Asistencia', '', '', '1', 'ADMINISTRADOR'),
(435, 'Planillas', '08.04.08', 'Control Provisiones', 'do form forms\\rpt_planillaprovision', 'rpt_planillaprovision', '1', 'ADMINISTRADOR'),
(436, 'Planillas', '08.04.09', 'Conceptos Promediables', 'do form forms\\rpt_planillapromediable', 'rpt_planillapromediable', '1', 'ADMINISTRADOR'),
(439, 'Tributos', '13.03.04', 'AFP NET', 'do form forms\\rpt_planillaafpnet', 'rpt_planillaafpnet', '1', 'ADMINISTRADOR'),
(443, 'Planillas', '08.02.09.05', 'Planilla Liquidacion', 'do form forms\\mov_planillaliq', 'mov_planillaliq', '1', 'ADMINISTRADOR'),
(444, 'Planillas', '08.02.11', 'Control Vacaciones', 'do form forms\\mov_controlvac', 'mov_controlvac', '1', 'ADMINISTRADOR'),
(448, 'Ventas', '03.02.07', 'Facturar Pedido', 'do form forms\\mov_pedidoptovtaft', 'mov_pedidoptovtaft', '0', 'ADMINISTRADOR'),
(450, 'Contabilidad', '06.04.09', 'Validación Distribución Gastos Vinculados', 'do form forms\\rpt_validadua', 'rpt_validadua', '0', 'ADMINISTRADOR'),
(452, 'Tesoreria', '05.04.05', 'Movimiento por Operación', 'do form forms\\rpt_movimientoxoperacion', 'rpt_movimientoxoperacion', '0', 'ADMINISTRADOR'),
(453, 'Logistica', '02.03.03', 'Ajuste Inventarios', 'do form forms\\mov_ingresoalmacen_ajuste', 'mov_ingresoalmacen_ajuste', '1', 'ADMINISTRADOR'),
(454, 'Tesoreria', '05.02.09', 'Registo de Banco', 'do form forms\\mov_movbanco', 'mov_movbanco', '0', 'ADMINISTRADOR'),
(455, 'Contabilidad', '06.04.10', 'Control Aperturas por Cobrar/Pagar', 'do form forms\\rpt_aperturactacte', 'rpt_aperturactacte', '0', 'ADMINISTRADOR'),
(456, 'Tesoreria', '05.04.06', 'Registros de Banco', 'do form forms\\rpt_registrobanco', 'rpt_registrobanco', '0', 'ADMINISTRADOR'),
(457, 'Tesoreria', '05.04.07', 'Registro Banco - Resumen', 'do form forms\\rpt_registrobanco_resumen', 'rpt_registrobanco_resumen', '0', ''),
(458, 'Tesoreria', '05.01.05', 'Aplicación de Seguros', 'do form forms\\tab_aplseguro', 'tab_aplseguro', '0', 'ADMINISTRADOR'),
(459, 'Tesoreria', '05.02.11', 'Aplicación de Seguros', 'do form forms\\mov_notacontable_aplseguro', 'mov_notacontable_aplseguro', '0', 'ADMINISTRADOR'),
(460, 'Contabilidad', '06.04.05.09', 'Estado Financiero - C.Costo', 'do form forms\\rpt_eeffccosto', 'rpt_eeffccosto', '0', 'ADMINISTRADOR'),
(461, 'Tesoreria', '05.02.12', 'Otras Provisiones', 'do form forms\\mov_arrfinanciero', 'mov_arrfinanciero', '1', 'ADMINISTRADOR'),
(462, 'Logistica', '02.04.11', 'Listado Guías de Remisión', 'do form forms\\rpt_guiaremision', 'rpt_guiaremision', '0', 'ADMINISTRADOR'),
(463, 'Logistica', '02.04.12', 'Rotación de Inventarios', 'do form forms\\rpt_rotacioninventario', 'rpt_rotacioninventario', '0', 'ADMINISTRADOR'),
(464, 'Utilitario', '99.01.05', 'Reporte de Privilegios de Usuario', 'do form forms\\rpt_usuario', 'rpt_usuario', '1', 'ADMINISTRADOR'),
(467, 'Tributos', '13.03.06', 'PDT 626 - Agentes de Retención', 'do form forms\\rpt_pdt626', 'rpt_pdt626', '1', 'ADMINISTRADOR'),
(472, 'Tributos', '13.04.04', 'Libro Retenciones IGV', 'do form forms\\rpt_libroretencionigv', 'rpt_libroretencionigv', '0', 'ADMINISTRADOR'),
(473, 'Tributos', '13.04.07', 'Libro Consignaciones', 'do form forms\\rpt_libroconsignacion', 'rpt_libroconsignacion', '0', 'ADMINISTRADOR'),
(474, 'Planillas', '08.04.10', 'Reporte de Planilla por Concepto', 'do form forms\\rpt_planillaconcepto', 'rpt_planillaconcepto', '1', 'ADMINISTRADOR'),
(475, 'Planillas', '08.04.11', 'Planilla CTS', 'do form forms\\rpt_planillacts', 'rpt_planillacts', '1', 'ADMINISTRADOR'),
(476, 'Planillas', '08.04.12', 'Retenciones por Aportes y Renta', 'do form forms\\rpt_planillaretencion', 'rpt_planillaretencion', '1', 'ADMINISTRADOR'),
(477, 'Contabilidad', '06.02.07', 'Provisión Cobranza Dudosa', 'do form forms\\mov_notacontable_cdudosa', 'mov_notacontable_cdudosa', '0', 'ADMINISTRADOR'),
(478, 'Tributos', '13.03.00', 'Abrir/Cerrar Periodo', 'do form forms\\aux_periodos with \'TRI\'', 'prc_periodos', '1', 'ADMINISTRADOR'),
(480, 'Tributos', '13.01.04', 'No Habidos', 'do form forms\\tab_nohabido', 'tab_nohabido', '1', 'ADMINISTRADOR'),
(481, 'Tributos', '13.01.05', 'Contribuyentes Renuncia Exoneración IGV', 'do form forms\\tab_renexonigv', 'tab_renexonigv', '1', 'ADMINISTRADOR'),
(482, 'Compras', '04.04.10', 'Lista Documentoos Registrados', 'do form forms\\rpt_provisiones', 'rpt_provisiones', '0', 'ADMINISTRADOR'),
(483, 'Logistica', '02.04.13', 'Provision Ingreso por Compra', 'do form forms\\rpt_negativos', 'rpt_negativos', '1', 'ADMINISTRADOR'),
(484, 'Planillas', '08.01.08', 'Asignar Conceptos a Planilla', 'do form forms\\aux_asignaconcepto', 'aux_asignaconcepto', '1', 'ADMINISTRADOR'),
(485, 'Ventas', '03.03.04', 'Envio CPE', 'do form forms\\rpt_validacpe', 'rpt_validacpe', '1', 'ADMINISTRADOR'),
(486, 'Ventas', '03.03.02', 'Comunicación de Baja', 'do form forms\\mov_doccomunicacion_baja', 'mov_doccomunicacion_baja', '1', 'ADMINISTRADOR'),
(487, 'Ventas', '03.03.03', 'Resumen Boleta', 'do form forms\\mov_doccomunicacion_resumen', 'mov_doccomunicacion_resumen', '1', 'ADMINISTRADOR'),
(488, 'Ventas', '03.03.05', 'Registro de Contingencia', 'do form forms\\mov_doccomunicacion_contingencia', 'mov_doccomunicacion_contingencia', '1', 'ADMINISTRADOR'),
(490, 'Ventas', '03.03.06', 'Consulta CPE', 'do form forms\\aux_consultacpe', 'aux_consultacpe', '1', 'ADMINISTRADOR'),
(491, 'Tesoreria', '05.01.03', 'Tipo Gasto', 'do frmtabla in prgs\\procedimientos with \'tipogasto\', \'Tipos de Gasto\'', 'tab_tipogasto', '0', 'ADMINISTRADOR'),
(492, 'Planillas', '08.04.03.03', 'Control Suspensiones Laborales', 'do form forms\\rpt_planillapermiso', 'rpt_planillapermiso', '1', 'ADMINISTRADOR'),
(493, 'Planillas', '08.04.16', 'Remuneración del Trabajador', 'do form forms\\rpt_remuneracion', 'rpt_remuneracion', '1', 'ADMINISTRADOR'),
(494, 'Tesoreria', '05.03.02', 'Control Cheques', 'do form forms\\aux_cheque', 'aux_cheque', '1', 'ADMINISTRADOR'),
(495, 'Tesoreria', '05.03.03', 'Macro BBVA', 'do form forms\\rpt_macrobbva', 'prt_macrobbva', '1', 'ADMINISTRADOR'),
(496, 'Ventas', '03.03.07', 'Validación Comunic.Baja y Resúmen Boletas', 'do form forms\\rpt_validabaja', 'rpt_validabaja', '1', 'ADMINISTRADOR'),
(497, 'Utiliario', '99.01.06', 'Buscar', 'do form forms\\busquedadoc', 'busquedadoc', '1', 'ADMINISTRADOR'),
(498, 'Ventas', '03.03.08', 'Importar', 'do form forms\\prc_importar', 'prc_importar', '0', 'ADMINISTRADOR'),
(499, 'Planillas', '08.04.17', 'Análisis Calculo Renta Quinta', 'do form forms\\rpt_planillarentaquinta', 'rpt_planillarentaquinta', '1', 'ADMINISTRADOR'),
(500, 'Planillas', '08.02.13', 'Provisión de Utilidades', 'do form forms\\mov_docutilidad', 'mov_docutilidad', '1', 'ADMINISTRADOR'),
(501, 'Planillas', '08.02.09.06', 'Planilla de Utilidades', 'do form forms\\mov_planillauti', 'mov_planillauti', '1', 'ADMINISTRADOR'),
(502, 'Maestros', '01.01.08', 'Grupos de Productos', 'do form forms\\tab_grupoproducto', 'tab_grupoproducto', '0', 'ADMINISTRADOR'),
(505, 'Contabilidad', '06.04.11', 'Saldos por Cuenta', 'do form forms\\rpt_saldoxcuenta', 'rpt_saldoxcuenta', '1', 'ADMINISTRADOR'),
(506, 'Contabilidad', '06.04.12', 'Reporte de Gastos e Ingresos', 'do form forms\\rpt_gastos', 'rpt_gastos', '0', 'ADMINISTRADOR'),
(507, 'Compras', '04.04.11', 'Detalle de Compras', 'do form forms\\rpt_detalle_compras', 'rpt_detallecompras', '1', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_version`
--

CREATE TABLE `menu_version` (
  `id` int(11) NOT NULL,
  `mve_codigo` varchar(20) NOT NULL,
  `mve_modulo` varchar(200) NOT NULL,
  `mve_descripcion` varchar(200) NOT NULL,
  `mve_estado` smallint(1) NOT NULL,
  `mve_usuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id` int(11) NOT NULL,
  `pag_operacion` varchar(20) NOT NULL,
  `pag_nombre` varchar(200) NOT NULL,
  `pag_montome` decimal(15,2) NOT NULL,
  `pag_montomn` decimal(15,2) NOT NULL,
  `pag_fecha` date NOT NULL,
  `pag_nro_tarjeta` varchar(150) NOT NULL,
  `pag_mesexp` date NOT NULL,
  `pag_anioexp` date NOT NULL,
  `pag_ccv` char(4) NOT NULL,
  `pag_usuario` varchar(20) NOT NULL,
  `fpa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo`
--

CREATE TABLE `periodo` (
  `id` int(11) NOT NULL,
  `per_codigo` varchar(50) DEFAULT NULL,
  `per_descripcion` varchar(200) DEFAULT NULL,
  `per_usuario` varchar(20) DEFAULT NULL,
  `per_estado` smallint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` int(11) NOT NULL,
  `per_nombre` varchar(50) DEFAULT NULL,
  `per_estado` smallint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `per_nombre`, `per_estado`) VALUES
(1, 'Control Superadmin', 1),
(2, 'Gestionar Empresas', 1),
(3, 'Gestionar Usuarios', 1),
(4, 'Gestionar Roles', 1),
(5, 'Gestionar Permisos', 1),
(6, 'Gestionar Rol Permiso', 1),
(7, 'Gestionar Suscripcion', 1),
(8, 'Gestionar Partners', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id` int(11) NOT NULL,
  `ver_id` int(11) NOT NULL,
  `pla_nro_mes` int(11) DEFAULT NULL,
  `pla_preciomn` decimal(10,2) DEFAULT NULL,
  `pla_estado` smallint(1) DEFAULT NULL,
  `pla_usuario` varchar(20) DEFAULT NULL,
  `pla_preciome` decimal(10,2) DEFAULT NULL,
  `pla_descripcion` varchar(150) DEFAULT NULL,
  `pla_es_prueba` smallint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`id`, `ver_id`, `pla_nro_mes`, `pla_preciomn`, `pla_estado`, `pla_usuario`, `pla_preciome`, `pla_descripcion`, `pla_es_prueba`) VALUES
(1, 1, 3, '0.00', 1, '', '0.00', 'PRUEBA', 1),
(2, 1, 3, '0.00', 1, '', '100.00', 'PLAN TRIMESTRAL - SPRINTER 1.1', 0),
(3, 2, 3, '0.00', 1, '', '160.00', 'PLAN TRIMESTRAL - SPRINTER 1.2', 0),
(4, 3, 3, '0.00', 1, '', '225.00', 'PLAN TRIMESTRAL - SPRINTER 1.3', 0),
(5, 1, 12, '0.00', 1, '', '354.00', 'PLAN ANUAL - SPRINTER 1.1', 0),
(6, 2, 12, '0.00', 1, '', '590.00', 'PLAN ANUAL - SPRINTER 1.2', 0),
(7, 3, 12, '0.00', 1, '', '826.00', 'PLAN ANUAL - SPRINTER 1.3', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegio`
--

CREATE TABLE `privilegio` (
  `id` int(11) NOT NULL,
  `crea` smallint(1) DEFAULT NULL,
  `edita` smallint(1) DEFAULT NULL,
  `anula` smallint(1) DEFAULT NULL,
  `borra` smallint(1) DEFAULT NULL,
  `consulta` smallint(1) DEFAULT NULL,
  `imprime` smallint(1) DEFAULT NULL,
  `aprueba` smallint(1) DEFAULT NULL,
  `precio` smallint(1) DEFAULT NULL,
  `usuario` varchar(150) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `usuario_empresa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegio_usuario`
--

CREATE TABLE `privilegio_usuario` (
  `id` int(11) NOT NULL,
  `pus_fecha_inicio` date NOT NULL,
  `pus_fecha_fin` date NOT NULL,
  `pus_estado` smallint(1) DEFAULT NULL,
  `usu_id` int(11) DEFAULT NULL,
  `emp_pertenece_id` int(11) DEFAULT NULL,
  `emp_acargo_id` int(11) NOT NULL,
  `pag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `punto_venta_aux`
--

CREATE TABLE `punto_venta_aux` (
  `id` int(8) NOT NULL,
  `id_usuario` int(8) NOT NULL,
  `id_empresa` int(8) NOT NULL,
  `id_punto_venta` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `punto_venta_aux`
--

INSERT INTO `punto_venta_aux` (`id`, `id_usuario`, `id_empresa`, `id_punto_venta`) VALUES
(1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `rol_nombre` varchar(50) DEFAULT NULL,
  `rol_estado` smallint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `rol_nombre`, `rol_estado`) VALUES
(1, 'ADMINISTRADOR', 0),
(2, 'CONTADOR', 0),
(3, 'SUPERADMIN', 0),
(4, 'VENDEDOR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id` int(11) NOT NULL,
  `rolid` int(11) NOT NULL,
  `permisoid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripcion`
--

CREATE TABLE `suscripcion` (
  `id` int(11) NOT NULL,
  `sus_codigo` varchar(20) NOT NULL,
  `sus_fechainicio` date NOT NULL,
  `sus_fechafin` date NOT NULL,
  `sus_estado` int(11) NOT NULL,
  `sus_usuario` varchar(20) NOT NULL,
  `sus_comprobante` varchar(250) NOT NULL,
  `sus_pedido` varchar(20) DEFAULT NULL,
  `pla_id` int(11) DEFAULT NULL,
  `fop_id` int(11) DEFAULT NULL,
  `est_id` int(11) DEFAULT NULL,
  `ver_id` int(11) DEFAULT NULL,
  `cup_id` int(11) DEFAULT NULL,
  `empresa_id` int(11) NOT NULL,
  `puntoventa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `suscripcion`
--

INSERT INTO `suscripcion` (`id`, `sus_codigo`, `sus_fechainicio`, `sus_fechafin`, `sus_estado`, `sus_usuario`, `sus_comprobante`, `sus_pedido`, `pla_id`, `fop_id`, `est_id`, `ver_id`, `cup_id`, `empresa_id`, `puntoventa_id`) VALUES
(1, '00001', '2019-02-26', '2019-03-26', 0, '1', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empresa`
--

CREATE TABLE `tipo_empresa` (
  `id` int(11) NOT NULL,
  `tem_codigo` varchar(20) NOT NULL,
  `tem_descripcion` varchar(200) NOT NULL,
  `tem_estado` smallint(1) NOT NULL,
  `tem_usuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_empresa`
--

INSERT INTO `tipo_empresa` (`id`, `tem_codigo`, `tem_descripcion`, `tem_estado`, `tem_usuario`) VALUES
(1, '01', 'EMPRESA', 1, 'msanchez'),
(2, '02', 'ESTUDIO', 1, 'msanchez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usu_usuario` varchar(200) NOT NULL,
  `usu_clave` varchar(200) NOT NULL,
  `usu_estado` smallint(1) NOT NULL,
  `usu_nombres` varchar(80) DEFAULT NULL,
  `usu_apellidos` varchar(80) DEFAULT NULL,
  `remember_token` varchar(250) DEFAULT NULL,
  `usu_usuario_registra` varchar(45) DEFAULT NULL,
  `usu_correo` varchar(75) DEFAULT NULL,
  `usu_telefono` varchar(45) DEFAULT NULL,
  `usu_estipo` int(11) DEFAULT '0',
  `usu_fecha_registro` date DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usu_usuario`, `usu_clave`, `usu_estado`, `usu_nombres`, `usu_apellidos`, `remember_token`, `usu_usuario_registra`, `usu_correo`, `usu_telefono`, `usu_estipo`, `usu_fecha_registro`, `rol_id`) VALUES
(1, 'DEMO@GMAIL.COM', '$2y$10$qTJhT8jbdXXuzwXPlgiCTOEeatwPs7n71f9uefP79H6KsVyXPV5ia', 1, 'DEMO', 'DEMO', 'yb051FN4XeWCUcWa2QBoWTzbn3HDUGN2D8Sqte1BQYOlYDDfN4KQcyX9Aasz', '', 'demo@gmail.com', '123456789', 0, '2019-02-26', 1),
(2, 'demov@gmail.com', '$2y$10$yBGHUvrAmARADPIFNohlGOT5/El.VNLpP1uHmZ9VVqI44oO4lDlSO', 1, 'DEMO', 'DEMO', 'q63OlTtPsTPUNhWqJZSxCsuLKJ43YeL4K0dctT65zurY0MMaODyuUN1Oq7eF', NULL, 'demov@gmail.com', '123456789', 0, '2019-02-26', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_empresa`
--

CREATE TABLE `usuario_empresa` (
  `id` int(11) NOT NULL,
  `uem_estado` smallint(1) NOT NULL,
  `uem_usuario` varchar(20) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `emp_acargo_id` int(11) NOT NULL,
  `emp_pertenece_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario_empresa`
--

INSERT INTO `usuario_empresa` (`id`, `uem_estado`, `uem_usuario`, `usu_id`, `emp_acargo_id`, `emp_pertenece_id`) VALUES
(1, 1, 'DEMO@GMAIL.COM', 1, 1, 1),
(2, 1, 'demov@gmail.com', 2, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `version`
--

CREATE TABLE `version` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `estado` smallint(1) NOT NULL,
  `usuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `version`
--

INSERT INTO `version` (`id`, `codigo`, `descripcion`, `estado`, `usuario`) VALUES
(1, '01', 'SPRINTER 1.1', 1, ''),
(2, '02', 'SPRINTER 1.2', 1, 'msanchez'),
(3, '03', 'SPRINTER 1.3 ', 1, 'MSANCHEZ'),
(4, '04', 'SPRINTER 1.4', 1, 'MSANCHEZ');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivo`
--
ALTER TABLE `archivo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKarchivo641809` (`emp_id`);

--
-- Indices de la tabla `certificado`
--
ALTER TABLE `certificado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_sunat` (`usuario_sunat`),
  ADD UNIQUE KEY `password_sunat` (`password_sunat`),
  ADD KEY `fk_certificado_1_idx` (`empresa_id`);

--
-- Indices de la tabla `conexion`
--
ALTER TABLE `conexion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cupon`
--
ALTER TABLE `cupon`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_cupon`
--
ALTER TABLE `detalle_cupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKdetalle_cu393311` (`cup_id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKempresa940083` (`est_id`),
  ADD KEY `FKempresa764201` (`tem_id`);

--
-- Indices de la tabla `estudio`
--
ALTER TABLE `estudio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu_version`
--
ALTER TABLE `menu_version`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `periodo`
--
ALTER TABLE `periodo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKperiodo_de93775` (`ver_id`);

--
-- Indices de la tabla `privilegio`
--
ALTER TABLE `privilegio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `privilegio_usuario`
--
ALTER TABLE `privilegio_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `punto_venta_aux`
--
ALTER TABLE `punto_venta_aux`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKrol_permis272334` (`permisoid`),
  ADD KEY `FKrol_permis670833` (`rolid`);

--
-- Indices de la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKsuscripcio413906` (`pla_id`),
  ADD KEY `FKsuscripcio592953` (`fop_id`),
  ADD KEY `_idx` (`cup_id`);

--
-- Indices de la tabla `tipo_empresa`
--
ALTER TABLE `tipo_empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKusuario_em767589` (`usu_id`),
  ADD KEY `FKusuario_em46131` (`emp_acargo_id`);

--
-- Indices de la tabla `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivo`
--
ALTER TABLE `archivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `certificado`
--
ALTER TABLE `certificado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conexion`
--
ALTER TABLE `conexion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cupon`
--
ALTER TABLE `cupon`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_cupon`
--
ALTER TABLE `detalle_cupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estudio`
--
ALTER TABLE `estudio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=508;

--
-- AUTO_INCREMENT de la tabla `menu_version`
--
ALTER TABLE `menu_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodo`
--
ALTER TABLE `periodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `privilegio`
--
ALTER TABLE `privilegio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `privilegio_usuario`
--
ALTER TABLE `privilegio_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `punto_venta_aux`
--
ALTER TABLE `punto_venta_aux`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_empresa`
--
ALTER TABLE `tipo_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `version`
--
ALTER TABLE `version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivo`
--
ALTER TABLE `archivo`
  ADD CONSTRAINT `FKarchivo641809` FOREIGN KEY (`emp_id`) REFERENCES `empresa` (`id`);

--
-- Filtros para la tabla `certificado`
--
ALTER TABLE `certificado`
  ADD CONSTRAINT `fk_certificado_1_idx` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`);

--
-- Filtros para la tabla `detalle_cupon`
--
ALTER TABLE `detalle_cupon`
  ADD CONSTRAINT `FKdetalle_cu393311` FOREIGN KEY (`cup_id`) REFERENCES `cupon` (`id`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `FKempresa764201` FOREIGN KEY (`tem_id`) REFERENCES `tipo_empresa` (`id`),
  ADD CONSTRAINT `FKempresa940083` FOREIGN KEY (`est_id`) REFERENCES `estudio` (`id`);

--
-- Filtros para la tabla `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `FKperiodo_de93775` FOREIGN KEY (`ver_id`) REFERENCES `version` (`id`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `FKrol_permis272334` FOREIGN KEY (`permisoid`) REFERENCES `permiso` (`id`),
  ADD CONSTRAINT `FKrol_permis670833` FOREIGN KEY (`rolid`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  ADD CONSTRAINT `FKsuscripcio` FOREIGN KEY (`cup_id`) REFERENCES `cupon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FKsuscripcio413906` FOREIGN KEY (`pla_id`) REFERENCES `plan` (`id`),
  ADD CONSTRAINT `FKsuscripcio592953` FOREIGN KEY (`fop_id`) REFERENCES `forma_pago` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `rol_id` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  ADD CONSTRAINT `FKusuario_em46131` FOREIGN KEY (`emp_acargo_id`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `FKusuario_em767589` FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
