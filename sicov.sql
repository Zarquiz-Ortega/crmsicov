-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-03-2024 a las 20:59:55
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sicov`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancelacion`
--

CREATE TABLE `cancelacion` (
  `id_cancelacion` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cancelacion`
--

INSERT INTO `cancelacion` (`id_cancelacion`, `id_user`, `id_mensaje`) VALUES
(1, 1, 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `nom_empresa` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `det_cita` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `classname` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `nom_empresa`, `id_user`, `fecha_hora`, `det_cita`, `classname`) VALUES
(1, 'mi empresa S.z de CV', 1, '2023-11-30 21:00:00', 'Llamada', 'importante'),
(2, 'empresa 1', 1, '2024-01-10 16:00:00', 'Visita a la empresa', 'urgente'),
(3, 'empresa 2', 1, '2024-01-16 19:00:00', 'Conferencia', 'pendiente'),
(4, 'empresa 1', 1, '2024-01-16 16:00:00', 'Conferencia', 'importante'),
(5, 'Selecione una opcion', 1, '2024-01-08 17:00:00', 'Llamada', 'pendiente'),
(6, 'empresa 1', 1, '2024-01-12 16:00:00', 'Reunion', 'urgente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `nombres` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nom_empresa` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`id`, `nombres`, `apellidos`, `telefono`, `nom_empresa`, `email`) VALUES
(1, 'Óscar ', 'alemania', '5600000000', 'mi empresa S.z de CV', 'miempresa@email.com'),
(2, 'empresa', 'empresa', '5600000000', 'empresa 1', 'empresa@email.com'),
(3, 'empresa', 'empresa', '5600000000', 'empresa 2', 'empresa@email.com'),
(4, 'empresa', 'empresa', '5600000000', 'empresa 3', 'empresa@email.com'),
(5, 'pepe', 'mollejas', '5600000000', 'Empresa 4', 'empresa@email.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `estado` varchar(20) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `estado`) VALUES
(1, 'Nuevo contacto'),
(2, 'En seguimiento'),
(3, 'Venta realizada'),
(4, 'Venta Cancelada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `mensaje` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `modulos` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `asunto` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `id_customers` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `enseguimiento` int(11) NOT NULL DEFAULT 0,
  `medio_contacto` int(11) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `mensaje`, `modulos`, `asunto`, `fecha`, `id_customers`, `id_estado`, `enseguimiento`, `medio_contacto`) VALUES
(34, 'le intero el sistema por la administracion de probedores ', 'Login,Inventario,Registro de clientes/proveedores', 'Cotización', '2023-11-14', 1, 3, 1, 1),
(35, 'empresa 1', 'Login,Registro de clientes/proveedores', 'Cotización', '2023-12-28', 2, 3, 1, 1),
(36, 'empresa 2', 'Dashboard', 'Cotización', '2023-12-28', 3, 1, 1, 2),
(37, 'empresa 3', 'Agenda', 'Cotización', '2023-12-28', 4, 4, 1, 2),
(38, 'hola', 'Inventario,Login,Registro de clientes/proveedores', 'Cotización', '2024-01-15', 5, 2, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `desarollo` tinyint(4) NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `nombre`, `descripcion`, `desarollo`, `estado`) VALUES
(1, 'Login', 'Es cómo los usuarios acceden al SICOV con su usuario y contraseña para usar sus funciones y datos.', 0, 1),
(2, 'Inventario', 'Mantiene un seguimiento preciso de los activos disponibles para la cuestión eficiente y decisiones informadas.', 0, 1),
(3, 'Registro de clientes/proveedores', 'Guarda datos importantes de las empresas con las que se hace negocios, facilitando su manejo y seguimiento.', 0, 1),
(4, 'Agenda', 'Organiza eventos y tareas eficientemente. Teniendo un calendario para verlo gráficamente.', 0, 1),
(5, 'Buscar cuentas/seguimiento de venta', 'Rastrea y acceder rápidamente a la información del cliente y ventas, facilitando la gestión y el análisis comercial.', 0, 1),
(7, 'Dashboard', 'Muestra de manera visual y concisa datos importantes en tiempo real ayudando a tomar decisiones y monitorear el rendimiento.', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `id_seguimiento` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL,
  `com_seg_1` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `com_seg_2` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `com_seg_3` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `com_seg_4` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `seguimiento`
--

INSERT INTO `seguimiento` (`id_seguimiento`, `id_user`, `id_mensaje`, `com_seg_1`, `com_seg_2`, `com_seg_3`, `com_seg_4`) VALUES
(1, 1, 34, 'primer contacto\r\n', 'Venta exitosa ', NULL, NULL),
(2, 4, 35, 'primer paso contacto con el cliente ', 'se vendió ', NULL, NULL),
(3, 1, 36, NULL, NULL, NULL, NULL),
(4, 1, 37, NULL, NULL, NULL, 'proceso de cancelación '),
(5, 1, 38, 'hola', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `nom_userFoto` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `meta` int(11) NOT NULL DEFAULT 5,
  `rol` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `usuario`, `password`, `nom_userFoto`, `meta`, `rol`) VALUES
(1, 'Luis Steven zarquiz', 'Ortega Chavez', 'zarquiz_admin', '$2y$10$Y0Jra3uS/SfWw7ACxGa20ufm/gWnu/3slaPT/DfqvQtRG5m90WZLy', 'Luis Steven zarquiz_Ortega Chavez.jpg', 5, 2),
(4, 'mario francisco', 'Hernandez Garcia', 'mahos', '$2y$10$xZTOy6QJIgcEHz0dXDvLoO4EEj.0jIyzBzoEZ.ki7r8Db2dPpMi8G', 'mario francisco_Hernandez Garcia.jpg', 5, 1),
(5, 'Grecia Jovanna', 'Fabela Perez', 'JovannaFabela', '$2y$10$zElxHVCpi.bmD.OuTvyttuGGuyJXGh9uYKVySW6RqMD15GCnDe.jq', 'Grecia Jovanna_Fabela Perez.jpg', 5, 1),
(6, 'Airam Fernanda', 'Fabela Perez', 'AiramFabela', '$2y$10$jsIT5uKXEXv0p/iSohkLJ.MELkgBEA3C2Yaep4W2/gY1DxAoxHG3S', 'Airam Fernanda_Fabela Perez.jpg', 5, 1),
(7, 'Jaime', 'Santillan Calva', 'JaimeSantillan', '$2y$10$Xh4umDta97.jVtfVQ5YG2OgXPIYajARFjRrbtvFHjJcVTE.zy5WXG', 'Jaime_Santillan Calva.jpg', 5, 1),
(8, 'Luis Antonio', 'Valay Gonzalez', 'LuisValay', '$2y$10$5jjpCcYzYHcNjjCfXnPWu.IxHWBxYFZYzaMindNSMoib8ZH89muD.', 'Luis Antonio_Valay Gonzalez.jpg', 5, 1),
(9, 'Adrian Javier', 'Torres Marcial', 'JavierTorres', '$2y$10$LPwePw4CjBUjUBXPkROVROs5cdaurfAp20UauH9OcchmCQ5WRvCyC', 'Adrian Javier_Torres Marcial.jpg', 5, 2),
(10, 'Jose Luis', 'Lopez Guerrero', 'Draedon', '$2y$10$AUtxN8xcDTlAjLIvDbVuM.lk1G99y6aqUKdO6roA9oBiz2FDNBGtK', 'Jose Luis_Lopez Guerrero.jpg', 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `descuento` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `monto`, `descuento`, `fecha`, `id_customer`, `id_user`, `id_mensaje`) VALUES
(1, 100000, 3, '2023-12-20', 1, 1, 34),
(2, 100000, 1, '2024-01-16', 2, 4, 35);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancelacion`
--
ALTER TABLE `cancelacion`
  ADD PRIMARY KEY (`id_cancelacion`),
  ADD KEY `can_user` (`id_user`),
  ADD KEY `can_mensaje` (`id_mensaje`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custumers_id` (`id_customers`),
  ADD KEY `estado_mensaje` (`id_estado`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `usuario_seguimiento` (`id_user`),
  ADD KEY `mensague_seguimiento` (`id_mensaje`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_customers` (`id_customer`),
  ADD KEY `venta_user` (`id_user`),
  ADD KEY `venta_mensaje` (`id_mensaje`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancelacion`
--
ALTER TABLE `cancelacion`
  MODIFY `id_cancelacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancelacion`
--
ALTER TABLE `cancelacion`
  ADD CONSTRAINT `can_mensaje` FOREIGN KEY (`id_mensaje`) REFERENCES `mensajes` (`id`),
  ADD CONSTRAINT `can_user` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `custumers_id` FOREIGN KEY (`id_customers`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `estado_mensaje` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`);

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `mensague_seguimiento` FOREIGN KEY (`id_mensaje`) REFERENCES `mensajes` (`id`),
  ADD CONSTRAINT `usuario_seguimiento` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_customers` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `venta_mensaje` FOREIGN KEY (`id_mensaje`) REFERENCES `mensajes` (`id`),
  ADD CONSTRAINT `venta_user` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
