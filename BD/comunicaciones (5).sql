-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-01-2025 a las 06:15:04
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comunicaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_ingresos`
--

CREATE TABLE `c_ingresos` (
  `id` int(11) NOT NULL,
  `nro_cedula` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cedula` varchar(250) NOT NULL,
  `anio` varchar(4) NOT NULL,
  `fecha_recep` datetime NOT NULL,
  `observaciones` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ipaddress` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `c_ingresos`
--

INSERT INTO `c_ingresos` (`id`, `nro_cedula`, `id_usuario`, `cedula`, `anio`, `fecha_recep`, `observaciones`, `ipaddress`) VALUES
(42, '42024350772201900020', 1, '00020-2019', '2019', '2019-01-05 03:41:00', 'test1', '::1'),
(43, '42024350773201900020', 2, '00020-2019', '2019', '2025-01-05 03:41:00', 'test2', '::1'),
(44, '42024350774201900020', 3, '00020-2019', '2019', '2025-01-05 03:41:00', 'test3', '::1'),
(45, '42024350775201900020', 4, '00020-2019', '2019', '2025-01-05 03:41:00', 'test4', '::1'),
(46, '42024350776201900020', 2, '00020-2019', '2019', '2025-01-05 03:41:00', 'test5', '::1'),
(47, '42024350777201900020', 1, '00020-2019', '2019', '2025-01-04 22:09:00', 'test6', '::1'),
(54, '42024349927202306132', 1, '06132-2023', '2023', '2025-01-05 00:18:00', 'test7', '::1'),
(56, '42024354135202406597', 2, '06597-2024', '2024', '2022-01-05 15:09:00', 'TEST8', '::1'),
(57, '42024352638202302785', 2, '02785-2023', '2023', '2025-01-05 21:14:00', '', '::1'),
(58, '42024353308202406614', 2, '06614-2024', '2024', '2025-01-05 21:34:00', '', '::1'),
(59, '42024351527202201068', 2, '01068-2022', '2022', '2025-01-05 21:34:00', '', '::1'),
(60, '42024352668202401797', 2, '01797-2024', '2024', '2023-01-05 21:35:00', '', '::1'),
(61, '42024353403202307863', 3, '07863-2023', '2023', '2024-01-05 21:35:00', '', '::1'),
(62, '42024352383202310175', 3, '10175-2023', '2023', '2024-11-05 21:35:00', '', '::1'),
(63, '42024352353202102241', 2, '02241-2021', '2021', '2022-01-03 00:00:00', '', '::1'),
(64, '42024352360202102241', 2, '02241-2021', '2021', '2025-01-05 21:35:00', '', '::1'),
(65, '42024353943202002366', 2, '02366-2020', '2020', '2025-01-02 21:51:00', '', '::1'),
(66, '42024354753202006075', 2, '06075-2020', '2020', '2025-01-05 21:51:00', '', '::1'),
(67, '42024356284202103703', 2, '03703-2021', '2021', '2025-01-05 21:51:00', '', '::1'),
(68, '42024357388202311345', 2, '11345-2023', '2023', '2024-12-07 21:51:00', '', '::1'),
(69, '42024351333202301395', 2, '01395-2023', '2023', '2024-12-31 00:00:00', '', '::1'),
(70, '42024361430201915506', 2, '15506-2019', '2019', '2024-12-25 00:00:00', '', '::1'),
(71, '42024362524202107727', 2, '07727-2021', '2021', '2025-01-05 21:51:00', '', '::1'),
(72, '42024361054202106560', 2, '06560-2021', '2021', '2025-01-05 23:13:00', '', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_recepcion`
--

