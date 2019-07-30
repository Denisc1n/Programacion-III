-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-07-2019 a las 03:29:33
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id10305948_dbcomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimoLogin` datetime DEFAULT NULL,
  `perfil` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `operaciones` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `usuario`, `clave`, `fechaRegistro`, `ultimoLogin`, `perfil`, `estado`, `operaciones`) VALUES
(1, 'Denis', 'denis', '12345', '2019-07-25 15:42:46', '2019-07-29 23:19:33', 'socio', 'H', 20),
(14, 'Leonel', 'leonelpedemonte', '123456789', '2019-07-27 03:33:46', '2019-07-27 00:44:15', 'mozo', 'H', 5),
(15, 'Marcos', 'marcoscocina', '112233', '2019-07-30 02:20:08', NULL, 'cocinero', 'H', 0),
(16, 'Lucas', 'lucascervecero', '223344', '2019-07-30 02:20:49', NULL, 'cervecero', 'H', 0),
(17, 'Gaston', 'gastonbarra', '667788', '2019-07-30 02:24:54', NULL, 'bartender', 'H', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `puntuacion_mesa` int(11) NOT NULL,
  `codigoMesa` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `puntuacion_restaurante` int(11) NOT NULL,
  `puntuacion_mozo` int(11) NOT NULL,
  `puntuacion_cocinero` int(11) NOT NULL,
  `comentario` text COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `importe` float NOT NULL,
  `codigoMesa` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id`, `importe`, `codigoMesa`, `fecha`) VALUES
(6, 80, 'mesa03', '2019-07-27'),
(7, 120, 'mesa02', '2019-07-27'),
(8, 240, 'mesa01', '2019-07-27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `foto` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`codigo`, `estado`, `foto`) VALUES
('mesa01', 'Cerrada', NULL),
('mesa02', 'Cerrada', NULL),
('mesa03', 'Cerrada', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora_pedido` time NOT NULL,
  `hora_entrega_estimada` time DEFAULT NULL,
  `hora_entrega` time DEFAULT NULL,
  `id_mesa` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pedido` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `importe` float NOT NULL,
  `mozo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre_cliente` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `estadoPedido` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sector` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `encargado_pedido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`codigo`, `fecha`, `hora_pedido`, `hora_entrega_estimada`, `hora_entrega`, `id_mesa`, `pedido`, `cantidad`, `importe`, `mozo`, `nombre_cliente`, `estadoPedido`, `sector`, `encargado_pedido`) VALUES
('cr7ve', '2019-07-27', '01:12:00', '01:29:00', '01:17:00', 'mesa02', 'Milanesa', 1, 120, 'Leonel', 'Raul Alfonsin', 'Servido', 'cocina', 14),
('npk5h', '2019-07-27', '01:23:00', '01:38:00', '01:24:00', 'mesa01', 'Choripan', 1, 240, 'Leonel', 'El Fino', 'Servido', 'cocina', 14),
('p54jh', '2019-07-27', '00:49:00', '00:51:00', '00:53:00', 'mesa03', 'Empanadas', 2, 80, 'Leonel', 'Gonzalo Britez', 'Servido', 'cocina', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `clave` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`) VALUES
(1, 'denis', 'denis@d.com', 'denis123');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
