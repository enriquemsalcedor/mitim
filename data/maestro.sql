-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-08-2017 a las 18:19:27
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lnj2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro`
--

CREATE TABLE `maestro` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `padre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `maestro`
--

INSERT INTO `maestro` (`id`, `nombre`, `descripcion`, `tipo`, `padre`) VALUES
(1, 'Sector A', '', 'Sectores', 0),
(2, 'Sector B', '', 'Sectores', 0),
(3, 'Sector C', '', 'Sectores', 0),
(4, 'Sector D', '', 'Sectores', 0),
(5, 'Sector E', '', 'Sectores', 0),
(6, 'Otros', '', 'Sectores', 0),
(7, 'Administración', '', 'Subsectores', 0),
(8, 'Administración 2', '', 'Subsectores', 0),
(9, 'Apoyo Norte', '', 'Subsectores', 0),
(10, 'Apoyo Sur', '', 'Subsectores', 0),
(11, 'Apoyo Sur - Administración 1', '', 'Subsectores', 0),
(12, 'Custodio 3', '', 'Subsectores', 0),
(13, 'Custodio 1', '', 'Subsectores', 0),
(14, 'Pabellón Norte', '', 'Subsectores', 0),
(15, 'Pabellón Sur', '', 'Subsectores', 0),
(16, 'Saneamiento', '', 'Subsectores', 0),
(17, 'Zona Norte', '', 'Subsectores', 0),
(18, 'Zona Sur', '', 'Subsectores', 0),
(19, 'Otros', '', 'Subsectores', 0),
(20, 'Servicio - 01: Operación de Equipos Especiales', '', 'Servicios', 0),
(21, 'Servicio - 02: Servicio de Lavandería', '', 'Servicios', 0),
(22, 'Servicio - 03: Mantenimiento de Activos (Preventivos & Correctivos)', '', 'Servicios', 0),
(23, 'Servicio - 04: Gerencia y Coordinación General', '', 'Servicios', 0),
(24, 'Limpieza, Aseo y Control de Plagas', '', 'Sistemas', 0),
(25, 'Mantenimiento de Infraestructura Edificaciones', '', 'Sistemas', 0),
(26, 'Mantenimiento de Infraestructura Urbanismo', '', 'Sistemas', 0),
(27, 'Servicio de Lavandería', '', 'Sistemas', 0),
(28, 'Sistema Administrativo', '', 'Sistemas', 0),
(29, 'Sistema de Agua Potable', '', 'Sistemas', 0),
(30, 'Sistema de Aires Acondicionados ', '', 'Sistemas', 0),
(31, 'Sistema de Saneamiento', '', 'Sistemas', 0),
(32, 'Sistema de Suplencia', '', 'Sistemas', 0),
(33, 'Sistemas de Vapor y Gas', '', 'Sistemas', 0),
(34, 'Registrado', ' ', 'Estados', 0),
(35, 'Creada', ' ', 'Estados', 0),
(36, 'Finalizada', ' ', 'Estados', 0),
(37, 'Por Firmar', ' ', 'Estados', 0),
(38, 'Parcial', ' ', 'Estados', 0),
(39, 'Cancelada', ' ', 'Estados', 0),
(40, 'Módulo 02', '', 'Subsector', 0),
(41, 'Estación Elevadora 2', '', 'Subsector', 0),
(42, 'Calderas', '', 'Subsector', 0),
(43, 'Zonas Comunes', '', 'Subsector', 0),
(44, 'Clasificación', '', 'Subsector', 0),
(45, 'PTAT', '', 'Subsector', 0),
(46, 'Portal Principal', '', 'Subsector', 0),
(47, 'Visita Conyugal', '', 'Subsector', 0),
(48, 'PTAR', '', 'Subsector', 0),
(49, 'Cocina', '', 'Subsector', 0),
(50, 'Mantenimiento', '', 'Subsector', 0),
(51, 'Bocatoma', '', 'Subsector', 0),
(52, 'Estación Elevadora 1', '', 'Subsector', 0),
(53, 'Lavandería', '', 'Subsector', 0),
(54, 'Visita Familiar', '', 'Subsector', 0),
(55, 'Custodio2', '', 'Subsector', 0),
(56, 'Mínima 1', '', 'Subsector', 0),
(57, 'Educativa', '', 'Subsector', 0),
(58, 'Patio 2 Mediana 4 Celda 45', '', 'Subsector', 0),
(59, 'Pabellón 4 Patio, 2 Celda 45', '', 'Subsector', 0),
(60, 'Oficinas Del Sector D', '', 'Subsector', 0),
(61, 'Cocina B', '', 'Subsector', 0),
(62, 'Extrema 2-3', '', 'Subsector', 0),
(63, 'Custodio', '', 'Sectores', 0),
(64, 'PTAR', '', 'Sectores', 0),
(65, 'Administración', '', 'Sectores', 0),
(66, 'PTAP', '', 'Sectores', 0),
(67, 'Bocatoma', '', 'Sectores', 0),
(68, 'Custodio 2', '', 'Subsectores', 0),
(69, 'Mediana 1', '', 'Subsectores', 0),
(70, 'Mediana 2', '', 'Subsectores', 0),
(71, 'Mediana 3', '', 'Subsectores', 0),
(72, 'Mediana 4', '', 'Subsectores', 0),
(73, 'Mediana 5', '', 'Subsectores', 0),
(74, 'Mediana 6', '', 'Subsectores', 0),
(75, 'Máxima 1 ', '', 'Subsectores', 0),
(76, 'Máxima 2', '', 'Subsectores', 0),
(77, 'Extrema 1', '', 'Subsectores', 0),
(78, 'Extrema 2', '', 'Subsectores', 0),
(79, 'Extrema 3', '', 'Subsectores', 0),
(80, 'Minima 1', '', 'Subsectores', 0),
(81, 'Minima 2', '', 'Subsectores', 0),
(82, 'Salud norte', '', 'Subsectores', 0),
(83, 'Salud sur ', '', 'Subsectores', 0),
(84, 'Visitas norte', '', 'Subsectores', 0),
(85, 'Visita sur', '', 'Subsectores', 0),
(86, 'Comedor norte', '', 'Subsectores', 0),
(87, 'Comedor sur ', '', 'Subsectores', 0),
(88, 'cocina', '', 'Subsectores', 0),
(89, 'Educativa norte', '', 'Subsectores', 0),
(90, 'Educativa sur ', '', 'Subsectores', 0),
(91, 'Deportes', '', 'Subsectores', 0),
(92, 'Taller de producción', '', 'Subsectores', 0),
(93, 'Subestación ', '', 'Subsectores', 0),
(94, 'Plantas eléctrica', '', 'Subsectores', 0),
(95, 'clasificación', '', 'Subsectores', 0),
(96, 'Atención a visitas', '', 'Subsectores', 0),
(97, 'CANINOS', '', 'Subsectores', 0),
(98, 'Bodega', '', 'Subsectores', 0),
(99, 'Portal principal', '', 'Subsectores', 0),
(100, 'portal', '', 'Subsectores', 0),
(101, 'Torre 1', '', 'Subsectores', 0),
(102, 'Torre 2', '', 'Subsectores', 0),
(103, 'Torre 3', '', 'Subsectores', 0),
(104, 'Torre 4', '', 'Subsectores', 0),
(105, 'Torre 5', '', 'Subsectores', 0),
(106, 'Torre 6', '', 'Subsectores', 0),
(107, 'Torre 7', '', 'Subsectores', 0),
(108, 'Torre 8', '', 'Subsectores', 0),
(109, 'Bombas ', '', 'Subsectores', 0),
(110, 'Blower', '', 'Subsectores', 0),
(111, 'Estación elevadora 1', '', 'Subsectores', 0),
(112, 'Estación elevadora 2', '', 'Subsectores', 0),
(113, 'Lavandería ', '', 'Subsectores', 0),
(114, 'Caldera', '', 'Subsectores', 0),
(115, 'Tanque 1', '', 'Subsectores', 0),
(116, 'Tanque 2', '', 'Subsectores', 0),
(117, 'Recolección basura ', '', 'Sistemas', 0),
(118, 'Prelibertad 1', '', 'Subsectores', 0),
(119, 'Prelibertad 2', '', 'Subsectores', 0),
(120, 'Mano de Obra', '', 'Costos', 0),
(121, 'Materiales', '', 'Costos', 0),
(122, 'Equipos', '', 'Costos', 0),
(123, 'Activo 1', '', 'Activos', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `maestro`
--
ALTER TABLE `maestro`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `maestro`
--
ALTER TABLE `maestro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