CREATE TABLE `c_recepcion` (
  `id` int(11) NOT NULL,
  `nro_cedula` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cedula` varchar(250) NOT NULL,
  `anio` varchar(250) NOT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `observaciones` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ipaddress` varchar(250) NOT NULL,
  `estado` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `c_recepcion`
--

INSERT INTO `c_recepcion` (`id`, `nro_cedula`, `id_usuario`, `cedula`, `anio`, `fecha_devolucion`, `observaciones`, `ipaddress`, `estado`) VALUES
(41, '42024350772201900020', 2, '00020-2019', '2019', '2025-01-05 03:43:00', 'RECEPCION1', '::1', 'Notificado'),
(42, '42024350774201900020', 2, '00020-2019', '2019', '2025-01-05 03:43:00', 'RECEPCION3', '::1', 'Notificado'),
(43, '42024350776201900020', 3, '00020-2019', '2019', '2025-01-05 03:43:00', 'recepcion5', '::1', 'Motivado'),
(45, '42024349930202306132', 3, '06132-2023', '2023', '2025-01-05 00:17:00', 'TEST ENTER JVX', '::1', 'Motivado'),
(46, '42024349927202306132', 2, '06132-2023', '2023', '2025-01-05 00:17:00', 'TEST FILTER', '::1', 'Notificado'),
(47, '42024354135202406597', 2, '06597-2024', '2024', '2025-01-05 19:25:00', 'test estado', '::1', 'Notificado'),
(48, '12345678798798798798', 2, '98798-7987', '7987', '2025-01-05 19:55:00', 'solo numeros', '::1', 'Motivado'),
(49, '42024352360202102241', 2, '02241-2021', '2021', '2025-01-05 21:35:00', '', '::1', 'Notificado'),
(50, '42024352326202310076', 2, '10076-2023', '2023', '2025-01-05 21:35:00', '', '::1', 'Notificado'),
(51, '42024352364202102241', 2, '02241-2021', '2021', '2025-01-05 21:35:00', '', '::1', 'Motivado'),
(52, '42024352382202310175', 2, '10175-2023', '2023', '2025-01-05 21:35:00', '', '::1', 'Motivado'),
(53, '42024352079201920383', 2, '20383-2019', '2019', '2025-01-05 21:35:00', '', '::1', 'Motivado'),
(54, '42024336335202208094', 2, '08094-2022', '2022', '2024-10-05 21:36:00', '', '::1', 'Motivado'),
(55, '42024362524202107727', 2, '07727-2021', '2021', '2023-07-05 21:56:00', '', '::1', 'Notificado'),
(56, '42024364156202201478', 2, '01478-2022', '2022', '2023-07-05 21:56:00', '', '::1', 'Notificado'),
(57, '42024363045202203920', 2, '03920-2022', '2022', '2024-11-05 21:57:00', '', '::1', 'Notificado'),
(58, '42024361722202309261', 2, '09261-2023', '2023', '2024-12-05 21:57:00', '', '::1', 'Notificado'),
(59, '42024361332202204666', 2, '04666-2022', '2022', '2024-09-05 21:57:00', '', '::1', 'Notificado'),
(60, '42024360765202309598', 2, '09598-2023', '2023', '2025-01-05 21:57:00', '', '::1', 'Motivado'),
(61, '42024358372202306825', 2, '06825-2023', '2023', '2024-01-05 21:57:00', '', '::1', 'Motivado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `m_motivos`
--

CREATE TABLE `m_motivos` (
  `m_id` int(5) NOT NULL,
  `cod_motivo` int(5) NOT NULL,
  `m_descripcion` varchar(250) NOT NULL,
  `m_color` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `m_motivos`
--

INSERT INTO `m_motivos` (`m_id`, `cod_motivo`, `m_descripcion`, `m_color`) VALUES
(1, 1, 'NOTIFICADO', 'VERDE'),
(2, 2, 'MOTIVADA', 'ROJO'),
(3, 3, 'BAJO PUERTA', 'AMARILLO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegios`
--

CREATE TABLE `privilegios` (
  `id` int(11) NOT NULL,
  `nombre_privilegio` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `privilegios`
--

INSERT INTO `privilegios` (`id`, `nombre_privilegio`, `descripcion`) VALUES
(1, 'admin', 'Acceso total al sistema'),
(2, 'usuario', 'Acceso parcial al sistema'),
(3, 'developer', 'Acceso total al sistema');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `fullname`, `password`, `email`) VALUES
(1, 'administrador', 'Administrador', '$2y$10$RYnF46mg1X3FrdqKUt7h8egD3hMGNxGb8FEcbv7MEJ62.WEeyhi2C', 'administrador@admin.com'),
(2, 'gfloresr', 'Gian Flores', '$2y$10$BvBgkxlBSW2mL66FgVrfOeG..fmsEPx6Ify7nPwgib4glHDcHViLy', 'gfloresr@pj.gob.pe'),
(3, 'admin', 'Admin', '$2y$10$vj5M3DyAKH//rxlcHNJ5euJJa9I8q5TGvWPN0bafCdZbRRt9UiyGG', 'admin@admin.com'),
(4, 'demo', 'Demo', '$2y$10$TyE4zAVROTn2SbYK5gr00uQOLx45s4YVSINRRdzRwbk9fAy8KUNlm', 'demo@demo'),
(8, 'yquispech', 'Yeins Danais', '$2y$10$K.bJ1hYTa97Zr0ZXqCnqtemntlLYHf3C3pRZdU5jtufeSdEkA7.Fy', 'yquispech@pj.gob.pe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_privilegios`
--

CREATE TABLE `usuario_privilegios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `privilegio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario_privilegios`
--

INSERT INTO `usuario_privilegios` (`id`, `usuario_id`, `privilegio_id`) VALUES
(1, 2, 1),
(2, 1, 1),
(3, 3, 1),
(4, 4, 2),
(5, 8, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `c_ingresos`
--
ALTER TABLE `c_ingresos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nro_cedula` (`nro_cedula`),
  ADD KEY `idx_nro_cedula` (`nro_cedula`),
  ADD KEY `idx_id_usuario` (`id_usuario`),
  ADD KEY `idx_cedula` (`cedula`),
  ADD KEY `idx_fecha_recep` (`fecha_recep`);

--
-- Indices de la tabla `c_recepcion`
--
ALTER TABLE `c_recepcion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nro_cedula` (`nro_cedula`),
  ADD KEY `idx_nro_cedula` (`nro_cedula`),
  ADD KEY `idx_id_usuario` (`id_usuario`),
  ADD KEY `idx_cedula` (`cedula`),
  ADD KEY `idx_fecha_devolucion` (`fecha_devolucion`);

--
-- Indices de la tabla `m_motivos`
--
ALTER TABLE `m_motivos`
  ADD PRIMARY KEY (`m_id`);

--
-- Indices de la tabla `privilegios`
--
ALTER TABLE `privilegios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_privilegio` (`nombre_privilegio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_privilegios`
--
ALTER TABLE `usuario_privilegios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `privilegio_id` (`privilegio_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `c_ingresos`
--
ALTER TABLE `c_ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `c_recepcion`
--
ALTER TABLE `c_recepcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `m_motivos`
--
ALTER TABLE `m_motivos`
  MODIFY `m_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `privilegios`
--
ALTER TABLE `privilegios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario_privilegios`
--
ALTER TABLE `usuario_privilegios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario_privilegios`
--
ALTER TABLE `usuario_privilegios`
  ADD CONSTRAINT `usuario_privilegios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_privilegios_ibfk_2` FOREIGN KEY (`privilegio_id`) REFERENCES `privilegios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
